<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductReviewDetailsCollection extends ResourceCollection
{
    protected $product;

    public function __construct($resource, $product)
    {
        parent::__construct($resource);
        $this->product = $product;
    }

    public function toArray($request)
    {
        $reviews = $this->collection->map(function ($review) {
            return [
                "id"              => (int) $review->id,
                "customer_name"   => $review->user_name,
                "customer_avatar" => $review->avatar_original ? uploaded_asset($review->avatar_original) : null,
                "rating"          => (int) $review->rating,
                "comment"         => $review->comment,
                "images"          => $review->photos
                                        ? array_map(fn($p) => uploaded_asset($p), explode(',', $review->photos))
                                        : [],
                "published_at"    => date('j F, Y', strtotime($review->created_at)),
                "status"          => $review->status == 1 ? 'published' : 'unpublished',
            ];
        });

        return [
            "status" => "success",
            "data" => [
                "product_info" => [
                    "product_id"        => $this->product->id,
                    "slug"              => $this->product->slug,
                    "product_name"      => $this->product->getTranslation('name'),
                    "product_thumbnail" => uploaded_asset($this->product->thumbnail_img),
                    "average_rating"    => (float) $this->product->rating,
                    "total_reviews"     => $this->product->reviews()->count(),
                ],
                "reviews" => $reviews,
            ],
            "meta" => [
                "current_page" => $this->resource->currentPage(),
                "last_page"    => $this->resource->lastPage(),
                "per_page"     => $this->resource->perPage(),
                "total"        => $this->resource->total(),
            ],
        ];
    }
}