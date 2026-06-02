<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            $table->boolean('is_routine')->default(false)->after('status');
            $table->string('routine_days')->nullable()->after('is_routine');
            $table->date('routine_end_date')->nullable()->after('routine_days');
        });
    }

    public function down(): void
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            $table->dropColumn(['is_routine', 'routine_days', 'routine_end_date']);
        });
    }
};
