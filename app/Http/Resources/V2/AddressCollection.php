<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {

                $location_available = false;
                $lat = 90.99;
                $lang = 180.99;

                if($data->latitude || $data->longitude) {
                    $location_available = true;
                    $lat = floatval($data->latitude) ;
                    $lang = floatval($data->longitude);
                }

                return [
                    'id'      => (int) $data->id,
                    'user_id' => (int) $data->user_id,
                    'address' => $data->address,
                    'country_id' => (int)  $data->country_id,
                    'state_id' =>  (int) $data->state_id,
                    'city_id' =>  (int) $data->city_id, 
                    'area_id' =>  (int) $data->area_id,                    
                    'country_name' => $data->country->name,
                    'state_name' => optional($data->state)->name,
                    'city_name' => $data->city->name,
                    'area_name' => optional($data->area)->name,
                    'postal_code' => $data->postal_code,
                    'phone' => $data->phone,
                    'set_default' =>(int) $data->set_default,
                    'set_billing' =>(int) $data->set_billing,
                    'location_available' => $location_available,
                    'lat' => $lat,
                    'lang' => $lang,
                    'valid' => $this->isValidAddress($data),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    private function isValidAddress($address)
    {
        $city = optional($address->city);
        $has_area_id = !is_null($address->area_id);
        $city_status = $city->status;
        $active_area_exists = $city->areas()->where('status', 1)->exists();
        $area_status = $has_area_id ? optional($address->area)->status : 1;
        $has_state = get_setting('has_state') == 1;

        $is_disabled = ($city_status === 0) ||
                       ($has_area_id && $area_status === 0) ||
                       ($active_area_exists && !$has_area_id) ||
                       ($has_state && $address->state_id == null);

        return !$is_disabled;
    }
}
