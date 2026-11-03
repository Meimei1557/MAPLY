<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Penyesuaian skema untuk data hasil pengumpulan (Palembang & Indralaya):
 *  - tipe  : tambah nilai 'Belum diketahui' (banyak sumber tidak mencantumkan putra/putri/campur,
 *            dan nilainya tidak boleh ditebak).
 *  - catatan_data : daftar kolom yang masih kosong/perlu diverifikasi (diisi saat impor).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->enum('tipe', ['Putra', 'Putri', 'Campur', 'Belum diketahui'])
                ->default('Belum diketahui')
                ->change();
            $table->text('catatan_data')->nullable()->after('status_data');
        });
    }

    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropColumn('catatan_data');
        });
        // Nilai 'Belum diketahui' sengaja tidak dihapus dari enum agar data yang ada tidak rusak.
    }
};
