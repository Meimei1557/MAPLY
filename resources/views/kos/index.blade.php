@extends('layouts.maply')
@section('title', 'Cari Kos')
@section('body-class', 'page-kos')
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
<style>
/* Maply — Cari Kos (gaya GoFood). Semua aturan di-scope ke body.page-kos. */
.kos-page { padding-bottom: 48px; }

/* Judul */
.kos-hero { display: flex; align-items: center; justify-content: space-between; gap: 12px 20px; flex-wrap: wrap; margin-bottom: 14px; }
.kos-hero__main { min-width: 0; display: flex; flex-wrap: wrap; gap: 8px 14px; align-items: center; }
.kos-hero h1 { font-size: clamp(24px, 3vw, 30px); letter-spacing: -.02em; line-height: 1.15; margin: 0; }
.kos-loc {
  display: inline-flex; align-items: center; gap: 8px; max-width: 100%;
  padding: 7px 14px; border-radius: 999px; border: 1px solid var(--border);
  background: var(--card); color: var(--text);
  font: inherit; font-size: 13.5px; font-weight: 600; line-height: 1.2; cursor: pointer;
  transition: border-color .18s, background .18s;
}
.kos-loc:hover { border-color: var(--neon); background: var(--card-elev); }
.kos-loc > i:first-child { color: var(--neon); }
.kos-loc span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.kos-loc__arrow { font-size: 10px; color: var(--muted-deep); }
.kos-hero__note { font-size: 12.5px; color: var(--warn); margin: 0; }
.kos-hero .btn { flex: none; border-radius: 999px; min-height: 40px; padding: 0 18px; font-size: 14px; }

/* Panel filter — ringkas, lanjutan terlipat, tiap kelompok punya warna (--acc) */
body.page-kos .filter-panel {
  position: static; max-height: none; overflow: visible;
  background: var(--card); border: 1px solid var(--border-soft);
  border-radius: 20px; box-shadow: var(--shadow-sm); margin: 0 0 16px;
}
body.page-kos .filter-panel .filter-form { display: flex; flex-direction: column; gap: 0; padding: 0; }
.kos-bar { display: flex; align-items: center; gap: 8px; padding: 12px 12px 8px; }
.kos-search { flex: 1; min-width: 0; }
body.page-kos .kos-search .input { height: 44px; border-radius: 999px; padding-left: 42px; font-size: 14.5px; background: var(--field); }
body.page-kos .kos-search > i { left: 16px; font-size: 14px; color: var(--neon); }
.kos-bar .btn { height: 44px; min-height: 44px; border-radius: 999px; padding: 0 20px; font-size: 14px; }
.kos-filter-toggle { gap: 8px; background: var(--field); color: var(--text); }
.kos-filter-toggle > i:first-child { color: var(--neon); }
.kos-filter-toggle .chev { font-size: 10px; color: var(--muted-deep); transition: transform .2s; }
body.page-kos .filter-panel.open .kos-filter-toggle .chev { transform: rotate(180deg); }
.kos-filter-toggle .cnt { min-width: 20px; height: 20px; padding: 0 6px; border-radius: 999px; display: inline-grid; place-items: center; background: var(--grad-brand); color: #fff; font-size: 11.5px; font-weight: 700; }

/* chip cepat */
.kos-chiprow { display: flex; align-items: center; gap: 6px; padding: 4px 12px 12px; overflow-x: auto; scrollbar-width: none; }
.kos-chiprow::-webkit-scrollbar { display: none; }
.kos-chiprow__sep { flex: none; width: 1px; height: 20px; background: var(--border); margin: 0 6px; }
.kchip { position: relative; flex: none; cursor: pointer; --acc: #8B3DFF; }
.kchip input { position: absolute; opacity: 0; pointer-events: none; }
.kchip span {
  display: inline-flex; align-items: center; gap: 6px; height: 32px; padding: 0 13px; border-radius: 999px;
  font-size: 13px; font-weight: 600; white-space: nowrap; color: var(--text);
  border: 1px solid color-mix(in srgb, var(--acc) 55%, transparent);
  background: color-mix(in srgb, var(--acc) 18%, transparent);
  transition: background .16s, border-color .16s, color .16s, transform .16s;
}
.kchip:hover span { border-color: var(--acc); transform: translateY(-1px); }
.kchip input:checked + span { background: var(--acc); border-color: var(--acc); color: #fff; box-shadow: 0 4px 12px color-mix(in srgb, var(--acc) 40%, transparent); }
.kchip input:focus-visible + span { outline: 2px solid var(--acc); outline-offset: 2px; }
.kchip--putra { --acc: #3B82F6; }
.kchip--putri { --acc: #EC4899; }
.kchip--campur { --acc: #14B8A6; }
.kchip--fas { --acc: #10B981; }
.kchip--sm span { height: 28px; padding: 0 11px; font-size: 12.5px; font-weight: 500; }

/* filter lanjutan (terlipat) */
.kos-filter__more { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .28s ease; }
body.page-kos .filter-panel.open .kos-filter__more { grid-template-rows: 1fr; }
.kos-filter__inner { overflow: hidden; min-height: 0; visibility: hidden; transition: visibility 0s linear .28s; }
body.page-kos .filter-panel.open .kos-filter__inner { visibility: visible; transition-delay: 0s; }
.kos-filter__pad { display: flex; flex-direction: column; gap: 14px; padding: 14px 14px 16px; border-top: 1px solid var(--border-soft); }
.kos-more-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.kos-more-head strong { font-size: 13px; letter-spacing: .04em; text-transform: uppercase; color: var(--muted); }
.kos-more-actions { display: flex; gap: 8px; }
.kos-more-actions .btn { min-height: 36px; height: 36px; padding: 0 16px; border-radius: 999px; font-size: 13px; }
.kos-fgrid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
.kos-fgrid .span-2 { grid-column: span 2; }
.kos-g { --acc: #8B3DFF; display: flex; flex-direction: column; gap: 5px; min-width: 0; }
.kos-g--ref { --acc: #06B6D4; }
.kos-g--harga { --acc: #F59E0B; }
.kos-g--kec { --acc: #6366F1; }
.kos-g--stat { --acc: #F472B6; }
.kos-g--data { --acc: #A78BFA; }
.kos-g--fas { --acc: #10B981; }
.kos-g > label, .kos-g > .field-label { display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: var(--muted); }
.kos-g > label::before, .kos-g > .field-label::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--acc); flex: none; }
.kos-g .select, .kos-g .input { height: 40px; min-height: 40px; padding-top: 0; padding-bottom: 0; font-size: 14px; border-radius: 12px; background-color: var(--field); border-color: color-mix(in srgb, var(--acc) 40%, transparent); }
.kos-g .select:focus, .kos-g .input:focus { border-color: var(--acc); box-shadow: 0 0 0 3px color-mix(in srgb, var(--acc) 25%, transparent); }
.kos-g .help { font-size: 11.5px; color: var(--muted-deep); }
.kos-chipset { display: flex; flex-wrap: wrap; gap: 6px; }

/* Peta */
body.page-kos .map-card { border-radius: 24px; border: 1px solid var(--border-soft); box-shadow: var(--shadow-sm); overflow: hidden; }
.kos-map__stage { position: relative; }
body.page-kos .map-box { height: clamp(300px, 44vh, 440px); }
.kos-map__actions { position: absolute; top: 12px; right: 12px; z-index: 5; display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
.kos-map__btn { min-height: 38px; padding: 7px 14px; border-radius: 999px; background: var(--card); color: var(--text); border: 1px solid var(--border); box-shadow: 0 4px 14px rgba(10, 5, 22, .35); font-size: 13.5px; }
.kos-map__btn i { color: var(--neon); }
.kos-map__btn:hover { border-color: var(--neon); background: var(--card-elev); }
.kos-map__legend { display: flex; flex-wrap: wrap; gap: 8px 18px; align-items: center; padding: 12px 18px; font-size: 13px; color: var(--muted); border-top: 1px solid var(--border-soft); background: var(--card); }
body.page-kos .map-note { background: var(--card); color: var(--muted); border-top: 1px solid var(--border-soft); padding: 10px 18px; font-size: 13.5px; }
body.page-kos .map-note i { color: var(--neon); }
body.page-kos .map-note--warn { background: var(--warn-bg); color: var(--warn); }
body.page-kos .map-note--warn i { color: var(--warn); }

/* Hasil */
.kos-results { margin-top: 8px; }
body.page-kos .results-head { margin: 26px 0 18px; }
body.page-kos .results-head h2 { font-size: clamp(21px, 2.6vw, 26px); letter-spacing: -.01em; }
body.page-kos .sort-box .select { border-radius: 999px; min-width: 210px; }

/* Kartu kos ala GoFood */
body.page-kos .kos-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 30px 22px; }
body.page-kos .kos-card { position: relative; gap: 12px; background: transparent; border: 0; box-shadow: none; border-radius: 0; overflow: visible; }
body.page-kos .kos-card:hover { transform: translateY(-3px); box-shadow: none; border-color: transparent; }
body.page-kos .kos-card .photo-box { aspect-ratio: 4 / 3; border-radius: 22px; box-shadow: 0 8px 22px rgba(10, 5, 22, .28); transition: box-shadow .25s; }
body.page-kos .kos-card:hover .photo-box { box-shadow: 0 14px 30px rgba(10, 5, 22, .4), 0 0 0 2px var(--neon); }
body.page-kos .kos-card .photo-box .badges { left: 12px; top: 12px; right: 12px; }
body.page-kos .kos-card .photo-box .status-badge { right: 12px; bottom: 12px; }
body.page-kos .kos-card__body { padding: 0 4px; gap: 7px; }
body.page-kos .kos-card h3 { font-size: 17px; font-weight: 700; line-height: 1.3; }
body.page-kos .kos-card h3 a::after { content: ""; position: absolute; inset: 0; z-index: 1; border-radius: 22px; }
body.page-kos .kos-card .loc strong { color: var(--neon); font-weight: 700; }
body.page-kos .kos-card .chip { border-radius: 999px; padding: 3px 11px; font-size: 12px; border-color: transparent; }
body.page-kos .kos-card__foot { margin-top: 2px; padding-top: 6px; border-top: 0; }
body.page-kos .kos-card .price { font-size: 19px; }
body.page-kos .kos-card__actions { position: relative; z-index: 2; }
body.page-kos .kos-card__actions .btn { min-height: 38px; border-radius: 999px; padding: 7px 16px; font-size: 13.5px; }

/* Responsif */
@media (max-width: 1180px) {
  body.page-kos .kos-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .kos-fgrid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 760px) {
  .kos-map__btn { padding: 6px 12px; font-size: 13px; }
}
@media (max-width: 640px) {
  body.page-kos .kos-grid { grid-template-columns: 1fr; gap: 26px; }
  .kos-fgrid { grid-template-columns: 1fr; }
  .kos-bar { flex-wrap: wrap; }
  .kos-search { flex-basis: 100%; }
  .kos-bar .btn { flex: 1; }
  .kos-more-head { flex-wrap: wrap; }
  .kos-fgrid .span-2 { grid-column: auto; }
  body.page-kos .map-box { height: 320px; }
  body.page-kos .sort-box { width: 100%; }
  body.page-kos .sort-box .select { flex: 1; min-width: 0; }
}
</style>
@endpush
@section('content')
@php
    $from = $paginator->total() ? ($paginator->currentPage() - 1) * $paginator->perPage() + 1 : 0;
    $to = $paginator->total() ? $from + $paginator->count() - 1 : 0;
    $tipeOptions = ['' => 'Semua jenis', 'Putra' => 'Putra', 'Putri' => 'Putri', 'Campur' => 'Campur'];
@endphp
<div class="container kos-page">

    {{-- 1. JUDUL --}}
    <header class="kos-hero">
        <div class="kos-hero__main">
            <h1>Temukan kos di sekitarmu</h1>
            <button type="button" class="kos-loc" id="kosLoc" title="Ubah titik acuan">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $ref['singkat'] ?? $ref['nama'] }}</span>
                <i class="fa-solid fa-chevron-down kos-loc__arrow"></i>
            </button>
            @if(!($ref['terverifikasi'] ?? true))<p class="kos-hero__note">Koordinat acuan masih perkiraan.</p>@endif
        </div>
        @can('kelola-kos')<a class="btn btn-primary" href="{{ route('admin.kos.create') }}"><i class="fa-solid fa-plus"></i> Tambah kos</a>@endcan
    </header>

    @include('partials.form-errors')

    {{-- 2. FILTER (ringkas; filter lanjutan terlipat) --}}
    <aside class="filter-panel" id="filterPanel" aria-label="Filter pencarian kos">
        <form class="filter-form" id="filterForm" method="GET" action="{{ route('kos.index') }}">
            <div class="kos-bar">
                <div class="input-icon kos-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="input" id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama kos, alamat, atau kecamatan" maxlength="100" aria-label="Nama atau lokasi">
                </div>
                <button type="button" class="btn btn-ghost kos-filter-toggle" data-toggle-filters aria-expanded="false" aria-controls="filterMore">
                    <i class="fa-solid fa-sliders"></i><span>Filter</span>
                    @if($activeFilters)<span class="cnt">{{ $activeFilters }}</span>@endif
                    <i class="fa-solid fa-chevron-down chev"></i>
                </button>
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>

            <div class="kos-chiprow" role="group" aria-label="Filter cepat">
                <label class="kchip"><input type="radio" name="wilayah" value="" data-autosubmit @checked(! $wilayah)><span>Semua wilayah</span></label>
                @foreach($wilayahOptions as $wk => $w)
                    <label class="kchip"><input type="radio" name="wilayah" value="{{ $wk }}" data-autosubmit @checked($wilayah === $wk)><span>{{ $w['label'] }}</span></label>
                @endforeach
                <span class="kos-chiprow__sep" aria-hidden="true"></span>
                @foreach($tipeOptions as $val => $label)
                    <label class="kchip {{ $val !== '' ? 'kchip--'.strtolower($val) : '' }}"><input type="radio" name="tipe" value="{{ $val }}" data-autosubmit @checked((string) request('tipe', '') === (string) $val)><span>{{ $label }}</span></label>
                @endforeach
            </div>

            <div class="kos-filter__more" id="filterMore">
              <div class="kos-filter__inner">
                <div class="kos-filter__pad">
                    <div class="kos-more-head">
                        <strong>Filter lanjutan</strong>
                        <div class="kos-more-actions">
                            <a class="btn btn-ghost" href="{{ route('kos.index') }}"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Terapkan</button>
                        </div>
                    </div>

                    <div class="kos-fgrid">
                        <div class="kos-g kos-g--ref span-2">
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
                        <div class="kos-g kos-g--ref">
                            <label for="radius">Jarak maksimum</label>
                            <select class="select" id="radius" name="radius">
                                <option value="">Semua jarak</option>
                                @foreach($radiusOptions as $km)<option value="{{ $km }}" @selected($radius !== null && (float) $radius === (float) $km)>Dalam {{ $km }} km</option>@endforeach
                            </select>
                        </div>
                        @if($kecamatanList->isNotEmpty())
                        <div class="kos-g kos-g--kec">
                            <label for="kecamatan">Kecamatan</label>
                            <select class="select" id="kecamatan" name="kecamatan">
                                <option value="">Semua kecamatan</option>
                                @foreach($kecamatanList as $kc)<option value="{{ $kc }}" @selected(request('kecamatan') === $kc)>{{ $kc }}</option>@endforeach
                            </select>
                        </div>
                        @endif
                        <div class="kos-g kos-g--harga">
                            <label for="harga_min">Harga min (Rp/bln)</label>
                            <input class="input" id="harga_min" type="number" name="harga_min" min="0" step="50000" inputmode="numeric" value="{{ $hargaMin }}" placeholder="Min">
                        </div>
                        <div class="kos-g kos-g--harga">
                            <label for="harga_max">Harga maks (Rp/bln)</label>
                            <input class="input" id="harga_max" type="number" name="harga_max" min="0" step="50000" inputmode="numeric" value="{{ $hargaMax }}" placeholder="Maks">
                        </div>
                        <div class="kos-g kos-g--stat">
                            <label for="verifikasi">Status verifikasi</label>
                            <select class="select" id="verifikasi" name="verifikasi">
                                <option value="semua" @selected($verifikasi === 'semua')>Semua status</option>
                                <option value="terverifikasi" @selected($verifikasi === 'terverifikasi')>Terverifikasi</option>
                                <option value="perlu_konfirmasi" @selected($verifikasi === 'perlu_konfirmasi')>Perlu konfirmasi</option>
                            </select>
                        </div>
                        <div class="kos-g kos-g--data">
                            <label for="data">Jenis data</label>
                            <select class="select" id="data" name="data">
                                <option value="semua" @selected($data === 'semua')>Semua data</option>
                                <option value="publik" @selected($data === 'publik')>Hanya dari sumber publik</option>
                                <option value="contoh" @selected($data === 'contoh')>Hanya data contoh</option>
                            </select>
                        </div>
                    </div>

                    <div class="kos-g kos-g--fas">
                        <span class="field-label">Fasilitas</span>
                        <div class="kos-chipset">
                            @foreach($facilityOptions as $f)
                                <label class="kchip kchip--fas kchip--sm"><input type="checkbox" name="fasilitas[]" value="{{ $f }}" @checked(in_array($f, (array) request('fasilitas', []), true))><span>{{ $f }}</span></label>
                            @endforeach
                        </div>
                    </div>
                </div>
              </div>
            </div>
        </form>
    </aside>

    {{-- 3. PETA --}}
    <div class="map-card">
        <div class="kos-map__stage">
            <div id="map" class="map-box"></div>
            <div class="kos-map__actions">
                <button type="button" class="btn btn-sm kos-map__btn" data-map-ref><i class="fa-solid fa-building-columns"></i> Titik acuan</button>
                <button type="button" class="btn btn-sm kos-map__btn" data-map-fit><i class="fa-solid fa-expand"></i> Semua hasil</button>
            </div>
        </div>
        <div class="kos-map__legend">
            <span><span class="legend-dot" style="background:var(--ok)"></span>Terverifikasi</span>
            <span><span class="legend-dot" style="background:var(--warn)"></span>Perlu konfirmasi</span>
            <span><span class="legend-dot" style="background:var(--lavender)"></span>Data contoh</span>
            <span><span class="legend-dot" style="background:var(--violet-deep)"></span>Kampus</span>
            <span><span class="legend-dot" style="background:var(--neon)"></span>Titik acuan</span>
        </div>
        <div class="map-note" id="mapCount" data-expected="{{ $mapItems->count() }}"><i class="fa-solid fa-location-dot"></i> <span>{{ $mapItems->count() }} marker akan ditampilkan di peta</span></div>
        @if($tanpaKoordinat > 0)
            <div class="map-note map-note--warn"><i class="fa-solid fa-triangle-exclamation"></i> {{ $tanpaKoordinat }} kos pada hasil ini belum memiliki koordinat, sehingga tidak muncul di peta.</div>
        @endif
    </div>

    {{-- 4. KOS YANG DITEMUKAN --}}
    <section class="kos-results">
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

    /* Tombol titik acuan di judul: buka filter lanjutan dan fokus ke pilihan titik acuan. */
    (function () {
        var btn = document.getElementById('kosLoc');
        var panel = document.getElementById('filterPanel');
        var sel = document.getElementById('kampus');
        if (!btn || !panel || !sel) return;
        btn.addEventListener('click', function () {
            panel.classList.add('open');
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            setTimeout(function () { sel.focus({ preventScroll: true }); }, 350);
        });
    })();
</script>
@endpush
