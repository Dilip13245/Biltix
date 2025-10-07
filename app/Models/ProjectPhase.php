<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    use HasFactory;

    protected $table = 'project_phases';

    protected $fillable = [
        'project_id', 'title', 'progress_percentage', 'created_by', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function milestones()
    {
        return $this->hasMany(PhaseMilestone::class, 'phase_id')->where('is_active', true)->where('is_deleted', false);
    }

    public function getTimeProgressAttribute()
    {
        $milestones = $this->milestones;
        
        if ($milestones->isEmpty()) {
            return 0;
        }

        $totalProgress = 0;
        $milestoneCount = 0;

        foreach ($milestones as $milestone) {
            if ($milestone->start_date && $milestone->due_date) {
                $totalProgress += $milestone->time_progress;
                $milestoneCount++;
            }
        }

        return $milestoneCount > 0 ? round($totalProgress / $milestoneCount, 2) : 0;
    }

    public function getHasExtensionsAttribute()
    {
        return $this->milestones->where('is_extended', true)->isNotEmpty();
    }

    public function getTotalExtensionDaysAttribute()
    {
        return $this->milestones->sum('extension_days');
    }
}