<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'project_code', 'project_title', 'contractor_name', 'project_manager_id', 'technical_engineer_id',
        'type', 'project_location', 'project_start_date', 'project_due_date',
        'priority', 'status', 'created_by', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'project_start_date' => 'date',
        'project_due_date' => 'date',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    // Relations using joins
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id', 'id');
    }

    public function technicalEngineer()
    {
        return $this->belongsTo(User::class, 'technical_engineer_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function phases()
    {
        return $this->hasMany(ProjectPhase::class, 'project_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'project_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'project_id', 'id');
    }
}