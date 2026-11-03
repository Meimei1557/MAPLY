@extends('layouts.maply')
@php $edit = $kos->exists; @endphp
@section('title', $edit ? 'Edit Kos' : 'Tambah Kos')
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush
@section('content')
<div class="container">
    <div class="page-head">
        <div><span class="eyebrow"><i class="fa-solid fa-user-shield"></i> Administrator</span><h1>{{ $edit ? 'Edit data kos' : 'Tambah kos baru' }}</h1>
            <p>Isi hanya informasi yang dapat dipastikan. Kosongkan kolom yang belum diketahui — jangan menebak.</p></div>
        <a class="btn btn-ghost" href="{{ route('admin.kos.index') }}" data-back><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    @include('partials.form-errors')

    <form method="POST" enctype="multipart/form-data" class="form-layout" data-once
          action="{{ $edit ? route('admin.kos.update', $kos) : route('admin.kos.store') }}">
        @csrf
        @if($edit)@method('PUT')@endif

        <section class="form-section">
            <h2><span class="fs-icon"><i class="fa-solid fa-circle-info"></i></span> Informasi dasar</h2>
            <p class="hint">Nama, jenis, harga, dan ketersediaan.</p>
            <div class="grid-2">
                <div class="fgroup span-2">
                    <label for="nama_kos">Nama kos <span class="req">*</span></label>
                    <input class="input @error('nama_kos') is-invalid @enderror" id="nama_kos" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos) }}" maxlength="150" required>
                    @error('nama_kos')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="tipe">Jenis kos <span class="req">*</span></label>
                    <select class="select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                        @foreach(\App\Models\Kos::TIPE as $t)<option value="{{ $t }}" @selected(old('tipe', $kos->tipe) === $t)>{{ $t }}</option>@endforeach
                    </select>
                    @error('tipe')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="status">Ketersediaan <span class="req">*</span></label>
                    <select class="select" id="status" name="status" required>
                        @foreach(\App\Models\Kos::STATUS as $s)<option value="{{ $s }}" @selected(old('status', $kos->status) === $s)>{{ $s }}</option>@endforeach
                    </select>
                    <span class="help">Kos berstatus "Penuh" tidak tampil di pencarian publik.</span>
                </div>
                <div class="fgroup span-2">
                    <label for="harga">Harga sewa per bulan (Rp)</label>
                    <input class="input @error('harga') is-invalid @enderror" id="harga" name="harga" inputmode="numeric" value="{{ old('harga', $kos->harga) }}" placeholder="Mis. 1200000 — kosongkan bila belum diketahui">
                    @error('harga')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup span-2">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="textarea @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" maxlength="3000">{{ old('deskripsi', $kos->deskripsi) }}</textarea>
                    @error('deskripsi')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="form-section">
            <h2><span class="fs-icon"><i class="fa-solid fa-location-dot"></i></span> Lokasi</h2>
            <p class="hint">Klik peta atau seret penanda untuk mengisi koordinat. Bila tidak diisi, kos tetap tampil di daftar tetapi tidak di peta.</p>
            <div class="grid-2">
                <div class="fgroup span-2">
                    <label for="alamat">Alamat <span class="req">*</span></label>
                    <textarea class="textarea @error('alamat') is-invalid @enderror" id="alamat" name="alamat" style="min-height:76px" maxlength="500" required>{{ old('alamat', $kos->alamat) }}</textarea>
                    @error('alamat')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="kecamatan">Kecamatan</label>
                    <input class="input" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $kos->kecamatan) }}" maxlength="100" placeholder="Mis. Ilir Barat I">
                    @error('kecamatan')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="kota">Kota <span class="req">*</span></label>
                    <input class="input" id="kota" name="kota" value="{{ old('kota', $kos->kota) }}" maxlength="100" required>
                    @error('kota')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="latitude">Latitude</label>
                    <input class="input @error('latitude') is-invalid @enderror" id="latitude" name="latitude" inputmode="decimal" value="{{ old('latitude', $kos->latitude) }}" placeholder="-2.986444">
                    @error('latitude')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="longitude">Longitude</label>
                    <input class="input @error('longitude') is-invalid @enderror" id="longitude" name="longitude" inputmode="decimal" value="{{ old('longitude', $kos->longitude) }}" placeholder="104.734333">
                    @error('longitude')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup span-2">
                    <label for="koordinat_status">Ketepatan koordinat</label>
                    <select class="select" id="koordinat_status" name="koordinat_status">
                        @foreach(\App\Models\Kos::KOORDINAT_STATUS as $val => $label)<option value="{{ $val }}" @selected(old('koordinat_status', $kos->koordinat_status ?? 'akurat') === $val)>{{ $label }}</option>@endforeach
                    </select>
                    <span class="help">Pilih "Perkiraan" bila titik hanya mewakili area atau jalan.</span>
                    @error('koordinat_status')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="span-2">
                    <div id="pickerMap" class="picker-map"></div>
                    <div class="picker-actions">
                        <button type="button" class="btn btn-ghost btn-sm" data-picker-locate><i class="fa-solid fa-crosshairs"></i> Gunakan lokasi saya</button>
                        <button type="button" class="btn btn-ghost btn-sm" data-picker-clear><i class="fa-solid fa-eraser"></i> Hapus titik</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="form-section">
            <h2><span class="fs-icon"><i class="fa-solid fa-wifi"></i></span> Fasilitas</h2>
            <p class="hint">Centang fasilitas yang benar-benar tersedia.</p>
            <div class="pill-group">
                @foreach($facilityOptions as $f)
                    <label class="pill"><input type="checkbox" name="fasilitas[]" value="{{ $f }}" @checked(in_array($f, (array) old('fasilitas', $selectedFasilitas), true))><span>{{ $f }}</span></label>
                @endforeach
            </div>
            <div class="fgroup" style="margin-top:16px">
                <label for="fasilitas_lain">Fasilitas lainnya</label>
                <input class="input" id="fasilitas_lain" name="fasilitas_lain" maxlength="255" value="{{ old('fasilitas_lain', $fasilitasLain) }}" placeholder="Pisahkan dengan koma, mis. Water heater, TV">
                @error('fasilitas_lain')<span class="err">{{ $message }}</span>@enderror
            </div>
        </section>

        <section class="form-section">
            <h2><span class="fs-icon"><i class="fa-solid fa-camera"></i></span> Foto</h2>
            <p class="hint">Gunakan foto milik sendiri atau yang Anda berhak memakainya. JPG/PNG/WEBP, maksimal 2 MB.</p>
            @if($edit && $kos->foto_url)
                <div class="current-photo">
                    <img src="{{ $kos->foto_url }}" alt="Foto saat ini" data-fallback>
                    <label class="check"><input type="checkbox" name="hapus_foto" value="1" @checked(old('hapus_foto'))> Hapus foto ini</label>
                </div>
            @endif
            <div class="grid-2">
                <div class="fgroup">
                    <label for="foto">Unggah foto</label>
                    <input class="input @error('foto') is-invalid @enderror" id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp">
                    @error('foto')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="foto_url">atau tautan foto (https)</label>
                    <input class="input @error('foto_url') is-invalid @enderror" id="foto_url" type="url" name="foto_url" value="{{ old('foto_url', ($kos->foto && str_starts_with((string) $kos->foto, 'http')) ? $kos->foto : '') }}" placeholder="https://…">
                    @error('foto_url')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="form-section">
            <h2><span class="fs-icon"><i class="fa-solid fa-address-book"></i></span> Kontak &amp; sumber data</h2>
            <p class="hint">Cantumkan nomor hanya bila pemiliknya mengizinkan atau nomor tersebut dipublikasikan untuk disewakan.</p>
            <div class="grid-2">
                <div class="fgroup">
                    <label for="kontak_nama">Nama kontak</label>
                    <input class="input" id="kontak_nama" name="kontak_nama" maxlength="100" value="{{ old('kontak_nama', $kos->kontak_nama) }}">
                </div>
                <div class="fgroup">
                    <label for="kontak_telepon">Nomor telepon / WhatsApp</label>
                    <input class="input @error('kontak_telepon') is-invalid @enderror" id="kontak_telepon" name="kontak_telepon" inputmode="tel" value="{{ old('kontak_telepon', $kos->kontak_telepon) }}" placeholder="08xxxxxxxxxx">
                    @error('kontak_telepon')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="sumber_nama">Nama sumber</label>
                    <input class="input" id="sumber_nama" name="sumber_nama" maxlength="150" value="{{ old('sumber_nama', $kos->sumber_nama) }}" placeholder="Mis. Mamikos, Google Maps, survei langsung">
                </div>
                <div class="fgroup">
                    <label for="sumber_url">Tautan sumber</label>
                    <input class="input @error('sumber_url') is-invalid @enderror" id="sumber_url" type="url" name="sumber_url" value="{{ old('sumber_url', $kos->sumber_url) }}" placeholder="https://…">
                    @error('sumber_url')<span class="err">{{ $message }}</span>@enderror
                </div>
                <div class="fgroup">
                    <label for="status_data">Status data <span class="req">*</span></label>
                    <select class="select" id="status_data" name="status_data" required>
                        @foreach(\App\Models\Kos::STATUS_DATA as $val => $label)<option value="{{ $val }}" @selected(old('status_data', $kos->status_data) === $val)>{{ $label }}</option>@endforeach
                    </select>
                    <span class="help">Pilih "Terverifikasi" hanya setelah dikonfirmasi langsung.</span>
                </div>
                <div class="fgroup">
                    <label for="terakhir_dicek">Terakhir dicek</label>
                    <input class="input @error('terakhir_dicek') is-invalid @enderror" id="terakhir_dicek" type="date" name="terakhir_dicek" max="{{ now()->toDateString() }}" value="{{ old('terakhir_dicek', optional($kos->terakhir_dicek)->toDateString()) }}">
                    @error('terakhir_dicek')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <div class="sticky-submit">
            <div class="container">
                <a class="btn btn-ghost" href="{{ route('admin.kos.index') }}">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {{ $edit ? 'Simpan perubahan' : 'Simpan kos' }}</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/maply-map.js') }}"></script>
<script>
    MaplyMap.initPicker({
        id: 'pickerMap', latId: 'latitude', lngId: 'longitude',
        center: { lat: {{ config('maply.titik_acuan.ampera.lat') }}, lng: {{ config('maply.titik_acuan.ampera.lng') }} }
    });
</script>
@endpush
