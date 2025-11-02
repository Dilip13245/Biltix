<?php

namespace App\Filament\Resources\InspectionResource\Pages;

use App\Filament\Resources\InspectionResource;
use App\Helpers\NotificationHelper;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewInspection extends ViewRecord
{
    protected static string $resource = InspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('complete')
                ->label('Mark Complete')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status !== 'completed')
                ->action(function () {
                    $oldStatus = $this->record->status;
                    $this->record->update(['status' => 'completed']);
                    
                    // Send notification for inspection completed
                    $project = \App\Models\Project::find($this->record->project_id);
                    $inspector = \App\Models\User::find(auth()->id());
                    if ($project && $inspector) {
                        NotificationHelper::send(
                            auth()->id(),
                            'inspection_completed',
                            'Inspection Completed Successfully',
                            "Your {$this->record->category} inspection has been completed successfully",
                            [
                                'inspection_id' => $this->record->id,
                                'project_id' => $project->id,
                                'category' => $this->record->category,
                                'action_url' => "/inspections/{$this->record->id}"
                            ],
                            'medium'
                        );
                        
                        NotificationHelper::sendToProjectTeam(
                            $project->id,
                            'inspection_completed',
                            'Inspection Completed',
                            "{$inspector->name} completed {$this->record->category} inspection",
                            [
                                'inspection_id' => $this->record->id,
                                'project_id' => $project->id,
                                'category' => $this->record->category,
                                'inspector_id' => auth()->id(),
                                'inspector_name' => $inspector->name,
                                'completed_at' => now()->toDateTimeString(),
                                'action_url' => "/inspections/{$this->record->id}"
                            ],
                            'high',
                            [auth()->id()]
                        );
                    }
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record->load('checklists', 'images'))
            ->schema([
                Section::make('Inspection Details')
                    ->schema([
                        TextEntry::make('project.project_title')
                            ->label('Project'),
                        TextEntry::make('phase.title')
                            ->label('Phase')
                            ->placeholder('No phase assigned'),
                        TextEntry::make('category')
                            ->label('Category')
                            ->badge(),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : 'Open'),
                        TextEntry::make('createdBy.name')
                            ->label('Created By'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                    ])->columns(2),

                Section::make('Checklist Items')
                    ->schema([
                        TextEntry::make('checklist_items')
                            ->label('')
                            ->html()
                            ->getStateUsing(function ($record) {
                                $checklists = \App\Models\InspectionChecklist::where('inspection_id', $record->id)->get();
                                
                                if ($checklists->isEmpty()) {
                                    return '<p class="text-gray-500">No checklist items found</p>';
                                }
                                
                                return '<ul class="space-y-2">' . $checklists->map(function ($checklist) {
                                    $icon = $checklist->is_checked 
                                        ? '<span class="text-green-600 font-bold">✓</span>' 
                                        : '<span class="text-red-600 font-bold">✗</span>';
                                    $status = $checklist->is_checked ? 'Checked' : 'Unchecked';
                                    return "<li class='flex items-center gap-2'>{$icon} <span class='font-medium'>{$checklist->checklist_item}</span> <span class='text-sm text-gray-500'>({$status})</span></li>";
                                })->implode('') . '</ul>';
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Images')
                    ->schema([
                        TextEntry::make('inspection_images')
                            ->label('')
                            ->html()
                            ->getStateUsing(function ($record) {
                                $images = \App\Models\InspectionImage::where('inspection_id', $record->id)->get();
                                
                                if ($images->isEmpty()) {
                                    return '<p class="text-gray-500">No images uploaded</p>';
                                }
                                
                                return '<div class="flex flex-wrap gap-4">' . $images->map(function ($image) {
                                    $url = asset('storage/' . $image->image_path);
                                    $name = $image->original_name ?: 'Image';
                                    return "<div class='border rounded p-2'><img src='{$url}' alt='{$name}' class='w-full h-32 object-cover rounded mb-2'><p class='text-sm text-gray-600'>{$name}</p></div>";
                                })->implode('') . '</div>';
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}