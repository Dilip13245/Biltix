<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        
        // Extract file information
        if (isset($data['file_path'])) {
            $filePath = $data['file_path'];
            $fullPath = storage_path('app/public/' . $filePath);
            
            if (file_exists($fullPath)) {
                $data['file_size'] = filesize($fullPath);
                $data['file_type'] = mime_content_type($fullPath);
                $data['name'] = basename($filePath);
                
                if (!isset($data['original_name']) || empty($data['original_name'])) {
                    $data['original_name'] = basename($filePath);
                }
            }
        }

        return $data;
    }
}