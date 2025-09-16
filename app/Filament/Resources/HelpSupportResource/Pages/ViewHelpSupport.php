<?php

namespace App\Filament\Resources\HelpSupportResource\Pages;

use App\Filament\Resources\HelpSupportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHelpSupport extends ViewRecord
{
    protected static string $resource = HelpSupportResource::class;
    
    public function getTitle(): string
    {
        return __('filament.actions.view_details') . ' #' . $this->record->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}