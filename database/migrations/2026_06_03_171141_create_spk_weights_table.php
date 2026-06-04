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
        Schema::create('spk_weights', function (Blueprint $table) {
            $table->id();
            $table->string('preset')->unique(); // default, urgent, almost_done, popular
            $table->double('c1');
            $table->double('c2');
            $table->double('c3');
            $table->double('c4');
            $table->double('c5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_weights');
    }
};
