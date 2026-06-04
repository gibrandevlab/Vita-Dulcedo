<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$pins = Illuminate\Support\Facades\DB::table('spk_pins')->where('preset', 'almost_done')->get();
foreach ($pins as $pin) {
    $campaign = App\Models\Campaign::find($pin->campaign_id);
    if ($campaign) {
        echo "Campaign ID: {$campaign->id} | Title: {$campaign->title} | Status: {$campaign->status} | Target: {$campaign->target_amount}\n";
    } else {
        echo "Campaign ID: {$pin->campaign_id} NOT found!\n";
    }
}
