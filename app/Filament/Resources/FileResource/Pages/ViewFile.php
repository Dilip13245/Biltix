<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFile extends ViewRecord
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('download')
                ->label(__('filament.actions.download'))
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (): string => asset('storage/' . $this->record->file_path))
                ->openUrlInNewTab(),
        ];
    }
}