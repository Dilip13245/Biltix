<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('filament.actions.new_permission'))
                ->after(function () {
                    \Filament\Notifications\Notification::make()
                        ->title(__('validation.permission_created'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
