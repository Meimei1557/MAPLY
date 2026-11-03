<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dan_register_dapat_dibuka(): void
    {
        $this->get('/login')->assertOk()->assertSee('Selamat Datang Kembali');
        $this->get('/register')->assertOk();
        $this->get('/lupa-password')->assertOk();
    }

    public function test_tamu_diarahkan_ke_login_untuk_halaman_terproteksi(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/kos')->assertRedirect('/login');
        $this->get('/admin/kos')->assertRedirect('/login');
    }

    public function test_login_dengan_email_berhasil(): void
    {
        $user = User::factory()->create(['email' => 'a@maply.test']);
        $this->post('/login', ['login' => 'a@maply.test', 'password' => 'password'])->assertRedirect('/welcome-animation');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_dengan_username_berhasil(): void
    {
        $user = User::factory()->create(['username' => 'budi_01']);
        $this->post('/login', ['login' => 'Budi_01', 'password' => 'password'])->assertRedirect('/welcome-animation');
        $this->assertAuthenticatedAs($user);
    }

    public function test_password_salah_ditolak_dengan_pesan(): void
    {
        User::factory()->create(['email' => 'a@maply.test']);
        $this->from('/login')->post('/login', ['login' => 'a@maply.test', 'password' => 'salah'])
            ->assertRedirect('/login')->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_akun_tidak_terdaftar_tidak_bisa_masuk(): void
    {
        $this->post('/login', ['login' => 'tidakada@maply.test', 'password' => 'password'])->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_pengguna_login_yang_membuka_login_tidak_loop(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/login')->assertRedirect('/dashboard');
        $this->get('/')->assertRedirect('/dashboard');
    }

    public function test_registrasi_berhasil_hash_password_dan_peran_user(): void
    {
        $this->post('/register', [
            'name' => 'Siti Aminah', 'username' => 'siti', 'email' => 'Siti@Maply.test',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/welcome-animation');

        $user = User::where('email', 'siti@maply.test')->firstOrFail();
        $this->assertNotSame('rahasia123', $user->password);
        $this->assertSame('user', $user->fresh()->role);
        $this->assertAuthenticatedAs($user);
    }

    public function test_registrasi_tidak_bisa_menyisipkan_peran_admin(): void
    {
        $this->post('/register', [
            'name' => 'Nakal', 'username' => 'nakal', 'email' => 'n@maply.test', 'role' => 'admin',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ]);
        $this->assertSame('user', User::where('username', 'nakal')->firstOrFail()->role);
    }

    public function test_registrasi_validasi(): void
    {
        User::factory()->create(['email' => 'ada@maply.test', 'username' => 'ada']);
        $this->post('/register', [
            'name' => 'X', 'username' => 'ada', 'email' => 'ada@maply.test',
            'password' => 'pendek', 'password_confirmation' => 'beda',
        ])->assertSessionHasErrors(['name', 'username', 'email', 'password']);
    }

    public function test_logout(): void
    {
        $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
