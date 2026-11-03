@extends('layouts.maply')
@section('title', 'Daftar Kampus')
@section('content')
<div class="container">
    <div class="page-head">
        <div>
            <h1>Kampus Palembang &amp; Indralaya</h1>
            <p>{{ $stat['total'] }} institusi ({{ $stat['aktif'] }} aktif) · {{ $stat['peta'] }} titik berkoordinat di peta</p>
        </div>
        <a class="btn btn-ghost" href="{{ route('kos.index') }}"><i class="fa-solid fa-magnifying-glass"></i> Cari kos</a>
    </div>

    {{-- Pencarian + filter (negeri/swasta, jenis, wilayah, kecamatan) --}}
    <form class="kampus-toolbar" method="GET" action="{{ route('kampus.index') }}">
        <div class="fgroup fgroup--grow">
            <label for="kq">Cari kampus</label>
            <input class="input" id="kq" type="search" name="q" value="{{ $q }}" placeholder="Nama atau singkatan institusi" maxlength="100">
        </div>
        <div class="fgroup">
            <label for="kstatus">Status</label>
            <select class="select" id="kstatus" name="status">
                <option value="">Negeri &amp; swasta</option>
                <option value="Negeri" @selected($status === 'Negeri')>Negeri</option>
                <option value="Swasta" @selected($status === 'Swasta')>Swasta</option>
            </select>
        </div>
        <div class="fgroup">
            <label for="kjenis">Jenis</label>
            <select class="select" id="kjenis" name="jenis">
                <option value="">Semua jenis</option>
                @foreach($jenisOptions as $j)<option value="{{ $j }}" @selected($jenis === $j)>{{ $j }}</option>@endforeach
            </select>
        </div>
        <div class="fgroup">
            <label for="kwilayah">Wilayah</label>
            <select class="select" id="kwilayah" name="wilayah">
                <option value="">Palembang &amp; Ogan Ilir</option>
                <option value="Palembang" @selected($wilayah === 'Palembang')>Palembang</option>
                <option value="Ogan Ilir" @selected($wilayah === 'Ogan Ilir')>Ogan Ilir (Indralaya)</option>
            </select>
        </div>
        <div class="fgroup">
            <label for="kkec">Kecamatan</label>
            <select class="select" id="kkec" name="kecamatan">
                <option value="">Semua kecamatan</option>
                @foreach($kecamatanList as $kc)<option value="{{ $kc }}" @selected($kecamatan === $kc)>{{ $kc }}</option>@endforeach
            </select>
        </div>
        <div class="fgroup">
            <label for="kkoord">Koordinat</label>
            <select class="select" id="kkoord" name="koordinat">
                <option value="semua" @selected($koordinat === 'semua')>Semua</option>
                <option value="terverifikasi" @selected($koordinat === 'terverifikasi')>Ada di peta</option>
                <option value="belum" @selected($koordinat === 'belum')>Belum terverifikasi</option>
            </select>
        </div>
        <div class="fgroup fgroup--btn">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Terapkan</button>
            <a class="btn btn-ghost" href="{{ route('kampus.index') }}"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        </div>
    </form>

    <p class="kampus-count">{{ $kampus->total() }} institusi @if($kampus->total()) · halaman {{ $kampus->currentPage() }}/{{ $kampus->lastPage() }}@endif</p>

    @if($kampus->isEmpty())
        <div class="empty">
            <div class="ico"><i class="fa-solid fa-graduation-cap"></i></div>
            <h3>Tidak ada kampus yang cocok</h3>
            <p>Coba ubah kata kunci atau longgarkan filter.</p>
            <a class="btn btn-primary" href="{{ route('kampus.index') }}"><i class="fa-solid fa-rotate-left"></i> Reset filter</a>
        </div>
    @else
        <div class="ref-grid ref-grid--kampus">
            @foreach($kampus as $k)
                @php
                    $initIdx = abs(crc32($k['kode'])) % 6;
                    $initial = mb_strtoupper(mb_substr(trim($k['label']), 0, 1));
                    $link = $k['punya_koordinat']
                        ? route('kos.index', ['kampus' => $k['kode_lokasi']])
                        : route('kos.index', ['kampus' => $k['kode']]);
                @endphp
                <a class="ref-card ref-card--kampus {{ $k['punya_koordinat'] ? '' : 'ref-card--no-loc' }}" href="{{ $link }}" title="{{ $k['nama'] }} — {{ $k['jenis'] }} {{ $k['status'] }}">
                    <span class="ref-circle">
                        @if($k['logo'])
                            <img src="{{ asset($k['logo']) }}" alt="Logo {{ $k['label'] }}" loading="lazy" width="84" height="84">
                        @else
                            <span class="ref-initial init-{{ $initIdx }}">{{ $initial }}</span>
                        @endif
                    </span>
                    <h3>{{ $k['label'] }}@unless($k['aktif']) <small class="badge badge--muted" style="font-size:10px;vertical-align:middle">nonaktif</small>@endunless</h3>
                    <p>{{ $k['jenis'] }} · {{ $k['status'] }}</p>
                    @if($k['terverifikasi'])
                        <small style="color:var(--ok)"><i class="fa-solid fa-circle-check"></i> Titik peta aktif</small>
                    @elseif($k['punya_koordinat'])
                        <small><i class="fa-solid fa-triangle-exclamation"></i> Koordinat perkiraan</small>
                    @else
                        <small><i class="fa-solid fa-triangle-exclamation"></i> Koordinat belum terverifikasi</small>
                    @endif
                </a>
            @endforeach
        </div>
        {{ $kampus->onEachSide(1)->links('pagination.maply') }}
    @endif
</div>
@endsection
