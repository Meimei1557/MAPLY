<?php

namespace App\Support;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use App\Models\Kos;
use Illuminate\Support\Collection;

/**
 * Sumber tunggal daftar kampus untuk dashboard, filter kos, peta, dan halaman kampus.
 *
 * Tabel `kampus` + `kampus_lokasi` memuat seluruh institusi; config('maply.titik_acuan')
 * hanya dipakai untuk titik NON-kampus (Ampera, Demang, Jakabaring).
 */
class KampusDirectory
{
    /** Radius (km) untuk menghitung jumlah kos di sekitar tiap titik kampus. */
    public const RADIUS_KOS_SEKITAR = 2;

    /**
     * Titik non-kampus dari config (jenis selain 'Kampus'): Ampera, Demang, Jakabaring.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function titikKota(): array
    {
        return collect(config('maply.titik_acuan'))
            ->reject(fn (array $r) => ($r['jenis'] ?? '') === 'Kampus')
            ->all();
    }

    /**
     * Referensi kampus satu-per-lokasi (kampus dengan beberapa lokasi, mis. UNSRI
     * Bukit Besar + Indralaya, muncul sebagai beberapa entri).
     *
     * @return Collection<int, array<string, mixed>> keyed by kode lokasi
     */
    public static function kampusDenganLokasi(): Collection
    {
        $refs = Kampus::query()
            ->where('aktif', true)
            ->with(['lokasiAktif'])
            ->get()
            ->flatMap(function (Kampus $k) {
                $lokasiList = $k->lokasiAktif;

                return $lokasiList->map(fn (KampusLokasi $l) => self::lokasiRef($l, $k, $lokasiList->count() > 1));
            })
            ->filter()
            ->keyBy('kode');

        return $refs;
    }

    /**
     * Bentuk referensi yang sama dengan config('maply.titik_acuan') supaya
     * view, peta, dan perhitungan jarak tidak perlu tahu asal datanya.
     *
     * @return array<string, mixed>
     */
    public static function lokasiRef(KampusLokasi $lokasi, ?Kampus $kampus = null, bool $multiLokasi = false): array
    {
        $kampus ??= $lokasi->kampus;
        $singkat = $kampus?->label ?? $lokasi->nama_lokasi;
        if ($multiLokasi && $kampus) {
            $singkat .= ' — '.$lokasi->nama_lokasi;
        }

        return [
            'kode' => $lokasi->kode,
            'nama' => $kampus?->nama_resmi ?? $lokasi->nama_lokasi,
            'singkat' => $singkat,
            'jenis' => 'Kampus',
            'ikon' => 'graduation-cap',
            'lat' => (float) $lokasi->latitude,
            'lng' => (float) $lokasi->longitude,
            'terverifikasi' => self::terverifikasi($lokasi),
            'presisi' => $lokasi->presisi,
            'sumber' => $lokasi->sumber_koordinat,
            'alamat' => $lokasi->alamat ?? $kampus?->alamat,
            'kecamatan' => $lokasi->kecamatan ?? $kampus?->kecamatan,
            'kab_kota' => $lokasi->kab_kota ?? $kampus?->kab_kota,
            'logo' => self::logoPath($lokasi, $kampus),
        ];
    }


    /**
     * Logo lokal yang sudah diverifikasi sebagai berkas gambar kampus.
     * Jangan memakai SVG berisi huruf/monogram buatan sebagai pengganti logo resmi.
     * Jika path database kosong atau rusak, gunakan aset raster lokal yang tersedia.
     */
    private static function logoPath(KampusLokasi $lokasi, ?Kampus $kampus): ?string
    {
        $pathTersimpan = $lokasi->logo_path;
        if ($pathTersimpan && !preg_match('/^https?:\\/\\//i', $pathTersimpan)
            && preg_match('/\\.(png|jpe?g|webp)$/i', $pathTersimpan)
            && is_file(public_path($pathTersimpan))) {
            return $pathTersimpan;
        }

        // Utamakan aset lokal berdasarkan kode kampus, termasuk bila ekstensi
        // file berbeda dari path lama yang tersimpan di database.
        $kode = $kampus?->kode;
        if ($kode) {
            foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
                $pathLokal = "logos/kampus/{$kode}.{$ext}";
                if (is_file(public_path($pathLokal))) {
                    return $pathLokal;
                }
            }
        }

        $asetResmi = [
            'unsri' => 'logos/kampus/unsri.png',
            'polsri' => 'logos/kampus/polsri.png',
            'uin-raden-fatah' => 'logos/kampus/uin-raden-fatah.png',
            'bina-darma' => 'logos/kampus/bina-darma.png',
            'um-palembang' => 'logos/kampus/um-palembang.png',
            'uigm' => 'logos/kampus/uigm.png',
            'tridinanti' => 'logos/kampus/tridinanti.png',
            'poltekkes' => 'logos/kampus/poltekkes.png',
            'akamigas' => 'logos/kampus/akamigas.png',
            'politeknik-pariwisata-palembang' => 'logos/kampus/politeknik-pariwisata-palembang.png',
            'stik-siti-khadijah' => 'logos/kampus/stik-siti-khadijah.jpg',
            // Logo berikut memakai URL sumber gambar karena file lokal resmi belum tersedia di arsip.
            'upgri-palembang' => 'logos/kampus/upgri-palembang.png',
            'umdp-palembang' => 'https://cdn.annibuku.com/uploads/logo-kampus/948-universitas-multi-data-palembang.jpg',
            'poltekbang-palembang' => 'https://assets.nsd.co.id/images/kampus/logo/RDFEMkIzMDktMkMzMi00Mzc5LUI2NjctRDIyNjdGNDkzMTE1.jpg',
            'sekolah-tinggi-ilmu-kesehatan-bina-husada' => 'https://pbs.twimg.com/profile_images/1580820760288849920/xhuXzRrC_400x400.jpg',
            'universitas-kader-bangsa' => 'https://1.bp.blogspot.com/-8JO9Xyjtdec/X8JrPehHpKI/AAAAAAAAK4s/CJakkAXUa2Qfc1fd9E7rraWMDsW_T_PnQCLcBGAsYHQ/w1200-h630-p-k-no-nu/Universitas_Kader_Bangsa.png',
            'universitas-iba' => 'https://iba.ac.id/assets/images/partner_1775796556.jpeg',
            'sekolah-tinggi-ilmu-kesehatan-mitra-adiguna' => 'https://digitepp.id/packages/upload/kep/logo/XnlNEVN.png',
        ];

        $fallback = $kode ? ($asetResmi[$kode] ?? null) : null;

        if (!$fallback) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $fallback)) {
            return $fallback;
        }

        return is_file(public_path($fallback)) ? $fallback : null;
    }

    /** Titik hanya dianggap terverifikasi bila presisi bukan 'perkiraan' dan punya jejak sumber. */
    public static function terverifikasi(KampusLokasi $lokasi): bool
    {
        if ($lokasi->presisi === 'perkiraan') {
            return false;
        }

        return $lokasi->latitude !== null
            && $lokasi->longitude !== null
            && filled($lokasi->verifikasi_koordinat);
    }

    /**
     * Referensi gabungan untuk select filter: grup Kampus (dari DB) + Titik kota (config).
     *
     * @return array{campus: array<string, array<string, mixed>>, kota: array<string, array<string, mixed>>}
     */
    public static function referensiFilter(): array
    {
        return [
            'campus' => self::kampusDenganLokasi()->all(),
            'kota' => self::titikKota(),
        ];
    }

    /**
     * Titik kampus terverifikasi untuk peta, termasuk jumlah kos terdekat.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function kampusUntukPeta(int $radiusKm = self::RADIUS_KOS_SEKITAR): array
    {
        // Ambil koordinat kos sekali saja, hitung jaraknya di PHP agar lintas driver SQL.
        $koordinatKos = Kos::tersedia()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['latitude', 'longitude']);

        $kampusKordinat = KampusLokasi::query()
            ->where('aktif', true)
            ->with('kampus:id,kode,nama_resmi,singkatan,kab_kota')
            ->get()
            ->filter(fn (KampusLokasi $l) => self::terverifikasi($l));

        $urlKos = fn (string $kode) => route('kos.index', ['kampus' => $kode]);

        return $kampusKordinat
            ->map(fn (KampusLokasi $l) => [
                'kode' => $l->kode,
                'nama' => $l->kampus?->nama_resmi ?? $l->nama_lokasi,
                'singkat' => $l->kampus?->label ?? $l->nama_lokasi,
                'alamat' => $l->alamat ?? $l->kampus?->alamat,
                'kecamatan' => $l->kecamatan,
                'kota' => $l->kab_kota,
                'lat' => (float) $l->latitude,
                'lng' => (float) $l->longitude,
                'presisi' => $l->presisi,
                'logo' => self::logoPath($l, $l->kampus),
                'kos_sekitar' => $koordinatKos
                    ->filter(fn ($k) => Geo::distanceKm((float) $l->latitude, (float) $l->longitude, (float) $k->latitude, (float) $k->longitude) <= $radiusKm)
                    ->count(),
                'url' => $urlKos($l->kode),
            ])
            ->sortBy('nama')
            ->values()
            ->all();
    }

    /**
     * Cari satu kampus/lokasi berdasarkan kode (untuk resolveReference).
     * Menerima kode lokasi ('polsri-1'), kode kampus ('polsri'), maupun kode
     * lama berakhiran '-utama'.
     *
     * @return array{0: string, 1: array<string, mixed>} [key, ref]
     */
    public static function refDariDb(string $key): ?array
    {
        $lokasi = KampusLokasi::query()
            ->where('aktif', true)
            ->where('kode', $key)
            ->with('kampus')
            ->first();

        if ($lokasi) {
            return [$lokasi->kode, self::lokasiRef($lokasi)];
        }

        $kampus = Kampus::query()
            ->where('aktif', true)
            ->where('kode', $key)
            ->with(['lokasiAktif'])
            ->first();

        if ($kampus) {
            $lokasi = $kampus->lokasiAktif->first();

            return $lokasi
                ? [$key, self::lokasiRef($lokasi, $kampus, $kampus->lokasiAktif->count() > 1)]
                : null;
        }

        return null;
    }
}
