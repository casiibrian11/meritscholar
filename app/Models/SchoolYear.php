<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_years';

    public $timestamps = false;

    protected $fillable = [
        'start_year',
        'end_year',
        'semester',
        'user_id',
        'visible',
        'active',
        'deleted_at'
    ];
}
