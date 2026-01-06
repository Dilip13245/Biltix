<?php

namespace App\Filament\Resources\StaticContentResource\Pages;

use App\Filament\Resources\StaticContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaticContents extends ListRecords
{
    protected static string $resource = StaticContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
