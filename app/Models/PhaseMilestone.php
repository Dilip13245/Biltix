<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseMilestone extends Model
{
    use HasFactory;

    protected $table = 'phase_milestones';

    protected $fillable = [
        'phase_id', 'milestone_name', 'days', 'extension_days', 'is_extended', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'extension_days' => 'integer',
        'is_extended' => 'boolean',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    protected $appends = ['start_date', 'due_date', 'original_due_date', 'time_progress', 'extended_progress', 'is_overdue'];

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }

    public function project()
    {
        return $this->hasOneThrough(Project::class, ProjectPhase::class, 'id', 'id', 'phase_id', 'project_id');
    }

    public function getStartDateAttribute()
    {
        // Use milestone creation date as start date
        return $this->created_at;
    }

    public function getDueDateAttribute()
    {
        $startDate = $this->start_date;
        if (!$startDate || !$this->days) {
            return null;
        }

        $dueDate = $startDate->copy()->addDays($this->days);
        
        // Check if manually extended
        if ($this->is_extended && $this->attributes['extension_days'] > 0) {
            $dueDate = $dueDate->addDays($this->attributes['extension_days']);
        }

        return $dueDate;
    }

    public function getOriginalDueDateAttribute()
    {
        $startDate = $this->start_date;
        if (!$startDate || !$this->days) {
            return null;
        }

        return $startDate->copy()->addDays($this->days);
    }

    public function getTimeProgressAttribute()
    {
        $startDate = $this->start_date;
        $originalDueDate = $this->original_due_date;
        
        if (!$startDate || !$originalDueDate) {
            return 0;
        }

        // If milestone hasn't started yet, return 0
        if (now()->lt($startDate)) {
            return 0;
        }

        $originalTotalDays = $startDate->diffInDays($originalDueDate);
        if ($originalTotalDays <= 0) {
            return 100;
        }

        $elapsedDays = $startDate->diffInDays(now());
        $progress = min(100, max(0, ($elapsedDays / $originalTotalDays) * 100));
        
        return round($progress, 2);
    }

    public function getExtendedProgressAttribute()
    {
        $startDate = $this->start_date;
        $extendedDueDate = $this->due_date;
        
        if (!$startDate || !$extendedDueDate || !$this->is_extended) {
            return 0;
        }

        $extendedTotalDays = $startDate->diffInDays($extendedDueDate);
        if ($extendedTotalDays <= 0) {
            return 100;
        }

        $elapsedDays = $startDate->diffInDays(now());
        $progress = min(100, max(0, ($elapsedDays / $extendedTotalDays) * 100));
        
        return round($progress, 2);
    }

    public function getIsOverdueAttribute()
    {
        $dueDate = $this->due_date;
        return $dueDate && now()->gt($dueDate);
    }

    public function getExtensionDaysAttribute()
    {
        return $this->attributes['extension_days'] ?? 0;
    }
}