<?php

namespace Database\Seeders;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed kampus dari database/data/kampus.json (110 institusi, hasil inventarisasi
 * PDDikti/referensi resmi 2026-11-02) dan kampus_lokasi.json (17 lokasi fisik
 * dengan koordinat terverifikasi + jejak sumber). Institusi tanpa koordinat
 * terverifikasi TIDAK diberi titik peta.
 */
class KampusSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode((string) file_get_contents(database_path('data/kampus.json')), true);
        if (!is_array($rows)) {
            $this->command?->error('kampus.json tidak valid.');

            return;
        }

        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                Kampus::updateOrCreate(
                    ['kode' => $row['kode']],
                    collect($row)->except(['kode'])->all(),
                );
            });
        }

        $lokasiRows = json_decode((string) file_get_contents(database_path('data/kampus_lokasi.json')), true);
        foreach ($lokasiRows as $row) {
            $kampus = Kampus::where('kode', $row['kampus_slug'])->first();
            if (!$kampus) {
                $this->command?->warn("Lokasi {$row['kode']} tanpa institusi {$row['kampus_slug']} — dilewati.");

                continue;
            }
            $logo = $row['logo'] ?? null;
            KampusLokasi::updateOrCreate(
                ['kode' => $row['kode']],
                [
                    'kampus_id' => $kampus->id,
                    'nama_lokasi' => $row['nama_lokasi'],
                    'is_utama' => $row['is_utama'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'presisi' => $row['presisi'],
                    'alamat' => $row['alamat'],
                    'kecamatan' => $row['kecamatan'],
                    'kab_kota' => $row['kab_kota'],
                    'provinsi' => $row['provinsi'],
                    'logo_path' => $logo['path'] ?? null,
                    'logo_sumber' => $logo['sumber'] ?? null,
                    'logo_url_sumber' => $logo['url_sumber'] ?? null,
                    'sumber_koordinat' => $row['sumber_koordinat'],
                    'metode_koordinat' => $row['metode_koordinat'],
                    'verifikasi_koordinat' => $row['verifikasi_koordinat'],
                    'tanggal_cek' => $row['tanggal_cek'],
                    'catatan' => $row['catatan'],
                ],
            );
        }

        $this->command?->info('Kampus: '.Kampus::count().' institusi, '.KampusLokasi::count().' lokasi terverifikasi.');
    }
}
