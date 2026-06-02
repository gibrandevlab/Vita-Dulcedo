<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kode_kunjungan', 13)->unique();
            $table->string('nama_pengunjung');
            $table->unsignedInteger('jumlah_pengunjung')->default(1);
            $table->date('tanggal_kunjungan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('tujuan_kunjungan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_requests');
    }
};
