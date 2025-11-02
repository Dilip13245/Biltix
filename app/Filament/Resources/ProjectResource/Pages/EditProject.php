<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\File;
use App\Models\FileCategory;
use App\Helpers\NotificationHelper;
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
                    $project = $this->record;
                    $project->update(['is_deleted' => true]);
                    
                    // Send notification for project deletion
                    NotificationHelper::sendToProjectTeam(
                        $project->id,
                        'project_deleted',
                        'Project Deleted',
                        "Project '{$project->project_title}' has been deleted",
                        [
                            'project_id' => $project->id,
                            'project_title' => $project->project_title,
                            'deleted_by' => auth()->id(),
                            'action_url' => "/projects"
                        ],
                        'high',
                        [auth()->id()]
                    );
                    
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
        
        // Send push notifications for project updates
        $project = $this->record->fresh(); // Refresh to get latest data
        $oldData = $this->record->getOriginal();
        $updater = \App\Models\User::find(auth()->id());
        
        // Refresh original after save to compare properly
        $this->record->refresh();
        $project = $this->record;
        
        // Check if project manager changed
        if (isset($oldData['project_manager_id']) && $oldData['project_manager_id'] != $project->project_manager_id) {
            // Notify old project manager if removed
            if ($oldData['project_manager_id']) {
                NotificationHelper::send(
                    $oldData['project_manager_id'],
                    'project_manager_changed',
                    'Project Manager Changed',
                    "You have been removed as project manager from '{$project->project_title}'",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'changed_by' => auth()->id(),
                        'action_url' => "/projects"
                    ],
                    'medium'
                );
            }
            
            // Notify new project manager if assigned
            if ($project->project_manager_id) {
                NotificationHelper::send(
                    $project->project_manager_id,
                    'project_manager_assigned',
                    'Assigned as Project Manager',
                    "You have been assigned as project manager for '{$project->project_title}'",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'assigned_by' => auth()->id(),
                        'assigned_by_name' => $updater ? $updater->name : 'Admin',
                        'action_url' => "/projects/{$project->id}"
                    ],
                    'high'
                );
            }
        }
        
        // Check if technical engineer changed
        if (isset($oldData['technical_engineer_id']) && $oldData['technical_engineer_id'] != $project->technical_engineer_id) {
            // Notify old technical engineer if removed
            if ($oldData['technical_engineer_id']) {
                NotificationHelper::send(
                    $oldData['technical_engineer_id'],
                    'technical_engineer_changed',
                    'Technical Engineer Changed',
                    "You have been removed as technical engineer from '{$project->project_title}'",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'changed_by' => auth()->id(),
                        'action_url' => "/projects"
                    ],
                    'medium'
                );
            }
            
            // Notify new technical engineer if assigned
            if ($project->technical_engineer_id) {
                NotificationHelper::send(
                    $project->technical_engineer_id,
                    'technical_engineer_assigned',
                    'Assigned as Technical Engineer',
                    "You have been assigned as technical engineer for '{$project->project_title}'",
                    [
                        'project_id' => $project->id,
                        'project_title' => $project->project_title,
                        'assigned_by' => auth()->id(),
                        'assigned_by_name' => $updater ? $updater->name : 'Admin',
                        'action_url' => "/projects/{$project->id}"
                    ],
                    'high'
                );
            }
        }
        
        // Check if status changed
        if (isset($oldData['status']) && $oldData['status'] != $project->status) {
            NotificationHelper::sendToProjectTeam(
                $project->id,
                'project_status_changed',
                'Project Status Updated',
                "Project '{$project->project_title}' status changed to " . ucfirst(str_replace('_', ' ', $project->status)),
                [
                    'project_id' => $project->id,
                    'project_title' => $project->project_title,
                    'old_status' => $oldData['status'],
                    'new_status' => $project->status,
                    'changed_by' => auth()->id(),
                    'changed_by_name' => $updater ? $updater->name : 'Admin',
                    'action_url' => "/projects/{$project->id}"
                ],
                'medium',
                [auth()->id()]
            );
        }
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