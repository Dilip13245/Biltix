<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snag extends Model
{
    use HasFactory;

    protected $table = 'snags';

    protected $fillable = [
        'snag_number', 'project_id', 'category_id', 'title', 'description', 'location',
        'priority', 'severity', 'status', 'reported_by', 'assigned_to', 'due_date',
        'resolved_at', 'resolved_by', 'resolution_notes', 'images_before',
        'images_after', 'cost_impact', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'due_date' => 'date',
        'resolved_at' => 'datetime',
        'images_before' => 'array',
        'images_after' => 'array',
        'cost_impact' => 'decimal:2',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    // Relationships to match Figma design
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category()
    {
        return $this->belongsTo(SnagCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(SnagComment::class, 'snag_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}