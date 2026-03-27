<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penghuni extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penghunis';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'jenis_kelamin',
        'status',
        'unit_id',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    protected $casts = [
        'jenis_kelamin' => 'string',
        'status' => 'string',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function keluhans()
    {
        return $this->hasMany(Keluhan::class, 'penghuni_id');
    }
}