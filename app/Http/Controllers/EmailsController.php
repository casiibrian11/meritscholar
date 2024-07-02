<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libraries\Brevo;
use App\Libraries\AppHelper;

use App\Models\EmailTemplate;
use App\Models\Scholarship;
use App\Models\Application;
use App\Http\Requests\EmailsRequest;

use Illuminate\Support\Facades\Auth;

class EmailsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['statuses'] = Brevo::$statuses;

        $templates = EmailTemplate::get();

        $data['templates'] = [];
        foreach ($templates as $template) {
            $data['templates'][$template['status']] = $template->toArray();
        }

        return view('emails.templates', compact('data'));
    }

    public function manage(Request $request)
    {
        $data = $request->all();
        $data['statuses'] = Brevo::$statuses;
        $status = array_keys($data['statuses']);

        if (!in_array($data['template'], $status)) {
            return redirect('/emails/templates')->with('error', 'Template not recognized.');    
        }

        $data['email'] = EmailTemplate::where('status', $data['template'])->first();

        return view('emails.manage', compact('data'));
    }

    public function view(Request $request)
    {
        $data = $request->all();
        $data['statuses'] = Brevo::$statuses;
        $status = array_keys($data['statuses']);

        if (!in_array($data['template'], $status)) {
            return redirect('/emails/templates')->with('error', 'Template not recognized.');    
        }

        $application = Application::first();
        $data = Brevo::emailTemplate($data, $application['id']);

        return view("emails.templates.email", compact('data'));
    }

    public function save(EmailsRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($validated, $dataToMerge);

        $data['statuses'] = Brevo::$statuses;
        $status = array_keys($data['statuses']);
        if (!in_array($data['status'], $status)) {
            return redirect('/emails/templates')->with('error', 'Unauthorized server request.');    
        }

        try {
            
            EmailTemplate::updateOrCreate([
               'status' => $data['status'] 
            ], $data);
            
            return redirect()->back()->with(['success' => AppHelper::saved()]);

        } catch(QueryException $e) {
            return redirect()->back()->with(['error' => $e->errorInfo[2]]);
        }
    }

    public function emailReport(Request $request) 
    {
        $data = $request->all();
        $email = $data['email'] ?? '';
        $startDate = $data['start_date'] ?? '';

        if (empty($startDate)) {
            $startDate = now()->subDays(30)->format('Y-m-d');
        }

        if (isset($data['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect('/emails')->with('error', "Invalid email. You must search using a valid email.");
        }

        $result['events'] = [];
        if (!empty($email)) {
            $result = (new Brevo())->getEmailReport($startDate, $email);
        } else {
            $result = (new Brevo())->getEmailReport($startDate);
        }
        
        $result['emails'] = ['0104mcmxcvi@gmail.com', 'reymond@vernaschediewelt.com', 'r3ym0nd.gln@gmail.com'];
        $result['email'] = $email;

        return view('emails.index', compact('result'));
    }
}
