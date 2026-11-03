<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Support\Geo;
use App\Support\KampusDirectory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KosController extends Controller
{
    public const URUTAN = [
        'terdekat' => 'Terdekat dari titik acuan',
        'termurah' => 'Harga termurah',
        'termahal' => 'Harga termahal',
        'terbaru' => 'Terbaru ditambahkan',
        'nama' => 'Nama (A–Z)',
    ];

    /**
     * Menentukan titik acuan jarak. Urutan: config titik kota/alias → kampus dari DB
     * (kode lokasi maupun kode kampus) → titik bawaan. Mendukung kunci lama
     * ('palembang', 'indralaya').
     *
     * @return array{0: string, 1: array<string, mixed>}
     */
    public static function resolveReference(?string $key): array
    {
        $refs = config('maply.titik_acuan');
        $key = Str::lower((string) $key);
        $key = config('maply.alias_titik', [])[$key] ?? $key;

        if (isset($refs[$key])) {
            return [$key, $refs[$key]];
        }

        // Kampus dari tabel kampus + kampus_lokasi (sumber tunggal daftar kampus).
        if ($key !== '' && ($dbRef = KampusDirectory::refDariDb($key)) !== null) {
            return $dbRef;
        }

        $key = config('maply.titik_default');

        return [$key, $refs[$key]];
    }

    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'wilayah' => ['nullable', 'in:'.implode(',', array_keys(config('maply.wilayah')))],
            'kampus' => ['nullable', 'string', 'max:80'],
            'harga_min' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'harga_max' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'tipe' => ['nullable', 'in:Putra,Putri,Campur'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['string', 'max:50'],
            'radius' => ['nullable', 'numeric', 'min:1', 'max:50'],
            'urut' => ['nullable', 'in:'.implode(',', array_keys(self::URUTAN))],
            'data' => ['nullable', 'in:semua,publik,contoh'],
            'verifikasi' => ['nullable', 'in:semua,terverifikasi,perlu_konfirmasi'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $wilayah = $request->input('wilayah');
        $kampusInput = $request->input('kampus') ?: ($wilayah ? config('maply.wilayah.'.$wilayah.'.acuan') : null);
        [$refKey, $ref] = self::resolveReference($kampusInput);
        $perPage = (int) config('maply.per_page', 9);

        $min = $request->filled('harga_min') ? (int) $request->input('harga_min') : null;
        $max = $request->filled('harga_max') ? (int) $request->input('harga_max') : null;
        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }
        $radius = $request->filled('radius') ? (float) $request->input('radius') : null;
        $urut = $request->input('urut', 'terdekat');
        $data = $request->input('data', 'semua');
        $verifikasi = $request->input('verifikasi', 'semua');

        $query = Kos::query()->with('fasilitas')->tersedia()->cari($request->input('q'));

        if ($min !== null) {
            $query->where('harga', '>=', $min);
        }
        if ($max !== null) {
            $query->where('harga', '<=', $max);
        }
        if ($wilayah) {
            $query->where('kota', config('maply.wilayah.'.$wilayah.'.kota'));
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->input('tipe'));
        }
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->input('kecamatan'));
        }
        if ($verifikasi !== 'semua') {
            $query->where('status_data', $verifikasi);
        }
        if ($data === 'publik') {
            $query->where('status_data', '!=', 'contoh');
        } elseif ($data === 'contoh') {
            $query->where('status_data', 'contoh');
        }
        foreach ((array) $request->input('fasilitas', []) as $facility) {
            $query->whereHas('fasilitas', fn ($q) => $q->where('nama_fasilitas', $facility));
        }

        // Jarak dihitung di PHP (bukan SQL) agar jalan di SQLite maupun MySQL. Jumlah data kos
        // per kota masih kecil; bila membesar (ribuan), pindahkan ke query berbasis bounding box.
        $items = $query->get();
        $items->each(function (Kos $k) use ($ref) {
            $k->jarak_km = $k->punya_koordinat
                ? Geo::distanceKm($ref['lat'], $ref['lng'], $k->latitude, $k->longitude)
                : null;
        });

        if ($radius !== null) {
            $items = $items->filter(fn (Kos $k) => $k->jarak_km !== null && $k->jarak_km <= $radius);
        }

        $items = match ($urut) {
            'termurah' => $items->sortBy(fn (Kos $k) => $k->harga ?? PHP_INT_MAX),
            'termahal' => $items->sortByDesc(fn (Kos $k) => $k->harga ?? -1),
            'terbaru' => $items->sortByDesc('id'),
            'nama' => $items->sortBy(fn (Kos $k) => Str::lower($k->nama_kos)),
            default => $items->sortBy(fn (Kos $k) => $k->jarak_km ?? INF),
        };
        $items = $items->values();

        $total = $items->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, (int) $request->input('page', 1)), $lastPage);

        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Peta menampilkan SEMUA hasil yang cocok (bukan hanya halaman aktif); data tanpa koordinat dilewati.
        $mapItems = $items
            ->filter(fn (Kos $k) => $k->punya_koordinat)
            ->map(fn (Kos $k) => $k->toMapData($k->jarak_km, $refKey))
            ->values();

        $activeFilters = collect([
            $request->filled('q'), $min !== null, $max !== null, $request->filled('tipe'),
            $request->filled('kecamatan'), $request->filled('fasilitas'), (bool) $wilayah, $radius !== null, $data !== 'semua', $verifikasi !== 'semua',
        ])->filter()->count();

        return view('kos.index', [
            'paginator' => $paginator,
            'total' => $total,
            'mapItems' => $mapItems,
            'tanpaKoordinat' => $items->filter(fn (Kos $k) => ! $k->punya_koordinat)->count(),
            'ref' => $ref,
            'refKey' => $refKey,
            'references' => KampusDirectory::referensiFilter(),
            'kampusPeta' => KampusDirectory::kampusUntukPeta(),
            'radius' => $radius,
            'radiusOptions' => config('maply.radius_opsi'),
            'urut' => $urut,
            'wilayah' => $wilayah,
            'wilayahOptions' => config('maply.wilayah'),
            'urutOptions' => self::URUTAN,
            'data' => $data,
            'verifikasi' => $verifikasi,
            'hargaMin' => $min,
            'hargaMax' => $max,
            'facilityOptions' => config('maply.fasilitas'),
            'kecamatanList' => Kos::tersedia()->whereNotNull('kecamatan')
                ->when($wilayah, fn ($q) => $q->where('kota', config('maply.wilayah.'.$wilayah.'.kota')))
                ->distinct()->orderBy('kecamatan')->pluck('kecamatan'),
            'activeFilters' => $activeFilters,
        ]);
    }

    public function show(Request $request, Kos $kos): View
    {
        [$refKey, $ref] = self::resolveReference($request->input('kampus'));
        $kos->load('fasilitas');

        $jarak = $kos->punya_koordinat
            ? Geo::distanceKm($ref['lat'], $ref['lng'], $kos->latitude, $kos->longitude)
            : null;

        $serupa = Kos::tersedia()
            ->with('fasilitas')
            ->where('id', '!=', $kos->id)
            ->when($kos->kecamatan, fn ($q) => $q->where('kecamatan', $kos->kecamatan))
            ->orderByRaw("case when status_data = 'contoh' then 1 else 0 end")
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('kos.show', [
            'kos' => $kos,
            'ref' => $ref,
            'refKey' => $refKey,
            'jarak' => $jarak,
            'serupa' => $serupa,
            'mapData' => $kos->punya_koordinat ? $kos->toMapData($jarak, $refKey) : null,
        ]);
    }
}
