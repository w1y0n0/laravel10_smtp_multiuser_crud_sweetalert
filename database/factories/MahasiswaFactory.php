<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'npm' => $this->faker->numerify('#########'),
            'angkatan' => Arr::random(['2021', '2022', '2023']),
            'jurusan' => Arr::random(['Komputer dan Bisnis', 'Rekayasa Elektro dan Mekatronika', 'Mesin dan Industri Pertanian']),
            // 'created_at' => now(),
        ];
    }
}
