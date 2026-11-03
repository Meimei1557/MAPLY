<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_candidate_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('ai_kos_candidates')->cascadeOnDelete();
            $table->string('source_name', 100);
            $table->string('source_url', 1000)->nullable();
            $table->json('evidence')->nullable();
            $table->timestamp('collected_at')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ai_candidate_sources'); }
};
