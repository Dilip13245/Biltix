<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChat extends Model
{
    use HasFactory;

    protected $table = 'project_chats';

    protected $fillable = ['project_id', 'user_id', 'message', 'attachment', 'is_deleted'];

    protected $casts = [
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function readStatus()
    {
        return $this->hasMany(ProjectChatReadStatus::class, 'chat_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
}
