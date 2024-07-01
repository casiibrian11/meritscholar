<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'scholarships';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'requirements',
        'user_id',
        'visible',
        'active',
        'deleted_at',
        'sort_number',
        'privilege',
        'is_per_semester'
    ];

    public function categories()
    {
        return $this->belongsTo(ScholarshipCategory::class, 'scholarship_category_id', 'id');
    }
}
