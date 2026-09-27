<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementPoint;
use App\Http\Requests\MeasurementPointRequest;

class MeasurementPointsController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:view_measurement_points'])->only('index');
        $this->middleware(['permission:edit_measurement_points'])->only('get_measurement_point');
        $this->middleware(['permission:delete_measurement_points'])->only('destroy');
    }

    public function index(Request $request) {
        $sort_search = null;
        $measurement_point_tabs = ['All Measurement Points'];
        $measurementPoints = MeasurementPoint::orderBy('created_at', 'desc');

        if ($request->search != null){
            $sort_search = $request->search;
            $measurementPoints = $measurementPoints->where('name', 'like', '%'.$request->search.'%');
        }

        $measurementPoints = $measurementPoints->paginate(15);
        return view('backend.product.measurementPoints.index', compact('measurementPoints', 'sort_search', 'measurement_point_tabs'));
    }

    public function filter(Request $request)
    {
        $measurementPoints = MeasurementPoint::orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $measurementPoints = $measurementPoints->where(function ($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%');
            });
        }

        $measurementPoints = $measurementPoints->paginate(15);
        $view = view(
            'backend.product.measurementPoints.table',
            compact('measurementPoints', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create(Request $request) {
        return view('backend.product.measurementPoints.create');
    }

    public function store(MeasurementPointRequest $request)
    {
        MeasurementPoint::create($request->only([
            'name'
        ]));

        return 1;
    }

    public function edit(Request $request)
    {
        $measurementPoint = MeasurementPoint::findOrFail($request->id);
        return view('backend.product.measurementPoints.edit', compact('measurementPoint'));
    }

    public function update(MeasurementPointRequest $request)
    {
        $measurementPoint = MeasurementPoint::findOrFail($request->id);
        $measurementPoint->name = $request->name;
        
        $measurementPoint->save();

        return 1;
    }

    public function destroy($id)
    {
        $measurementPoint = MeasurementPoint::findOrFail($id);
        $measurementPoint = MeasurementPoint::where('id', $id)->first();

        if (!is_null($measurementPoint)) {
            $measurementPoint->delete();
        }
        
        return 1;
    }

    public function bulk_measurement_points_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $measurementPoint_id) {
                MeasurementPoint::destroy($measurementPoint_id);
            }

            return 1;
        }

        return 0;
    }

    public function admin_ajax_add_measurement_point_modal(Request $request)
    {
        return view('backend.product.measurementPoints.ajax_add_measurement_point_modal');
    }

    public function admin_ajax_add_measurement_point_store(Request $request)
    {
        $measurement_point = MeasurementPoint::create([
            'name' => $request->measurement_point_name
        ]);

        return response()->json([
            'success'        => true,
            'message'        => translate('Measurement Point has been inserted successfully'),
            'measurement_point_id'    => $measurement_point->id,
            'measurement_point_name'  => $measurement_point->name,
        ]);
    }
}
