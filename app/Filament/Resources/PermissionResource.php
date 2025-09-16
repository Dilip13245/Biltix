<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.permissions');
    }
    
    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.user_management');
    }
    
    protected static ?int $navigationSort = 2;
    
    public static function canViewAny(): bool
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
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.fields.permission_details'))
                    ->schema([
                        Forms\Components\Select::make('module')
                            ->label(__('filament.fields.module'))
                            ->required()
                            ->options([
                                'projects' => 'Projects',
                                'tasks' => 'Tasks',
                                'inspections' => 'Inspections',
                                'snags' => 'Snags',
                                'plans' => 'Plans',
                                'files' => 'Files',
                                'photos' => 'Photos',
                                'daily_logs' => 'Daily Logs',
                                'team' => 'Team Management',
                                'notifications' => 'Notifications',
                                'reports' => 'Reports',
                                'timeline' => 'Timeline',
                                'progress' => 'Progress Tracking',
                            ])
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('name', null);
                            }),
                        Forms\Components\Select::make('action')
                            ->label(__('filament.fields.action'))
                            ->required()
                            ->options([
                                'create' => 'Create',
                                'view' => 'View',
                                'edit' => 'Edit',
                                'update' => 'Update',
                                'delete' => 'Delete',
                                'assign' => 'Assign',
                                'approve' => 'Approve',
                                'conduct' => 'Conduct',
                                'complete' => 'Complete',
                                'resolve' => 'Resolve',
                                'comment' => 'Comment',
                                'upload' => 'Upload',
                                'download' => 'Download',
                                'markup' => 'Markup',
                                'track' => 'Track',
                                'coordinate' => 'Coordinate',
                                'review' => 'Review',
                            ])
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (callable $set, $get) {
                                $module = $get('module');
                                $action = $get('action');
                                if ($module && $action) {
                                    $set('name', $module . '.' . $action);
                                    $set('display_name', ucfirst(str_replace('_', ' ', $module)) . ' - ' . ucfirst($action));
                                }
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.fields.permission_name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->minLength(3)
                            ->regex('/^[a-z_\.]+$/')
                            ->helperText(__('filament.helpers.auto_generated'))
                            ->validationMessages([
                                'regex' => __('validation.permission_name_format'),
                                'min_length' => __('validation.permission_name_min'),
                                'unique' => __('validation.permission_name_unique'),
                            ]),
                        Forms\Components\TextInput::make('display_name')
                            ->label(__('filament.fields.display_name'))
                            ->required()
                            ->maxLength(255)
                            ->minLength(3)
                            ->regex('/^[A-Za-z\s\-]+$/')
                            ->helperText(__('filament.helpers.user_friendly_name'))
                            ->validationMessages([
                                'regex' => __('validation.permission_display_format'),
                                'min_length' => __('validation.permission_display_min'),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament.fields.description'))
                            ->placeholder(__('filament.placeholders.permission_description'))
                            ->rows(3)
                            ->maxLength(300)
                            ->minLength(5)
                            ->validationMessages([
                                'min_length' => __('validation.permission_description_min'),
                                'max_length' => __('validation.permission_description_max'),
                            ]),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.fields.active'))
                            ->default(true),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label(__('filament.fields.permission'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('module')
                    ->label(__('filament.fields.module'))
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('action')
                    ->label(__('filament.fields.action'))
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'create' => 'success',
                        'edit', 'update' => 'warning',
                        'delete' => 'danger',
                        'view' => 'gray',
                        default => 'primary',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.fields.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->defaultSort('module')

            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('filament.actions.view'))
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()
                    ->label(__('filament.actions.edit'))
                    ->icon('heroicon-o-pencil')
                    ->after(function () {
                        Notification::make()
                            ->title(__('validation.permission_updated'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label(__('filament.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->after(function () {
                        Notification::make()
                            ->title(__('validation.permission_deleted'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
