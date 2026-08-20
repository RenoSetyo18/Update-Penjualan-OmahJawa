<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Models\Transaksi;
use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;

$t = Transaksi::first();
$t->status = 'selesai';
$t->save();

$controller = new TransaksiController();
$req = Request::create('/','POST',['status'=>'lunas']);
$res = $controller->updateStatus($req, $t->id);

echo 'Result: ' . get_class($res) . "\n";
$t2 = Transaksi::find($t->id);
echo 'Status now: ' . $t2->status . "\n";
