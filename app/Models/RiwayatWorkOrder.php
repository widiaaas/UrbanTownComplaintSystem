<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatWorkOrder extends Model
{
    use HasFactory;

    protected $table = 'riwayat_work_order';

    protected $fillable = [
        'work_order_id',
        'status',
        'keterangan',
        'lampiran',
        'dibuat_oleh',
        'waktu',
    ];

    protected $casts = [
        'status' => 'string',
        'lampiran' => 'array',
        'waktu' => 'datetime',
    ];

    // Relationships
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }
}