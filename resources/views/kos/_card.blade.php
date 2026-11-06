{{-- Kartu kos. Variabel: $item (Kos), $refKey (string), $withMapButton (bool, opsional) --}}
@php
    $url = route('kos.show', [$item, 'kampus' => $refKey ?? config('maply.titik_default')]);
    $jarak = $item->jarak_km ?? null;
@endphp
<article class="kos-card">
    <div class="photo-box {{ $item->foto_url ? '' : 'is-empty' }}">
        @if($item->foto_url)
            <img src="{{ $item->foto_url }}" alt="Foto {{ $item->nama_kos }}" loading="lazy" data-fallback>
        @endif
        <div class="placeholder"><i class="fa-solid fa-house-chimney"></i><span>Foto belum tersedia</span></div>
        <div class="badges">
            <span class="badge badge--{{ $item->tipe_slug }}">Kos {{ $item->tipe }}</span>
            @if($item->is_contoh)<span class="badge badge--muted">Data contoh</span>@endif
            @if($item->catatan_data)<span class="badge badge--warn" title="{{ $item->catatan_data }}">Data belum lengkap</span>@endif
        </div>
        <span class="badge status-badge {{ $item->status === 'Tersedia' ? 'badge--ok' : 'badge--danger' }}">
            {{ $item->status === 'Tersedia' ? 'Tersedia' : 'Penuh' }}
        </span>
    </div>
    <div class="kos-card__body">
        <h3><a href="{{ $url }}">{{ $item->nama_kos }}</a></h3>
        <p class="loc"><i class="fa-solid fa-location-dot"></i><span>{{ $item->alamat }}@if($item->kecamatan), {{ $item->kecamatan }}@endif
            @if($jarak !== null)<br><strong>{{ number_format($jarak, 1, ',', '.') }} km</strong> dari titik acuan @endif</span></p>
        @if($item->fasilitas->isNotEmpty())
            <div class="chips">
                @foreach($item->fasilitas->take(3) as $f)<span class="chip"><i class="fa-solid fa-check" style="font-size:10px"></i> {{ $f->nama_fasilitas }}</span>@endforeach
                @if($item->fasilitas->count() > 3)<span class="chip">+{{ $item->fasilitas->count() - 3 }}</span>@endif
            </div>
        @endif
        <div class="kos-card__foot">
            @if($item->harga !== null)
                <div class="price">{{ $item->harga_label }} <small>/ bulan</small></div>
            @else
                <div class="price unknown">Harga belum diketahui</div>
            @endif
            <div class="kos-card__actions">
                @if(($withMapButton ?? false) && $item->punya_koordinat)
                    <button type="button" class="btn btn-ghost btn-sm" data-focus-kos="{{ $item->id }}" title="Tampilkan di peta"><i class="fa-solid fa-location-crosshairs"></i> Peta</button>
                @elseif(! $item->punya_koordinat)
                    <a class="btn btn-ghost btn-sm" href="{{ $item->google_maps_url }}" target="_blank" rel="noopener" title="Koordinat belum ada — cari di Google Maps"><i class="fa-solid fa-map"></i> Maps</a>
                @endif
                <a class="btn btn-primary btn-sm" href="{{ $url }}">Detail</a>
            </div>
        </div>
    </div>
</article>
