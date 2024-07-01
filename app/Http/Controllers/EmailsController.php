<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libraries\Brevo;

class EmailsController extends Controller
{
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
