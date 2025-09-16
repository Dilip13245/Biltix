<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.actions.new_role'))
                ->after(function () {
                    \Filament\Notifications\Notification::make()
                        ->title(__('validation.role_created'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
