@extends('layouts.maply')
@section('title', 'Beranda')
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
<style>
    /* Tombol ditempatkan di kolom tersendiri agar tidak menutupi logo kampus. */
    .campus-carousel-wrap {
        display: grid;
        grid-template-columns: 38px minmax(0, 1fr) 38px;
        align-items: center;
        column-gap: 10px;
        width: 100%;
    }

    .campus-carousel-arrow {
        position: relative;
        z-index: 2;
        display: grid;
        place-items: center;
        width: 38px;
        height: 38px;
        padding: 0;
        border: 1px solid rgba(181, 108, 255, .55);
        border-radius: 50%;
        background: rgba(24, 10, 43, .94);
        color: #fff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .24);
        cursor: pointer;
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
    }

    .campus-carousel-arrow:hover {
        background: #963bfa;
        transform: scale(1.08);
        box-shadow: 0 5px 18px rgba(150, 59, 250, .3);
    }

    .campus-carousel-arrow:focus-visible {
        outline: 2px solid #d9b5ff;
        outline-offset: 3px;
    }

    .campus-carousel {
        min-width: 0;
        width: 100%;
    }

    @media (max-width: 600px) {
        .campus-carousel-wrap {
            grid-template-columns: 32px minmax(0, 1fr) 32px;
            column-gap: 6px;
        }

        .campus-carousel-arrow {
            width: 32px;
            height: 32px;
        }
    }
</style>
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

    {{-- ================= KAMPUS & AREA ================= --}}
    <section class="section campus-picker" id="titik-acuan">
        <div class="section__head campus-picker__head">
            <div><span class="eyebrow"><i class="fa-solid fa-graduation-cap"></i> Kampus &amp; area</span><h2>Cari kos dekat tujuanmu</h2><p>Pilih kampus tujuanmu.</p></div>
            <a class="link-arrow" href="{{ route('kampus.index') }}">Lihat semua kampus <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="campus-picker__panel">
            <div class="campus-carousel-wrap">
                <button type="button" class="campus-carousel-arrow campus-carousel-arrow--left" id="campusPrev" aria-label="Geser daftar kampus ke kiri" title="Kampus sebelumnya">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="campus-carousel" id="campusCarousel" tabindex="0" aria-label="Daftar kampus">
                    @foreach($kampusCards as $key => $r)
                        <a class="campus-option {{ $r['terverifikasi'] ? '' : 'is-unverified' }}" href="{{ route('kos.index', ['kampus' => $key]) }}" data-campus-option data-name="{{ $r['singkat'] }}" aria-label="Pilih {{ $r['nama'] }}" title="{{ $r['nama'] }}">
                            <span class="campus-option__logo">
                                @if($r['logo'])
                                    <img src="{{ preg_match('/^https?:\/\//i', $r['logo']) ? $r['logo'] : asset($r['logo']) }}" alt="Logo resmi {{ $r['singkat'] }}" loading="lazy" width="100" height="100" onerror="this.hidden=true; this.nextElementSibling.hidden=false">
                                    <span class="campus-logo-placeholder" aria-label="Logo belum tersedia" hidden><i class="fa-solid fa-graduation-cap"></i></span>
                                @else
                                    <span class="campus-logo-placeholder" aria-label="Logo belum tersedia"><i class="fa-solid fa-graduation-cap"></i></span>
                                @endif
                            </span>
                            <span class="campus-option__name">{{ $r['singkat'] }}</span>
                            <span class="campus-option__check"><i class="fa-solid fa-check"></i></span>
                        </a>
                    @endforeach
                </div>
                <button type="button" class="campus-carousel-arrow campus-carousel-arrow--right" id="campusNext" aria-label="Geser daftar kampus ke kanan" title="Kampus berikutnya">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

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
    (() => {
        const root = document.querySelector('.campus-picker');
        if (!root) return;

        const viewport = root.querySelector('#campusCarousel');
        if (!viewport) return;

        const originals = [...viewport.querySelectorAll('[data-campus-option]')];
        if (!originals.length) return;

        // Semua kampus Palembang dan Indralaya ditampilkan dalam satu carousel.
        const track = document.createElement('div');
        track.className = 'campus-carousel__track';

        originals.forEach(option => {
            option.hidden = false;
            track.appendChild(option);
        });

        viewport.appendChild(track);

        let selected = null;
        let paused = false;
        let raf = 0;
        let previousTime = 0;
        let offset = 0;
        let loopWidth = 0;
        const speed = 22; // px/detik, lambat dan stabil

        function markSelection() {
            originals.forEach(option => {
                const active = option === selected;
                option.classList.toggle('is-selected', active);
                option.setAttribute('aria-current', active ? 'true' : 'false');
            });
        }

        function rebuild() {
            track.querySelectorAll('[data-carousel-clone]').forEach(node => node.remove());

            originals.forEach(option => {
                option.hidden = false;

                const clone = option.cloneNode(true);
                clone.dataset.carouselClone = 'true';
                clone.setAttribute('aria-hidden', 'true');
                clone.removeAttribute('aria-current');
                track.appendChild(clone);
            });

            markSelection();

            requestAnimationFrame(() => {
                loopWidth = track.scrollWidth / 2;
                offset = -loopWidth;
                track.style.transform = `translate3d(${offset}px,0,0)`;
                previousTime = 0;
            });
        }

        function animate(time) {
            if (previousTime && !paused && loopWidth > 0) {
                const delta = Math.min(time - previousTime, 50);
                offset += speed * delta / 1000; // bergerak dari kiri ke kanan

                if (offset >= 0) offset = -loopWidth;

                track.style.transform = `translate3d(${offset}px,0,0)`;
            }

            previousTime = time;
            raf = requestAnimationFrame(animate);
        }

        // Logo asli dan duplikat tetap berupa tautan aktif menuju hasil pencarian.
        track.addEventListener('click', event => {
            const option = event.target.closest('[data-campus-option]');
            if (!option) return;

            if (option.dataset.carouselClone) {
                event.preventDefault();

                const original = originals.find(item =>
                    item.dataset.name === option.dataset.name &&
                    item.href === option.href
                );

                if (original) window.location.href = original.href;
                return;
            }

            selected = option;
            markSelection();
        });

        viewport.addEventListener('mouseenter', () => {
            paused = true;
        });

        viewport.addEventListener('mouseleave', () => {
            paused = false;
            previousTime = 0;
        });

        viewport.addEventListener('focusin', () => {
            paused = true;
        });

        viewport.addEventListener('focusout', event => {
            if (!viewport.contains(event.relatedTarget)) {
                paused = false;
                previousTime = 0;
            }
        });

        viewport.addEventListener('touchstart', () => {
            paused = true;
        }, { passive: true });

        viewport.addEventListener('touchend', () => {
            paused = false;
            previousTime = 0;
        }, { passive: true });

        // Tombol panah menggeser beberapa kartu sekaligus agar kampus lebih cepat dijangkau.
        function moveByArrow(direction) {
            if (!loopWidth) return;

            const firstCard = originals[0];
            const cardWidth = firstCard.getBoundingClientRect().width;
            const trackGap = parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap) || 0;
            const step = Math.max(220, (cardWidth + trackGap) * 2);

            paused = true;
            offset += direction * step;

            // Jaga posisi tetap berada pada rentang loop tanpa jeda kosong.
            while (offset >= 0) offset -= loopWidth;
            while (offset <= -loopWidth) offset += loopWidth;

            track.style.transition = 'transform 380ms ease';
            track.style.transform = `translate3d(${offset}px,0,0)`;

            window.setTimeout(() => {
                track.style.transition = '';
                previousTime = 0;
                paused = false;
            }, 400);
        }

        const prevButton = root.querySelector('#campusPrev');
        const nextButton = root.querySelector('#campusNext');

        prevButton?.addEventListener('click', () => moveByArrow(-1));
        nextButton?.addEventListener('click', () => moveByArrow(1));

        rebuild();
        raf = requestAnimationFrame(animate);

        window.addEventListener('resize', rebuild);
    })();

    MaplyMap.initList({
        id: 'homeMap',
        ref: @json($refDefault),
        items: @json($mapItems),
        campuses: @json($kampusPeta),
        showRef: true
    });
</script>
@endpush
