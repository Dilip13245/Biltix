<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLog extends Model
{
    use HasFactory;

    protected $table = 'staff_logs';

    protected $fillable = [
        'daily_log_id', 'staff_type', 'count', 'hours_worked',
        'tasks_performed', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'hours_worked' => 'decimal:2',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}