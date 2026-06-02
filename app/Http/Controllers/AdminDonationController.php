<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

class AdminDonationController extends Controller
{
    /**
     * Tampilkan daftar donasi untuk admin dengan filter & pagination.
     */
    public function index(Request $request)
    {
        // Pastikan hanya admin yang bisa akses
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk pihak panti.');
        }

        $query = Donation::orderBy('created_at', 'desc');

        // Filter pencarian (nama donatur / kode donasi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('kode_donasi', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Filter rentang tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Hitung total per status (sebelum filter status, tapi dengan filter search & tanggal)
        $queryForCount = Donation::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $queryForCount->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('kode_donasi', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        }
        if ($request->filled('tanggal_dari')) {
            $queryForCount->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $queryForCount->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $counts = [
            'all'      => (clone $queryForCount)->count(),
            'pending'  => (clone $queryForCount)->where('status', 'pending')->count(),
            'approved' => (clone $queryForCount)->where('status', 'approved')->count(),
            'rejected' => (clone $queryForCount)->where('status', 'rejected')->count(),
        ];

        $donations = $query->paginate(10)->withQueryString();

        return view('admin.donasi', compact('donations', 'counts'));
    }

    /**
     * Setujui donasi.
     */
    public function approve(Donation $donation)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($donation->status !== 'approved') {
            $donation->update(['status' => 'approved']);

            // Sinkronisasi dengan campaign jika ada
            if ($donation->campaign_id) {
                $campaign = $donation->campaign;
                if ($campaign) {
                    $campaign->collected_amount += $donation->jumlah_donasi;
                    
                    // Cek apakah target sudah tercapai
                    if ($campaign->target_amount > 0 && $campaign->collected_amount >= $campaign->target_amount) {
                        $campaign->status = 'completed';
                    }
                    
                    $campaign->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Donasi dengan kode ' . $donation->kode_donasi . ' berhasil terverifikasi!');
    }

    /**
     * Tolak donasi.
     */
    public function reject(Donation $donation)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $donation->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Donasi dengan kode ' . $donation->kode_donasi . ' telah ditolak.');
    }
}
