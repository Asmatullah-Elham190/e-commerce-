<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use App\Models\CustomSaleAlert;
use App\Models\FlashDeal;
use App\Models\Product;
use Artisan;
use Illuminate\Http\Request;

class CustomSaleAlertController extends Controller
{
    public function index()
    {
        $products = Product::isApprovedPublished()->where('auction_product', 0)->orderBy('created_at', 'desc')->get();
        return view('backend.marketing.custom_sale_alert.index', compact('products'));
    }

    public function products(Request $request)
    {
        $product_ids = $request->product_ids;
        return view('backend.marketing.custom_sale_alert.edit', compact('product_ids'));
    }


    public function products_update(Request $request)
    {
         $settings = [
            'show_custom_product_sale_alert' => BusinessSetting::where('type', 'show_custom_product_sale_alert')->first(),
            'sale_alert_min_time' => BusinessSetting::where('type', 'sale_alert_min_time')->first(),
            'sale_alert_max_time' => BusinessSetting::where('type', 'sale_alert_max_time')->first()
        ];

        foreach ($settings as $key => $setting) {
            if ($setting) {
                $setting->value = $request->has($key) ? $request->input($key) : 0;
                $setting->save();
            }else{
                $newSetting = new BusinessSetting();
                $newSetting->type = $key;
                $newSetting->value = $request->has($key) ? $request->input($key) : 0;
                $newSetting->save();
            }
        }
        CustomSaleAlert::truncate();

        if ($request->has('products')) {
            $data = [];
            foreach ($request->products as $productId) {
                $data[] = [
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            CustomSaleAlert::insert($data);
        }

        Artisan::call('cache:clear');
        flash(translate("Custom Product Visitors settings updated successfully"))->success();
        return redirect()->route('custom-sale-alerts.index');

    }
}
