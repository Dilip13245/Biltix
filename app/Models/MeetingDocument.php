<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingDocument extends Model
{
    use HasFactory;

    protected $table = 'meeting_documents';

    protected $fillable = [
        'meeting_id', 'file_path', 'file_name', 'file_type', 'file_size', 'uploaded_by'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
