<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

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
    'urgent'     => [],
    'almost_done' => [],
    'popular'     => [],
];

foreach ($allPins as $pin) {
    if (isset($pinnedCampaignsData[$pin->campaign_id])) {
        $pinnedCampaignsByPreset[$pin->preset][$pin->slot_number] = $pinnedCampaignsData[$pin->campaign_id];
    }
}

echo "=== DEFAULT ===\n";
foreach ($pinnedCampaignsByPreset['default'] as $slot => $c) {
    echo "Slot $slot: {$c->title} (ID: {$c->id})\n";
}

echo "=== ALMOST_DONE ===\n";
foreach ($pinnedCampaignsByPreset['almost_done'] as $slot => $c) {
    echo "Slot $slot: {$c->title} (ID: {$c->id})\n";
}
