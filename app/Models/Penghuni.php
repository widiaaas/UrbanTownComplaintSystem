<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penghuni extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penghuni';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'identity_number',
        'gender',
        'status',
        'unit_id',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    protected $casts = [
        'gender' => 'string',
        'status' => 'string',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    // Relationships
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function keluhan()
    {
        return $this->hasMany(Keluhan::class, 'penghuni_id');
    }

    // Relasi ke unit sebagai penghuni aktif (inverse)
    public function unitSebagaiPenghuniAktif()
    {
        return $this->hasOne(Unit::class, 'penghuni_aktif_id');
    }
}