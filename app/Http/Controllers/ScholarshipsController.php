<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;
use Illuminate\Support\Facades\Storage;

use App\Libraries\AppHelper;
use App\Models\Scholarship;
use App\Models\ScholarshipCategory;
use App\Models\Requirement;
use App\Models\SchoolYear;
use App\Models\ScholarshipOffer;
use App\Models\ApplicationDetail;
use App\Models\Application;
use App\Models\Course;
use App\Models\Note;
use App\Models\User;
use App\Models\SubmittedRequirement;
use App\Models\Setting;

use App\Http\Requests\ScholarshipsRequest;
use App\Http\Requests\ApplicationDetailsRequest;

use App\Libraries\Brevo;
use App\Libraries\Notifications;
use App\Events\NewApplication;

class ScholarshipsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['visible'] = $data['visible'] ?? '';
        $data['deleted'] = $data['deleted'] ?? '';

        $data['categories'] = ScholarshipCategory::orderBy('sort_number', 'ASC')->get();

        $data['scholarships'] = Scholarship::when(!empty($data['deleted']), function($query){
                                    $query->onlyTrashed();
                                })
                                ->when(!empty($data['visible']) && $data['visible'] == 'yes', function($query){
                                    $query->where('visible', true);
                                })
                                ->when(!empty($data['visible']) && $data['visible'] == 'no', function($query){
                                    $query->where('visible', false);
                                })
                                ->with('categories')
                                ->orderBy('id', 'DESC')
                                ->paginate(10);
        
        $data['requirements'] = Requirement::orderBy('id', 'DESC')->get();

        $array = [];
        foreach ($data['requirements'] as $requirement) {
            $array[$requirement['id']] = $requirement->toArray();
        }

        $data['array'] = $array;
                    
        if (!empty($data['scholarships']->total())) {
            $data['scholarships'] = $data['scholarships']->appends($request->except('page'));
        }

        return view('scholarships.index', compact('data'));
    }

    public function save(ScholarshipsRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $validated['description'] = strtolower($validated['description']);
        $validated['requirements'] = implode(',', $validated['requirements']);
        $validated['is_per_semester'] = ($validated['is_per_semester'] == 'true') ?? false;
        
        if (empty($validated['privilege'])) {
            $validated['is_per_semester'] = false;
        }

        $data = array_merge($validated, $dataToMerge);

        $scholarship = Scholarship::where('description', $data['description'])->first();
            
        try {

            if(isset($key['id'])) {
                
                if (!empty($scholarship) && $scholarship['id'] <> $key['id']) {
                    return response()->json(['error' => AppHelper::exists()]);
                }
                Scholarship::where('id', $key['id'])->update($data);
                $message = AppHelper::updated();
                
            } else {

                if (!empty($scholarship)) {
                    return response()->json(['error' => AppHelper::exists()]);
                }

                Scholarship::create($data);
                $message = AppHelper::saved();
                
            }
            
            return response()->json([
                'success' => $message,
            ]);

        } catch(QueryException $e) {
            return response()->json(['error' => $e->errorInfo[2]]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $data = Scholarship::find($id);

        try {

            $data->forceDelete();

            return response()->json([
                'success' => AppHelper::deleted(),
            ]);

        } catch(QueryException $e) {
            
            // $data->update([ 'deleted_at' => now() ]);

            return response()->json([
                'error' => AppHelper::notAllowed(),
            ]);
            
        }
    }

    public function visibility(Scholarship $scholarship)
    {
        if ($scholarship['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $scholarship->update(['visible' => $status]);

        return redirect('/scholarships')->with('success', AppHelper::updated());
    }

    public function restore($id)
    {
        $scholarship = Scholarship::withTrashed()->where('id', $id)->restore();
        return redirect('/scholarships')->with('success', AppHelper::restored());
    }

    public function selectSchoolYear(Request $request)
    {
        $userId = Auth::user()->id;
        $info = \App\Models\StudentInformation::where('user_id', $userId)->first();

        if (empty($info) || !$info['completed'] || empty($info['student_id'])) {
            return redirect('/personal-information')->with('error', "Some required information is still missing. You need to complete your information first.");
        }

        $data = SchoolYear::where('active', true)->where('visible', true)->get();

        return view('frontend.school-years.index', compact('data'));
    }

    public function scholarships(Request $request, SchoolYear $sy)
    {
        if (!$sy['visible'] || !$sy['active']) {
            abort(401);
        }

        $data['sy_id'] = $sy['id'];

        $data['offers'] = ScholarshipOffer::with('scholarships')
                                    ->where('sy_id', $sy['id'])
                                    ->where('active', true)
                                    ->orderBy('id', 'ASC')
                                    ->get();
        
        $data['applications'] = Application::where('sy_id', $data['sy_id'])
                                    ->where('user_id', Auth::user()->id)
                                    ->get();

        $requirements = Requirement::where('visible', true)->get();

        $data['categories'] = ScholarshipCategory::orderBy('sort_number', 'ASC')->get();
        
        $requirementsArray = [];
        foreach ($requirements as $requirement) {
            $requirementsArray[$requirement['id']] = $requirement->toArray();
        }

        $data['requirements'] = $requirementsArray;

        return view('frontend.scholarships.list', compact('data'));
    }

    public function application(Request $request, SchoolYear $sy)
    {
        if (!$sy['visible'] || !$sy['active']) {
            abort(401);
        }

        $data['sy'] = $sy;
        $data['sy_id'] = $sy['id'];
        $data['user_id'] = Auth::user()->id;

        $data['courses'] = Course::where('visible', true)->orderBy('course_name', 'ASC')->get();

        $data['detail'] = ApplicationDetail::where('sy_id', $data['sy_id'])
                                    ->where('user_id', $data['user_id'])
                                    ->with('courses')
                                    ->whereHas('courses', function($query){
                                        return $query->with('colleges');
                                    })
                                    ->first();
        $data['college_name'] = "";
        if (!empty($data['detail']['courses']['colleges']['college_name'])) {
            $data['college_name'] = $data['detail']['courses']['colleges']['college_name'];
        }

        $query = new Application;
        $query = $query->where('sy_id', $data['sy_id'])->where('user_id', $data['user_id']);
                                    
        $ids = $query->pluck('scholarship_offer_id')->toArray();
        $data['applications'] = $query->with('scholarship_offers')
                                    ->with('school_years')
                                    ->get();

        $data['offers'] = ScholarshipOffer::with('scholarships')
                                ->where('sy_id', $sy['id'])
                                ->where('active', true)
                                ->whereNotIn('id', $ids)
                                ->orderBy('id', 'ASC')
                                ->get();

        $data['applied'] = $query->where('completed', true)->count();
        $data['disable'] = ($data['applied'] > 0) ? 'readonly disabled="disabled"' : '';

        return view('frontend.scholarships.application', compact('data'));
    }

    public function requirements(Request $request, SchoolYear $sy, Application $application)
    {
        if (!$sy['visible'] || !$sy['active']) {
            abort(401);
        }

        $data['notes'] = Note::where('application_id', $application['id'])->orderBy('id', 'DESC')->get();

        $data['sy_id'] = $sy['id'];
        $data['user_id'] = Auth::user()->id;

        $data['sy'] = $sy;
        $data['application'] = Application::where('id', $application['id'])
                                    ->where('user_id', $data['user_id'])
                                    ->with('scholarship_offers')
                                    ->first();
        
        $query = Requirement::where('visible', true);
        $data['requirements'] = $query->get();
        $data['submitted'] = SubmittedRequirement::where('application_id', $application['id'])
                                        ->where('user_id', $data['user_id'])
                                        ->get();

        $array = [];
        $array2 = [];

        foreach ($data['requirements'] as $requirement) {
            $array[$requirement['id']] = $requirement->toArray();
        }

        foreach ($data['submitted'] as $submitted) {
            $array2[$submitted['requirement_id']] = $submitted->toArray();
        }

        $data['requirements'] = $array;
        $data['submitted'] = $array2;

        $requirements = $application['scholarship_offers']['scholarships']['requirements'];
        $ids = explode(',', $requirements);

        $required = $query->whereIn('id', $ids)
                        ->where('required', true)
                        ->count();
        $submitted = count($data['submitted']);

        $data['ok'] = false;
        if ($submitted >= $required) {
            $data['ok'] = true;
        }

        $data['officeHoursOnly'] = AppHelper::officeHoursOnly();
        $data['pastOfficeHours'] = AppHelper::pastOfficeHours();
        $data['weekendsAllowed'] = AppHelper::weekendsAllowed();
        $data['settings'] = Setting::all();

        $settings = [];
        foreach ($data['settings'] as $setting) {
            $settings[$setting['name']] = $setting['value'];
        }

        $data['settings'] = $settings;
        
        return view('frontend.scholarships.requirements', compact('data'));
    }

    public function storeRequirements(Request $request)
    {
        $data = $request->all();
        $attachments = $data['attachment'];
        $applicationId = $data['application_id'] ?? '';
        $userId = Auth::user()->id;

        if (empty($applicationId)) {
            return redirect()->back()->with('error', "Unauthorized server request!");
        }

        foreach ($attachments as $requirement_id => $attachment) {

            $requirement = Requirement::where('id', $requirement_id)->first();
            $submitted = SubmittedRequirement::where('user_id', $userId)
                                    ->where('application_id', $applicationId)
                                    ->where('requirement_id', $requirement_id)
                                    ->first();
            
            if (empty($requirement)) {
                continue;
            }
            
            if ($requirement['required'] && empty($attachment) && empty($submitted)) {
                return redirect()->back()->with('error', "You must submit all required attachments.");
            }
            
            $hasFile = false;
        
            if (is_object($attachments[$requirement_id])) {
                $hasFile = $attachments[$requirement_id]->isValid();
            }

            if ($hasFile) {
                $filenameWithExtension = $attachments[$requirement_id]->getClientOriginalName();
                $file = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $extension = $attachments[$requirement_id]->getClientOriginalExtension();
                $attachment = $file.'_'.time().''.rand(10000,99999).'.'.$extension;

                $size = ($attachments[$requirement_id]->getSize() / 1024);
                if ($size > 2048) {
                    return redirect()->back()->with('error', "Attachment must not be larger than 2MB.");
                }

                // Validating file formats
                if (!empty($requirement['file_type'])) {
                    $fileTypes = explode(',', $requirement['file_type']);
                    if (!in_array($extension, $fileTypes)) {
                        return redirect()->back()->with('error', "It seems like you submitted invalid file type. See requirement's allowed file types.");
                    }
                }

                // Upload image
                $path = $attachments[$requirement_id]->storeAs('public/submitted-requirements', $attachment);
            }

            // Delete files
            if (!empty($submitted)) {
                $file = $submitted['attachment'];
                if (!empty($file)) {
                    $filex   = 'public/submitted-requirements/'.$file;
                    if (Storage::exists($filex)) {
                        Storage::delete($filex);   
                    }
                }
            }
            
            if (empty($submitted)) {
                
                SubmittedRequirement::create([
                    'application_id' => $applicationId,
                    'requirement_id' => $requirement_id,
                    'user_id' => $userId,
                    'attachment' => $attachment
                ]);

            } else {

                if (empty($attachment)) {
                    $attachment = $submitted['attachment'];
                }

                if (!is_null($submitted['request_to_change'])) {
                    $submitted->update([
                        'attachment' => $attachment,
                        'request_to_change' => false
                    ]);
                } else {
                    $submitted->update(['attachment' => $attachment]);
                }
            }
        }

        return redirect()->back()->with('success', "Requirements has been submitted. Check your attachments before completing your application.");
    }

    public function removeAttachment(SubmittedRequirement $requirement)
    {
        $userId = Auth::user()->id;
        if ($requirement['user_id'] <> $userId) {
            abort(401);
        }

        try {

            $requirement->delete();
            
            if (!empty($requirement['attachment'])) {
                $file = $requirement['attachment'];
                $filex   = 'public/submitted-requirements/'.$file;
                if (Storage::exists($filex)) {
                    Storage::delete($filex);   
                }
            }

            return redirect()->back()->with('success', "Attachment has been removed.");

        } catch(QueryException $e) {
            return redirect()->back()->with('error', "A problem occurred while processing your request. Reload page then try again.");
        }
    }

    public function completeApplication(Request $request)
    {
        $data = $request->all();
        $userId = Auth::user()->id;
        $id = $data['id'] ?? '';
        $sy_id = $data['sy_id'] ?? '';

        if (empty($id) || empty($sy_id)) {
            return redirect()->back()->with('error', "Invalid request! Reload page then try again.");
        }

        $application = Application::where('id', $id)
                                    ->where('user_id', $userId)
                                    ->with('scholarship_offers')
                                    ->with('school_years')
                                    ->first();

        $requirements = $application['scholarship_offers']['scholarships']['requirements'];
        $ids = explode(',', $requirements);
        
        $required = Requirement::whereIn('id', $ids)
                            ->where('visible', true)
                            ->where('required', true)
                            ->count();

        $needsUpdate = SubmittedRequirement::where('application_id', $id)
                            ->where('user_id', $userId)
                            ->where('request_to_change', true)
                            ->count();
        
        if ($needsUpdate > 0) {
            return redirect()->back()->with('error', "Comply to requested updates first!");
        }
        
        $submitted = SubmittedRequirement::where('application_id', $id)
                            ->where('user_id', $userId)
                            ->count();

        if ($submitted < $required) {
            $lacking = ($required - $submitted);
            return redirect()->back()->with('error', "The system detected that you still lack ({$lacking}) of the required attachments. Check your attachments.");
        }

        if (!is_null($application['request_to_change'])) {
            $application->update([
                'completed' => true,
                'request_to_change' => false,
                'date_updated' => now(),
                'under_review' => null
            ]);
        } else {
            $application->update([
                'completed' => true,
                'under_review' => null,
                'date_completed' => now(),
            ]);
        }

        broadcast(new NewApplication());

        // Dynamic email template notification
        $data['template'] = 'completed';
        $template = Brevo::emailTemplate($data, $application['id']);

        $name = ucwords($application['users']['first_name']).' '.ucwords($application['users']['last_name']);
        $sy = $application['school_years'];
        $semester = "for the <b>{$sy['semester']} semester of S.Y. {$sy['start_year']} - {$sy['end_year']}</b>";
        $scholarship = strtoupper($application['scholarship_offers']['scholarships']['description']);
        $subject = "We received your application for {$scholarship}";
        $subject = !empty($template['subject']) ? $template['subject'] : $subject;
        
        Notifications::notify($application['user_id'], $subject, $template['htmlContent']);

        // Notification to admin | support, depending on the settings
        $messageContentForNotification = "<b>{$name}</b> submitted an application for <b>{$scholarship}</b> {$semester}. Login to your account to review the application. ";
        self::notify($messageContentForNotification);
        return redirect()->back()->with('success', "You're application has been submitted. Wait for notifications to your email regarding your application.");
    }


    public function applicationSave(ApplicationDetailsRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($validated, $dataToMerge);
            
        try {
            if(isset($key['id'])) {
                ApplicationDetail::where('id', $key['id'])->update($data);
                $message = "Your application details has been updated.";
            } else {
                ApplicationDetail::create($data);
                $message = AppHelper::saved();    
            }

            return redirect()->back()->with(['success' => $message]);

        } catch(QueryException $e) {
            return redirect()->back()->with(['error' => $e->errorInfo[2]]);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (empty($data['offers'])) {
            return redirect()->back()->with(['error' => "Select from the options first!"]);
        }
        
        $offers = ScholarshipOffer::whereIn('id', $data['offers'])
                                ->where('active', true)
                                ->get();

        foreach ($offers as $offer) {
            if (now()->parse($offer['date_to'])->lt(now())) {
                continue;
            }

            Application::create([
                'scholarship_offer_id' => $offer['id'],
                'sy_id' => $offer['sy_id'],
                'user_id' => Auth::user()->id,
            ]);
        }

        return redirect()->back();
    }

    public function withdraw(Request $request, SchoolYear $sy, Application $application)
    {
        $user = Auth::user();
        $user = User::where('id', $user['id'])->first();

        if (!is_null($application['approved']) 
            || !is_null($application['under_review']) 
            || $application['user_id'] <> $user['id']) {
            
            $user->update([
                'is_active' => false,
                'disabled_note' => 'tried to manually delete an application last '.now()->format('F j, Y h:i:s a'),
                'disabled_by' => 'system'
            ]);

            return redirect('/unauthorized');
        }

        $submitted = SubmittedRequirement::where('application_id', $application['id'])->get();

        $additionalMessage = "";

        if (count($submitted) > 0) {
            foreach ($submitted as $submitted) {
                if (!empty($submitted['attachment'])) {
                    // Delete file also
                    
                    $filex   = 'public/submitted-requirements/'.$submitted['sample'];

                    if (Storage::exists($filex)) {
                        Storage::delete($filex);   
                    }
                }

                $submitted->delete();
            }
        }

        $application->forceDelete();

        return redirect()->back()->with('success', 'Your application has been withdrawn.');
    }

    public static function notify($messageContent)
    {
        $notifyAdmin = AppHelper::notifyAdmin();
        $notifySupport = AppHelper::notifySupport();
        $brevo = new Brevo();
        $messageVersions = [];
        $mail = [];

        $subject = 'New scholarship application submitted.';
        $site = config('app')['name'];
        $from = config('mail')['from']['name'];

        $closing = "<br /><br />
                    <b>NOTE</b>: This is an automated notification. 
                    You are receiving this because you are the :user_type for :site
                    <br /><br />
                    Regards, 
                    <br /><br />
                    <b>:from</b>";

        if ($notifyAdmin || $notifySupport) {

            if ($notifyAdmin) {
                $admins = User::where('is_active', true)
                                ->where('user_type', 'admin')
                                ->get();
                foreach ($admins as $user) {
                    $name = ucwords($user['first_name'].' '.$user['last_name']);
                    $greetings = "Hi {$name}, <br /><br />";
                    $closing = "<br /><br />
                                <b>NOTE</b>: This is an automated notification. 
                                You are receiving this because you are the {$user['user_type']} for {$site}
                                <br /><br />
                                Regards, 
                                <br /><br />
                                {$from}";

                    $mail = [
                        'htmlContent' => $greetings.$messageContent.$closing,
                        'name' => $name,
                        'email' => $user['email'],
                        'subject' => $subject
                    ];

                    $messageVersions[] = $brevo->getMessageVersions($mail);
                }
            }

            if ($notifySupport) {
                $supports = User::where('is_active', true)
                                ->where('user_type', 'support')
                                ->get();

                foreach ($supports as $support) {
                    $name = ucwords($support['first_name'].' '.$support['last_name']);
                    $greetings = "Hi {$name}, <br /><br />";
                    $closing = "<br /><br />
                                <b>NOTE</b>: This is an automated notification. 
                                You are receiving this because you are the {$support['user_type']} for {$site}
                                <br /><br />
                                Regards, 
                                <br /><br />
                                {$from}";

                    $mail = [
                        'htmlContent' => $greetings.$messageContent.$closing,
                        'name' => $name,
                        'email' => $support['email'],
                        'subject' => $subject
                    ];

                    $messageVersions[] = $brevo->getMessageVersions($mail);
                }
            }

            $mail = [
                'senderEmail' => config('mail')['from']['address'],
                'senderName' => config('mail')['from']['name'],
                'defaultSubject' => $subject,
                'defaultHtmlContent' => $messageContent
            ];
            
            $data = $brevo->getPayload($mail, $messageVersions);
            $result = $brevo->emailsBatchSend($data);
            return $result;
        }

        return true;
    }

    public function table(Request $request)
    {
        $data = $request->all();

        $id = $data['id'] ?? '';
        $html = "";

        if (!empty($id)) {
            $data['scholarships'] = Scholarship::where('scholarship_category_id', $id)->orderBy('sort_number', 'ASC')->get();
            if (count($data['scholarships']) > 0){
                $html = view('frontend.scholarships.table', compact('data'))->render();
            }
        }

        return response()->json([
            'html' => $html
        ]);
    }
}
