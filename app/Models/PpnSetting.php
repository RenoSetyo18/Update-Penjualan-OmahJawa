<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpnSetting extends Model
{
    protected $table = 'ppn_settings';

    protected $fillable = ['rate', 'is_enabled'];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }
}
