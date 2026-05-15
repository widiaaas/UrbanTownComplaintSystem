<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseKeluhan extends Model
{
    protected $table = 'knowledge_base_keluhan';

    protected $fillable = [
        'knowledge_base_id',
        'diagnosis_id',
        'keluhan_id',
        'catatan'
    ];


    public function knowledgeBase()
    {
        return $this->belongsTo(KnowledgeBase::class);
    }

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR (optional)
    |--------------------------------------------------------------------------
    */

    public function getNamaKeluhanAttribute()
    {
        return $this->keluhan?->judul
            ?? $this->keluhan?->pengajuan?->judul
            ?? '-';
    }
}