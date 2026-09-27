<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingBoxSizeRequest;
use App\Models\ShippingBoxSize;
use App\Utility\ShippingBoxSizeUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Redirect;

class ShippingBoxSizeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:shipping_box_size_index'])->only('index');
        $this->middleware(['permission:shipping_box_size_create'])->only('create');
        $this->middleware(['permission:shipping_box_size_create'])->only('store');
        $this->middleware(['permission:shipping_box_size_edit'])->only('edit');
        $this->middleware(['permission:shipping_box_size_edit'])->only('update');
        $this->middleware(['permission:shipping_box_size_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $shipping_box_size_tabs = ['All Box Sizes'];
        $shipping_box_sizes = ShippingBoxSize::orderBy('id', 'desc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $shipping_box_sizes-> $shipping_box_sizes->where('courier_type', 'like', '%' . $sort_search . '%');
        }
        $shipping_box_sizes = $shipping_box_sizes->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.setup_configurations.box_size.index', compact('shipping_box_sizes', 'sort_search', 'shipping_box_size_tabs'));
    }

    public function create()
    {
        return view('backend.setup_configurations.box_size.create');
    }

    public function store(ShippingBoxSizeRequest $request)
    {
        $user_id = Auth::id();

        $box_size = new ShippingBoxSize();
        $box_size->courier_type   = $request->courier_type;
        $box_size->length   = $request->length;
        $box_size->height   = $request->height;
        $box_size->breadth  = $request->breadth;
        $box_size->user_id  = $user_id;
        $box_size->save();

        return response()->json([
            'success' => true,
            'message' => translate('Box Sizes has been inserted successfully'),
            'redirect' => route('shipping_box_size.index')
        ]);
    }

    public function edit($id)
    {
        $box_size  = ShippingBoxSize::findOrFail($id);
        return view('backend.setup_configurations.box_size.edit', compact('box_size'));
    }

    public function update(ShippingBoxSizeRequest $request, $id)
    {
        $box_size = ShippingBoxSize::findOrFail($id);
        $box_size->length   = $request->length;
        $box_size->height   = $request->height;
        $box_size->breadth  = $request->breadth;
        $box_size->courier_type  = $request->courier_type;
        $box_size->save();

        return response()->json([
            'success' => true,
            'message' => translate('Box Sizes has been updated successfully'),
            'redirect' => route('shipping_box_size.index')
        ]);
    }

    public function destroy($id)
    {
        $box_size = ShippingBoxSize::findOrFail($id);
        ShippingBoxSizeUtility::delete_shipping_box_size($id);
        return 1;
    }

    public function getBoxSizes(Request $request)
    {
        $user_id = $request->user_id;
        $shipping_system = $request->shipping_system;

        $query = ShippingBoxSize::where('user_id', $user_id);

        if ($shipping_system === 'shiprocket') {
            $query->where('courier_type', 'shiprocket');
        }

        $box = $query->get();

        return response()->json($box);
    }

    public function bulk_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $shipping_box_size_id) {
                $shipping_box_size = ShippingBoxSize::findOrFail($shipping_box_size_id);
                if (!$shipping_box_size) {
                    continue;
                }

                ShippingBoxSizeUtility::delete_shipping_box_size($shipping_box_size_id);
            }
            return 1;
        }
    }

    public function filter(Request $request)
    {
        //Log::info('Filter Shipping Box Sizes Request: ', $request->all());
        $shipping_box_sizes = ShippingBoxSize::orderBy('id', 'desc');
        $sort_search = null;

        if ($request->search != null) {
            $sort_search = $request->search;
            $shipping_box_sizes = $shipping_box_sizes->where('courier_type', 'like', '%' . $sort_search . '%');
        }

        $shipping_box_sizes = $shipping_box_sizes->paginate(15);
        $view = view(
            'backend.setup_configurations.box_size.table',
            compact('shipping_box_sizes', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }
}
