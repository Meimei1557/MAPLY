<?php

namespace Tests\Feature;

use App\Models\Fasilitas;
use App\Models\Kos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit lengkap filter pencarian (REVISI 5):
 * a) form hero (q, kampus, tipe, harga_max),
 * b) chip cepat dashboard (6 chip, radius mengikuti dropdown "Dekat"),
 * c) halaman /kos (semua filter, urutan, Terapkan/Reset, toggle panel, nilai terpilih),
 * d) peta mengikuti filter,
 * e) paginasi membawa query string,
 * f) kombinasi filter = irisan; data tanpa koordinat dilewati tanpa error.
 */
class KosSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    // ------------------------------------------------------------- dasar

    public function test_halaman_berjalan_saat_database_kosong(): void
    {
        $this->get('/dashboard')->assertOk();
        $this->get('/kos')->assertOk()->assertSee('Belum ada data kos');
    }

    public function test_pencarian_tanpa_hasil_tidak_error(): void
    {
        Kos::factory()->create(['nama_kos' => 'Kos Melati']);
        $this->get('/kos?q=tidak-ada-xyz')->assertOk()->assertSee('Belum ada kos yang cocok');
    }

    public function test_cari_berdasarkan_nama_dan_alamat(): void
    {
        Kos::factory()->create(['nama_kos' => 'Kos Melati', 'alamat' => 'Jl. A']);
        Kos::factory()->create(['nama_kos' => 'Kos Mawar', 'alamat' => 'Jl. Bukit Besar']);
        $this->get('/kos?q=Melati')->assertSee('Kos Melati')->assertDontSee('Kos Mawar');
        $this->get('/kos?q=Bukit')->assertSee('Kos Mawar')->assertDontSee('Kos Melati');
    }

    public function test_filter_harga_tipe_dan_fasilitas(): void
    {
        $a = Kos::factory()->create(['nama_kos' => 'Murah Putri', 'harga' => 600000, 'tipe' => 'Putri']);
        $b = Kos::factory()->create(['nama_kos' => 'Mahal Putra', 'harga' => 2000000, 'tipe' => 'Putra']);
        Fasilitas::create(['kos_id' => $b->id, 'nama_fasilitas' => 'AC']);

        $this->get('/kos?harga_max=1000000')->assertSee('Murah Putri')->assertDontSee('Mahal Putra');
        $this->get('/kos?harga_min=1500000')->assertSee('Mahal Putra')->assertDontSee('Murah Putri');
        $this->get('/kos?tipe=Putri')->assertSee('Murah Putri')->assertDontSee('Mahal Putra');
        $this->get('/kos?fasilitas[]=AC')->assertSee('Mahal Putra')->assertDontSee('Murah Putri');
    }

    public function test_data_tanpa_koordinat_dan_tanpa_harga_tidak_error(): void
    {
        Kos::factory()->tanpaKoordinat()->create(['nama_kos' => 'Tanpa Titik', 'harga' => null]);
        $this->get('/kos')->assertOk()->assertSee('Tanpa Titik')->assertSee('belum memiliki koordinat');
        $this->get('/kos?radius=3')->assertOk()->assertDontSee('Tanpa Titik');
        $this->get('/kos/'.Kos::first()->id)->assertOk()->assertSee('Harga belum diketahui');
    }

    // ------------------------------------- a) form pencarian hero dashboard

    public function test_form_pencarian_hero_menerapkan_semua_parameter(): void
    {
        // Kos dekat Ampera (pusat kota) bertipe Putri murah — cocok dengan semua parameter.
        Kos::factory()->create([
            'nama_kos' => 'Kos Ampera Putri',
            'tipe' => 'Putri',
            'harga' => 800000,
            'latitude' => -2.9880,
            'longitude' => 104.7620,
        ]);
        // Penjebak: lokasinya dekat tapi tipe Putra.
        Kos::factory()->create([
            'nama_kos' => 'Kos Ampera Putra',
            'tipe' => 'Putra',
            'harga' => 800000,
            'latitude' => -2.9890,
            'longitude' => 104.7621,
        ]);

        $res = $this->get('/kos?q=Ampera&kampus=ampera&tipe=Putri&harga_max=1000000');
        $res->assertOk()->assertSee('Kos Ampera Putri')->assertDontSee('Kos Ampera Putra');

        // Titik acuan yang dipakai adalah Ampera (bukan titik bawaan).
        $data = $res->original->gatherData();
        $this->assertSame('Pusat Kota (Ampera)', $data['ref']['singkat']);
    }

    public function test_form_hero_mengirim_ke_halaman_kos_dengan_field_lengkap(): void
    {
        $html = $this->get('/dashboard')->getContent();
        // Form hero submit ke /kos dan memuat 4 parameter: q, kampus, tipe, harga_max.
        $this->assertStringContainsString('name="q"', $html);
        $this->assertStringContainsString('name="kampus"', $html);
        $this->assertStringContainsString('name="tipe"', $html);
        $this->assertStringContainsString('name="harga_max"', $html);
        $this->assertStringContainsString('action="'.route('kos.index').'"', $html);
    }

    // ------------------------------------------ b) chip cepat di dashboard

    public function test_chip_cepat_dashboard_lengkap_enam_chip(): void
    {
        $html = $this->get('/dashboard')->getContent();

        foreach (['Kos Putri', 'Kos Putra', 'Kos Campur', 'Di bawah Rp1 juta', 'Ber-AC', 'Radius 3 km'] as $chip) {
            $this->assertStringContainsString($chip, $html);
        }
        // Setiap chip mengarah ke /kos dengan filter yang sesuai.
        $this->assertStringContainsString('tipe=Putri', $html);
        $this->assertStringContainsString('tipe=Putra', $html);
        $this->assertStringContainsString('tipe=Campur', $html);
        $this->assertStringContainsString('harga_max=1000000', $html);
        $this->assertStringContainsString('fasilitas', $html);
        $this->assertStringContainsString('radius=3', $html);
        // Penanda data-radius-chip untuk JS (radius mengikuti dropdown "Dekat").
        $this->assertStringContainsString('data-radius-chip', $html);
    }

    public function test_chip_fasilitas_ac_menerapkan_filter_fasilitas(): void
    {
        $ac = Kos::factory()->create(['nama_kos' => 'Kos BerAC']);
        Fasilitas::create(['kos_id' => $ac->id, 'nama_fasilitas' => 'AC']);
        Kos::factory()->create(['nama_kos' => 'Kos TanpaAC']);

        $this->get('/kos?fasilitas[0]=AC')->assertSee('Kos BerAC')->assertDontSee('Kos TanpaAC');
    }

    public function test_radius_mengikuti_kampus_terpilih_dan_abaikan_tanpa_koordinat(): void
    {
        // Titik acuan Ampera: -2.988946, 104.762056.
        Kos::factory()->create(['nama_kos' => 'Dekat Ampera', 'latitude' => -2.9885, 'longitude' => 104.7621]);
        Kos::factory()->create(['nama_kos' => 'Jauh Ampera', 'latitude' => -3.0500, 'longitude' => 104.8000]);
        Kos::factory()->tanpaKoordinat()->create(['nama_kos' => 'Tanpa Titik Ampera']);

        // Radius 3 km tanpa kampus eksplisit memakai titik bawaan (UNSRI Bukit Besar);
        // dengan kampus=ampera irisan berubah sesuai pilihan dropdown "Dekat".
        $this->get('/kos?radius=3&kampus=ampera')
            ->assertOk()
            ->assertSee('Dekat Ampera')
            ->assertDontSee('Jauh Ampera')
            ->assertDontSee('Tanpa Titik Ampera');

        // Radius dari titik bawaan tidak menampilkan kos dekat Ampera (±3,1 km dari Bukit Besar).
        $this->get('/kos?radius=2')->assertDontSee('Dekat Ampera');
    }

    // ------------------------------------------ c) halaman Cari Kos (/kos)

    public function test_filter_wilayah_kecamatan_verifikasi_dan_jenis_data(): void
    {
        Kos::factory()->create([
            'nama_kos' => 'Kos Plg Terverifikasi', 'kota' => 'Palembang',
            'kecamatan' => 'Kemuning', 'status_data' => 'terverifikasi',
        ]);
        Kos::factory()->create([
            'nama_kos' => 'Kos Indralaya Contoh', 'kota' => 'Indralaya',
            'kecamatan' => 'Indralaya', 'status_data' => 'contoh',
        ]);

        $this->get('/kos?wilayah=palembang')->assertSee('Kos Plg Terverifikasi')->assertDontSee('Kos Indralaya Contoh');
        $this->get('/kos?wilayah=indralaya')->assertSee('Kos Indralaya Contoh')->assertDontSee('Kos Plg Terverifikasi');
        $this->get('/kos?kecamatan=Kemuning')->assertSee('Kos Plg Terverifikasi')->assertDontSee('Kos Indralaya Contoh');
        $this->get('/kos?verifikasi=terverifikasi')->assertSee('Kos Plg Terverifikasi')->assertDontSee('Kos Indralaya Contoh');
        $this->get('/kos?data=contoh')->assertSee('Kos Indralaya Contoh')->assertDontSee('Kos Plg Terverifikasi');
        $this->get('/kos?data=publik')->assertSee('Kos Plg Terverifikasi')->assertDontSee('Kos Indralaya Contoh');
    }

    public function test_fasilitas_multikombinasi_menghasilkan_irisan(): void
    {
        $dua = Kos::factory()->create(['nama_kos' => 'Kos AC WiFi']);
        Fasilitas::create(['kos_id' => $dua->id, 'nama_fasilitas' => 'AC']);
        Fasilitas::create(['kos_id' => $dua->id, 'nama_fasilitas' => 'WiFi']);

        $satu = Kos::factory()->create(['nama_kos' => 'Kos WiFi Saja']);
        Fasilitas::create(['kos_id' => $satu->id, 'nama_fasilitas' => 'WiFi']);

        $this->get('/kos?fasilitas[0]=AC&fasilitas[1]=WiFi')
            ->assertSee('Kos AC WiFi')
            ->assertDontSee('Kos WiFi Saja');
    }

    public function test_pengurutan_lima_opsi(): void
    {
        Kos::factory()->create(['nama_kos' => 'Kos Budi', 'harga' => 900000, 'latitude' => -2.99, 'longitude' => 104.73]);
        Kos::factory()->create(['nama_kos' => 'Kos Ani', 'harga' => 500000, 'latitude' => -2.95, 'longitude' => 104.75]);
        Kos::factory()->create(['nama_kos' => 'Kos Cici', 'harga' => 1500000, 'latitude' => -2.97, 'longitude' => 104.74]);

        // termurah / termahal
        $this->get('/kos?urut=termurah')->assertSeeInOrder(['Kos Ani', 'Kos Budi', 'Kos Cici']);
        $this->get('/kos?urut=termahal')->assertSeeInOrder(['Kos Cici', 'Kos Budi', 'Kos Ani']);
        // nama (A–Z)
        $this->get('/kos?urut=nama')->assertSeeInOrder(['Kos Ani', 'Kos Budi', 'Kos Cici']);
        // terbaru (id terbesar lebih dulu): Cici dibuat terakhir
        $this->get('/kos?urut=terbaru')->assertSeeInOrder(['Kos Cici', 'Kos Budi', 'Kos Ani']);
        // terdekat dari titik bawaan UNSRI Bukit Besar (-2.986444, 104.734333):
        // jarak Budi < Cici < Ani.
        $this->get('/kos?urut=terdekat')->assertSeeInOrder(['Kos Budi', 'Kos Cici', 'Kos Ani']);
    }

    public function test_filter_form_memiliki_tombol_terapkan_reset_dan_toggle_panel(): void
    {
        $html = $this->get('/kos')->getContent();

        $this->assertStringContainsString('Terapkan', $html);
        $this->assertStringContainsString('Reset', $html);
        $this->assertStringContainsString('href="'.route('kos.index').'"', $html);
        $this->assertStringContainsString('data-toggle-filters', $html);
        $this->assertStringContainsString('id="filterPanel"', $html);
        $this->assertStringContainsString('id="filterForm"', $html);
        // Pilihan urutan tersedia lengkap.
        foreach (['terdekat', 'termurah', 'termahal', 'terbaru', 'nama'] as $urut) {
            $this->assertStringContainsString('value="'.$urut.'"', $html);
        }
    }

    public function test_nilai_filter_tetap_terpilih_setelah_submit(): void
    {
        Kos::factory()->create(['nama_kos' => 'Kos Kemuning', 'kecamatan' => 'Kemuning']);
        $html = $this->get('/kos?q=Kemuning&tipe=Putri&harga_max=1000000&verifikasi=perlu_konfirmasi&data=semua')->getContent();

        $this->assertStringContainsString('value="Kemuning"', $html); // input q mempertahankan nilai
        // Tipe kos berupa radio pill: tetap terpilih.
        $this->assertMatchesRegularExpression('/name="tipe" value="Putri" checked/', $html);
        // Select verifikasi dan data tetap memilih nilai yang dikirim.
        foreach (['verifikasi' => 'perlu_konfirmasi', 'data' => 'semua'] as $name => $value) {
            $this->assertMatchesRegularExpression(
                '/name="'.$name.'".*?<option value="'.preg_quote($value, '/').'" selected/s',
                $html,
                "Select $name harus tetap memilih $value setelah submit."
            );
        }
        // Harga maksimum terisi kembali pada input.
        $this->assertMatchesRegularExpression('/name="harga_max"[^>]*value="1000000"/', $html);
    }

    public function test_checkbox_fasilitas_tetap_tercentang_setelah_submit(): void
    {
        $kos = Kos::factory()->create();
        Fasilitas::create(['kos_id' => $kos->id, 'nama_fasilitas' => 'AC']);
        $html = $this->get('/kos?fasilitas[0]=AC')->getContent();

        $this->assertMatchesRegularExpression(
            '/name="fasilitas\[\]" value="AC" checked/',
            $html,
            'Checkbox AC harus tetap tercentang setelah submit.'
        );
    }

    public function test_paginasi_membawa_filter_pada_query_string(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Kos::factory()->create(['nama_kos' => 'Kos Pagu '.$i, 'tipe' => 'Putri']);
        }

        $html = $this->get('/kos?q=Pagu&tipe=Putri')->getContent();
        // Tautan halaman mempertahankan q dan tipe.
        $this->assertStringContainsString('q=Pagu', $html);
        $this->assertStringContainsString('tipe=Putri', $html);
        $this->get('/kos?q=Pagu&tipe=Putri&page=2')->assertOk()->assertSee('12 kos ditemukan');
    }

    // ------------------------------------------------- d) peta ikut filter

    public function test_marker_peta_mengikuti_filter_aktif(): void
    {
        Kos::factory()->create([
            'nama_kos' => 'Peta Putri', 'tipe' => 'Putri', 'harga' => 700000,
            'latitude' => -2.98, 'longitude' => 104.74,
        ]);
        Kos::factory()->create([
            'nama_kos' => 'Peta Putra', 'tipe' => 'Putra', 'harga' => 700000,
            'latitude' => -2.98, 'longitude' => 104.74,
        ]);

        $res = $this->get('/kos?tipe=Putri');
        $data = $res->original->gatherData();
        $mapItems = collect($data['mapItems']);
        $this->assertSame(1, $mapItems->count());
        $this->assertSame('Peta Putri', $mapItems->first()['nama']);
    }

    // --------------------------------- f) kombinasi beberapa filter sekaligus

    public function test_kombinasi_filter_menghasilkan_irisan_yang_benar(): void
    {
        // Cocok dengan SEMUA filter.
        Kos::factory()->create([
            'nama_kos' => 'Irisan Cocok', 'tipe' => 'Putri', 'harga' => 800000,
            'kota' => 'Palembang', 'kecamatan' => 'Kemuning',
            'latitude' => -2.9895, 'longitude' => 104.7620,
        ]);
        Fasilitas::create(['kos_id' => Kos::where('nama_kos', 'Irisan Cocok')->first()->id, 'nama_fasilitas' => 'AC']);

        // Gagal di harga.
        Kos::factory()->create([
            'nama_kos' => 'Irisan Mahal', 'tipe' => 'Putri', 'harga' => 3000000,
            'kota' => 'Palembang', 'kecamatan' => 'Kemuning',
            'latitude' => -2.9895, 'longitude' => 104.7620,
        ]);
        // Gagal di tipe.
        Kos::factory()->create([
            'nama_kos' => 'Irisan Putra', 'tipe' => 'Putra', 'harga' => 800000,
            'kota' => 'Palembang', 'kecamatan' => 'Kemuning',
            'latitude' => -2.9895, 'longitude' => 104.7620,
        ]);

        $res = $this->get('/kos?q=Irisan&tipe=Putri&harga_max=1000000&kecamatan=Kemuning&kampus=ampera&fasilitas[0]=AC&radius=3');
        $res->assertOk()->assertSee('Irisan Cocok')
            ->assertDontSee('Irisan Mahal')
            ->assertDontSee('Irisan Putra');

        $data = $res->original->gatherData();
        $this->assertSame(1, $data['total']);
    }

    public function test_parameter_tidak_valid_ditolak_validasi(): void
    {
        Kos::factory()->create(['nama_kos' => 'Kos Normal']);
        $this->get('/kos?tipe=Ngawur')->assertSessionHasErrors('tipe');
        $this->get('/kos?radius=999')->assertSessionHasErrors('radius');
        $this->get('/kos?urut=acak')->assertSessionHasErrors('urut');
        $this->get('/kos?wilayah=asing')->assertSessionHasErrors('wilayah');
    }

    // ------------------------------------------------- detail & keamanan

    public function test_detail_dan_parameter_tidak_valid(): void
    {
        $kos = Kos::factory()->create();
        $this->get('/kos/'.$kos->id.'?kampus=ngawur')->assertOk()->assertSee($kos->nama_kos);
        $this->get('/kos/999999')->assertNotFound();
    }

    public function test_nama_kos_di_peta_dikirim_sebagai_json_aman(): void
    {
        Kos::factory()->create(['nama_kos' => '<script>alert(1)</'.'script>']);
        $html = $this->get('/kos')->getContent();
        $this->assertStringNotContainsString('<script>alert(1)</'.'script>', $html);
    }
}
