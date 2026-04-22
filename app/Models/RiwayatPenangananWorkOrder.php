<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenangananWorkOrder extends Model
{
    use HasFactory;

    protected $table = 'riwayat_penanganan_work_orders';

    protected $fillable = [
        'work_order_id', 
        'status',
        'judul',
        'judul',
        'deskripsi',
        'penanggung_jawab_id',
        'waktu',
    ];

    protected $casts = [
        'lampiran' => 'array',
        'waktu' => 'datetime',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(Pengguna::class, 'penanggung_jawab_id');
    }
}