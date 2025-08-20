<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    use HasFactory;

    protected $table = 'project_phases';

    protected $fillable = [
        'project_id', 'name', 'description', 'phase_order', 'start_date', 'end_date',
        'status', 'budget_allocated', 'actual_cost', 'progress_percentage', 'created_by',
        'is_active', 'is_deleted'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget_allocated' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}