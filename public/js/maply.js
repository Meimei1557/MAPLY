/* Maply — interaksi umum (tanpa dependensi) */
(function () {
  'use strict';
  const $ = (s, r) => (r || document).querySelector(s);
  const $$ = (s, r) => Array.from((r || document).querySelectorAll(s));
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Toggle tema gelap/terang (poin 1) ---------- */
  const THEME_KEY = 'maply-theme';
  const THEME_COLOR = { dark: '#10091F', light: '#F4F1FB' };
  function terapkanTema(tema, simpan) {
    document.documentElement.setAttribute('data-theme', tema);
    const meta = document.getElementById('meta-theme-color');
    if (meta) meta.setAttribute('content', THEME_COLOR[tema] || THEME_COLOR.dark);
    $$('[data-theme-toggle]').forEach((b) => {
      b.setAttribute('aria-pressed', tema === 'light' ? 'true' : 'false');
      b.title = tema === 'light' ? 'Ganti ke mode gelap' : 'Ganti ke mode terang';
    });
    if (simpan) { try { localStorage.setItem(THEME_KEY, tema); } catch (e) { /* abaikan */ } }
  }
  $$('[data-theme-toggle]').forEach((b) => b.addEventListener('click', () => {
    const sekarang = document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
    terapkanTema(sekarang === 'light' ? 'dark' : 'light', true);
  }));
  // Ikuti perubahan preferensi sistem bila pengguna belum memilih manual
  try {
    const media = window.matchMedia('(prefers-color-scheme: light)');
    const handler = (e) => {
      let manual = null;
      try { manual = localStorage.getItem(THEME_KEY); } catch (err) { /* abaikan */ }
      if (manual !== 'dark' && manual !== 'light') terapkanTema(e.matches ? 'light' : 'dark', false);
    };
    if (media.addEventListener) media.addEventListener('change', handler);
    else if (media.addListener) media.addListener(handler);
  } catch (e) { /* abaikan */ }
  terapkanTema(document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark', false);


  /* ---------- Sidebar drawer (mobile) ---------- */
  const sidebar = $('#sidebar');
  const backdrop = $('#sidebarBackdrop');
  const burger = $('#navToggle');
  function setDrawer(open) {
    if (!sidebar) return;
    sidebar.classList.toggle('open', open);
    if (backdrop) backdrop.hidden = !open;
    requestAnimationFrame(() => backdrop && backdrop.classList.toggle('show', open));
    if (burger) burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open && window.innerWidth <= 960 ? 'hidden' : '';
  }
  if (burger) burger.addEventListener('click', () => setDrawer(!sidebar.classList.contains('open')));
  if (backdrop) backdrop.addEventListener('click', () => setDrawer(false));
  // Tutup drawer saat tautan sidebar diklik di layar kecil
  $$('.sidebar .snav').forEach((a) => a.addEventListener('click', () => { if (window.innerWidth <= 960) setDrawer(false); }));
  window.addEventListener('keydown', (e) => { if (e.key === 'Escape') setDrawer(false); });

  /* ---------- Menu pengguna: tutup saat klik di luar / tekan Escape ---------- */
  document.addEventListener('click', (e) => {
    $$('details.user-menu[open]').forEach((d) => { if (!d.contains(e.target)) d.removeAttribute('open'); });
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') $$('details.user-menu[open]').forEach((d) => d.removeAttribute('open'));
  });

  /* ---------- Notifikasi ---------- */
  $$('.flash [data-dismiss]').forEach((b) => b.addEventListener('click', () => b.closest('.flash').remove()));
  setTimeout(() => {
    $$('.flash.success').forEach((f) => { f.classList.add('hide'); setTimeout(() => f.remove(), 450); });
  }, 6000);

  /* ---------- Tampil/sembunyi password ---------- */
  $$('[data-toggle-password]').forEach((btn) => btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.togglePassword);
    if (!input) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    const i = btn.querySelector('i');
    if (i) i.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
  }));

  /* ---------- Cegah kirim ganda + indikator loading ---------- */
  $$('form[data-once]').forEach((form) => form.addEventListener('submit', () => {
    const btn = $('button[type=submit]', form);
    if (btn) {
      btn.disabled = true;
      btn.classList.add('loading');
      if (!btn.dataset.label) btn.dataset.label = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses…';
    }
  }));
  window.addEventListener('pageshow', () => {
    $$('form[data-once] button[type=submit]').forEach((b) => {
      b.disabled = false;
      b.classList.remove('loading');
      if (b.dataset.label) b.innerHTML = b.dataset.label;
    });
  });

  /* ---------- Urutan/pilihan yang langsung mengirim form ---------- */
  $$('[data-autosubmit]').forEach((el) => el.addEventListener('change', () => {
    const f = el.form || el.closest('form');
    if (f) f.submit();
  }));

  /* ---------- Panel filter (mobile) ---------- */
  const panel = $('#filterPanel');
  $$('[data-toggle-filters]').forEach((b) => b.addEventListener('click', () => {
    if (!panel) return;
    const open = panel.classList.toggle('open');
    b.setAttribute('aria-expanded', open ? 'true' : 'false');
  }));

  /* ---------- Chip "Radius 3 km" mengikuti kampus terpilih di dropdown "Dekat" ---------- */
  const heroKampus = $('#hk');
  $$('[data-radius-chip]').forEach((chip) => {
    const apply = () => {
      try {
        const url = new URL(chip.getAttribute('href'), window.location.origin);
        if (heroKampus && heroKampus.value) url.searchParams.set('kampus', heroKampus.value);
        else url.searchParams.delete('kampus');
        chip.setAttribute('href', url.pathname + url.search);
      } catch (e) { /* biarkan href bawaan */ }
    };
    apply();
    if (heroKampus) heroKampus.addEventListener('change', apply);
  });

  /* ---------- Tombol kembali: pakai riwayat browser bila berasal dari halaman Maply ---------- */
  $$('[data-back]').forEach((a) => a.addEventListener('click', (e) => {
    try {
      if (document.referrer && new URL(document.referrer).origin === location.origin && history.length > 1) {
        e.preventDefault();
        history.back();
      }
    } catch (err) { /* biarkan tautan berjalan normal */ }
  }));

  /* ---------- Foto gagal dimuat -> tampilkan placeholder ---------- */
  document.addEventListener('error', (e) => {
    const t = e.target;
    if (t && t.tagName === 'IMG' && t.hasAttribute('data-fallback')) {
      const box = t.closest('.photo-box');
      if (box) box.classList.add('no-photo');
      t.remove();
    }
  }, true);

  /* ---------- Dialog konfirmasi hapus ---------- */
  const dlg = $('#confirmDialog');
  const triggers = $$('[data-confirm-delete]');
  if (dlg && typeof dlg.showModal === 'function') {
    const form = $('form', dlg);
    const nameEl = $('[data-dlg-name]', dlg);
    triggers.forEach((btn) => btn.addEventListener('click', () => {
      form.action = btn.dataset.action;
      nameEl.textContent = btn.dataset.name || 'data ini';
      dlg.showModal();
    }));
    $$('[data-dlg-close]', dlg).forEach((b) => b.addEventListener('click', () => dlg.close()));
    dlg.addEventListener('click', (e) => { if (e.target === dlg) dlg.close(); });
  } else {
    triggers.forEach((btn) => btn.addEventListener('click', () => {
      if (!window.confirm('Hapus "' + (btn.dataset.name || 'data ini') + '"? Tindakan ini tidak dapat dibatalkan.')) return;
      const meta = $('meta[name="csrf-token"]');
      const f = document.createElement('form');
      f.method = 'POST';
      f.action = btn.dataset.action;
      [['_method', 'DELETE'], ['_token', meta ? meta.content : '']].forEach(([n, v]) => {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = n; i.value = v; f.appendChild(i);
      });
      document.body.appendChild(f);
      f.submit();
    }));
  }

  /* ---------- Bagikan & favorit (halaman detail) ---------- */
  const feedback = $('#actionFeedback');
  const say = (msg) => { if (feedback) feedback.textContent = msg; };

  $$('[data-share]').forEach((btn) => btn.addEventListener('click', async () => {
    const data = { title: btn.dataset.title || document.title, text: 'Lihat kos ini di Maply', url: window.location.href };
    try {
      if (navigator.share) {
        await navigator.share(data);
      } else if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(data.url);
        say('Tautan berhasil disalin.');
      } else {
        window.prompt('Salin tautan ini:', data.url);
      }
    } catch (err) {
      if (!err || err.name !== 'AbortError') say('Tautan tidak dapat dibagikan. Salin dari bilah alamat browser.');
    }
  }));

  $$('[data-favorite]').forEach((btn) => {
    const key = 'maply-favorite-' + btn.dataset.favorite;
    const read = () => { try { return localStorage.getItem(key) === '1'; } catch (e) { return false; } };
    const write = (v) => { try { localStorage.setItem(key, v ? '1' : '0'); } catch (e) { /* abaikan */ } };
    const render = (on) => {
      btn.classList.toggle('liked', on);
      btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      const i = $('i', btn);
      if (i) i.className = on ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
      btn.title = on ? 'Hapus dari favorit' : 'Simpan ke favorit';
    };
    let on = read();
    render(on);
    btn.addEventListener('click', () => {
      on = !on; write(on); render(on);
      say(on ? 'Kos disimpan ke favorit (tersimpan di browser ini).' : 'Kos dihapus dari favorit.');
    });
  });

  /* ---------- Gulir ke peta pada halaman detail ---------- */
  $$('[data-scroll-map]').forEach((b) => b.addEventListener('click', () => {
    const t = document.getElementById('lokasi');
    if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }));

  /* ---------- Parallax halus pada dekorasi halaman login ---------- */
  const fx = $('#authFx');
  if (fx && !reducedMotion && window.matchMedia('(pointer: fine)').matches) {
    let raf = null;
    window.addEventListener('mousemove', (e) => {
      if (raf) return;
      raf = requestAnimationFrame(() => {
        const dx = (e.clientX / window.innerWidth - 0.5);
        const dy = (e.clientY / window.innerHeight - 0.5);
        $$('[data-depth]', fx).forEach((el) => {
          const d = parseFloat(el.dataset.depth) || 12;
          el.style.transform = 'translate(' + (-dx * d).toFixed(1) + 'px,' + (-dy * d).toFixed(1) + 'px)';
        });
        raf = null;
      });
    }, { passive: true });
  }
})();
