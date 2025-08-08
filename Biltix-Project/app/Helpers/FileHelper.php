<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class FileHelper
{
    /**
     * Upload an image and return its path or full URL.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param bool $withFullUrl
     * @return string|null
     */
   public static function uploadImage($file, $folder = 'uploads')
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

        $timestamp = now()->format('Ymd_His');
        $extension = $file->getClientOriginalExtension();
        $filename = $safeName . '_' . $timestamp . '.' . $extension;

        $file->storeAs($folder, $filename, 'public');

        return $filename;
    }
}

// FCM notification helper (global function, not inside class)
if (!function_exists('sendFcmNotification')) {
    function sendFcmNotification($fcmToken, $title, $body, $data = [])
    {
        $serverKey = config('services.fcm.server_key');
        if (!$serverKey || !$fcmToken) return false;
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ]);
        return $response->json();
    }
}