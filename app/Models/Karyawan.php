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
        'user_id',
        'nip',
        'nama',
        'telp',
        'email',
        'role',
        'departemen',
        'jenis_kelamin',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class);
    }

}