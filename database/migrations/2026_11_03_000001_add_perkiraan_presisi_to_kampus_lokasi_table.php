<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah nilai 'perkiraan' pada kolom presisi kampus_lokasi untuk koordinat
 * hasil geocoding alamat yang belum bisa diverifikasi sebagai titik peta.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('kampus_lokasi')) {
            return;
        }

        Schema::table('kampus_lokasi', function (Blueprint $table) {
            $table->enum('presisi', ['gedung', 'kawasan', 'perkiraan'])
                ->default('kawasan')
                ->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('kampus_lokasi')) {
            return;
        }

        Schema::table('kampus_lokasi', function (Blueprint $table) {
            $table->enum('presisi', ['gedung', 'kawasan'])
                ->default('kawasan')
                ->change();
        });
    }
};
