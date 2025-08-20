<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $table = 'user_devices';

    protected $fillable = [
        'user_id', 'token', 'device_type', 'ip_address', 'uuid',
        'os_version', 'device_model', 'app_version', 'device_token',
        'is_active', 'is_deleted'
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
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}