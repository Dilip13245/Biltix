<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Models\PlanFeature;
use Illuminate\Support\Facades\Cache;

class SubscriptionHelper
{
    /**
     * Cache TTL in seconds (5 minutes)
     */
    private const CACHE_TTL = 300;

    /**
     * Check if user has access to a feature through their subscription
     */
    public static function hasFeature($user, string $featureKey): bool
    {
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (!$user || !$user->is_active) {
            return false;
        }

        // Get the company owner (for team members, get parent user)
        $companyOwner = self::getCompanyOwner($user);
        
        if (!$companyOwner) {
            return false;
        }

        $cacheKey = "subscription_feature_{$companyOwner->id}_{$featureKey}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($companyOwner, $featureKey) {
            $subscription = self::getActiveSubscription($companyOwner);

            if (!$subscription) {
                return false;
            }

            return $subscription->hasFeature($featureKey);
        });
    }

    /**
     * Check if user has module access (simple check against enabled_modules array)
     */
    public static function hasModuleAccess($user, string $module): bool
    {
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (!$user || !$user->is_active) {
            return false;
        }

        // Get the company owner (for team members, get parent user)
        $companyOwner = self::getCompanyOwner($user);
        
        if (!$companyOwner) {
            return false;
        }

        $cacheKey = "subscription_module_{$companyOwner->id}_{$module}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($companyOwner, $module) {
            $subscription = self::getActiveSubscription($companyOwner);

            if (!$subscription || !$subscription->plan) {
                return false;
            }

            return $subscription->plan->hasModuleAccess($module);
        });
    }

    /**
     * Get the company owner for a user
     */
    public static function getCompanyOwner($user): ?User
    {
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (!$user) {
            return null;
        }

        // If user is a sub-user (team member), get parent
        if ($user->is_sub_user && $user->parent_user_id) {
            return User::find($user->parent_user_id);
        }

        return $user;
    }

    /**
     * Get active subscription for a company owner
     */
    public static function getActiveSubscription($user): ?UserSubscription
    {
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (!$user) {
            return null;
        }

        $companyOwner = self::getCompanyOwner($user);

        if (!$companyOwner) {
            return null;
        }

        return UserSubscription::where('user_id', $companyOwner->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Get subscription info for a user
     */
    public static function getUserSubscriptionInfo($user): array
    {
        if (is_int($user)) {
            $user = User::find($user);
        }

        if (!$user) {
            return [
                'has_subscription' => false,
                'plan_name' => null,
                'status' => 'user_not_found',
                'expires_at' => null,
                'days_remaining' => 0,
                'features' => []
            ];
        }

        $subscription = self::getActiveSubscription($user);

        if (!$subscription) {
            return [
                'has_subscription' => false,
                'plan_name' => null,
                'status' => 'no_subscription',
                'expires_at' => null,
                'days_remaining' => 0,
                'features' => []
            ];
        }

        $info = $subscription->toInfoArray();
        $info['has_subscription'] = true;
        $info['enabled_modules'] = $subscription->plan->getEnabledModules();

        return $info;
    }

    /**
     * Check if subscription is expired or expiring soon
     */
    public static function checkSubscriptionExpiry($user): array
    {
        $subscription = self::getActiveSubscription($user);

        if (!$subscription) {
            return [
                'is_valid' => false,
                'message' => 'No active subscription'
            ];
        }

        if ($subscription->isExpired()) {
            return [
                'is_valid' => false,
                'message' => 'Subscription has expired'
            ];
        }

        if ($subscription->isExpiringSoon()) {
            return [
                'is_valid' => true,
                'warning' => true,
                'message' => "Subscription expires in {$subscription->daysRemaining()} days"
            ];
        }

        return [
            'is_valid' => true,
            'warning' => false,
            'message' => 'Subscription is active'
        ];
    }

    /**
     * Clear subscription cache for a user
     */
    public static function clearSubscriptionCache($userId): void
    {
        $user = is_int($userId) ? User::find($userId) : $userId;
        
        if (!$user) {
            return;
        }

        $companyOwner = self::getCompanyOwner($user);
        
        if (!$companyOwner) {
            return;
        }

        // Get all features and clear their cache
        $features = PlanFeature::distinct()->pluck('feature_key');
        
        foreach ($features as $featureKey) {
            Cache::forget("subscription_feature_{$companyOwner->id}_{$featureKey}");
        }
    }

    /**
     * Create subscription for a user
     */
    public static function createSubscription($userId, $planId, int $durationDays = 365): UserSubscription
    {
        return UserSubscription::create([
            'user_id' => $userId,
            'plan_id' => $planId,
            'starts_at' => now(),
            'expires_at' => now()->addDays($durationDays),
            'status' => 'active',
            'auto_renew' => false,
            'is_trial' => false
        ]);
    }

    /**
     * Get all available plans
     */
    public static function getAvailablePlans(): array
    {
        return SubscriptionPlan::active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'display_name' => $plan->display_name,
                    'price' => $plan->price,
                    'billing_period' => $plan->billing_period,
                    'currency' => $plan->currency,
                    'description' => $plan->description,
                    'enabled_modules' => $plan->getEnabledModules(),
                    'is_default' => $plan->is_default
                ];
            })
            ->toArray();
    }
}
