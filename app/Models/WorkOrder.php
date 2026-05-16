<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;

class WorkOrder extends Model
{
    use HasFactory;

    protected $table = 'work_orders'; 

    protected $fillable = [
        'nomor_tiket',
        'keluhan_id',
        'departemen_id', 
        'instruksi',
        'status',
        'lokasi',
        'penanggung_jawab_id',
        'taken_at',
        'lampiran',
        'tanggal_dibuat',
        'tanggal_selesai',
    ];

    protected $casts = [
        'status' => 'string',
        'taken_at' => 'datetime',
        'lampiran' => 'array',
        'tanggal_dibuat' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(Pengguna::class, 'penanggung_jawab_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPenangananWorkOrder::class, 'work_order_id')
            ->latest('waktu');
    }
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }
}