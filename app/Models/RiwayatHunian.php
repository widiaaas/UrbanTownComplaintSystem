<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatHunian extends Model
{
    protected $fillable = [

        'penghuni_id',
        'unit_id',
        'status',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}