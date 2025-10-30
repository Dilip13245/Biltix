<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'task_number', 'project_id', 'phase_id', 'title', 'description', 'status', 'priority',
        'assigned_to', 'created_by', 'start_date', 'due_date', 'estimated_hours', 'completed_at',
        'progress_percentage', 'location', 'attachments', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'completed_at' => 'datetime',
        'progress_percentage' => 'integer',
        'attachments' => 'array',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function images()
    {
        return $this->hasMany(TaskImage::class)->where('is_active', true)->where('is_deleted', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }

    // Automatically set status to pending if current date >= due_date
    // Commented out to return actual database status
    // public function getStatusAttribute($value)
    // {
    //     if ($value !== 'completed' && $this->due_date && now()->format('Y-m-d') >= $this->due_date->format('Y-m-d')) {
    //         return 'pending';
    //     }
    //     return $value;
    // }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($task) {
            if (!$task->status) {
                $task->status = 'todo';
            }
            if (!$task->task_number) {
                $task->task_number = 'TSK-' . str_pad(Task::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}