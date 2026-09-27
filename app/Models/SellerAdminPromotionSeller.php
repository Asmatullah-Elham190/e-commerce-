<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminPromotionSeller extends Model
{
    protected $table = 'seller_admin_promotion_sellers';

    protected $fillable = ['seller_admin_promotion_id', 'seller_id'];
}