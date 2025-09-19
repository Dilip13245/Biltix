<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\File;
use App\Models\FileCategory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('delete')
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['is_deleted' => true]);
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing files for display
        $constructionPlans = $this->record->files()
            ->whereHas('category', fn($q) => $q->where('name', 'Construction Plans'))
            ->where('is_deleted', false)
            ->pluck('file_path')
            ->toArray();
            
        $ganttCharts = $this->record->files()
            ->whereHas('category', fn($q) => $q->where('name', 'Gantt Charts'))
            ->where('is_deleted', false)
            ->pluck('file_path')
            ->toArray();
            
        $data['construction_plans'] = $constructionPlans;
        $data['gantt_chart'] = $ganttCharts;
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        $this->handleFileUploads();
    }
    
    protected function handleFileUploads(): void
    {
        $data = $this->form->getState();
        
        // Get or create file categories
        $constructionPlansCategory = FileCategory::firstOrCreate(['name' => 'Construction Plans']);
        $ganttChartCategory = FileCategory::firstOrCreate(['name' => 'Gantt Charts']);
        
        // Get existing file paths
        $existingConstructionPlans = $this->record->files()
            ->whereHas('category', fn($q) => $q->where('name', 'Construction Plans'))
            ->where('is_deleted', false)
            ->pluck('file_path')
            ->toArray();
            
        $existingGanttCharts = $this->record->files()
            ->whereHas('category', fn($q) => $q->where('name', 'Gantt Charts'))
            ->where('is_deleted', false)
            ->pluck('file_path')
            ->toArray();
        
        // Handle construction plans
        $newConstructionPlans = $data['construction_plans'] ?? [];
        
        // Mark removed files as deleted
        $removedConstructionPlans = array_diff($existingConstructionPlans, $newConstructionPlans);
        if (!empty($removedConstructionPlans)) {
            $this->record->files()
                ->whereHas('category', fn($q) => $q->where('name', 'Construction Plans'))
                ->whereIn('file_path', $removedConstructionPlans)
                ->update(['is_deleted' => true]);
        }
        
        // Add new files
        $addedConstructionPlans = array_diff($newConstructionPlans, $existingConstructionPlans);
        foreach ($addedConstructionPlans as $filePath) {
            $this->createFileRecord($filePath, $constructionPlansCategory->id);
        }
        
        // Handle gantt charts
        $newGanttCharts = $data['gantt_chart'] ?? [];
        
        // Mark removed files as deleted
        $removedGanttCharts = array_diff($existingGanttCharts, $newGanttCharts);
        if (!empty($removedGanttCharts)) {
            $this->record->files()
                ->whereHas('category', fn($q) => $q->where('name', 'Gantt Charts'))
                ->whereIn('file_path', $removedGanttCharts)
                ->update(['is_deleted' => true]);
        }
        
        // Add new files
        $addedGanttCharts = array_diff($newGanttCharts, $existingGanttCharts);
        foreach ($addedGanttCharts as $filePath) {
            $this->createFileRecord($filePath, $ganttChartCategory->id);
        }
    }
    
    protected function createFileRecord(string $filePath, int $categoryId): void
    {
        $fullPath = storage_path('app/public/' . $filePath);
        
        if (file_exists($fullPath)) {
            // Check if file record already exists
            $existingFile = File::where('project_id', $this->record->id)
                ->where('file_path', $filePath)
                ->where('category_id', $categoryId)
                ->first();
                
            if (!$existingFile) {
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
            } else if ($existingFile->is_deleted) {
                // Restore if it was previously deleted
                $existingFile->update(['is_deleted' => false]);
            }
        }
    }
}