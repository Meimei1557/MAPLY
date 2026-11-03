@extends('layouts.maply')
@section('title', 'AI Kost Manager')
@section('content')
<div class="container">
    <div class="page-head">
        <div><span class="eyebrow"><i class="fa-solid fa-user-shield"></i> Administrator</span><h1>AI Kost Manager</h1><p>Pipeline 3 tahap: ingest multi-sumber &rarr; ekstraksi Gemini &rarr; validasi dua lapis. Semua hasil menunggu persetujuan admin.</p></div>
        <span class="badge badge--ok"><i class="fa-solid fa-shield-halved"></i> Tidak ada auto-publish</span>
    </div>

    @if(!$apiConfigured)
        <div class="notice notice--warn ai-config-warn">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div><strong>GEMINI_API_KEY belum diatur.</strong> Pipeline tetap bisa berjalan, tetapi ekstraksi AI (Tahap 2) dan penilaian AI (Lapis B Tahap 3) dilewati — kandidat hanya memakai gerbang deterministik dan diperiksa manual. Atur <code>GEMINI_API_KEY</code> di <code>.env</code> lalu jalankan <code>php artisan config:clear</code>.</div>
        </div>
    @endif

    <div class="stat-mini-grid">
        @foreach([['Kandidat ditemukan', $stats['total'], 'fa-magnifying-glass'], ['Bukti kuat', $stats['strong'], 'fa-shield-halved'], ['Perlu diperiksa', $stats['review'], 'fa-clipboard-check'], ['Mencurigakan', $stats['suspicious'], 'fa-triangle-exclamation'], ['Ditolak', $stats['rejected'], 'fa-ban'], ['Diterbitkan', $stats['published'], 'fa-circle-check']] as [$label, $value, $icon])
            <div class="stat-mini"><i class="fa-solid {{ $icon }}"></i><b>{{ $value }}</b><span>{{ $label }}</span></div>
        @endforeach
    </div>

    <section class="panel" style="margin-bottom:22px">
        <h2><span class="fs-icon" style="width:34px;height:34px;border-radius:10px;background:var(--grad-brand-soft);border:1px solid var(--border-soft);color:var(--neon);display:inline-grid;place-items:center;font-size:14px;flex:none"><i class="fa-solid fa-satellite-dish"></i></span> Pencarian spesifik</h2>
        <p style="color:var(--muted);font-size:14px;margin-bottom:16px">Kriteria mempersempit hasil: tahap validasi menandai kandidat yang tidak cocok, dan AI hanya mengisi data yang ada buktinya.</p>
        <form method="POST" action="{{ route('admin.ai-kos.search') }}">
            @csrf
            <div style="display:flex;gap:22px;flex-wrap:wrap;margin-bottom:14px">
                <div>
                    <strong style="display:block;margin-bottom:8px;font-size:13.5px;color:var(--lavender)">Sumber data <span style="color:var(--danger)">*</span></strong>
                    @foreach($connectors as $connector)
                        <label class="check" style="display:flex;margin-bottom:6px;{{ $connector['configured'] ? '' : 'opacity:.55' }}">
                            <input type="checkbox" name="sources[]" value="{{ $connector['key'] }}" {{ $connector['configured'] ? '' : 'disabled' }} @checked(in_array($connector['key'], old('sources', [])))>
                            <span>{{ $connector['label'] }}</span>
                            @if(!$connector['configured'])
                                <small style="display:block;margin-left:22px;color:var(--warn)"><i class="fa-solid fa-triangle-exclamation"></i> Nonaktif — {{ $connector['reason'] }}</small>
                            @endif
                        </label>
                    @endforeach
                </div>
                <div style="flex:3;min-width:280px;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;align-content:start">
                    <label class="fgroup">Kota / wilayah
                        <select class="select" name="region" required>
                            <option value="palembang">Palembang</option>
                            <option value="indralaya">Indralaya, Ogan Ilir</option>
                        </select>
                    </label>
                    <label class="fgroup">Kata kunci
                        <input class="input" name="keyword" maxlength="120" value="{{ old('keyword', 'kost') }}" placeholder="mis. kost putri">
                    </label>
                    <label class="fgroup">Kecamatan
                        <input class="input" name="kecamatan" maxlength="100" value="{{ old('kecamatan') }}" placeholder="mis. Ilir Barat II">
                    </label>
                    <label class="fgroup">Dekat kampus
                        <select class="select" name="kampus">
                            <option value="">Semua kampus</option>
                            @foreach(\App\Models\Kampus::query()->where('aktif', true)->orderBy('nama_resmi')->get(['kode', 'nama_resmi', 'singkatan']) as $k)
                                <option value="{{ $k->kode }}">{{ $k->singkatan ?: $k->nama_resmi }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="fgroup">Harga min (Rp/bulan)
                        <input class="input" type="number" name="harga_min" min="0" step="50000" value="{{ old('harga_min') }}" placeholder="mis. 500000">
                    </label>
                    <label class="fgroup">Harga maks (Rp/bulan)
                        <input class="input" type="number" name="harga_max" min="0" step="50000" value="{{ old('harga_max') }}" placeholder="mis. 1500000">
                    </label>
                    <label class="fgroup">Tipe kos
                        <select class="select" name="tipe">
                            <option value="">Semua tipe</option>
                            <option value="Putra">Putra</option>
                            <option value="Putri">Putri</option>
                            <option value="Campur">Campur</option>
                        </select>
                    </label>
                    <label class="fgroup">Maks hasil
                        <input class="input" type="number" name="max_results" min="1" max="200" value="{{ old('max_results', 50) }}">
                    </label>
                </div>
            </div>
            <label class="fgroup" id="field-url" style="display:none;max-width:560px;margin-bottom:14px">URL halaman publik (untuk sumber URL)
                <input class="input" type="url" name="url" placeholder="https://contoh.com/iklan-kost" value="{{ old('url') }}">
                <small class="help">Meta standar halaman (title, description, og:*) diambil sebagai kandidat mentah — 1 URL per permintaan.</small>
            </label>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-wand-magic-sparkles"></i> Mulai Pencarian</button>
                <a class="btn btn-ghost" href="{{ route('admin.ai-kos.import.form') }}"><i class="fa-solid fa-file-import"></i> Impor berkas JSON/CSV</a>
            </div>
        </form>
        <small style="color:var(--muted-deep);display:block;margin-top:12px">
            @if($queueConnection === 'sync')
                <code>QUEUE_CONNECTION=sync</code> — untuk run kecil proses berjalan langsung saat tombol ditekan (tunggu hingga selesai).
            @else
                Pipeline berjalan lewat antrean <code>{{ $queueConnection }}</code>. Pastikan worker aktif: <code>php artisan queue:work</code>. Status run diperbarui otomatis pada tabel riwayat.
            @endif
        </small>
    </section>

    <section class="panel" style="margin-bottom:22px">
        <h2>Riwayat pencarian</h2>
        <div class="table-scroll" style="margin-top:12px"><table class="table"><thead><tr><th>Waktu</th><th>Wilayah</th><th>Sumber</th><th>Status</th><th>Ditemukan</th><th>Baru</th><th>Duplikat</th><th>Bukti kuat</th><th>Ditolak</th><th>Catatan &amp; aksi</th></tr></thead><tbody>
        @forelse($runs as $run)
            @php $kriteria = data_get((array) $run->options, 'kriteria', []); @endphp
            <tr>
                <td>{{ $run->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst($run->region) }}</td>
                <td><small>{{ collect((array) $run->sources)->implode(', ') ?: '-' }}</small></td>
                <td>
                    <span class="ai-run-status ai-run-status--{{ $run->status }}">
                        @if($run->status === 'queued')<i class="fa-regular fa-clock"></i>@elseif($run->status === 'running')<i class="fa-solid fa-spinner fa-spin"></i>@elseif($run->status === 'completed')<i class="fa-solid fa-circle-check"></i>@else<i class="fa-solid fa-circle-exclamation"></i>@endif
                        {{ $run->status }}
                    </span>
                </td>
                <td>{{ $run->found_count }}</td>
                <td>{{ $run->new_count }}</td>
                <td>{{ $run->duplicate_count }}</td>
                <td>{{ $run->valid_count }}</td>
                <td>{{ $run->rejected_count }}</td>
                <td>
                    <small style="white-space:pre-line">{{ $run->message }}</small>
                    @if($kriteria)
                        <small style="display:block;margin-top:4px;color:var(--muted-deep)">Kriteria: {{ collect($kriteria)->reject(fn ($v, $k) => $k === 'max_results')->map(fn ($v, $k) => $k.'='.(is_array($v) ? implode(',', $v) : $v))->implode(', ') ?: '-' }}</small>
                    @endif
                    @if(in_array($run->status, ['completed', 'failed']))
                        <form method="POST" action="{{ route('admin.ai-kos.search') }}" style="margin-top:6px">
                            @csrf
                            @foreach(collect((array) $run->sources) as $src)<input type="hidden" name="sources[]" value="{{ $src }}">@endforeach
                            <input type="hidden" name="region" value="{{ $run->region }}">
                            <input type="hidden" name="keyword" value="{{ $run->keyword }}">
                            @foreach($kriteria as $krKey => $krVal)
                                <input type="hidden" name="{{ $krKey }}" value="{{ is_array($krVal) ? implode(',', $krVal) : $krVal }}">
                            @endforeach
                            <button type="submit" class="btn btn-ghost btn-sm" onclick="return confirm('Ulangi pencarian ini dengan kriteria yang sama?')"><i class="fa-solid fa-rotate-right"></i> Coba ulang</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty<tr><td colspan="10" style="color:var(--muted)">Belum ada pencarian.</td></tr>@endforelse
        </tbody></table></div>
    </section>

    <section class="panel">
        <h2>Hasil pencarian &amp; verifikasi</h2>
        <p style="color:var(--muted);font-size:14px;margin:8px 0 14px">Trust Score = gerbang deterministik (50%) + penilaian AI (35%) + kelengkapan data (15%). Badge AI: <span class="badge badge--ok">✓ valid</span> <span class="badge badge--warn">? suspicious</span> <span class="badge badge--danger">✗ invalid</span></p>

        <form method="GET" action="{{ route('admin.ai-kos.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;margin-bottom:16px">
            <label class="fgroup" style="min-width:150px">Status
                <select class="select" name="status"><option value="">Semua</option>
                    @foreach(['strong_evidence'=>'Bukti kuat','needs_review'=>'Perlu diperiksa','suspicious'=>'Mencurigakan','rejected'=>'Ditolak','published'=>'Diterbitkan'] as $key=>$label)
                        <option value="{{ $key }}" @selected(($filter['status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="fgroup" style="min-width:150px">Sumber
                <input class="input" name="sumber" value="{{ $filter['sumber'] ?? '' }}" placeholder="mis. OpenStreetMap">
            </label>
            <label class="fgroup" style="min-width:130px">Kota
                <input class="input" name="kota" value="{{ $filter['kota'] ?? '' }}" placeholder="mis. Palembang">
            </label>
            <label class="fgroup" style="min-width:110px">Skor min
                <input class="input" type="number" name="skor_min" min="0" max="100" value="{{ $filter['skor_min'] ?? '' }}">
            </label>
            <label class="fgroup" style="min-width:110px">Skor maks
                <input class="input" type="number" name="skor_max" min="0" max="100" value="{{ $filter['skor_max'] ?? '' }}">
            </label>
            <button class="btn btn-ghost btn-sm" type="submit"><i class="fa-solid fa-filter"></i> Terapkan</button>
            <a class="btn btn-ghost btn-sm" href="{{ route('admin.ai-kos.index') }}">Reset</a>
        </form>

        <form method="POST" action="{{ route('admin.ai-kos.bulk') }}">
        @csrf
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:12px;padding:11px 13px;background:var(--field);border:1px solid var(--border);border-radius:11px">
            <strong style="font-size:14px"><i class="fa-solid fa-bolt" style="color:var(--neon)"></i> Aksi massal:</strong>
            <select class="select" name="action" style="max-width:230px">
                <option value="approve">Setujui &amp; publikasikan</option>
                <option value="reject">Tolak kandidat</option>
                <option value="reprocess">Proses ulang (Tahap 2+3)</option>
            </select>
            <button class="btn btn-primary btn-sm" type="submit" onclick="return confirm('Terapkan aksi massal pada kandidat terpilih?')">Terapkan pada terpilih</button>
            <small style="color:var(--muted-deep)">Approve massal memakai data kandidat apa adanya; kandidat tanpa alamat dilewati.</small>
        </div>
        <div class="table-scroll"><table class="table"><thead><tr><th></th><th>Nama kost/tempat</th><th>Wilayah</th><th>Trust Score</th><th>AI</th><th>Status &amp; alasan</th><th>Sumber</th><th>Aksi</th></tr></thead><tbody>
        @forelse($candidates as $candidate)
            <tr>
                <td><input type="checkbox" name="ids[]" value="{{ $candidate->id }}" @disabled((bool) $candidate->published_kos_id) aria-label="Pilih {{ $candidate->nama_kos }}"></td>
                <td><strong>{{ $candidate->nama_kos }}</strong><br><small>{{ $candidate->alamat }}</small></td>
                <td>{{ $candidate->kota }}</td>
                <td><strong>{{ $candidate->trust_score }}/100</strong></td>
                <td>@if($candidate->ai_verdict === 'valid')<span class="badge badge--ok">✓ valid</span>@elseif($candidate->ai_verdict === 'suspicious')<span class="badge badge--warn">? suspicious</span>@elseif($candidate->ai_verdict === 'invalid')<span class="badge badge--danger">✗ invalid</span>@else<span class="badge">belum dinilai</span>@endif</td>
                <td>
                    <span class="badge">{{ str_replace('_',' ',$candidate->verification_status) }}</span>
                    @if(($alasanUtama = collect($candidate->verification_reasons ?? [])->first()) !== null)
                        <small style="display:block;margin-top:4px;color:var(--muted-deep)">{{ \Illuminate\Support\Str::limit($alasanUtama, 110) }}</small>
                    @endif
                </td>
                <td>{{ $candidate->source_name }}</td>
                <td><a class="btn btn-ghost btn-sm" href="{{ route('admin.ai-kos.show',$candidate) }}">Periksa</a></td>
            </tr>
        @empty<tr><td colspan="8" style="color:var(--muted)">Belum ada kandidat. Mulai pencarian atau impor berkas untuk mengumpulkan data.</td></tr>@endforelse
        </tbody></table></div>
        </form>
        <div style="margin-top:16px">{{ $candidates->links('pagination.maply') }}</div>
    </section>
</div>

@push('scripts')
<script>
    // Tampilkan input URL hanya bila sumber "url" dicentang.
    document.querySelectorAll('input[name="sources[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', perbaruiFieldUrl);
        perbaruiFieldUrl();
    });
    function perbaruiFieldUrl() {
        var urlDicentang = document.querySelector('input[name="sources[]"][value="url"]')?.checked;
        var field = document.getElementById('field-url');
        if (field) field.style.display = urlDicentang ? 'block' : 'none';
    }
</script>
@endpush
@endsection
