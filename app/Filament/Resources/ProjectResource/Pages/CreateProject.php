<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\File;
use App\Models\FileCategory;
use App\Helpers\FileHelper;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    protected static string $view = 'filament.resources.project-resource.pages.create-project';
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['project_code'] = $this->generateProjectCode();
        $data['priority'] = 'medium'; // Default priority
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        
        // Remove file fields from project data as they'll be handled separately
        unset($data['construction_plans'], $data['gantt_chart']);
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        $this->handleFileUploads();
    }
    
    protected function handleFileUploads(): void
    {
        $data = $this->form->getState();
        
        // Get or create file categories
        $constructionPlansCategory = FileCategory::firstOrCreate(['name' => 'Construction Plans']);
        $ganttChartCategory = FileCategory::firstOrCreate(['name' => 'Gantt Charts']);
        
        // Handle construction plans
        if (!empty($data['construction_plans'])) {
            foreach ($data['construction_plans'] as $filePath) {
                $this->createFileRecord($filePath, $constructionPlansCategory->id);
            }
        }
        
        // Handle gantt charts
        if (!empty($data['gantt_chart'])) {
            foreach ($data['gantt_chart'] as $filePath) {
                $this->createFileRecord($filePath, $ganttChartCategory->id);
            }
        }
    }
    
    protected function createFileRecord(string $filePath, int $categoryId): void
    {
        $fullPath = storage_path('app/public/' . $filePath);
        
        if (file_exists($fullPath)) {
            $originalName = basename($filePath);
            $fileSize = filesize($fullPath);
            $mimeType = mime_content_type($fullPath);
            
            File::create([
                'project_id' => $this->record->id,
                'category_id' => $categoryId,
                'name' => $originalName,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'file_type' => $mimeType,
                'uploaded_by' => auth()->id(),
                'is_public' => false,
            ]);
        }
    }
    
    private function generateProjectCode(): string
    {
        $year = date('Y');
        $lastProject = \App\Models\Project::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
            
        $nextNumber = $lastProject ? (int)substr($lastProject->project_code, -3) + 1 : 1;
        
        return 'PRJ-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}