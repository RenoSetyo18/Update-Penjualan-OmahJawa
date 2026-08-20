<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    protected $table = 'jenis';
    protected $fillable = ['nama_jenis', 'deskripsi'];
    public $timestamps = true;

    // Relasi ke Menu
    public function menus()
    {
        return $this->hasMany(Menu::class, 'jenis_id');
    }
}
