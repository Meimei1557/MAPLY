<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\Kos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KosSeeder extends Seeder
{
    /**
     * Sumber data:
     *  - database/data/kos_publik.json
     *  - database/data/kos_hasil_pengumpulan.json
     *  - database/data/kos_tambahan.json
     *  - database/data/kos_contoh.json (data demonstrasi)
     *
     * Fasilitas hanya diperbarui jika kolom fasilitas tersedia
     * di dalam data JSON.
     */
    public function run(): void
    {
        $this->import(database_path('data/kos_publik.json'));
        $this->import(database_path('data/kos_hasil_pengumpulan.json'));
        $this->import(database_path('data/kos_tambahan.json'));

        if (filter_var(config('maply.seed_contoh'), FILTER_VALIDATE_BOOLEAN)) {
            $this->import(database_path('data/kos_contoh.json'));
        }
    }

    private function import(string $file): void
    {
        if (! is_file($file)) {
            $this->command?->warn("File data tidak ditemukan: {$file}");

            return;
        }

        $rows = json_decode((string) file_get_contents($file), true);

        if (! is_array($rows)) {
            $this->command?->error("Format JSON tidak valid: {$file}");

            return;
        }

        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {

                // Periksa keberadaan kolom sebelum menghapusnya dari data kos.
                $adaDataFasilitas = array_key_exists('fasilitas', $row);

                $fasilitas = $row['fasilitas'] ?? [];

                unset($row['fasilitas']);

                // Catatan kelengkapan data.
                if (
                    ! array_key_exists('catatan_data', $row)
                    && ($row['status_data'] ?? '') !== 'contoh'
                ) {
                    $kurang = [];

                    foreach ([
                        'harga' => 'harga',
                        'kontak_telepon' => 'nomor telepon',
                        'latitude' => 'koordinat',
                    ] as $k => $l) {
                        if (empty($row[$k])) {
                            $kurang[] = $l;
                        }
                    }

                    if (! $fasilitas) {
                        $kurang[] = 'fasilitas';
                    }

                    $row['catatan_data'] = $kurang
                        ? 'Belum ada: '.implode(', ', $kurang).'.'
                        : null;
                }

                // Simpan atau perbarui data kos.
                $kos = Kos::updateOrCreate(
                    [
                        'nama_kos' => $row['nama_kos'],
                        'alamat' => $row['alamat'],
                    ],
                    $row
                );

                // Fasilitas hanya diperbarui jika kolom tersedia di JSON.
                if ($adaDataFasilitas && is_array($fasilitas)) {
                    Fasilitas::where('kos_id', $kos->id)->delete();

                    foreach (array_unique($fasilitas) as $nama) {
                        if (! is_string($nama) || trim($nama) === '') {
                            continue;
                        }

                        Fasilitas::create([
                            'kos_id' => $kos->id,
                            'nama_fasilitas' => trim($nama),
                        ]);
                    }
                }
            });
        }
    }
}
