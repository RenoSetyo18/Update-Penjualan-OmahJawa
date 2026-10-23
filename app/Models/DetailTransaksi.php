<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';

    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'jumlah',
        'harga',
        'subtotal',
        'note',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function getEstimasiMenitAttribute()
    {
        $minutesPerUnit = $this->menu?->estimasi_menit ?? 5;
        return $minutesPerUnit * $this->jumlah;
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

}
