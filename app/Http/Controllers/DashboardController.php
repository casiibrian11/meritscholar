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
    public function index(Request $request)
    {
        $data = $request->all();
        $data['sy_id'] = $data['sy_id'] ?? '';

        $data['school_years'] = SchoolYear::orderBy('semester', 'ASC')->orderBy('start_year', 'ASC')->get();

        $query = new StudentInformation;
        $appQuery = new Application;

        $gender = $query->with('users')
                        ->when(!empty($data['sy_id']), function($query) use ($data) {
                            $query->whereHas('users', function($query) use ($data) {
                                $query->whereHas('applications', function($query) use ($data) {
                                    $query->where('sy_id', $data['sy_id']);
                                });
                            });
                        })
                        ->whereHas('users', function($query) {
                            $query->where('user_type', 'student');
                        })
                        ->select('gender')
                        ->selectRaw('COUNT(gender) AS count')
                        ->groupBy('gender')
                        ->get()
                        ->toArray();

        $sex = $query->with('users')
                    ->when(!empty($data['sy_id']), function($query) use ($data) {
                        $query->whereHas('users', function($query) use ($data) {
                            $query->whereHas('applications', function($query) use ($data) {
                                $query->where('sy_id', $data['sy_id']);
                            });
                        });
                    })
                    ->whereHas('users', function($query) {
                        $query->where('user_type', 'student');
                    })
                    ->select('sex')
                    ->selectRaw('COUNT(sex) AS count')
                    ->groupBy('sex')
                    ->get()
                    ->toArray();

        $applications = $appQuery->where('completed', true)
                            ->when(!empty($data['sy_id']), function($query) use ($data) {
                                $query->where('sy_id', $data['sy_id']);
                            })
                            ->selectRaw('COUNT(created_at) AS count, DATE(created_at) AS date')
                            ->groupBy('date')
                            ->get()
                            ->toArray();
        $scholarships = $appQuery->where('completed', true)
                            ->when(!empty($data['sy_id']), function($query) use ($data) {
                                $query->where('sy_id', $data['sy_id']);
                            })
                            ->selectRaw('COUNT(scholarship_offer_id) AS offer_count, scholarship_offer_id')
                            ->groupBy('scholarship_offer_id')
                            ->get()
                            ->toArray();

        $offerIds = [];
        foreach ($scholarships as $key => $value) {
            $offerIds[] = $scholarships[$key]['scholarship_offer_id'];
        }
        
        $offers = ScholarshipOffer::whereIn('id', $offerIds)
                                ->with('scholarships')
                                ->get()
                                ->toArray();

        $scholarshipOffers = [];
        foreach ($offers as $offer) {
            $scholarshipOffers[$offer['id']] = $offer;
        }      
        
        $genderLabels = [];
        $genderCounts = [];
        $sexLabels = [];
        $sexCounts = [];
        $applicationsLabels = [];
        $applicationsCounts = [];
        $scholarshipsLabels = [];
        $scholarshipsCounts = [];
        $scholarshipsBg = [];


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

        foreach ($applications as $key => $value) {
            $applicationCount = $applications[$key]['count'];
            $applicationsLabels[] = now()->parse($applications[$key]['date'])->format('M j, Y');
            $applicationsCounts[] = $applicationCount;
        }

        foreach ($scholarships as $key => $value) {
            $scholarshipCount = $scholarships[$key]['offer_count'];
            $offerId = $scholarships[$key]['scholarship_offer_id'];

            $scholarshipsLabels[] = strtoupper($scholarshipOffers[$offerId]['scholarships']['description']);
            $scholarshipsCounts[] = $scholarshipCount;
            $scholarshipsBg[] = "#".rand(111111, 999999);
        }

        $data['genderLabels'] = $genderLabels;
        $data['genderCounts'] = $genderCounts;

        $data['sexLabels'] = $sexLabels;
        $data['sexCounts'] = $sexCounts;

        $data['applicationsLabels'] = $applicationsLabels;
        $data['applicationsCounts'] = $applicationsCounts;

        $data['scholarshipsLabels'] = $scholarshipsLabels;
        $data['scholarshipsCounts'] = $scholarshipsCounts;
        $data['scholarshipsBg'] = $scholarshipsBg;

        return view('dashboard', compact('data'));
    }
}
