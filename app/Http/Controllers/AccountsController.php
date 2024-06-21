<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\User;
use App\Models\StudentInformation;
use App\Http\Requests\AccountRequest;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $info = StudentInformation::where('user_id', $userId)->first();

        if (empty($info)) {
            return redirect('/personal-information')->with('info', 'Complete your personal information first.');
        }

        if (!$info['step1'] || !$info['step2'] || !$info['completed']) {

            $message = 'Some personal information is still missing. Complete your account information first.';

            if (!$info['step1']) {
                return redirect('/personal-information')->with('error', $message);
            } elseif (!$info['step2']) {
                return redirect('/address')->with('error', $message);
            } else {
                return redirect('/additional-information')->with('error', $message);
            }
            
        }

        return view('frontend.profile.index', compact('info'));
    }

    public function account(Request $request)
    {
        return view('frontend.profile.account');
    }

    public function update(AccountRequest $request)
    {
        $userId = Auth::user()->id;
        $validated = $request->validated();

        User::where('id', $userId)
            ->update($validated);

        return redirect('/profile')->with('success', "Your account has been updated.");
    }

    public function info(Request $request)
    {
        $userId = Auth::user()->id;
        $info = StudentInformation::where('user_id', $userId)->first();

        return view('frontend.profile.personal-information', compact('info'));
    }

    public function address(Request $request)
    {
        $userId = Auth::user()->id;
        $info = StudentInformation::where('user_id', $userId)->first();

        return view('frontend.profile.address', compact('info'));
    }

    public function additionalInformation(Request $request)
    {
        $userId = Auth::user()->id;
        $info = StudentInformation::where('user_id', $userId)->first();

        return view('frontend.profile.additional-information', compact('info'));
    }

    public function save(Request $request)
    {
        $userId = Auth::user()->id;
        $data = $request->all();

        $dataToMerge = [
            'user_id' => $userId
        ];

        $data = array_merge($data, $dataToMerge);

        $info = StudentInformation::where('user_id', $userId)->first();

        try {

            $step = $data['step'];

            if ($data['step'] == "1") {
                $redirect = '/address';
            } elseif ($data['step'] == "2") {
                $data['step2'] = true;
                $redirect = '/additional-information';
            } else {
                $data['completed'] = true;
                $redirect = '/profile';
            }

            unset($data['_token'], $data['step']);

            if(!empty($info)) {
                $info->update($data);
            } else {
                $data['step1'] = true;
                StudentInformation::create($data);
            }

            if ($step == '3') {
                return redirect($redirect)->with('success', 'You have completed your personal information!');
            }

            return redirect($redirect);

        } catch(QueryException $e) {
            return response()->json(['error' => $e->errorInfo[2]]);
        }
    }

    public function verifyEmail(Request $request)
    {
        $data = $request->all();
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => "Invalid email!"]);
        }

        return response()->json(['success' => true]);
    }
}
