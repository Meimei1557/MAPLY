<?php

namespace App\Support\AiData;

use App\Models\AiSearchRun;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Connector impor berkas JSON/CSV yang diunggah admin.
 *
 * Skema baris mengikuti database/data/kos_publik.json:
 *   nama_kos (wajib), alamat, kecamatan, kota, latitude, longitude, harga,
 *   tipe, fasilitas (array atau dipisah "|"), telepon, website, sumber_nama, sumber_url.
 *
 * Prinsip kejujuran data: field yang tidak ada/tidak valid dibiarkan NULL.
 */
class JsonFileConnector implements SourceConnector
{
    public function key(): string
    {
        return 'json_file';
    }

    public function label(): string
    {
        return 'Berkas JSON/CSV';
    }

    public function isConfigured(): bool
    {
        return true; // Siap dipakai; berkas disertakan lewat halaman impor admin.
    }

    public function unconfiguredReason(): string
    {
        return '';
    }

    public function search(AiSearchRun $run): array
    {
        $path = (string) data_get($run->options ?? [], 'import_path', '');

        if ($path === '' || !Storage::disk('local')->exists($path)) {
            throw new RuntimeException(
                'Berkas impor tidak ditemukan di penyimpanan. Ulangi proses impor dari halaman admin.'
            );
        }

        $payload = json_decode(Storage::disk('local')->get($path), true);

        $rows = is_array($payload['rows'] ?? null) ? $payload['rows'] : [];
        $original = (string) ($payload['original'] ?? 'berkas');

        $regionKey = $run->region === 'indralaya' ? 'indralaya' : 'palembang';
        $label = config("ai_pipeline.regions.{$regionKey}.label");

        $candidates = [];

        foreach ($rows as $index => $row) {
            if (!is_array($row)) {
                continue;
            }

            $nama = trim((string) ($row['nama_kos'] ?? ''));

            if ($nama === '') {
                continue;
            }

            $lat = $row['latitude'] ?? null;
            $lng = $row['longitude'] ?? null;

            // ID provider stabil dari isi baris: impor ulang berkas sama tidak membuat kandidat kembar.
            $hash = substr(sha1(mb_strtolower($nama) . '|' . mb_strtolower((string) ($row['kota'] ?? '')) . '|' . mb_strtolower((string) ($row['alamat'] ?? ''))), 0, 32);

            $candidates[] = new RawCandidate(
                nama: $nama,
                alamat: ($row['alamat'] ?? null) !== null ? trim((string) $row['alamat']) ?: null : null,
                kota: trim((string) ($row['kota'] ?? '')) ?: $label,
                telepon: ($row['telepon'] ?? null) !== null ? trim((string) $row['telepon']) ?: null : null,
                website: ($row['website'] ?? null) !== null ? trim((string) $row['website']) ?: null : null,
                kecamatan: ($row['kecamatan'] ?? null) !== null ? trim((string) $row['kecamatan']) ?: null : null,
                lat: is_numeric($lat) ? (float) $lat : null,
                lng: is_numeric($lng) ? (float) $lng : null,
                harga: is_numeric($row['harga'] ?? null) ? max(0, (int) $row['harga']) : null,
                tipe: in_array($row['tipe'] ?? '', ['Putra', 'Putri', 'Campur'], true) ? $row['tipe'] : null,
                fasilitas: self::normalisasiFasilitas($row['fasilitas'] ?? null),
                providerId: 'json:' . $hash,
                sourceName: (string) ($row['sumber_nama'] ?? '') ?: 'Impor Berkas',
                sourceUrl: ($row['sumber_url'] ?? null) ? mb_substr((string) $row['sumber_url'], 0, 500) : null,
                rawData: [
                    'baris' => $index + 1,
                    'berkas_asal' => $original,
                    'row' => $row,
                ],
            );
        }

        return $candidates;
    }

    /**
     * Validasi & normalisasi isi berkas unggahan (dipakai controller impor sebelum preview).
     *
     * @return array{rows: array, skipped: int}
     *
     * @throws RuntimeException bila struktur tidak valid atau melebihi batas.
     */
    public static function parseUpload(string $content, string $originalName): array
    {
        $maxRows = (int) config('ai_pipeline.import.max_rows', 2000);

        $extension = mb_strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (in_array($extension, ['csv', 'txt'], true)) {
            $rows = self::parseCsv($content);
        } else {
            $rows = self::parseJson($content);
        }

        if (count($rows) === 0) {
            throw new RuntimeException('Berkas tidak berisi baris data yang dapat dibaca.');
        }

        if (count($rows) > $maxRows) {
            throw new RuntimeException(
                "Berkas berisi " . count($rows) . " baris. Batas maksimal adalah {$maxRows} baris."
            );
        }

        $normalized = [];
        $skipped = 0;

        foreach ($rows as $row) {
            if (!is_array($row)) {
                $skipped++;
                continue;
            }

            $nama = trim((string) ($row['nama_kos'] ?? $row['nama'] ?? ''));

            if ($nama === '') {
                $skipped++;
                continue;
            }

            $row['nama_kos'] = $nama;
            unset($row['nama']);

            // Normalisasi tipe data supaya preview dan ingest konsisten.
            $row['harga'] = is_numeric($row['harga'] ?? null) ? (int) $row['harga'] : null;
            $row['latitude'] = is_numeric($row['latitude'] ?? null) ? (float) $row['latitude'] : null;
            $row['longitude'] = is_numeric($row['longitude'] ?? null) ? (float) $row['longitude'] : null;
            $row['fasilitas'] = self::normalisasiFasilitas($row['fasilitas'] ?? null);

            $normalized[] = $row;
        }

        if (count($normalized) === 0) {
            throw new RuntimeException(
                'Tidak ada baris valid. Setiap baris wajib memiliki kolom "nama_kos" yang tidak kosong.'
            );
        }

        return ['rows' => $normalized, 'skipped' => $skipped];
    }

    /**
     * Parse JSON: harus berupa array of object.
     */
    private static function parseJson(string $content): array
    {
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Berkas JSON tidak valid: ' . json_last_error_msg() . '.');
        }

        if (!is_array($decoded)) {
            throw new RuntimeException('Struktur JSON harus berupa array berisi objek data kost.');
        }

        // Dukung pembungkus seperti {"data": [...]}.
        if (isset($decoded[0]) === false && is_array($decoded) && array_is_list($decoded) === false) {
            foreach (['data', 'rows', 'kos', 'kost'] as $key) {
                if (is_array($decoded[$key] ?? null)) {
                    $decoded = $decoded[$key];
                    break;
                }
            }
        }

        return $decoded;
    }

    /**
     * Parse CSV dengan pemisah koma atau titik koma; baris pertama adalah header.
     */
    private static function parseCsv(string $content): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        $lines = array_values(array_filter($lines, fn ($line) => trim($line) !== ''));

        if (count($lines) < 2) {
            throw new RuntimeException('Berkas CSV harus memiliki header dan minimal satu baris data.');
        }

        $header = $lines[0];
        $delimiter = (substr_count($header, ';') > substr_count($header, ',')) ? ';' : ',';

        $columns = array_map(fn ($col) => mb_strtolower(trim($col)), str_getcsv($header, $delimiter));

        $rows = [];

        foreach (array_slice($lines, 1) as $line) {
            $values = str_getcsv($line, $delimiter);
            $row = [];

            foreach ($columns as $i => $column) {
                if ($column === '') {
                    continue;
                }

                $row[$column] = isset($values[$i]) ? trim($values[$i]) : '';
            }

            // Kolom kosong -> NULL (jangan dikarang).
            $row = array_map(fn ($value) => ($value === '' ? null : $value), $row);

            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Fasilitas diterima sebagai array, atau string dipisah "|", ",", atau ";".
     */
    public static function normalisasiFasilitas(mixed $value): array
    {
        if (is_string($value)) {
            $value = preg_split('/[|,;]+/u', $value) ?: [];
        }

        if (!is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->values()
            ->take(30)
            ->all();
    }
}
