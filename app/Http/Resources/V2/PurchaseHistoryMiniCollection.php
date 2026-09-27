<?php

namespace App\Http\Resources\V2;

use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseHistoryMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'code' => $data->code,
                    'user_id' => intval($data->user_id),
                    'payment_type' => ucwords(str_replace('_', ' ', $data->payment_type)),
                    'payment_status' => translate($data->payment_status),
                    'payment_status_string' => ucwords(str_replace('_', ' ', translate($data->payment_status))),
                    'delivery_status' => translate($data->delivery_status),
                    'delivery_status_string' => $data->delivery_status == translate('pending') ? translate("Order Placed") : ucwords(str_replace('_', ' ',  translate($data->delivery_status))),
                    'grand_total' => format_price(convert_price($data->grand_total)),
                    'date' => Carbon::createFromTimestamp($data->date)->format('d-m-Y'),
                    'shop_name' => $data->shop->name ?? $data->seller->name,
                    'shop_logo' => $data->shop?->logo
                        ? uploaded_asset($data->shop->logo)
                        : (
                            $data->seller?->avatar_original
                                ? uploaded_asset($data->seller->avatar_original)
                                : null
                        ),

                    'links' => [
                        'details' => ''
                    ],

                    'items' => $data->orderDetails->map(function ($item) {

                        return [
                            'product_id' => $item->product_id,

                            'product_name' => $item->product?->name,

                            'product_thumbnail_image' => $item->product?->thumbnail_img
                                ? uploaded_asset($item->product->thumbnail_img)
                                : null,

                            'price' => single_price($item->price),
                            'qty' => $item->quantity,
                            // 'is_reviewed' => $item->reviewed ?? 0,
                            'is_reviewed' => Review::where('user_id', $item->order->user_id)->where('product_id', $item->product_id)->exists() ? 1 : 0,
                            'attributes' => $item->variation,
                        ];
                    })->values()
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
