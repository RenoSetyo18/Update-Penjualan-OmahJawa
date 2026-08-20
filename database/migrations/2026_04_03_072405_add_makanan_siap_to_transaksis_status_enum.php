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
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('belum_diproses', 'sedang_diproses', 'makanan_siap', 'selesai', 'lunas') NOT NULL DEFAULT 'belum_diproses'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status ENUM('belum_diproses', 'sedang_diproses', 'selesai', 'lunas') NOT NULL DEFAULT 'belum_diproses'");
    }
};
