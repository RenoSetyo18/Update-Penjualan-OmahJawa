<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koki extends Model
{
    use HasFactory;

    protected $table = 'koki';

    protected $fillable = [
        'nomeja',
        'menu',
        'status',
    ];
}
