<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        $username = env('USER_FOR_ENC_DEC');
        $password = env('PASSWORD_FOR_ENC_DEC');

        if ($request->getUser() !== $username || $request->getPassword() !== $password) {
            return response('Unauthorized', 401, [
                'WWW-Authenticate' => 'Basic realm="Encryption Tool"'
            ]);
        }

        return $next($request);
    }
}