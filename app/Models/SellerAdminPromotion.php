<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminPromotion extends Model
{
    protected $table = 'seller_admin_promotions';

    protected $guarded = [];

    public function flashSale()
    {
        return $this->belongsTo(FlashDeal::class, 'flash_sale_id');
    }

    public function sellers()
    {
        return $this->belongsToMany(
            User::class,
            'seller_admin_promotion_sellers',
            'seller_admin_promotion_id',
            'seller_id'
        )->withTimestamps();
    }

        public function respondedSellers()
    {
        return $this->sellers()->wherePivot('responded', 1);
    }

    public function getPromoTypeLabelAttribute()
    {
        if ($this->flash_sale_id) {
            return translate('FLASH DEALS');
        } elseif ($this->todays_deal) {
            return translate("Today's Deal");
        } elseif ($this->featured_products) {
            return translate('Featured Products');
        }
        return '';
    }

    public function participates()
    {
        return $this->hasMany(SellerAdminPromotionParticipate::class, 'seller_admin_promotion_id');
    }

    public static function activeForSeller($sellerId, $type)
    {
        $query = self::whereHas('sellers', function ($q) use ($sellerId) {
            $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
        });

        if ($type === 'todays_deal') {
            $query->where('todays_deal', 1);
        } elseif ($type === 'featured_products') {
            $query->where('featured_products', 1);
        } elseif ($type === 'flash_sale') {
            $query->whereNotNull('flash_sale_id');
        }

        return $query->latest()->first();
    }
}