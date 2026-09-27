<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ProductService;

class TodaysDealController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $product_types = [];
        $seller_type = '';
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        $product_types = ['Todays Deal Product List'];
        $sellers = User::where('user_type', 'seller')->get();
        return view('backend.promotion_and_offers.todays_deal.index', compact('seller_type', 'categories', 'product_types', 'sellers'));
    }

    public function update(Request $request)
    {
        $allIds = $request->all_ids ?? [];
        $checkedIds = $request->checked_ids ?? [];

        if (empty($allIds)) {
            return response()->json(['success' => false], 400);
        }

        if (!empty($checkedIds)) {
            Product::whereIn('id', $checkedIds)
                ->update(['todays_deal' => 1]);
        }

        $uncheckedIds = array_diff($allIds, $checkedIds);
        if (!empty($uncheckedIds)) {
            Product::whereIn('id', $uncheckedIds)
                ->update(['todays_deal' => 0]);
        }

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $todays_deal = 1;
        $products = $this->productService->todays_deal_products_search($request->except(['_token']), $todays_deal);
        $single_select = $request->single_select ?? 0;
        return view('backend.promotion_and_offers.todays_deal.products_search', compact('products', 'single_select', 'todays_deal'));
    }
}
