<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Indeks pendukung pencarian/pemetaan kos (aman untuk data yang sudah ada). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            if (!Schema::hasIndex('kos', 'kos_koordinat_idx')) {
                $table->index(['latitude', 'longitude'], 'kos_koordinat_idx');
            }
            if (!Schema::hasIndex('kos', 'kos_status_data_idx')) {
                $table->index('status_data', 'kos_status_data_idx');
            }
            if (!Schema::hasIndex('kos', 'kos_kecamatan_idx')) {
                $table->index('kecamatan', 'kos_kecamatan_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            foreach (['kos_koordinat_idx', 'kos_status_data_idx', 'kos_kecamatan_idx'] as $idx) {
                if (Schema::hasIndex('kos', $idx)) {
                    $table->dropIndex($idx);
                }
            }
        });
    }
};
