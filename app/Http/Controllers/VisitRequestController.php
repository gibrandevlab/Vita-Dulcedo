<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisitRequestController extends Controller
{
    /**
     * Tampilkan halaman kegiatan + kalender + form request kunjungan.
     */
    public function index(Request $request)
    {
        // Ambil semua kunjungan yang approved untuk data kalender
        // Format: ['YYYY-MM-DD' => [list of visits]]
        $approvedVisits = VisitRequest::approved()
            ->select('id', 'tanggal_kunjungan', 'jam_mulai', 'jam_selesai', 'tujuan_kunjungan', 'nama_pengunjung', 'jumlah_pengunjung', 'is_routine', 'routine_days', 'routine_end_date')
            ->orderBy('tanggal_kunjungan')
            ->orderBy('jam_mulai')
            ->get();

        // Kelompokkan by tanggal untuk kalender
        $calendarData = [];
        foreach ($approvedVisits as $visit) {
            $eventData = [
                'jam_mulai'     => substr($visit->jam_mulai, 0, 5),
                'jam_selesai'   => substr($visit->jam_selesai, 0, 5),
                'tujuan'        => $visit->tujuan_kunjungan,
                'nama'          => $visit->nama_pengunjung,
                'jumlah'        => $visit->jumlah_pengunjung,
            ];

            if ($visit->is_routine && $visit->routine_days && $visit->routine_end_date) {
                // Jika kegiatan rutin, ulangi di hari-hari yang dipilih sampai tanggal berakhir
                $startDate = Carbon::parse($visit->tanggal_kunjungan);
                $endDate = Carbon::parse($visit->routine_end_date);
                $routineDays = explode(',', $visit->routine_days);

                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    // dayOfWeekIso: 1 = Senin, 7 = Minggu
                    if (in_array((string)$date->dayOfWeekIso, $routineDays)) {
                        $key = $date->format('Y-m-d');
                        if (!isset($calendarData[$key])) {
                            $calendarData[$key] = [];
                        }
                        $calendarData[$key][] = $eventData;
                    }
                }
            } else {
                // Kegiatan biasa (sekali jalan)
                $key = $visit->tanggal_kunjungan->format('Y-m-d');
                if (!isset($calendarData[$key])) {
                    $calendarData[$key] = [];
                }
                $calendarData[$key][] = $eventData;
            }
        }

        // Riwayat kunjungan yang disetujui (paginate 5)
        $riwayatKunjungan = VisitRequest::approved()
            ->select('id', 'tanggal_kunjungan', 'jam_mulai', 'jam_selesai', 'tujuan_kunjungan', 'jumlah_pengunjung')
            ->orderByDesc('tanggal_kunjungan')
            ->paginate(5);

        // Cek status kunjungan by kode
        $searchResult = null;
        $searchQuery  = null;
        if ($request->filled('kode')) {
            $searchQuery  = strtoupper(trim($request->kode));
            $searchResult = VisitRequest::where('kode_kunjungan', $searchQuery)->first();
        }

        return view('kegiatan', [
            'calendarData'     => json_encode($calendarData),
            'riwayatKunjungan' => $riwayatKunjungan,
            'searchResult'     => $searchResult,
            'searchQuery'      => $searchQuery,
        ]);
    }

    /**
     * Proses submit request kunjungan (wajib login).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengunjung'   => 'required|string|max:255',
            'jumlah_pengunjung' => 'required|integer|min:1|max:100',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i|after:jam_mulai',
            'tujuan_kunjungan'  => 'required|string|max:1000',
        ], [
            'nama_pengunjung.required'   => 'Nama pengunjung wajib diisi.',
            'jumlah_pengunjung.required' => 'Jumlah pengunjung wajib diisi.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.after_or_equal' => 'Tanggal kunjungan tidak boleh di masa lampau.',
            'jam_mulai.required'         => 'Jam mulai wajib diisi.',
            'jam_selesai.required'       => 'Jam selesai wajib diisi.',
            'jam_selesai.after'          => 'Jam selesai harus setelah jam mulai.',
            'tujuan_kunjungan.required'  => 'Tujuan kunjungan wajib diisi.',
        ]);

        // Generate kode kunjungan unik: 3 huruf besar + 10 angka
        $letters = '';
        for ($i = 0; $i < 3; $i++) {
            $letters .= chr(random_int(65, 90));
        }
        $digits = '';
        for ($i = 0; $i < 10; $i++) {
            $digits .= random_int(0, 9);
        }
        $kodeKunjungan = $letters . $digits;

        VisitRequest::create([
            'user_id'           => Auth::id(),
            'kode_kunjungan'    => $kodeKunjungan,
            'nama_pengunjung'   => $request->nama_pengunjung,
            'jumlah_pengunjung' => $request->jumlah_pengunjung,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'tujuan_kunjungan'  => $request->tujuan_kunjungan,
            'status'            => 'pending',
        ]);

        // Format tanggal untuk pesan WA
        $tgl = Carbon::parse($request->tanggal_kunjungan)->translatedFormat('d F Y');

        // Buat pesan WhatsApp
        $message = "Halo Panti Asuhan Vita Dulcedo,\n\n"
                 . "Saya ingin mengajukan permohonan kunjungan dengan rincian berikut:\n"
                 . "• KODE SPESIAL: {$kodeKunjungan}\n"
                 . "• Nama Pengunjung: {$request->nama_pengunjung}\n"
                 . "• Jumlah Pengunjung: {$request->jumlah_pengunjung} orang\n"
                 . "• Tanggal: {$tgl}\n"
                 . "• Waktu: {$request->jam_mulai} – {$request->jam_selesai}\n"
                 . "• Tujuan: {$request->tujuan_kunjungan}\n\n"
                 . "Mohon bantuannya untuk konfirmasi permohonan kunjungan saya. Terima kasih!";

        $waUrl = "https://api.whatsapp.com/send?phone=6285814701149&text=" . urlencode($message);

        return redirect()->route('kegiatan')->with([
            'success'        => 'Permohonan kunjungan Anda telah dikirim dan sedang menunggu persetujuan admin.',
            'kode_kunjungan' => $kodeKunjungan,
            'wa_url'         => $waUrl,
        ]);
    }
}
