<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminPromotionParticipateProduct extends Model
{
    protected $guarded = [];

    public function participate()
    {
        return $this->belongsTo(SellerAdminPromotionParticipate::class, 'seller_admin_promotion_participate_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}