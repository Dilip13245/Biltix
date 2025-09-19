<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'System Users';
    protected static ?int $navigationSort = 3;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.users');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.system_users');
    }
    
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
        return $form->schema([
            Forms\Components\Section::make(__('filament.fields.user_information'))->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament.fields.full_name'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(100)
                    ->regex('/^[a-zA-Z\s\-\'\.\.]+$/')
                    ->placeholder('John Doe'),
                Forms\Components\TextInput::make('email')
                    ->label(__('filament.fields.email_address'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('john@company.com'),
                Forms\Components\TextInput::make('phone')
                    ->label(__('filament.fields.phone_number'))
                    ->tel()
                    ->required()
                    ->regex('/^[\+]?[0-9]\d{9,14}$/')
                    ->maxLength(20)
                    ->placeholder('966501234567')
                    ->extraInputAttributes([
                        'dir' => 'ltr',
                        'style' => app()->getLocale() === 'ar' ? 'text-align: right; direction: ltr;' : ''
                    ]),
                Forms\Components\Select::make('role')
                    ->label(__('filament.fields.role'))
                    ->required()
                    ->options([
                        'contractor' => 'Contractor',
                        'consultant' => 'Consultant', 
                        'project_manager' => 'Project Manager',
                        'site_engineer' => 'Site Engineer',
                        'stakeholder' => 'Stakeholder'
                    ])
                    ->native(false)
                    ->placeholder('Select a role'),
            ])->columns(2),
            
            Forms\Components\Section::make(__('filament.fields.company_details'))->schema([
                Forms\Components\TextInput::make('company_name')
                    ->label(__('filament.fields.company_name'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(200)
                    ->regex('/^[a-zA-Z0-9\s\-\&\.\_\,\(\)\']+$/')
                    ->placeholder('ABC Construction Ltd'),
                Forms\Components\TextInput::make('designation')
                    ->label(__('filament.fields.designation'))
                    ->maxLength(255)
                    ->placeholder('Senior Engineer'),
                Forms\Components\TextInput::make('employee_count')
                    ->label(__('filament.fields.employee_count'))
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(50000)
                    ->placeholder('50'),
            ])->columns(2),
            
            Forms\Components\Section::make(__('filament.fields.account_settings'))->schema([
                Forms\Components\TextInput::make('password')
                    ->label(__('filament.fields.password'))
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/')
                    ->placeholder('••••••••')
                    ->helperText(__('filament.helpers.password_requirements')),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->default(true)
                    ->helperText(__('filament.helpers.inactive_users')),
            ])->columns(2),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label(__('filament.fields.role'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'contractor' => 'success',
                        'consultant' => 'info',
                        'project_manager' => 'warning',
                        'site_engineer' => 'primary',
                        'stakeholder' => 'gray',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('company_name')
                    ->label(__('filament.fields.company_name'))
                    ->searchable()
                    ->toggleable(),
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
                    ->toggleable()
                    ->placeholder(__('filament.fields.never')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label(__('filament.fields.role'))
                    ->options([
                        'contractor' => 'Contractor',
                        'consultant' => 'Consultant',
                        'project_manager' => 'Project Manager', 
                        'site_engineer' => 'Site Engineer',
                        'stakeholder' => 'Stakeholder'
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.fields.status'))
                    ->placeholder(__('filament.options.all_users'))
                    ->trueLabel(__('filament.options.active_only'))
                    ->falseLabel(__('filament.options.inactive_only')),
            ])
            ->actions([
                Tables\Actions\Action::make('resetPassword')
                    ->label(__('filament.actions.reset_password'))
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament.fields.new_password'))
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/')
                            ->helperText(__('filament.helpers.password_requirements')),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update(['password' => Hash::make($data['password'])]);
                        Notification::make()
                            ->title(__('filament.messages.password_reset_success'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('toggleStatus')
                    ->icon(fn (User $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (User $record): string => $record->is_active ? 'danger' : 'success')
                    ->label(fn (User $record): string => $record->is_active ? __('filament.actions.deactivate') : __('filament.actions.activate'))
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->update(['is_active' => !$record->is_active]);
                        Notification::make()
                            ->title(__('filament.messages.user_status_updated'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('filament.actions.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => true]);
                            Notification::make()
                                ->title(__('filament.messages.users_activated'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('filament.actions.deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => false]);
                            Notification::make()
                                ->title(__('filament.messages.users_deactivated'))
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}