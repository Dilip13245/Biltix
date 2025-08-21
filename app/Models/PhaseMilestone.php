<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseMilestone extends Model
{
    use HasFactory;

    protected $table = 'phase_milestones';

    protected $fillable = [
        'phase_id', 'milestone_name', 'days', 'is_active', 'is_deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }
}