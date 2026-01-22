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
        'is_active', 'is_deleted', 'is_sub_user', 'parent_user_id', 'force_password_change'
    ];

    protected $hidden = ['password', 'remember_token', 'otp'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'is_sub_user' => 'boolean',
        'force_password_change' => 'boolean',
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

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id', 'id');
    }

    public function subUsers()
    {
        return $this->hasMany(User::class, 'parent_user_id', 'id');
    }

    // Permission methods - checks BOTH subscription module access AND role permission
    public function hasPermission($module, $action)
    {
        // First check: Does user's subscription plan include this module?
        if (!\App\Helpers\SubscriptionHelper::hasModuleAccess($this, $module)) {
            return false;
        }
        
        // Second check: Does user's role have this specific permission?
        return \App\Helpers\RoleHelper::hasPermission($this, $module, $action);
    }

    public function lacksPermission($module, $action)
    {
        return !$this->hasPermission($module, $action);
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public function getDashboardAccess()
    {
        return \App\Helpers\RoleHelper::getDashboardAccess($this->role);
    }

    public function canRegister()
    {
        return \App\Helpers\RoleHelper::canRegister($this->role);
    }

    public function getRoleDisplayName()
    {
        return \App\Helpers\RoleHelper::getRoleDisplayName($this->role);
    }

    // ================================
    // Subscription Methods
    // ================================

    /**
     * Get the subscription for this user (own or parent's)
     */
    public function subscription()
    {
        // If this is a sub-user, get parent's subscription
        if ($this->is_sub_user && $this->parent_user_id) {
            return $this->hasOneThrough(
                \App\Models\UserSubscription::class,
                User::class,
                'id', // Local key on users (parent_user_id points to this)
                'user_id', // Foreign key on user_subscriptions
                'parent_user_id', // Local key on this user
                'id' // Local key on parent user
            );
        }

        // Main user has their own subscription
        return $this->hasOne(\App\Models\UserSubscription::class, 'user_id');
    }

    /**
     * Get the company owner (for subscription checking)
     */
    public function getCompanyOwner(): ?User
    {
        if ($this->is_sub_user && $this->parent_user_id) {
            return User::find($this->parent_user_id);
        }
        return $this;
    }

    /**
     * Get active subscription (own or parent's)
     */
    public function getActiveSubscription(): ?\App\Models\UserSubscription
    {
        $owner = $this->getCompanyOwner();
        
        if (!$owner) {
            return null;
        }

        return \App\Models\UserSubscription::where('user_id', $owner->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Check if user has subscription feature access
     */
    public function hasSubscriptionFeature(string $featureKey): bool
    {
        return \App\Helpers\SubscriptionHelper::hasFeature($this, $featureKey);
    }

    /**
     * Check if user can access module.action (role + subscription)
     */
    public function canAccess(string $module, string $action): bool
    {
        // First check role permission
        if (!$this->hasPermission($module, $action)) {
            return false;
        }

        // Then check subscription feature
        return $this->hasSubscriptionFeature("{$module}.{$action}");
    }

    /**
     * Get subscription info for API response
     */
    public function getSubscriptionInfo(): array
    {
        $subscription = $this->getActiveSubscription();
        
        if (!$subscription) {
            return [
                'has_subscription' => false,
                'plan_name' => null,
                'status' => 'no_subscription',
                'expires_at' => null,
                'days_remaining' => 0
            ];
        }

        return $subscription->toInfoArray();
    }
}