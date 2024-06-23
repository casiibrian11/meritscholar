<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable  = [
        'scholarship_offer_id',
        'sy_id',
        'completed',
        'approved',
        'under_review',
        'request_to_change',
        'date_completed',
        'date_updated',
        'user_id'
    ];

    public function scholarship_offers()
    {
        return $this->belongsTo(ScholarshipOffer::class, 'scholarship_offer_id','id')->withTrashed();
    }

    public function school_years()
    {
        return $this->belongsTo(SchoolYear::class, 'sy_id','id')->withTrashed();
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id')->withTrashed();
    }

    public function details()
    {
        return $this->hasOne(ApplicationDetail::class, 'user_id', 'user_id');
    }
}
