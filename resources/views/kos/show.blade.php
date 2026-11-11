@extends('layouts.maply')
@section('title', $kos->nama_kos)
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush
@section('content')
<div class="container">
    <div class="detail-top">
        <a class="back-btn" href="{{ route('kos.index', ['kampus' => $refKey]) }}" data-back><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        <span>Detail kos</span>
    </div>

    <div class="detail-layout">
    <div class="detail-main">

        @php
            $fotoKos = $kos->foto_url
                ?: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&auto=format&fit=crop';
        @endphp

        <div class="detail-photo photo-box">
            <img src="{{ $fotoKos }}" alt="Foto {{ $kos->nama_kos }}" data-fallback>
        </div>

        <div class="panel">


            <div class="panel">
                <div class="detail-title">
                    <div>
                        <div class="badges">
                            <span class="badge badge--{{ $kos->tipe_slug }}">Kos {{ $kos->tipe }}</span>
                            <span class="badge {{ $kos->status === 'Tersedia' ? 'badge--ok' : 'badge--danger' }}">{{ $kos->status === 'Tersedia' ? 'Tersedia' : 'Penuh' }}</span>
                            <span class="badge {{ $kos->status_data === 'terverifikasi' ? 'badge--ok' : ($kos->status_data === 'contoh' ? 'badge--muted' : 'badge--warn') }}">{{ $kos->status_data_label }}</span>
                        </div>
                        <h1>{{ $kos->nama_kos }}</h1>
                    </div>
                    <button type="button" class="icon-btn" data-favorite="{{ $kos->id }}" aria-pressed="false" title="Simpan ke favorit"><i class="fa-regular fa-heart"></i></button>
                </div>
                <p class="detail-addr"><i class="fa-solid fa-location-dot"></i><span>{{ $kos->alamat }}@if($kos->kecamatan), {{ $kos->kecamatan }}@endif, {{ $kos->kota }}</span></p>
            </div>

            @if($kos->status_data !== 'terverifikasi')
                <div class="notice notice--warn">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        @if($kos->is_contoh)
                            <strong>Ini data contoh.</strong> Bukan listing kos yang nyata.
                        @else
                            <strong>Informasi perlu dikonfirmasi.</strong> Data dari sumber publik dan dapat berubah — pastikan harga dan ketersediaan ke pemilik sebelum membayar.
                        @endif
                        @if($kos->sumber_url)
                            <br>Sumber: <a href="{{ $kos->sumber_url }}" target="_blank" rel="noopener noreferrer nofollow">{{ $kos->sumber_nama ?: 'Lihat sumber' }}</a>
                        @endif
                        @if($kos->terakhir_dicek)<br><small>Terakhir dicek: {{ $kos->terakhir_dicek->translatedFormat('j F Y') }}</small>@endif
                    </div>
                </div>
            @endif

            <div class="panel">
                <h2>Deskripsi</h2>
                <p>{{ $kos->deskripsi ?: 'Belum ada deskripsi untuk kos ini.' }}</p>
                <div class="info-list" style="margin-top:16px">
                    <div class="info-item"><span>Jenis kos</span><strong>{{ $kos->tipe }}</strong></div>
                    <div class="info-item"><span>Harga</span><strong>{{ $kos->harga_label }}@if($kos->harga !== null) / bulan @endif</strong></div>
                    <div class="info-item"><span>Jarak ke {{ $ref['singkat'] }}</span><strong>{{ $jarak !== null ? number_format($jarak, 1, ',', '.').' km' : 'Belum diketahui' }}</strong></div>
                    <div class="info-item"><span>Koordinat</span><strong>@if($kos->punya_koordinat){{ $kos->koordinat_label }}@else Belum tersedia @endif</strong></div>
                    @if($kos->catatan_data)<div class="info-item" style="grid-column:1/-1"><span>Catatan kelengkapan data</span><strong style="font-weight:500">{{ $kos->catatan_data }}</strong></div>@endif
                </div>
            </div>

            <div class="panel">
                <h2>Fasilitas</h2>
                @if($kos->fasilitas->isEmpty())
                    <p style="color:var(--muted)">Data fasilitas belum tersedia.</p>
                @else
                    <div class="facility-list">
                        @foreach($kos->fasilitas as $f)<div class="facility"><i class="fa-solid fa-circle-check"></i><span>{{ $f->nama_fasilitas }}</span></div>@endforeach
                    </div>
                @endif
            </div>

            <div class="panel" id="lokasi">
                <h2>Lokasi</h2>
                @if($mapData)
                    <div id="detailMap"></div>
                    @if($kos->koordinat_status === 'perkiraan')<p style="margin-top:10px;font-size:13.5px;color:var(--warn)"><i class="fa-solid fa-circle-info"></i> Titik pada peta adalah perkiraan area/jalan, bukan lokasi bangunan yang pasti.</p>@endif
                @else
                    <div class="no-coord"><i class="fa-solid fa-map-location-dot"></i>
                        <p>Koordinat belum tersedia, jadi penanda tidak bisa ditampilkan di peta.</p></div>
                @endif
                <p style="margin-top:14px"><a class="btn btn-ghost" href="{{ $kos->google_maps_url }}" target="_blank" rel="noopener"><i class="fa-solid fa-map"></i> Buka di Google Maps</a></p>
            </div>

            @if($serupa->isNotEmpty())
                <div>
                    <h2 style="margin-bottom:14px">Kos lain di sekitar</h2>
                    <div class="kos-grid" style="grid-template-columns:repeat(auto-fill,minmax(250px,1fr))">
                        @foreach($serupa as $item)@include('kos._card', ['item' => $item, 'refKey' => $refKey])@endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <aside class="side-card">
            <div class="panel">
                @if($kos->harga !== null)
                    <div class="big-price">{{ $kos->harga_label }} <small>/ bulan</small></div>
                @else
                    <div class="big-price unknown">Harga belum diketahui</div>
                @endif
                <div class="side-actions" style="margin-top:16px">
                    @if($kos->whatsapp_url)
                        <a class="btn btn-primary btn-block" href="{{ $kos->whatsapp_url }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Hubungi {{ $kos->kontak_nama ?: 'pemilik' }}</a>
                    @else
                        <div class="notice notice--info" style="font-size:13.5px"><i class="fa-solid fa-circle-info"></i><div>Kontak pemilik belum tersedia pada data ini.@if($kos->sumber_url) Cek halaman sumber untuk informasi kontak.@endif</div></div>
                    @endif
                    @if($kos->sumber_url)
                        <a class="btn btn-ghost btn-block" href="{{ $kos->sumber_url }}" target="_blank" rel="noopener noreferrer nofollow"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat sumber listing</a>
                    @endif
                    <div class="row">
                        @if($mapData)<button type="button" class="btn btn-ghost" data-scroll-map><i class="fa-solid fa-location-dot"></i> Lihat di peta</button>@endif
                        <button type="button" class="btn btn-ghost" data-share data-title="{{ $kos->nama_kos }}"><i class="fa-solid fa-share-nodes"></i> Bagikan</button>
                    </div>
                    <p class="action-feedback" id="actionFeedback" aria-live="polite"></p>
                </div>
            </div>

            @can('kelola-kos')
                <div class="admin-bar">
                    <span><i class="fa-solid fa-user-shield"></i> Administrator</span>
                    <div>
                        <a class="btn btn-light btn-sm" href="{{ route('admin.kos.edit', $kos) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" data-confirm-delete data-action="{{ route('admin.kos.destroy', $kos) }}" data-name="{{ $kos->nama_kos }}"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                    </div>
                </div>
            @endcan
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/maply-map.js') }}"></script>
@if($mapData)
@php $refJs = collect($ref)->only(['lat', 'lng', 'nama', 'singkat', 'terverifikasi'])->all(); @endphp
<script>
    MaplyMap.initDetail({
        id: 'detailMap',
        kos: @json($mapData),
        ref: @json($refJs)
    });
</script>
@endif
@endpush
