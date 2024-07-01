<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarshipCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'scholarship_categories';

    protected $fillable = [
        'category_name',
        'sort_number',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
