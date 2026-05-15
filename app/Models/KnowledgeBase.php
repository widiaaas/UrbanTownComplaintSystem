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
        'usage_count',
        'created_by',
        'status'
    ];

    protected $casts = [
        'usage_count' => 'integer'
    ];

    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function creator()
    {
        return $this->belongsTo(Pengguna::class, 'created_by', 'id');
    }

    public function keluhans()
    {
        return $this->belongsToMany(
            Keluhan::class,
            'knowledge_base_keluhan'
        )
        ->withPivot([
            'diagnosis_id',
            'catatan'
        ])
        ->withTimestamps();
    }
}