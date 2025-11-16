<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLogRoleDescription extends Model
{
    use HasFactory;

    protected $table = 'daily_log_role_descriptions';

    protected $fillable = [
        'project_id',
        'created_by',
        'role',
        'description',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Get the project that owns this role description.
     */
    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id');
    }

    /**
     * Get the user who created this role description.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope to get only active and non-deleted records.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}
