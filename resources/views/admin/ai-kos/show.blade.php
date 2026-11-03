@extends('layouts.maply')
@section('title', 'Detail Kandidat AI')
@section('content')
@php
    $verdictBadge = match ($candidate->ai_verdict) {
        'valid' => ['badge--ok', '✓ valid'],
        'suspicious' => ['badge--warn', '? suspicious'],
        'invalid' => ['badge--danger', '✗ invalid'],
        default => ['badge--muted', 'belum dinilai AI'],
    };
@endphp
<div class="container" style="max-width:960px">
    <div class="detail-top">
        <a href="{{ route('admin.ai-kos.index') }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i> AI Kost Manager</a>
        <span>Detail kandidat</span>
    </div>
    <div class="page-head" style="padding-top:0"><div><span class="eyebrow"><i class="fa-solid fa-clipboard-check"></i> Pemeriksaan kandidat</span><h1>{{ $candidate->nama_kos }}</h1><p>{{ $candidate->alamat ?: 'Alamat belum ditemukan' }}</p></div><span class="badge">{{ $candidate->trust_score }}/100 · {{ str_replace('_',' ',$candidate->verification_status) }}</span></div>

    <div class="panel" style="margin-bottom:18px">
        <h2>Hasil pemeriksaan bukti</h2>
        <div class="progress-track" style="margin:10px 0 12px"><div class="progress-fill" style="width:{{ max(0, min(100, $candidate->trust_score)) }}%"></div></div>
        <p><strong>Trust Score: {{ $candidate->trust_score }}/100</strong> — gabungan gerbang deterministik (50%), penilaian AI (35%), dan kelengkapan data (15%).</p>
        <ul style="color:var(--muted);padding-left:18px;margin:10px 0 0">@foreach((array)$candidate->verification_reasons as $reason)<li>{{ $reason }}</li>@endforeach</ul>
        <p style="color:var(--muted);font-size:13.5px;margin-top:10px"><strong>Catatan:</strong> skor menilai kelengkapan dan konsistensi bukti publik, bukan jaminan tempat tersebut beroperasi sebagai kost. Verifikasi manual tetap diperlukan.</p>
    </div>

    <div class="panel" style="margin-bottom:18px">
        <h2>Analisis AI <span class="badge {{ $verdictBadge[0] }}" style="margin-left:8px">{{ $verdictBadge[1] }}</span></h2>
        @if($candidate->ai_verdict)
            <p>Keyakinan AI: <strong>{{ number_format($candidate->ai_confidence * 100, 0) }}%</strong>@if(is_array($candidate->ai_analysis) && !empty($candidate->ai_analysis['model'])) · model <code>{{ $candidate->ai_analysis['model'] }}</code> @endif @if($candidate->ai_processed_at) · diproses {{ $candidate->ai_processed_at->format('d/m/Y H:i') }}@endif</p>
            @if(is_array($candidate->ai_analysis) && !empty($candidate->ai_analysis['reasons']))
                <p style="margin-top:8px"><strong>Alasan AI:</strong></p>
                <ul style="color:var(--muted);padding-left:18px;margin:6px 0 0">@foreach($candidate->ai_analysis['reasons'] as $reason)<li>{{ $reason }}</li>@endforeach</ul>
            @endif
        @else
            <p style="color:var(--muted)">Kandidat ini belum dinilai oleh AI (Gemini belum dikonfigurasi, kandidat tidak lolos gerbang deterministik, atau pemrosesan gagal — lihat catatan di atas).</p>
        @endif

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-top:14px">
            <div>
                <h3 style="margin:0 0 8px;font-size:16px">Hasil ekstraksi AI (ai_extracted)</h3>
                @if(is_array($candidate->ai_extracted) && $candidate->ai_extracted !== [])
                    <div class="table-scroll"><table class="table">
                        <thead><tr><th>Field</th><th>Nilai</th></tr></thead>
                        <tbody>
                        @foreach(['nama'=>'Nama','alamat'=>'Alamat','kecamatan'=>'Kecamatan','harga_per_bulan'=>'Harga per bulan','tipe'=>'Tipe','telepon'=>'Telepon'] as $key=>$label)
                            @php($nilai = $candidate->ai_extracted[$key] ?? null)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    @if($key === 'harga_per_bulan' && $nilai !== null) Rp {{ number_format($nilai, 0, ',', '.') }}
                                    @elseif($key === 'fasilitas') {{ implode(', ', (array) $nilai) }}
                                    @else {{ $nilai ?? '— (tidak ada bukti)' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if(!empty($candidate->ai_extracted['fasilitas']))
                            <tr><td>Fasilitas</td><td>{{ implode(', ', (array) $candidate->ai_extracted['fasilitas']) ?: '—' }}</td></tr>
                        @endif
                        </tbody>
                    </table></div>
                @else
                    <p style="color:var(--muted)">Belum ada hasil ekstraksi AI. Jalankan <code>php artisan maply:ai-reprocess --run={{ $candidate->search_run_id }}</code> bila Gemini sudah dikonfigurasi.</p>
                @endif
            </div>
            <div>
                <h3 style="margin:0 0 8px;font-size:16px">Data mentah (raw_data)</h3>
                <details><summary style="cursor:pointer;color:var(--neon);font-weight:600">Data sumber</summary><pre class="code-block" style="max-height:320px;overflow:auto;margin-top:8px">{{ json_encode($candidate->raw_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre></details>
            </div>
        </div>
    </div>

    <div class="panel" style="margin-bottom:18px">
        <h2>Data yang ditemukan</h2>
        <dl style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:2px 14px;margin:0">
            <div><dt>Wilayah</dt><dd>{{ $candidate->kota }}</dd></div>
            <div><dt>Nomor telepon</dt><dd>{{ $candidate->telepon ?: 'Tidak ditemukan' }}</dd></div>
            <div><dt>Website</dt><dd>@if($candidate->website)<a href="{{ $candidate->website }}" target="_blank" rel="noopener" style="color:var(--neon)">Buka website</a>@else Tidak ditemukan @endif</dd></div>
            <div><dt>Harga</dt><dd>{{ $candidate->harga ? 'Rp '.number_format($candidate->harga,0,',','.') : 'Belum diketahui' }}</dd></div>
            <div><dt>Jenis kost</dt><dd>{{ $candidate->tipe }}</dd></div>
            <div><dt>Koordinat</dt><dd>{{ $candidate->latitude && $candidate->longitude ? $candidate->latitude.', '.$candidate->longitude : 'Tidak ditemukan' }}</dd></div>
            <div><dt>Ditemukan</dt><dd>{{ optional($candidate->found_at)->format('d/m/Y H:i') ?: '-' }}</dd></div>
            <div><dt>Sumber utama</dt><dd>{{ $candidate->source_name ?: 'Tidak diketahui' }}</dd></div>
        </dl>
        @if($candidate->google_maps_url)<a class="btn btn-ghost" style="margin-top:14px" href="{{ $candidate->google_maps_url }}" target="_blank" rel="noopener"><i class="fa-solid fa-map"></i> Buka di peta</a>@endif
    </div>

    <div class="panel" style="margin-bottom:18px">
        <h2>Bukti sumber ({{ $candidate->sources->count() }})</h2>
        <p style="color:var(--muted);font-size:14px;margin:6px 0 14px">Setiap sumber menyimpan bukti terpisah; penilaian AI memakai seluruh bukti ini.</p>
        @forelse($candidate->sources as $source)
            <div style="border:1px solid var(--border);border-radius:11px;padding:12px;margin-bottom:10px;background:var(--field)">
                <p style="margin:0 0 4px"><strong>{{ $source->source_name }}</strong> · {{ $source->collected_at?->format('d/m/Y H:i') }}<br>
                @if($source->source_url)<a href="{{ $source->source_url }}" target="_blank" rel="noopener" style="color:var(--neon)">{{ $source->source_url }}</a>@else URL sumber tidak tersedia @endif</p>
                <details><summary style="cursor:pointer;color:var(--neon);font-weight:600">Lihat bukti (JSON)</summary><pre class="code-block" style="max-height:260px;overflow:auto;margin-top:8px">{{ json_encode($source->evidence, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre></details>
            </div>
        @empty<p style="color:var(--muted)">Belum ada riwayat sumber.</p>@endforelse
    </div>

    @if(!$candidate->published_kos_id && $candidate->verification_status !== 'rejected')
    <div class="panel" style="margin-bottom:18px">
        <h2>Persetujuan dan publikasi</h2>
        <p style="color:var(--muted);font-size:14px;margin:6px 0 14px">Periksa dan koreksi data sebelum diterbitkan. Harga dan jenis kost dibiarkan tidak diketahui jika tidak ada bukti.</p>
        @if(is_array($candidate->ai_extracted) && $candidate->ai_extracted !== [])
            <button type="button" id="btn-pakai-ekstraksi" class="btn btn-ghost" style="margin-bottom:14px"><i class="fa-solid fa-robot"></i> Gunakan hasil ekstraksi AI</button>
        @endif
        <form method="POST" action="{{ route('admin.ai-kos.approve',$candidate) }}" id="form-approve" style="display:flex;flex-direction:column;gap:14px">
            @csrf
            <div class="fgroup"><label>Nama kost</label><input class="input" name="nama_kos" required maxlength="255" value="{{ old('nama_kos',$candidate->nama_kos) }}"></div>
            <div class="fgroup"><label>Alamat</label><textarea class="textarea" name="alamat" required rows="3">{{ old('alamat',$candidate->alamat) }}</textarea></div>
            <div class="two-col">
                <div class="fgroup"><label>Wilayah</label><select class="select" name="kota"><option @selected($candidate->kota==='Palembang')>Palembang</option><option @selected($candidate->kota==='Indralaya')>Indralaya</option></select></div>
                <div class="fgroup"><label>Kecamatan (opsional)</label><input class="input" name="kecamatan" maxlength="100" value="{{ old('kecamatan',$candidate->kecamatan) }}"></div>
                <div class="fgroup"><label>Harga per bulan (kosongkan jika tidak diketahui)</label><input class="input" type="number" min="0" name="harga" value="{{ old('harga',$candidate->harga) }}"></div>
                <div class="fgroup"><label>Jenis kost</label><select class="select" name="tipe">@foreach(['Belum diketahui','Putra','Putri','Campur'] as $type)<option value="{{ $type }}" @selected($candidate->tipe===$type)>{{ $type }}</option>@endforeach</select></div>
                <div class="fgroup"><label>Telepon (opsional)</label><input class="input" name="telepon" maxlength="25" value="{{ old('telepon',$candidate->telepon) }}"></div>
            </div>
            <div><button class="btn btn-primary" type="submit" onclick="return confirm('Terbitkan data kandidat ini ke database dan peta Maply?')"><i class="fa-solid fa-check"></i> Setujui &amp; Publikasikan</button></div>
        </form>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;padding-bottom:28px">
        <form method="POST" action="{{ route('admin.ai-kos.reject',$candidate) }}" onsubmit="return confirm('Tandai kandidat ini sebagai ditolak?')">@csrf<button class="btn btn-danger" type="submit"><i class="fa-solid fa-ban"></i> Tolak kandidat</button></form>
        <form method="POST" action="{{ route('admin.ai-kos.destroy',$candidate) }}" onsubmit="return confirm('Hapus kandidat ini beserta riwayat sumbernya?')">@csrf @method('DELETE')<button class="btn btn-ghost" type="submit"><i class="fa-solid fa-trash-can"></i> Hapus kandidat</button></form>
    </div>
    @elseif($candidate->published_kos_id)
        <p class="flash success">Sudah dipublikasikan. <a href="{{ route('kos.show',$candidate->published_kos_id) }}">Lihat listing</a></p>
    @else
        <p class="flash warning">Kandidat ini telah ditolak.</p>
    @endif
</div>

@if(is_array($candidate->ai_extracted) && $candidate->ai_extracted !== [])
@push('scripts')
<script>
    // Tombol "Gunakan hasil ekstraksi AI": mengisi form approve dari ai_extracted.
    // Admin tetap bebas mengedit nilai apa pun sebelum menerbitkan.
    document.getElementById('btn-pakai-ekstraksi')?.addEventListener('click', function () {
        var hasil = @json($candidate->ai_extracted);
        var form = document.getElementById('form-approve');
        if (!form || !hasil) return;

        var isi = function (name, value) {
            var field = form.querySelector('[name="' + name + '"]');
            if (field && value !== null && value !== undefined && value !== '') {
                field.value = value;
            }
        };

        isi('nama_kos', hasil.nama);
        isi('alamat', hasil.alamat);
        isi('kecamatan', hasil.kecamatan);
        isi('harga', hasil.harga_per_bulan);
        isi('telepon', hasil.telepon);

        if (hasil.tipe) {
            var pilihTipe = form.querySelector('[name="tipe"]');
            if (pilihTipe && ['Putra', 'Putri', 'Campur', 'Belum diketahui'].includes(hasil.tipe)) {
                pilihTipe.value = hasil.tipe;
            }
        }

        this.insertAdjacentHTML('afterend', '<small style="display:block;margin-top:8px;color:var(--ok)">Form terisi dari ekstraksi AI — periksa dan sesuaikan sebelum menerbitkan.</small>');
    });
</script>
@endpush
@endif
@endsection
