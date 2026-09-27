<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\SellerAdminPromotionParticipateProduct;
use App\Services\ProductService;
use Illuminate\Http\Request;

class PromotionalProductController extends Controller
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
        $product_types = ['Promotional Product List'];
        return view('backend.promotion_and_offers.index', compact('seller_type', 'categories', 'product_types'));
    }

    public function seller_index(Request $request)
    {
        $seller_type = '';
        $product_types = [];
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        $product_types = ['Seller Promotional Product List'];
        return view('backend.promotion_and_offers.seller_index', compact('seller_type', 'categories', 'product_types'));
    }

    public function dashboard(Request $request)
    {
        $totalProducts = Product::where('approved', 1)->where('published', 1)->count();
        $promotionalProducts = Product::where('auction_product', 0)->where('wholesale_product', 0)->where('promotional', 1)->count();

        $totalFlashDeals = FlashDeal::count();
        $activeFlashDeals = FlashDeal::where('status', '1')->count();

        $todaysDeal = Product::where('promotional', 1)->where('todays_deal', 1)->count();

        $all_categories = Category::count();
        $main_categories = Category::where('parent_id', 0)->count();

        $totalCoupons = Coupon::count();
        $today = strtotime(date('d-m-Y'));

        $activeCoupons = Coupon::where(function ($query) use ($today) {
            $query->where(function ($q) use ($today) {
                $q->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->where('type', '!=', 'welcome_base');
            })
            ->orWhere(function ($q) {
                $q->where('type', 'welcome_base')
                ->where('status', 1);
            });
        })->count();

        return view('backend.promotion_and_offers.dashboard', compact(
            'totalProducts',
            'promotionalProducts',
            'totalFlashDeals',
            'activeFlashDeals',
            'todaysDeal',
            'totalCoupons',
            'activeCoupons',
            'all_categories',
            'main_categories'
        ));
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
                ->update(['promotional' => 1]);
        }

        $uncheckedIds = array_diff($allIds, $checkedIds);
        if (!empty($uncheckedIds)) {
            Product::whereIn('id', $uncheckedIds)
                ->update(['promotional' => 0, 'todays_deal' => 0]);

            FlashDealProduct::whereIn('product_id', $uncheckedIds)->delete();
        }

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $promotional = 1;
        $products = $this->productService->promotional_products_search($request->except(['_token']), $promotional);
        $single_select = $request->single_select ?? 0;
        return view('backend.promotion_and_offers.products_search', compact('products', 'single_select', 'promotional'));
    }

    public function filter(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $products = Product::where('auction_product', 0)->where('wholesale_product', 0)->where('promotional', 1);
        if ($request->product_type == 'drafts') {
            $products = $products->where('draft', 1)->where('added_by', 'admin');
        } else {
            $products = $products->where('draft', 0);
            if ($request->seller_type == 'admin') {
                $products = $products->where('added_by', 'admin');
            } elseif ($request->seller_type == 'seller') {
                $products = $products->where('added_by', 'seller');
                if ($request->user_id != null) {
                    $products = $products->where('user_id', $request->user_id);
                }
            }
            if ($request->product_type != 'drafts') {
                if ($request->product_type == 'digital_products') {
                    $products = $products->where('digital', 1);
                } else if ($request->product_type == 'physical_products') {
                    $products = $products->where('digital', 0);
                } else if ($request->product_type == 'not_approved') {
                    $products = $products->where('approved', 0);
                } else if ($request->product_type == 'pos_product_list') {
                    $products = $products->where('pos', 1);
                } else if ($request->product_type == 'promotional_product_list') {
                    $products = $products->where('promotional', 1);
                } else if ($request->product_type == 'todays_deal_product_list') {
                    $products = $products->where('todays_deal', 1);
                }
            }
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $filters = $request->selected_filter ?? [];
        if (!empty($filters)) {
            if (in_array('low-stock', $filters)) {
                $products->where(function ($query) {
                    $query->whereRaw("
                        (
                            SELECT CASE
                                WHEN products.variant_product = 1 
                                    THEN (SELECT SUM(qty) FROM product_stocks WHERE product_stocks.product_id = products.id)
                                ELSE 
                                    (SELECT qty FROM product_stocks WHERE product_stocks.product_id = products.id LIMIT 1)
                            END
                        ) <= products.low_stock_quantity
                    ");
                });
            }
            if (in_array('all-discount', $filters)) {
                $products->where('discount', '>', 0);
            }
            if (in_array('all-publish', $filters)) {
                $products->where('published', 1);
            }
            if (in_array('refundable', $filters)) {
                $products->where('refundable', 1);
            }
        }
        if ($request->filled('brand_id')) {
            $products = $products->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $products = $products->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->category_id);
            });
        }

        if (in_array($request->product_type, ['promotional_product_list', 'todays_deal_product_list'])) {
            $products = $products->orderBy('updated_at', 'desc')->paginate(15);
        } else {
            $products = $products->orderBy('created_at', 'desc')->paginate(15);
        }

        $type = $request->seller_type;
        $ptoduct_type = $request->product_type;

        $view = view(
            'backend.promotion_and_offers.filter',
            compact('products', 'type', 'col_name', 'query', 'sort_search', 'ptoduct_type')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function seller_filter(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'seller')
            ->where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->whereHas('participateProduct');

        if ($request->product_type == 'drafts') {
            $products = $products->where('draft', 1)->where('added_by', 'seller');
        } else {
            $products = $products->where('draft', 0);

            if ($request->product_type == 'digital_products') {
                $products = $products->where('digital', 1);
            } else if ($request->product_type == 'physical_products') {
                $products = $products->where('digital', 0);
            } else if ($request->product_type == 'not_approved') {
                $products = $products->where('approved', 0);
            } else if ($request->product_type == 'pos_product_list') {
                $products = $products->where('pos', 1);
            } else if ($request->product_type == 'todays_deal_product_list') {
                $products = $products->whereHas('participateProduct', function ($q) {
                    $q->where('todays_deal', 1);
                });
            }
        }

        if ($request->filled('user_id')) {
            $products = $products->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $sort_search = $request->search;
            $products = $products->where(function ($q) use ($sort_search) {
                $q->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($sq) use ($sort_search) {
                    $sq->where('sku', 'like', '%' . $sort_search . '%');
                });
            });
        }

        $filters = $request->selected_filter ?? [];
        if (!empty($filters)) {
            if (in_array('low-stock', $filters)) {
                $products->where(function ($query) {
                    $query->whereRaw("
                        (
                            SELECT CASE
                                WHEN products.variant_product = 1 
                                    THEN (SELECT SUM(qty) FROM product_stocks WHERE product_stocks.product_id = products.id)
                                ELSE 
                                    (SELECT qty FROM product_stocks WHERE product_stocks.product_id = products.id LIMIT 1)
                            END
                        ) <= products.low_stock_quantity
                    ");
                });
            }
            if (in_array('all-discount', $filters)) {
                $products->where('discount', '>', 0);
            }
            if (in_array('all-publish', $filters)) {
                $products->where('published', 1);
            }
            if (in_array('refundable', $filters)) {
                $products->where('refundable', 1);
            }
        }

        if ($request->filled('brand_id')) {
            $products = $products->where('brand_id', $request->brand_id);
        }
        if ($request->filled('category_id')) {
            $products = $products->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->category_id);
            });
        }

        if ($request->filled('type')) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
        } else {
            if (in_array($request->product_type, ['seller_promotional_product_list', 'todays_deal_product_list'])) {
                $products = $products->orderBy('updated_at', 'desc');
            } else {
                $products = $products->orderBy('created_at', 'desc');
            }
        }

        $products = $products->paginate(15);

        $productIds = $products->pluck('id');
        $participateProducts = SellerAdminPromotionParticipateProduct::whereIn('product_id', $productIds)
            ->get()
            ->groupBy('product_id')
            ->map(function ($rows) {
                return (object) [
                    'todays_deal' => $rows->contains(fn($r) => $r->todays_deal == 1),
                    'featured'    => $rows->contains(fn($r) => $r->featured == 1),
                    'flash_sale'  => $rows->contains(fn($r) => $r->flash_sale == 1),
                    'discount'    => optional($rows->firstWhere('flash_sale', 1))->discount,
                ];
            });

        $type = $request->seller_type;
        $ptoduct_type = $request->product_type;

        $view = view(
            'backend.promotion_and_offers.seller_filter',
            compact('products', 'type', 'col_name', 'query', 'sort_search', 'ptoduct_type', 'participateProducts')
        )->render();

        return response()->json(['html' => $view]);
    }
}
