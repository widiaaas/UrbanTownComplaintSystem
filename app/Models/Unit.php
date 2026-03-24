<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unit';

    protected $fillable = [
        'no_unit',
        'gedung',
        'lantai',
        'nomor_kamar',
        'status',
        'user_id',
        'penghuni_aktif_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function penghuniAktif()
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_aktif_id');
    }

    public function penghuni()
    {
        return $this->hasMany(Penghuni::class, 'unit_id');
    }

    public function keluhan()
    {
        return $this->hasMany(Keluhan::class, 'unit_id');
    }
}