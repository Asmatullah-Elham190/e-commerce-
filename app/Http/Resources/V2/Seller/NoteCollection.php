<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                =>(int) $this->id,
            'note'              =>$this->description
        ];
    }
}