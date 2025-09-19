<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Forms\Components\View;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.roles');
    }
    
    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Admin Management';
    }
    
    protected static ?int $navigationSort = 1;
    
    public static function canViewAny(): bool
    {
        return true; // Allow all authenticated admin users
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
                Forms\Components\Section::make(__('filament.fields.role_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.fields.role_name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->minLength(3)
                            ->regex('/^[a-z_]+$/')
                            ->placeholder(__('filament.placeholders.role_example'))
                            ->helperText(__('filament.helpers.use_lowercase'))
                            ->validationMessages([
                                'regex' => __('validation.role_name_format'),
                                'min_length' => __('validation.role_name_min'),
                            ]),
                        Forms\Components\TextInput::make('display_name')
                            ->label(__('filament.fields.display_name'))
                            ->required()
                            ->maxLength(255)
                            ->minLength(2)
                            ->regex('/^[A-Za-z\s]+$/')
                            ->placeholder(__('filament.placeholders.display_example'))
                            ->helperText(__('filament.helpers.user_friendly_name'))
                            ->validationMessages([
                                'regex' => __('validation.display_name_format'),
                                'min_length' => __('validation.display_name_min'),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament.fields.description'))
                            ->placeholder(__('filament.placeholders.role_description'))
                            ->rows(3)
                            ->maxLength(500)
                            ->minLength(10)
                            ->validationMessages([
                                'min_length' => __('validation.description_min'),
                                'max_length' => __('validation.description_max'),
                            ]),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.fields.active'))
                            ->default(true)
                            ->helperText(__('filament.helpers.inactive_roles')),
                    ])->columns(2),
                    
                Forms\Components\Section::make(__('filament.fields.permissions'))
                    ->schema([
                        Forms\Components\View::make('filament.bulk-actions')
                            ->view('filament.components.permission-bulk-actions'),
                        Forms\Components\CheckboxList::make('permissions')
                            ->label(__('filament.fields.select_permissions'))
                            ->relationship('permissions', 'display_name')
                            ->options(function () {
                                return Permission::where('is_active', true)
                                    ->get()
                                    ->pluck('display_name', 'id')
                                    ->toArray();
                            })
                            ->columns(3)
                            ->gridDirection('row')
                            ->helperText(__('filament.helpers.select_permissions_help'))
                            ->required()
                            ->minItems(1)
                            ->validationMessages([
                                'required' => __('validation.permissions_required'),
                                'min_items' => __('validation.permissions_min'),
                            ])
                            ->extraAttributes(['id' => 'permissions-checkbox-list']),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label(__('filament.fields.role_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.fields.system_name'))
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament.fields.description'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label(__('filament.fields.permissions'))
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.fields.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->defaultSort('display_name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All roles')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function () {
                        Cache::flush();
                        Notification::make()
                            ->title(__('validation.role_updated'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_deleted' => true]);
                        Notification::make()
                            ->title(__('validation.role_deleted'))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete')
                        ->label('Delete')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_deleted' => true])),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
