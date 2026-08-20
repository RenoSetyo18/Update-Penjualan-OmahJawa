<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaksi;

$statusValues = Transaksi::select('status')->distinct()->get();
echo "Distinct status values:\n";
foreach ($statusValues as $s) {
    echo $s->status . "\n";
}
?>
