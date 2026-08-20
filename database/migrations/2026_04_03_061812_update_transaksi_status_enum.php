<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pertama, tambahkan nilai enum baru ke enum yang sudah ada
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('pending', 'belum_diproses', 'sedang_diproses', 'selesai', 'lunas') NOT NULL DEFAULT 'belum_diproses'");

        // Kemudian update data existing
        DB::table('transaksis')->where('status', 'pending')->update(['status' => 'belum_diproses']);

        // Akhirnya, hapus nilai enum lama
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('belum_diproses', 'sedang_diproses', 'selesai', 'lunas') NOT NULL DEFAULT 'belum_diproses'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan enum ke status sebelumnya
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('pending', 'belum_diproses', 'sedang_diproses', 'selesai', 'lunas') NOT NULL DEFAULT 'pending'");

        // Kembalikan data
        DB::table('transaksis')->where('status', 'belum_diproses')->update(['status' => 'pending']);

        // Hapus nilai enum baru
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('pending', 'selesai', 'lunas') NOT NULL DEFAULT 'pending'");
    }
};
