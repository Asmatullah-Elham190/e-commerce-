<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAdminNoticeSeller extends Model
{
    protected $table = 'seller_admin_notice_sellers';

    protected $fillable = ['seller_admin_notice_id', 'seller_id', 'seen'];
}