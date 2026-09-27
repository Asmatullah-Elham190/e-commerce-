<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductReviewCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            "status" => "success",
            "data" => $this->collection->map(function ($data) {
                return [
                    "product_id"        => (int) $data->product_id,
                    "product_name"      => $data->product_name,
                    "slug"              => $data->product_slug,
                    "product_thumbnail" => uploaded_asset($data->product_thumbnail_img),
                    "rating"            => (float) $data->rating,
                    "total_reviews"     => (int) $data->total_reviews,
                    "new_reviews_count" => (int) $data->new_reviews_count,
                    "has_new_review"    => (bool) $data->has_new_review,
                ];
            }),
        ];
    }
}