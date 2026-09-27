<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Product extends Model
{
    use PreventDemoModeChanges;
    
    protected $guarded = ['choice_attributes'];

    protected $with = ['product_translations', 'taxes', 'thumbnail'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function main_category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function frequently_bought_products()
    {
        return $this->hasMany(FrequentlyBoughtProduct::class);
    }

    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function product_queries()
    {
        return $this->hasMany(ProductQuery::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_products()
    {
        return $this->hasMany(FlashDealProduct::class);
    }

    public function bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Upload::class, 'thumbnail_img');
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }

    public function scopeDigital($query)
    {
        return $query->where('digital', 1);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
    public function scopeIsApprovedPublished($query)
    {
        return $query->where('approved', '1')->where('published', 1);
    }

    public function last_viewed_products()
    {
        return $this->hasMany(LastViewedProduct::class);
    }

    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    public function warrantyNote()
    {
        return $this->belongsTo(Note::class, 'warranty_note_id');
    }

    public function refundNote()
    {
        return $this->belongsTo(Note::class, 'refund_note_id');
    }

    public function shippingNote()
    {
        return $this->belongsTo(Note::class, 'shipping_note_id');
    }

    public function codNote()
    {
        return $this->belongsTo(Note::class, 'delivery_note_id');
    }

    public function customSaleAlerts()
    {
        return $this->hasMany(CustomSaleAlert::class, 'product_id');
    }

    // add gallery image to thumb

   public function thumbnailImg(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            $photos = $attributes['photos'] ?? null;

            if ($photos) {
                $photosArray = explode(',', $photos);
                $count = count($photosArray);

                return $value ?: ($count > 0 ? $photosArray[0] : null);
            }

            return $value;
        });
    }


    protected function videoLink(): Attribute
    {
        return Attribute::make(
           
            get: fn($value) => json_decode($value, true), 

         
            set: function ($value) {
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (is_array($decoded)) {
                        $value = $decoded;
                    } else {
                        $value = array_filter(explode(',', $value));
                    }
                }

                if (!is_array($value)) {
                    return null;
                }

                $filtered = array_filter($value, function ($item) {
                    return trim($item) !== '';
                });

                return empty($filtered) ? null : json_encode($filtered);
            },
        );
    }

    public function getGoogleFeedData()
    {
        return [
            'id' => $this->id,
            'title' => $this->getTranslation('name'),
            'description' => strip_tags($this->getTranslation('description')),
            'link' => route('product', $this->slug),
            'image_link' => uploaded_asset($this->thumbnail_img),
            'price' => $this->unit_price . ' ' . get_setting('currency_symbol'),
            'availability' => $this->stock_quantity > 0 ? 'in stock' : 'out of stock',
            'brand' => $this->brand->name ?? 'No Brand',
            'condition' => 'new',
            'mpn' => $this->sku ?? $this->id,
            'product_type' => $this->main_category->name ?? '',
            'gtin' => $this->gtin ?? '',
        ];
    }

    public function participateProduct()
    {
        return $this->hasMany(SellerAdminPromotionParticipateProduct::class, 'product_id');
    }
}
