<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\SchoolYear;
use App\Http\Requests\SchoolYearsRequest;

class SchoolYearsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['visible'] = $data['visible'] ?? '';
        $data['deleted'] = $data['deleted'] ?? '';

        $data['school_years'] = SchoolYear::when(!empty($data['deleted']), function($query){
                                    $query->onlyTrashed();
                                })
                                ->when(!empty($data['visible']) && $data['visible'] == 'yes', function($query){
                                    $query->where('visible', true);
                                })
                                ->when(!empty($data['visible']) && $data['visible'] == 'no', function($query){
                                    $query->where('visible', false);
                                })
                                ->orderBy('id', 'DESC')
                                ->paginate(10); 


        if (!empty($data['school_years']->total())) {
            $data['school_years'] = $data['school_years']->appends($request->except('page'));
        }

        return view('sy.index', compact('data'));
    }

    public function save(SchoolYearsRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $validated['start_year'] = (int)$validated['start_year'];
        $validated['end_year'] = ($validated['start_year'] + 1);

        $data = array_merge($validated, $dataToMerge);

        $sy = SchoolYear::where('start_year', $data['start_year'])
                            ->where('end_year', $data['end_year'])
                            ->where('semester', $data['semester'])
                            ->first();
            
        try {

            if(isset($key['id'])) {
                
                if (!empty($sy) && $sy['id'] <> $key['id']) {
                    return response()->json(['error' => AppHelper::exists()]);
                }
                SchoolYear::where('id', $key['id'])->update($data);
                $message = AppHelper::updated();
                
            } else {

                if (!empty($sy)) {
                    return response()->json(['error' => AppHelper::exists()]);
                }

                SchoolYear::create($data);
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
        $data = SchoolYear::find($id);

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

    public function visibility(SchoolYear $sy)
    {
        if ($sy['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $sy->update(['visible' => $status]);

        return redirect('/sy')->with('success', AppHelper::updated());
    }

    public function restore($id)
    {
        $sy = SchoolYear::withTrashed()->where('id', $id)->restore();
        return redirect('/sy')->with('success', AppHelper::restored());
    }
}
