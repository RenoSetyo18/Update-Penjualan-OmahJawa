<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
$transaksis = DB::table('transaksis')->get();
echo "Data saat ini:\n";
foreach($transaksis as $t){ echo "ID {$t->id} st={$t->status}\n";}
