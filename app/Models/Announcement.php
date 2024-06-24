<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    public $timestamps = false;

    protected $fillable  = [
        'title',
        'content',
        'visible',
        'notify_users',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id')->withTrashed();
    }
}
