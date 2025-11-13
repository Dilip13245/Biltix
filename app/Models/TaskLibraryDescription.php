<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLibraryDescription extends Model
{
    use HasFactory;

    protected $table = 'task_library_descriptions';

    protected $fillable = [
        'task_library_id',
        'description',
        'sort_order',
        'is_active',
        'is_deleted'
    ];

    protected $casts = [
        'task_library_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    public function taskLibrary()
    {
        return $this->belongsTo(TaskLibrary::class, 'task_library_id');
    }
}
