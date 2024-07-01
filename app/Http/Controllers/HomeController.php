<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Brevo;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_type = Auth::user()->user_type;
        
        if ($user_type == 'student') {
            return redirect('/profile');
        } else {
            return redirect('/dashboard');
        }
    }

    public function verification()
    {
        return view('verification');
    }

    public function verify(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if (!empty($user['verification_code']) && $data['verification_code'] <> $user['verification_code']) {
            return redirect('/verification')->with('error', 'Verification code not matched. Please check the email we sent you then try again.');
        }

        $user->update(['is_verified' => true, 'verification_code' => null]);

        return redirect('/home');
    }

    public function sendVerification()
    {
        $user = Auth::user();

        if ($user['is_verified']) {
            return redirect('/home');
        }

        $user->update(['verification_code' => ""]);
        $code = rand(111111, 999999);
        $user->update(['verification_code' => $code]);

        $result = self::sendVerificationCode($user);

        if (!empty($result['error'])) {
            
            if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                $user->delete();
                $message = "The server detected an invalid email. Account has been removed. Try to register again with valid email.";
            } else {
                $message = "Try again by logging in your registered account.";
            }

            Auth::logout();
            Session::flush();

            return redirect('/login')->with('error', "Server error occurred. We are unable to send the verification code. {$message}");
        }

        return redirect('/verification');
    }

    public static function sendVerificationCode($user = [])
    {
        $brevo = new Brevo();
        $name = "{$user['first_name']} {$user['last_name']}";
        $from = config('mail')['from']['name'];
        $htmlContent = "";
        $htmlContent .= "Hi {$name}, <br /> <br /> Verify your account using this code: <b>{$user['verification_code']}</b>. <br /> <br />";
        $htmlContent .= "If you did not performed any actions related to this. Login to your account and secure your profile by changing your password, or contact the administrator for help.<br /><br />";
        $htmlContent .= "Regards, <br /><br /> {$from}";

        $email = $user['email'];
        $subject = "Verify your account";

        $mail = [
            'htmlContent' => $htmlContent,
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'senderEmail' => config('mail')['from']['address'],
            'senderName' => config('mail')['from']['name']
        ];

        $result = $brevo->executeEmailsBatchSend($mail);
        return $result;
    }
}
