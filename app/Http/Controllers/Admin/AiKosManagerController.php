<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\IngestKosCandidatesJob;
use App\Jobs\ProcessCandidateWithAiJob;
use App\Jobs\ValidateCandidatesJob;
use App\Models\AiKosCandidate;
use App\Models\AiSearchRun;
use App\Models\Kampus;
use App\Models\Kos;
use App\Support\AiData\GeminiClient;
use App\Support\AiData\JsonFileConnector;
use App\Support\AiData\SourceConnectorManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class AiKosManagerController extends Controller
{
    public function __construct(protected SourceConnectorManager $connectors)
    {
    }

    public function index(Request $request): View
    {
        // Filter tabel kandidat: status / sumber / rentang skor / kota.
        $filter = $request->validate([
            'status' => ['nullable', 'in:strong_evidence,needs_review,suspicious,rejected,published'],
            'sumber' => ['nullable', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:80'],
            'skor_min' => ['nullable', 'integer', 'min:0', 'max:100'],
            'skor_max' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $candidates = AiKosCandidate::query()->with('sources')->latest();

        if (!empty($filter['status'])) {
            $candidates->where('verification_status', $filter['status'] === 'published'
                ? 'published'
                : $filter['status']);
        }

        if (!empty($filter['sumber'])) {
            $candidates->where('source_name', 'like', '%' . $filter['sumber'] . '%');
        }

        if (!empty($filter['kota'])) {
            $candidates->where('kota', 'like', '%' . $filter['kota'] . '%');
        }

        if (isset($filter['skor_min'])) {
            $candidates->where('trust_score', '>=', $filter['skor_min']);
        }

        if (isset($filter['skor_max'])) {
            $candidates->where('trust_score', '<=', $filter['skor_max']);
        }

        $daftarConnector = collect($this->connectors->all())
            ->map(fn ($connector) => [
                'key' => $connector->key(),
                'label' => $connector->label(),
                'configured' => $connector->isConfigured(),
                'reason' => $connector->isConfigured() ? null : $connector->unconfiguredReason(),
            ])
            ->values()
            ->all();

        return view('admin.ai-kos.index', [
            'runs' => AiSearchRun::withCount('candidates')->latest()->limit(8)->get(),
            'candidates' => $candidates->paginate(15)->appends($request->query()),
            'connectors' => $daftarConnector,
            'filter' => $filter,
            'stats' => [
                'total' => AiKosCandidate::count(),
                'strong' => AiKosCandidate::where('verification_status', 'strong_evidence')->count(),
                'review' => AiKosCandidate::where('verification_status', 'needs_review')->count(),
                'rejected' => AiKosCandidate::where('verification_status', 'rejected')->count(),
                'suspicious' => AiKosCandidate::where('verification_status', 'suspicious')->count(),
                'published' => AiKosCandidate::whereNotNull('published_kos_id')->count(),
            ],
            'apiConfigured' => GeminiClient::configured(),
            'queueConnection' => config('queue.default'),
        ]);
    }

    /**
     * Mulai pencarian dari sumber terpilih (Tahap 1), lalu chain Tahap 2 & 3.
     *
     * Input pencarian SPESIFIK (poin 10b): wilayah, kecamatan, dekat kampus,
     * rentang harga, tipe kos, kata kunci, jumlah hasil maksimal, dan sumber.
     * Kriteria disimpan ke run->options.kriteria dan dipakai Tahap 2 (prompt AI)
     * serta Tahap 3 (alasan relevansi pada kandidat).
     */
    public function search(Request $request): RedirectResponse
    {
        $keys = $this->connectors->keys();

        $data = $request->validate([
            'region' => ['required', 'in:palembang,indralaya'],
            'keyword' => ['nullable', 'string', 'max:120'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kampus' => ['nullable', 'string', 'max:80', Rule::exists('kampus', 'kode')->where('aktif', true)],
            'harga_min' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'harga_max' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'tipe' => ['nullable', 'in:Putra,Putri,Campur'],
            'max_results' => ['nullable', 'integer', 'min:1', 'max:' . (int) config('ai_pipeline.search.max_results_max', 200)],
            'sources' => ['required', 'array', 'min:1'],
            'sources.*' => [Rule::in($keys)],
            'url' => ['nullable', 'url', 'max:1000', Rule::requiredIf(in_array('url', (array) $request->input('sources', [])))],
        ]);

        $hargaMin = isset($data['harga_min']) ? (int) $data['harga_min'] : null;
        $hargaMax = isset($data['harga_max']) ? (int) $data['harga_max'] : null;

        if ($hargaMin !== null && $hargaMax !== null && $hargaMin > $hargaMax) {
            [$hargaMin, $hargaMax] = [$hargaMax, $hargaMin];
        }

        $active = AiSearchRun::whereIn('status', ['queued', 'running'])->exists();
        if ($active) return back()->with('warning', 'Pencarian lain masih berjalan. Tunggu hingga selesai sebelum memulai pencarian baru.')->withInput();

        $sources = (array) $data['sources'];

        // Sumber yang belum dikonfigurasi ditolak dengan pesan jelas.
        foreach ($sources as $key) {
            $connector = $this->connectors->get($key);

            if ($connector && !$connector->isConfigured()) {
                return back()->with('warning', "{$connector->label()} belum dapat dipakai: {$connector->unconfiguredReason()}")->withInput();
            }
        }

        $options = [];

        if (in_array('url', $sources, true)) {
            $options['url'] = trim((string) $data['url']);
        }

        // Kriteria terstruktur untuk filter relevansi (poin 10b/c).
        $kriteria = array_filter([
            'kecamatan' => isset($data['kecamatan']) ? trim((string) $data['kecamatan']) : null,
            'kampus' => $data['kampus'] ?? null,
            'harga_min' => $hargaMin,
            'harga_max' => $hargaMax,
            'tipe' => $data['tipe'] ?? null,
            'max_results' => isset($data['max_results']) ? (int) $data['max_results'] : (int) config('ai_pipeline.search.max_results', 50),
        ], fn ($v) => $v !== null && $v !== '');

        if ($kriteria !== []) {
            $options['kriteria'] = $kriteria;
        }

        $run = AiSearchRun::create([
            'admin_id' => $request->user()->id,
            'region' => $data['region'],
            'keyword' => trim($data['keyword'] ?? 'kost') ?: 'kost',
            'status' => 'queued',
            'sources' => $sources,
            'options' => $options,
        ]);

        if (!$this->dispatchPipeline($run)) {
            return back()->with('warning', 'Pipeline gagal dijadwalkan. Periksa log aplikasi, lalu coba lagi.')->withInput();
        }

        $ringkasKriteria = collect($kriteria)
            ->reject(fn ($v, $k) => $k === 'max_results')
            ->map(fn ($v, $k) => $k . '=' . (is_array($v) ? implode(',', $v) : $v))
            ->implode(', ');

        return back()->with('success', 'Pencarian dijadwalkan (' . implode(', ', $sources) . ')'
            . ($ringkasKriteria !== '' ? ' dengan kriteria: ' . $ringkasKriteria : '')
            . '. Tahap ingest, pemrosesan AI, dan validasi berjalan otomatis. Periksa status pada riwayat pencarian.');
    }

    // ------------------------------------------------------------------ Impor

    public function importForm(): View
    {
        return view('admin.ai-kos.import', [
            'preview' => session('ai_import'),
        ]);
    }

    /**
     * Langkah 1 impor: unggah berkas JSON/CSV, validasi struktur, simpan preview 10 baris.
     */
    public function importPreview(Request $request): RedirectResponse
    {
        $request->validate([
            'region' => ['required', 'in:palembang,indralaya'],
            'berkas' => ['required', 'file', 'max:' . config('ai_pipeline.import.max_size_kb', 5120), 'extensions:json,csv,txt'],
        ]);

        $berkas = $request->file('berkas');

        try {
            $hasil = JsonFileConnector::parseUpload(
                (string) file_get_contents($berkas->getRealPath()),
                $berkas->getClientOriginalName() ?: 'berkas.json',
            );
        } catch (RuntimeException $e) {
            throw ValidationException::withMessages(['berkas' => $e->getMessage()]);
        }

        // Simpan baris ternormalisasi ke penyimpanan lokal untuk dibaca connector saat ingest.
        $path = 'ai-imports/' . Str::uuid() . '.json';
        Storage::disk('local')->put($path, json_encode([
            'original' => $berkas->getClientOriginalName(),
            'rows' => $hasil['rows'],
        ], JSON_UNESCAPED_UNICODE));

        session([
            'ai_import' => [
                'path' => $path,
                'original' => $berkas->getClientOriginalName(),
                'region' => $request->input('region'),
                'total' => count($hasil['rows']),
                'skipped' => $hasil['skipped'],
                'preview' => array_slice($hasil['rows'], 0, 10),
            ],
        ]);

        return redirect()->route('admin.ai-kos.import.form')
            ->with('success', 'Berkas terbaca: ' . count($hasil['rows']) . ' baris valid. Periksa preview lalu konfirmasi.');
    }

    /**
     * Langkah 2 impor: konfirmasi preview -> jalan sebagai AiSearchRun biasa
     * (riwayat lengkap, chain Tahap 2 & 3 sama seperti pencarian).
     */
    public function importConfirm(Request $request): RedirectResponse
    {
        $impor = session('ai_import');

        if (!$impor || empty($impor['path']) || !Storage::disk('local')->exists($impor['path'])) {
            return redirect()->route('admin.ai-kos.import.form')->with('warning', 'Sesi impor berakhir atau berkas tidak ditemukan. Unggah ulang berkasnya.');
        }

        $active = AiSearchRun::whereIn('status', ['queued', 'running'])->exists();
        if ($active) return back()->with('warning', 'Pencarian lain masih berjalan. Tunggu hingga selesai sebelum mengimpor.');

        $run = AiSearchRun::create([
            'admin_id' => $request->user()->id,
            'region' => $impor['region'] ?? 'palembang',
            'keyword' => 'impor: ' . ($impor['original'] ?? 'berkas'),
            'status' => 'queued',
            'sources' => ['json_file'],
            'options' => [
                'import_path' => $impor['path'],
                'import_original' => $impor['original'] ?? null,
            ],
        ]);

        if (!$this->dispatchPipeline($run)) {
            return back()->with('warning', 'Pipeline impor gagal dijadwalkan. Periksa log aplikasi, lalu coba lagi.');
        }

        session()->forget('ai_import');

        return redirect()->route('admin.ai-kos.index')->with('success', 'Impor dijadwalkan. Kandidat akan muncul setelah Tahap 1-3 selesai.');
    }

    public function importCancel(): RedirectResponse
    {
        $impor = session('ai_import');

        if ($impor && !empty($impor['path'])) {
            Storage::disk('local')->delete($impor['path']);
        }

        session()->forget('ai_import');

        return redirect()->route('admin.ai-kos.import.form')->with('success', 'Impor dibatalkan.');
    }

    // ------------------------------------------------------------------ Detail

    public function show(AiKosCandidate $candidate): View
    {
        return view('admin.ai-kos.show', ['candidate' => $candidate->load('sources')]);
    }

    // ------------------------------------------------------------------ Persetujuan

    public function approve(Request $request, AiKosCandidate $candidate): RedirectResponse
    {
        if ($candidate->published_kos_id) return back()->with('warning', 'Kandidat ini sudah dipublikasikan.');
        if ($candidate->verification_status === 'rejected') return back()->with('warning', 'Kandidat yang ditolak tidak dapat dipublikasikan.');

        $data = $request->validate([
            'nama_kos' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:2000'],
            'kota' => ['required', 'in:Palembang,Indralaya'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'harga' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'tipe' => ['required', 'in:Putra,Putri,Campur,Belum diketahui'],
            'telepon' => ['nullable', 'string', 'max:25'],
        ]);

        $kos = $this->publishCandidate($candidate, $data, $request->user()->id);

        return redirect()->route('admin.ai-kos.index')->with('success', 'Kost disimpan dan diterbitkan ke Maply: ' . $kos->nama_kos);
    }

    /**
     * Aksi massal: approve / reject / reprocess kandidat terpilih.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,reject,reprocess'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $candidates = AiKosCandidate::whereIn('id', $data['ids'])->get();
        $user = $request->user();

        if ($data['action'] === 'reject') {
            $jumlah = 0;

            foreach ($candidates as $candidate) {
                if ($candidate->published_kos_id) {
                    continue;
                }

                $candidate->update([
                    'verification_status' => 'rejected',
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                ]);
                $jumlah++;
            }

            return back()->with('success', "{$jumlah} kandidat ditandai ditolak.");
        }

        if ($data['action'] === 'approve') {
            $diterbitkan = 0;
            $dilewati = [];

            foreach ($candidates as $candidate) {
                if ($candidate->published_kos_id || $candidate->verification_status === 'rejected') {
                    $dilewati[] = $candidate->nama_kos;
                    continue;
                }

                // Data dipakai apa adanya dari kandidat; tanpa alamat/kota valid tidak dapat diterbitkan.
                if (trim((string) $candidate->alamat) === '') {
                    $dilewati[] = $candidate->nama_kos . ' (alamat kosong)';
                    continue;
                }

                $this->publishCandidate($candidate, [
                    'nama_kos' => $candidate->nama_kos,
                    'alamat' => $candidate->alamat,
                    'kota' => in_array($candidate->kota, ['Palembang', 'Indralaya'], true) ? $candidate->kota : 'Palembang',
                    'kecamatan' => $candidate->kecamatan,
                    'harga' => $candidate->harga,
                    'tipe' => in_array($candidate->tipe, ['Putra', 'Putri', 'Campur', 'Belum diketahui'], true) ? $candidate->tipe : 'Belum diketahui',
                    'telepon' => $candidate->telepon,
                ], $user->id);
                $diterbitkan++;
            }

            $pesan = "{$diterbitkan} kandidat diterbitkan.";

            if ($dilewati !== []) {
                $pesan .= ' Dilewati: ' . implode(', ', array_slice($dilewati, 0, 5)) . (count($dilewati) > 5 ? ', ...' : '');
            }

            return back()->with($diterbitkan > 0 ? 'success' : 'warning', $pesan);
        }

        // Reproses: jalankan ulang Tahap 2 + 3 untuk kandidat terpilih.
        $diproses = $candidates->filter(
            fn ($candidate) => $candidate->published_kos_id === null && $candidate->reviewed_by === null,
        );

        if ($diproses->isEmpty()) {
            return back()->with('warning', 'Tidak ada kandidat yang dapat diproses ulang (sudah direview/diterbitkan).');
        }

        foreach ($diproses->groupBy(fn ($candidate) => $candidate->search_run_id ?? 0) as $groupRunId => $group) {
            $runId = $groupRunId ?: null;

            ProcessCandidateWithAiJob::withChain([
                new ValidateCandidatesJob($runId),
            ])->dispatch($runId, $group->pluck('id')->all());
        }

        return back()->with('success', $diproses->count() . ' kandidat dijadwalkan untuk diproses ulang (Tahap 2 + 3).');
    }

    public function reject(Request $request, AiKosCandidate $candidate): RedirectResponse
    {
        $candidate->update(['verification_status' => 'rejected', 'reviewed_by' => $request->user()->id, 'reviewed_at' => now()]);
        return back()->with('success', 'Kandidat ditandai ditolak. Riwayat sumber tetap disimpan.');
    }

    public function destroy(AiKosCandidate $candidate): RedirectResponse
    {
        if ($candidate->published_kos_id) return back()->with('warning', 'Kandidat yang sudah diterbitkan tidak dapat dihapus dari sini.');
        $candidate->delete();
        return back()->with('success', 'Kandidat dihapus.');
    }

    // ------------------------------------------------------------------ Internal

    /**
     * Terbitkan kandidat ke tabel kos (status terverifikasi) — prinsip lama dipertahankan:
     * data yang tidak tersedia tidak diisi, dan MAPLY_AI_AUTO_PUBLISH=false tetap dihormati
     * (pipeline tidak pernah memanggil metode ini).
     */
    protected function publishCandidate(AiKosCandidate $candidate, array $data, int $userId): Kos
    {
        $kos = DB::transaction(function () use ($candidate, $data, $userId) {
            $kos = Kos::create([
                'nama_kos' => $data['nama_kos'],
                'alamat' => $data['alamat'],
                'kota' => $data['kota'],
                'kecamatan' => $data['kecamatan'] ?? null,
                'provinsi' => 'Sumatera Selatan',
                'harga' => $data['harga'] ?? null,
                'tipe' => $data['tipe'],
                'status' => 'Tersedia',
                'status_data' => 'terverifikasi',
                'latitude' => $candidate->latitude,
                'longitude' => $candidate->longitude,
                'koordinat_status' => $candidate->latitude !== null && $candidate->longitude !== null ? 'osm' : null,
                'kontak_telepon' => $data['telepon'] ?? $candidate->telepon,
                'sumber_nama' => $candidate->source_name,
                'sumber_url' => $candidate->source_url,
                'terakhir_dicek' => now()->toDateString(),
                'catatan_data' => 'Ditemukan melalui AI Kost Manager. Data yang tidak tersedia tidak diisi.',
                'user_id' => $userId,
            ]);
            $candidate->update(['published_kos_id' => $kos->id, 'verification_status' => 'published', 'reviewed_by' => $userId, 'reviewed_at' => now()]);
            return $kos;
        });

        return $kos;
    }

    /**
     * Jadwalkan pipeline lengkap: Tahap 1 -> Tahap 2 -> Tahap 3 (chained).
     */
    protected function dispatchPipeline(AiSearchRun $run): bool
    {
        try {
            IngestKosCandidatesJob::withChain([
                new ProcessCandidateWithAiJob($run->id),
                new ValidateCandidatesJob($run->id),
            ])->dispatch($run->id);

            return true;
        } catch (Throwable $e) {
            // Pada QUEUE_CONNECTION=sync, kegagalan langsung muncul di sini.
            Log::error('Maply pipeline: gagal menjadwalkan pipeline', [
                'run_id' => $run->id,
                'error' => $e->getMessage(),
            ]);

            $run->update([
                'status' => 'failed',
                'finished_at' => now(),
                'message' => Str::limit('Gagal menjadwalkan pipeline: ' . $e->getMessage(), 500),
            ]);

            return false;
        }
    }
}
