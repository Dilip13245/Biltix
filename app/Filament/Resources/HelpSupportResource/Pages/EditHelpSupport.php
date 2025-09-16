<?php

namespace App\Filament\Resources\HelpSupportResource\Pages;

use App\Filament\Resources\HelpSupportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHelpSupport extends EditRecord
{
    protected static string $resource = HelpSupportResource::class;
    
    public function getTitle(): string
    {
        return __('filament.actions.edit') . ' #' . $this->record->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}