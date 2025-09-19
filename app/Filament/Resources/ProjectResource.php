<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.projects');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.project_management');
    }
    
    public static function canViewAny(): bool
    {
        return true;
    }
    
    public static function canView($record): bool
    {
        return true;
    }
    
    public static function canCreate(): bool
    {
        return true;
    }
    
    public static function canEdit($record): bool
    {
        return true;
    }
    
    public static function canDelete($record): bool
    {
        return true;
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['project_code', 'project_title', 'contractor_name', 'project_location', 'type', 'status'];
    }
    
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->project_title;
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Code' => $record->project_code,
            'Contractor' => $record->contractor_name,
            'Type' => ucfirst($record->type),
            'Status' => ucfirst(str_replace('_', ' ', $record->status)),
            'Location' => $record->project_location,
        ];
    }
    
    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ProjectResource::getUrl('view', ['record' => $record]);
    }
    


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.fields.project_information'))->schema([

                Forms\Components\TextInput::make('project_title')
                    ->label(__('filament.fields.project_title'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(200)
                    ->regex('/^[a-zA-Z0-9\s\-\&\.\_\,\(\)\']+$/')
                    ->placeholder('Riverside Commercial Complex'),
                Forms\Components\TextInput::make('contractor_name')
                    ->label(__('filament.fields.contractor_name'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(200)
                    ->regex('/^[a-zA-Z0-9\s\-\&\.\_\,\(\)\']+$/')
                    ->placeholder('ABC Construction Ltd'),
                Forms\Components\Select::make('type')
                    ->label(__('filament.fields.project_type'))
                    ->required()
                    ->options([
                        'commercial' => 'Commercial',
                        'residential' => 'Residential',
                        'industrial' => 'Industrial',
                        'renovation' => 'Renovation',
                    ])
                    ->native(false)
                    ->placeholder('Select project type'),
            ])->columns(2),
            
            Forms\Components\Section::make(__('filament.fields.project_details'))->schema([
                Forms\Components\TextInput::make('project_location')
                    ->label(__('filament.fields.project_location'))
                    ->required()
                    ->minLength(5)
                    ->maxLength(500)
                    ->placeholder('123 Main Street, Downtown District, City'),
                Forms\Components\DatePicker::make('project_start_date')
                    ->label(__('filament.fields.start_date'))
                    ->required()
                    ->native(false)
                    ->minDate(now())
                    ->maxDate(now()->addYears(5)),
                Forms\Components\DatePicker::make('project_due_date')
                    ->label(__('filament.fields.due_date'))
                    ->required()
                    ->native(false)
                    ->after('project_start_date')
                    ->minDate(now())
                    ->maxDate(now()->addYears(10)),

            ])->columns(2),
            
            Forms\Components\Section::make(__('filament.fields.project_files'))->schema([
                Forms\Components\FileUpload::make('construction_plans')
                    ->label(__('filament.fields.construction_plans'))
                    ->multiple()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])
                    ->maxSize(25600) // 25MB
                    ->directory('projects/documents')
                    ->visibility('public')
                    ->downloadable()
                    ->previewable(false)
                    ->deletable(true)
                    ->helperText(__('filament.helpers.construction_plans_help')),
                Forms\Components\FileUpload::make('gantt_chart')
                    ->label(__('filament.fields.gantt_charts'))
                    ->multiple()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])
                    ->maxSize(25600) // 25MB
                    ->directory('projects/documents')
                    ->visibility('public')
                    ->downloadable()
                    ->previewable(false)
                    ->deletable(true)
                    ->helperText(__('filament.helpers.gantt_charts_help')),
            ])->columns(2),
            
            Forms\Components\Section::make(__('filament.fields.team_assignment'))->schema([
                Forms\Components\Select::make('project_manager_id')
                    ->label(__('filament.fields.project_manager'))
                    ->options(function () {
                        return User::where('role', 'project_manager')
                            ->where('is_active', true)
                            ->where('is_deleted', false)
                            ->get()
                            ->mapWithKeys(fn ($user) => [$user->id => "{$user->name} ({$user->email})"]);
                    })
                    ->searchable()
                    ->placeholder('Select project manager')
                    ->helperText(__('filament.helpers.project_manager_help')),
                Forms\Components\Select::make('technical_engineer_id')
                    ->label(__('filament.fields.technical_engineer'))
                    ->options(function () {
                        return User::where('role', 'site_engineer')
                            ->where('is_active', true)
                            ->where('is_deleted', false)
                            ->get()
                            ->mapWithKeys(fn ($user) => [$user->id => "{$user->name} ({$user->email})"]);
                    })
                    ->searchable()
                    ->placeholder('Select technical engineer')
                    ->helperText(__('filament.helpers.technical_engineer_help')),
                Forms\Components\Select::make('status')
                    ->label(__('filament.fields.status'))
                    ->required()
                    ->options([
                        'planning' => 'Planning',
                        'active' => 'Active',
                        'on_hold' => 'On Hold',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('planning')
                    ->native(false),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->default(true)
                    ->helperText(__('filament.helpers.inactive_projects')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withoutGlobalScope('active'))
            ->columns([
                Tables\Columns\TextColumn::make('project_code')
                    ->label(__('filament.fields.project_code'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('project_title')
                    ->label(__('filament.fields.project_title'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'commercial' => 'success',
                        'residential' => 'info',
                        'industrial' => 'warning',
                        'renovation' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('projectManager.name')
                    ->label(__('filament.fields.project_manager'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'planning' => 'gray',
                        'active' => 'success',
                        'on_hold' => 'warning',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('project_start_date')
                    ->label(__('filament.fields.start_date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('project_due_date')
                    ->label(__('filament.fields.due_date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('filament.fields.project_type'))
                    ->options([
                        'commercial' => 'Commercial',
                        'residential' => 'Residential',
                        'industrial' => 'Industrial',
                        'renovation' => 'Renovation',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.fields.status'))
                    ->options([
                        'planning' => 'Planning',
                        'active' => 'Active',
                        'on_hold' => 'On Hold',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.fields.status'))
                    ->placeholder(__('filament.options.all_projects'))
                    ->trueLabel(__('filament.options.active_only'))
                    ->falseLabel(__('filament.options.inactive_only')),
                Tables\Filters\TernaryFilter::make('is_deleted')
                    ->label(__('filament.fields.deleted_status'))
                    ->placeholder(__('filament.options.all_projects'))
                    ->trueLabel(__('filament.options.deleted_only'))
                    ->falseLabel(__('filament.options.not_deleted')),
            ])
            ->actionsColumnLabel('Actions')
            ->actions([
                Tables\Actions\Action::make('archive')
                    ->label(__('filament.actions.archive'))
                    ->icon('heroicon-o-archive-box')
                    ->color('info')
                    ->visible(fn (Project $record): bool => $record->is_active)
                    ->requiresConfirmation()
                    ->action(function (Project $record): void {
                        $record->update(['is_active' => false]);
                        Notification::make()
                            ->title(__('filament.messages.project_archived'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('restore')
                    ->label(__('filament.actions.restore'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn (Project $record): bool => !$record->is_active)
                    ->action(function (Project $record): void {
                        $record->update(['is_active' => true]);
                        Notification::make()
                            ->title(__('filament.messages.project_restored'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()->label('')->tooltip('View'),
                Tables\Actions\EditAction::make()->label('')->tooltip('Edit'),
                Tables\Actions\Action::make('delete')
                    ->label('')
                    ->tooltip(__('filament.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (Project $record): bool => !$record->is_deleted)
                    ->requiresConfirmation()
                    ->action(function (Project $record): void {
                        $record->update(['is_deleted' => true]);
                        Notification::make()
                            ->title(__('filament.messages.project_deleted'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('restore_deleted')
                    ->label('')
                    ->tooltip(__('filament.actions.restore'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn (Project $record): bool => $record->is_deleted)
                    ->action(function (Project $record): void {
                        $record->update(['is_deleted' => false]);
                        Notification::make()
                            ->title(__('filament.messages.project_restored'))
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete')
                        ->label(__('filament.actions.delete'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->action(function ($records): void {
                            $records->each->update(['is_deleted' => true]);
                            Notification::make()
                                ->title(__('filament.messages.projects_deleted'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('archive')
                        ->label(__('filament.actions.archive'))
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => false]);
                            Notification::make()
                                ->title(__('filament.messages.projects_archived'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('restore')
                        ->label(__('filament.actions.restore'))
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => true]);
                            Notification::make()
                                ->title(__('filament.messages.projects_restored'))
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),

        ];
    }
}