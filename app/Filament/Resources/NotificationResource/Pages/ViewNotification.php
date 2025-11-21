<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_read')
                ->label('Mark as Read')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => !$this->record->is_read)
                ->action(function (): void {
                    $this->record->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Marked as read')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('mark_unread')
                ->label('Mark as Unread')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->visible(fn () => $this->record->is_read)
                ->action(function (): void {
                    $this->record->update([
                        'is_read' => false,
                        'read_at' => null,
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Marked as unread')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make()
                ->action(fn () => $this->record->update(['is_deleted' => true])),
        ];
    }
}
