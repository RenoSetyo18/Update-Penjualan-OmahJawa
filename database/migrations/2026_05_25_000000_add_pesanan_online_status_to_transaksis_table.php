<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to update enum values without requiring doctrine/dbal.
        if (Schema::hasTable('transaksis')) {
            DB::statement("ALTER TABLE transaksis MODIFY status ENUM('belum_diproses','sedang_diproses','makanan_siap','selesai','lunas','Pesanan Online') NOT NULL DEFAULT 'belum_diproses'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transaksis')) {
            DB::statement("ALTER TABLE transaksis MODIFY status ENUM('belum_diproses','sedang_diproses','makanan_siap','selesai','lunas') NOT NULL DEFAULT 'belum_diproses'");
        }
    }
};
