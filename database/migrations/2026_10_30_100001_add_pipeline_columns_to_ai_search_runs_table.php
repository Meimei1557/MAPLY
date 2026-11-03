<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi additive untuk pipeline AI Tahap 1 (ingest multi-sumber).
 * - sources        : daftar kunci sumber terpilih pada form admin (mis. ["osm","google_places"]).
 * - options        : opsi tambahan per run (mis. path berkas impor JSON, URL tunggal untuk UrlConnector).
 * - valid_count    : jumlah kandidat berstatus strong_evidence setelah Tahap 3.
 * - rejected_count : jumlah kandidat berstatus rejected setelah Tahap 3.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('ai_search_runs', function (Blueprint $table) {
            $table->json('sources')->nullable()->after('keyword');
            $table->json('options')->nullable()->after('sources');
            $table->unsignedInteger('valid_count')->default(0)->after('duplicate_count');
            $table->unsignedInteger('rejected_count')->default(0)->after('valid_count');
        });
    }

    public function down(): void
    {
        Schema::table('ai_search_runs', function (Blueprint $table) {
            $table->dropColumn(['sources', 'options', 'valid_count', 'rejected_count']);
        });
    }
};
