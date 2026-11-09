<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
    <title>@yield('title', 'Maply') — Maply</title>
    @stack('head')
</head>
<body class="@yield('body-class')">
@php $user = auth()->user(); @endphp
<div class="app-shell">
    {{-- ================= SIDEBAR ================= --}}
    <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
        <a class="sidebar__brand" href="{{ route('dashboard') }}" aria-label="Maply — kembali ke Beranda">
            <span class="brand__mark"><i class="fa-solid fa-house-chimney"></i></span>
            <span>
                <span class="brand__name" style="font-family:var(--font-display);font-weight:800;font-size:21px">Maply</span>
                <span style="display:block;font-size:10px;color:var(--muted-deep);letter-spacing:.18em;text-transform:uppercase">Budget · Lokasi · Rumah</span>
            </span>
        </a>

        <nav class="sidebar__body">
            <span class="sidebar__section">Menu</span>
            <a class="snav {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a class="snav {{ request()->routeIs('kos.index') ? 'active' : '' }}" href="{{ route('kos.index') }}">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Kos
            </a>
            <a class="snav" href="{{ route('dashboard') }}#peta">
                <i class="fa-solid fa-map-location-dot"></i> Peta
            </a>
            <a class="snav {{ request()->routeIs('kampus.index') ? 'active' : '' }}" href="{{ route('kampus.index') }}">
                <i class="fa-solid fa-graduation-cap"></i> Kampus &amp; Area
            </a>

            @can('kelola-kos')
                <span class="sidebar__section">Administrator</span>
                <a class="snav {{ request()->routeIs('admin.kos.*') ? 'active' : '' }}" href="{{ route('admin.kos.index') }}">
                    <i class="fa-solid fa-list-check"></i> Kelola Kos
                </a>
                <a class="snav {{ request()->routeIs('admin.ai-kos.*') ? 'active' : '' }}" href="{{ route('admin.ai-kos.index') }}">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> AI Kost Manager
                </a>
                <a class="snav {{ request()->routeIs('admin.koordinat') ? 'active' : '' }}" href="{{ route('admin.koordinat') }}">
                   <a class="snav {{ request()->routeIs('admin.koordinat') ? 'active' : '' }}" href="{{ route('admin.koordinat') }}">
    <i class="fa-solid fa-map-location-dot"></i>
    <span>Peta Koordinat</span>
</a>
                </a>
            @endcan
        </nav>

        {{-- Ilustrasi Jembatan Ampera — dekorasi & penanda lokasi Palembang.
             Cari kos dekat Ampera tetap tersedia lewat dropdown "Dekat" dan filter titik acuan. --}}
       
{{-- Ilustrasi Jembatan Ampera MAPLY --}}
<div class="sidebar__ampera" aria-hidden="true">
    <img
        src="{{ asset('images/ampera-maply.png') }}"
        alt=""
        class="sidebar__ampera-image"
    />
    <span class="sidebar__ampera-glow"></span>
</div>


        <div class="sidebar__foot">
            <div class="side-user">
                <span class="avatar">{{ strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}</span>
                <span class="side-user__meta">
                    <strong>{{ $user->name }}</strong>
                    <span>{{ $user->role_label }}</span>
                </span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="side-logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
            </form>
        </div>
    </aside>
    <div class="backdrop" id="sidebarBackdrop" hidden></div>

    {{-- ================= AREA UTAMA ================= --}}
    <div class="app-main">
        <header class="topbar">
            <button class="nav-burger" id="navToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="sidebar"><i class="fa-solid fa-bars"></i></button>
            <a class="topbar__brand" href="{{ route('dashboard') }}"><span class="brand__mark"><i class="fa-solid fa-house-chimney"></i></span> Maply</a>

            <form class="topbar__search" method="GET" action="{{ route('kos.index') }}" role="search">
                <div class="input-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input class="input" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama kos, alamat, atau kecamatan…" maxlength="100" aria-label="Cari kos atau lokasi">
                </div>
            </form>

            <div class="topbar__right">
                @can('kelola-kos')
                    <span class="role-chip"><i class="fa-solid fa-user-shield"></i> Admin</span>
                @endcan
                <button type="button" class="theme-toggle" data-theme-toggle aria-label="Ganti tema terang/gelap" aria-pressed="false">
                    <i class="fa-solid fa-moon icon-moon" aria-hidden="true"></i>
                    <i class="fa-solid fa-sun icon-sun" aria-hidden="true"></i>
                </button>
                <details class="user-menu">
                    <summary aria-label="Menu akun">
                        <span class="avatar">{{ strtoupper(mb_substr($user->name ?? 'U', 0, 1)) }}</span>
                        <span class="user-menu__name">{{ $user->name }}</span>
                        <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--muted-deep)"></i>
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
            </div>
        </header>

        <main class="app-content">
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

        <footer class="footer">
            <div class="container">
                <span>© {{ date('Y') }} Maply · Pencarian kos berdasarkan lokasi dan kriteria.</span>
                <span>Peta © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors</span>
            </div>
        </footer>
    </div>
</div>

{{-- ================= CHATBOT MOY AI ================= --}}
@php $moyLogo = asset('images/moy-ai-logo.svg'); @endphp
<div class="maply-ai-chat" id="maplyAiChat">
    
<button type="button" class="maply-ai-launch" id="aiChatToggle" aria-expanded="false" aria-controls="aiChatPanel" aria-label="Buka chat Moy AI">

    <span class="moy-ai-pin">
        <svg viewBox="0 0 100 120" aria-hidden="true">
            <defs>
                <linearGradient id="moyPinGradient" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#e9c7ff"/>
                    <stop offset="45%" stop-color="#a855f7"/>
                    <stop offset="100%" stop-color="#6420c4"/>
                </linearGradient>
            </defs>

            <path d="M50 5 C25 5 9 23 9 47 C9 75 35 99 50 115 C65 99 91 75 91 47 C91 23 75 5 50 5Z"
                  fill="url(#moyPinGradient)"
                  stroke="#e9d5ff"
                  stroke-width="2.5"/>

            <circle cx="50" cy="46" r="27" fill="#f5eaff"/>
            <circle cx="40" cy="41" r="3.2" fill="#6420a5"/>
            <circle cx="60" cy="41" r="3.2" fill="#6420a5"/>

            <path d="M38 53 Q50 67 62 53"
                  fill="none"
                  stroke="#6420a5"
                  stroke-width="3.5"
                  stroke-linecap="round"/>
        </svg>
    </span>

    <span class="moy-ai-label">Moy AI</span>
    <span class="ai-ping" aria-hidden="true"></span>

</button>


    <section class="maply-ai-panel" id="aiChatPanel" hidden aria-label="Chat Moy AI">
        <header class="maply-ai-header">
            <div class="maply-ai-heading">
                <span class="maply-ai-avatar"><img src="{{ $moyLogo }}" alt="Logo Moy AI" width="40" height="40"></span>
                <span>
                    <strong>Moy AI</strong>
                    <small><span class="maply-ai-dot"></span> Asisten kos Maply</small>
                </span>
            </div>
            <div class="maply-ai-actions">
                <button type="button" id="aiChatMin" aria-label="Minimalkan chat"><i class="fa-solid fa-minus"></i></button>
                <button type="button" id="aiChatNew" aria-label="Percakapan baru"><i class="fa-solid fa-rotate-left"></i></button>
                <button type="button" id="aiChatClose" aria-label="Tutup chat"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </header>

        <div class="maply-ai-messages" id="aiChatMessages" aria-live="polite">
            <div class="ai-msg ai-msg--ai">
                <span class="ai-msg-avatar"><img src="{{ $moyLogo }}" alt="" width="26" height="26"></span>
                <div class="ai-msg-body">
                    <p class="ai-bubble">Halo! Saya Moy AI, asisten kos Maply. Mau cari kos, atau ada hal lain yang ingin ditanyakan?</p>
                </div>
            </div>
        </div>

        <div class="ai-suggest" id="aiChatSuggest" aria-label="Saran pertanyaan">
            <button type="button" data-ask="Cari kos dekat kampus">Cari kos dekat kampus</button>
            <button type="button" data-ask="Rekomendasi kos sesuai budget">Sesuai budget</button>
            <button type="button" data-ask="Cari kos dengan fasilitas Wi-Fi">Kos ber-Wi-Fi</button>
        </div>

        <form id="aiChatForm" autocomplete="off">
            <textarea id="aiChatInput" rows="1" maxlength="500" required placeholder="Tulis pesan untuk Moy AI…" aria-label="Pesan"></textarea>
            <button type="submit" id="aiChatSend" aria-label="Kirim pesan"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
        <small class="maply-ai-note">Jawaban AI dapat keliru.</small>
    </section>
</div>

<script>
(()=> {
    'use strict';
    const toggle=document.getElementById('aiChatToggle');
    const panel=document.getElementById('aiChatPanel');
    const closeBtn=document.getElementById('aiChatClose');
    const minBtn=document.getElementById('aiChatMin');
    const newChat=document.getElementById('aiChatNew');
    const suggest=document.getElementById('aiChatSuggest');
    const form=document.getElementById('aiChatForm');
    const input=document.getElementById('aiChatInput');
    const send=document.getElementById('aiChatSend');
    const messages=document.getElementById('aiChatMessages');
    if(!toggle||!panel||!form)return;

    const moyLogo=@json($moyLogo);
    const greeting='Halo! Saya Moy AI, asisten kos Maply. Mau cari kos, atau ada hal lain yang ingin ditanyakan?';
    let history=[];
    let conversationSummary="";

    const nowTime=()=>new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
    function scrollDown(){messages.scrollTop=messages.scrollHeight;}
    function addMsg(text,side,withTime){
        const wrap=document.createElement('div');
        wrap.className='ai-msg ai-msg--'+side;
        let body=wrap;
        if(side!=='user'){
            const av=document.createElement('span');
            av.className='ai-msg-avatar';
            const img=document.createElement('img');
            img.src=moyLogo;img.alt='';img.width=26;img.height=26;
            av.appendChild(img);
            wrap.appendChild(av);
            body=document.createElement('div');
            body.className='ai-msg-body';
            wrap.appendChild(body);
        }
        const el=document.createElement('p');
        el.className=side==='user'?'ai-user':'ai-bubble';
        el.textContent=text;
        body.appendChild(el);
        if(withTime){const t=document.createElement('time');t.textContent=nowTime();body.appendChild(t);}
        messages.appendChild(wrap);
        scrollDown();
        return el;
    }
    function addTyping(){
        const wrap=document.createElement('div');
        wrap.className='ai-msg ai-msg--ai';
        wrap.id='aiTypingMsg';
        const av=document.createElement('span');
        av.className='ai-msg-avatar';
        const img=document.createElement('img');
        img.src=moyLogo;img.alt='';img.width=26;img.height=26;
        av.appendChild(img);
        wrap.appendChild(av);
        const body=document.createElement('div');
        body.className='ai-msg-body';
        body.innerHTML='<p class="ai-bubble ai-typing"><span class="dots"><i></i><i></i><i></i></span></p>';
        wrap.appendChild(body);
        messages.appendChild(wrap);
        scrollDown();
        return wrap;
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
        addMsg(greeting,'ai',false);
        input.focus();
    }

    // Buka / tutup / minimalkan
    function setPanel(open){
        panel.hidden=!open;
        toggle.setAttribute('aria-expanded',String(open));
        if(open)input.focus();
    }
    toggle.addEventListener('click',()=>setPanel(panel.hidden));
    closeBtn.addEventListener('click',()=>setPanel(false));
    minBtn.addEventListener('click',()=>{
        const min=panel.classList.toggle('is-min');
        minBtn.setAttribute('aria-label',min?'Perbesar chat':'Minimalkan chat');
        if(!min)input.focus();
    });
    newChat.addEventListener('click',resetChat);

    // Saran cepat: isi dan kirim langsung
    if(suggest) suggest.addEventListener('click',(e)=>{
        const b=e.target.closest('[data-ask]');
        if(!b||send.disabled)return;
        input.value=b.dataset.ask;
        form.requestSubmit ? form.requestSubmit() : form.dispatchEvent(new Event('submit',{cancelable:true}));
    });

    // Enter kirim, Shift+Enter baris baru, tinggi mengikuti isi
    input.addEventListener('keydown',(e)=>{
        if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();form.requestSubmit?form.requestSubmit():form.dispatchEvent(new Event('submit',{cancelable:true}));}
    });
    input.addEventListener('input',()=>{input.style.height='auto';input.style.height=Math.min(input.scrollHeight,92)+'px';});

    form.addEventListener('submit',async event=>{
        event.preventDefault();
        const message=input.value.trim();
        if(!message||send.disabled)return;

        addMsg(message,'user',true);
        input.value='';
        input.style.height='auto';
        const loading=addTyping();
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

            loading.remove();
            addMsg(data.message||'Maaf, saya belum dapat menjawab.','ai',true);
            history.push({role:'user',text:message});
            history.push({role:'model',text:data.message||''});
            if(typeof data.summary==='string')conversationSummary=data.summary;
            // Simpan maksimal 16 giliran/pesan di browser; server akan merangkum bagian lama.
            history=history.slice(-16);
            addKostCards(data.items||[]);
        }catch(error){
            loading.remove();
            addMsg(error.message||'Tidak dapat menghubungi Moy AI. Silakan coba lagi.','ai',true);
        }finally{
            send.disabled=false;
            input.disabled=false;
            input.focus();
            scrollDown();
        }
    });
})();
</script>

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
