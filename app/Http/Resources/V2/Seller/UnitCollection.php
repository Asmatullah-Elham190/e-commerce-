<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>(int) $this->id,
            'name'              =>$this->name
        ];
    }
}