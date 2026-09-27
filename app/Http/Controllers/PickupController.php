<?php

namespace App\Http\Controllers;

use App\Http\Requests\PickupAddressRequest;
use App\Models\PickupAddress;
use App\Utility\PickupAddressUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Redirect;

class PickupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:pickup_address_index'])->only('index');
        $this->middleware(['permission:pickup_address_create'])->only('create');
        $this->middleware(['permission:pickup_address_create'])->only('store');
        $this->middleware(['permission:pickup_address_edit'])->only('edit');
        $this->middleware(['permission:pickup_address_edit'])->only('update');
        $this->middleware(['permission:pickup_address_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $pickup_address_tabs = ['All Addresses', 'Active', 'Inactive'];
        $pickup_addresses = PickupAddress::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $pickup_addresses = $pickup_addresses->where('address_nickname', 'courier_type', 'like', '%' . $sort_search . '%');
        }
        $pickup_addresses = $pickup_addresses->paginate(15);
        return view('backend.setup_configurations.pickup.pickup_address_list', compact('pickup_addresses', 'sort_search', 'pickup_address_tabs'));
    }

    public function create()
    {
        return view('backend.setup_configurations.pickup.pickup_address_create');
    }

    public function store(PickupAddressRequest $request)
    {
        $user_id = Auth::id();
       
        $address = new PickupAddress();
        $address->courier_type      = $request->courier_type;
        $address->address_nickname  = $request->address_nickname;
        $address->user_id           = $user_id;
        $address->save();

        return response()->json([
                'success' => true,
                'message' => translate('Pickup Address has been inserted successfully'),
                'redirect' => route('pickup_address.index')
            ]);
    }

    public function edit($id)
    {
        $pickup_address  = PickupAddress::findOrFail($id);
        return view('backend.setup_configurations.pickup.pickup_address_edit', compact('pickup_address'));
    }

    public function update(PickupAddressRequest $request, $id)
    {
        $address = PickupAddress::findOrFail($id);
        $address->courier_type      = $request->courier_type;
        $address->address_nickname  = $request->address_nickname;
        $address->save();

        return response()->json([
                'success' => true,
                'message' => translate('Pickup Address has been updated successfully'),
                'redirect' => route('pickup_address.index')
            ]);
    }

    public function destroy($id)
    {
        $pickup_address = PickupAddress::findOrFail($id);

        PickupAddressUtility::delete_pickup_address($id);
        return 1;
    }

    public function getPickupAddresses(Request $request)
    {
        $user_id = $request->user_id;
        $shipping_system = $request->shipping_system;

        $query = PickupAddress::where('user_id', $user_id)->where('status', 1);

        if ($shipping_system === 'shiprocket') {
            $query->where('courier_type', 'shiprocket');
        }

        $addresses = $query->get();

        return response()->json($addresses);
    }

    public function bulk_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $pickup_address_id) {
                $pickup_address = PickupAddress::findOrFail($pickup_address_id);
                if (!$pickup_address) {
                    continue;
                }

                PickupAddressUtility::delete_pickup_address($pickup_address_id);
            }
            return 1;
        }
    }

    public function filter(Request $request)
    {
        Log::info('Filter Pickup Addresses Request: ', $request->all());
        $pickup_addresses = PickupAddress::orderBy('id', 'desc');
        $sort_search = null;

        if ($request->pickup_address_status == "active") {
            $pickup_addresses = $pickup_addresses->where('status', 1);
        } else if ($request->pickup_address_status == 'inactive') {
            $pickup_addresses = $pickup_addresses->where('status', 0);
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $pickup_addresses = $pickup_addresses->where('address_nickname', 'courier_type', 'like', '%' . $sort_search . '%');
        }

        $pickup_addresses = $pickup_addresses->paginate(15);
        $view = view(
            'backend.setup_configurations.pickup.pickup_address_table',
            compact('pickup_addresses', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function updateStatus(Request $request)
    {
        $pickup_address = PickupAddress::findOrFail($request->id);
        $pickup_address->status = $request->status;
        $pickup_address->save();
        return 1;
    }
}
