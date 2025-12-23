<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectQualityWork extends Model
{
    protected $table = 'project_quality_work';
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
