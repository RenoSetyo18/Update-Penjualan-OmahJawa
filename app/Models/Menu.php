<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'id',
        'nama',
        'harga',
        'jenis_id',
        'deskripsi',
        'note',
        'estimasi_waktu',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Estimasi waktu dalam menit berdasarkan pilihan FCFS
    public function getEstimasiMenitAttribute()
    {
        return match ($this->estimasi_waktu) {
            'cepat' => 5,
            'sedang' => 10,
            'lama' => 20,
            default => 5,
        };
    }

    // Relasi ke Jenis
    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }
}
