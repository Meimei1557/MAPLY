<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_kos_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('search_run_id')->nullable()->constrained('ai_search_runs')->nullOnDelete();
            $table->string('provider_id', 191)->nullable()->unique();
            $table->string('nama_kos');
            $table->text('alamat')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota', 80);
            $table->unsignedInteger('harga')->nullable();
            $table->string('telepon', 40)->nullable();
            $table->string('website', 500)->nullable();
            $table->string('foto_url', 1000)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('google_maps_url', 1000)->nullable();
            $table->json('fasilitas')->nullable();
            $table->string('tipe', 30)->default('Belum diketahui');
            $table->unsignedTinyInteger('trust_score')->default(0);
            $table->string('verification_status', 30)->default('needs_review')->index();
            $table->json('verification_reasons')->nullable();
            $table->json('raw_data')->nullable();
            $table->string('source_name', 100)->nullable();
            $table->string('source_url', 1000)->nullable();
            $table->timestamp('found_at')->nullable();
            $table->foreignId('published_kos_id')->nullable()->constrained('kos')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['kota', 'verification_status']);
        });
    }
    public function down(): void { Schema::dropIfExists('ai_kos_candidates'); }
};
