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
        'penanggung_jawab_id',
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

    public function penanggungJawab()
    {
        return $this->belongsTo(Pengguna::class, 'penanggung_jawab_id');
    }
}