<?php

namespace App\Helpers;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioHelper
{
    private static $client;

    private static function getClient()
    {
        if (!self::$client) {
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            self::$client = new Client($sid, $token);
        }
        return self::$client;
    }

    public static function sendSMS($to, $message)
    {
        try {
            $client = self::getClient();
            $from = env('TWILIO_PHONE_NUMBER');

            $message = $client->messages->create($to, [
                'from' => $from,
                'body' => $message
            ]);

            Log::info('SMS sent successfully', [
                'to' => $to,
                'message_sid' => $message->sid
            ]);

            return [
                'success' => true,
                'message_sid' => $message->sid
            ];
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public static function sendOTP($phone, $otp)
    {
        $message = "Your Biltix OTP is: {$otp}. Valid for 10 minutes. Do not share this code.";
        return self::sendSMS($phone, $message);
    }
}