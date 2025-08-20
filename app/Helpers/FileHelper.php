<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Upload image file
     */
    public static function uploadImage(UploadedFile $file, string $directory = 'uploads'): string
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Store in storage/app/public/{directory}
        $file->storeAs('public/' . $directory, $filename);
        
        return $filename;
    }

    /**
     * Upload any file
     */
    public static function uploadFile(UploadedFile $file, string $directory = 'uploads'): array
    {
        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $size = $file->getSize();
        $mimeType = $file->getMimeType();
        
        // Store in storage/app/public/{directory}
        $file->storeAs('public/' . $directory, $filename);
        
        return [
            'filename' => $filename,
            'original_name' => $originalName,
            'size' => $size,
            'mime_type' => $mimeType,
            'path' => $directory . '/' . $filename
        ];
    }

    /**
     * Delete file
     */
    public static function deleteFile(string $filePath): bool
    {
        if (Storage::exists('public/' . $filePath)) {
            return Storage::delete('public/' . $filePath);
        }
        
        return false;
    }

    /**
     * Get file URL
     */
    public static function getFileUrl(string $filePath): string
    {
        return asset('storage/' . $filePath);
    }

    /**
     * Check if file exists
     */
    public static function fileExists(string $filePath): bool
    {
        return Storage::exists('public/' . $filePath);
    }
}