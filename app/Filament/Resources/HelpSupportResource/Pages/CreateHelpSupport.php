<?php

namespace App\Filament\Resources\HelpSupportResource\Pages;

use App\Filament\Resources\HelpSupportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHelpSupport extends CreateRecord
{
    protected static string $resource = HelpSupportResource::class;
    
    public function getTitle(): string
    {
        return __('filament.actions.create') . ' ' . __('filament.fields.help_support');
    }
}