<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

use App\Libraries\AppHelper;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\ScholarshipOffer;
use App\Http\Requests\ScholarshipOffersRequest;


class ManageScholarshipsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $data['active'] = $data['active'] ?? '';
        $data['deleted'] = $data['deleted'] ?? '';

        $data['scholarship_offers'] = ScholarshipOffer::when(!empty($data['deleted']), function($query){
                                                $query->onlyTrashed();
                                            })
                                            ->when(!empty($data['active']) && $data['active'] == 'yes', function($query){
                                                $query->where('active', true);
                                            })
                                            ->when(!empty($data['active']) && $data['active'] == 'no', function($query){
                                                $query->where('active', false);
                                            })
                                            ->orderBy('id', 'DESC')
                                            ->paginate(10);

        if (!empty($data['scholarship_offers']->total())) {
            $data['scholarship_offers'] = $data['scholarship_offers']->appends($request->except('page'));
        }

        return view('scholarships.manage.index', compact('data'));
    }

    public function manage(Request $request)
    {
        $data = $request->all();

        $data['scholarships'] = Scholarship::orderBy('id', 'DESC')->get();
        $data['school_years'] = SchoolYear::where('visible', true)->orderBy('id', 'DESC')->get();

        return view('scholarships.manage.manage', compact('data'));
    }

    public function save(ScholarshipOffersRequest $request)
    {
        $key = $request->safe()->only(['id']);
        $validated = $request->safe()->except(['id']);

        $dataToMerge = [
            'user_id' => Auth::user()->id
        ];

        if (!empty($validated['date_from']) && !empty($validated['date_to'])) {
            $from = $validated['date_from'];
            $to = $validated['date_to'];
            
            if (now()->parse($to)->lt($from)) {
                return response()->json([
                    'error' => "End date must be later than start date.",
                ]);
            }
        }

        $validated['active'] = ($validated['active'] == 'true') ?? false;
        $data = array_merge($validated, $dataToMerge);

        try {

            if(isset($key['id'])) {
                
                ScholarshipOffer::where('id', (int)$key['id'])->update($data);
                $offer['id'] = $key['id'];
                $message = AppHelper::updated();
            } else {
                $offer = ScholarshipOffer::create($data);
                $message = AppHelper::saved();
            }
            
            return response()->json([
                'success' => $message,
                'id' => $offer['id']
            ]);

        } catch(QueryException $e) {
            return response()->json(['error' => $e->errorInfo[2]]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $data = ScholarshipOffer::find($id);

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

    public function visibility(Scholarship $scholarship)
    {
        if ($scholarship['visible']) {
            $status = false;
        } else {
            $status = true;
        }

        $scholarship->update(['visible' => $status]);

        return redirect('/scholarships')->with('success', AppHelper::updated());
    }

    public function restore($id)
    {
        $scholarship = Scholarship::withTrashed()->where('id', $id)->restore();
        return redirect('/scholarships')->with('success', AppHelper::restored());
    }

    function loadScholarships(Request $request)
    {
        $data = $request->all();
        $data['sy_id'] = $data['sy_id'] ?? '';

        $query = ScholarshipOffer::where('sy_id', $data['sy_id']);
        $ids = $query->pluck('scholarship_id')->toArray();
        $data['offers'] = $query->with('scholarships')->get();

        $data['scholarships'] = Scholarship::where('visible', true)
                                    ->whereNotIn('id', $ids)
                                    ->orderBy('id', 'DESC')->get();

        $html = view('scholarships.manage.list', compact('data'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }
}
