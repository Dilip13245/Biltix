<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\TeamMember;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 5;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.team_members');
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
            Forms\Components\Select::make('project_id')
                ->label('Project')
                ->options(Project::pluck('project_title', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\Select::make('user_id')
                ->label('Team Member')
                ->options(User::where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\TextInput::make('role_in_project')
                ->label('Role in Project')
                ->required()
                ->maxLength(255),
            Forms\Components\Hidden::make('assigned_by')
                ->default(auth()->id()),
            Forms\Components\Hidden::make('assigned_at')
                ->default(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.project_title')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role_in_project')
                    ->label('Project Role')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('assignedBy.name')
                    ->label('Assigned By')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Assigned Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(Project::pluck('project_title', 'id')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Members')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $teamMember = $record;
                        $project = \App\Models\Project::find($teamMember->project_id);
                        $removedMember = \App\Models\User::find($teamMember->user_id);
                        
                        // Send notification before deletion
                        if ($project && $removedMember) {
                            \App\Helpers\NotificationHelper::send(
                                $teamMember->user_id,
                                'team_member_removed',
                                'Removed from Project Team',
                                "You have been removed from project '{$project->project_title}' team",
                                [
                                    'project_id' => $project->id,
                                    'project_title' => $project->project_title,
                                    'removed_by' => auth()->id(),
                                    'action_url' => "/projects"
                                ],
                                'medium'
                            );
                        }
                        
                        $record->update(['is_deleted' => true]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $teamMember = $record;
                                $project = \App\Models\Project::find($teamMember->project_id);
                                $removedMember = \App\Models\User::find($teamMember->user_id);
                                
                                // Send notification before deletion
                                if ($project && $removedMember) {
                                    \App\Helpers\NotificationHelper::send(
                                        $teamMember->user_id,
                                        'team_member_removed',
                                        'Removed from Project Team',
                                        "You have been removed from project '{$project->project_title}' team",
                                        [
                                            'project_id' => $project->id,
                                            'project_title' => $project->project_title,
                                            'removed_by' => auth()->id(),
                                            'action_url' => "/projects"
                                        ],
                                        'medium'
                                    );
                                }
                                
                                $record->update(['is_deleted' => true]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('assigned_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'view' => Pages\ViewTeamMember::route('/{record}'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}