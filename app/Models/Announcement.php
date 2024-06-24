<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable  = [
        'content',
        'visible',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id')->withTrashed();
    }
}
