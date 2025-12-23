<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterialAdequacy extends Model
{
    protected $table = 'project_material_adequacy';
    protected $fillable = [
        'project_id',
        'description',
        'created_by',
        'is_active',
        'is_deleted',
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
