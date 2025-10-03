<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Models\ProjectPhase;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 2;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.tasks');
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label(__('filament.fields.task_title'))
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label(__('filament.fields.description'))
                ->rows(3),
            Forms\Components\Select::make('project_id')
                ->label(__('filament.fields.project'))
                ->options(Project::pluck('project_title', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\Select::make('phase_id')
                ->label(__('filament.fields.phase'))
                ->options(ProjectPhase::where('is_active', true)
                    ->where('is_deleted', false)
                    ->pluck('title', 'id'))
                ->searchable(),
            Forms\Components\Select::make('assigned_to')
                ->label(__('filament.fields.assigned_to'))
                ->options(User::where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\Hidden::make('created_by')
                ->default(auth()->id()),
            Forms\Components\DatePicker::make('due_date')
                ->label(__('filament.fields.due_date'))
                ->required()
                ->native(false),
            Forms\Components\Select::make('status')
                ->label(__('filament.fields.status'))
                ->options([
                    'pending' => __('filament.options.pending'),
                    'in_progress' => __('filament.options.in_progress'),
                    'completed' => __('filament.options.completed'),
                ])
                ->default('in_progress')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task_number')
                    ->label(__('filament.fields.task_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.project_title')
                    ->label(__('filament.fields.project'))
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.fields.task_title'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label(__('filament.fields.assigned_to'))
                    ->searchable()
                    ->default(__('filament.options.unassigned')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if ($record->status !== 'completed' && $record->due_date && now()->format('Y-m-d') >= $record->due_date->format('Y-m-d')) {
                            return 'overdue';
                        }
                        return $record->status;
                    })
                    ->formatStateUsing(fn (?string $state): string => match($state) {
                        'overdue' => __('filament.options.overdue'),
                        'pending' => __('filament.options.pending'),
                        'in_progress' => __('filament.options.in_progress'),
                        'completed' => __('filament.options.completed'),
                        default => __('filament.options.pending')
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'overdue' => 'danger',
                        'pending' => 'gray',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('filament.fields.due_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label(__('filament.fields.project'))
                    ->options(Project::pluck('project_title', 'id')),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.fields.status'))
                    ->options([
                        'pending' => __('filament.options.pending'),
                        'in_progress' => __('filament.options.in_progress'),
                        'completed' => __('filament.options.completed'),
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label(__('filament.fields.assigned_to'))
                    ->options(User::where('is_active', true)->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_completed')
                    ->label(__('filament.actions.mark_completed'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'completed')
                    ->action(function ($record) {
                        $record->update(['status' => 'completed']);
                        Notification::make()
                            ->title(__('filament.messages.task_completed'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}