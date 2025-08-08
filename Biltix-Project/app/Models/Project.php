<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'start_date',
        'end_date',
        'budget',
        'location',
        'client_name',
        'client_contact',
        'progress_percentage',
        'project_manager_id',
        'images',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'progress_percentage' => 'integer',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class);
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_team_members');
    }

    public function plans()
    {
        return $this->hasMany(ProjectPlan::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProjectGallery::class);
    }
}