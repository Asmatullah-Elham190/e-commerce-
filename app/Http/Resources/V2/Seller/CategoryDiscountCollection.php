<?php

namespace App\Http\Resources\V2\Seller;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryDiscountCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            "status" => "success",
            "data" => $this->collection->map(function ($category) {
                $discount   = $category->sellerDiscount;
                $parent     = $category->parent_id ? Category::find($category->parent_id) : null;

                return [
                    "category_id"           => $category->id,
                    "name"                  => $category->getTranslation('name'),
                    "icon"                  => $category->icon ? uploaded_asset($category->icon) : null,
                    "parent_category_id"    => $category->parent_id ?: null,
                    "parent_category_name"  => $parent ? $parent->getTranslation('name') : null,
                    "is_digital"            => (string) $category->digital,
                    "discount"              => $discount ? (float) $discount->discount : 0.0,
                    "discount_type"         => "percent",
                    "discount_start_date"   => $discount && $discount->discount_start_date
                                                    ? date('Y-m-d H:i:s', $discount->discount_start_date)
                                                    : null,
                    "discount_end_date"     => $discount && $discount->discount_end_date
                                                    ? date('Y-m-d H:i:s', $discount->discount_end_date)
                                                    : null,
                ];
            }),
            "meta" => [
                "current_page" => $this->currentPage(),
                "last_page"    => $this->lastPage(),
                "per_page"     => $this->perPage(),
                "total"        => $this->total(),
            ],
        ];
    }
}