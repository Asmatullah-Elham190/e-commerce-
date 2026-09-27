<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class SizeChart extends Model
{
    use PreventDemoModeChanges;

    protected $guarded = [];
    
    public function sizeChartDetails()
    {
        return $this->hasMany(SizeChartDetail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findForProduct($product)
    {
        if ($product->main_category && $product->main_category->sizeChart) {
            return $product->main_category->sizeChart;
        }

        return self::whereNull('category_id')
            ->get()
            ->first(function ($chart) use ($product) {
                $productIds = json_decode($chart->product_ids, true) ?? [];
                return in_array((string) $product->id, $productIds);
            });
    }
}
