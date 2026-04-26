<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sinonim extends Model
{
    protected $table = 'sinonims'; // 🔥 wajib (karena nama tidak standar plural)

    protected $fillable = [
        'kata_asli',
        'kata_normal',
        'konteks'
    ];
}