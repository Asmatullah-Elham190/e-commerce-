<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GeneralSettingCollection extends ResourceCollection
{
    public function toArray($request)
    {
       return [
            
           'whatsapp_number' => config('services.whatsapp.number'),
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
