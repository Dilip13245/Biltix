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

    public function tasks()
    {
        return $this->hasMany(Task::class, 'phase_id')->where('is_active', true)->where('is_deleted', false);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'phase_id')->where('is_active', true)->where('is_deleted', false);
    }

    public function snags()
    {
        return $this->hasMany(Snag::class, 'phase_id')->where('is_active', true)->where('is_deleted', false);
    }

    /**
     * Calculate progress based on approved status of tasks, inspections, and snags
     * Formula: (approved items / total items) * 100
     * Each category (tasks, inspections, snags) has equal weight
     */
    public function getTimeProgressAttribute()
    {
        // Get all tasks, inspections, and snags for this phase
        $tasks = $this->tasks;
        $inspections = $this->inspections;
        $snags = $this->snags;

        // Calculate approval percentage for each category
        $taskProgress = 0;
        if ($tasks->count() > 0) {
            $approvedTasks = $tasks->where('status', 'approve')->count();
            $taskProgress = ($approvedTasks / $tasks->count()) * 100;
        }

        $inspectionProgress = 0;
        if ($inspections->count() > 0) {
            $approvedInspections = $inspections->where('status', 'approved')->count();
            $inspectionProgress = ($approvedInspections / $inspections->count()) * 100;
        }

        $snagProgress = 0;
        if ($snags->count() > 0) {
            $approvedSnags = $snags->where('status', 'approve')->count();
            $snagProgress = ($approvedSnags / $snags->count()) * 100;
        }

        // Calculate total items
        $totalItems = $tasks->count() + $inspections->count() + $snags->count();
        
        if ($totalItems === 0) {
            // If no items exist, return 0
            return 0;
        }

        // Weighted average: each category contributes equally
        $categoryCount = 0;
        $totalProgress = 0;

        if ($tasks->count() > 0) {
            $totalProgress += $taskProgress;
            $categoryCount++;
        }

        if ($inspections->count() > 0) {
            $totalProgress += $inspectionProgress;
            $categoryCount++;
        }

        if ($snags->count() > 0) {
            $totalProgress += $snagProgress;
            $categoryCount++;
        }

        // If no categories have items, return 0
        if ($categoryCount === 0) {
            return 0;
        }

        // Average of all categories that have items
        $finalProgress = $totalProgress / $categoryCount;

        return round($finalProgress, 2);
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