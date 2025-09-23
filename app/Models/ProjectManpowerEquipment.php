<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectManpowerEquipment extends Model
{
    use HasFactory;

    protected $table = 'project_manpower_equipment';

    protected $fillable = [
        'project_id',
        'category',
        'count',
        'is_active',
        'is_deleted',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'count' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}