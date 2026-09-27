<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
use App\Models\State;
use Auth;
use Log;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } else {
            $address->user_id   = Auth::user()->id;
        }
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = $request->city_id;
        $address->area_id       = $request->area_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = '+' . $request->country_code . $request->phone;
        $address->save();

        flash(translate('Address info Stored successfully'))->success();
        return back();
    }

    public function billing_store(Request $request)
    {
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } else {
            $address->user_id   = Auth::user()->id;
        }
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = $request->city_id;
        $address->area_id       = $request->area_id;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = '+' . $request->country_code . $request->phone;
        $address->set_billing   = 1;
        $address->save();

        flash(translate('Billing Address Stored successfully'))->success();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
        $data['cities'] = City::where('status', 1)
            ->where(get_setting('has_state') == 1 ? 'state_id' : 'country_id', get_setting('has_state') == 1 ? $data['address_data']->state_id : (get_active_countries()->count() == 1 ? get_active_countries()->first()->id : $data['address_data']->country_id))
            ->get();
        //Log::info('Fetched cities:', ['cities' => $data['cities']]);
        $data['areas'] = Area::where('status', 1)->where('city_id', $data['address_data']->city_id)->get();
        $returnHTML = view('frontend.partials.address.address_edit_modal', $data)->render();
        return response()->json(array('data' => $data, 'html' => $returnHTML));
        //        return ;
    }

    public function edit_billing($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
        $data['cities'] = City::where('status', 1)
            ->where(get_setting('has_state') == 1 ? 'state_id' : 'country_id', get_setting('has_state') == 1 ? $data['address_data']->state_id : (get_active_countries()->count() == 1 ? get_active_countries()->first()->id : $data['address_data']->country_id))
            ->get();
        //Log::info('Fetched cities:', ['cities' => $data['cities']]);
        $data['areas'] = Area::where('status', 1)->where('city_id', $data['address_data']->city_id)->get();
        $returnHTML = view('frontend.partials.address.billing_address_edit_modal', $data)->render();
        return response()->json(array('data' => $data, 'html' => $returnHTML));
        //        return ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $address->address       = $request->address;
        if (!$request->state_id && ($request->country_id != $address->country_id)) {
            $address->state_id = null;
        } else {
            $address->state_id = $request->state_id ?? $address->state_id;
        }
        $address->country_id    = $request->country_id;
        $address->city_id       = $request->city_id ?? $address->city_id;
        $address->area_id       = $request->area_id ?? null;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->phone;
        $address->save();
        flash(translate('Address info updated successfully'))->success();
        return back();
    }

    public function billing_update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $address->address       = $request->address;
        if (!$request->state_id && ($request->country_id != $address->country_id)) {
            $address->state_id = null;
        } else {
            $address->state_id = $request->state_id ?? $address->state_id;
        }
        $address->country_id    = $request->country_id;
        $address->city_id       = $request->city_id ?? $address->city_id;
        $address->area_id       = $request->area_id ?? null;
        $address->longitude     = $request->longitude;
        $address->latitude      = $request->latitude;
        $address->postal_code   = $request->postal_code;
        $address->phone         = $request->phone;
        $address->set_billing   = 1;
        $address->save();
        flash(translate('Billing Address updated successfully'))->success();
        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if (!$address->set_default && !$address->set_billing) {
            $address->delete();
            return back();
        }
        flash(translate('Default address cannot be deleted'))->warning();
        return back();
    }

    public function getStates(Request $request)
    {
        $states = State::where('status', 1)->where('country_id', $request->country_id)->get();
        $html = '<option value="">' . translate("Select State") . '</option>';

        foreach ($states as $state) {
            $html .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }

        echo json_encode($html);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('status', 1)->where('state_id', $request->state_id)->get();
        $html = '<option value="">' . translate("Select City") . '</option>';

        foreach ($cities as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
        }

        echo json_encode($html);
    }

    public function getAreas(Request $request)
    {
        $areas = Area::where('status', 1)->where('city_id', $request->city_id)->get();
        if ($areas->isEmpty()) {
            $html = '<option value="" disabled selected>' . translate("Area not available") . '</option>';
        } else {
            $html = '<option value="">' . translate("Select Area") . '</option>';
            foreach ($areas as $row) {
                $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
            }
        }
        echo json_encode($html);
    }

    public function set_default($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_default = 1;
        $address->save();

        return back();
    }

    public function set_billing($id)
    {
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_billing = 0;
            $address->save();
        }
        $address = Address::findOrFail($id);
        $address->set_billing = 1;
        $address->save();

        return back();
    }

    public function getCitiesByCountry(Request $request)
    {
        $cities = City::where('status', 1)->where('country_id', $request->country_id)->get();
        $html = '<option value="">' . translate("Select City") . '</option>';

        foreach ($cities as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
        }

        echo json_encode($html);
    }
}
