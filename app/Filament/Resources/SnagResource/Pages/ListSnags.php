<?php

namespace App\Filament\Resources\SnagResource\Pages;

use App\Filament\Resources\SnagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSnags extends ListRecords
{
    protected static string $resource = SnagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
