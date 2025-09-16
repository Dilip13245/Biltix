<?php

namespace App\Filament\Resources\HelpSupportResource\Pages;

use App\Filament\Resources\HelpSupportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHelpSupports extends ListRecords
{
    protected static string $resource = HelpSupportResource::class;
    
    public function getTitle(): string
    {
        return __('filament.navigation.help_support');
    }
    
    protected function getHeaderActions(): array
    {
        return [];
    }
}