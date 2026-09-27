<?php

namespace App\Http\Controllers\Seller;

use App\Models\Category;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\SellerAdminPromotion;
use App\Models\SellerAdminPromotionParticipate;
use App\Models\SellerAdminPromotionParticipateProduct;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('seller.promotion_and_offers.index', compact('seller_type', 'categories', 'product_types'));
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
        return view('seller.promotion_and_offers.products_search', compact('products', 'single_select', 'promotional'));
    }

    public function filter(Request $request)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $auth_user      = auth()->user()->id;
        $products = Product::where('user_id', $auth_user)->where('auction_product', 0)->where('wholesale_product', 0)->where('promotional', 1);
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

        $sellerId = Auth::id();
        $activeTodaysDealPromotion = SellerAdminPromotion::activeForSeller($sellerId, 'todays_deal');
        $activeFeaturedPromotion   = SellerAdminPromotion::activeForSeller($sellerId, 'featured_products');
        $activeFlashSalePromotion = SellerAdminPromotion::activeForSeller($sellerId, 'flash_sale');


        $participateProductsRaw = SellerAdminPromotionParticipateProduct::whereHas('participate', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->get()
            ->groupBy('product_id');

        $participateProducts = $participateProductsRaw->map(function ($rows) {
            $merged = (object) [
                'todays_deal'   => $rows->contains(fn($r) => $r->todays_deal == 1),
                'featured'      => $rows->contains(fn($r) => $r->featured == 1),
                'flash_sale'    => $rows->contains(fn($r) => $r->flash_sale == 1),
                'discount'      => optional($rows->firstWhere('flash_sale', 1))->discount,
                'flash_sale_id' => optional($rows->firstWhere('flash_sale', 1))->flash_sale_id,
            ];
            return $merged;
        });


        $view = view(
            'seller.promotion_and_offers.filter',
            compact('products', 'type', 'col_name', 'query', 'sort_search', 'ptoduct_type',
            'activeTodaysDealPromotion', 'activeFeaturedPromotion', 'activeFlashSalePromotion', 'participateProducts')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function productSelectModal()
    {
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        return view('seller.promotion_and_offers.product_select_right_canvas', compact('categories'));
    }

    public function markAsTodaysDeal(Request $request)
    {
        $request->validate(['product_id' => 'required|integer|exists:products,id']);

        $sellerId = Auth::id();
        $promotion = SellerAdminPromotion::activeForSeller($sellerId, 'todays_deal');

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => translate('No active promotion found')], 422);
        }

        $participate = SellerAdminPromotionParticipate::firstOrCreate([
            'seller_admin_promotion_id' => $promotion->id,
            'seller_id'                 => $sellerId,
        ]);

        SellerAdminPromotionParticipateProduct::updateOrCreate(
            [
                'seller_admin_promotion_participate_id' => $participate->id,
                'product_id'                             => $request->product_id,
            ],
            ['todays_deal' => 1]
        );

        $promotion->sellers()->updateExistingPivot($sellerId, ['responded' => 1]);

        return response()->json(['success' => true, 'message' => translate('Product marked as Today\'s Deal')]);
    }

    public function markAsFeatured(Request $request)
    {
        $request->validate(['product_id' => 'required|integer|exists:products,id']);

        $sellerId = Auth::id();
        $promotion = SellerAdminPromotion::activeForSeller($sellerId, 'featured_products');

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => translate('No active promotion found')], 422);
        }

        $participate = SellerAdminPromotionParticipate::firstOrCreate([
            'seller_admin_promotion_id' => $promotion->id,
            'seller_id'                 => $sellerId,
        ]);

        SellerAdminPromotionParticipateProduct::updateOrCreate(
            [
                'seller_admin_promotion_participate_id' => $participate->id,
                'product_id'                             => $request->product_id,
            ],
            ['featured' => 1]
        );

        $promotion->sellers()->updateExistingPivot($sellerId, ['responded' => 1]);

        return response()->json(['success' => true, 'message' => translate('Product marked as Featured')]);
    }

    public function flashSaleModal()
    {
        return view('seller.promotion_and_offers.flash_sale_right_canvas');
    }

    public function getActiveFlashSalesForSeller(Request $request)
    {
        $sellerId = Auth::id();

        $promotions = SellerAdminPromotion::with('flashSale')
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
            })
            ->whereNotNull('flash_sale_id')
            ->get();

        $flashSales = $promotions->pluck('flashSale')
            ->filter()
            ->unique('id')
            ->values();

        return response()->json([
            'success' => true,
            'flash_sales' => $flashSales->map(function ($fs) {
                return [
                    'id'    => $fs->id,
                    'title' => $fs->getTranslation('title'),
                ];
            }),
        ]);
    }

    public function markAsFlashSale(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|integer|exists:products,id',
            'flash_sale_id' => 'required|integer|exists:flash_deals,id',
            'discount'      => 'required|numeric|min:0',
        ]);

        $sellerId = Auth::id();

        $promotion = SellerAdminPromotion::whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
            })
            ->where('flash_sale_id', $request->flash_sale_id)
            ->latest()
            ->first();

        if (!$promotion) {
            return response()->json(['success' => false, 'message' => translate('No active promotion found for this flash sale')], 422);
        }

        $participate = SellerAdminPromotionParticipate::firstOrCreate([
            'seller_admin_promotion_id' => $promotion->id,
            'seller_id'                 => $sellerId,
        ]);

        SellerAdminPromotionParticipateProduct::updateOrCreate(
            [
                'seller_admin_promotion_participate_id' => $participate->id,
                'product_id'                             => $request->product_id,
            ],
            [
                'flash_sale'    => 1,
                'flash_sale_id' => $request->flash_sale_id,
                'discount'      => $request->discount,
            ]
        );

        $promotion->sellers()->updateExistingPivot($sellerId, ['responded' => 1]);

        return response()->json(['success' => true, 'message' => translate('Product marked for Flash Sale')]);
    }

}
