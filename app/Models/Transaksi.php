<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
    protected $fillable = ['kode', 'user_id', 'kasir_id', 'nomeja', 'atasnama', 'totalharga', 'ppn_rate', 'ppn_amount', 'jumlah_bayar', 'status', 'metode_pembayaran', 'paid_at'];

    // Status constants
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_LUNAS = 'lunas';

    public static function transaksiStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_BELUM_BAYAR => 'Belum Bayar',
            self::STATUS_LUNAS => 'Lunas',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function kasirStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_BELUM_BAYAR => 'Belum Lunas',
            self::STATUS_LUNAS => 'Lunas',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

}
