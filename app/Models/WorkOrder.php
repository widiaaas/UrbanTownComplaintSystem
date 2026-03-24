<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $table = 'work_order';

    protected $fillable = [
        'nomor_wo',
        'keluhan_id',
        'departemen_tujuan',
        'instruksi',
        'status',
        'taken_by',
        'taken_at',
        'petugas',
        'laporan',
        'lampiran',
        'tanggal_dibuat',
        'tanggal_selesai',
    ];

    protected $casts = [
        'departemen_tujuan' => 'string',
        'status' => 'string',
        'taken_at' => 'datetime',
        'lampiran' => 'array',
        'tanggal_dibuat' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Relationships
    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }

    public function takenBy()
    {
        return $this->belongsTo(Pengguna::class, 'taken_by');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatWorkOrder::class, 'work_order_id');
    }
}