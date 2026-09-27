<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'todays_deal'       => (bool) $this->todays_deal,
            'featured_products' => (bool) $this->featured_products,
            'flash_sale_id'     => $this->flash_sale_id,
            'flash_sale_title'  => $this->flashSale?->getTranslation('title'),
            'promo_type_label'  => $this->promo_type_label ?? null,
            'message'           => $this->message,
            'created_at'        => $this->created_at?->format('M d Y, h:i a'),
        ];
    }
}