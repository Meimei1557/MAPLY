@extends('layouts.maply')
@section('title', 'Impor Berkas Kost')
@section('content')
@php($impor = $preview)
<div class="container" style="max-width:900px">
    <div class="detail-top">
        <a href="{{ route('admin.ai-kos.index') }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i> AI Kost Manager</a>
        <span>Impor data</span>
    </div>
    <div class="page-head" style="padding-top:0"><div><span class="eyebrow"><i class="fa-solid fa-file-import"></i> Impor data</span><h1>Impor Berkas JSON/CSV</h1><p>Unggah daftar kandidat kost dari berkas publik. Semua baris menjadi kandidat — bukan listing final — dan melewati Tahap 2 &amp; 3 seperti pencarian biasa.</p></div></div>

    @if(!$impor)
        <div class="panel">
            <h2>Langkah 1 — Unggah berkas</h2>
            <p style="color:var(--muted);font-size:14px;margin:6px 0 14px">Format JSON (array of object) atau CSV dengan header. Skema mengikuti <code>database/data/kos_publik.json</code>:</p>
            <pre class="code-block">{
  "nama_kos": "Kost Contoh",          // WAJIB, tidak boleh kosong
  "alamat": "Jl. Contoh No. 1",
  "kecamatan": "Ilir Barat I",
  "kota": "Palembang",
  "latitude": -2.986444,
  "longitude": 104.734333,
  "harga": 700000,
  "tipe": "Putri",                    // Putra | Putri | Campur
  "fasilitas": ["WiFi", "AC"],        // atau "WiFi|AC"
  "telepon": "081234567890",
  "website": "https://contoh.com",
  "sumber_nama": "Nama Situs",
  "sumber_url": "https://situs.com/iklan"
}</pre>
            <form method="POST" action="{{ route('admin.ai-kos.import.preview') }}" enctype="multipart/form-data" style="margin-top:16px">
                @csrf
                <div class="two-col">
                    <div class="fgroup"><label>Berkas (JSON / CSV, maks {{ number_format(config('ai_pipeline.import.max_size_kb', 5120) / 1024, 1) }} MB, maks {{ number_format(config('ai_pipeline.import.max_rows', 2000)) }} baris)</label><input class="input" type="file" name="berkas" accept=".json,.csv,.txt" required></div>
                    <div class="fgroup"><label>Wilayah default</label><select class="select" name="region"><option value="palembang">Palembang</option><option value="indralaya">Indralaya, Ogan Ilir</option></select></div>
                </div>
                <div style="margin-top:14px"><button class="btn btn-primary" type="submit"><i class="fa-solid fa-file-arrow-up"></i> Unggah &amp; Periksa</button></div>
                @error('berkas')<p class="flash warning" style="margin-top:10px">{{ $message }}</p>@enderror
                @error('region')<p class="flash warning" style="margin-top:10px">{{ $message }}</p>@enderror
            </form>
        </div>
    @else
        <div class="panel" style="margin-bottom:20px">
            <h2>Langkah 2 — Preview ({{ min(10, $impor['total']) }} dari {{ $impor['total'] }} baris)</h2>
            <p style="color:var(--muted);font-size:14px;margin:6px 0 14px">Berkas: <strong>{{ $impor['original'] }}</strong>@if($impor['skipped'] > 0) · <span style="color:var(--warn)">{{ $impor['skipped'] }} baris dilewati (tanpa nama_kos)</span>@endif</p>
            <div class="table-scroll"><table class="table"><thead><tr><th>Nama</th><th>Alamat</th><th>Kota</th><th>Harga</th><th>Tipe</th><th>Koordinat</th><th>Sumber</th></tr></thead><tbody>
            @foreach($impor['preview'] as $baris)
                <tr>
                    <td><strong>{{ $baris['nama_kos'] }}</strong></td>
                    <td>{{ $baris['alamat'] ?? '—' }}</td>
                    <td>{{ $baris['kota'] ?? '—' }}</td>
                    <td>{{ isset($baris['harga']) && $baris['harga'] !== null ? 'Rp ' . number_format($baris['harga'], 0, ',', '.') : '—' }}</td>
                    <td>{{ $baris['tipe'] ?? '—' }}</td>
                    <td>{{ $baris['latitude'] !== null ? $baris['latitude'] . ', ' . $baris['longitude'] : '—' }}</td>
                    <td>{{ $baris['sumber_nama'] ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody></table></div>
            <form method="POST" action="{{ route('admin.ai-kos.import.confirm') }}" style="display:flex;gap:10px;margin-top:16px">
                @csrf
                <button class="btn btn-primary" type="submit" onclick="return confirm('Jalankan impor sebagai pencarian AI? Kandidat akan melewati Tahap 1-3 otomatis.')"><i class="fa-solid fa-play"></i> Konfirmasi &amp; Jalankan Impor</button>
            </form>
            <form method="POST" action="{{ route('admin.ai-kos.import.cancel') }}" style="margin-top:10px">
                @csrf
                <button class="btn btn-ghost btn-sm" type="submit">Batalkan impor</button>
            </form>
        </div>
    @endif
</div>
@endsection
