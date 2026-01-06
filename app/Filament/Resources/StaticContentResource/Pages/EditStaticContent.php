<?php

namespace App\Filament\Resources\StaticContentResource\Pages;

use App\Filament\Resources\StaticContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStaticContent extends EditRecord
{
    protected static string $resource = StaticContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
