<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaTranslation;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Country;
use App\Models\State;

class AreaController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_cities'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
    {
        $sort_area = $request->sort_area;
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $sort_country = $request->sort_country;

        $area_queries = Area::whereHas('city', function ($q) {
            $q->where('status', 1);
             });

        if ($sort_country) {
            $area_queries->whereHas('city.country', function ($q) use ($sort_country) {
                $q->where('id', $sort_country);
            });
        }

        if ($sort_state) {
            $area_queries->whereHas('city', function ($q) use ($sort_state) {
                $q->where('state_id', $sort_state);
            });
        }
        

        if ($sort_city) {
            $area_queries->where('city_id', $sort_city);
        }

        if ($sort_area) {
            $area_queries->where('name', 'like', "%$sort_area%");
        }

        $areas = $area_queries->orderBy('created_at', 'desc')->paginate(15);
        $cities = $sort_state? City::where('state_id', $sort_state)->get(): collect();
        $states = State::where('status', 1)
            ->whereHas('cities', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        $countries = Country::where('status', 1)
            ->whereHas('cities', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        return view('backend.setup_configurations.areas.index', compact(
            'areas', 'cities', 'states', 'countries', 'sort_city', 'sort_state', 'sort_area', 'sort_country'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $area = new Area;

        $area->name = $request->name;
        $area->cost = $request->cost;
        $area->status = 0;
        $area->city_id = $request->city_id;
        $area->save();

        flash(translate('Area has been inserted successfully'))->success();

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
        $area  = Area::findOrFail($id);
        $states = State::where('status', 1)->whereHas('cities', function ($query) {
                $query->where('status', 1);
            })->get();
        $countries = Country::where('status', 1)->whereHas('cities', function ($query) {
                $query->where('status', 1);
            })->get();

        if(get_setting('has_state') == 1) {
            $cities = City::where('state_id', $area->city->state_id ?? null)
                ->where('status', 1)
                ->get();
        } else {
            $cities = City::where('country_id', $area->city->country_id ?? null)
                ->where('status', 1)
                ->get();
        }

        return view('backend.setup_configurations.areas.edit', compact('area', 'lang', 'states', 'cities', 'countries'));
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
       $area = Area::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $area->name = $request->name;
        }

        $area->city_id = $request->city_id;
        $area->cost = $request->cost;

        $area->save();

        $area_translation = AreaTranslation::firstOrNew(['lang' => $request->lang, 'area_id' => $area->id]);
        $area_translation->name = $request->name;
        $area_translation->save();

        flash(translate('Area has been updated successfully'))->success();
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
        $area = Area::findOrFail($id);
        $area->area_translations()->delete();
        Area::destroy($id);

        flash(translate('Area has been deleted successfully'))->success();
        return redirect()->route('areas.index');
    }

    public function updateStatus(Request $request){
        $area = Area::findOrFail($request->id);
        $area->status = $request->status;
        $area->save();

        return 1;
    }
}
