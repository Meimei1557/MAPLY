<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
    <title>@yield('title', 'Maply') — Maply</title>
    @stack('head')
</head>
<body>
@php $user = auth()->user(); @endphp
<header class="navbar">
    <div class="container navbar__inner">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Maply — kembali ke Beranda">
            <span class="brand__mark"><i class="fa-solid fa-house-chimney"></i></span>
            <span><span class="brand__name">Maply</span><span class="brand__tag">Budget. Lokasi. Rumah.</span></span>
        </a>

        <nav class="nav-links" id="mainNav" aria-label="Navigasi utama">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <a class="nav-link {{ request()->routeIs('kos.*') ? 'active' : '' }}" href="{{ route('kos.index') }}"><i class="fa-solid fa-magnifying-glass"></i> Cari Kos</a>
            <a class="nav-link" href="{{ route('dashboard') }}#peta"><i class="fa-solid fa-map-location-dot"></i> Peta</a>
            <a class="nav-link" href="{{ route('dashboard') }}#titik-acuan"><i class="fa-solid fa-building-columns"></i> Kampus & Area</a>
            @can('kelola-kos')
                <a class="nav-link {{ request()->routeIs('admin.kos.*') ? 'active' : '' }}" href="{{ route('admin.kos.index') }}"><i class="fa-solid fa-screwdriver-wrench"></i> Kelola Kos</a>
                <a class="nav-link {{ request()->routeIs('admin.ai-kos.*') ? 'active' : '' }}" href="{{ route('admin.ai-kos.index') }}"><i class="fa-solid fa-wand-magic-sparkles"></i> AI Kost Manager</a>
            @endcan
        </nav>

        <div class="nav-right">
            <details class="user-menu">
                <summary aria-label="Menu akun">
                    <span class="avatar">{{ strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}</span>
                    <span class="user-menu__name">{{ $user->name }}</span>
                    <i class="fa-solid fa-chevron-down" style="font-size:11px"></i>
                </summary>
                <div class="user-menu__panel">
                    <div class="user-menu__info">
                        <strong>{{ $user->name }}</strong>
                        <span>{{ $user->email }}</span><br>
                        <span class="badge {{ $user->isAdmin() ? 'badge--warn' : 'badge--muted' }}" style="margin-top:6px">{{ $user->role_label }}</span>
                    </div>
                    @can('kelola-kos')
                        <a class="user-menu__item" href="{{ route('admin.kos.create') }}"><i class="fa-solid fa-plus"></i> Tambah kos</a>
                        <a class="user-menu__item" href="{{ route('admin.kos.index') }}"><i class="fa-solid fa-list-check"></i> Kelola data kos</a>
                    @endcan
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="user-menu__item danger"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                    </form>
                </div>
            </details>
            <button class="nav-toggle" id="navToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mainNav"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</header>

<main>
    @if(session('success') || session('error') || session('warning'))
        <div class="container flash-wrap" role="status">
            @foreach(['success' => 'fa-circle-check', 'error' => 'fa-circle-exclamation', 'warning' => 'fa-triangle-exclamation'] as $type => $icon)
                @if(session($type))
                    <div class="flash {{ $type }}"><i class="fa-solid {{ $icon }} lead"></i><span>{{ session($type) }}</span><button type="button" data-dismiss aria-label="Tutup notifikasi"><i class="fa-solid fa-xmark"></i></button></div>
                @endif
            @endforeach
        </div>
    @endif

    @yield('content')
</main>


<div class="maply-ai-chat" id="maplyAiChat">
    <button type="button" class="maply-ai-launch" id="aiChatToggle" aria-expanded="false" aria-controls="aiChatPanel">
        <i class="fa-solid fa-comments"></i> Chat Maply AI
    </button>
    <section class="maply-ai-panel" id="aiChatPanel" hidden aria-label="Chat Maply AI">
        <header class="maply-ai-header">
            <div class="maply-ai-heading">
                <span class="maply-ai-avatar"><i class="fa-solid fa-sparkles"></i></span>
                <span><strong>Maply AI</strong><small><span class="maply-ai-dot"></span> Siap mengobrol</small></span>
            </div>
            <div class="maply-ai-actions">
                <button type="button" id="aiChatNew" title="Percakapan baru" aria-label="Percakapan baru"><i class="fa-solid fa-plus"></i></button>
                <button type="button" id="aiChatClose" title="Tutup chat" aria-label="Tutup">×</button>
            </div>
        </header>
        <div class="maply-ai-messages" id="aiChatMessages" aria-live="polite">
            <p class="ai-bubble">Halo! Saya Maply AI. Kita bisa mengobrol tentang berbagai hal, atau mencari kost di Palembang dan Indralaya. Ada yang ingin kamu tanyakan?</p>
        </div>
        <form id="aiChatForm" autocomplete="off">
            <input id="aiChatInput" maxlength="500" required placeholder="Tulis pesan untuk Maply AI…" aria-label="Pesan">
            <button type="submit" id="aiChatSend" aria-label="Kirim pesan"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
        <small class="maply-ai-note">Jawaban AI dapat keliru. Periksa kembali informasi penting.</small>
    </section>
</div>
<style>
.maply-ai-chat{position:fixed;right:22px;bottom:22px;z-index:1000;font-family:inherit}
.maply-ai-launch{border:0;border-radius:30px;padding:13px 19px;background:#a64d79;color:#fff;box-shadow:0 8px 28px #0002;cursor:pointer;font-weight:700}
.maply-ai-panel{position:absolute;right:0;bottom:62px;width:min(390px,calc(100vw - 28px));height:min(590px,calc(100vh - 115px));min-height:390px;background:#fff;border:1px solid #eadce4;border-radius:20px;box-shadow:0 16px 50px #2714212b;overflow:hidden;display:flex;flex-direction:column}
.maply-ai-panel[hidden]{display:none}
.maply-ai-header{padding:13px 15px;background:#a64d79;color:#fff;display:flex;align-items:center;justify-content:space-between}
.maply-ai-heading{display:flex;align-items:center;gap:10px}
.maply-ai-heading>span:last-child{display:grid;gap:3px}
.maply-ai-heading small{font-size:10px;color:#f9eaf1}
.maply-ai-avatar{width:36px;height:36px;border-radius:50%;display:grid;place-items:center;background:#ffffff25}
.maply-ai-dot{display:inline-block;width:7px;height:7px;background:#9af0bd;border-radius:50%;margin-right:4px}
.maply-ai-actions{display:flex;align-items:center;gap:7px}
.maply-ai-actions button{width:31px;height:31px;border:0;border-radius:8px;background:#ffffff20;color:#fff;font-size:18px;cursor:pointer}
.maply-ai-messages{padding:15px;overflow:auto;flex:1;background:#fffafd;display:flex;flex-direction:column;gap:9px}
.ai-bubble,.ai-user{white-space:pre-wrap;overflow-wrap:anywhere;padding:10px 12px;border-radius:13px;line-height:1.55;font-size:13px;max-width:90%;margin:0}
.ai-bubble{background:#f3e8ef;color:#34232d;align-self:flex-start;border-bottom-left-radius:4px}
.ai-user{background:#a64d79;color:#fff;align-self:flex-end;border-bottom-right-radius:4px}
.ai-typing{opacity:.72;font-style:italic}
.ai-result{width:100%;border:1px solid #eadce4;background:#fff;border-radius:12px;padding:11px;font-size:12px;color:#352832}
.ai-result strong{display:block;font-size:13px;color:#3a2632}
.ai-result p{margin:5px 0;line-height:1.5}
.ai-result a{color:#a64d79;font-weight:700;text-decoration:none}
.ai-result a:hover{text-decoration:underline}
.ai-result-links{display:flex;gap:14px;flex-wrap:wrap;margin-top:7px}
.maply-ai-panel form{display:flex;align-items:center;gap:8px;padding:11px;border-top:1px solid #eee;background:#fff}
.maply-ai-panel input{min-width:0;flex:1;border:1px solid #e4d7df;border-radius:22px;padding:11px 14px;outline:none;font-size:13px}
.maply-ai-panel input:focus{border-color:#a64d79;box-shadow:0 0 0 3px #a64d7918}
.maply-ai-panel form button{flex:0 0 40px;width:40px;height:40px;border:0;background:#a64d79;color:#fff;border-radius:50%;cursor:pointer}
.maply-ai-panel form button:disabled{opacity:.55;cursor:wait}
.maply-ai-note{padding:0 13px 10px;color:#888;font-size:10px;background:#fff}
@media(max-width:480px){.maply-ai-chat{right:12px;bottom:12px}.maply-ai-panel{right:0;width:calc(100vw - 24px);height:min(650px,calc(100vh - 90px));bottom:58px}}
</style>
<script>
(()=> {
    const toggle=document.getElementById('aiChatToggle');
    const panel=document.getElementById('aiChatPanel');
    const close=document.getElementById('aiChatClose');
    const newChat=document.getElementById('aiChatNew');
    const form=document.getElementById('aiChatForm');
    const input=document.getElementById('aiChatInput');
    const send=document.getElementById('aiChatSend');
    const messages=document.getElementById('aiChatMessages');
    if(!toggle||!panel||!form)return;

    const greeting='Halo! Saya Maply AI. Kita bisa mengobrol tentang berbagai hal, atau mencari kost di Palembang dan Indralaya. Ada yang ingin kamu tanyakan?';
    let history=[];
    let conversationSummary="";

    function scrollDown(){messages.scrollTop=messages.scrollHeight;}
    function addBubble(text,kind='ai-bubble'){
        const el=document.createElement('p');
        el.className=kind;
        el.textContent=text;
        messages.appendChild(el);
        scrollDown();
        return el;
    }
    function addKostCards(items){
        (items||[]).forEach(item=>{
            const card=document.createElement('article');
            card.className='ai-result';
            const title=document.createElement('strong');
            title.textContent=item.name||'Kost Maply';
            const info=document.createElement('p');
            const price=item.price?('Rp '+Number(item.price).toLocaleString('id-ID')+'/bulan'):'Harga belum tercantum';
            info.textContent=[item.address,item.city,price,item.type].filter(Boolean).join(' · ');
            const links=document.createElement('div');
            links.className='ai-result-links';
            if(item.url){
                const detail=document.createElement('a');
                detail.href=item.url;
                detail.textContent='Lihat detail kost';
                links.appendChild(detail);
            }
            if(item.maps){
                const map=document.createElement('a');
                map.href=item.maps;
                map.target='_blank';
                map.rel='noopener noreferrer';
                map.textContent='Buka peta';
                links.appendChild(map);
            }
            card.append(title,info,links);
            messages.appendChild(card);
        });
        scrollDown();
    }

    function resetChat(){
        history=[];
        conversationSummary="";
        messages.replaceChildren();
        addBubble(greeting);
        input.focus();
    }

    toggle.addEventListener('click',()=>{
        panel.hidden=!panel.hidden;
        toggle.setAttribute('aria-expanded',String(!panel.hidden));
        if(!panel.hidden)input.focus();
    });
    close.addEventListener('click',()=>{
        panel.hidden=true;
        toggle.setAttribute('aria-expanded','false');
    });
    newChat.addEventListener('click',resetChat);

    form.addEventListener('submit',async event=>{
        event.preventDefault();
        const message=input.value.trim();
        if(!message||send.disabled)return;

        addBubble(message,'ai-user');
        input.value='';
        const loading=addBubble('Maply AI sedang berpikir…','ai-bubble ai-typing');
        send.disabled=true;
        input.disabled=true;

        try{
            const response=await fetch(@json(route('ai.chat')),{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/json',
                    'X-CSRF-TOKEN':@json(csrf_token())
                },
                body:JSON.stringify({message,history,summary:conversationSummary})
            });
            const data=await response.json();
            if(!response.ok)throw new Error(data.message||'Permintaan tidak berhasil.');

            loading.classList.remove('ai-typing');
            loading.textContent=data.message||'Maaf, saya belum dapat menjawab.';
            history.push({role:'user',text:message});
            history.push({role:'model',text:data.message||''});
            if(typeof data.summary==='string')conversationSummary=data.summary;
            // Simpan maksimal 16 giliran/pesan di browser; server akan merangkum bagian lama.
            history=history.slice(-16);
            addKostCards(data.items||[]);
        }catch(error){
            loading.classList.remove('ai-typing');
            loading.textContent=error.message||'Tidak dapat menghubungi Maply AI. Silakan coba lagi.';
        }finally{
            send.disabled=false;
            input.disabled=false;
            input.focus();
            scrollDown();
        }
    });
})();
</script>

<footer class="footer">
    <div class="container">
        <span>© {{ date('Y') }} Maply · Pencarian kos berdasarkan lokasi dan kriteria.</span>
        <span>Peta © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors</span>
    </div>
</footer>

@can('kelola-kos')
<dialog class="confirm" id="confirmDialog" aria-labelledby="dlgTitle">
    <form method="POST" action="" class="dialog-card">
        @csrf
        @method('DELETE')
        <div class="ico"><i class="fa-solid fa-trash-can"></i></div>
        <h3 id="dlgTitle">Hapus data kos?</h3>
        <p>Kos <strong data-dlg-name>ini</strong> beserta fasilitasnya akan dihapus permanen dan tidak bisa dikembalikan.</p>
        <div class="dialog-actions">
            <button type="button" class="btn btn-ghost" data-dlg-close>Batal</button>
            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i> Ya, hapus</button>
        </div>
    </form>
</dialog>
@endcan

<script src="{{ asset('js/maply.js') }}"></script>
@stack('scripts')
</body>
</html>
