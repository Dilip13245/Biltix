<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\SubscriptionPlan;
use App\Models\PlanFeature;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.subscription_plans') ?? 'Subscription Plans';
    }
    
    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.subscriptions') ?? 'Subscriptions';
    }
    
    protected static ?int $navigationSort = 1;

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
        return $form
            ->schema([
                Forms\Components\Section::make('Plan Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Plan Name (System)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->regex('/^[a-z_]+$/')
                            ->placeholder('e.g., basic, pro, enterprise')
                            ->helperText('Lowercase with underscores only'),
                        Forms\Components\TextInput::make('display_name')
                            ->label('Display Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Basic Plan, Pro Plan'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Brief description of this plan'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_default')
                            ->label('Default Plan')
                            ->helperText('Set as default for new users')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('USD')
                            ->placeholder('300.00'),
                        Forms\Components\Select::make('billing_period')
                            ->label('Billing Period')
                            ->options([
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly',
                            ])
                            ->default('yearly')
                            ->required(),
                        Forms\Components\TextInput::make('currency')
                            ->label('Currency')
                            ->default('USD')
                            ->maxLength(10),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])->columns(4),

                Forms\Components\Section::make('Limits')
                    ->schema([
                        Forms\Components\TextInput::make('max_projects')
                            ->label('Max Projects')
                            ->numeric()
                            ->nullable()
                            ->placeholder('Leave empty for unlimited'),
                        Forms\Components\TextInput::make('max_team_members')
                            ->label('Max Team Members')
                            ->numeric()
                            ->nullable()
                            ->placeholder('Leave empty for unlimited'),
                    ])->columns(2),

                Forms\Components\Section::make('Modules Access')
                    ->description('Enable/disable access to modules for this plan (modules from Role Permissions)')
                    ->schema([
                        Forms\Components\CheckboxList::make('enabled_modules')
                            ->label('')
                            ->options(function () {
                                // Get modules from existing permissions
                                $modules = Permission::select('module')
                                    ->distinct()
                                    ->whereNotNull('module')
                                    ->pluck('module')
                                    ->mapWithKeys(function ($module) {
                                        // Format: 'projects' => 'Projects'
                                        return [$module => ucfirst(str_replace('_', ' ', $module))];
                                    })
                                    ->toArray();
                                
                                return $modules;
                            })
                            ->columns(4)
                            ->bulkToggleable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('System Name')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('billing_period')
                    ->label('Period')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'monthly' => 'info',
                        'yearly' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('features_count')
                    ->label('Features')
                    ->counts('features')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('subscriptions_count')
                    ->label('Subscribers')
                    ->counts('subscriptions')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function () {
                        Cache::flush();
                        Notification::make()
                            ->title('Plan updated and cache cleared')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        if ($record->subscriptions()->exists()) {
                            Notification::make()
                                ->title('Cannot delete plan with active subscriptions')
                                ->danger()
                                ->send();
                            return false;
                        }
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }

    /**
     * Get module options from existing permissions
     */
    private static function getModuleOptions(): array
    {
        $existingModules = Permission::distinct()->pluck('module', 'module')->toArray();
        
        $defaultModules = [
            'auth' => 'Auth',
            'dashboard' => 'Dashboard',
            'profile' => 'Profile',
            'projects' => 'Projects',
            'phases' => 'Phases',
            'plans' => 'Plans',
            'tasks' => 'Tasks',
            'snags' => 'Snags',
            'inspections' => 'Inspections',
            'daily_logs' => 'Daily Logs',
            'files' => 'Files',
            'gallery' => 'Gallery',
            'team' => 'Team',
            'notifications' => 'Notifications',
            'reports' => 'Reports',
            'help_support' => 'Help & Support',
            'timeline' => 'Timeline',
            'milestones' => 'Milestones',
            'chat' => 'Chat',
            'gantt' => 'Gantt Chart',
            'meetings' => 'Meetings',
            'location' => 'Location',
            'progress' => 'Progress',
        ];

        return array_merge($defaultModules, $existingModules);
    }

    /**
     * Get action options
     */
    private static function getActionOptions(): array
    {
        $existingActions = Permission::distinct()->pluck('action', 'action')->toArray();
        
        $defaultActions = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'upload' => 'Upload',
            'download' => 'Download',
            'assign' => 'Assign',
            'approve' => 'Approve',
            'resolve' => 'Resolve',
            'complete' => 'Complete',
            'markup' => 'Markup',
            'access' => 'Access',
            'send' => 'Send',
            'add_member' => 'Add Member',
            'edit_member' => 'Edit Member',
            'delete_member' => 'Delete Member',
            'library' => 'Library',
            'generate' => 'Generate',
            'register' => 'Register',
            'login' => 'Login',
            'password_reset' => 'Password Reset',
            'archive' => 'Archive',
        ];

        return array_merge($defaultActions, $existingActions);
    }
}
