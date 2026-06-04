<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use App\Services\SpkService;

class AdminSpkController extends Controller
{
    /**
     * Tampilkan halaman kelola pinning & pembobotan SPK.
     */
    public function index()
    {
        // Ambil semua campaign aktif
        $campaigns = Campaign::where('status', 'active')
            ->orderBy('title', 'asc')
            ->get();

        // Ambil campaign yang saat ini di-pin per preset
        $pinnedRecords = DB::table('spk_pins')
            ->join('campaigns', 'spk_pins.campaign_id', '=', 'campaigns.id')
            ->where('campaigns.status', 'active')
            ->select('spk_pins.preset', 'spk_pins.slot_number', 'spk_pins.campaign_id as campaign_id', 'campaigns.*')
            ->get();

        $pinnedCampaigns = [];
        foreach (['default', 'urgent', 'almost_done', 'popular'] as $p) {
            $pinnedCampaigns[$p] = [];
        }

        foreach ($pinnedRecords as $record) {
            $campaign = new Campaign((array) $record);
            $campaign->exists = true;
            $campaign->id = $record->campaign_id;
            
            $pinnedCampaigns[$record->preset][$record->slot_number] = $campaign;
        }

        // Ambil bobot dari database, fallback ke konstanta default
        $spkService = new SpkService();
        $presets = SpkService::getPresets();
        $weights = [];
        foreach (array_keys($presets) as $preset) {
            $w = $spkService->getPresetWeights($preset);
            $weights[$preset] = [
                'c1' => $w[0],
                'c2' => $w[1],
                'c3' => $w[2],
                'c4' => $w[3],
                'c5' => $w[4],
            ];
        }

        return view('admin.spk_pinning', [
            'campaigns'        => $campaigns,
            'pinnedCampaigns'  => $pinnedCampaigns,
            'presets'          => $presets,
            'weights'          => $weights,
            'criteriaInfo'     => SpkService::getCriteriaInfo(),
            'rankingPreview'   => null,
            'previewPreset'    => null,
        ]);
    }

    /**
     * Endpoint AJAX: Hitung ranking preview berdasarkan bobot yang dikirim.
     */
    public function hitung(Request $request)
    {
        $request->validate([
            'preset'    => 'required|string|in:default,urgent,almost_done,popular',
            'weights'   => 'required|array',
            'weights.c1' => 'required|numeric|min:0|max:1',
            'weights.c2' => 'required|numeric|min:0|max:1',
            'weights.c3' => 'required|numeric|min:0|max:1',
            'weights.c4' => 'required|numeric|min:0|max:1',
            'weights.c5' => 'required|numeric|min:0|max:1',
        ]);

        $preset = $request->input('preset');
        $customWeights = [
            (float) $request->input('weights.c1'),
            (float) $request->input('weights.c2'),
            (float) $request->input('weights.c3'),
            (float) $request->input('weights.c4'),
            (float) $request->input('weights.c5'),
        ];

        // Validasi total bobot = 1.0 (toleransi ±0.01)
        $totalWeight = array_sum($customWeights);
        if (abs($totalWeight - 1.0) > 0.01) {
            return response()->json([
                'error' => 'Total bobot harus 1.00 (100%). Saat ini: ' . number_format($totalWeight, 2),
            ], 422);
        }

        // Ambil semua campaign aktif
        $campaigns = Campaign::where('status', 'active')
            ->where('target_amount', '>', 0)
            ->with(['donations' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        if ($campaigns->count() === 0) {
            return response()->json([
                'ranking' => [],
                'message' => 'Tidak ada campaign aktif untuk dihitung.',
            ]);
        }

        // Hitung ranking menggunakan SpkService dengan bobot kustom
        // Sementara simpan bobot kustom ke database agar SpkService bisa membacanya
        // Atau kita bisa langsung modifikasi calculate() — tapi lebih simpel kita
        // simpan sementara di database lalu revert? Tidak. 
        // Lebih baik kita panggil calculate langsung dan override bobot secara internal.
        // Karena SpkService membaca dari DB, kita perlu approach berbeda.
        // Solusi: gunakan calculateWithWeights() custom method.
        
        $spkService = new SpkService();
        $result = $spkService->calculateWithCustomWeights($campaigns, $customWeights);

        // Format output untuk frontend
        $ranking = [];
        $top15 = array_slice($result['ranking'], 0, 15);
        foreach ($top15 as $idx => $item) {
            $c = $item['campaign'];
            $ranking[] = [
                'rank'             => $idx + 1,
                'id'               => $c->id,
                'title'            => $c->title,
                'topsis_score'     => round($item['topsis_score'], 4),
                'saw_score'        => round($item['saw_score'], 4),
                'final_score'      => round($item['final_score'], 4),
                'collected_amount' => $c->collected_amount,
                'target_amount'    => $c->target_amount,
                'percentage'       => $c->target_amount > 0 ? min(100, round(($c->collected_amount / $c->target_amount) * 100)) : 0,
            ];
        }

        return response()->json([
            'ranking' => $ranking,
            'weights' => $customWeights,
            'preset'  => $preset,
        ]);
    }

    /**
     * Simpan konfigurasi pembobotan + pinning rekomendasi slot.
     */
    public function update(Request $request)
    {
        $request->validate([
            'slots'   => 'nullable|array',
            'slots.*' => 'nullable|array',
            'weights' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // 1. Simpan bobot per preset
            if ($request->filled('weights')) {
                foreach ($request->input('weights') as $preset => $w) {
                    $validPresets = ['default', 'urgent', 'almost_done', 'popular'];
                    if (!in_array($preset, $validPresets)) continue;

                    $c1 = (float) ($w['c1'] ?? 0);
                    $c2 = (float) ($w['c2'] ?? 0);
                    $c3 = (float) ($w['c3'] ?? 0);
                    $c4 = (float) ($w['c4'] ?? 0);
                    $c5 = (float) ($w['c5'] ?? 0);

                    DB::table('spk_weights')->updateOrInsert(
                        ['preset' => $preset],
                        [
                            'c1' => $c1,
                            'c2' => $c2,
                            'c3' => $c3,
                            'c4' => $c4,
                            'c5' => $c5,
                            'updated_at' => now(),
                            'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                        ]
                    );
                }
            }

            // 2. Simpan pin_rank per preset ke tabel spk_pins
            if ($request->has('slots')) {
                $slotsInput = $request->input('slots');

                foreach (['default', 'urgent', 'almost_done', 'popular'] as $preset) {
                    // Hapus data pin lama untuk preset ini
                    DB::table('spk_pins')->where('preset', $preset)->delete();

                    if (isset($slotsInput[$preset]) && is_array($slotsInput[$preset])) {
                        foreach ($slotsInput[$preset] as $slotIndex => $campaignId) {
                            $slotIndex = (int) $slotIndex;
                            if ($campaignId && $slotIndex >= 1 && $slotIndex <= 15) {
                                DB::table('spk_pins')->insert([
                                    'preset' => $preset,
                                    'slot_number' => $slotIndex,
                                    'campaign_id' => $campaignId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.spk.pinning')->with('success', 'Konfigurasi pembobotan & slot rekomendasi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.spk.pinning')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
