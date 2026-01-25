<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChatReadStatus extends Model
{
    use HasFactory;

    protected $table = 'project_chat_read_status';

    protected $fillable = ['chat_id', 'user_id', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function chat()
    {
        return $this->belongsTo(ProjectChat::class, 'chat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
