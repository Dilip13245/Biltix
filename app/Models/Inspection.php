<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspections';

    protected $fillable = [
        'project_id', 'phase_id', 'category', 'description', 'comment', 'status', 'inspected_by', 'created_by',
        'is_active', 'is_deleted', 'notes', 'started_at'
    ];
    
    protected $attributes = [
        'status' => 'open',
        'is_active' => true,
        'is_deleted' => false,
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'started_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_deleted', false);
    }

    public function checklists()
    {
        return $this->hasMany(InspectionChecklist::class, 'inspection_id');
    }

    public function images()
    {
        return $this->hasMany(InspectionImage::class, 'inspection_id')
            ->where('is_active', true)
            ->where('is_deleted', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inspectedBy()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }
}