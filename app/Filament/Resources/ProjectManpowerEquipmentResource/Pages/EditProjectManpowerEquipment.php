<?php

namespace App\Filament\Resources\ProjectManpowerEquipmentResource\Pages;

use App\Filament\Resources\ProjectManpowerEquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectManpowerEquipment extends EditRecord
{
    protected static string $resource = ProjectManpowerEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
