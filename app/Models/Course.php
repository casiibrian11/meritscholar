<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    public $timestamps = false;

    protected $fillable = [
        'course_code',
        'course_name',
        'college_id',
        'user_id',
        'visible',
        'deleted_at',
    ];

    public function colleges()
    {
        return $this->belongsTo(College::class, 'college_id','id');
    }
}
