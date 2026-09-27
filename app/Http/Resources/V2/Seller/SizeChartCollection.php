<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class SizeChartCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status' => 'success',
            "message"=> "Size charts fetched successfully",
            'data' => [
                'id'                =>(int) $this->id,
                'title'             =>$this->name,
                'added_by'          =>$this->user->name
            ],
        ];
    }
}