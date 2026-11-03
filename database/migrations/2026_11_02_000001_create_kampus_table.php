<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Institusi perguruan tinggi (identitas, sumber PDDikti/referensi resmi).
 * Satu institusi BISA punya banyak lokasi fisik (lihat tabel kampus_lokasi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampus', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 80)->unique();
            $table->string('nama_resmi', 200);
            $table->string('singkatan', 60)->nullable();
            $table->enum('status', ['Negeri', 'Swasta']);
            $table->string('jenis', 40); // Universitas | Institut | Sekolah Tinggi | Politeknik | Akademi
            $table->boolean('aktif')->default(true);
            $table->string('alamat', 500)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kab_kota', 80); // Palembang | Ogan Ilir
            $table->string('provinsi', 80)->default('Sumatera Selatan');
            $table->string('situs_resmi', 300)->nullable();
            $table->json('sumber_data')->nullable(); // [{url, tanggal}]
            $table->date('tanggal_cek')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['kab_kota', 'status']);
            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kampus');
    }
};
