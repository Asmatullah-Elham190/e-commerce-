<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerRequestResource extends JsonResource
{
    public function toArray($request)
    {
        $item = $this->item_label;

        return [
            'id'         => $this->id,
            'item_type'  => $item['type'] ?? null,
            'item_value' => $item['value'] ?? null,
            'message'    => $this->message,
            'created_at' => $this->created_at?->format('M d Y, h:i a'),
        ];
    }
}