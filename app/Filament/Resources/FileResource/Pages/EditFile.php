<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFile extends EditRecord
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract file information if file was updated
        if (isset($data['file_path']) && $data['file_path'] !== $this->record->file_path) {
            $filePath = $data['file_path'];
            $fullPath = storage_path('app/public/' . $filePath);
            
            if (file_exists($fullPath)) {
                $data['file_size'] = filesize($fullPath);
                $data['file_type'] = mime_content_type($fullPath);
                $data['name'] = basename($filePath);
            }
        }

        return $data;
    }
}