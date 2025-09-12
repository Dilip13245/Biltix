<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photos';

    protected $fillable = [
        'project_id', 'phase_id', 'title', 'description', 'file_name', 'file_path',
        'thumbnail_path', 'file_size', 'taken_at', 'taken_by', 'location',
        'tags', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'taken_at' => 'datetime',
        'tags' => 'array',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
    
    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'taken_by');
    }
}