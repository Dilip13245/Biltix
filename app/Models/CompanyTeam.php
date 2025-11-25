<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'member_user_id',
        'added_by',
        'role',
        'is_active',
        'is_deleted',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    // Relationship: Company owner (main user)
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    // Relationship: Team member
    public function member()
    {
        return $this->belongsTo(User::class, 'member_user_id');
    }

    // Relationship: Who added this member
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
