<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['user_type'] = $data['user_type'] ?? '';
        $data['keyword'] = $data['keyword'] ?? '';
        $data['verified'] = $data['verified'] ?? '';
        $data['active'] = $data['active'] ?? '';

        $users = User::when(!empty($data['user_type']), function($query) use ($data){
                        $query->where('user_type', $data['user_type']);
                    })
                    ->when(!empty($data['verified']) && $data['verified'] == 'yes', function($query){
                        $query->where('is_verified', true);
                    })
                    ->when(!empty($data['verified']) && $data['verified'] == 'no', function($query){
                        $query->where('is_verified', false);
                    })
                    ->when(!empty($data['active']) && $data['active'] == 'yes', function($query){
                        $query->where('is_active', true);
                    })
                    ->when(!empty($data['active']) && $data['active'] == 'no', function($query){
                        $query->where('is_active', false);
                    })
                    ->when(!empty($data['keyword']), function($query) use ($data){
                        $query->where('last_name', 'LIKE', "%{$data['keyword']}%")
                            ->orWhere('first_name', 'LIKE', "%{$data['keyword']}%")
                            ->orWhere('middle_name', 'LIKE', "%{$data['keyword']}%")
                            ->orWhere('email', 'LIKE', "%{$data['keyword']}%");
                    })
                    ->orderBy('last_name', 'DESC')
                    ->paginate(100);
        
        $data['verified'] = $request->input('verified') ?? "";
        return view('users.index', compact('users', 'data'));
    }

    public function delete(User $user)
    {
        try {
            $user->forceDelete();
            return redirect('/users')->with('success', 'User has been permanently deleted.');
        } catch (\Throwable $th) {
            return redirect('/users?'.time())->with('error', 'You are not allowed to delete this.');
        }
    }

    public function update(User $user)
    {
        if ($user->is_active) {
            $isActive = false;
            $status = 'deactivated';
        } else {
            $isActive = true;
            $status = 'activated';
        }
        $user->update(['is_active' => $isActive]);

        return redirect('/users')->with('success', "User's account has been {$status}.");
    }

    public function verification(User $user)
    {
        if ($user->is_verified) {
            $isVerified = false;
            $status = 'updated to unverified status';
        } else {
            $isVerified = true;
            $status = 'verified';
        }
        $user->update(['is_verified' => $isVerified]);

        return redirect('/users')->with('success', "User's account has been {$status}.");
    }

    public function updateUserType(Request $request, User $user)
    {
        $data = $request->all();
        $type = $data['user_type'] ?? '';

        if (empty($type)) {
            return redirect('/users')->with('error', "Invalid server request.");
        }

        $user->update(['user_type' => $type]);

        return redirect('/users')->with('success', "User's account has been updated.");
    }
}
