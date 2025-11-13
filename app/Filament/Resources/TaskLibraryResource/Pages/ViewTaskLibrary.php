<?php

namespace App\Filament\Resources\TaskLibraryResource\Pages;

use App\Filament\Resources\TaskLibraryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewTaskLibrary extends ViewRecord
{
    protected static string $resource = TaskLibraryResource::class;

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
                Section::make('Task Library Information')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Task Title')
                            ->weight('bold'),
                        TextEntry::make('descriptions.description')
                            ->label('Descriptions')
                            ->listWithLineBreaks()
                            ->bulleted(),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                    ]),
            ]);
    }
}

