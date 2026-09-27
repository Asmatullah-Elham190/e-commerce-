<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class PreorderCoupon extends Model
{
    use HasFactory,PreventDemoModeChanges;


    public function preorder_product(){
        return $this->belongsTo(PreorderProduct::class,'preorder_product_id');
    }
}
