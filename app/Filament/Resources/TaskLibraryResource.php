<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskLibraryResource\Pages;
use App\Models\TaskLibrary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskLibraryResource extends Resource
{
    protected static ?string $model = TaskLibrary::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 10;
    
    public static function getNavigationLabel(): string
    {
        return 'Task Library';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.project_management');
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Task Library Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Task Title')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Enter task title'),
                    
                    Forms\Components\Repeater::make('descriptions')
                        ->label('Descriptions')
                        ->relationship('descriptions')
                        ->schema([
                            Forms\Components\Textarea::make('description')
                                ->label('Description')
                                ->required()
                                ->maxLength(500)
                                ->rows(3)
                                ->placeholder('Enter task description (max 500 characters)')
                                ->helperText('Maximum 500 characters allowed. This matches the task description field limit.')
                                ->columnSpanFull(),
                            
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Sort Order')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                            
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ])
                        ->defaultItems(1)
                        ->minItems(1)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['description'] ?? 'New Description')
                        ->addActionLabel('Add Description')
                        ->reorderableWithButtons()
                        ->orderColumn('sort_order'),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScope('active'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Task Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('descriptions_count')
                    ->label('Descriptions Count')
                    ->counts('descriptions')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Tasks')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListTaskLibraries::route('/'),
            'create' => Pages\CreateTaskLibrary::route('/create'),
            'view' => Pages\ViewTaskLibrary::route('/{record}'),
            'edit' => Pages\EditTaskLibrary::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_deleted', false);
    }
}

