/* Maply — modul peta (Leaflet + OpenStreetMap). Semua teks dari data dimasukkan lewat textContent (aman dari XSS). */
(function (global) {
  'use strict';
  const TILE_URL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  const ATTRIBUTION = '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>';

  function make(tag, cls, text) {
    const n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text !== undefined && text !== null) n.textContent = text;
    return n;
  }
  const rupiah = (v) => (v === null || v === undefined ? 'Harga belum diketahui' : 'Rp ' + Number(v).toLocaleString('id-ID') + ' / bulan');
  const valid = (a, b) => typeof a === 'number' && typeof b === 'number' && isFinite(a) && isFinite(b);

  function baseMap(id, center, zoom) {
    const el = document.getElementById(id);
    if (!el) return null;
    if (!global.L) {
      el.classList.add('map-fallback');
      el.textContent = 'Peta tidak dapat dimuat. Periksa koneksi internet Anda lalu muat ulang halaman.';
      return null;
    }
    const map = L.map(el, { scrollWheelZoom: false }).setView(center, zoom);
    L.tileLayer(TILE_URL, { maxZoom: 19, attribution: ATTRIBUTION }).addTo(map);
    map.on('click', () => map.scrollWheelZoom.enable());
    map.on('mouseout', () => map.scrollWheelZoom.disable());
    return map;
  }

  function pinIcon(kind) {
    const icons = {
      'ref': 'fa-building-columns',
      'kampus': 'fa-graduation-cap',
      'house': 'fa-house',
    };
    const icon = icons[kind] || 'fa-house';
    return L.divIcon({
      className: 'map-pin-wrap',
      html: '<span class="map-pin' + (kind ? ' map-pin--' + kind : '') + '"><i class="fa-solid ' + icon + '"></i></span>',
      iconSize: [34, 34], iconAnchor: kind === 'kampus' ? [17, 17] : [10, 40], popupAnchor: kind === 'kampus' ? [0, -18] : [6, -38]
    });
  }

  function row(box, label, value) {
    if (value === null || value === undefined || value === '') return;
    const r = make('div', 'map-popup__row');
    r.appendChild(make('b', null, label + ': '));
    r.appendChild(make('span', null, value));
    box.appendChild(r);
  }

  function popupNode(k, refName) {
    const box = make('div', 'map-popup');
    box.appendChild(make('strong', 'map-popup__title', k.nama));
    if (k.contoh) box.appendChild(make('span', 'badge badge--muted', 'Data contoh'));
    box.appendChild(make('div', 'map-popup__price', rupiah(k.harga)));
    row(box, 'Alamat', [k.alamat, k.kecamatan, k.kota].filter(Boolean).join(', '));
    row(box, 'Jenis', k.tipe && k.tipe !== 'Belum diketahui' ? 'Kos ' + k.tipe : 'Belum diketahui');
    row(box, 'Fasilitas', k.fasilitas && k.fasilitas.length ? k.fasilitas.join(', ') : 'Belum ada data');
    if (k.koordinat === 'perkiraan' || (k.koordinat && String(k.koordinat).toLowerCase().includes('perkiraan'))) {
      row(box, 'Lokasi', 'Perkiraan area/jalan untuk menjaga privasi');
    }
    if (typeof k.jarak === 'number') row(box, 'Jarak', k.jarak.toFixed(1).replace('.', ',') + ' km dari ' + (refName || 'titik acuan'));
    row(box, 'Status data', k.statusData);
    const actions = make('div', 'map-popup__actions');
    const detail = make('a', 'map-popup__link', 'Lihat detail');
    detail.href = k.url;
    const gm = make('a', 'map-popup__link map-popup__link--ghost', 'Google Maps');
    gm.href = k.maps; gm.target = '_blank'; gm.rel = 'noopener';
    actions.appendChild(detail); actions.appendChild(gm);
    box.appendChild(actions);
    return box;
  }

  function refPopup(ref) {
    const box = make('div', 'map-popup');
    box.appendChild(make('strong', 'map-popup__title', ref.nama));
    box.appendChild(make('div', 'map-popup__meta', 'Titik acuan jarak' + (ref.terverifikasi ? '' : ' (koordinat belum terverifikasi)')));
    return box;
  }

  /** Popup titik kampus: nama, alamat, jumlah kos terdekat, tombol cari kos. */
  function campusPopup(c) {
    const box = make('div', 'map-popup');
    box.appendChild(make('strong', 'map-popup__title', c.nama));
    const alamat = [c.alamat, c.kecamatan, c.kota].filter(Boolean).join(', ');
    if (alamat) box.appendChild(make('div', 'map-popup__meta', alamat));
    const n = typeof c.kos_sekitar === 'number' ? c.kos_sekitar : 0;
    box.appendChild(make('div', 'map-popup__meta', 'Kos dalam radius 2 km: ' + (n > 0 ? n : 'belum ada')));
    const actions = make('div', 'map-popup__actions');
    const cari = make('a', 'map-popup__link', 'Cari kos dekat sini');
    cari.href = c.url;
    actions.appendChild(cari);
    box.appendChild(actions);
    return box;
  }

  /**
   * Layer titik kampus dengan tombol toggle "Tampilkan kampus" (poin 9).
   * Marker di-cluster bila plugin markercluster tersedia.
   */
  function addCampusLayer(map, campuses, bounds) {
    const list = (campuses || []).filter((c) => valid(c.lat, c.lng));
    if (!list.length) return null;
    const group = global.L.markerClusterGroup
      ? L.markerClusterGroup({ maxClusterRadius: 40, showCoverageOnHover: false, disableClusteringAtZoom: 15 })
      : L.layerGroup();
    list.forEach((c) => {
      const m = L.marker([c.lat, c.lng], { icon: pinIcon('kampus'), title: c.nama });
      m.bindPopup(campusPopup(c), { maxWidth: 300 });
      group.addLayer(m);
      bounds.extend([c.lat, c.lng]);
    });
    group.addTo(map);

    // Kontrol toggle khusus Maply di pojok kiri atas
    const ToggleControl = L.Control.extend({
      options: { position: 'topleft' },
      onAdd: function () {
        const div = L.DomUtil.create('div', 'leaflet-bar maply-layer-toggle');
        const a = L.DomUtil.create('a', 'is-on', div);
        a.href = '#';
        a.setAttribute('role', 'button');
        a.textContent = 'Kampus: on';
        let on = true;
        L.DomEvent.on(a, 'click', (e) => {
          L.DomEvent.preventDefault(e);
          on = !on;
          if (on) map.addLayer(group); else map.removeLayer(group);
          a.textContent = 'Kampus: ' + (on ? 'on' : 'off');
          a.classList.toggle('is-on', on);
        });
        return div;
      }
    });
    new ToggleControl().addTo(map);
    return group;
  }

  /** Peta daftar/beranda. cfg: {id, ref:{lat,lng,nama,singkat,terverifikasi}, items:[], campuses:[], radiusKm, showRef} */
  function initList(cfg) {
    const ref = cfg.ref;
    const map = baseMap(cfg.id, [ref.lat, ref.lng], 13);
    if (!map) return null;
    const markers = {};
    const cluster = global.L.markerClusterGroup ? L.markerClusterGroup({ maxClusterRadius: 45, spiderfyOnMaxZoom: true, showCoverageOnHover: false }).addTo(map) : null;
    const bounds = L.latLngBounds([[ref.lat, ref.lng]]);
    let refMarker = null;

    if (cfg.showRef !== false) {
      refMarker = L.marker([ref.lat, ref.lng], { icon: pinIcon('ref'), title: ref.nama, zIndexOffset: 500 }).addTo(map);
      refMarker.bindPopup(refPopup(ref), { maxWidth: 260 });
    }
    // Titik semua kampus (koordinat terverifikasi) — poin 8/9
    addCampusLayer(map, cfg.campuses, bounds);
    (cfg.items || []).forEach((k) => {
      if (!valid(k.lat, k.lng)) return; // data tanpa koordinat dilewati
      const markerKind = k.contoh ? 'contoh' : (k.statusCode === 'terverifikasi' ? 'verified' : 'review');
      const m = L.marker([k.lat, k.lng], { icon: pinIcon(markerKind), title: k.nama });
      if (cluster) cluster.addLayer(m); else m.addTo(map);
      m.bindPopup(popupNode(k, ref.singkat), { maxWidth: 300 });
      markers[k.id] = m;
      bounds.extend([k.lat, k.lng]);
    });
    if (cfg.radiusKm) {
      const c = L.circle([ref.lat, ref.lng], { radius: cfg.radiusKm * 1000, color: '#8B3DFF', fillColor: '#B56CFF', fillOpacity: 0.08, weight: 2, dashArray: '6 6' }).addTo(map);
      bounds.extend(c.getBounds());
    }

    const count = Object.keys(markers).length;
    global.MaplyStats = { markers: count };
    if (cfg.countEl) {
      const ce = document.getElementById(cfg.countEl);
      if (ce && ce.querySelector('span')) ce.querySelector('span').textContent = count + ' marker ditampilkan di peta';
    }
    const fitAll = () => { if (count || cfg.radiusKm) map.fitBounds(bounds, { padding: [32, 32], maxZoom: 15 }); else map.setView([ref.lat, ref.lng], 13); };
    fitAll();

    document.querySelectorAll('[data-map-fit]').forEach((b) => b.addEventListener('click', fitAll));
    document.querySelectorAll('[data-map-ref]').forEach((b) => b.addEventListener('click', () => {
      map.setView([ref.lat, ref.lng], 15);
      if (refMarker) setTimeout(() => refMarker.openPopup(), 300);
    }));
    document.addEventListener('click', (e) => {
      const b = e.target.closest('[data-focus-kos]');
      if (!b) return;
      const m = markers[Number(b.dataset.focusKos)];
      if (!m) return;
      e.preventDefault();
      const el = document.getElementById(cfg.id);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
      map.setView(m.getLatLng(), Math.max(map.getZoom(), 14));
      if (cluster && cluster.zoomToShowLayer) cluster.zoomToShowLayer(m, () => m.openPopup()); else setTimeout(() => m.openPopup(), 400);
    });
    return map;
  }

  /** Peta detail satu kos. cfg: {id, kos, ref} */
  function initDetail(cfg) {
    const k = cfg.kos;
    if (!k || !valid(k.lat, k.lng)) return null;
    const approximate = k.koordinat === 'perkiraan' || (k.koordinat && String(k.koordinat).toLowerCase().includes('perkiraan'));
    const map = baseMap(cfg.id, [k.lat, k.lng], approximate ? 14 : 16);
    if (!map) return null;
    const markerKind = k.contoh ? 'contoh' : (k.statusCode === 'terverifikasi' ? 'verified' : 'review');
    const m = L.marker([k.lat, k.lng], { icon: pinIcon(markerKind), title: k.nama }).addTo(map);
    m.bindPopup(popupNode(k, cfg.ref && cfg.ref.singkat), { maxWidth: 270 }).openPopup();
    if (cfg.ref && valid(cfg.ref.lat, cfg.ref.lng)) {
      const r = L.marker([cfg.ref.lat, cfg.ref.lng], { icon: pinIcon('ref'), title: cfg.ref.nama }).addTo(map);
      r.bindPopup(refPopup(cfg.ref));
      L.polyline([[k.lat, k.lng], [cfg.ref.lat, cfg.ref.lng]], { color: '#8B3DFF', weight: 2, dashArray: '6 6' }).addTo(map);
    }
    return map;
  }

  /** Pemilih titik untuk formulir admin. cfg: {id, latId, lngId, center:{lat,lng}} */
  function initPicker(cfg) {
    const latEl = document.getElementById(cfg.latId);
    const lngEl = document.getElementById(cfg.lngId);
    if (!latEl || !lngEl) return null;
    const parse = (v) => parseFloat(String(v).replace(',', '.'));
    const ok = (a, b) => isFinite(a) && isFinite(b) && Math.abs(a) <= 90 && Math.abs(b) <= 180;
    const a0 = parse(latEl.value), b0 = parse(lngEl.value);
    const has = ok(a0, b0);
    const map = baseMap(cfg.id, has ? [a0, b0] : [cfg.center.lat, cfg.center.lng], has ? 16 : 13);
    if (!map) return null;
    let marker = null;

    const write = (lat, lng) => { latEl.value = lat.toFixed(6); lngEl.value = lng.toFixed(6); };
    const place = (lat, lng, fromInput) => {
      if (!marker) {
        marker = L.marker([lat, lng], { draggable: true, icon: pinIcon('') }).addTo(map);
        marker.on('dragend', () => { const p = marker.getLatLng(); write(p.lat, p.lng); });
      } else {
        marker.setLatLng([lat, lng]);
      }
      if (!fromInput) write(lat, lng);
    };
    if (has) place(a0, b0, true);

    map.on('click', (e) => place(e.latlng.lat, e.latlng.lng, false));
    const fromInputs = () => {
      const a = parse(latEl.value), b = parse(lngEl.value);
      if (ok(a, b)) { place(a, b, true); map.setView([a, b], Math.max(map.getZoom(), 16)); }
    };
    latEl.addEventListener('change', fromInputs);
    lngEl.addEventListener('change', fromInputs);

    const clear = document.querySelector('[data-picker-clear]');
    if (clear) clear.addEventListener('click', () => {
      if (marker) { map.removeLayer(marker); marker = null; }
      latEl.value = ''; lngEl.value = '';
    });
    const locate = document.querySelector('[data-picker-locate]');
    if (locate) locate.addEventListener('click', () => {
      if (!navigator.geolocation) { alert('Peramban Anda tidak mendukung geolokasi.'); return; }
      navigator.geolocation.getCurrentPosition(
        (pos) => { place(pos.coords.latitude, pos.coords.longitude, false); map.setView([pos.coords.latitude, pos.coords.longitude], 17); },
        () => alert('Lokasi tidak dapat diakses. Izinkan akses lokasi atau klik langsung pada peta.')
      );
    });
    return map;
  }

  global.MaplyMap = { initList: initList, initDetail: initDetail, initPicker: initPicker };
})(window);
