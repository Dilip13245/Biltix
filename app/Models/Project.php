<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'project_code', 'name', 'description', 'type', 'status', 'location', 'address',
        'start_date', 'end_date', 'budget', 'actual_cost', 'client_name',
        'client_email', 'client_phone', 'client_address', 'project_manager_id',
        'created_by', 'progress_percentage', 'images', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'images' => 'array',
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
}