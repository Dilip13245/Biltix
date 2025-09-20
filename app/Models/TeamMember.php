<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'team_members';

    protected $fillable = [
        'user_id', 'project_id', 'role_in_project', 'assigned_at',
        'assigned_by', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}