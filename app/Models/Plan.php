<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'project_id', 'title', 'plan_type', 'file_name', 'file_path', 'file_size',
        'file_type', 'version', 'status', 'thumbnail_path', 'uploaded_by',
        'approved_by', 'approved_at', 'is_active', 'is_deleted'
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