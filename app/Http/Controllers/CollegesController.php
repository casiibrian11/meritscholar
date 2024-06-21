<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\College;
use App\Http\Requests\CollegesRequest;

class CollegesController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['visible'] = $data['visible'] ?? '';
        $data['deleted'] = $data['deleted'] ?? '';

        $colleges = College::when(!empty($data['visible']) && $data['visible'] == 'yes', function($query){
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

        if (!empty($colleges->total())) {
            $colleges = $colleges->appends($request->except('page'));
        }

        return view('colleges.index', compact('colleges', 'data'));
    }

    public function save(CollegesRequest $request)
    {
        $input = $request->safe()->only(['college_code','college_name']);

        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($validated, $dataToMerge);

        try {

            if(isset($key['id'])) {

                College::where('id', $key['id'])->update($data);
                $message = AppHelper::updated();
                
            } else {

                College::create($data);
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
        $data = College::find($id);
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

    public function visibility(College $college)
    {
        if ($college['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $college->update(['visible' => $status]);

        return redirect('/colleges')->with('success', AppHelper::updated());
    }

    public function restore($id)
    {
        $college = College::withTrashed()->where('id', $id)->restore();
        return redirect('/colleges')->with('success', AppHelper::restored());
    }
}
