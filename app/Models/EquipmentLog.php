<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentLog extends Model
{
    use HasFactory;

    protected $table = 'equipment_logs';

    protected $fillable = [
        'daily_log_id', 'equipment_id', 'equipment_type', 'operator_name',
        'status', 'hours_used', 'location', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'hours_used' => 'decimal:2',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}