<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_unit'])->only('index');
        $this->middleware(['permission:edit_unit'])->only('get_measurement_point');
        $this->middleware(['permission:delete_unit'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $unit_tabs = ['All Units'];
        $units = Unit::orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $units = $units->where('name', 'like', '%' . $sort_search . '%');
        }
        $units = $units->paginate(15);
        return view('backend.product.units.index', compact('sort_search', 'unit_tabs', 'units'));
    }

    public function create(Request $request)
    {
        return view('backend.product.units.create');
    }

    public function filter(Request $request)
    {
        $units = Unit::orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $units = $units->where(function ($query) use ($sort_search) {
                $query->where('name', 'like', '%' . $sort_search . '%');
            });
        }

        $units = $units->paginate(15);
        $view = view(
            'backend.product.units.table',
            compact('units', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function store(Request $request)
    {
        Unit::create([
            'name' => $request->name
        ]);

        return 1;
    }

    public function edit(Request $request)
    {
        $unit = Unit::findOrFail($request->id);
        return view('backend.product.units.edit', compact('unit'));
    }

    public function update(Request $request)
    {
        $unit = Unit::findOrFail($request->id);
        $unit->name = $request->name;
        $unit->save();
        return 1;
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        $unit = Unit::where('id', $id)->first();
        if (!is_null($unit)) {
            $unit->delete();
        }
        return 1;
    }

    public function bulk_unit_delete(Request $request)
    {
        if ($request->id) {

            foreach ($request->id as $unit_id) {
                Unit::destroy($unit_id);
            }

            return 1;
        }

        return 0;
    }

    public function admin_ajax_add_unit_modal(Request $request)
    {
        return view('backend.product.units.ajax_add_unit_modal');
    }

    public function admin_ajax_add_unit_store(Request $request)
    {
        $unit = Unit::create([
            'name' => $request->unit_name
        ]);

        return response()->json([
            'success'        => true,
            'message'        => translate('Unit has been inserted successfully'),
            'unit_id'    => $unit->id,
            'unit_name'  => $unit->name,
        ]);
    }
}
