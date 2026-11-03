<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KosRequest;
use App\Models\Fasilitas;
use App\Models\Kos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class KosController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'data' => ['nullable', 'in:semua,terverifikasi,perlu_konfirmasi,contoh,tanpa_koordinat,tanpa_harga'],
        ]);

        $query = Kos::query()->withCount('fasilitas')->cari($request->input('q'));

        if ($request->filled('data') && $request->input('data') !== 'semua') {
            match ($request->input('data')) {
                'tanpa_koordinat' => $query->where(fn ($w) => $w->whereNull('latitude')->orWhereNull('longitude')),
                'tanpa_harga' => $query->whereNull('harga'),
                default => $query->where('status_data', $request->input('data')),
            };
        }

        return view('admin.kos.index', [
            'kosList' => $query->orderByDesc('id')->paginate(12)->withQueryString(),
            'dataFilter' => $request->input('data', 'semua'),
        ]);
    }

    /**
     * Halaman pengelolaan koordinat peta: ringkasan status geocoding dan daftar
     * kost yang perlu ditinjau. Tidak mengubah data — hanya menampilkan.
     */
    public function coordinateDashboard(): View
    {
        $total = Kos::count();
        $valid = Kos::whereNotNull('latitude')->whereNotNull('longitude')->count();
        $kosong = $total - $valid;

        // "Perlu pemeriksaan" = memiliki koordinat tetapi berstatus geocoding
        // 'needs_review' (skor rendah/sedang) atau 'candidate' (skor tinggi
        // yang tetap perlu diverifikasi lapangan).
        $review = Kos::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereIn('geocoding_status', ['needs_review', 'candidate'])
            ->count();

        // Distribusi status geocoding (hanya jika kolom tersedia).
        $statusCounts = Schema::hasColumn('kos', 'geocoding_status')
            ? Kos::select('geocoding_status')
                ->selectRaw('COUNT(*) AS jumlah')
                ->groupBy('geocoding_status')
                ->pluck('jumlah', 'geocoding_status')
            : collect();

        // Daftar kost yang perlu ditinjau (max 25, terbaru dulu).
        $recent = Schema::hasColumn('kos', 'geocoding_status')
            ? Kos::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereIn('geocoding_status', ['needs_review', 'candidate'])
                ->orWhereNull('latitude')
                ->orWhereNull('longitude')
                ->orderByDesc('id')
                ->limit(25)
                ->get()
            : Kos::whereNull('latitude')
                ->orWhereNull('longitude')
                ->orderByDesc('id')
                ->limit(25)
                ->get();

        return view('admin.kos.koordinat', [
            'total' => $total,
            'valid' => $valid,
            'kosong' => $kosong,
            'review' => $review,
            'statusCounts' => $statusCounts,
            'recent' => $recent,
        ]);
    }

    public function create(): View
    {
        $kos = new Kos([
            'kota' => 'Palembang',
            'status' => 'Tersedia',
            'status_data' => 'perlu_konfirmasi',
            'tipe' => 'Campur',
        ]);

        return view('admin.kos.form', $this->formData($kos));
    }

    public function store(KosRequest $request): RedirectResponse
    {
        $kos = DB::transaction(function () use ($request) {
            $kos = new Kos($this->attributes($request));
            $kos->user_id = $request->user()->id;
            $kos->foto = $this->resolveFoto($request, null);
            $kos->save();
            $this->syncFasilitas($kos, $request);

            return $kos;
        });

        return redirect()->route('kos.show', $kos)
            ->with('success', 'Kos "'.$kos->nama_kos.'" berhasil ditambahkan.');
    }

    public function edit(Kos $kos): View
    {
        $kos->load('fasilitas');

        return view('admin.kos.form', $this->formData($kos));
    }

    public function update(KosRequest $request, Kos $kos): RedirectResponse
    {
        DB::transaction(function () use ($request, $kos) {
            $foto = $this->resolveFoto($request, $kos);
            $kos->fill($this->attributes($request));
            $kos->foto = $foto;
            $kos->save();
            $this->syncFasilitas($kos, $request);
        });

        return redirect()->route('kos.show', $kos)
            ->with('success', 'Perubahan data "'.$kos->nama_kos.'" berhasil disimpan.');
    }

    public function destroy(Kos $kos): RedirectResponse
    {
        $nama = $kos->nama_kos;

        DB::transaction(function () use ($kos) {
            $this->deleteLocalFoto($kos->foto);
            $kos->fasilitas()->delete();
            $kos->delete();
        });

        return redirect()->route('admin.kos.index')
            ->with('success', 'Kos "'.$nama.'" telah dihapus.');
    }

    // ------------------------------------------------------------ helper

    /** @return array<string, mixed> */
    private function formData(Kos $kos): array
    {
        $standar = config('maply.fasilitas');
        $dipilih = $kos->exists ? $kos->fasilitas->pluck('nama_fasilitas')->all() : [];

        return [
            'kos' => $kos,
            'facilityOptions' => $standar,
            'selectedFasilitas' => $dipilih,
            'fasilitasLain' => implode(', ', array_values(array_diff($dipilih, $standar))),
        ];
    }

    /** @return array<string, mixed> */
    private function attributes(KosRequest $request): array
    {
        $v = $request->validated();
        $punyaKoordinat = isset($v['latitude'], $v['longitude']);

        return [
            'nama_kos' => trim($v['nama_kos']),
            'alamat' => trim($v['alamat']),
            'kota' => trim($v['kota']),
            'provinsi' => 'Sumatera Selatan',
            'kecamatan' => $v['kecamatan'] ?? null,
            'latitude' => $punyaKoordinat ? $v['latitude'] : null,
            'longitude' => $punyaKoordinat ? $v['longitude'] : null,
            'koordinat_status' => $punyaKoordinat ? ($v['koordinat_status'] ?? 'akurat') : null,
            'harga' => isset($v['harga']) ? (int) $v['harga'] : null,
            'tipe' => $v['tipe'],
            'status' => $v['status'],
            'deskripsi' => $v['deskripsi'] ?? null,
            'kontak_nama' => $v['kontak_nama'] ?? null,
            'kontak_telepon' => $v['kontak_telepon'] ?? null,
            'sumber_nama' => $v['sumber_nama'] ?? null,
            'sumber_url' => $v['sumber_url'] ?? null,
            'status_data' => $v['status_data'],
            'terakhir_dicek' => $v['terakhir_dicek'] ?? null,
        ];
    }

    private function syncFasilitas(Kos $kos, KosRequest $request): void
    {
        $nama = collect((array) $request->input('fasilitas', []))
            ->merge(explode(',', (string) $request->input('fasilitas_lain', '')))
            ->map(fn ($n) => mb_substr(trim((string) $n), 0, 50))
            ->filter()
            ->unique(fn ($n) => mb_strtolower($n))
            ->values();

        $kos->fasilitas()->delete();
        foreach ($nama as $n) {
            Fasilitas::create(['kos_id' => $kos->id, 'nama_fasilitas' => $n]);
        }
    }

    /** Menentukan nilai kolom foto: unggahan > hapus > URL > nilai lama. */
    private function resolveFoto(KosRequest $request, ?Kos $kos): ?string
    {
        $lama = $kos?->foto;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('kos', 'public');
            $this->deleteLocalFoto($lama);

            return $path;
        }
        if ($request->boolean('hapus_foto')) {
            $this->deleteLocalFoto($lama);

            return null;
        }
        if ($request->filled('foto_url')) {
            if ($lama !== $request->input('foto_url')) {
                $this->deleteLocalFoto($lama);
            }

            return $request->input('foto_url');
        }

        return $lama;
    }

    private function deleteLocalFoto(?string $foto): void
    {
        if ($foto && ! str_starts_with($foto, 'http')) {
            Storage::disk('public')->delete($foto);
        }
    }
}
