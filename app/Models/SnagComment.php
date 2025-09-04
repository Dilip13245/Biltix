<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnagComment extends Model
{
    use HasFactory;

    protected $table = 'snag_comments';

    protected $fillable = [
        'snag_id', 'user_id', 'comment', 'status_changed_to', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function snag()
    {
        return $this->belongsTo(Snag::class, 'snag_id');
    }
}