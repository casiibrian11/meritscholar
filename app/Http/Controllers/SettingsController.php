<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Setting;
use App\Libraries\AppHelper;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $query = new Setting();
        $settings = $query->all();

        $set['officeHoursOnly'] = AppHelper::officeHoursOnly();
        $set['pastOfficeHours'] = AppHelper::pastOfficeHours();
        $set['weekendsAllowed'] = AppHelper::weekendsAllowed();
        
        $set['start'] = $query->where('name', 'office_hours_start')->first();
        $set['end'] = $query->where('name', 'office_hours_end')->first();

        return view('settings.index', compact('settings', 'set'));
    }

    public function save(Request $request)
    {
        $data = $request->all();
        $name = $data['name'] ?? '';
        $value = $data['value'] ?? '';
        $data['user_id'] = Auth::user()->id;

        if (empty($name) || empty($value)) {
            return response([
                'error' => 'Invalid request.'
            ]);
        }

        if ($name == 'office_hours_only' || $name == 'allow_weekends') {
            $data['value'] = ($data['value'] == 'true') ?? false;   
        }

        Setting::updateOrCreate([
            'name' => $data['name']
        ], $data);

        return response([
            'success' => 'System settings has been updated'
        ]);
    }
}
