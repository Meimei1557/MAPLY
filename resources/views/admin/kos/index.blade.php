@extends('layouts.maply')
@section('title', 'Kelola Kos')
@section('content')
<div class="container">
    <div class="page-head">
        <div><span class="eyebrow"><i class="fa-solid fa-user-shield"></i> Administrator</span><h1>Kelola Data Kos</h1><p>Tambah, ubah, dan hapus data kos.</p></div>
        <a class="btn btn-primary" href="{{ route('admin.kos.create') }}"><i class="fa-solid fa-plus"></i> Tambah kos</a>
    </div>

    @include('partials.form-errors')

    <form class="toolbar" method="GET" action="{{ route('admin.kos.index') }}">
        <div class="input-icon grow"><i class="fa-solid fa-magnifying-glass"></i>
            <input class="input" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama, alamat, atau kecamatan" aria-label="Cari kos"></div>
        <select class="select" name="data" style="width:auto;min-width:190px" aria-label="Filter status data" data-autosubmit>
            @foreach(['semua' => 'Semua status data', 'terverifikasi' => 'Terverifikasi', 'perlu_konfirmasi' => 'Perlu dikonfirmasi', 'contoh' => 'Data contoh', 'tanpa_koordinat' => 'Belum ada koordinat', 'tanpa_harga' => 'Belum ada harga'] as $val => $label)
                <option value="{{ $val }}" @selected($dataFilter === $val)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" type="submit">Cari</button>
        @if(request()->hasAny(['q', 'data']))<a class="btn btn-ghost" href="{{ route('admin.kos.index') }}">Reset</a>@endif
    </form>

    @if($kosList->isEmpty())
        <div class="empty"><div class="ico"><i class="fa-solid fa-house-circle-xmark"></i></div><h3>Tidak ada data</h3>
            <p>Belum ada kos yang cocok dengan pencarian ini.</p><a class="btn btn-primary" href="{{ route('admin.kos.create') }}">Tambah kos</a></div>
    @else
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Kos</th><th>Harga</th><th>Jenis</th><th>Status data</th><th>Peta</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($kosList as $k)
                    <tr>
                        <td data-label="Kos"><div class="name">{{ $k->nama_kos }}</div><div class="sub">{{ $k->alamat }}@if($k->kecamatan), {{ $k->kecamatan }}@endif</div></td>
                        <td data-label="Harga">{{ $k->harga !== null ? $k->harga_label : '—' }}</td>
                        <td data-label="Jenis"><span class="badge badge--{{ $k->tipe_slug }}">{{ $k->tipe }}</span> @if($k->status === 'Penuh')<span class="badge badge--danger">Penuh</span>@endif</td>
                        <td data-label="Status data"><span class="badge {{ $k->status_data === 'terverifikasi' ? 'badge--ok' : ($k->status_data === 'contoh' ? 'badge--muted' : 'badge--warn') }}">{{ $k->status_data_label }}</span></td>
                        <td data-label="Peta">@if($k->punya_koordinat)<i class="fa-solid fa-circle-check" style="color:var(--ok)"></i> Ada @else<i class="fa-solid fa-circle-minus" style="color:var(--muted-deep)"></i> Belum @endif</td>
                        <td data-label="Aksi"><div class="actions">
                            <a class="btn btn-ghost btn-sm" href="{{ route('kos.show', $k) }}"><i class="fa-solid fa-eye"></i> Lihat</a>
                            <a class="btn btn-ghost btn-sm" href="{{ route('admin.kos.edit', $k) }}"><i class="fa-solid fa-pen"></i> Edit</a>
                            <button type="button" class="btn btn-danger-ghost btn-sm" data-confirm-delete data-action="{{ route('admin.kos.destroy', $k) }}" data-name="{{ $k->nama_kos }}"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                        </div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding-bottom:24px">{{ $kosList->onEachSide(1)->links('pagination.maply') }}</div>
    @endif
</div>
@endsection
