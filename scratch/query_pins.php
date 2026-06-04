<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$pins = Illuminate\Support\Facades\DB::table('spk_pins')->get();
foreach ($pins as $pin) {
    echo "Preset: {$pin->preset} | Slot: {$pin->slot_number} | Campaign ID: {$pin->campaign_id}\n";
}
