<?php

namespace App\Filament\Resources\PhotoResource\Pages;

use App\Filament\Resources\PhotoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePhoto extends CreateRecord
{
    protected static string $resource = PhotoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['taken_by'] = auth()->id();
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        
        // Extract file information
        if (isset($data['file_path'])) {
            $filePath = $data['file_path'];
            $fullPath = storage_path('app/public/' . $filePath);
            
            if (file_exists($fullPath)) {
                $data['file_size'] = filesize($fullPath);
                $data['file_name'] = basename($filePath);
            }
        }

        return $data;
    }
}