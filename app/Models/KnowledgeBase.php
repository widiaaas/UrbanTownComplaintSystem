<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeBase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'judul',
        'kategori',
        'departemen_terkait',
        'keywords',
        'variasi',
        'keluhan_id',
        'created_by',
        'status'
    ];

    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'created_by', 'id');
    }
}