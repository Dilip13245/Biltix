<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HelpSupportResource\Pages;
use App\Models\HelpSupport;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class HelpSupportResource extends Resource
{
    protected static ?string $model = HelpSupport::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    
    public static function canViewAny(): bool
    {
        return true; // Allow all admins to view
    }
    
    public static function canCreate(): bool
    {
        return true; // Allow all admins to create
    }
    
    public static function canEdit($record): bool
    {
        return true; // Allow all admins to edit
    }
    
    public static function canDelete($record): bool
    {
        return true; // Allow all admins to delete
    }
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.help_support');
    }
    
    public static function getModelLabel(): string
    {
        return __('filament.fields.help_support');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('filament.fields.help_support');
    }
    
    protected static ?string $breadcrumb = null;
    
    public static function getBreadcrumb(): string
    {
        return __('filament.navigation.help_support');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.support');
    }
    
    protected static ?int $navigationSort = 100;
    
    protected static ?string $navigationGroup = 'Support';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.fields.ticket_information'))
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(__('filament.fields.user'))
                            ->options(User::where('is_active', true)->pluck('full_name', 'id'))
                            ->searchable()
                            ->nullable(),
                        Forms\Components\TextInput::make('full_name')
                            ->label(__('filament.fields.customer_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->label(__('filament.fields.message'))
                            ->required()
                            ->rows(4),
                        Forms\Components\Select::make('status')
                            ->label(__('filament.fields.status'))
                            ->options([
                                'pending' => __('filament.options.pending'),
                                'in_progress' => __('filament.options.in_progress'),
                                'resolved' => __('filament.options.resolved'),
                                'closed' => __('filament.options.closed'),
                            ])
                            ->required()
                            ->default('pending'),
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
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.fields.ticket_id'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('filament.fields.customer_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament.fields.email'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('message')
                    ->label(__('filament.fields.message'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->formatStateUsing(fn (string $state): string => __("filament.options.{$state}"))
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'in_progress', 
                        'success' => 'resolved',
                        'gray' => 'closed',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.fields.status'))
                    ->options([
                        'pending' => __('filament.options.pending'),
                        'in_progress' => __('filament.options.in_progress'),
                        'resolved' => __('filament.options.resolved'),
                        'closed' => __('filament.options.closed'),
                    ])
                    ->placeholder(__('filament.options.all')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->placeholder(__('filament.options.all'))
                    ->trueLabel(__('filament.options.active_only'))
                    ->falseLabel(__('filament.options.inactive_only')),
            ])
            ->emptyStateHeading(__('filament.messages.no_tickets'))
            ->emptyStateDescription(__('filament.messages.no_tickets_description'))
            ->emptyStateIcon('heroicon-o-question-mark-circle')
            ->actions([
                Tables\Actions\Action::make('update_status')
                    ->icon('heroicon-o-arrow-path')
                    ->tooltip(__('filament.actions.update_status'))
                    ->label('')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label(__('filament.fields.status'))
                            ->options([
                                'pending' => __('filament.options.pending'),
                                'in_progress' => __('filament.options.in_progress'),
                                'resolved' => __('filament.options.resolved'),
                                'closed' => __('filament.options.closed'),
                            ])
                            ->required()
                    ])
                    ->action(function (array $data, $record): void {
                        $record->update(['status' => $data['status']]);
                        Notification::make()
                            ->title(__('validation.status_updated'))
                            ->success()
                            ->send();
                    })
                    ->fillForm(fn ($record): array => [
                        'status' => $record->status,
                    ])
                    ->modalHeading(__('filament.actions.update_status'))
                    ->modalSubmitActionLabel(__('filament.actions.update_status'))
                    ->modalCancelActionLabel(__('filament.actions.cancel')),
                Tables\Actions\Action::make('view_details')
                    ->icon('heroicon-o-eye')
                    ->tooltip(__('filament.actions.view_details'))
                    ->label('')
                    ->modalHeading(__('filament.actions.ticket_details'))
                    ->modalContent(fn ($record) => view('filament.modals.help-support-details', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('filament.actions.close')),
            ])
            ->actionsColumnLabel(__('filament.fields.actions'))
            ->recordAction('view_details')
            ->recordUrl(null)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHelpSupports::route('/'),
            'create' => Pages\CreateHelpSupport::route('/create'),
            'view' => Pages\ViewHelpSupport::route('/{record}'),
            'edit' => Pages\EditHelpSupport::route('/{record}/edit'),
        ];
    }
}