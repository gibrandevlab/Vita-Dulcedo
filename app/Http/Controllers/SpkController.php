<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Services\SpkService;

class SpkController extends Controller
{
    /**
     * Tampilkan halaman rekomendasi campaign berdasarkan SPK TOPSIS & SAW.
     */
    public function index(Request $request)
    {
        $preset = $request->input('preset', 'default');

        // Validasi preset
        $validPresets = array_keys(SpkService::getPresets());
        if (!in_array($preset, $validPresets)) {
            $preset = 'default';
        }

        // Ambil semua campaign aktif dengan relasi donasi yang approved
        $campaigns = Campaign::where('status', 'active')
            ->where('target_amount', '>', 0)
            ->with(['donations' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        $result = null;
        if ($campaigns->count() > 0) {
            $spkService = new SpkService();
            $result = $spkService->calculate($campaigns, $preset);
        }

        return view('spk.index', [
            'result' => $result,
            'presets' => SpkService::getPresets(),
            'activePreset' => $preset,
            'campaignCount' => $campaigns->count(),
        ]);
    }
}
