<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'project_id', 'category_id', 'name', 'original_name', 'file_path',
        'file_size', 'file_type', 'description', 'uploaded_by', 'is_public', 
        'shared_with', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'shared_with' => 'array',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
    
    public function category()
    {
        return $this->belongsTo(FileCategory::class, 'category_id');
    }
}