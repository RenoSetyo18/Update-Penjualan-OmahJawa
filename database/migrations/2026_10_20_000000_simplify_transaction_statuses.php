<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status', 32)->default('belum_bayar')->change();
        });

        DB::table('transaksis')
            ->where(function ($query) {
                $query->where('status', 'Pesanan Online')
                    ->orWhere('kode', 'like', 'ORD-%');
            })
            ->update(['status' => 'lunas']);

        DB::table('transaksis')
            ->where('status', '<>', 'lunas')
            ->update(['status' => 'belum_bayar']);

        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status', ['belum_bayar', 'lunas'])
                ->default('belum_bayar')
                ->change();
        });

        if (Schema::hasColumn('transaksis', 'paid_at')) {
            DB::table('transaksis')
                ->where('status', 'lunas')
                ->whereNull('paid_at')
                ->update(['paid_at' => DB::raw('COALESCE(updated_at, created_at)')]);
        }

        $kitchenColumns = Schema::hasTable('detail_transaksis')
            ? array_values(array_filter(
                ['menu_status', 'ready_at', 'notified_at'],
                fn (string $column): bool => Schema::hasColumn('detail_transaksis', $column)
            ))
            : [];

        if (Schema::hasTable('detail_transaksis') && $kitchenColumns !== []) {
            Schema::table('detail_transaksis', function (Blueprint $table) use ($kitchenColumns) {
                $table->dropColumn($kitchenColumns);
            });
        }
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status', 32)->default('belum_bayar')->change();
        });

        DB::table('transaksis')
            ->where('status', 'belum_bayar')
            ->update(['status' => 'belum_diproses']);

        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status', [
                'belum_diproses',
                'sedang_diproses',
                'makanan_siap',
                'selesai',
                'lunas',
                'Pesanan Online',
            ])
                ->default('belum_diproses')
                ->change();
        });

        if (Schema::hasTable('detail_transaksis')) {
            $restoreMenuStatus = !Schema::hasColumn('detail_transaksis', 'menu_status');
            $restoreReadyAt = !Schema::hasColumn('detail_transaksis', 'ready_at');
            $restoreNotifiedAt = !Schema::hasColumn('detail_transaksis', 'notified_at');

            if ($restoreMenuStatus || $restoreReadyAt || $restoreNotifiedAt) {
                Schema::table('detail_transaksis', function (Blueprint $table) use ($restoreMenuStatus, $restoreReadyAt, $restoreNotifiedAt) {
                    if ($restoreMenuStatus) {
                        $table->enum('menu_status', ['belum_siap', 'siap'])->default('belum_siap');
                    }
                    if ($restoreReadyAt) {
                        $table->timestamp('ready_at')->nullable();
                    }
                    if ($restoreNotifiedAt) {
                        $table->timestamp('notified_at')->nullable();
                    }
                });
            }
        }
    }
};
