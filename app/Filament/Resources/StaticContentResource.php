<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaticContentResource\Pages;
use App\Models\StaticContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StaticContentResource extends Resource
{
    protected static ?string $model = StaticContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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
        return StaticContent::count() < 4;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        return true;
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.static_content');
    }

    public static function getModelLabel(): string
    {
        return __('filament.fields.static_content');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.fields.static_content');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.settings');
    }

    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.fields.content_information'))
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label(__('filament.fields.type'))
                            ->options([
                                'privacy' => __('filament.options.privacy_policy'),
                                'terms' => __('filament.options.terms_conditions'),
                            ])
                            ->required()
                            ->disabled(fn ($record) => $record !== null),
                        Forms\Components\Select::make('language')
                            ->label(__('filament.fields.language'))
                            ->options([
                                'en' => 'English',
                                'ar' => 'العربية',
                            ])
                            ->required()
                            ->disabled(fn ($record) => $record !== null),
                        Forms\Components\TextInput::make('title')
                            ->label(__('filament.fields.title'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                            ->label(__('filament.fields.content'))
                            ->required()
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.fields.type'))
                    ->formatStateUsing(fn (string $state): string => __(
                        $state === 'privacy' ? 'filament.options.privacy_policy' : 'filament.options.terms_conditions'
                    ))
                    ->sortable(),
                Tables\Columns\TextColumn::make('language')
                    ->label(__('filament.fields.language'))
                    ->formatStateUsing(fn (string $state): string => $state === 'en' ? 'English' : 'العربية')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label(__('filament.fields.content'))
                    ->limit(100)
                    ->html(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('filament.fields.type'))
                    ->options([
                        'privacy' => __('filament.options.privacy_policy'),
                        'terms' => __('filament.options.terms_conditions'),
                    ]),
                Tables\Filters\SelectFilter::make('language')
                    ->label(__('filament.fields.language'))
                    ->options([
                        'en' => 'English',
                        'ar' => 'العربية',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.fields.active'))
                    ->placeholder(__('filament.options.all'))
                    ->trueLabel(__('filament.options.active_only'))
                    ->falseLabel(__('filament.options.inactive_only')),
            ])
            ->emptyStateHeading(__('filament.messages.no_content'))
            ->emptyStateDescription(__('filament.messages.no_content_description'))
            ->emptyStateIcon('heroicon-o-document-text')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete')
                        ->label(__('filament.actions.delete'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_deleted' => true])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaticContents::route('/'),
            'create' => Pages\CreateStaticContent::route('/create'),
            'edit' => Pages\EditStaticContent::route('/{record}/edit'),
        ];
    }
}
