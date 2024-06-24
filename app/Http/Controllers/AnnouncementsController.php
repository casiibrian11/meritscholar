<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\Announcement;
use App\Http\Requests\AnnouncementsRequest;

class AnnouncementsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        $data['announcements'] = Announcement::orderBy('created_at','DESC')->paginate(10);

        if (!empty($data['announcements']->total())) {
            $data['announcements'] = $data['announcements']->appends($request->except('page'));
        }

        return view('announcements.index', compact('data'));
    }

    public function manage(Request $request)
    {
        $data = $request->all();

        $data['announcement'] = [];
        if (!empty($data['id'])) {
            $data['announcement'] = Announcement::where('id', $data['id'])->first();

            if (empty($data['announcement'])) {
                return redirect('/announcements')->with('error', 'Selected record not found');
            }
        }

        return view('announcements.manage', compact('data'));
    }

    public function save(AnnouncementsRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($validated, $dataToMerge);
        try {

            if(isset($key['id'])) {
                $data['updated_at'] = now();
                Announcement::where('id', $key['id'])->update($data);
                $message = AppHelper::updated();
                
            } else {
                $data['created_at'] = now();
                $data['updated_at'] = null;
                Announcement::create($data);
                $message = AppHelper::saved();
                
            }
            
            return response()->json([
                'success' => $message,
            ]);

        } catch(QueryException $e) {
            return response()->json(['error' => $e->errorInfo[2]]);
        }
    }

    public function visibility(Announcement $announcement)
    {
        if ($announcement['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $announcement->update(['visible' => $status]);

        return redirect('/announcements')->with('success', AppHelper::updated());
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $data = Announcement::find($id);

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

    public function announcements(Request $request)
    {
        $announcements = Announcement::where('visible', true)->orderBy('created_at', 'DESC')->get();
        return view('frontend.announcements', compact('announcements'));
    }
}

