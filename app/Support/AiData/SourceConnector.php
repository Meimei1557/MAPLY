<?php

namespace App\Support\AiData;

use App\Models\AiSearchRun;

/**
 * Kontrak connector sumber data (driver pattern) untuk Tahap 1.
 *
 * Setiap sumber eksternal (Overpass, Google Places, berkas JSON/CSV, URL tunggal)
 * mengimplementasikan kontrak ini dan mengembalikan daftar RawCandidate.
 */
interface SourceConnector
{
    /**
     * Kunci sumber yang stabil (dipakai pada ai_search_runs.sources dan form admin).
     */
    public function key(): string;

    /**
     * Label ramah pengguna (Bahasa Indonesia) untuk UI admin.
     */
    public function label(): string;

    /**
     * True bila driver siap dipakai (mis. API key sudah diatur di .env).
     * Driver yang belum dikonfigurasi tampil nonaktif di form admin beserta alasannya.
     */
    public function isConfigured(): bool;

    /**
     * Alasan driver belum siap (Bahasa Indonesia), dikosongkan bila isConfigured() = true.
     */
    public function unconfiguredReason(): string;

    /**
     * Ambil kandidat mentah dari sumber untuk satu AiSearchRun.
     *
     * @return RawCandidate[]
     *
     * @throws \Throwable bila sumber gagal total; pesan dicatat ke Log dan ai_search_runs.message
     *                    oleh orchestrator IngestKosCandidatesJob.
     */
    public function search(AiSearchRun $run): array;
}
