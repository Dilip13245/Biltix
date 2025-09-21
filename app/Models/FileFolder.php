<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFolder extends Model
{
    use HasFactory;

    protected $table = 'file_folders';

    protected $fillable = [
        'project_id', 'name', 'description', 'created_by', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }



    public function files()
    {
        return $this->hasMany(File::class, 'folder_id');
    }
}