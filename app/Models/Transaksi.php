<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
    protected $fillable = ['kode', 'user_id', 'nomeja', 'atasnama', 'totalharga', 'jumlah_bayar', 'status', 'metode_pembayaran', 'paid_at'];

    // Status constants
    const STATUS_BELUM_DIPROSES = 'belum_diproses';
    const STATUS_SEDANG_DIPROSES = 'sedang_diproses';
    const STATUS_MAKANAN_SIAP = 'makanan_siap';
    const STATUS_SELESAI = 'selesai';
    const STATUS_LUNAS = 'lunas';
    const STATUS_PESANAN_ONLINE = 'Pesanan Online';

    public static function kokiStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_BELUM_DIPROSES, self::STATUS_PESANAN_ONLINE => 'Belum Diproses',
            self::STATUS_SEDANG_DIPROSES => 'Sedang Diproses',
            self::STATUS_MAKANAN_SIAP, self::STATUS_SELESAI, self::STATUS_LUNAS => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function transaksiStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_BELUM_DIPROSES, self::STATUS_SEDANG_DIPROSES, self::STATUS_PESANAN_ONLINE => 'Belum Siap',
            self::STATUS_MAKANAN_SIAP => 'Makanan Siap',
            self::STATUS_SELESAI, self::STATUS_LUNAS => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function kasirStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_BELUM_DIPROSES, self::STATUS_SEDANG_DIPROSES, self::STATUS_PESANAN_ONLINE => 'Menunggu',
            self::STATUS_MAKANAN_SIAP, self::STATUS_SELESAI => 'Bayar',
            self::STATUS_LUNAS => 'Lunas',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    /**
     * Check if transaction is being processed by both chef and cashier
     */
    public function isBeingProcessed()
    {
        return $this->status === self::STATUS_SEDANG_DIPROSES;
    }

    public function isOnlineOrder(): bool
    {
        return $this->status === self::STATUS_PESANAN_ONLINE || str_starts_with($this->kode, 'ORD-');
    }

    public function isAlreadyPaidOnline(): bool
    {
        return $this->isOnlineOrder() || $this->status === self::STATUS_LUNAS;
    }
}
