<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snag extends Model
{
    use HasFactory;

    protected $table = 'snags';

    protected $fillable = [
        'snag_number', 'project_id', 'phase_id', 'title', 'description', 'comment', 'location',
        'image', 'status', 'reported_by', 'assigned_to', 'due_date',
        'resolved_at', 'resolved_by', 'resolution_notes', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'due_date' => 'date',
        'resolved_at' => 'datetime',
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



    public function comments()
    {
        return $this->hasMany(SnagComment::class, 'snag_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}