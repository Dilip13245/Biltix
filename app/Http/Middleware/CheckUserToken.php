<?php

namespace App\Http\Middleware;

use App\Helpers\EncryptDecrypt;
use App\Models\UserDevice;
use Closure;
use Illuminate\Http\Request;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class CheckUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($request->hasHeader('token')) {

                $token = EncryptDecrypt::requestDecrypt($request->header('token'), 'token');
                if ($token != '') {

                    if ($token == env('GUESTTOKEN')) {

                        $request['user_type'] = 'GUEST';

                        return $next($request);
                    } else {

                        $userDeviceData = UserDevice::where('token', $token)->first();

                        if ($userDeviceData) {
                            $request['user_id'] = $userDeviceData->user_id;
                            $request['token'] = $token;

                            return $next($request);
                        } else {
                            if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                                return response()->json(
                                    EncryptDecrypt::bodyEncrypt(json_encode([
                                        'code' => 401,
                                        'message' => trans('auth.token_not_found'),
                                        'data' => new stdClass(),
                                    ])),
                                    401
                                );
                            } else {
                                return response()->json([
                                    'code' => 401,
                                    'message' => trans('auth.token_not_found'),
                                    'data' => new stdClass(),
                                ], 401);
                            }
                        }
                    }
                } else {
                    if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                        return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                            'code' => 401,
                            'message' => trans('auth.invalid_token'),
                            'data' => new stdClass(),
                        ])), 401);
                    } else {
                        return response()->json([
                            'code' => 401,
                            'message' => trans('auth.invalid_token'),
                            'data' => new stdClass(),
                        ], 401);
                    }
                }
            } else {
                if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                    return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                        'code' => 401,
                        'message' => trans('auth.token_not_found'),
                        'data' => new stdClass(),
                    ])), 401);
                } else {
                    return response()->json([
                        'code' => 401,
                        'message' => trans('auth.token_not_found'),
                        'data' => new stdClass(),
                    ], 401);
                }
            }
        } catch (\Exception $e) {
            // Handle the exception
            // You can log the error, return a custom response, or take any other action based on your application's requirements.
            // For example:
            if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
                return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                    'code' => 401,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'data' => new stdClass(),
                ])), 401);
            } else {
                return response()->json([
                    'code' => 401,
                    'message' => 'An error occurred: ' . $e->getMessage(),
                    'data' => new stdClass(),
                ], 401);
            }
        }
    }
}