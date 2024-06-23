<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

use App\Libraries\AppHelper;
use App\Libraries\Brevo;
use App\Libraries\Notifications;

use App\Models\Scholarship;
use App\Models\Requirement;
use App\Models\SchoolYear;
use App\Models\ScholarshipOffer;
use App\Models\ApplicationDetail;
use App\Models\Application;
use App\Models\Course;
use App\Models\Note;
use App\Models\User;
use App\Models\College;
use App\Models\SubmittedRequirement;


use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class ApplicationsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data = self::data($data, $request);

        $data['pdf'] = '/pdf'.$request->getRequestUri();
        $data['excel'] = '/excel'.$request->getRequestUri();

        return view('applications.index', compact('data'));
    }

    public function toPdf(Request $request)
    {
        $data = $request->all();
        $data = self::data($data, $request, 'pdf');

        if (!empty($data['sy_id'])) {
            $data['sy'] = SchoolYear::where('id', $data['sy_id'])->first();
        }

        if (!empty($data['scholarship_id'])) {
            $data['scholarship'] = Scholarship::where('id', $data['scholarship_id'])->first();
        }

        $html =  view('applications.pdf', compact('data'));

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();

        // $html = view('applications.pdf', compact('data'))->render();
        // $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait')->setOptions(['defaultFont' => 'sans-serif']);;
        // return $pdf->stream();
    }

    public function toExcel(Request $request)
    {
        $data = $request->all();
        $data = self::data($data, $request, 'excel');

        if (!empty($data['sy_id'])) {
            $data['sy'] = SchoolYear::where('id', $data['sy_id'])->first();
        }

        if (!empty($data['scholarship_id'])) {
            $data['scholarship'] = Scholarship::where('id', $data['scholarship_id'])->first();
        }

        $html =  view('applications.excel', compact('data'));

        return $html;

        return Excel::download((new \App\Libraries\Export($data))->view(), 'excel.xlsx');
    }

    public static function data($data, $request, $source = "")
    {
        $filters = $data;
        $data['status'] = $data['status'] ?? '';
        $data['sy_id'] = $data['sy_id'] ?? '';
        $data['scholarship_id'] = $data['scholarship_id'] ?? '';

        $status = $data['status'];
        $syId = $data['sy_id'];
        $scholarshipId = $data['scholarship_id'];

        $query = new Application;
        
        $customQuery['to_review'] = $query->where('completed', true)
                                            ->where('under_review', null)
                                            ->where('approved', null)
                                            ->when(!empty($scholarshipId), function($query) use ($scholarshipId) {
                                                $query->whereHas('scholarship_offers', function($query) use ($scholarshipId) {
                                                    $query->with('scholarships')->whereHas('scholarships', function($query) use ($scholarshipId) {
                                                        $query->where('id', $scholarshipId);
                                                    });
                                                });
                                            })
                                            ->when(!empty($syId), function($query) use ($syId) {
                                                $query->where('sy_id', $syId);
                                            });

        $customQuery['for_approval'] = $query->where('completed', true)
                                                ->where('under_review', 0)
                                                ->where('approved', null)
                                                ->when(!empty($scholarshipId), function($query) use ($scholarshipId) {
                                                    $query->whereHas('scholarship_offers', function($query) use ($scholarshipId) {
                                                        $query->with('scholarships')->whereHas('scholarships', function($query) use ($scholarshipId) {
                                                            $query->where('id', $scholarshipId);
                                                        });
                                                    });
                                                })
                                                ->when(!empty($syId), function($query) use ($syId) {
                                                    $query->where('sy_id', $syId);
                                                });

        $customQuery['approved'] = $query->where('completed', true)
                                            ->where('under_review','<>', null)
                                            ->where('approved', true)
                                            ->when(!empty($scholarshipId), function($query) use ($scholarshipId) {
                                                $query->whereHas('scholarship_offers', function($query) use ($scholarshipId) {
                                                    $query->with('scholarships')->whereHas('scholarships', function($query) use ($scholarshipId) {
                                                        $query->where('id', $scholarshipId);
                                                    });
                                                });
                                            })
                                            ->when(!empty($syId), function($query) use ($syId) {
                                                $query->where('sy_id', $syId);
                                            });

        $customQuery['denied'] = $query->where('completed', true)
                                        ->where('under_review','<>', null)
                                        ->where('approved', false)
                                        ->when(!empty($scholarshipId), function($query) use ($scholarshipId) {
                                            $query->whereHas('scholarship_offers', function($query) use ($scholarshipId) {
                                                $query->with('scholarships')->whereHas('scholarships', function($query) use ($scholarshipId) {
                                                    $query->where('id', $scholarshipId);
                                                });
                                            });
                                        })
                                        ->when(!empty($syId), function($query) use ($syId) {
                                            $query->where('sy_id', $syId);
                                        });

        $completed = $customQuery['to_review']->count();
        $forApproval = $customQuery['for_approval']->count();
        $approved = $customQuery['approved']->count();
        $denied = $customQuery['denied']->count();

        $data['count'] = [
            'to_review' => $completed,
            'for_approval' => $forApproval,
            'approved' => $approved,
            'denied' => $denied,
        ];

        $data['school_years'] = SchoolYear::withTrashed()
                                    ->orderBy('id', 'ASC')
                                    ->get();
        $data['scholarships'] = Scholarship::withTrashed()
                                    ->orderBy('description', 'ASC')
                                    ->get();

        $data['applications'] = Application::with('users')
                                    ->with('school_years')
                                    ->with('scholarship_offers')
                                    ->with('details')
                                    ->where('completed', true)
                                    ->when(!empty($data['college_id']), function($query) use ($data){
                                        $query->whereHas('details', function($query) use ($data){
                                            $query->whereHas('courses', function($query) use ($data){
                                                $query->whereHas('colleges', function($query) use ($data){
                                                    $query->where('college_id', $data['college_id']);
                                                });
                                            });
                                        });
                                    })
                                    ->when(!empty($data['course_id']), function($query) use ($data){
                                        $query->whereHas('details', function($query) use ($data){
                                            $query->where('course_id', $data['course_id']);
                                        });
                                    })
                                    ->when(!empty($data['keyword']), function($query) use ($data) {
                                        $query->whereHas('users', function ($query) use ($data) {
                                            $query->where('first_name', 'LIKE', "%{$data['keyword']}%")
                                                ->orWhere('middle_name', 'LIKE', "%{$data['keyword']}%")
                                                ->orWhere('last_name', 'LIKE', "%{$data['keyword']}%")
                                                ->orWhere('email', 'LIKE', "%{$data['keyword']}%");
                                        });
                                    })
                                    ->when(empty($filters) && empty($data['keyword']), function($q) {
                                        $q->whereNull('approved');
                                    })
                                    ->when(!empty($scholarshipId), function($query) use ($scholarshipId) {
                                        $query->whereHas('scholarship_offers', function($query) use ($scholarshipId) {
                                            $query->with('scholarships')->whereHas('scholarships', function($query) use ($scholarshipId) {
                                                $query->where('id', $scholarshipId);
                                            });
                                        });
                                    })
                                    ->when(!empty($syId), function($query) use ($syId) {
                                        $query->where('sy_id', $syId);
                                    })
                                    ->when(empty($status) && empty($syId) && empty($scholarshipId), function($query) {
                                        $query->orWhere('request_to_change', true)
                                            ->orWhere('request_to_change', '<>', 0);
                                    })
                                    ->when($status == 'to_review', function($query) {
                                        $query->where('under_review', null)->where('approved', null);
                                    })
                                    ->when($status == 'for_approval', function($query) {
                                        $query->where('under_review', '<>', null)->where('approved', null);
                                    })
                                    ->when($status == 'approved', function($query) {
                                        $query->where('under_review','<>', null)->where('approved', true);
                                    })
                                    ->when($status == 'denied', function($query){
                                        $query->where('under_review','<>', null)->where('approved', false);
                                    });
        if (!empty($source)) {
            $data['applications'] = $data['applications']->get();
        } else {
            $data['applications'] = $data['applications']->paginate(50);
            if (!empty($data['applications']->total())) {
                $data['applications'] = $data['applications']->appends($request->except('page'));
            }
        }

        $details = ApplicationDetail::with('courses')->get();

        $array = [];
        
        foreach ($details as $detail) {
            $array[$detail['user_id']] = $detail->toArray();
        }

        $data['detail'] = $array;

        return $data;
    }

    public function requirements(Request $request, Application $application)
    {
        $data = $request->all();

        $data['requirements'] = SubmittedRequirement::with('users')
                                    ->with('requirements')
                                    ->where('application_id', $application['id'])
                                    ->orderBy('requirement_id', 'ASC')
                                    ->get();

        $data['notes'] = Note::where('application_id', $application['id'])->get();

        $data['application'] = Application::with('users')
                                        ->with('school_years')
                                        ->with('scholarship_offers')
                                        ->where('id', $application['id'])
                                        ->first();

        $data['details'] = ApplicationDetail::with('courses')
                                        ->where('user_id', $data['application']['user_id'])
                                        ->where('sy_id', $data['application']['sy_id'])
                                        ->first();

        if (is_null($application['under_review'])) {

            $message = "Your application is now under review.";
            $application = Application::with('scholarship_offers')
                                ->with('users')
                                ->with('school_years')
                                ->where('id', $application['id'])
                                ->first();
        
            $scholarship = strtoupper($application['scholarship_offers']['scholarships']['description']);
            $sy = $application['school_years'];
            $semester = "for the <b>{$sy['semester']} semester of S.Y. {$sy['start_year']} - {$sy['end_year']}</b>";
            $messageContent = "";
            $messageContent .= "Hi ".ucwords($application['users']['first_name']).' '.ucwords($application['users']['last_name']).", <br /><br />";
            $messageContent .= "Your application for <b>{$scholarship}</b> {$semester} is now under review.<br /><br />You will receive a separate notification regarding the status of your application.";
            Notifications::notify($application['user_id'], $message, $messageContent);

            $application->update([ 'under_review' => false ]);
        }

        return view('applications.requirements', compact('data'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $approved = $data['approved'] ?? '';
        $id = $data['id'] ?? '';
        $status = $data['status'] ?? '';
        $action = $data['action'] ?? '';
        $undo = $data['undo'] ?? '';

        if (!empty($action) && $action == 'update-application') {

            if (!empty($undo)) {
                $approved = null;
            } else {
                $approved = ($approved == 'true') ?? false;
            }
            
            Application::where('id', $id)->update(['approved' => $approved]);

            return response()->json([
                'success' => true
            ]);
        }

        if (empty($data['status'])) {
            $data['approved'] = ($approved == 'true') ?? false;
            $data['status_updated_at'] = now();
        }

        $data['note'] = $data['note'] ?? '';

        unset($data['status']);

        SubmittedRequirement::where('id', $id)->update($data);

        return response()->json([
            'success' => true
        ]);
    }

    public function saveNote(Request $request)
    {
        $data = $request->all();
        $applicationId = $data['id'] ?? '';
        $note = $data['note'] ?? '';
        $userId = Auth::user()->id;

        if (empty($applicationId) || empty($note)) {
            return redirect()->back()->with('error', 'Invalid server request.');
        }

        Note::create([
            'application_id' => $applicationId,
            'note' => $note,
            'user_id' => $userId
        ]);

        return redirect()->back()->with('success', 'Note has been added.');

    }

    public function deleteNote(Note $note)
    {
        $note->delete();
        return redirect()->back()->with('success', 'Note has been deleted.');
    }

    public function requestToChange(SubmittedRequirement $requirement, Application $application)
    {
        $application->update([
            'request_to_change' => true,
            'completed' => false
        ]);

        $requirement->update([
            'request_to_change' => true
        ]);

        return redirect()->back()->with('success', 'Application status has been updated.');
    }

    public function updateStatus(Application $application, Request $request)
    {
        $data = $request->all();
        $approved = $data['approved'] ?? '';

        if (!in_array($approved, ['true', 'false'])) {
            return redirect()->back()->with('error', 'Unauthorized request!');    
        }

        $approved = ($approved == 'true') ?? false;
        
        $application->update([
            'approved' => $approved,
            'under_review' => true
        ]);

        if ($approved) {
            $message = "Application has been approved.";
            $status = "approved";
        } else {
            $message = "Application has been denied.";
            $status = "denied";
        }

        $application = Application::with('scholarship_offers')
                                ->with('users')
                                ->with('school_years')
                                ->where('id', $application['id'])
                                ->first();
        
        $scholarship = strtoupper($application['scholarship_offers']['scholarships']['description']);
        $sy = $application['school_years'];
        $semester = "for the <b>{$sy['semester']} semester of S.Y. {$sy['start_year']} - {$sy['end_year']}</b>";
        $messageContent = "";
        $messageContent .= "Hi ".ucwords($application['users']['first_name']).' '.ucwords($application['users']['last_name']).", <br /><br />";
        $messageContent .= "Your application for <b>{$scholarship}</b> {$semester} has been {$status}.<br /><br />Login to your account to check for any additional information.";
        Notifications::notify($application['user_id'], 'Your '.strtolower($message), $messageContent);

        return redirect()->back()->with('success', $message." A notification has also been sent to the student.");
    }

    public function download(SubmittedRequirement $requirement)
    {
        if (empty($requirement['attachment'])) {
            abort(401);
        }

        $file   = 'public/submitted-requirements/'.$requirement['attachment'];
        if (Storage::exists($file)) {
            $file   = public_path('/storage/submitted-requirements/'.$requirement['attachment']);
            return response()->download($file);
        } else {
            abort(401);
        }
    }

    public function masterlist(Request $request)
    {
        $data = $request->all();
        $data = self::data($data, $request);

        $data['pdf'] = '/pdf'.$request->getRequestUri();
        $data['excel'] = '/excel'.$request->getRequestUri();

        $data['colleges'] = College::orderBy('college_name', 'ASC')->get();

        return view('applications.masterlist', compact('data'));
    }
}
