<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RiwayatPenangananWorkOrder extends Model
{
    use HasFactory;

    protected $table = 'riwayat_penanganan_work_orders';

    protected $fillable = [
        'work_order_id', 
        'status',
        'judul',
        'deskripsi',
        'lampiran',
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

    public function getWaktuAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
}