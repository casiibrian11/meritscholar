<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarshipOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'scholarship_offers';

    protected $fillable = [
        'sy_id',
        'scholarship_id',
        'date_from',
        'date_to',
        'user_id',
        'active',
        'deleted_at'
    ];

    public function scholarships()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id','id');
    }
}
