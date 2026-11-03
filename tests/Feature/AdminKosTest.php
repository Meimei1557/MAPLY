<?php

namespace Tests\Feature;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKosTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $override = []): array
    {
        return array_merge([
            'nama_kos' => 'Kos Uji Coba', 'tipe' => 'Putri', 'status' => 'Tersedia', 'harga' => '1.200.000',
            'alamat' => 'Jl. Uji Coba No. 1', 'kecamatan' => 'Ilir Barat I', 'kota' => 'Palembang',
            'latitude' => '-2,986444', 'longitude' => '104.734333', 'koordinat_status' => 'akurat',
            'fasilitas' => ['AC', 'WiFi'], 'fasilitas_lain' => 'TV, water heater',
            'status_data' => 'perlu_konfirmasi',
        ], $override);
    }

    public function test_pengguna_biasa_tidak_boleh_mengelola_kos(): void
    {
        $this->actingAs(User::factory()->create());
        $kos = Kos::factory()->create();

        $this->get('/admin/kos')->assertForbidden();
        $this->get('/admin/kos/create')->assertForbidden();
        $this->post('/admin/kos', $this->payload())->assertForbidden();
        $this->put('/admin/kos/'.$kos->id, $this->payload())->assertForbidden();
        $this->delete('/admin/kos/'.$kos->id)->assertForbidden();
        $this->assertDatabaseCount('kos', 1);
    }

    public function test_admin_dapat_menambah_kos(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->get('/admin/kos/create')->assertOk();

        $this->post('/admin/kos', $this->payload())->assertRedirect();
        $kos = Kos::where('nama_kos', 'Kos Uji Coba')->firstOrFail();
        $this->assertSame(1200000, $kos->harga);
        $this->assertEqualsWithDelta(-2.986444, $kos->latitude, 0.000001);
        $this->assertEqualsCanonicalizing(['AC', 'WiFi', 'TV', 'water heater'], $kos->fasilitas->pluck('nama_fasilitas')->all());
    }

    public function test_validasi_formulir(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->post('/admin/kos', $this->payload(['nama_kos' => '', 'tipe' => 'X', 'latitude' => '10', 'sumber_url' => 'bukan-url']))
            ->assertSessionHasErrors(['nama_kos', 'tipe', 'latitude', 'sumber_url']);
        $this->post('/admin/kos', $this->payload(['longitude' => '']))->assertSessionHasErrors('longitude');
        $this->assertDatabaseCount('kos', 0);
    }

    public function test_admin_dapat_mengubah_dan_menghapus_kos(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $kos = Kos::factory()->create(['nama_kos' => 'Lama']);

        $this->get('/admin/kos/'.$kos->id.'/edit')->assertOk()->assertSee('Lama');
        $this->put('/admin/kos/'.$kos->id, $this->payload(['nama_kos' => 'Baru']))->assertRedirect();
        $this->assertSame('Baru', $kos->fresh()->nama_kos);

        $this->delete('/admin/kos/'.$kos->id)->assertRedirect('/admin/kos');
        $this->assertDatabaseMissing('kos', ['id' => $kos->id]);
        $this->assertDatabaseCount('fasilitas', 0);
    }

    public function test_koordinat_boleh_kosong(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->post('/admin/kos', $this->payload(['latitude' => '', 'longitude' => '', 'harga' => '']))->assertRedirect();
        $kos = Kos::firstOrFail();
        $this->assertNull($kos->latitude);
        $this->assertNull($kos->harga);
    }

    public function test_unggah_foto(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->admin()->create());
        $this->post('/admin/kos', $this->payload(['foto' => UploadedFile::fake()->image('kos.png', 600, 400)]))->assertRedirect();
        $kos = Kos::firstOrFail();
        Storage::disk('public')->assertExists($kos->foto);
        $this->delete('/admin/kos/'.$kos->id);
        Storage::disk('public')->assertMissing($kos->foto);
    }
}
