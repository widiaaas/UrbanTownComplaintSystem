<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    use HasFactory;

    protected $table = 'keluhan';

    protected $fillable = [
        'ticket',
        'unit_id',
        'penghuni_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'taken_by',
        'taken_at',
        'decision',
        'decision_date',
        'feedback_status',
        'feedback_alasan',
        'feedback_date',
        'lampiran',
    ];

    protected $casts = [
        'priority' => 'string',
        'status' => 'string',
        'feedback_status' => 'string',
        'taken_at' => 'datetime',
        'decision_date' => 'datetime',
        'feedback_date' => 'datetime',
        'lampiran' => 'array',
    ];

    // Relationships
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_id');
    }

    public function takenBy()
    {
        return $this->belongsTo(Pengguna::class, 'taken_by');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatKeluhan::class, 'keluhan_id');
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