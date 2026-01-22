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
             if ($request->expectsJson()) {
                return response()->json([
                    'code' => 403,
                    'message' => 'Access denied. Subscription plan limit or expired.',
                    'data' => new \stdClass()
                ], 403);
            }
            
            $moduleName = __('messages.' . $module, [], null, app()->getLocale());
            if ($moduleName === 'messages.' . $module) {
                $moduleName = ucfirst(str_replace('_', ' ', $module));
            }
            
            // Allow dashboard but block specific modules
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