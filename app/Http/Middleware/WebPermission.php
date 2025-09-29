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