<?php

namespace App\Filament\Resources\ProjectActivityResource\Pages;

use App\Filament\Resources\ProjectActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectActivities extends ListRecords
{
    protected static string $resource = ProjectActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
