<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Pengguna> */
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'role' => 'anggota',
            'no_hp' => '08'.fake()->numerify('##########'),
            'alamat' => fake()->streetAddress(),
            'remember_token' => Str::random(10),
        ];
    }

    public function ketua(): static     { return $this->state(['role' => 'ketua']); }
    public function bendahara(): static { return $this->state(['role' => 'bendahara']); }
}
