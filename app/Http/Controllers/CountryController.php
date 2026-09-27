<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:shipping_country_setting'])->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_country = $request->sort_country;
        $country_queries = Country::query();
        if($request->sort_country) {
            $country_queries->where('name', 'like', "%$sort_country%");
        }
        $countries = $country_queries->orderBy('status', 'desc')->paginate(15);

        return view('backend.setup_configurations.countries.index', compact('countries', 'sort_country'));
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request){
        $country = Country::findOrFail($request->id);
        $country->status = $request->status;
        if($country->save()){
            if ($request->status == 0) {
                if (get_setting('has_state') == 1){
                   $states = $country->states;
                    foreach ($states as $state) {
                        $state->status = 0;
                        $state->save();
                        foreach ($state->cities as $city) {
                            $city->status = 0;
                            $city->save();
                            foreach ($city->areas as $area) {
                                $area->status = 0;
                                $area->save();
                            }
                        }
                    }
                }else{
                    foreach ($country->cities as $city) {
                            $city->status = 0;
                            $city->save();
                            foreach ($city->areas as $area) {
                                $area->status = 0;
                                $area->save();
                            }
                        }
                }
                
            }

            return 1;
        }
        return 0;
    }
}
