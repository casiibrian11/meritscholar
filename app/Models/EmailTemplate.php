<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $table = "email_templates";

    protected $fillable = [
        'email_content',
        'status',
        'user_id'
    ];
}
