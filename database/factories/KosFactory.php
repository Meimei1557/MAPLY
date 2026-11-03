<?php

namespace Database\Factories;

use App\Models\Kos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kos>
 */
class KosFactory extends Factory
{
    protected $model = Kos::class;

    public function definition(): array
    {
        return [
            'nama_kos' => 'Kos '.fake()->unique()->words(2, true),
            'alamat' => 'Jl. '.fake()->streetName().' No. '.fake()->numberBetween(1, 99),
            'provinsi' => 'Sumatera Selatan',
            'kota' => 'Palembang',
            'kecamatan' => fake()->randomElement(['Ilir Barat I', 'Kemuning', 'Sukarami', 'Ilir Timur I']),
            'latitude' => fake()->randomFloat(6, -3.05, -2.92),
            'longitude' => fake()->randomFloat(6, 104.70, 104.80),
            'koordinat_status' => 'perkiraan',
            'harga' => fake()->numberBetween(5, 20) * 100000,
            'tipe' => fake()->randomElement(Kos::TIPE),
            'deskripsi' => fake()->sentence(),
            'status' => 'Tersedia',
            'status_data' => 'perlu_konfirmasi',
        ];
    }

    public function tanpaKoordinat(): static
    {
        return $this->state(fn () => ['latitude' => null, 'longitude' => null, 'koordinat_status' => null]);
    }

    public function contoh(): static
    {
        return $this->state(fn () => ['status_data' => 'contoh']);
    }
}
