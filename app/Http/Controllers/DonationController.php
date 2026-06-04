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

        // Ambil top 15 recommended campaigns untuk masing-masing preset untuk mode smart
        $allActiveCampaigns = \App\Models\Campaign::where('status', 'active')
            ->where('target_amount', '>', 0)
            ->with(['donations' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        // Ambil data pinning per preset dari database
        $allPins = \Illuminate\Support\Facades\DB::table('spk_pins')
            ->join('campaigns', 'spk_pins.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.status', 'active')
            ->select('spk_pins.preset', 'spk_pins.slot_number', 'spk_pins.campaign_id')
            ->get();

        $pinnedCampaignIds = $allPins->pluck('campaign_id')->unique()->toArray();
        $pinnedCampaignsData = \App\Models\Campaign::whereIn('id', $pinnedCampaignIds)
            ->with(['donations' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get()
            ->keyBy('id');

        $pinnedCampaignsByPreset = [
            'default'     => [],
            'urgent'      => [],
            'almost_done' => [],
            'popular'     => [],
        ];

        foreach ($allPins as $pin) {
            if (isset($pinnedCampaignsData[$pin->campaign_id])) {
                $pinnedCampaignsByPreset[$pin->preset][$pin->slot_number] = $pinnedCampaignsData[$pin->campaign_id];
            }
        }

        $smartData = [
            'default'     => [],
            'urgent'      => [],
            'almost_done' => [],
            'popular'     => [],
        ];

        if ($allActiveCampaigns->count() > 0) {
            $spkService = new \App\Services\SpkService();

            foreach (array_keys($smartData) as $presetKey) {
                $pinnedCampaigns = $pinnedCampaignsByPreset[$presetKey] ?? [];
                $pinnedIds = array_map(fn($c) => $c->id, $pinnedCampaigns);

                $spkResult = $spkService->calculate($allActiveCampaigns, $presetKey);

                // Filter ranking: hapus campaign yang sudah di-pin (hindari duplikat)
                $filteredRanking = array_filter($spkResult['ranking'], function ($item) use ($pinnedIds) {
                    return !in_array($item['campaign']->id, $pinnedIds);
                });
                $filteredRanking = array_values($filteredRanking);

                // Build merged 15 slots: pinned campaigns get priority positions
                $merged = [];
                $autoIndex = 0;

                for ($slot = 1; $slot <= 15; $slot++) {
                    if (isset($pinnedCampaigns[$slot])) {
                        // Slot ini dikunci oleh admin untuk preset ini
                        $c = $pinnedCampaigns[$slot];
                        $merged[] = $this->formatCampaignForSmart($c, $slot, true);
                    } elseif (isset($filteredRanking[$autoIndex])) {
                        // Isi otomatis dari ranking TOPSIS-SAW
                        $c = $filteredRanking[$autoIndex]['campaign'];
                        $merged[] = $this->formatCampaignForSmart($c, $slot, false);
                        $autoIndex++;
                    }
                }

                $smartData[$presetKey] = $merged;
            }
        }

        return view('donasi', [
            'totalDonasi'   => $totalDonasi,
            'riwayatHarian' => $riwayatHarian,
            'searchResult'  => $searchResult,
            'searchQuery'   => $searchQuery,
            'campaigns'     => $campaigns,
            'smartData'     => $smartData,
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

    /**
     * Format data campaign untuk mode smart.
     */
    private function formatCampaignForSmart($campaign, int $rank, bool $isPinned): array
    {
        $percentage = $campaign->target_amount > 0 
            ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100)) 
            : 0;

        return [
            'id' => $campaign->id,
            'title' => $campaign->title,
            'description' => $campaign->description,
            'image' => $campaign->image ? \Illuminate\Support\Facades\Storage::url($campaign->image) : null,
            'target_amount' => (float) $campaign->target_amount,
            'collected_amount' => (float) $campaign->collected_amount,
            'percentage' => $percentage,
            'deadline_human' => $campaign->deadline ? \Carbon\Carbon::parse($campaign->deadline)->diffForHumans() : null,
            'rank' => $rank,
            'is_pinned' => $isPinned,
        ];
    }
}

