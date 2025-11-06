<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectionResource\Pages;
use App\Models\Inspection;
use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InspectionResource extends Resource
{
    protected static ?string $model = Inspection::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = 3;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.navigation.inspections');
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
            Forms\Components\Select::make('project_id')
                ->label(__('filament.fields.project'))
                ->options(Project::pluck('project_title', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\Select::make('phase_id')
                ->label(__('filament.fields.phase'))
                ->options(ProjectPhase::where('is_active', true)
                    ->where('is_deleted', false)
                    ->pluck('title', 'id'))
                ->searchable(),
            Forms\Components\Select::make('category')
                ->label(__('filament.fields.category'))
                ->options([
                    'structural' => __('filament.options.structural'),
                    'electrical' => __('filament.options.electrical'),
                    'plumbing' => __('filament.options.plumbing'),
                    'safety' => __('filament.options.safety'),
                    'quality' => __('filament.options.quality'),
                    'mechanical' => __('filament.options.mechanical'),
                    'finishing' => __('filament.options.finishing'),
                ])
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label(__('filament.fields.description'))
                ->required()
                ->rows(3),
            Forms\Components\Repeater::make('checklists')
                ->label(__('filament.fields.checklist_items'))
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('checklist_item')
                        ->label(__('filament.fields.checklist_item'))
                        ->required(),
                    Forms\Components\Toggle::make('is_checked')
                        ->label(__('filament.fields.checked'))
                        ->default(false),
                    Forms\Components\Hidden::make('is_active')
                        ->default(true),
                    Forms\Components\Hidden::make('is_deleted')
                        ->default(false),
                ])
                ->minItems(1)
                ->defaultItems(1)
                ->addActionLabel(__('filament.actions.add_checklist_item'))
                ->columnSpanFull(),
            Forms\Components\Repeater::make('images')
                ->label(__('filament.fields.images'))
                ->relationship()
                ->schema([
                    Forms\Components\FileUpload::make('image_path')
                        ->label(__('filament.fields.image'))
                        ->image()
                        ->directory('inspections/images')
                        ->visibility('public')
                        ->required(),
                    Forms\Components\TextInput::make('original_name')
                        ->label(__('filament.fields.description'))
                        ->placeholder(__('filament.placeholders.image_description')),
                ])
                ->addActionLabel(__('filament.actions.add_image'))
                ->columnSpanFull(),
            Forms\Components\Select::make('status')
                ->label(__('filament.fields.status'))
                ->options([
                    'todo' => __('filament.options.todo'),
                    'in_progress' => __('filament.options.in_progress'),
                    'completed' => __('filament.options.completed'),
                    'approved' => __('filament.options.approved'),
                ])
                ->default('todo')
                ->required(),
            Forms\Components\Hidden::make('created_by')
                ->default(auth()->id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.project_title')
                    ->label(__('filament.fields.project'))
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('phase.title')
                    ->label(__('filament.fields.phase'))
                    ->searchable()
                    ->default(__('filament.options.no_phase')),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.fields.category'))
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament.fields.description'))
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('checklists_count')
                    ->label(__('filament.fields.checklist_items'))
                    ->counts('checklists')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match($state) {
                        'todo' => __('filament.options.todo'),
                        'in_progress' => __('filament.options.in_progress'),
                        'completed' => __('filament.options.completed'),
                        'approved' => __('filament.options.approved'),
                        default => __('filament.options.pending')
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'todo' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'approved' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('filament.fields.created_by'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label(__('filament.fields.project'))
                    ->options(Project::pluck('project_title', 'id')),
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('filament.fields.category'))
                    ->options([
                        'structural' => __('filament.options.structural'),
                        'electrical' => __('filament.options.electrical'),
                        'plumbing' => __('filament.options.plumbing'),
                        'safety' => __('filament.options.safety'),
                        'quality' => __('filament.options.quality'),
                        'mechanical' => __('filament.options.mechanical'),
                        'finishing' => __('filament.options.finishing'),
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.fields.status'))
                    ->options([
                        'todo' => __('filament.options.todo'),
                        'in_progress' => __('filament.options.in_progress'),
                        'completed' => __('filament.options.completed'),
                        'approved' => __('filament.options.approved'),
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInspections::route('/'),
            'create' => Pages\CreateInspection::route('/create'),
            'view' => Pages\ViewInspection::route('/{record}'),
            'edit' => Pages\EditInspection::route('/{record}/edit'),
        ];
    }
}