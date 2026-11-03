<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi additive untuk pipeline AI Tahap 2 & 3.
 * - ai_extracted   : hasil ekstraksi Gemini (JSON) — TIDAK menimpa kolom asli kandidat.
 * - ai_verdict     : penilaian semantik AI: valid | suspicious | invalid.
 * - ai_confidence  : keyakinan AI 0,00-1,00.
 * - ai_analysis    : detail analisis AI (verdict, confidence, reasons, model, diproses_pada).
 * - ai_processed_at: waktu kandidat terakhir diproses Tahap 2.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('ai_kos_candidates', function (Blueprint $table) {
            $table->json('ai_extracted')->nullable()->after('raw_data');
            $table->string('ai_verdict', 20)->nullable()->after('ai_extracted');
            $table->decimal('ai_confidence', 3, 2)->nullable()->after('ai_verdict');
            $table->json('ai_analysis')->nullable()->after('ai_confidence');
            $table->timestamp('ai_processed_at')->nullable()->after('ai_analysis');
            $table->index(['verification_status', 'trust_score']);
        });
    }

    public function down(): void
    {
        Schema::table('ai_kos_candidates', function (Blueprint $table) {
            $table->dropIndex(['verification_status', 'trust_score']);
            $table->dropColumn(['ai_extracted', 'ai_verdict', 'ai_confidence', 'ai_analysis', 'ai_processed_at']);
        });
    }
};
