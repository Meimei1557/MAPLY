<?php

namespace Tests\Feature;

use App\Models\Kos;
use App\Models\User;
use Database\Seeders\KosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataKostTest extends TestCase
{
    use RefreshDatabase;

    private function dataFile(string $file): array
    {
        return json_decode(file_get_contents(database_path('data/'.$file)), true);
    }

    private function mapItems(string $url = '/kos'): array
    {
        $html = $this->actingAs(User::factory()->create())->get($url)->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/items:\s*(\[.*?\]),\s*radiusKm/s', $html);
        preg_match('/items:\s*(\[.*?\]),\s*radiusKm/s', $html, $m);

        return json_decode($m[1], true);
    }

    public function test_semua_data_json_masuk_database_tanpa_terlewat(): void
    {
        config(['maply.seed_contoh' => false]);
        $this->seed(KosSeeder::class);

        $baru = $this->dataFile('kos_hasil_pengumpulan.json');
        $lama = $this->dataFile('kos_publik.json');
        $tambahan = $this->dataFile('kos_tambahan.json');
        // 288 kost hasil pengumpulan (137 Indralaya + 151 Palembang) per 22 Okt 2026.
        $this->assertCount(288, $baru);
        // Seeder memuat tiga berkas: kos_publik + kos_hasil_pengumpulan + kos_tambahan (145, 30 Okt 2026).
        $totalSeeder = count($baru) + count($lama) + count($tambahan);
        $this->assertSame($totalSeeder, Kos::count());
        $this->assertSame(0, Kos::where('status_data', 'contoh')->count());
        // 151 Palembang di kos_hasil_pengumpulan.json; kos_tambahan menambah 145 Palembang lagi.
        $this->assertSame(151, Kos::where('kota', 'Palembang')->whereNotNull('sumber_url')->count() - count($lama) - count($tambahan));
        // 137 Indralaya (107 lama + 30 baru) — kos_tambahan tidak berisi Indralaya.
        $this->assertSame(137, Kos::where('kota', 'Indralaya')->count());

        // Seeder aman dijalankan ulang (tidak menggandakan data).
        $this->seed(KosSeeder::class);
        $this->assertSame($totalSeeder, Kos::count());
    }

    public function test_jumlah_marker_sama_dengan_kos_berkoordinat_dan_tidak_menumpuk(): void
    {
        config(['maply.seed_contoh' => false]);
        $this->seed(KosSeeder::class);

        $berkoordinat = Kos::whereNotNull('latitude')->whereNotNull('longitude')->count();
        $items = $this->mapItems('/kos');
        $this->assertCount($berkoordinat, $items);
        $this->assertGreaterThan(200, $berkoordinat);

        $titik = collect($items)->map(fn ($i) => $i['lat'].','.$i['lng'])->unique()->count();
        $this->assertGreaterThan(200, $titik, 'Marker tidak boleh menumpuk di satu titik.');
        foreach ($items as $i) {
            $this->assertTrue($i['lat'] < -2.8 && $i['lat'] > -3.4 && $i['lng'] > 104.5 && $i['lng'] < 105.0, $i['nama']);
        }
    }

    public function test_filter_wilayah_pencarian_dan_data_popup(): void
    {
        config(['maply.seed_contoh' => false]);
        $this->seed(KosSeeder::class);

        $pal = $this->mapItems('/kos?wilayah=palembang');
        $ind = $this->mapItems('/kos?wilayah=indralaya');
        $this->assertCount(122, $pal);
        $this->assertCount(107, $ind);
        $this->assertSame(['Palembang'], collect($pal)->pluck('kota')->unique()->all());

        $cari = $this->mapItems('/kos?q=Diradari');
        $this->assertCount(1, $cari);
        $this->assertSame('Diradari Kost', $cari[0]['nama']);
        $this->assertNotEmpty($cari[0]['alamat']);
        $this->assertArrayHasKey('fasilitas', $cari[0]);
        $this->assertArrayHasKey('tipe', $cari[0]);

        // pencarian berdasarkan alamat
        $this->assertNotEmpty($this->mapItems('/kos?q=Lorong+Siguntang'));
        // filter jenis: hanya data yang tipenya diketahui yang lolos
        $putri = $this->mapItems('/kos?tipe=Putri');
        $this->assertNotEmpty($putri);
        $this->assertSame(['Putri'], collect($putri)->pluck('tipe')->unique()->all());
    }

    public function test_kos_tanpa_koordinat_tetap_tersimpan_dan_tidak_error(): void
    {
        config(['maply.seed_contoh' => false]);
        $this->seed(KosSeeder::class);
        $k = Kos::where('nama_kos', 'Kost Emi/Sumitro')->firstOrFail();
        $this->assertNull($k->latitude);
        $this->assertStringContainsString('koordinat', $k->catatan_data);
        $this->actingAs(User::factory()->create())->get('/kos/'.$k->id)->assertOk()->assertSee('Catatan kelengkapan data');
    }
}
