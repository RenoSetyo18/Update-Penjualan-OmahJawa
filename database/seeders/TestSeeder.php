<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Jenis;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah jenis sudah ada
        $jenis = Jenis::first();
        if (!$jenis) {
            $jenis = Jenis::create([
                'nama_jenis' => 'Test Makanan',
                'deskripsi' => 'Menu makanan untuk testing'
            ]);
        }

        // Buat menu untuk testing
        Menu::create([
            'jenis_id' => $jenis->id,
            'nama' => 'Nasi Goreng Test',
            'harga' => 15000,
            'deskripsi' => 'Menu untuk testing transaksi'
        ]);

        Menu::create([
            'jenis_id' => $jenis->id,
            'nama' => 'Ayam Bakar Test',
            'harga' => 20000,
            'deskripsi' => 'Menu kedua untuk testing'
        ]);
    }
}
