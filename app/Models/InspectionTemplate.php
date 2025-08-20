<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionTemplate extends Model
{
    use HasFactory;

    protected $table = 'inspection_templates';

    protected $fillable = [
        'name', 'category', 'checklist_items', 'created_by', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'checklist_items' => 'array',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}