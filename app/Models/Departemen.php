<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemens';

    protected $fillable = [
        'nama_departemen',
    ];


    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }


    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}