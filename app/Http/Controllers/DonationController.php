<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Tampilkan halaman donasi dengan data agregat.
     */
    public function index(Request $request)
    {
        // Total donasi keseluruhan (hanya yang approved)
        $totalDonasi = Donation::approved()->sum('jumlah_donasi');

        // Riwayat agregat harian (hanya approved) — semua waktu, pagination 5
        $riwayatHarian = Donation::approved()
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(jumlah_donasi) as total_harian'),
                DB::raw('COUNT(*) as jumlah_donatur')
            )
            ->groupBy('tanggal')
            ->orderByDesc('tanggal')
            ->paginate(5);

        // Logika Cek Status Donasi Berdasarkan Kode Unik
        $searchResult = null;
        $searchQuery = null;
        if ($request->filled('kode')) {
            $searchQuery = strtoupper(trim($request->kode));
            $searchResult = Donation::where('kode_donasi', $searchQuery)->first();
        }

        // Ambil data program kebutuhan yang aktif — pagination 10 per halaman
        $campaigns = \App\Models\Campaign::where('status', 'active')->latest()->paginate(10);

        return view('donasi', [
            'totalDonasi'   => $totalDonasi,
            'riwayatHarian' => $riwayatHarian,
            'searchResult'  => $searchResult,
            'searchQuery'   => $searchQuery,
            'campaigns'     => $campaigns,
        ]);
    }

    /**
     * Proses submit konfirmasi donasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'jumlah_donasi' => 'required|numeric|min:1000',
            'nama_lengkap' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'pesan' => 'nullable|string|max:1000',
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'jumlah_donasi.required' => 'Jumlah donasi wajib diisi.',
            'jumlah_donasi.min' => 'Minimal donasi Rp 1.000.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'bukti_transfer.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_transfer.image' => 'File harus berupa gambar.',
            'bukti_transfer.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Simpan file bukti transfer
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        // Generate kode donasi unik: 3 huruf besar + 10 angka
        $letters = '';
        for ($i = 0; $i < 3; $i++) {
            $letters .= chr(random_int(65, 90)); // A-Z
        }
        $digits = '';
        for ($i = 0; $i < 10; $i++) {
            $digits .= random_int(0, 9);
        }
        $kodeDonasi = $letters . $digits;

        Donation::create([
            'user_id' => Auth::id(),
            'campaign_id' => $request->campaign_id,
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_telepon' => $request->nomor_telepon,
            'kode_donasi' => $kodeDonasi,
            'jumlah_donasi' => $request->jumlah_donasi,
            'pesan' => $request->pesan,
            'bukti_transfer' => $path,
            'status' => 'pending',
        ]);

        // Buat format pesan WhatsApp
        $message = "Halo Panti Asuhan Vita Dulcedo,\n\n"
                 . "Saya telah melakukan donasi melalui website dengan rincian berikut:\n"
                 . "• KODE SPESIAL: {$kodeDonasi}\n"
                 . "• Nama Donatur: {$request->nama_lengkap}\n"
                 . "• No. Telepon: {$request->nomor_telepon}\n"
                 . "• Nominal Donasi: Rp " . number_format($request->jumlah_donasi, 0, ',', '.') . "\n"
                 . "• Pesan: " . ($request->pesan ?: '-') . "\n\n"
                 . "Mohon bantuannya untuk melakukan konfirmasi/verifikasi donasi saya. Terima kasih!";

        $waUrl = "https://api.whatsapp.com/send?phone=6285814701149&text=" . urlencode($message);

        return redirect()->route('donasi')->with([
            'success' => 'Terima kasih! Konfirmasi donasi Anda telah kami terima dan sedang menunggu verifikasi admin.',
            'kode_donasi' => $kodeDonasi,
            'wa_url' => $waUrl
        ]);
    }
}
