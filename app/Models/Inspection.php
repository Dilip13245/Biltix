<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspections';

    protected $fillable = [
        'inspection_number', 'project_id', 'phase_id', 'template_id', 'title', 'description', 'status',
        'scheduled_date', 'started_at', 'completed_at', 'inspector_id', 'location',
        'overall_result', 'score_percentage', 'notes', 'images', 'created_by',
        'is_active', 'is_deleted'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score_percentage' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}