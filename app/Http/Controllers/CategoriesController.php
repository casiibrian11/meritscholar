<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Db;
use Illuminate\Support\Facades\Storage;

use App\Libraries\AppHelper;
use App\Models\Scholarship;
use App\Models\ScholarshipCategory;

use App\Http\Requests\CategoriesRequest;

class CategoriesController extends Controller
{
    public function save(CategoriesRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($validated, $dataToMerge);
        $data['category_name'] = strtolower($data['category_name']);

        $category = ScholarshipCategory::where('category_name', $data['category_name'])->first();
        try {

            if(isset($key['id'])) {
                
                if (!empty($category) && $category['id'] <> $key['id']) {
                    return response()->json(['error' => AppHelper::exists()]);
                }
                ScholarshipCategory::where('id', $key['id'])->update($data);
                $message = AppHelper::updated();
                
            } else {

                if (!empty($category)) {
                    return response()->json(['error' => AppHelper::exists()]);
                }

                $max = ScholarshipCategory::max('sort_number');
                if (empty($max)) {
                    $data['sort_number'] = 1;
                } else {
                    $data['sort_number'] = ($max + 1);
                }

                ScholarshipCategory::create($data);
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
        $data = ScholarshipCategory::find($id);

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

    public function form(Request $request)
    {
        $data = $request->all();

        if (!empty($data['id'])) {
            $data['category'] = ScholarshipCategory::find($data['id']);
        }

        $html = view('scholarships.categories.form', compact('data'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }

    public function sort(Request $request)
    {
        $id = $request->input('id');
        $sort_number = $request->input('sort_number');
        $data = ScholarshipCategory::find($id);
        try {

            $data->update(['sort_number' => $sort_number]);
            
            return response()->json([
                'success' => AppHelper::updated(),
            ]);

        } catch(QueryException $e) {
            return response()->json(['error' => $e->errorInfo[2]]);
        }
    }
}
