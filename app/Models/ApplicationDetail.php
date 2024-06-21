<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDetail extends Model
{
    use HasFactory;

    protected $fillable  = [
        'sy_id',
        'course_id',
        'year_level',
        'section',
        'units_enrolled',
        'gwa',
        'user_id'
    ];

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id','id');
    }
}
