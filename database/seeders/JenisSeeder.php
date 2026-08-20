<?php

namespace Database\Seeders;

use App\Models\Jenis;
use Illuminate\Database\Seeder;

class JenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jenis::create([
            'nama_jenis' => 'Makanan',
            'deskripsi' => 'Makanan utama dan menunya',
        ]);

        Jenis::create([
            'nama_jenis' => 'Minuman',
            'deskripsi' => 'Minuman panas dan minuman dingin',
        ]);

        Jenis::create([
            'nama_jenis' => 'Cemilan',
            'deskripsi' => 'Camilan ringan dan snack',
        ]);

        Jenis::create([
            'nama_jenis' => 'Dessert',
            'deskripsi' => 'Makanan penutup dan hidangan manis',
        ]);
    }
}
