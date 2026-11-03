@extends('layouts.maply')
@section('title', 'Cari Kos')
@section('body-class', 'page-kos')
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
@endpush
@section('content')
@php
    $from = $paginator->total() ? ($paginator->currentPage() - 1) * $paginator->perPage() + 1 : 0;
    $to = $paginator->total() ? $from + $paginator->count() - 1 : 0;
@endphp
<div class="container">
    <div class="page-head">
        <div>
            <h1>Cari Kos</h1>
            <p>Di sekitar <strong>{{ $ref['nama'] }}</strong>@if(!($ref['terverifikasi'] ?? true)) · koordinat acuan perkiraan @endif</p>
        </div>
        @can('kelola-kos')<a class="btn btn-primary" href="{{ route('admin.kos.create') }}"><i class="fa-solid fa-plus"></i> Tambah kos</a>@endcan
    </div>

    @include('partials.form-errors')

    <div class="search-layout">
        <aside class="filter-panel" id="filterPanel">
            <div class="filter-panel__head" data-toggle-filters>
                <h2><i class="fa-solid fa-sliders" style="color:var(--neon)"></i> Filter @if($activeFilters)<span class="badge">{{ $activeFilters }} aktif</span>@endif</h2>
                <button type="button" class="btn btn-ghost btn-sm filter-toggle" data-toggle-filters aria-expanded="false" aria-controls="filterForm">Tampilkan / sembunyikan</button>
            </div>
            <form class="filter-form" id="filterForm" method="GET" action="{{ route('kos.index') }}">
                <div class="fgroup">
                    <label for="q">Nama atau lokasi</label>
                    <div class="input-icon"><i class="fa-solid fa-magnifying-glass"></i>
                        <input class="input" id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Nama kos, alamat, atau kecamatan" maxlength="100"></div>
                </div>
                <div class="fgroup">
                    <span class="field-label">Wilayah</span>
                    <div class="pill-group">
                        <label class="pill"><input type="radio" name="wilayah" value="" @checked(! $wilayah)><span>Semua</span></label>
                        @foreach($wilayahOptions as $wk => $w)
                            <label class="pill"><input type="radio" name="wilayah" value="{{ $wk }}" @checked($wilayah === $wk)><span>{{ $w['label'] }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div class="fgroup">
                    <label for="kampus">Titik acuan jarak</label>
                    <select class="select" id="kampus" name="kampus">
                        <optgroup label="Kampus">
                            @foreach($references['campus'] as $key => $r)<option value="{{ $key }}" @selected($refKey === $key)>{{ $r['singkat'] }}</option>@endforeach
                        </optgroup>
                        <optgroup label="Titik kota & area">
                            @foreach($references['kota'] as $key => $r)<option value="{{ $key }}" @selected($refKey === $key)>{{ $r['singkat'] }}</option>@endforeach
                        </optgroup>
                    </select>
                    <span class="help">Kampus lengkap: <a href="{{ route('kampus.index') }}">lihat daftar</a></span>
                </div>
                <div class="fgroup">
                    <label for="radius">Jarak maksimum</label>
                    <select class="select" id="radius" name="radius">
                        <option value="">Semua jarak</option>
                        @foreach($radiusOptions as $km)<option value="{{ $km }}" @selected($radius !== null && (float) $radius === (float) $km)>Dalam {{ $km }} km</option>@endforeach
                    </select>
                </div>
                <div class="fgroup">
                    <span class="field-label">Harga per bulan (Rp)</span>
                    <div class="two-col">
                        <input class="input" type="number" name="harga_min" min="0" step="50000" inputmode="numeric" value="{{ $hargaMin }}" placeholder="Min" aria-label="Harga minimum">
                        <input class="input" type="number" name="harga_max" min="0" step="50000" inputmode="numeric" value="{{ $hargaMax }}" placeholder="Maks" aria-label="Harga maksimum">
                    </div>
                </div>
                <div class="fgroup">
                    <span class="field-label">Jenis kos</span>
                    <div class="pill-group">
                        @foreach(['' => 'Semua', 'Putra' => 'Putra', 'Putri' => 'Putri', 'Campur' => 'Campur'] as $val => $label)
                            <label class="pill"><input type="radio" name="tipe" value="{{ $val }}" @checked((string) request('tipe', '') === (string) $val)><span>{{ $label }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div class="fgroup">
                    <span class="field-label">Fasilitas</span>
                    <div class="pill-group">
                        @foreach($facilityOptions as $f)
                            <label class="pill"><input type="checkbox" name="fasilitas[]" value="{{ $f }}" @checked(in_array($f, (array) request('fasilitas', []), true))><span>{{ $f }}</span></label>
                        @endforeach
                    </div>
                </div>
                @if($kecamatanList->isNotEmpty())
                <div class="fgroup">
                    <label for="kecamatan">Kecamatan</label>
                    <select class="select" id="kecamatan" name="kecamatan">
                        <option value="">Semua kecamatan</option>
                        @foreach($kecamatanList as $kc)<option value="{{ $kc }}" @selected(request('kecamatan') === $kc)>{{ $kc }}</option>@endforeach
                    </select>
                </div>
                @endif
                <div class="fgroup">
                    <label for="verifikasi">Status verifikasi</label>
                    <select class="select" id="verifikasi" name="verifikasi">
                        <option value="semua" @selected($verifikasi === 'semua')>Semua status</option>
                        <option value="terverifikasi" @selected($verifikasi === 'terverifikasi')>Terverifikasi</option>
                        <option value="perlu_konfirmasi" @selected($verifikasi === 'perlu_konfirmasi')>Perlu konfirmasi</option>
                    </select>
                </div>
                <div class="fgroup">
                    <label for="data">Jenis data</label>
                    <select class="select" id="data" name="data">
                        <option value="semua" @selected($data === 'semua')>Semua data</option>
                        <option value="publik" @selected($data === 'publik')>Hanya dari sumber publik</option>
                        <option value="contoh" @selected($data === 'contoh')>Hanya data contoh</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Terapkan</button>
                    <a class="btn btn-ghost" href="{{ route('kos.index') }}"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                </div>
            </form>
        </aside>

        <section>
            <div class="map-card">
                <div class="map-toolbar">
                    <div class="map-toolbar__legend">
                        <span><span class="legend-dot" style="background:var(--ok)"></span>Terverifikasi</span>
                        <span><span class="legend-dot" style="background:var(--warn)"></span>Perlu konfirmasi</span>
                        <span><span class="legend-dot" style="background:var(--lavender)"></span>Data contoh</span>
                        <span><span class="legend-dot" style="background:var(--violet-deep)"></span>Kampus</span>
                        <span><span class="legend-dot" style="background:var(--neon)"></span>Titik acuan</span>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        <button type="button" class="btn btn-ghost btn-sm" data-map-ref><i class="fa-solid fa-building-columns"></i> Titik acuan</button>
                        <button type="button" class="btn btn-ghost btn-sm" data-map-fit><i class="fa-solid fa-expand"></i> Semua hasil</button>
                    </div>
                </div>
                <div id="map" class="map-box"></div>
                <div class="map-note" id="mapCount" data-expected="{{ $mapItems->count() }}"><i class="fa-solid fa-location-dot"></i> <span>{{ $mapItems->count() }} marker akan ditampilkan di peta</span></div>
                @if($tanpaKoordinat > 0)
                    <div class="map-note"><i class="fa-solid fa-triangle-exclamation"></i> {{ $tanpaKoordinat }} kos pada hasil ini belum memiliki koordinat, sehingga tidak muncul di peta.</div>
                @endif
            </div>

            <div class="results-head">
                <div>
                    <h2>{{ $total }} kos ditemukan</h2>
                    @if($total > 0)<p>Menampilkan {{ $from }}–{{ $to }}@if($radius) · radius {{ rtrim(rtrim(number_format($radius, 1, ',', '.'), '0'), ',') }} km @endif</p>@endif
                </div>
                <label class="sort-box">Urutkan
                    <select class="select" name="urut" form="filterForm" data-autosubmit aria-label="Urutkan hasil">
                        @foreach($urutOptions as $val => $label)<option value="{{ $val }}" @selected($urut === $val)>{{ $label }}</option>@endforeach
                    </select>
                </label>
            </div>

            @if($total === 0)
                <div class="empty">
                    <div class="ico"><i class="fa-solid fa-house-circle-xmark"></i></div>
                    @if($activeFilters)
                        <h3>Belum ada kos yang cocok</h3>
                        <p>Coba longgarkan filter harga, jarak, atau fasilitas, atau gunakan kata kunci lain.</p>
                        <a class="btn btn-primary" href="{{ route('kos.index') }}"><i class="fa-solid fa-rotate-left"></i> Reset filter</a>
                    @else
                        <h3>Belum ada data kos</h3>
                        <p>Database masih kosong. @can('kelola-kos')Tambahkan kos pertama atau jalankan seeder.@endcan</p>
                        @can('kelola-kos')<a class="btn btn-primary" href="{{ route('admin.kos.create') }}"><i class="fa-solid fa-plus"></i> Tambah kos</a>@endcan
                    @endif
                </div>
            @else
                <div class="kos-grid">
                    @foreach($paginator as $item)@include('kos._card', ['item' => $item, 'refKey' => $refKey, 'withMapButton' => true])@endforeach
                </div>
                {{ $paginator->onEachSide(1)->links('pagination.maply') }}
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="{{ asset('js/maply-map.js') }}"></script>
@php $refJs = collect($ref)->only(['lat', 'lng', 'nama', 'singkat', 'terverifikasi'])->all(); @endphp
<script>
    MaplyMap.initList({
        id: 'map',
        ref: @json($refJs),
        campuses: @json($kampusPeta),
        items: @json($mapItems),
        radiusKm: @json($radius),
        showRef: true,
        countEl: 'mapCount'
    });
</script>
@endpush
