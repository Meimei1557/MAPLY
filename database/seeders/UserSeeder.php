<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun demo. GANTI password sebelum aplikasi dipakai di server publik
     * (atau set MAPLY_ADMIN_PASSWORD di .env sebelum menjalankan seeder).
     */
    public function run(): void
    {
        $this->upsert('Administrator Maply', 'admin', 'admin@maply.test',
            (string) config('maply.admin_password'), User::ROLE_ADMIN);

        // Akun pengguna demo dari versi awal (dipertahankan).
        $this->upsert('Meisya', 'meisya', 'test@example.com', 'password', User::ROLE_USER);
    }

    private function upsert(string $name, string $username, string $email, string $password, string $role): void
    {
        $user = User::firstOrNew(['email' => $email]);
        $user->fill(['name' => $name, 'username' => $username, 'password' => Hash::make($password)]);
        $user->role = $role;
        $user->email_verified_at ??= now();
        $user->save();
    }
}
