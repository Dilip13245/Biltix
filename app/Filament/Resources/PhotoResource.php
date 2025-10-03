<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoResource\Pages;
use App\Models\Photo;
use App\Models\Project;
use App\Models\ProjectPhase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class PhotoResource extends Resource
{
    protected static ?string $model = Photo::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Project Management';

    protected static ?int $navigationSort = 8;

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.photos');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.photo');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.photos');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.photo_information'))
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label(__('filament.fields.project'))
                            ->options(Project::active()->pluck('project_title', 'id'))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('phase_id', null)),





                        Forms\Components\FileUpload::make('file_path')
                            ->label(__('filament.fields.photo'))
                            ->directory('photos')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(5120)
                            ->deletable(true)
                            ->downloadable(true)
                            ->previewable(true)
                            ->openable(true)
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label(__('filament.fields.description'))
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('taken_at')
                            ->label(__('filament.fields.taken_at'))
                            ->default(now())
                            ->required(),





                        Forms\Components\Hidden::make('taken_by')
                            ->default(auth()->id()),

                        Forms\Components\Hidden::make('is_active')
                            ->default(true),

                        Forms\Components\Hidden::make('is_deleted')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label(__('filament.fields.photo'))
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->file_path))
                    ->size(60)
                    ->square(),



                Tables\Columns\TextColumn::make('project.project_title')
                    ->label(__('filament.fields.project'))
                    ->searchable()
                    ->sortable(),





                Tables\Columns\TextColumn::make('file_size')
                    ->label(__('filament.fields.file_size'))
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 2) . ' KB')
                    ->sortable(),

                Tables\Columns\TextColumn::make('taken_at')
                    ->label(__('filament.fields.taken_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('uploader.name')
                    ->label(__('filament.fields.taken_by'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label(__('filament.fields.project'))
                    ->options(Project::active()->pluck('project_title', 'id'))
                    ->searchable(),

                Tables\Filters\SelectFilter::make('phase_id')
                    ->label(__('filament.fields.phase'))
                    ->options(ProjectPhase::active()->pluck('title', 'id'))
                    ->searchable(),

                Tables\Filters\Filter::make('taken_at')
                    ->label(__('filament.fields.taken_date'))
                    ->form([
                        Forms\Components\DatePicker::make('taken_from')
                            ->label(__('filament.fields.from')),
                        Forms\Components\DatePicker::make('taken_until')
                            ->label(__('filament.fields.until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['taken_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('taken_at', '>=', $date),
                            )
                            ->when(
                                $data['taken_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('taken_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('download')
                    ->label(__('filament.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Photo $record): string => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        $record->update(['is_deleted' => true]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_deleted' => true]);
                            });
                        }),
                ]),
            ])
            ->defaultSort('taken_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.photo_information'))
                    ->schema([
                        Infolists\Components\ImageEntry::make('file_path')
                            ->label(__('filament.fields.photo'))
                            ->getStateUsing(fn ($record) => asset('storage/' . $record->file_path))
                            ->columnSpanFull(),



                        Infolists\Components\TextEntry::make('project.project_title')
                            ->label(__('filament.fields.project')),





                        Infolists\Components\TextEntry::make('file_size')
                            ->label(__('filament.fields.file_size'))
                            ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 2) . ' KB'),

                        Infolists\Components\TextEntry::make('taken_at')
                            ->label(__('filament.fields.taken_at'))
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('uploader.name')
                            ->label(__('filament.fields.taken_by')),

                        // Infolists\Components\TextEntry::make('description')
                        //     ->label(__('filament.fields.description'))
                        //     ->columnSpanFull()
                        //     ->placeholder(__('filament.placeholders.no_description')),


                    ])
                    ->columns(2),
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
            'index' => Pages\ListPhotos::route('/'),
            'create' => Pages\CreatePhoto::route('/create'),
            'view' => Pages\ViewPhoto::route('/{record}'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_deleted', false);
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
        return false;
    }

    public static function canDelete($record): bool
    {
        return true;
    }
}