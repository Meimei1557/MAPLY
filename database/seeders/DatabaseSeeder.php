<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // Wajib sebelum KosSeeder supaya kartu lingkaran kampus di dashboard
            // (section Kampus & Area) langsung terisi pada instalasi baru.
            KampusSeeder::class,
            KosSeeder::class,
        ]);
    }
}
