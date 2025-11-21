<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SnagResource\Pages;
use App\Models\Snag;
use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SnagResource extends Resource
{
    protected static ?string $model = Snag::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 4;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.snags');
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
                ->label(__('filament.fields.snag_title'))
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label(__('filament.fields.description'))
                ->required()
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
            Forms\Components\TextInput::make('location')
                ->label(__('filament.fields.location'))
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('assigned_to')
                ->label(__('filament.fields.assign_to'))
                ->options(User::where('is_active', true)->pluck('name', 'id'))
                ->searchable(),
            Forms\Components\Select::make('status')
                ->label(__('filament.fields.status'))
                ->options([
                    'todo' => __('filament.options.todo'),
                    'complete' => __('filament.options.completed'),
                    'approve' => __('filament.options.approve'),
                ])
                ->default('todo')
                ->required(),
            Forms\Components\Textarea::make('comment')
                ->label(__('filament.fields.comment'))
                ->rows(2),
            Forms\Components\Hidden::make('reported_by')
                ->default(auth()->id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('snag_number')
                    ->label(__('filament.fields.snag_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.project_title')
                    ->label(__('filament.fields.project'))
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('location')
                    ->label(__('filament.fields.location'))
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : 'Todo')
                    ->color(fn (?string $state): string => match ($state) {
                        'todo' => 'warning',
                        'complete' => 'success',
                        'approve' => 'primary',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('reporter.name')
                    ->label(__('filament.fields.reported_by'))
                    ->searchable()
                    ->default(__('filament.options.unknown')),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label(__('filament.fields.assigned_to'))
                    ->searchable()
                    ->default(__('filament.options.unassigned')),
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
                        'todo' => __('filament.options.todo'),
                        'complete' => __('filament.options.completed'),
                        'approve' => __('filament.options.approve'),
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label(__('filament.fields.assigned_to'))
                    ->options(User::where('is_active', true)->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resolve')
                    ->label(__('filament.actions.resolve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'complete' && $record->status !== 'approve')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'complete',
                            'resolved_at' => now(),
                            'resolved_by' => auth()->id(),
                        ]);
                        
                        // Send notification for snag resolution
                        $project = \App\Models\Project::find($record->project_id);
                        $resolver = \App\Models\User::find(auth()->id());
                        if ($project && $resolver) {
                            $recipients = [$record->reported_by];
                            if ($project->project_manager_id) {
                                $recipients[] = $project->project_manager_id;
                            }
                            $recipients = array_unique(array_diff($recipients, [auth()->id()]));
                            
                            if (!empty($recipients)) {
                                \App\Helpers\NotificationHelper::send(
                                    $recipients,
                                    'snag_resolved',
                                    __('filament.placeholders.snag_resolved_title'),
                                    __('filament.placeholders.snag_resolved_message', ['title' => $record->title, 'resolver' => $resolver->name]),
                                    [
                                        'snag_id' => $record->id,
                                        'snag_title' => $record->title,
                                        'project_id' => $project->id,
                                        'resolver_id' => auth()->id(),
                                        'resolver_name' => $resolver->name,
                                        'action_url' => "/snags/{$record->id}"
                                    ],
                                    'medium'
                                );
                            }
                        }
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
            'index' => Pages\ListSnags::route('/'),
            'create' => Pages\CreateSnag::route('/create'),
            'view' => Pages\ViewSnag::route('/{record}'),
            'edit' => Pages\EditSnag::route('/{record}/edit'),
        ];
    }
}