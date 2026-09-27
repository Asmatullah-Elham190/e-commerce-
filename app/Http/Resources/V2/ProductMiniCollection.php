<?php

namespace App\Http\Resources\V2;

use App\Models\CustomLabel;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $wholesale_product =
                    ($data->wholesale_product == 1) ? true : false;
                $customLabels = [];
                if (!empty($data->custom_label_id)) {
                    $labelIds = explode(',', $data->custom_label_id);
                    $customLabels = CustomLabel::whereIn('id', $labelIds)
                        ->get()
                        ->map(function ($label) {
                            return [
                                'id'   => $label->id,
                                'text' => $label->text,
                                'background_color' => $label->background_color,
                                'text_color' => $label->text_color,
                            ];
                        })->toArray();
                }
                return [
                    'id' => $data->id,
                    'slug' => $data->slug,
                    'name' => $data->getTranslation('name'),
                    'custom_labels'    => $customLabels,
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'discount' => "-" . discount_in_percentage($data) . "%",
                    'stroked_price' => home_base_price($data),
                    'main_price' => home_discounted_base_price($data),
                    'rating' => (float) $data->rating,
                    'review_count' => $data->reviews->count(),
                    'sales' => (int) $data->num_of_sale,
                    'is_wholesale' => $wholesale_product,
                    'links' => [
                        'details' => route('products.show', $data->id),
                    ]
                ];
            })
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
