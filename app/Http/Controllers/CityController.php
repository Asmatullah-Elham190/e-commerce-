<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_cities'])->only('index', 'create', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $sort_country = $request->sort_country;
        $cities_queries = City::query();
        // if (get_setting('has_state') == 1) {
        //     $cities_queries->whereHas('state', function ($q) {
        //         $q->where('status', 1);
        //     });
        // } else {
            $cities_queries->whereHas('country', function ($q) {
                $q->where('status', 1);
            });
        
        if ($request->sort_city) {
            $cities_queries->where('name', 'like', "%$sort_city%");
        }
        if ($request->sort_state) {
            $cities_queries->where('state_id', $request->sort_state);
        }
        if ($request->sort_country) {
            $cities_queries->where('country_id', $request->sort_country);
        }
        $cities = $cities_queries->orderBy('created_at', 'desc')->paginate(15);
        $states = State::where('status', 1)->get();
        $countries = Country::where('status', 1)->get();
        return view('backend.setup_configurations.cities.index', compact('cities', 'states', 'countries', 'sort_city', 'sort_state', 'sort_country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = new City;

        $city->name = $request->name;
        $city->cost = $request->cost;
        $city->status = 0;
        $city->state_id = $request->state_id ?? null;
        $city->country_id = $request->country_id ? $request->country_id : State::findOrFail($request->state_id)->country_id;
        $city->save();

        flash(translate('City has been inserted successfully'))->success();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang  = $request->lang;
        $city  = City::findOrFail($id);
        $states = State::where('status', 1)->get();
        $countries = Country::where('status', 1)->get();
        return view('backend.setup_configurations.cities.edit', compact('city', 'lang', 'states', 'countries'));
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
        $city = City::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $city->name = $request->name;
        }
        //if request country changed , state should null if $request->country_id is not null and $request->state_id is null
        if ($request->country_id && !$request->state_id && $request->country_id != $city->country_id) {
            $city->state_id = null;
        } else {
            $city->state_id = $request->state_id ?? $city->state_id;
        }
        $city->country_id = $request->country_id ? $request->country_id  : State::findOrFail($city->state_id)->country_id;
        $city->cost = $request->cost;

        $city->save();

        $city_translation = CityTranslation::firstOrNew(['lang' => $request->lang, 'city_id' => $city->id]);
        $city_translation->name = $request->name;
        $city_translation->save();

        flash(translate('City has been updated successfully'))->success();
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
        $city = City::findOrFail($id);
        $city->city_translations()->delete();
        City::destroy($id);

        flash(translate('City has been deleted successfully'))->success();
        return redirect()->route('cities.index');
    }

    public function updateStatus(Request $request)
    {
        $city = City::findOrFail($request->id);

        $city->status = $request->status;
        $city->save();
        if (!$city->status) {
            foreach ($city->areas as $area) {
                $area->status = 0;
                $area->save();
            }
        }
        return 1;
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get(['id', 'name']);
        return response()->json($cities);
    }

    public function getCitiesByCountry(Request $request)
    {
        $cities = City::where('country_id', $request->country_id)->where('status', 1)->get(['id', 'name']);
        return response()->json($cities);
    }
}