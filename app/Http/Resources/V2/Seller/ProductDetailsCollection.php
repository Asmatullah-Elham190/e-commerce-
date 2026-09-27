<?php

namespace App\Http\Resources\V2\Seller;

use App\Http\Resources\V2\UploadedFileCollection;
use App\Models\Unit;
use App\Models\Upload;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $unit_name = Unit::where('id', $this->unit)->value('name');
        if(addon_is_activated('refund_request')){
            $refundable = $this->refundable;
            $show_refund_notes = $this->show_refund_notes;
            $refund_note_id = $this->refund_note_id;
        }
        $warranty_id  = $this->warranty_id;
        $show_warranty_note = $this->show_warranty_note;
        $warranty_note_id = $this->warranty_note_id;
        $cash_on_delivery = $this->cash_on_delivery;
        $show_delivery_notes = $this->show_delivery_notes;
        $delivery_note_id = $this->delivery_note_id;
        $shipping_type = $this->shipping_type;
        $est_shipping_days = $this->est_shipping_days;
        $flat_shipping_cost = $this->flat_shipping_cost;
        $is_quantity_multiplied = $this->is_quantity_multiplied;
        $show_estimated_shipping_time = $this->show_estimated_shipping_time;
        $show_shipping_note = $this->show_shipping_note;
        $shipping_note_id = $this->shipping_note_id;
        $frequently_bought_selection_type = $this->frequently_bought_selection_type;

        $fq_bought_products = [];
        $fq_bought_product_category = null;

        $frequently_bought_rows = \App\Models\FrequentlyBoughtProduct::where('product_id', $this->id)->get();

        if ($frequently_bought_selection_type == 'product') {
            $product_ids = $frequently_bought_rows
                ->pluck('frequently_bought_product_id')
                ->filter()
                ->values()
                ->toArray();

            $fq_bought_products = \App\Models\Product::whereIn('id', $product_ids)
                ->get(['id', 'name', 'category_id', 'thumbnail_img'])
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->getTranslation('name', $this->lang ?? env('DEFAULT_LANGUAGE')),
                        'catgeory' => $product->main_category->name,
                        'thumnail_img' => uploaded_asset($product->thumbnail_img)
                    ];
                })
                ->toArray();

        } elseif ($frequently_bought_selection_type == 'category') {
            $category_id = optional($frequently_bought_rows->first())->category_id;

            if ($category_id) {
                $category = \App\Models\Category::where('id', $category_id)->first(['id', 'name']);
                if ($category) {
                    $fq_bought_product_category = [
                        'id' => $category->id,
                        'name' => $category->getTranslation('name', $this->lang ?? env('DEFAULT_LANGUAGE')),
                    ];
                }
            }
        }

        return [
            "id" => $this->id,
            'lang'          => $this->lang,
            'product_name'  => $this->getTranslation('name', $this->lang),
            'product_unit'  => $unit_name,
            'description'   => $this->getTranslation('description', $this->lang),
            "category_id" => $this->category_id,
            "category_ids" => $this->categories()->pluck('category_id')->toArray(),
            "brand_id" => $this->brand_id,
            "photos" => new UploadedFileCollection(Upload::whereIn("id", explode(",", $this->photos))->get()),
            "thumbnail_img" => new UploadedFileCollection(Upload::whereIn("id", explode(",", $this->thumbnail_img))->get()),
            "short_video_thumbnail" => uploaded_asset($this->short_video_thumbnail),
            "short_video" => new UploadedFileCollection(Upload::whereIn("id", explode(",", $this->short_video))->get()),
            "video_link" => $this->video_link,
            "tags" => $this->tags,
            "unit_price" => $this->unit_price,
            "purchase_price" => $this->purchase_price,
            "variant_product" => $this->variant_product,
            "attributes" => json_decode($this->attributes),
            "choice_options" => json_decode($this->choice_options),
            "colors" => json_decode($this->colors),
            "variations" => $this->variations,
            "stocks" =>  new StockCollection($this->stocks),
            "todays_deal" => $this->todays_deal,
            "published" => $this->published,
            "approved" => $this->approved,
            "refundable" => $refundable,
            "show_refund_notes" => $show_refund_notes,
            "refund_note_id" => $refund_note_id,
            "warranty_id"  => $warranty_id,
            "show_warranty_note" => $show_warranty_note,
            "warranty_note_id" => $warranty_note_id,
            "cash_on_delivery" => $cash_on_delivery,
            "show_delivery_notes" => $show_delivery_notes,
            "delivery_note_id" => $delivery_note_id,
            "shipping_type" => $shipping_type,
            "est_shipping_days" => $est_shipping_days,
            "flat_shipping_cost" => $flat_shipping_cost,
            "is_quantity_multiplied" => $is_quantity_multiplied,
            "show_estimated_shipping_time" => $show_estimated_shipping_time,
            "show_shipping_note" => $show_shipping_note,
            "shipping_note_id" => $shipping_note_id,
            "frequently_bought_selection_type" => $frequently_bought_selection_type,
            "fq_bought_products" => $fq_bought_products,               
            "fq_bought_product_category" => $fq_bought_product_category, 
            "stock_visibility_state" => $this->stock_visibility_state,
            "featured" => $this->featured,
            "seller_featured" => $this->seller_featured,
            "current_stock" => $this->current_stock,
            "weight" => $this->weight,
            "min_qty" => $this->min_qty,
            "low_stock_quantity" => $this->low_stock_quantity,
            "discount" => $this->discount,
            "discount_type" => $this->discount_type,
            "discount_start_date" => date("Y-m-d", $this->discount_start_date),
            "discount_end_date" => date("Y-m-d", $this->discount_end_date),
            "tax" => $this->taxes,
            "tax_type" => $this->tax_type,
            "shipping_cost" => $this->shipping_cost,
            "num_of_sale" => $this->num_of_sale,
            "meta_title" => $this->meta_title,
            "meta_description" => $this->meta_description,
            "meta_img" => new UploadedFileCollection(Upload::where("id", $this->meta_img)->get()),
            "pdf" => new UploadedFileCollection(Upload::whereIn("id", explode(",", $this->pdf))->get()),
            "slug" => $this->slug,
            "rating" => $this->rating,
            "barcode" => $this->barcode,
            "digital" => $this->digital,
            "auction_product" => $this->auction_product,
            "file_name" => $this->file_name,
            "file_path" => $this->file_path,
            "external_link" => $this->external_link,
            "external_link_btn" => $this->external_link_btn,
            "wholesale_product" => $this->wholesale_product,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }


    public function with($request)
    {
        return [
            'result' => true,
            'status' => 200
        ];
    }
}
