<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\Requirement;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class RequirementsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['visible'] = $data['visible'] ?? '';
        $data['deleted'] = $data['deleted'] ?? '';

        $data['requirements'] = Requirement::when(!empty($data['deleted']), function($query){
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


        if (!empty($data['requirements']->total())) {
            $data['requirements'] = $data['requirements']->appends($request->except('page'));
        }

        return view('requirements.index', compact('data'));
    }

    public function manage(Request $request)
    {
        $data = $request->all();

        $id = $data['id'] ?? '';
        $requirement = [];

        if (!empty($id)) {
            $requirement = Requirement::find($id);

            if (empty($requirement)) {
                return redirect('/requirements')->with('error', "Selected record not found.");
            }

            $requirement['file_type'] = explode(',', $requirement['file_type']);
        }

        return view('requirements.manage', compact('data', 'requirement'));
    }

    public function loadFileTypes(Request $request)
    {
        $data = $request->all();
        $type = $data['type'] ?? '';

        if (empty($type)) {
            return response()->json(['error' => "Select a file type."]);
        }

        $html = view('requirements.file_types', compact('data'))->render();

        return response()->json(['html' => $html]);
    }

    public function save(Request $request)
    {

        $rules = [
            'label' => 'required|string',
            'description' => 'nullable|string',
            'required' => 'required',
            'type' => ['required', Rule::in(['text', 'number', 'document', 'image'])],
        ];

        $type = $request->input('type');
        $hasFile = $request->hasFile('sample');
        $fileTypes = $request->input('file_type');
        if (!empty($fileTypes)) {
            $fileTypes = implode(',', $fileTypes);
        }

        $customRule = [];

        if (in_array($type, ['image', 'document'])) {
            $customRule = [ 'file_type' => 'required|array' ];
        }

        if ($type == 'image' && $hasFile) {
            $customRule = [ 'sample' =>  'nullable|image|max:2056|mimes:'.$fileTypes];
        }

        $rules = array_merge($rules, $customRule);

        $messages = [
            'required' => ':attribute is required.',
            'file_type.array' => 'You must select a file type.',
            'image.max' => 'Maximum file size to upload is 2MB.',
            'image.mimes' => 'Invalid image file type.',
        ];

        $error  = Validator::make($request->all(), $rules, $messages);

        if ($error->fails()) {
            return redirect()->back()->with('errors', $error->errors())->withInput();
        }

        $data = $request->all();
        unset($data['_token']);
        $data['file_type'] = $fileTypes;
        $data['required'] = ($data['required'] == 'yes') ?? false;

        if (empty($data['id'])) {
            unset($data['id']);
        }

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        $data = array_merge($data, $dataToMerge);
            
        try {

            if ($hasFile) {
                $filenameWithExtension = $request->file('sample')->getClientOriginalName();
                $file = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('sample')->getClientOriginalExtension();
                $toStore = $file.'_'.time().'.'.$extension;
                
                // Upload image
                $path = $request->file('sample')->storeAs('public/requirements', $toStore);
            } else {
                $toStore    = null;
            }

            $data['sample'] = $toStore;

            if(isset($data['id'])) {
                $query = Requirement::where('id', $data['id']);
                $requirement = $query->first();

                $filex   = 'public/requirements/'.$requirement['sample'];
                if (Storage::exists($filex)) {
                    Storage::delete($filex);   
                }

                $query->update($data);
                $message = AppHelper::updated();

            } else {

                $requirement = Requirement::create($data);
                $message = AppHelper::saved();
            }
            
            return redirect('/requirements/manage?id='.$requirement['id'])->with('success', $message);

        } catch(QueryException $e) {
            return redirect()->back()->with('errors', $e->errorInfo[2]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $data = Requirement::find($id);

        // Delete file also
        if (!empty($data['sample'])) {
            $filex   = 'public/requirements/'.$data['sample'];
            if (Storage::exists($filex)) {
                Storage::delete($filex);   
            }
        }

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

    public function visibility(Requirement $requirement)
    {
        if ($requirement['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $requirement->update(['visible' => $status]);

        return redirect('/requirements')->with('success', AppHelper::updated());
    }

    public function restore($id)
    {
        $requirement = Requirement::withTrashed()->where('id', $id)->restore();
        return redirect('/requirements')->with('success', AppHelper::restored());
    }
}
