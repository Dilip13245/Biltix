<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    protected $table = 'projects';
    
    protected $fillable = ['project_title'];

    public function activities()
    {
        return $this->hasMany(ProjectActivity::class, 'project_id');
    }

    public function manpowerEquipment()
    {
        return $this->hasMany(ProjectManpowerEquipment::class, 'project_id');
    }

    public function safetyItems()
    {
        return $this->hasMany(ProjectSafetyItem::class, 'project_id');
    }
}