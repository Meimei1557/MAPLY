<?php

namespace Database\Seeders;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class KampusSeeder extends Seeder
{
    public function run(): void
    {
        $fileKampus = database_path('data/kampus.json');
        $fileLokasi = database_path('data/kampus_lokasi.json');

        if (!is_file($fileKampus) || !is_file($fileLokasi)) {
            throw new RuntimeException('File data kampus atau lokasi tidak ditemukan.');
        }

        $rows = json_decode(
            file_get_contents($fileKampus),
            true
        );

        $lokasiRows = json_decode(
            file_get_contents($fileLokasi),
            true
        );

        if (!is_array($rows) || !is_array($lokasiRows)) {
            throw new RuntimeException('Format JSON kampus atau lokasi tidak valid.');
        }

        // Validasi jumlah data sesuai daftar final MAPLY.
        if (count($rows) !== 20) {
            throw new RuntimeException('Data kampus harus berjumlah tepat 20.');
        }

        if (count($lokasiRows) !== 19) {
            throw new RuntimeException('Data lokasi harus berjumlah tepat 19.');
        }

        // Validasi kode kampus.
        $kodeKampus = array_column($rows, 'kode');

        if (count(array_unique($kodeKampus)) !== count($kodeKampus)) {
            throw new RuntimeException('Ditemukan kode kampus ganda.');
        }

        $daftarKampus = array_fill_keys($kodeKampus, true);

        // Validasi kode dan relasi lokasi.
        $kodeLokasi = [];
        $jumlahUtama = [];

        foreach ($lokasiRows as $row) {
            if (empty($row['kode']) || empty($row['kampus_slug'])) {
                throw new RuntimeException('Ada kode lokasi atau kampus_slug yang kosong.');
            }

            if (isset($kodeLokasi[$row['kode']])) {
                throw new RuntimeException("Kode lokasi ganda: {$row['kode']}");
            }

            $kodeLokasi[$row['kode']] = true;

            if (!isset($daftarKampus[$row['kampus_slug']])) {
                throw new RuntimeException(
                    "Institusi lokasi {$row['kode']} tidak ditemukan."
                );
            }

            if (
                !isset($row['latitude'], $row['longitude']) ||
                !is_numeric($row['latitude']) ||
                !is_numeric($row['longitude'])
            ) {
                throw new RuntimeException(
                    "Koordinat lokasi {$row['kode']} tidak valid."
                );
            }

            if (($row['is_utama'] ?? false) === true) {
                $jumlahUtama[$row['kampus_slug']] =
                    ($jumlahUtama[$row['kampus_slug']] ?? 0) + 1;
            }
        }

        foreach ($jumlahUtama as $slug => $jumlah) {
            if ($jumlah !== 1) {
                throw new RuntimeException(
                    "Institusi {$slug} harus memiliki tepat satu lokasi utama."
                );
            }
        }

       
        // Sinkronisasi dilakukan dalam satu transaksi.
        DB::transaction(function () use (
            $rows,
            $lokasiRows,
            $kodeLokasi,
            $daftarKampus
        ) {

            // Nonaktifkan data lama terlebih dahulu.
            Kampus::query()->update(['aktif' => false]);

            KampusLokasi::query()->update(['aktif' => false]);

            // Aktifkan dan perbarui 20 institusi pilihan.
            foreach ($rows as $row) {
                Kampus::updateOrCreate(
                    ['kode' => $row['kode']],
                    collect($row)
                        ->except(['kode'])
                        ->all()
                );
            }

            // Aktifkan dan perbarui 19 lokasi final.
            foreach ($lokasiRows as $row) {
                $kampus = Kampus::where(
                    'kode',
                    $row['kampus_slug']
                )->first();

                if (!$kampus) {
                    throw new RuntimeException(
                        "Kampus {$row['kampus_slug']} tidak ditemukan."
                    );
                }

                $logo = $row['logo'] ?? null;

                KampusLokasi::updateOrCreate(
                    ['kode' => $row['kode']],
                    [
                        'kampus_id' => $kampus->id,
                        'nama_lokasi' => $row['nama_lokasi'],
                        'is_utama' => $row['is_utama'],
                        'aktif' => true,
                        'latitude' => $row['latitude'],
                        'longitude' => $row['longitude'],
                        'presisi' => $row['presisi'],
                        'alamat' => $row['alamat'] ?? null,
                        'kecamatan' => $row['kecamatan'] ?? null,
                        'kab_kota' => $row['kab_kota'],
                        'provinsi' => $row['provinsi'],
                        'logo_path' => $logo['path'] ?? null,
                        'logo_sumber' => $logo['sumber'] ?? null,
                        'logo_url_sumber' => $logo['url_sumber'] ?? null,
                        'sumber_koordinat' => $row['sumber_koordinat'] ?? null,
                        'metode_koordinat' => $row['metode_koordinat'] ?? null,
                        'verifikasi_koordinat' => $row['verifikasi_koordinat'] ?? null,
                        'tanggal_cek' => $row['tanggal_cek'] ?? null,
                        'catatan' => $row['catatan'] ?? null,
                    ]
                );
            }

            // Hapus lokasi yang tidak termasuk dalam 19 data final.
            KampusLokasi::query()
                ->whereNotIn('kode', array_keys($kodeLokasi))
                ->delete();

            // Hapus kampus yang tidak termasuk dalam 20 data final.
            Kampus::query()
                ->whereNotIn('kode', array_keys($daftarKampus))
                ->delete();
        });

        // Periksa jumlah data aktif setelah sinkronisasi.
        $jumlahKampusAktif = Kampus::where('aktif', true)->count();
        $jumlahLokasiAktif = KampusLokasi::where('aktif', true)->count();

        if ($jumlahKampusAktif !== 20 || $jumlahLokasiAktif !== 19) {
            throw new RuntimeException(
                "Sinkronisasi gagal. Kampus aktif: {$jumlahKampusAktif}, " .
                "lokasi aktif: {$jumlahLokasiAktif}."
            );
        }

        $this->command?->info(
            "Sinkronisasi selesai: {$jumlahKampusAktif} institusi aktif, " .
            "{$jumlahLokasiAktif} lokasi aktif."
        );
    }
}