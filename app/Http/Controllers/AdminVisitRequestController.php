<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitRequest;
use Illuminate\Support\Facades\Auth;

class AdminVisitRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = VisitRequest::with('user');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengunjung', 'like', "%{$search}%")
                  ->orWhere('kode_kunjungan', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $visits = $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->paginate(10)->withQueryString();

        // Hitung count per status
        $counts = [
            'all'      => VisitRequest::count(),
            'pending'  => VisitRequest::where('status', 'pending')->count(),
            'approved' => VisitRequest::where('status', 'approved')->count(),
            'rejected' => VisitRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.kegiatan', compact('visits', 'counts'));
    }
    public function storeInternal(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tujuan_kegiatan' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_pengunjung' => 'required|integer|min:1',
            'routine_days' => 'required_with:is_routine|array|min:1',
            'routine_end_date' => 'nullable|date|after_or_equal:tanggal_kunjungan|before:+2 years',
        ]);

        $kode = 'INT' . random_int(1000000000, 9999999999);
        $isRoutine = $request->has('is_routine');
        $routineDays = $isRoutine && $request->routine_days ? implode(',', $request->routine_days) : null;

        VisitRequest::create([
            'user_id' => Auth::id(),
            'kode_kunjungan' => $kode,
            'nama_pengunjung' => $request->nama_kegiatan,
            'jumlah_pengunjung' => $request->jumlah_pengunjung,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tujuan_kunjungan' => $request->tujuan_kegiatan,
            'status' => 'approved',
            'is_routine' => $isRoutine,
            'routine_days' => $routineDays,
            'routine_end_date' => $isRoutine ? $request->routine_end_date : null,
        ]);

        return back()->with('success', "Kegiatan internal '{$request->nama_kegiatan}' berhasil ditambahkan.");
    }

    public function approve(VisitRequest $visit)
    {
        $visit->update(['status' => 'approved']);
        return back()->with('success', "Kunjungan {$visit->kode_kunjungan} telah disetujui.");
    }

    public function reject(VisitRequest $visit)
    {
        $visit->update(['status' => 'rejected']);
        return back()->with('success', "Kunjungan {$visit->kode_kunjungan} telah ditolak.");
    }
}
