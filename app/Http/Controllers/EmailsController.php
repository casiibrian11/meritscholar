<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Libraries\Brevo;

class EmailsController extends Controller
{
    public function emailReport(Request $request) 
    {
        $email = '0104mcmxcvi@gmail.com';
        $result = (new Brevo())->getEmailReportPerEmail($email);
        dd($result);

        $result = (new Brevo())->getEmailReport($email);

        dd($result);
    }

    public function sendSms(Request $request)
    {
        $recipient = '+639533717683';
        $content = "This is a test sms";
        $result = (new Brevo())->sendSms($recipient, $content);
        dd($result);
    }
}
