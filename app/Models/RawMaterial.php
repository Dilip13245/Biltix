<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'type',
        'quantity',
        'description',
        'image_path',
        'original_image_name',
        'file_size',
        'status',
        'rejection_note',
        'posted_by',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'is_active',
        'is_deleted',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        return asset('storage/raw_materials/' . $this->image_path);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
