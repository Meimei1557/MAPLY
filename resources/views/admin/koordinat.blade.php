@extends('layouts.maply')
@section('title', 'Peta Koordinat')
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
@endpush
@section('content')
<div class="container">
    <div class="page-head">
        <div><span class="eyebrow"><i class="fa-solid fa-user-shield"></i> Administrator</span><h1>Peta Koordinat</h1>
            <p>Periksa konsistensi: marker di peta ini memakai koordinat database yang sama dengan tombol Google Maps pada setiap kos.</p></div>
    </div>

    <div class="stats">
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-map-pin"></i></span><div><strong>{{ $mapItems->count() }}</strong><span>Kos dengan koordinat (peta)</span></div></div>
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-circle-minus"></i></span><div><strong>{{ $tanpaKoordinat }}</strong><span>Kos tanpa koordinat</span></div></div>
        <div class="stat"><span class="stat__icon"><i class="fa-solid fa-triangle-exclamation"></i></span><div><strong>{{ $perkiraan }}</strong><span>Koordinat perkiraan</span></div></div>
        <div class="stat"><span class="stat__icon"><i class="fa-brands fa-google"></i></span><div><strong>{{ $google }}</strong><span>Pin dari sumber listing</span></div></div>
    </div>

    <div class="map-card">
        <div class="map-toolbar">
            <div class="map-toolbar__legend">
                <span><span class="legend-dot" style="background:var(--ok)"></span>Terpercaya</span>
                <span><span class="legend-dot" style="background:var(--warn)"></span>Perlu konfirmasi</span>
                <span><span class="legend-dot" style="background:var(--lavender)"></span>Data contoh</span>
                <span><span class="legend-dot" style="background:var(--neon)"></span>Kampus</span>
            </div>
            <button type="button" class="btn btn-ghost btn-sm" data-map-fit><i class="fa-solid fa-expand"></i> Tampilkan semua</button>
        </div>
        <div id="adminMap" class="map-box"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="{{ asset('js/maply-map.js') }}"></script>
<script>
    MaplyMap.initList({
        id: 'adminMap',
        ref: @json($pusatJs),
        items: @json($mapItems),
        showRef: false
    });
</script>
@endpush
