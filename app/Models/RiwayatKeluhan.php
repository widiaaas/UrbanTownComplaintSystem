<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKeluhan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_keluhans';

    protected $fillable = [
        'keluhan_id',
        'judul',
        'keterangan',
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }
}