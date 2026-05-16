<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'karyawans';

    protected $fillable = [
        'pengguna_id',
        'departemen_id',
        'nip',
        'nama',
        'no_telepon',
        'email',
        'jenis_kelamin',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function keluhans()
    {
        return $this->hasMany(
            Keluhan::class,'penanggung_jawab_id','pengguna_id'
        );
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class, 'penanggung_jawab_id');
    }
}