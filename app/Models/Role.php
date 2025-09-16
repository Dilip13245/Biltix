<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_roles');
    }

    public function hasPermission($module, $action)
    {
        return $this->permissions()
            ->where('module', $module)
            ->where('action', $action)
            ->where('is_active', true)
            ->exists();
    }
}