<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'diagnosis';

    protected $fillable = [
        'knowledge_base_id',
        'keluhan_id',
        'penyebab',
        'deskripsi',
        'langkah_penyelesaian',
    ];

    public function knowledgeBase()
    {
        return $this->belongsTo(KnowledgeBase::class);
    }

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }
}