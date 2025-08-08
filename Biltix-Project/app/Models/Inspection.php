<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'inspector_id',
        'title',
        'description',
        'inspection_date',
        'status',
        'checklist_items',
        'notes',
        'images',
        'is_passed',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'checklist_items' => 'array',
        'images' => 'array',
        'is_passed' => 'boolean',
    ];

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}