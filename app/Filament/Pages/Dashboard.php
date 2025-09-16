<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return 'Biltix Admin';
    }
    
    public function getHeading(): string
    {
        return 'Welcome';
    }
    
    public function getSubheading(): ?string
    {
        return null;
    }
    
    public function getColumns(): int | string | array
    {
        return 1;
    }
}