<?php

namespace App\Filament\Resources\ProjectActivityResource\Pages;

use App\Filament\Resources\ProjectActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectActivity extends EditRecord
{
    protected static string $resource = ProjectActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
