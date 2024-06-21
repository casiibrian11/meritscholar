<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInformation extends Model
{
    use HasFactory;

    protected $fillable  = [
        'learner_reference_number',
        'atm_account_number',
        'student_id',
        'sex',
        'gender',
        'birthdate',
        'address_line',
        'barangay',
        'municipality',
        'province',
        'region',
        'contact_number',
        'monthly_income',
        'parent_status',
        'disability',
        'indigenous_group',
        'facebook_link',
        'operations',
        'user_id',
        'step1',
        'step2',
        'completed'
    ];

    // 'college_id'
    // 'course_id'
    // 'year_level'
    // 'section'
    // 'units_enrolled'
    // 'gwa'
    // 'message'

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
