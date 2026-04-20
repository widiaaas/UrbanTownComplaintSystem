<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'no_unit', 
        'gedung',
        'lantai',
        'nomor_kamar',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];
    protected $appends = ['current_penghuni'];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function penghunis()
    {
        return $this->hasMany(Penghuni::class, 'unit_id');
    }

    public function penghuniAktif()
    {
        return $this->hasOne(Penghuni::class, 'unit_id')
            ->where('status', 'Aktif');
    }

    public function keluhans()
    {
        return $this->hasMany(Keluhan::class, 'unit_id');
    }

    public function getCurrentPenghuniAttribute()
    {
        return $this->penghuniAktif?->nama;
    }
}