<?php

namespace App\Filament\Resources\TaskLibraryResource\Pages;

use App\Filament\Resources\TaskLibraryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskLibrary extends EditRecord
{
    protected static string $resource = TaskLibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

