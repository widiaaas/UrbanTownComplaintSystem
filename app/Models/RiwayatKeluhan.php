<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKeluhan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_keluhan';

    protected $fillable = [
        'keluhan_id',
        'tipe_aktor',
        'judul',
        'keterangan',
        'waktu',
    ];

    protected $casts = [
        'tipe_aktor' => 'string',
        'waktu' => 'datetime',
    ];

    // Relationships
    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }
}