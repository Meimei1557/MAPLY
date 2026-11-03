@extends('layouts.maply')
@section('title', 'Peta Koordinat')
@section('content')
<div class="container">
    <div class="page-head">
        <div><span class="eyebrow"><i class="fa-solid fa-user-shield"></i> Administrator</span><h1>Pengelolaan Koordinat Peta</h1>
            <p>Geocoding menghasilkan kandidat koordinat, bukan bukti bahwa pin tepat pada bangunan. Periksa hasil di peta dan tandai verifikasi hanya setelah diperiksa.</p></div>
        <a class="btn btn-ghost" href="{{ route('admin.kos.index') }}"><i class="fa-solid fa-arrow-left"></i> Kelola kos</a>
    </div>

    <div class="stat-mini-grid">
        @foreach ([['Total kost', $total, 'fa-house-chimney'], ['Koordinat valid', $valid, 'fa-circle-check'], ['Belum ada koordinat', $kosong, 'fa-circle-minus'], ['Perlu pemeriksaan', $review, 'fa-triangle-exclamation']] as [$label, $value, $icon])
            <article class="stat-mini"><i class="fa-solid {{ $icon }}"></i><b>{{ number_format($value, 0, ',', '.') }}</b><span>{{ $label }}</span></article>
        @endforeach
    </div>

    <div class="detail-layout" style="grid-template-columns:1fr 1fr;align-items:start">
        <div class="panel">
            <h2>Status geocoding</h2>
            @if($statusCounts->isEmpty())
                <p style="color:var(--muted)">Jalankan <code>php artisan migrate</code> untuk menambahkan kolom pelacakan geocoding.</p>
            @else
                <div class="info-list">
                    @foreach($statusCounts as $status => $jumlah)
                        <div class="info-item"><span>{{ $status ?: 'belum diproses' }}</span><strong>{{ number_format($jumlah, 0, ',', '.') }}</strong></div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="panel">
            <h2>Mulai proses geocoding</h2>
            <p style="color:var(--muted);font-size:14px;margin-bottom:12px">Jalankan dari terminal di folder proyek. Perintah ini hanya mengambil data tanpa koordinat dan tidak menimpa koordinat yang sudah ada. Nominatim publik dibatasi satu permintaan per detik; gunakan batch kecil.</p>
            <pre class="code-block">php artisan maply:geocode-kos --limit=20</pre>
            <p style="color:var(--muted);font-size:14px;margin:12px 0">Untuk mencoba kembali data gagal/tidak ditemukan:</p>
            <pre class="code-block">php artisan maply:geocode-kos --limit=20 --retry</pre>
        </div>
    </div>

    <section class="section">
        <div class="section__head"><div><span class="eyebrow"><i class="fa-solid fa-clipboard-check"></i> Tinjauan</span><h2>Data yang perlu ditinjau</h2></div></div>
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Kost</th><th>Alamat</th><th>Status</th><th>Tindakan</th></tr></thead>
                <tbody>
                @forelse($recent as $kos)
                    <tr>
                        <td data-label="Kost">{{ $kos->nama_kos }}</td>
                        <td data-label="Alamat">{{ $kos->alamat }}, {{ $kos->kota }}</td>
                        <td data-label="Status"><span class="badge badge--warn">{{ $kos->geocoding_status ?? 'belum diproses' }}</span></td>
                        <td data-label="Tindakan"><div class="actions">
                            <a class="btn btn-ghost btn-sm" href="{{ route('admin.kos.edit', $kos) }}"><i class="fa-solid fa-pen"></i> Periksa / edit koordinat</a>
                            <a class="btn btn-ghost btn-sm" target="_blank" rel="noopener" href="{{ $kos->google_maps_url }}"><i class="fa-solid fa-map"></i> Google Maps</a>
                        </div></td>
                    </tr>
                @empty <tr><td colspan="4" style="padding:.9rem;color:var(--muted)">Tidak ada data untuk ditinjau.</td></tr> @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
