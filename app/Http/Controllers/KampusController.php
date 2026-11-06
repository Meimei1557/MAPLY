<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\KampusLokasi;
use App\Support\KampusDirectory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Daftar seluruh kampus (sumber tunggal: tabel kampus + kampus_lokasi) dengan
 * pencarian, filter (negeri/swasta, jenis, kecamatan/wilayah), dan paginasi
 * supaya halaman tetap ringan dengan 110+ institusi.
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

        $query = Kampus::query()
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
            'kecamatanList' => Kampus::query()
                ->whereNotNull('kecamatan')->distinct()->orderBy('kecamatan')->pluck('kecamatan'),
            'stat' => [
                'total' => Kampus::query()->count(),
                'aktif' => Kampus::query()->where('aktif', true)->count(),
                'peta' => KampusLokasi::query()->where('aktif', true)->count(),
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
            'logo' => $lokasi?->logo_path,
            'punya_koordinat' => $lokasi !== null,
            'terverifikasi' => $lokasi !== null && KampusDirectory::terverifikasi($lokasi),
        ];
    }
}
