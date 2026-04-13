<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluhan extends Model
{
    use HasFactory,SoftDeletes;
 
    protected $table = 'keluhans';

    protected $fillable = [
        'ticket',
        'unit_id',
        'penghuni_id',
        'judul',
        'deskripsi',
        'status',
        'penanggung_jawab_id',
        'taken_at',
        'keputusan',
        'tanggal_keputusan',
        'lampiran',
    ];

    protected $casts = [
        'status' => 'string',
        'taken_at' => 'datetime',
        'tanggal_keputusan' => 'datetime',
        'lampiran' => 'array',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(Pengguna::class, 'penanggung_jawab_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPenangananKeluhan::class, 'keluhan_id');
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'keluhan_id');
    }

    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class, 'keluhan_id');
    }
}