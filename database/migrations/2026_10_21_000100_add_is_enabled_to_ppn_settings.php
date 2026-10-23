<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppn_settings', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(true)->after('rate');
        });
    }

    public function down(): void
    {
        Schema::table('ppn_settings', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });
    }
};
