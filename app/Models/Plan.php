<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'project_id', 'title', 'drawing_number', 'file_name', 'file_path', 'file_size',
        'file_type', 'uploaded_by', 'is_active', 'is_deleted'
    ];

    protected $attributes = [
        'title' => null,
        'drawing_number' => null,
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}