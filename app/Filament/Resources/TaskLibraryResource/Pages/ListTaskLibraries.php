<?php

namespace App\Filament\Resources\TaskLibraryResource\Pages;

use App\Filament\Resources\TaskLibraryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskLibraries extends ListRecords
{
    protected static string $resource = TaskLibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

