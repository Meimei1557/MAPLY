<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use App\Support\KampusDirectory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Daftar kampus aktif (sumber tunggal: tabel kampus + kampus_lokasi) dengan
 * pencarian, filter (negeri/swasta, jenis, kecamatan/wilayah), dan paginasi.
 */
class KampusController extends Controller
{
    public const JENIS = ['Universitas', 'Institut', 'Sekolah Tinggi', 'Politeknik', 'Akademi'];

    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:Negeri,Swasta'],
            'jenis' => ['nullable', 'in:'.implode(',', self::JENIS)],
            'wilayah' => ['nullable', 'in:Palembang,Ogan Ilir'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'koordinat' => ['nullable', 'in:semua,terverifikasi,belum'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $perPage = 24;

        // Hanya ambil institusi yang aktif.
        $query = Kampus::query()
            ->where('aktif', true)
            ->with(['lokasiAktif'])
            ->withCount('lokasiAktif');

        if ($q = trim((string) $request->input('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_resmi', 'like', "%{$q}%")
                    ->orWhere('singkatan', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        if ($request->filled('wilayah')) {
            $query->where('kab_kota', $request->input('wilayah'));
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->input('kecamatan'));
        }

        if ($request->input('koordinat', 'semua') === 'terverifikasi') {
            $query->whereHas('lokasiAktif');
        } elseif ($request->input('koordinat', 'semua') === 'belum') {
            $query->whereDoesntHave('lokasiAktif');
        }

        $kampus = $query
            ->orderByDesc('lokasi_aktif_count')
            ->orderBy('nama_resmi')
            ->paginate($perPage)
            ->withQueryString();

        $cards = $kampus->getCollection()
            ->map(fn (Kampus $k) => $this->kamusCard($k));

        $kampus->setCollection($cards);

        return view('kampus.index', [
            'kampus' => $kampus,
            'q' => $request->input('q'),
            'status' => $request->input('status'),
            'jenis' => $request->input('jenis'),
            'wilayah' => $request->input('wilayah'),
            'kecamatan' => $request->input('kecamatan'),
            'koordinat' => $request->input('koordinat', 'semua'),
            'jenisOptions' => self::JENIS,

            // Daftar kecamatan hanya dari institusi aktif.
            'kecamatanList' => Kampus::query()
                ->where('aktif', true)
                ->whereNotNull('kecamatan')
                ->distinct()
                ->orderBy('kecamatan')
                ->pluck('kecamatan'),

            // Statistik hanya menghitung institusi dan lokasi aktif.
            'stat' => [
                'total' => Kampus::query()
                    ->where('aktif', true)
                    ->count(),

                'aktif' => Kampus::query()
                    ->where('aktif', true)
                    ->count(),

                'peta' => KampusLokasi::query()
                    ->where('aktif', true)
                    ->whereHas('kampus', function ($q) {
                        $q->where('aktif', true);
                    })
                    ->count(),
            ],
        ]);
    }

    /**
     * Data kartu untuk satu kampus: logo (bila ada), inisial fallback, status koordinat.
     *
     * @return array<string, mixed>
     */
    private function kamusCard(Kampus $k): array
    {
        $lokasi = $k->lokasiAktif->first();

        return [
            'kode' => $k->kode,
            'kode_lokasi' => $lokasi?->kode,
            'label' => $k->label,
            'nama' => $k->nama_resmi,
            'status' => $k->status,
            'jenis' => $k->jenis,
            'aktif' => $k->aktif,
            'kecamatan' => $k->kecamatan,
            'kab_kota' => $k->kab_kota,
            'logo' => $this->logoKampus($k, $lokasi),
            'punya_koordinat' => $lokasi !== null,
            'terverifikasi' => $lokasi !== null && KampusDirectory::terverifikasi($lokasi),
        ];
    }

    /**
     * Logo kartu: logo_path lokasi bila filenya ada; jika tidak, file public/logos/kampus/{kode}.*
     * (dipakai juga untuk kampus yang belum punya titik koordinat).
     */
    private function logoKampus(Kampus $k, ?KampusLokasi $lokasi): ?string
    {
        if ($lokasi?->logo_path && is_file(public_path($lokasi->logo_path))) {
            return $lokasi->logo_path;
        }

        foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
            $path = "logos/kampus/{$k->kode}.{$ext}";

            if (is_file(public_path($path))) {
                return $path;
            }
        }

        return null;
    }
}
