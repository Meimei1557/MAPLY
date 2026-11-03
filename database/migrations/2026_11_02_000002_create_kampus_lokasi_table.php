<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lokasi fisik kampus (satu institusi dapat punya beberapa lokasi).
 * Koordinat di tabel ini adalah SATU-SATUNYA sumber untuk marker website
 * DAN tautan Google Maps agar keduanya selalu menunjuk titik yang sama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampus_lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampus_id')->constrained('kampus')->cascadeOnDelete();
            $table->string('kode', 100)->unique();
            $table->string('nama_lokasi', 150);
            $table->boolean('is_utama')->default(false);
            $table->boolean('aktif')->default(true);

            // 7 desimal ≈ 1 cm; cukup untuk menunjuk pintu bangunan.
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('presisi', ['gedung', 'kawasan'])->default('kawasan');

            $table->string('alamat', 500)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kab_kota', 80);
            $table->string('provinsi', 80)->default('Sumatera Selatan');

            // Logo resmi: file di public/ + catatan sumbernya (tanpa hotlink).
            $table->string('logo_path', 300)->nullable();
            $table->string('logo_sumber', 300)->nullable();
            $table->string('logo_url_sumber', 500)->nullable();

            // Jejak verifikasi koordinat.
            $table->string('sumber_koordinat', 500)->nullable();
            $table->string('metode_koordinat', 300)->nullable();
            $table->json('verifikasi_koordinat')->nullable();
            $table->date('tanggal_cek')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['kab_kota', 'is_utama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kampus_lokasi');
    }
};
