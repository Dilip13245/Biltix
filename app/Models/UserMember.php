<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_name',
        'member_phone',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}