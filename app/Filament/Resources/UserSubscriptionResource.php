<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSubscriptionResource\Pages;
use App\Models\UserSubscription;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class UserSubscriptionResource extends Resource
{
    protected static ?string $model = UserSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.user_subscriptions') ?? 'User Subscriptions';
    }
    
    protected static ?string $navigationGroup = null;
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.subscriptions') ?? 'Subscriptions';
    }
    
    protected static ?int $navigationSort = 2;

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
                Forms\Components\Section::make('Subscription Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Company/User')
                            ->options(
                                User::where('is_sub_user', false)
                                    ->where('is_active', true)
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('plan_id')
                            ->label('Subscription Plan')
                            ->options(SubscriptionPlan::active()->pluck('display_name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Start Date')
                            ->required()
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expiry Date')
                            ->required()
                            ->default(now()->addYear()),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                                'pending' => 'Pending',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->maxLength(255),
                        Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'moyasar' => 'Moyasar',
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                                'bank_transfer' => 'Bank Transfer',
                                'manual' => 'Manual',
                            ]),
                        Forms\Components\TextInput::make('amount_paid')
                            ->label('Amount Paid')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\Toggle::make('auto_renew')
                            ->label('Auto Renew')
                            ->default(false),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('companyUser.name')
                    ->label('Company/User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('companyUser.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('plan.display_name')
                    ->label('Plan')
                    ->badge()
                    ->color(fn ($record): string => match ($record->plan?->name) {
                        'pro' => 'success',
                        'basic' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        'pending' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Started')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->color(fn ($record): string => 
                        $record->isExpiringSoon() ? 'warning' : 
                        ($record->isExpired() ? 'danger' : 'success')
                    ),
                Tables\Columns\TextColumn::make('daysRemaining')
                    ->label('Days Left')
                    ->state(fn ($record): string => $record->daysRemaining() . ' days')
                    ->color(fn ($record): string => 
                        $record->daysRemaining() <= 7 ? 'danger' : 
                        ($record->daysRemaining() <= 30 ? 'warning' : 'success')
                    ),
                Tables\Columns\IconColumn::make('auto_renew')
                    ->label('Auto Renew')
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('expires_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                        'pending' => 'Pending',
                    ]),
                Tables\Filters\SelectFilter::make('plan_id')
                    ->label('Plan')
                    ->options(SubscriptionPlan::pluck('display_name', 'id')),
                Tables\Filters\Filter::make('expiring_soon')
                    ->label('Expiring Soon (7 days)')
                    ->query(fn ($query) => $query
                        ->where('status', 'active')
                        ->where('expires_at', '<=', now()->addDays(7))
                        ->where('expires_at', '>', now())
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('extend')
                    ->label('Extend')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('days')
                            ->label('Extend by (days)')
                            ->numeric()
                            ->default(365)
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->extend($data['days']);
                        Cache::flush();
                        Notification::make()
                            ->title("Extended subscription by {$data['days']} days")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->cancel();
                        Cache::flush();
                        Notification::make()
                            ->title('Subscription cancelled')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'active'),
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
            'index' => Pages\ListUserSubscriptions::route('/'),
            'create' => Pages\CreateUserSubscription::route('/create'),
            'edit' => Pages\EditUserSubscription::route('/{record}/edit'),
        ];
    }
}
