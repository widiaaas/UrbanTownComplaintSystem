<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenangananKeluhan extends Model
{
    use HasFactory;  

    protected $table = 'riwayat_penanganan_keluhans';

    protected $fillable = [
        'keluhan_id', 
        'status',
        'judul',
        'deskripsi',
        'lampiran',
        'waktu'
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'lampiran' => 'array',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }

}