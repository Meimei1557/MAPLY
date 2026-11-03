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
     *  - database/data/kos_publik.json : listing dari sumber publik (tiap data punya sumber_url).
     *    Status 'perlu_konfirmasi'. Koordinat sengaja dikosongkan bila belum terverifikasi.
     *  - database/data/kos_hasil_pengumpulan.json : 288 kos Palembang & Indralaya (sumber: Lokanesia, pin Google Maps).
     *  - database/data/kos_tambahan.json : 145 kost tambahan dari halaman Lokanesia baru
     *    (Tridinanti, UIGM, PalComTech) — dihasilkan 2026-10-22.
     *  - database/data/kos_contoh.json : DATA CONTOH untuk demonstrasi fitur (bukan listing nyata).
     *    Lewati dengan MAPLY_SEED_CONTOH=false di .env.
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
                $fasilitas = $row['fasilitas'] ?? [];
                unset($row['fasilitas']);
                if (! array_key_exists('catatan_data', $row) && ($row['status_data'] ?? '') !== 'contoh') {
                    $kurang = [];
                    foreach (['harga' => 'harga', 'kontak_telepon' => 'nomor telepon', 'latitude' => 'koordinat'] as $k => $l) {
                        if (empty($row[$k])) {
                            $kurang[] = $l;
                        }
                    }
                    if (! $fasilitas) {
                        $kurang[] = 'fasilitas';
                    }
                    $row['catatan_data'] = $kurang ? 'Belum ada: '.implode(', ', $kurang).'.' : null;
                }

                $kos = Kos::updateOrCreate(
                    ['nama_kos' => $row['nama_kos'], 'alamat' => $row['alamat']],
                    $row
                );

                Fasilitas::where('kos_id', $kos->id)->delete();
                foreach (array_unique($fasilitas) as $nama) {
                    Fasilitas::create(['kos_id' => $kos->id, 'nama_fasilitas' => $nama]);
                }
            });
        }
    }
}
