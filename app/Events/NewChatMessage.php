<?php

namespace App\Events;

use App\Models\ProjectChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(ProjectChat $chat)
    {
        $this->chat = $chat->load('user:id,name,profile_image');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('project-chat.' . $this->chat->project_id);
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->chat->id,
            'project_id' => $this->chat->project_id,
            'user_id' => $this->chat->user_id,
            'message' => $this->chat->message,
            'attachment' => $this->chat->attachment ? asset('storage/' . $this->chat->attachment) : null,
            'created_at' => $this->chat->created_at->toISOString(),
            'user' => [
                'id' => $this->chat->user->id,
                'name' => $this->chat->user->name,
                'profile_image' => $this->chat->user->profile_image 
                    ? asset('storage/profile/' . $this->chat->user->profile_image) 
                    : null,
            ],
        ];
    }
}
