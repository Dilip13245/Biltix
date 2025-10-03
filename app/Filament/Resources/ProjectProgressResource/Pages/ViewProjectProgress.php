<?php

namespace App\Filament\Resources\ProjectProgressResource\Pages;

use App\Filament\Resources\ProjectProgressResource;
use App\Models\ProjectActivity;
use App\Models\ProjectManpowerEquipment;
use App\Models\ProjectSafetyItem;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
class ViewProjectProgress extends ViewRecord
{
    protected static string $resource = ProjectProgressResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make(__('filament.labels.project_progress'))
                    ->columnSpanFull()
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.overview'))
                            ->schema([
                                Infolists\Components\Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                ])
                                ->schema([
                                    Infolists\Components\Section::make()
                                        ->schema([
                                            Infolists\Components\TextEntry::make('activities_count')
                                                ->label(__('filament.labels.total_activities'))
                                                ->state(fn() => $this->record->activities()->count())
                                                ->icon('heroicon-o-clipboard-document-list')
                                                ->size('lg')
                                                ->weight('bold'),
                                        ])
                                        ->columnSpan(1),
                                        
                                    Infolists\Components\Section::make()
                                        ->schema([
                                            Infolists\Components\TextEntry::make('manpower_count')
                                                ->label(__('filament.labels.manpower_equipment'))
                                                ->state(fn() => $this->record->manpowerEquipment()->count())
                                                ->icon('heroicon-o-users')
                                                ->size('lg')
                                                ->weight('bold'),
                                        ])
                                        ->columnSpan(1),
                                        
                                    Infolists\Components\Section::make()
                                        ->schema([
                                            Infolists\Components\TextEntry::make('safety_count')
                                                ->label(__('filament.labels.safety_items'))
                                                ->state(fn() => $this->record->safetyItems()->count())
                                                ->icon('heroicon-o-shield-check')
                                                ->size('lg')
                                                ->weight('bold'),
                                        ])
                                        ->columnSpan(1),
                                ]),
                                    
                                Infolists\Components\Section::make(__('filament.labels.project_information'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('project_title')
                                            ->label(__('filament.fields.project_title'))
                                            ->size('lg')
                                            ->weight('bold'),
                                    ]),
                            ]),
                            
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.ongoing_activities'))
                            ->schema([
                                Infolists\Components\Section::make(__('filament.labels.ongoing_activities'))
                                    ->headerActions([
                                        Infolists\Components\Actions\Action::make('addActivity')
                                            ->label(__('filament.actions.add_activity'))
                                            ->icon('heroicon-o-plus')
                                            ->color('primary')
                                            ->form([
                                                Forms\Components\Textarea::make('description')
                                                    ->label(__('filament.fields.description'))
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->rows(3),
                                            ])
                                            ->action(function (array $data) {
                                                ProjectActivity::create([
                                                    'project_id' => $this->record->id,
                                                    'description' => $data['description'],
                                                    'created_by' => auth()->id(),
                                                ]);
                                            })
                                            ->successNotificationTitle(__('filament.notifications.activity_added')),
                                    ])
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('activities')
                                            ->schema([
                                                Infolists\Components\Section::make()
                                                    ->schema([
                                                        Infolists\Components\Split::make([
                                                            Infolists\Components\Grid::make(2)
                                                                ->schema([
                                                                    Infolists\Components\TextEntry::make('description')
                                                                        ->label(__('filament.fields.activity'))
                                                                        ->weight('medium')
                                                                        ->size('sm'),
                                                                    Infolists\Components\TextEntry::make('created_at')
                                                                        ->label(__('filament.fields.created'))
                                                                        ->dateTime()
                                                                        ->size('sm')
                                                                        ->color('gray'),
                                                                ]),
                                                            Infolists\Components\Actions::make([
                                                                Infolists\Components\Actions\Action::make('editActivity')
                                                                    ->label(__('filament.actions.edit'))
                                                                    ->icon('heroicon-o-pencil')
                                                                    ->color('warning')
                                                                    ->size('sm')
                                                                    ->button()
                                                                    ->form([
                                                                        Forms\Components\Textarea::make('description')
                                                                            ->label(__('filament.fields.description'))
                                                                            ->required()
                                                                            ->maxLength(255)
                                                                            ->rows(3),
                                                                    ])
                                                                    ->fillForm(fn($record) => ['description' => $record->description])
                                                                    ->action(function (array $data, $record) {
                                                                        $record->update(['description' => $data['description']]);
                                                                    })
                                                                    ->successNotificationTitle(__('filament.notifications.activity_updated')),
                                                            ])
                                                            ->alignEnd(),
                                                        ]),
                                                    ])
                                                    ->compact(),
                                            ])
                                            ->contained(false),
                                    ])
                                    ->extraAttributes(['style' => 'max-height: 70vh; overflow-y: auto;']),
                            ]),
                            
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.manpower_equipment'))
                            ->schema([
                                Infolists\Components\Section::make(__('filament.labels.manpower_equipment'))
                                    ->headerActions([
                                        Infolists\Components\Actions\Action::make('addManpower')
                                            ->label(__('filament.actions.add_item'))
                                            ->icon('heroicon-o-plus')
                                            ->color('primary')
                                            ->form([
                                                Forms\Components\TextInput::make('category')
                                                    ->label(__('filament.fields.category'))
                                                    ->required()
                                                    ->maxLength(100),
                                                Forms\Components\TextInput::make('count')
                                                    ->label(__('filament.fields.count'))
                                                    ->required()
                                                    ->numeric()
                                                    ->minValue(0),
                                            ])
                                            ->action(function (array $data) {
                                                ProjectManpowerEquipment::create([
                                                    'project_id' => $this->record->id,
                                                    'category' => $data['category'],
                                                    'count' => $data['count'],
                                                    'created_by' => auth()->id(),
                                                ]);
                                            })
                                            ->successNotificationTitle(__('filament.notifications.item_added')),
                                    ])
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('manpowerEquipment')
                                            ->schema([
                                                Infolists\Components\Section::make()
                                                    ->schema([
                                                        Infolists\Components\Split::make([
                                                            Infolists\Components\Grid::make(2)
                                                                ->schema([
                                                                    Infolists\Components\TextEntry::make('category')
                                                                        ->label(__('filament.fields.category'))
                                                                        ->weight('medium')
                                                                        ->size('sm'),
                                                                    Infolists\Components\TextEntry::make('count')
                                                                        ->label(__('filament.fields.count'))
                                                                        ->badge()
                                                                        ->size('sm'),
                                                                ]),
                                                            Infolists\Components\Actions::make([
                                                                Infolists\Components\Actions\Action::make('editManpower')
                                                                    ->label(__('filament.actions.edit'))
                                                                    ->icon('heroicon-o-pencil')
                                                                    ->color('warning')
                                                                    ->size('sm')
                                                                    ->button()
                                                                    ->form([
                                                                        Forms\Components\TextInput::make('category')
                                                                            ->label(__('filament.fields.category'))
                                                                            ->required()
                                                                            ->maxLength(100),
                                                                        Forms\Components\TextInput::make('count')
                                                                            ->label(__('filament.fields.count'))
                                                                            ->required()
                                                                            ->numeric()
                                                                            ->minValue(0),
                                                                    ])
                                                                    ->fillForm(fn($record) => [
                                                                        'category' => $record->category,
                                                                        'count' => $record->count,
                                                                    ])
                                                                    ->action(function (array $data, $record) {
                                                                        $record->update([
                                                                            'category' => $data['category'],
                                                                            'count' => $data['count'],
                                                                        ]);
                                                                    })
                                                                    ->successNotificationTitle(__('filament.notifications.item_updated')),
                                                            ])
                                                            ->alignEnd(),
                                                        ]),
                                                    ])
                                                    ->compact(),
                                            ])
                                            ->contained(false),
                                    ])
                                    ->extraAttributes(['style' => 'max-height: 70vh; overflow-y: auto;']),
                            ]),
                            
                        Infolists\Components\Tabs\Tab::make(__('filament.labels.safety_category'))
                            ->schema([
                                Infolists\Components\Section::make(__('filament.labels.safety_category'))
                                    ->headerActions([
                                        Infolists\Components\Actions\Action::make('addSafety')
                                            ->label(__('filament.actions.add_safety_item'))
                                            ->icon('heroicon-o-plus')
                                            ->color('primary')
                                            ->form([
                                                Forms\Components\Textarea::make('checklist_item')
                                                    ->label(__('filament.fields.safety_item'))
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->rows(3),
                                            ])
                                            ->action(function (array $data) {
                                                ProjectSafetyItem::create([
                                                    'project_id' => $this->record->id,
                                                    'checklist_item' => $data['checklist_item'],
                                                    'created_by' => auth()->id(),
                                                ]);
                                            })
                                            ->successNotificationTitle(__('filament.notifications.safety_item_added')),
                                    ])
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('safetyItems')
                                            ->schema([
                                                Infolists\Components\Section::make()
                                                    ->schema([
                                                        Infolists\Components\Split::make([
                                                            Infolists\Components\Grid::make(2)
                                                                ->schema([
                                                                    Infolists\Components\TextEntry::make('checklist_item')
                                                                        ->label(__('filament.fields.safety_item'))
                                                                        ->weight('medium')
                                                                        ->size('sm'),
                                                                    Infolists\Components\TextEntry::make('created_at')
                                                                        ->label(__('filament.fields.created'))
                                                                        ->dateTime()
                                                                        ->size('sm')
                                                                        ->color('gray'),
                                                                ]),
                                                            Infolists\Components\Actions::make([
                                                                Infolists\Components\Actions\Action::make('editSafety')
                                                                    ->label(__('filament.actions.edit'))
                                                                    ->icon('heroicon-o-pencil')
                                                                    ->color('warning')
                                                                    ->size('sm')
                                                                    ->button()
                                                                    ->form([
                                                                        Forms\Components\Textarea::make('checklist_item')
                                                                            ->label(__('filament.fields.safety_item'))
                                                                            ->required()
                                                                            ->maxLength(255)
                                                                            ->rows(3),
                                                                    ])
                                                                    ->fillForm(fn($record) => ['checklist_item' => $record->checklist_item])
                                                                    ->action(function (array $data, $record) {
                                                                        $record->update(['checklist_item' => $data['checklist_item']]);
                                                                    })
                                                                    ->successNotificationTitle(__('filament.notifications.safety_item_updated')),
                                                            ])
                                                            ->alignEnd(),
                                                        ]),
                                                    ])
                                                    ->compact(),
                                            ])
                                            ->contained(false),
                                    ])
                                    ->extraAttributes(['style' => 'max-height: 70vh; overflow-y: auto;']),
                            ]),
                    ]),
            ]);
    }
}