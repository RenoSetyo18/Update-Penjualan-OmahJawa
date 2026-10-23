<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transaksis', 'kasir_id')) {
            Schema::table('transaksis', function (Blueprint $table) {
                $table->foreignId('kasir_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transaksis', 'kasir_id')) {
            Schema::table('transaksis', function (Blueprint $table) {
                $table->dropConstrainedForeignId('kasir_id');
            });
        }
    }
};
