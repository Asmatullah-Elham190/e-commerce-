<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminPromotionParticipate extends Model
{
    protected $guarded = [];

    public function promotion()
    {
        return $this->belongsTo(SellerAdminPromotion::class, 'seller_admin_promotion_id');
    }

    public function products()
    {
        return $this->hasMany(SellerAdminPromotionParticipateProduct::class, 'seller_admin_promotion_participate_id');
    }
}