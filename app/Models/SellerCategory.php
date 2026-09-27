<?php

namespace App\Models;

use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCategory extends Model
{
    use HasFactory;
    use PreventDemoModeChanges;
    protected $fillable = [
        'seller_id',
        'category_id',
        'discount',
        'discount_start_date',
        'discount_end_date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
