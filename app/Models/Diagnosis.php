<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use SoftDeletes;
    protected $table = 'diagnosis';
    
    protected $fillable = [
        'knowledge_base_id',
        'penyebab',
        'deskripsi',
        'langkah_penyelesaian',
        'lampiran',
        'usage_count'
    ];

    protected $casts = [
        'lampiran' => 'array',
        'usage_count' => 'integer'
    ];



    public function knowledgeBase()
    {
        return $this->belongsTo(KnowledgeBase::class);
    }

    public function knowledgeKeluhan()
    {
        return $this->hasMany(KnowledgeBaseKeluhan::class);
    }

    public function keluhans()
    {
        return $this->belongsToMany(
            Keluhan::class,
            'knowledge_base_keluhan'
        )
        ->withPivot([
            'knowledge_base_id',
            'catatan'
        ])
        ->withTimestamps();
    }



    public function getTotalKeluhanAttribute()
    {
        return $this->keluhans()->count();
    }

    public function getNamaKnowledgeAttribute()
    {
        return $this->knowledgeBase?->judul ?? '-';
    }
}