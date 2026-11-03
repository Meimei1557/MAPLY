<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kos');
            $table->text('alamat');
            $table->string('provinsi')->default('Sumatera Selatan');
            $table->string('kota')->default('Palembang');
            $table->string('kecamatan')->nullable();

            // Koordinat boleh kosong: data tanpa koordinat tetap tampil di daftar, hanya tidak di peta.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            // akurat = titik bangunan terverifikasi, perkiraan = titik area/jalan saja.
            $table->string('koordinat_status', 20)->nullable();

            // Harga boleh kosong bila sumber tidak mencantumkannya (jangan dikarang).
            $table->unsignedInteger('harga')->nullable();
            $table->enum('tipe', ['Putra', 'Putri', 'Campur']);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Tersedia', 'Penuh'])->default('Tersedia');

            // terverifikasi | perlu_konfirmasi | contoh
            $table->string('status_data', 20)->default('perlu_konfirmasi');
            $table->string('foto')->nullable();
            $table->string('kontak_nama')->nullable();
            $table->string('kontak_telepon', 25)->nullable();
            $table->string('sumber_nama')->nullable();
            $table->string('sumber_url', 500)->nullable();
            $table->date('terakhir_dicek')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['kota', 'status']);
            $table->index('harga');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
