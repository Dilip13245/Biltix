<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanMarkup extends Model
{
    use HasFactory;

    protected $table = 'plan_markups';

    protected $fillable = [
        'plan_id', 'user_id', 'markup_type', 'markup_data', 'title',
        'description', 'status', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'markup_data' => 'array',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }
}