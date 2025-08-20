<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResult extends Model
{
    use HasFactory;

    protected $table = 'inspection_results';

    protected $fillable = [
        'inspection_id', 'item_name', 'result', 'notes', 'images', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}