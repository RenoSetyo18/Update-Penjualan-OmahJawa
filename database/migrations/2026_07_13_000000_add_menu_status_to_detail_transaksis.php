<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            // Tambah kolom untuk status per-menu (belum_siap, siap)
            $table->enum('menu_status', ['belum_siap', 'siap'])->default('belum_siap')->after('note');

            // Tambah timestamp untuk kapan menu menjadi siap
            $table->timestamp('ready_at')->nullable()->after('menu_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->dropColumn(['menu_status', 'ready_at']);
        });
    }
};
