<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'requirements';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'file_type',
        'label',
        'description',
        'sample',
        'required',
        'user_id',
        'visible',
        'deleted_at'
    ];
}