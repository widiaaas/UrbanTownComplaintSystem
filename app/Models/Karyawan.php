<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'id_pegawai',
        'nama',
        'telp',
        'email',
        'jabatan',
        'gender',
        'status',
    ];

    protected $casts = [
        'gender' => 'string',
        'status' => 'string',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}