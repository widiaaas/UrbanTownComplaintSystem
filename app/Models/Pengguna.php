<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'username',
        'password_hash',
        'role',
        'is_active',
        'must_change_password',
        'last_login',
    ];

    protected $hidden = [
        'password_hash',
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
        return $this->password_hash;
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
        return $this->hasMany(Keluhan::class, 'taken_by');
    }

    public function workOrderDiambil()
    {
        return $this->hasMany(WorkOrder::class, 'taken_by');
    }

    public function riwayatWorkOrder()
    {
        return $this->hasMany(RiwayatWorkOrder::class, 'dibuat_oleh');
    }

    public function knowledgeBase()
    {
        return $this->hasMany(KnowledgeBase::class, 'created_by');
    }
    
}