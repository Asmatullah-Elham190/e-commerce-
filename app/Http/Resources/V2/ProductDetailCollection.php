<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Review;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\MeasurementPoint;
use App\Models\Unit;

class ProductDetailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $precision = 2;
                $calculable_price = home_discounted_base_price($data, false);
                $calculable_price = number_format($calculable_price, $precision, '.', '');
                $calculable_price = floatval($calculable_price);
                // $calculable_price = round($calculable_price, 2);
                $photo_paths = get_images_path($data->photos);
                $short_video_paths = $data->short_video ? get_videos_path($data->short_video) : [];
                $short_video_thumbnail_paths = $data->short_video_thumbnail ? get_images_path($data->short_video_thumbnail) : [];
                $photos = [];
                $videos = [];
                $warranty = null;
                if ($data->has_warranty == 1 && $data->warranty_id != null) {
                    $warranty = [
                        'warranty_logo' => uploaded_asset($data->warranty->logo),
                        'title'          => $data->warranty->getTranslation('text'),
                        'note'        => ($data->warranty_note_id != null && $data->show_warranty_note == 1) ? $data->warrantyNote->getTranslation('description') : null,
                    ];
                }

                $refund = null;
                $shipping_note = null;
                if($data->show_shipping_note==1 && $data->shipping_note_id!=null){
                    $shipping_note = [
                        'note' => $data->shippingNote->getTranslation('description'),
                    ];
                }

                $delivery_note = null;
                if($data->show_delivery_notes==1 && $data->delivery_note_id ){
                    $delivery_note = [
                        'note' => $data->codNote->getTranslation('description'),
                    ];
                }

                if (addon_is_activated('refund_request') && $data->refundable == 1) {
                    $refund_sticker = get_setting('refund_sticker');

                    $refund = [
                        'is_refundable' => true,
                        'sticker' => $refund_sticker ? uploaded_asset($refund_sticker) : static_asset('/assets/img/cashback-gurantee-logo.svg'),
                        'show_notes' => (bool) $data->show_refund_notes,
                        'note' => ($data->refund_note_id != null && $data->show_refund_notes == 1) ? $data->refundNote->getTranslation('description') : null,
                        'policy_url' => route('returnpolicy'),
                    ];
                }

                $size_chart = null;

                if ($data->main_category && $data->main_category->sizeChart) {

                    $chart = $data->main_category->sizeChart;

                    $measurementPoints = MeasurementPoint::whereIn(
                        'id',
                        json_decode($chart->measurement_points, true)
                    )->get();

                    $size_options = AttributeValue::selectRaw('id,value')
                        ->whereIn('id', json_decode($chart->size_options, true))
                        ->get();

                    $columns = $size_options->pluck('value')->values()->toArray();

                    $rows = [];

                    foreach ($size_options as $size_option) {

                        $inch = [];
                        $cm = [];

                        foreach ($measurementPoints as $measurementPoint) {

                            $detail = $chart->sizeChartDetails
                                ->where('measurement_point_id', $measurementPoint->id)
                                ->where('attribute_value_id', $size_option->id)
                                ->first();

                            $inch[$measurementPoint->name] = $detail->inch_value ?? null;

                            $cm[$measurementPoint->name] = $detail->cen_value ?? null;
                        }

                        $rows[] = [
                            'measurement' => $size_option->value,
                            'inch' => $inch,
                            'cm' => $cm,
                        ];
                    }

                    $size_chart = [
                        'title' => $chart->name,

                        'fit_type' => $chart->fit_type,

                        'stretch_type' => $chart->stretch_type,

                        'description' => $chart->description,

                        'columns' => $columns,

                        'rows' => $rows,
                    ];
                }

                if (!empty($photo_paths)) {
                    for ($i = 0; $i < count($photo_paths); $i++) {
                        if ($photo_paths[$i] != "") {
                            $item = array();
                            $item['variant'] = "";
                            $item['path'] = $photo_paths[$i];
                            $photos[] = $item;
                        }
                    }
                }

                if (!empty($short_video_paths)) {
                    for ($i = 0; $i < count($short_video_paths); $i++) {
                        if ($short_video_paths[$i] != "") {
                            $item = array();
                            $item['path'] = $short_video_paths[$i];
                            $item['thumbnail'] = $short_video_thumbnail_paths[$i] ?? "";
                            $videos[] = $item;
                        }
                    }
                }

                foreach ($data->stocks as $stockItem) {
                    if ($stockItem->image != null && $stockItem->image != "") {
                        $item = array();
                        $item['variant'] = $stockItem->variant;
                        $item['path'] = uploaded_asset($stockItem->image);
                        $photos[] = $item;
                    }
                }

                $brand = [
                    'id' => 0,
                    'name' => "",
                    'slug' => "",
                    'logo' => "",
                ];

                if ($data->brand != null) {
                    $brand = [
                        'id' => $data->brand->id,
                        'slug' => $data->brand->slug,
                        'name' => $data->brand->getTranslation('name'),
                        'logo' => uploaded_asset($data->brand->logo),
                    ];
                }

                $unit_name = Unit::where('id', $data->unit)->value('name');
                $whole_sale = [];
                if (addon_is_activated('wholesale')) {
                    $whole_sale =  ProductWholesaleResource::collection($data->stocks->first()->wholesalePrices);
                }
                return [
                    'id' => (int)$data->id,
                    'name' => $data->getTranslation('name'),
                    'added_by' => $data->added_by,
                    'seller_id' => $data->user->id,
                    'shop_id' => $data->added_by == 'admin' ? 0 : $data->user->shop->id,
                    'shop_slug' => $data->added_by == 'admin' ? '' : $data->user->shop->slug,
                    'shop_name' => $data->added_by == 'admin' ? translate('In House Product') : $data->user->shop->name,
                    'shop_logo' => $data->added_by == 'admin' ? uploaded_asset((json_decode(get_setting('business_info'), true) ?? [])['shop_logo'] ?? null) : (uploaded_asset($data->user->shop->logo) ?? ""),                    'photos' => $photos,
                    'thumbnail_image' => uploaded_asset($data->thumbnail_img),
                    'tags' => explode(',', $data->tags),
                    'price_high_low' => (float)explode('-', home_discounted_base_price($data, false))[0] == (float)explode('-', home_discounted_price($data, false))[1] ? format_price((float)explode('-', home_discounted_price($data, false))[0]) : "From " . format_price((float)explode('-', home_discounted_price($data, false))[0]) . " to " . format_price((float)explode('-', home_discounted_price($data, false))[1]),
                    'choice_options' => $this->convertToChoiceOptions(json_decode($data->choice_options)),
                    'colors' => collect(json_decode($data->colors) ?? [])->map(function ($color) {
                            return [
                                'code' => $color,
                                'name' => get_single_color_name($color),
                            ];
                        })->values(),
                    'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                    'discount' => "-" . discount_in_percentage($data) . "%",
                    // 'stroked_price' => home_base_price($data),
                    'stroked_price' => home_price($data),
                    'main_price' => home_discounted_base_price($data),
                    'calculable_price' => $calculable_price,
                    'currency_symbol' => currency_symbol(),
                    'current_stock' => (int)$data->stocks->first()->qty,
                    'unit' => $unit_name,
                    'rating' => (float)$data->rating,
                    'rating_count' => (int)Review::where(['product_id' => $data->id])->count(),
                    'earn_point' => (float)$data->earn_point,
                    'description' => $data->getTranslation('description'),
                    'downloads' => $data->pdf ? uploaded_asset($data->pdf) : null,
                    'video_link' => $data->video_link != null ?  $data->video_link : [],
                    'brand' => $brand,
                    'link' => route('product', $data->slug),
                    'wholesale' => $whole_sale,
                    'est_shipping_time' => (int)$data->est_shipping_days,
                    'videos' => $videos,
                    'size_chart' => $size_chart,
                    'min_viewer' => (int) get_setting('min_custom_product_visitors'),
                    'max_viewer' => (int) get_setting('max_custom_product_visitors'),
                    'todays_deal' => (int)$data->todays_deal,
                    'flash_sale' =>  get_product_active_flash_deal_end_date($data->id, $data->discount_end_date),
                    'cod'  => (int)$data->cash_on_delivery,
                    'free_shipping' => $data->shipping_type == 'free' ? true : false,
                    'warranty' => $warranty,
                    'refund' => $refund,
                    'shipping_note' => $shipping_note,
                    'delivery_note' => $delivery_note,
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

    protected function convertToChoiceOptions($data)
    {
        $result = array();
        if ($data) {
            foreach ($data as $key => $choice) {
                $item['name'] = $choice->attribute_id;
                $item['title'] = Attribute::find($choice->attribute_id)->getTranslation('name');
                $item['options'] = $choice->values;
                array_push($result, $item);
            }
        }
        return $result;
    }

    protected function convertPhotos($data)
    {
        $result = array();
        foreach ($data as $key => $item) {
            array_push($result, uploaded_asset($item));
        }
        return $result;
    }
}