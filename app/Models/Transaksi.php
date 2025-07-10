<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
    protected $fillable = ['kode', 'nomeja', 'atasnama', 'totalharga', 'status', 'status_pembayaran'];

    // Status constants
    const STATUS_BELUM_DIPROSES = 'belum_diproses';
    const STATUS_SEDANG_DIPROSES = 'sedang_diproses';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_LUNAS = 'Lunas';

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
}
