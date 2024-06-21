<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedRequirement extends Model
{
    use HasFactory;

    protected $table = 'submitted_requirements';

    protected $fillable = [
        'application_id',
        'requirement_id',
        'attachment',
        'approved',
        'request_to_change',
        'note',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function requirements()
    {
        return $this->belongsTo(Requirement::class, 'requirement_id','id');
    }
}
