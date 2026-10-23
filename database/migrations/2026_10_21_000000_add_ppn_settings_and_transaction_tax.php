<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppn_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate', 5, 2)->default(10);
            $table->timestamps();
        });

        DB::table('ppn_settings')->insert([
            'id' => 1,
            'rate' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('transaksis', function (Blueprint $table) {
            $table->decimal('ppn_rate', 5, 2)->default(0)->after('totalharga');
            $table->decimal('ppn_amount', 10, 2)->default(0)->after('ppn_rate');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['ppn_rate', 'ppn_amount']);
        });

        Schema::dropIfExists('ppn_settings');
    }
};
