<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {

                $review_image_paths = $data->photos ? get_images_path($data->photos) : [];
                $review_images = [];
                if (!empty($review_image_paths)) {
                    for ($i = 0; $i < count($review_image_paths); $i++) {
                        if ($review_image_paths[$i] != "") {
                            $item = array();
                            $item['path'] = $review_image_paths[$i];
                            $review_images[] = $item;
                        }
                    }
                }
                return [
                    'user_id'   => $data->user_id ? $data->user->id : null,
                    'user_name' => $data->user_id ? $data->user->name : $data->custom_reviewer_name,
                    'avatar'    => $data->user_id ? uploaded_asset($data->user->avatar_original) : '',
                    'images'    => $review_images,
                    'rating'    => floatval(number_format($data->rating, 1, '.', '')),
                    'comment'   => $data->comment,
                    'time'      => $data->updated_at->diffForHumans()
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
