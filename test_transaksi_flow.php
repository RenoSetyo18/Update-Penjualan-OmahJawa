<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaksi;
use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;

$transaksi = Transaksi::first();
$transaksi->status = 'sedang_diproses';
$transaksi->save();

echo "Before: {$transaksi->id} {$transaksi->status}\n";

$controller = new TransaksiController();
$req = Request::create('/transaksi/update-status/'.$transaksi->id, 'POST', ['status' => 'selesai']);
$res = $controller->updateStatus($req, $transaksi->id);

$transaksi = Transaksi::find($transaksi->id);
echo "After: {$transaksi->id} {$transaksi->status}\n";
