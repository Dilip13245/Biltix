<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'price',
        'billing_period',
        'currency',
        'description',
        'max_projects',
        'max_team_members',
        'is_default',
        'is_active',
        'sort_order',
        'enabled_modules'  // Simple array of enabled module names
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_projects' => 'integer',
        'max_team_members' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'enabled_modules' => 'array'  // JSON array of module names
    ];

    /**
     * Get all features for this plan (legacy support)
     */
    public function features()
    {
        return $this->hasMany(PlanFeature::class, 'plan_id');
    }

    /**
     * Get all subscriptions using this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }

    /**
     * Scope: Only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if plan has access to a specific module
     * Simple check - just checks if module is in enabled_modules array
     */
    public function hasModuleAccess(string $module): bool
    {
        $enabledModules = $this->enabled_modules ?? [];
        return in_array($module, $enabledModules);
    }

    /**
     * Get all enabled modules for this plan
     */
    public function getEnabledModules(): array
    {
        return $this->enabled_modules ?? [];
    }

    /**
     * Get the formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    /**
     * Get the default plan
     */
    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->where('is_active', true)->first();
    }
}

