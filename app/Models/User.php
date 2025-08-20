<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'company_name',
        'designation', 'employee_count', 'member_number', 'member_name',
        'profile_image', 'language', 'timezone', 'last_login_at', 'otp',
        'is_active', 'is_deleted'
    ];

    protected $hidden = ['password', 'remember_token', 'otp'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Soft delete scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    // Relations using joins (no foreign keys)
    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'user_id', 'id');
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id', 'id');
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by', 'id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to', 'id');
    }
}