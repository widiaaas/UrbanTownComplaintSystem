<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeBase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'knowledge_bases';

    protected $fillable = [
        'judul',
        'kategori',
        'departemen_terkait',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(Pengguna::class, 'created_by');
    }

    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class);
    }
}