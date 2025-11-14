<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Helpers\NotificationHelper;
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
    
    protected function afterCreate(): void
    {
        $file = $this->record;
        $project = \App\Models\Project::find($file->project_id);
        
        // Notify project team about new file upload
        if ($project) {
            NotificationHelper::sendToProjectTeam(
                $project->id,
                'file_uploaded',
                trans('messages.new_file_uploaded'),
                trans('messages.new_file_uploaded_to_project') . " '{$file->original_name}'",
                [
                    'file_id' => $file->id,
                    'file_name' => $file->original_name,
                    'file_category' => $file->category_id ?? 'Documents',
                    'project_id' => $project->id,
                    'uploaded_by' => auth()->id(),
                    'action_url' => "/files/{$file->id}"
                ],
                'low',
                [auth()->id()]
            );
        }
    }
}