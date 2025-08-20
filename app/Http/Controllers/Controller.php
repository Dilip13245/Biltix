<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\EncryptDecrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*
    * encrypt page load
    */
    public function encryptIndex()
    {
        return view('encrypt');
    }

    /**
     * Send response to user
     *
     * @return json;
     */
    public function toJsonEnc($result = [], $message = '', $status = 200)
    {
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                'code' => $status,
                'message' => $message,
                'data' => ! empty($result) ? $result : new \stdClass(),
            ])), $status);
        } else {
            return response()->json([
                'code' => $status,
                'message' => $message,
                'data' => !empty($result) ? $result : new \stdClass(),
            ], $status);
        }
    }

    /**
     * change encrype decrypt function
     *
     *
     * @return Response json
     */
    public function changeEncDecData(Request $request)
    {
        if ($request->type == 'encrypt') {
            $data['decrypt_value'] = $request->data;
            $data['encrypt_value'] = EncryptDecrypt::bodyEncrypt($request->data);
        } else {
            $data['decrypt_value'] = json_encode(EncryptDecrypt::bodyDecrypt($request->data));
            $data['encrypt_value'] = $request->data;
        }

        return view('encrypt', $data);
    }

    public function validateResponse($errors, $result = [])
    {
        $err = '';

        foreach ($errors->all() as $key => $val) {
            $err = $val;
            break;
        }
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                'code' => Config::get('constant.ERROR'),
                'message' => $err,
                'data' => ! empty($result) ? $result : new \stdClass(),
            ])));
        } else {
            return response()->json([
                'code' => Config::get('constant.ERROR'),
                'message' => $err,
                'data' => ! empty($result) ? $result : new \stdClass(),
            ]);
        }
    }
}