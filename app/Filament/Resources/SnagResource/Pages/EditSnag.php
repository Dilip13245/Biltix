<?php

namespace App\Filament\Resources\SnagResource\Pages;

use App\Filament\Resources\SnagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSnag extends EditRecord
{
    protected static string $resource = SnagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
