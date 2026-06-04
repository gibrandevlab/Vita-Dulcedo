<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spk_pins', function (Blueprint $table) {
            $table->id();
            $table->string('preset'); // default, urgent, almost_done, popular
            $table->integer('slot_number'); // 1 to 15
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['preset', 'slot_number']);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('pin_rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->integer('pin_rank')->nullable();
        });

        Schema::dropIfExists('spk_pins');
    }
};
