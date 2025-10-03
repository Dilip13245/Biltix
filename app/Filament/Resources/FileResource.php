<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Models\File;
use App\Models\FileCategory;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Project Management';

    protected static ?int $navigationSort = 7;

    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.files');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.file');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.files');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.file_information'))
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label(__('filament.fields.project'))
                            ->options(Project::active()->pluck('project_title', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('category_id')
                            ->label(__('filament.fields.category'))
                            ->options(FileCategory::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('original_name')
                            ->label(__('filament.fields.file_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('file_path')
                            ->label(__('filament.fields.file'))
                            ->directory('project_files')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/png', 'text/plain'])
                            ->maxSize(10240)
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label(__('filament.fields.description'))
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_public')
                            ->label(__('filament.fields.is_public'))
                            ->default(false),

                        Forms\Components\Hidden::make('uploaded_by')
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
                Tables\Columns\TextColumn::make('original_name')
                    ->label(__('filament.fields.file_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.project_title')
                    ->label(__('filament.fields.project'))
                    ->searchable()
                    ->sortable(),



                Tables\Columns\TextColumn::make('file_type')
                    ->label(__('filament.fields.file_type'))
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        $extension = strtolower(pathinfo($state, PATHINFO_EXTENSION));
                        if (empty($extension)) {
                            // Try to get extension from mime type
                            $mimeMap = [
                                'application/pdf' => 'PDF',
                                'application/msword' => 'DOC',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'DOCX',
                                'application/vnd.ms-excel' => 'XLS',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'XLSX',
                                'image/jpeg' => 'JPG',
                                'image/png' => 'PNG',
                                'image/gif' => 'GIF',
                                'text/plain' => 'TXT',
                                'application/dwg' => 'DWG',
                            ];
                            return $mimeMap[$state] ?? strtoupper(substr($state, strrpos($state, '/') + 1));
                        }
                        return strtoupper($extension);
                    })
                    ->color(fn (string $state): string => match (true) {
                        str_contains(strtolower($state), 'pdf') => 'danger',
                        str_contains(strtolower($state), 'doc') || str_contains(strtolower($state), 'word') => 'info',
                        str_contains(strtolower($state), 'excel') || str_contains(strtolower($state), 'sheet') => 'success',
                        str_contains(strtolower($state), 'image') || in_array(strtolower(pathinfo($state, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']) => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('file_size')
                    ->label(__('filament.fields.file_size'))
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 2) . ' KB')
                    ->sortable(),



                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.fields.upload_date'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label(__('filament.fields.project'))
                    ->options(Project::active()->pluck('project_title', 'id'))
                    ->searchable(),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('filament.fields.category'))
                    ->options(FileCategory::active()->pluck('name', 'id'))
                    ->searchable(),

                Tables\Filters\Filter::make('file_type')
                    ->label(__('filament.fields.file_type'))
                    ->form([
                        Forms\Components\Select::make('file_type')
                            ->options([
                                'pdf' => 'PDF',
                                'doc' => 'DOC/DOCX',
                                'xls' => 'XLS/XLSX',
                                'jpg' => 'Images',
                                'txt' => 'Text',
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['file_type'],
                            fn (Builder $query, $type): Builder => $query->where('file_type', 'like', "%{$type}%")
                        );
                    }),

                Tables\Filters\TernaryFilter::make('is_public')
                    ->label(__('filament.fields.is_public')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label(__('filament.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (File $record): string => asset('storage/' . $record->file_path))
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
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.file_information'))
                    ->schema([
                        Infolists\Components\TextEntry::make('original_name')
                            ->label(__('filament.fields.file_name')),

                        Infolists\Components\TextEntry::make('project.project_title')
                            ->label(__('filament.fields.project')),

                        Infolists\Components\TextEntry::make('category.name')
                            ->label(__('filament.fields.category')),

                        Infolists\Components\TextEntry::make('file_type')
                            ->label(__('filament.fields.file_type'))
                            ->formatStateUsing(fn (string $state): string => strtoupper(pathinfo($state, PATHINFO_EXTENSION))),

                        Infolists\Components\TextEntry::make('file_size')
                            ->label(__('filament.fields.file_size'))
                            ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 2) . ' KB'),

                        Infolists\Components\IconEntry::make('is_public')
                            ->label(__('filament.fields.is_public'))
                            ->boolean(),

                        Infolists\Components\TextEntry::make('description')
                            ->label(__('filament.fields.description'))
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament.fields.upload_date'))
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('filament.sections.file_preview'))
                    ->schema([
                        Infolists\Components\TextEntry::make('file_path')
                            ->label(__('filament.fields.file_url'))
                            ->formatStateUsing(fn (string $state): string => asset('storage/' . $state))
                            ->url(fn (File $record): string => asset('storage/' . $record->file_path))
                            ->openUrlInNewTab(),
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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'view' => Pages\ViewFile::route('/{record}'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
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
        return true;
    }

    public static function canDelete($record): bool
    {
        return true;
    }
}