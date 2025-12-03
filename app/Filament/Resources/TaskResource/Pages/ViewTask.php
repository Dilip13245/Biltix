<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Helpers\NotificationHelper;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Enums\FontWeight;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('mark_completed')
                ->label('Mark Complete')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'completed')
                ->action(function () {
                    $oldStatus = $this->record->status;
                    $this->record->update([
                        'status' => 'completed',
                        'progress_percentage' => 100,
                        'completed_at' => now(),
                    ]);
                    
                    // Send notification for task completion
                    $project = \App\Models\Project::find($this->record->project_id);
                    $recipients = [$this->record->created_by];
                    if ($project && $project->project_manager_id) {
                        $recipients[] = $project->project_manager_id;
                    }
                    $recipients = array_unique(array_diff($recipients, [auth()->id(), $this->record->assigned_to]));
                    
                    if (!empty($recipients)) {
                        NotificationHelper::send(
                            $recipients,
                            'task_status_changed',
                            'Task Completed',
                            "Task '{$this->record->title}' has been marked as completed",
                            [
                                'task_id' => $this->record->id,
                                'task_title' => $this->record->title,
                                'old_status' => $oldStatus,
                                'new_status' => 'completed',
                                'completed_by' => auth()->id(),
                                'project_id' => $this->record->project_id,
                                'action_url' => "/tasks/{$this->record->id}"
                            ],
                            'medium'
                        );
                    }
                    
                    NotificationHelper::sendToProjectTeam(
                        $this->record->project_id,
                        'task_status_changed',
                        'Task Completed',
                        "Task '{$this->record->title}' has been completed",
                        [
                            'task_id' => $this->record->id,
                            'task_title' => $this->record->title,
                            'old_status' => $oldStatus,
                            'new_status' => 'completed',
                            'completed_by' => auth()->id(),
                            'project_id' => $this->record->project_id,
                            'action_url' => "/tasks/{$this->record->id}"
                        ],
                        'low',
                        [auth()->id(), $this->record->created_by, $this->record->assigned_to]
                    );
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Task Details')
                    ->schema([
                        TextEntry::make('task_number')
                            ->label('Task Number')
                            ->badge(),
                        TextEntry::make('title')
                            ->label('Task Name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->placeholder('No description provided')
                            ->extraAttributes(['style' => 'word-break: break-word; overflow-wrap: break-word;']),
                        TextEntry::make('project.project_title')
                            ->label('Project'),
                        TextEntry::make('phase.title')
                            ->label('Phase')
                            ->placeholder('No phase assigned'),
                        TextEntry::make('assignedUser.name')
                            ->label('Assigned To')
                            ->placeholder('Unassigned'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : 'Pending'),
                        TextEntry::make('due_date')
                            ->label('Due Date')
                            ->date(),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                    ])->columns(2),

                Section::make('Task Images')
                    ->schema([
                        TextEntry::make('task_images')
                            ->label('')
                            ->html()
                            ->getStateUsing(function ($record) {
                                $images = \App\Models\TaskImage::where('task_id', $record->id)->get();
                                
                                if ($images->isEmpty()) {
                                    return '<p class="text-gray-500">No images uploaded</p>';
                                }
                                
                                return '<div class="flex flex-wrap gap-4">' . $images->map(function ($image) {
                                    $url = asset('storage/' . $image->image_path);
                                    $name = $image->original_name ?: 'Task Image';
                                    return "<div class='border rounded p-2'><img src='{$url}' alt='{$name}' class='w-full h-32 object-cover rounded mb-2'><p class='text-sm text-gray-600'>{$name}</p></div>";
                                })->implode('') . '</div>';
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}