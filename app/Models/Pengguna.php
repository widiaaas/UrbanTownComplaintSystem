<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Karyawan;
use App\Models\Unit;
use App\Models\Keluhan;
use App\Models\WorkOrder;
use App\Models\KnowledgeBase;

class Pengguna extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $table = 'penggunas';

    protected $fillable = [
        'username',
        'password',
        'role',
        'is_active',
        'must_change_password',
        'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Relationships
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'user_id');
    }

    public function keluhanDiambil()
    {
        return $this->hasMany(Keluhan::class, 'penanggung_jawab_id');
    }

    public function workOrderDiambil()
    {
        return $this->hasMany(WorkOrder::class, 'penanggung_jawab_id');
    }

    public function knowledgeBase()
    {
        return $this->hasMany(KnowledgeBase::class, 'created_by');
    }
}