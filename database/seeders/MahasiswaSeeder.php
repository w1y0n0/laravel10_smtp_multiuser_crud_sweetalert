<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Mahasiswa::factory(10)->create();

        // DB::table('mahasiswa')->insert([
        //     'name' => 'Kevin',
        //     'email' => 'kevin@gmail.com',
        //     'npm' => 230602089,
        //     'angkatan' => 2023,
        //     'jurusan' => 'Komputer dan Bisnis',
        //     'created_at' => date('Y-m-d H:i:s')
        // ]);
    }
}
