<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use App\Models\User;
use App\Models\Project;
use App\Helpers\NotificationHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 5;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.notifications');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.system');
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
        return false; // Notifications are read-only after creation
    }
    
    public static function canDelete($record): bool
    {
        return true;
    }
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'message', 'type', 'user.name', 'user.email'];
    }
    
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'User' => $record->user?->name,
            'Type' => ucfirst(str_replace('_', ' ', $record->type)),
            'Priority' => ucfirst($record->priority),
            'Status' => $record->is_read ? 'Read' : 'Unread',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.fields.notification_details'))->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('filament.fields.title'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('filament.placeholders.notification_title')),
                Forms\Components\Textarea::make('message')
                    ->label(__('filament.fields.message'))
                    ->required()
                    ->rows(4)
                    ->maxLength(1000)
                    ->placeholder(__('filament.placeholders.notification_message')),
                Forms\Components\Select::make('type')
                    ->label(__('filament.fields.notification_type'))
                    ->required()
                    ->options([
                        'system' => __('filament.options.system'),
                        'announcement' => __('filament.options.announcement'),
                        'alert' => __('filament.options.alert'),
                        'reminder' => __('filament.options.reminder'),
                        'task_assigned' => __('filament.options.task_assigned'),
                        'snag_reported' => __('filament.options.snag_reported'),
                        'inspection_due' => __('filament.options.inspection_due'),
                        'project_updated' => __('filament.options.project_updated'),
                    ])
                    ->default('system')
                    ->native(false),
                Forms\Components\Select::make('priority')
                    ->label(__('filament.fields.priority'))
                    ->required()
                    ->options([
                        'low' => __('filament.options.low'),
                        'medium' => __('filament.options.medium'),
                        'high' => __('filament.options.high'),
                    ])
                    ->default('medium')
                    ->native(false),
            ])->columns(2),
            
            Forms\Components\Section::make(__('filament.fields.recipients'))->schema([
                Forms\Components\Radio::make('recipient_type')
                    ->label(__('filament.fields.send_to'))
                    ->options([
                        'single' => __('filament.options.single_user'),
                        'multiple' => __('filament.options.multiple_users'),
                        'role' => __('filament.options.by_role'),
                        'project' => __('filament.options.by_project'),
                        'all' => __('filament.options.all_users'),
                    ])
                    ->default('single')
                    ->reactive()
                    ->required(),
                    
                Forms\Components\Select::make('user_id')
                    ->label(__('filament.fields.user'))
                    ->options(function () {
                        return User::where('is_active', true)
                            ->where('is_deleted', false)
                            ->get()
                            ->mapWithKeys(fn ($user) => [$user->id => "{$user->name} ({$user->email})"]);
                    })
                    ->searchable()
                    ->visible(fn ($get) => $get('recipient_type') === 'single')
                    ->required(fn ($get) => $get('recipient_type') === 'single'),
                    
                Forms\Components\Select::make('user_ids')
                    ->label(__('filament.fields.users'))
                    ->options(function () {
                        return User::where('is_active', true)
                            ->where('is_deleted', false)
                            ->get()
                            ->mapWithKeys(fn ($user) => [$user->id => "{$user->name} ({$user->email})"]);
                    })
                    ->multiple()
                    ->searchable()
                    ->visible(fn ($get) => $get('recipient_type') === 'multiple')
                    ->required(fn ($get) => $get('recipient_type') === 'multiple'),
                    
                Forms\Components\Select::make('role')
                    ->label(__('filament.fields.role'))
                    ->options([
                        'contractor' => __('filament.roles.contractor'),
                        'consultant' => __('filament.roles.consultant'),
                        'project_manager' => __('filament.roles.project_manager'),
                        'site_engineer' => __('filament.roles.site_engineer'),
                        'stakeholder' => __('filament.roles.stakeholder'),
                    ])
                    ->visible(fn ($get) => $get('recipient_type') === 'role')
                    ->required(fn ($get) => $get('recipient_type') === 'role')
                    ->native(false),
                    
                Forms\Components\Select::make('project_id')
                    ->label(__('filament.fields.project'))
                    ->options(function () {
                        return Project::where('is_active', true)
                            ->where('is_deleted', false)
                            ->get()
                            ->mapWithKeys(fn ($project) => [$project->id => "{$project->project_title} ({$project->project_code})"]);
                    })
                    ->searchable()
                    ->visible(fn ($get) => $get('recipient_type') === 'project')
                    ->required(fn ($get) => $get('recipient_type') === 'project'),
                    
                Forms\Components\Placeholder::make('all_users_info')
                    ->label('')
                    ->content(__('filament.placeholders.notification_sent_to_all'))
                    ->visible(fn ($get) => $get('recipient_type') === 'all'),
            ])->columns(1),
            
            Forms\Components\Section::make(__('filament.fields.additional_settings'))
                ->visibleOn(['edit'])
                ->schema([
                Forms\Components\TextInput::make('action_url')
                    ->label(__('filament.fields.action_url'))
                    ->placeholder('/dashboard')
                    ->helperText(__('filament.placeholders.deep_link_help')),
                Forms\Components\Toggle::make('send_push')
                    ->label(__('filament.fields.send_push_notification'))
                    ->default(true)
                    ->helperText(__('filament.placeholders.send_push_help')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.fields.user'))
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->color(fn (string $state): string => match ($state) {
                        'system' => 'gray',
                        'announcement' => 'info',
                        'alert' => 'danger',
                        'reminder' => 'warning',
                        'task_assigned' => 'primary',
                        'snag_reported' => 'danger',
                        'inspection_due' => 'warning',
                        'project_updated' => 'success',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('message')
                    ->label(__('filament.fields.message'))
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('filament.fields.priority'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_read')
                    ->label(__('filament.fields.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.fields.sent_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('filament.fields.notification_type'))
                    ->options([
                        'system' => __('filament.options.system'),
                        'announcement' => __('filament.options.announcement'),
                        'alert' => __('filament.options.alert'),
                        'reminder' => __('filament.options.reminder'),
                        'task_assigned' => __('filament.options.task_assigned'),
                        'snag_reported' => __('filament.options.snag_reported'),
                        'inspection_due' => __('filament.options.inspection_due'),
                        'project_updated' => __('filament.options.project_updated'),
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('filament.fields.priority'))
                    ->options([
                        'low' => __('filament.options.low'),
                        'medium' => __('filament.options.medium'),
                        'high' => __('filament.options.high'),
                    ]),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label(__('filament.fields.read_status'))
                    ->placeholder(__('filament.options.all_notifications'))
                    ->trueLabel(__('filament.options.read_only'))
                    ->falseLabel(__('filament.options.unread_only')),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label(__('filament.fields.user'))
                    ->options(function () {
                        return User::where('is_active', true)
                            ->where('is_deleted', false)
                            ->get()
                            ->mapWithKeys(fn ($user) => [$user->id => $user->name]);
                    })
                    ->searchable(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('filament.fields.from_date')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('filament.fields.until_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actionsColumnLabel('Actions')
            ->actions([
                Tables\Actions\Action::make('mark_read')
                    ->label('')
                    ->tooltip(__('filament.actions.mark_as_read'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Notification $record): bool => !$record->is_read)
                    ->action(function (Notification $record): void {
                        $record->update([
                            'is_read' => true,
                            'read_at' => now(),
                        ]);
                        FilamentNotification::make()
                            ->title(__('filament.messages.marked_as_read'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('mark_unread')
                    ->label('')
                    ->tooltip(__('filament.actions.mark_as_unread'))
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->visible(fn (Notification $record): bool => $record->is_read)
                    ->action(function (Notification $record): void {
                        $record->update([
                            'is_read' => false,
                            'read_at' => null,
                        ]);
                        FilamentNotification::make()
                            ->title(__('filament.messages.marked_as_unread'))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()->label('')->tooltip(__('filament.actions.view')),
                Tables\Actions\Action::make('delete')
                    ->label('')
                    ->tooltip(__('filament.actions.delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_deleted' => true])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_read')
                        ->label(__('filament.actions.mark_as_read'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records): void {
                            $records->each->update([
                                'is_read' => true,
                                'read_at' => now(),
                            ]);
                            FilamentNotification::make()
                                ->title(__('filament.messages.notifications_marked_read'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('mark_unread')
                        ->label(__('filament.actions.mark_as_unread'))
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(function ($records): void {
                            $records->each->update([
                                'is_read' => false,
                                'read_at' => null,
                            ]);
                            FilamentNotification::make()
                                ->title(__('filament.messages.notifications_marked_unread'))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('delete')
                        ->label(__('filament.actions.delete'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_deleted' => true])),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('broadcast')
                    ->label(__('filament.actions.broadcast_notification'))
                    ->icon('heroicon-o-megaphone')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament.fields.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->label(__('filament.fields.message'))
                            ->required()
                            ->rows(4)
                            ->maxLength(1000),
                        Forms\Components\Select::make('type')
                            ->label(__('filament.fields.type'))
                            ->required()
                            ->options([
                                'system' => __('filament.options.system'),
                                'announcement' => __('filament.options.announcement'),
                                'alert' => __('filament.options.alert'),
                                'reminder' => __('filament.options.reminder'),
                            ])
                            ->default('announcement')
                            ->native(false),
                        Forms\Components\Select::make('priority')
                            ->label(__('filament.fields.priority'))
                            ->required()
                            ->options([
                                'low' => __('filament.options.low'),
                                'medium' => __('filament.options.medium'),
                                'high' => __('filament.options.high'),
                            ])
                            ->default('medium')
                            ->native(false),
                        Forms\Components\Radio::make('recipient_type')
                            ->label(__('filament.fields.send_to'))
                            ->options([
                                'all' => __('filament.options.all_users'),
                                'role' => __('filament.options.by_role'),
                                'project' => __('filament.options.by_project'),
                                'multiple' => __('filament.options.select_users'),
                            ])
                            ->default('all')
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('role')
                            ->label(__('filament.fields.role'))
                            ->options([
                                'contractor' => __('filament.roles.contractor'),
                                'consultant' => __('filament.roles.consultant'),
                                'project_manager' => __('filament.roles.project_manager'),
                                'site_engineer' => __('filament.roles.site_engineer'),
                                'stakeholder' => __('filament.roles.stakeholder'),
                            ])
                            ->visible(fn ($get) => $get('recipient_type') === 'role')
                            ->required(fn ($get) => $get('recipient_type') === 'role')
                            ->native(false),
                        Forms\Components\Select::make('project_id')
                            ->label(__('filament.fields.project'))
                            ->options(function () {
                                return Project::where('is_active', true)
                                    ->where('is_deleted', false)
                                    ->get()
                                    ->mapWithKeys(fn ($project) => [$project->id => "{$project->project_title} ({$project->project_code})"]);
                            })
                            ->searchable()
                            ->visible(fn ($get) => $get('recipient_type') === 'project')
                            ->required(fn ($get) => $get('recipient_type') === 'project'),
                        Forms\Components\Select::make('user_ids')
                            ->label(__('filament.fields.users'))
                            ->options(function () {
                                return User::where('is_active', true)
                                    ->where('is_deleted', false)
                                    ->get()
                                    ->mapWithKeys(fn ($user) => [$user->id => "{$user->name} ({$user->email})"]);
                            })
                            ->multiple()
                            ->searchable()
                            ->visible(fn ($get) => $get('recipient_type') === 'multiple')
                            ->required(fn ($get) => $get('recipient_type') === 'multiple'),
                    ])
                    ->action(function (array $data): void {
                        $userIds = [];
                        
                        // Determine recipients based on type
                        if ($data['recipient_type'] === 'all') {
                            $userIds = User::where('is_active', true)
                                ->where('is_deleted', false)
                                ->pluck('id')
                                ->toArray();
                        } elseif ($data['recipient_type'] === 'role') {
                            $userIds = User::where('role', $data['role'])
                                ->where('is_active', true)
                                ->where('is_deleted', false)
                                ->pluck('id')
                                ->toArray();
                        } elseif ($data['recipient_type'] === 'project') {
                            NotificationHelper::sendToProjectTeam(
                                $data['project_id'],
                                $data['type'],
                                $data['title'],
                                $data['message'],
                                ['action_url' => '/dashboard'],
                                $data['priority']
                            );
                            FilamentNotification::make()
                                ->title('Notification sent to project team')
                                ->success()
                                ->send();
                            return;
                        } elseif ($data['recipient_type'] === 'multiple') {
                            $userIds = $data['user_ids'];
                        }
                        
                        if (!empty($userIds)) {
                            NotificationHelper::send(
                                $userIds,
                                $data['type'],
                                $data['title'],
                                $data['message'],
                                ['action_url' => '/dashboard'],
                                $data['priority']
                            );
                            
                            FilamentNotification::make()
                                ->title('Notification sent to ' . count($userIds) . ' users')
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'view' => Pages\ViewNotification::route('/{record}'),
        ];
    }
}
