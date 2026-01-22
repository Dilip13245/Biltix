<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'feature_key',
        'feature_name',
        'module',
        'action',
        'limit_value',
        'is_active'
    ];

    protected $casts = [
        'limit_value' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the subscription plan
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Scope: Only active features
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by module
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Get all unique modules
     */
    public static function getModules(): array
    {
        return static::distinct()->pluck('module')->filter()->toArray();
    }

    /**
     * Get feature key from module and action
     */
    public static function makeFeatureKey(string $module, string $action): string
    {
        return "{$module}.{$action}";
    }

    /**
     * Parse feature key into module and action
     */
    public static function parseFeatureKey(string $featureKey): array
    {
        $parts = explode('.', $featureKey, 2);
        return [
            'module' => $parts[0] ?? null,
            'action' => $parts[1] ?? null
        ];
    }
}
