@extends('layouts.maply')
@section('title', 'Beranda')
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
@endpush
@section('content')
@php $user = auth()->user(); @endphp
<div class="container">
    {{-- ================= HERO ================= --}}
    <section class="hero">
        <div class="hero__grid">
            <div>
                <span class="hero__hi"><i class="fa-solid fa-location-dot wave"></i> Palembang &amp; Indralaya</span>
                <h1>Selamat datang, <em>{{ $user->name }}</em>.</h1>

                <form class="search-card" method="GET" action="{{ route('kos.index') }}">
                    <div class="field field-q"><label for="hq">Nama / lokasi</label><input id="hq" type="search" name="q" placeholder="Mis. Bukit Besar, Demang…" maxlength="100"></div>
                    <div class="field"><label for="hk">Dekat</label>
                        <select id="hk" name="kampus">
                            <optgroup label="Kampus">
                                @foreach($kampusCards as $key => $r)<option value="{{ $key }}">{{ $r['singkat'] }}</option>@endforeach
                            </optgroup>
                            <optgroup label="Titik kota & area">
                                @foreach($titikKota as $key => $r)<option value="{{ $key }}">{{ $r['singkat'] }}</option>@endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="field"><label for="ht">Jenis kos</label>
                        <select id="ht" name="tipe"><option value="">Semua</option><option>Putra</option><option>Putri</option><option>Campur</option></select>
                    </div>
                    <div class="field"><label for="hh">Harga maks / bulan</label>
                        <select id="hh" name="harga_max"><option value="">Berapa saja</option><option value="700000">≤ Rp700 rb</option><option value="1000000">≤ Rp1 juta</option><option value="1500000">≤ Rp1,5 juta</option><option value="2000000">≤ Rp2 juta</option></select>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                </form>

                <div class="quick-chips">
                    <a href="{{ route('kos.index', ['tipe' => 'Putri']) }}">Kos Putri</a>
                    <a href="{{ route('kos.index', ['tipe' => 'Putra']) }}">Kos Putra</a>
                    <a href="{{ route('kos.index', ['tipe' => 'Campur']) }}">Kos Campur</a>
                    <a href="{{ route('kos.index', ['harga_max' => 1000000, 'urut' => 'termurah']) }}">Di bawah Rp1 juta</a>
                    <a href="{{ route('kos.index', ['fasilitas' => ['AC']]) }}">Ber-AC</a>
                    <a href="{{ route('kos.index', ['radius' => 3]) }}" data-radius-chip>Radius 3 km</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= STATISTIK (data aktual) ================= --}}
    <div class="stats reveal-up">
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-house-chimney"></i></span><div><strong>{{ $stats['total'] }}</strong><span>Kos tersedia</span></div></div>
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-link"></i></span><div><strong>{{ $stats['publik'] }}</strong><span>Dari sumber publik</span></div></div>
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-city"></i></span><div><strong>{{ $stats['kecamatan'] }}</strong><span>Kecamatan tercakup</span></div></div>
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-map-pin"></i></span><div><strong>{{ $stats['di_peta'] }}</strong><span>Titik di peta</span></div></div>
    </div>

    {{-- ================= PETA ================= --}}
    <section class="section" id="peta">
        <div class="section__head">
            <div><span class="eyebrow"><i class="fa-solid fa-map-location-dot"></i> Peta</span><h2>Sebaran kos &amp; kampus</h2></div>
            <a class="link-search-full" href="{{ route('kos.index') }}">
                <i class="fa-solid fa-magnifying-glass"></i>
                Cari kos
            </a>
        </div>
        <div class="map-card">
            <div class="map-toolbar">
                <div class="map-toolbar__legend">
                    <span><span class="legend-dot" style="background:var(--ok)"></span>Terverifikasi</span>
                    <span><span class="legend-dot" style="background:var(--warn)"></span>Perlu konfirmasi</span>
                    <span><span class="legend-dot" style="background:var(--lavender)"></span>Data contoh</span>
                    <span><span class="legend-dot" style="background:var(--violet-deep)"></span>Kampus</span>
                    <span><span class="legend-dot" style="background:var(--neon)"></span>Titik acuan</span>
                </div>
                <button type="button" class="btn btn-ghost btn-sm" data-map-fit><i class="fa-solid fa-expand"></i> Tampilkan semua</button>
            </div>
            <div id="homeMap" class="map-box"></div>
            @if($mapItems->isEmpty())
                <div class="map-note"><i class="fa-solid fa-circle-info"></i> Belum ada kos dengan koordinat. Penanda akan muncul setelah koordinat ditambahkan.</div>
            @endif
        </div>
    </section>

    {{-- ================= KAMPUS & AREA (kartu bulat berlogo) ================= --}}
    <section class="section" id="titik-acuan">
        <div class="section__head">
            <div><span class="eyebrow"><i class="fa-solid fa-graduation-cap"></i> Kampus &amp; area</span><h2>Cari dekat lokasi yang kamu tuju</h2></div>
            <a class="link-arrow" href="{{ route('kampus.index') }}">Semua kampus <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="ref-grid ref-grid--kampus">
            @foreach($kampusCards as $key => $r)
                @php
                    // Warna lingkaran inisial konsisten: hash sederhana dari kode kampus
                    $initIdx = abs(crc32($key)) % 6;
                    $initial = mb_strtoupper(mb_substr(trim($r['singkat']), 0, 1));
                @endphp
                <a class="ref-card ref-card--kampus {{ $r['terverifikasi'] ? '' : 'ref-card--no-loc' }}" href="{{ route('kos.index', ['kampus' => $key]) }}" title="{{ $r['nama'] }}">
                    <span class="ref-circle">
                        @if($r['logo'])
                            <img src="{{ asset($r['logo']) }}" alt="Logo {{ $r['singkat'] }}" loading="lazy" width="84" height="84">
                        @else
                            <span class="ref-initial init-{{ $initIdx }}">{{ $initial }}</span>
                        @endif
                    </span>
                    <h3>{{ $r['singkat'] }}</h3>
                    @unless($r['terverifikasi'])<small><i class="fa-solid fa-triangle-exclamation"></i> Koordinat belum terverifikasi</small>@endunless
                </a>
            @endforeach
        </div>
    </section>

    {{-- ================= KOS UNGGULAN ================= --}}
    <section class="section">
        <div class="section__head">
            <div><span class="eyebrow"><i class="fa-solid fa-star"></i> Pilihan</span><h2>Kos untuk dilihat lebih dulu</h2></div>
            <a class="link-arrow" href="{{ route('kos.index') }}">Lihat semua <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        @if($unggulan->isEmpty())
            <div class="empty"><div class="ico"><i class="fa-solid fa-house-circle-xmark"></i></div><h3>Belum ada data kos</h3>
                <p>Database masih kosong. @can('kelola-kos')Tambahkan kos pertama atau jalankan seeder.@else Silakan kembali lagi nanti.@endcan</p>
                @can('kelola-kos')<a class="btn btn-primary" href="{{ route('admin.kos.create') }}"><i class="fa-solid fa-plus"></i> Tambah kos</a>@endcan</div>
        @else
            <div class="kos-grid">
                @foreach($unggulan as $item)@include('kos._card', ['item' => $item, 'refKey' => config('maply.titik_default')])@endforeach
            </div>
        @endif
    </section>

    {{-- ================= TRANSPARANSI DATA ================= --}}
    <section class="section">
        <div class="section__head"><div><span class="eyebrow"><i class="fa-solid fa-shield-halved"></i> Transparansi data</span><h2>Bagaimana informasi kos dikelola</h2></div></div>
        <div class="info-grid">
            <div class="info-card"><div class="ico"><i class="fa-solid fa-link"></i></div><h3>Bersumber publik</h3>
                <p>Disertai tautan sumber. Harga dan fasilitas dapat berubah.</p><span class="badge badge--warn">Perlu dikonfirmasi</span></div>
            <div class="info-card"><div class="ico"><i class="fa-solid fa-circle-check"></i></div><h3>Terverifikasi</h3>
                <p>Ditandai admin setelah dikonfirmasi ke pemilik atau lokasi.</p><span class="badge badge--ok">Terverifikasi</span></div>
            <div class="info-card"><div class="ico"><i class="fa-solid fa-flask"></i></div><h3>Data contoh</h3>
                <p>Dipakai untuk demonstrasi fitur. Bukan listing nyata.</p><span class="badge badge--muted">Data contoh</span></div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="{{ asset('js/maply-map.js') }}"></script>
<script>
    MaplyMap.initList({
        id: 'homeMap',
        ref: @json($refDefault),
        items: @json($mapItems),
        campuses: @json($kampusPeta),
        showRef: true
    });
</script>
@endpush
