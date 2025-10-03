<?php

namespace App\Filament\Resources\ProjectSafetyItemResource\Pages;

use App\Filament\Resources\ProjectSafetyItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectSafetyItems extends ListRecords
{
    protected static string $resource = ProjectSafetyItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
