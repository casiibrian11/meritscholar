<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\College;
use App\Models\Course;
use App\Http\Requests\CoursesRequest;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['visible'] = $data['visible'] ?? '';
        $data['deleted'] = $data['deleted'] ?? '';

        $data['courses'] = Course::with('colleges')
                                ->when(!empty($data['visible']) && $data['visible'] == 'yes', function($query){
                                    $query->where('visible', true);
                                })
                                ->when(!empty($data['visible']) && $data['visible'] == 'no', function($query){
                                    $query->where('visible', false);
                                })
                                ->when(!empty($data['deleted']), function($query){
                                    $query->onlyTrashed();
                                })
                                ->orderBy('id', 'DESC')
                                ->paginate(10); 


        $data['colleges'] = College::orderBy('id', 'DESC')->get();

        if (!empty($data['courses']->total())) {
            $data['courses'] = $data['courses']->appends($request->except('page'));
        }

        return view('courses.index', compact('data'));
    }

    public function save(CoursesRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($validated, $dataToMerge);

        try {

            if(isset($key['id'])) {

                Course::where('id', $key['id'])->update($data);
                $message = AppHelper::updated();
                
            } else {

                Course::create($data);
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
        $data = Course::find($id);

        try {
            $data->forceDelete();
            
            return response()->json([
                'success' => AppHelper::deleted(),
            ]);

        } catch(QueryException $e) {
            
            $data->update([ 'deleted_at' => now() ]);

            return response()->json([
                'success' => AppHelper::archived(),
            ]);
        }
    }

    public function visibility(Course $course)
    {
        if ($course['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $course->update(['visible' => $status]);

        return redirect('/courses')->with('success', AppHelper::updated());
    }

    public function restore($id)
    {
        $college = Course::withTrashed()->where('id', $id)->restore();
        return redirect('/courses')->with('success', AppHelper::restored());
    }
}
