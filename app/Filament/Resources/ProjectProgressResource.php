<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectProgressResource\Pages;
use App\Models\ProjectProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectProgressResource extends Resource
{
    protected static ?string $model = ProjectProgress::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 6;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.project_progress');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return __('filament.navigation.project_management');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('project_title')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project_title')
                    ->label(__('filament.fields.project'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('activities_count')
                    ->label(__('filament.fields.activities'))
                    ->counts('activities'),
                Tables\Columns\TextColumn::make('manpower_equipment_count')
                    ->label(__('filament.fields.manpower_equipment'))
                    ->counts('manpowerEquipment'),
                Tables\Columns\TextColumn::make('safety_items_count')
                    ->label(__('filament.fields.safety_items'))
                    ->counts('safetyItems'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectProgresses::route('/'),
            'view' => Pages\ViewProjectProgress::route('/{record}'),
        ];
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
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}