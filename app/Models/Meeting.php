<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';

    protected $fillable = [
        'project_id', 'phase_id', 'title', 'description', 'date', 'start_time', 'end_time',
        'location_type', 'location', 'status', 'created_by', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'date' => 'date',
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

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_attendees', 'meeting_id', 'user_id')
                    ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(MeetingDocument::class, 'meeting_id');
    }
}
