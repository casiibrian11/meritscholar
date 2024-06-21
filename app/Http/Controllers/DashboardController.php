<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;
use Illuminate\Support\Facades\Storage;

use App\Libraries\AppHelper;
use App\Libraries\Brevo;

use App\Models\Scholarship;
use App\Models\Requirement;
use App\Models\SchoolYear;
use App\Models\ScholarshipOffer;
use App\Models\ApplicationDetail;
use App\Models\Application;
use App\Models\Course;
use App\Models\User;
use App\Models\StudentInformation;


class DashboardController extends Controller
{
    public function index()
    {
        $query = new StudentInformation;

        $gender = $query->with('users')
                        ->whereHas('users', function($query) {
                            $query->where('user_type', 'student');
                        })
                        ->select('gender')
                        ->selectRaw('COUNT(gender) AS count')
                        ->groupBy('gender')
                        ->get()
                        ->toArray();

        $sex = $query->with('users')
                    ->whereHas('users', function($query) {
                        $query->where('user_type', 'student');
                    })
                    ->select('sex')
                    ->selectRaw('COUNT(sex) AS count')
                    ->groupBy('sex')
                    ->get()
                    ->toArray();

        
        $genderLabels = [];
        $genderCounts = [];
        $sexLabels = [];
        $sexCounts = [];

        foreach ($gender as $key => $value) {
            
            $genderCount = $gender[$key]['count'];

            $percentage[$key] = ($genderCount / count($gender)) * 100;
            $percentage[$key] = number_format($percentage[$key]);
            $genderLabels[] = ucwords($gender[$key]['gender'])." ({$percentage[$key]}%)";
            
            $genderCounts[] = $genderCount;
        }

        foreach ($sex as $key => $value) {
            
            $sexCount = $sex[$key]['count'];

            $percentage[$key] = ($sexCount / count($sex)) * 100;
            $percentage[$key] = number_format($percentage[$key]);
            $sexLabels[] = ucwords($sex[$key]['sex'])." ({$percentage[$key]}%)";

            $sexCounts[] = $sexCount;
        }

        $data['genderLabels'] = $genderLabels;
        $data['genderCounts'] = $genderCounts;

        $data['sexLabels'] = $sexLabels;
        $data['sexCounts'] = $sexCounts;

        return view('dashboard', compact('data'));
    }
}
