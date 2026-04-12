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
        'judul',
        'keterangan',
        'waktu',
        'user_id',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }
    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}