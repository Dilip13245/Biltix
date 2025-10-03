<?php

namespace App\Filament\Resources\PhotoResource\Pages;

use App\Filament\Resources\PhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPhoto extends ViewRecord
{
    protected static string $resource = PhotoResource::class;

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