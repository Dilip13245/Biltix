<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\RoleHelper;

class WebPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = $request->attributes->get('user');
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication required');
        }

        // Check Subscription Access first
        // This ensures expired subscriptions or plans without the module are blocked
        if (!\App\Helpers\SubscriptionHelper::hasModuleAccess($user, $module)) {
            // Check if subscription is completely expired (no active subscription)
            $subscriptionStatus = \App\Helpers\SubscriptionHelper::checkSubscriptionExpiry($user);
            $isExpired = !$subscriptionStatus['is_valid'] && 
                         ($subscriptionStatus['message'] === 'No active subscription' || 
                          $subscriptionStatus['message'] === 'Subscription has expired');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'code' => 403,
                    'message' => $isExpired ? 'Subscription expired. Please renew.' : 'Access denied. Feature not included in your plan.',
                    'subscription_expired' => $isExpired,
                    'data' => new \stdClass()
                ], 403);
            }
            
            // If subscription is expired, redirect to renewal page
            if ($isExpired) {
                return redirect()->route('subscription.renew')
                    ->with('error', __('messages.subscription_expired_renew'))
                    ->with('subscription_expired', true);
            }
            
            $moduleName = __('messages.' . $module, [], null, app()->getLocale());
            if ($moduleName === 'messages.' . $module) {
                $moduleName = ucfirst(str_replace('_', ' ', $module));
            }
            
            // Module not in plan - redirect back with error
            $errorMessage = __('messages.subscription_plan_limit', ['module' => $moduleName]);
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->with('subscription_limit', true)
                ->with('denied_module', $moduleName);
        }

        if (!RoleHelper::hasPermission($user, $module, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'code' => 403,
                    'message' => 'Access denied. Insufficient permissions.',
                    'data' => new \stdClass()
                ], 403);
            }
            
            // Get module display name from language file
            $moduleName = __('messages.' . $module, [], null, app()->getLocale());
            
            // If translation doesn't exist, use capitalized module name
            if ($moduleName === 'messages.' . $module) {
                $moduleName = ucfirst(str_replace('_', ' ', $module));
            }
            
            $errorMessage = __('messages.permission_denied_for_module', ['module' => $moduleName]);
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->with('permission_denied', true)
                ->with('denied_module', $moduleName);
        }

        return $next($request);
    }
}