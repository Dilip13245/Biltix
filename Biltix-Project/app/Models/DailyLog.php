<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'log_date',
        'weather_conditions',
        'work_performed',
        'materials_used',
        'equipment_used',
        'workers_count',
        'issues_encountered',
        'notes',
        'images',
    ];

    protected $casts = [
        'log_date' => 'date',
        'materials_used' => 'array',
        'equipment_used' => 'array',
        'workers_count' => 'integer',
        'images' => 'array',
    ];

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}