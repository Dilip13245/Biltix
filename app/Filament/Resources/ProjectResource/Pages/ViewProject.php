<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.fields.project_information'))
                    ->schema([
                        Infolists\Components\TextEntry::make('project_code')
                            ->label(__('filament.fields.project_code'))
                            ->badge()
                            ->color('gray'),
                        Infolists\Components\TextEntry::make('project_title')
                            ->label(__('filament.fields.project_title')),
                        Infolists\Components\TextEntry::make('contractor_name')
                            ->label(__('filament.fields.contractor_name')),
                        Infolists\Components\TextEntry::make('type')
                            ->label(__('filament.fields.project_type'))
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                        Infolists\Components\TextEntry::make('project_location')
                            ->label(__('filament.fields.project_location'))
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Infolists\Components\Section::make(__('filament.fields.project_timeline'))
                    ->schema([
                        Infolists\Components\TextEntry::make('project_start_date')
                            ->label(__('filament.fields.start_date'))
                            ->date(),
                        Infolists\Components\TextEntry::make('project_due_date')
                            ->label(__('filament.fields.due_date'))
                            ->date(),
                        Infolists\Components\TextEntry::make('status')
                            ->label(__('filament.fields.status'))
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),

                    ])->columns(2),
                    
                Infolists\Components\Section::make(__('filament.fields.team_information'))
                    ->schema([
                        Infolists\Components\TextEntry::make('projectManager.name')
                            ->label(__('filament.fields.project_manager')),
                        Infolists\Components\TextEntry::make('technicalEngineer.name')
                            ->label(__('filament.fields.technical_engineer')),
                        Infolists\Components\TextEntry::make('creator.name')
                            ->label(__('filament.fields.created_by')),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label(__('filament.fields.active'))
                            ->boolean(),
                    ])->columns(2),
            ]);
    }
}