<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'is_mine'    => $this->user_id == $request->user()->id,
            'message'    => $this->message,
            'seen'       => (bool) $this->seen,
            'created_at' => $this->created_at?->format('M d Y, h:i a'),
        ];
    }
}