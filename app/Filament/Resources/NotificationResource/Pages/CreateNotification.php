<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Models\Project;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification as FilamentNotification;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle recipient type and send notifications
        $userIds = [];
        
        if ($data['recipient_type'] === 'single') {
            $userIds = [$data['user_id']];
        } elseif ($data['recipient_type'] === 'multiple') {
            $userIds = $data['user_ids'];
        } elseif ($data['recipient_type'] === 'role') {
            $userIds = User::where('role', $data['role'])
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->pluck('id')
                ->toArray();
        } elseif ($data['recipient_type'] === 'project') {
            // For project, we'll send to team after creation
            $data['_send_to_project'] = true;
        } elseif ($data['recipient_type'] === 'all') {
            $userIds = User::where('is_active', true)
                ->where('is_deleted', false)
                ->pluck('id')
                ->toArray();
        }
        
        // Store user IDs for after creation hook
        $data['_user_ids'] = $userIds;
        
        // Set the first user as the primary user_id for database
        if (!empty($userIds)) {
            $data['user_id'] = $userIds[0];
        }
        
        // Add action URL if not set
        if (empty($data['action_url'])) {
            $data['action_url'] = '/dashboard';
        }
        
        // Store additional data
        $data['data'] = [
            'action_url' => $data['action_url'],
            'recipient_type' => $data['recipient_type'],
        ];
        
        // Remove temporary fields
        unset($data['recipient_type'], $data['user_ids'], $data['role'], $data['project_id'], $data['send_push'], $data['action_url']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->data;
        $record = $this->record;
        
        // Send notifications to all recipients
        if (isset($data['_send_to_project']) && $data['_send_to_project']) {
            NotificationHelper::sendToProjectTeam(
                $data['project_id'],
                $record->type,
                $record->title,
                $record->message,
                json_decode($record->data, true) ?? [],
                $record->priority
            );
            
            FilamentNotification::make()
                ->title('Notification sent to project team')
                ->success()
                ->send();
        } elseif (!empty($data['_user_ids'])) {
            NotificationHelper::send(
                $data['_user_ids'],
                $record->type,
                $record->title,
                $record->message,
                json_decode($record->data, true) ?? [],
                $record->priority
            );
            
            FilamentNotification::make()
                ->title('Notification sent to ' . count($data['_user_ids']) . ' users')
                ->success()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
