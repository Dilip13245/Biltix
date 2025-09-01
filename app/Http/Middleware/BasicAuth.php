<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\EncryptDecrypt;
use Illuminate\Support\Facades\Config;

class BasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        $username = env('USER_FOR_ENC_DEC');
        $password = env('PASSWORD_FOR_ENC_DEC');

        if ($request->getUser() !== $username || $request->getPassword() !== $password) {
            if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                    'code' => 401,
                    'message' => 'Unauthorized access',
                    'data' => new \stdClass(),
                ])), 401);
            } else {
                return response()->json([
                    'code' => 401,
                    'message' => 'Unauthorized access',
                    'data' => new \stdClass(),
                ], 401);
            }
        }

        return $next($request);
    }
}