<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.admins');
    }
    
    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.admin_management');
    }
    
    protected static ?int $navigationSort = 3;
    
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
        return ['name', 'email', 'phone'];
    }
    
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email,
            'Phone' => $record->phone,
            'Status' => $record->is_active ? 'Active' : 'Inactive',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.fields.admin_information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.fields.full_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament.fields.email_address'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('filament.fields.phone_number'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament.fields.password'))
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->maxLength(255)
                            ->helperText(fn (string $context): string => $context === 'edit' ? __('filament.helpers.leave_blank_password') : ''),

                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.fields.active'))
                            ->default(true)
                            ->helperText(__('filament.helpers.inactive_admins')),
                    ])->columns(2),
                    
                Forms\Components\Section::make(__('filament.fields.role_assignment'))
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->label(__('filament.fields.assign_admin_roles'))
                            ->relationship('roles', 'display_name')
                            ->options(function () {
                                return Role::where('is_active', true)
                                    ->whereIn('name', ['super_admin', 'admin', 'moderator'])
                                    ->get()
                                    ->mapWithKeys(function ($role) {
                                        $descriptions = [
                                            'super_admin' => 'Full system access',
                                            'admin' => 'Standard admin permissions', 
                                            'moderator' => 'Limited content management'
                                        ];
                                        return [$role->id => $role->display_name . ' - ' . $descriptions[$role->name]];
                                    });
                            })
                            ->columns(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament.fields.email'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('roles.display_name')
                    ->label(__('filament.fields.roles'))
                    ->badge()
                    ->color('success')
                    ->separator(', '),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('filament.fields.phone'))
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.fields.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('last_login_at')
                    ->label(__('filament.fields.last_login'))
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->placeholder(__('filament.fields.never')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All admins')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_deleted' => true])),
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
