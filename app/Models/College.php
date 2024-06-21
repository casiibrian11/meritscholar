<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class College extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'colleges';

    protected $fillable = [
        'college_code',
        'college_name',
        'college_dean',
        'user_id',
        'visible',
        'deleted_at'
    ];
}
