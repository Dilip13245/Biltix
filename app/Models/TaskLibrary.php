<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLibrary extends Model
{
    use HasFactory;

    protected $table = 'task_library';

    protected $fillable = [
        'title',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    public function descriptions()
    {
        return $this->hasMany(TaskLibraryDescription::class, 'task_library_id')
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->orderBy('sort_order');
    }
}
