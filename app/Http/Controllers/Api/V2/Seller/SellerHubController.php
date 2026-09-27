<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\Seller\MessageResource;
use App\Http\Resources\V2\Seller\NoticeResource;
use App\Http\Resources\V2\Seller\PromotionResource;
use App\Http\Resources\V2\Seller\SellerRequestResource;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\SellerAdminConversation;
use App\Models\SellerAdminMessage;
use App\Models\SellerAdminNotice;
use App\Models\SellerAdminPromotion;
use App\Models\SellerAdminPromotionParticipate;
use App\Models\SellerAdminPromotionParticipateProduct;
use App\Models\SellerAdminRequest;
use App\Models\Shop;
use App\Models\User;
use App\Services\SellerHubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SellerHubController extends Controller
{
    protected $sellerHubService;

    public function __construct(SellerHubService $sellerHubService)
    {
        $this->sellerHubService = $sellerHubService;
    }

    private function getAdminId()
    {
        return User::where('user_type', 'admin')->value('id');
    }

    public function all(Request $request)
    {
        $sellerId = Auth::id();

        $hubData = $this->sellerHubService->getHubData($sellerId);

        SellerAdminNotice::whereHas('sellers', function ($q) use ($sellerId) {
            $q->where('seller_admin_notice_sellers.seller_id', $sellerId);
        })->get()->each(function ($notice) use ($sellerId) {
            $notice->sellers()->updateExistingPivot($sellerId, ['seen' => 1]);
        });

        SellerAdminPromotion::whereHas('sellers', function ($q) use ($sellerId) {
            $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
        })->get()->each(function ($promotion) use ($sellerId) {
            $promotion->sellers()->updateExistingPivot($sellerId, ['seen' => 1]);
        });

        $activities = $hubData['activities']->map(function ($activity) {
            $data = null;

            switch ($activity['type']) {
                case 'notice':
                    $data = new NoticeResource($activity['data']);
                    break;

                case 'promotion':
                    $data = new PromotionResource($activity['data']);
                    break;

                case 'message':
                    $data = $activity['data']->lastMessage
                        ? new MessageResource($activity['data']->lastMessage)
                        : null;
                    break;
            }

            return [
                'type' => $activity['type'],
                'data' => $data,
            ];
        })->filter(function ($activity) {
            return $activity['data'] !== null;
        })->values();

        return response()->json([
            'status' => 'success',
            'data'    => $activities,
        ]);
    }

    public function notices(Request $request)
    {
        $sellerId = Auth::id();
        $notices = $this->getNoticesData($sellerId, true);

        return response()->json([
            'status' => 'success',
            'data'    => NoticeResource::collection($notices),
        ]);
    }

    private function getNoticesData($sellerId, $markSeen = false)
    {
        $shop = Shop::where('user_id', $sellerId)->first();

        $notices = SellerAdminNotice::with(['sellers', 'seenSellers'])
            ->where('save_as_preset', 0)
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_notice_sellers.seller_id', $sellerId);
            })
            ->orderByRaw("notice_type = 'default' desc")
            ->orderBy('created_at', 'desc')
            ->get();

        if ($markSeen) {
            $notices->each(function ($notice) use ($sellerId) {
                $notice->sellers()->updateExistingPivot($sellerId, ['seen' => 1]);
            });
        }

        $presetNotices = collect();

        if ($shop) {
            $presetDefaults = SellerAdminNotice::whereIn('id', [1, 2, 3, 4])
                ->where('status', 1)
                ->get()
                ->keyBy('id');

            if ($shop->verification_status == 0 && $presetDefaults->has(1)) {
                $presetNotices->push($presetDefaults->get(1));
            }

            if (
                addon_is_activated('gst_system')
                && $shop->gst_verification == 0
                && $presetDefaults->has(2)
            ) {
                $presetNotices->push($presetDefaults->get(2));
            }

            if ($shop->package_invalid_at) {
                $expiryDate = Carbon::parse($shop->package_invalid_at);
                $today = Carbon::today();

                $isExpired = $today->gte($expiryDate);
                $withinWeekOfExpiry = !$isExpired && $today->diffInDays($expiryDate) <= 7;

                if ($withinWeekOfExpiry && $presetDefaults->has(3)) {
                    $notice = $presetDefaults->get(3);
                    $notice->expiry_date = $expiryDate->format('M d, Y');
                    $presetNotices->push($notice);
                }

                if ($isExpired && $presetDefaults->has(4)) {
                    $notice = $presetDefaults->get(4);
                    $notice->expiry_date = $expiryDate->format('M d, Y');
                    $presetNotices->push($notice);
                }
            }
        }

        return $presetNotices->concat($notices);
    }

    public function all_requests(Request $request)
    {
        $sellerId = Auth::id();

        $requests = SellerAdminRequest::where('seller_id', $sellerId)
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data'    => SellerRequestResource::collection($requests),
            'meta'    => [
                'current_page' => $requests->currentPage(),
                'last_page'    => $requests->lastPage(),
                'total'        => $requests->total(),
            ],
        ]);
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'items'   => 'required|array|min:1',
            'items.*' => 'in:category,brand,color,attribute,unit,measurement_point,warranty',
            'message' => 'nullable|string',
        ]);

        $fieldMap = [
            'category'          => 'category_name',
            'brand'             => 'brand_name',
            'color'             => 'color_name',
            'attribute'         => 'attribute_name',
            'unit'              => 'unit_name',
            'measurement_point' => 'measurement_point_name',
            'warranty'          => 'warranty_name',
        ];

        $errors = [];
        $data   = [];

        foreach ($request->items as $item) {
            $field = $fieldMap[$item];
            $value = trim((string) $request->input($field));

            if ($value === '') {
                $errors[$field] = [translate('This field is required')];
            }

            $data[$field] = $value;
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors'  => $errors,
            ], 422);
        }

        $newRequest = SellerAdminRequest::create(array_merge($data, [
            'seller_id' => Auth::id(),
            'name'      => implode(', ', array_map(function ($item) {
                return ucfirst(str_replace('_', ' ', $item));
            }, $request->items)),
            'message'   => $request->message,
        ]));

        return response()->json([
            'status' => 'success',
            'message' => translate('Request has been sent successfully'),
        ], 201);
    }

    public function promotions(Request $request)
    {
        $sellerId = Auth::id();

        $promotions = SellerAdminPromotion::with(['flashSale', 'sellers', 'respondedSellers'])
            ->whereHas('sellers', function ($q) use ($sellerId) {
                $q->where('seller_admin_promotion_sellers.seller_id', $sellerId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $promotions->each(function ($promotion) use ($sellerId) {
            $promotion->sellers()->updateExistingPivot($sellerId, ['seen' => 1]);
        });

        return response()->json([
            'status' => 'success',
            'data'    => PromotionResource::collection($promotions),
        ]);
    }

    public function messages(Request $request)
    {
        $sellerId = Auth::id();
        $adminId  = $this->getAdminId();
        $admin    = User::where('user_type', 'admin')->first();

        $conversation = SellerAdminConversation::where(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $sellerId);
        })->orWhere(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $sellerId)->where('receiver_id', $adminId);
        })->first();

        $messages = collect();

        if ($conversation) {
            SellerAdminMessage::where('seller_admin_conversation_id', $conversation->id)
                ->where('user_id', '!=', $sellerId)
                ->where('seen', 0)
                ->update(['seen' => 1]);

            $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        }

        return response()->json([
            'status' => 'success',
            'data'    => [
                'admin'    => $admin ? [
                    'id'     => $admin->id,
                    'name'   => $admin->name,
                    'avatar' => $admin->avatar_original ? uploaded_asset($admin->avatar_original) : null,
                ] : null,
                'messages' => MessageResource::collection($messages),
            ],
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $sellerId = Auth::id();
        $adminId  = $this->getAdminId();

        $conversation = SellerAdminConversation::where(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $adminId)->where('receiver_id', $sellerId);
        })->orWhere(function ($q) use ($adminId, $sellerId) {
            $q->where('sender_id', $sellerId)->where('receiver_id', $adminId);
        })->first();

        if (!$conversation) {
            $conversation = SellerAdminConversation::create([
                'sender_id'   => $sellerId,
                'receiver_id' => $adminId,
            ]);
        } else {
            $conversation->touch();
        }

        $message = SellerAdminMessage::create([
            'seller_admin_conversation_id' => $conversation->id,
            'user_id'                      => $sellerId,
            'message'                      => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => translate('Message sent successfully'),
        ], 201);
    }

    public function promotional_product_list(Request $request)
    {
        $auth_user = auth()->user()->id;
 
        $products = Product::where('user_id', $auth_user)
            ->where('auction_product', 0)
            ->where('wholesale_product', 0)
            ->where('promotional', 1);
 
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
                } elseif ($request->product_type == 'physical_products') {
                    $products = $products->where('digital', 0);
                } elseif ($request->product_type == 'not_approved') {
                    $products = $products->where('approved', 0);
                } elseif ($request->product_type == 'pos_product_list') {
                    $products = $products->where('pos', 1);
                } elseif ($request->product_type == 'promotional_product_list') {
                    $products = $products->where('promotional', 1);
                } elseif ($request->product_type == 'todays_deal_product_list') {
                    $products = $products->where('todays_deal', 1);
                }
            }
        }
 
        $col_name    = null;
        $query       = null;
        $sort_search = null;
 
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
 
        if ($request->type != null) {
            $var      = explode(",", $request->type);
            $col_name = $var[0];
            $query    = $var[1];
            $products = $products->orderBy($col_name, $query);
        }
 
        $filters = $request->selected_filter ?? [];
        if (!empty($filters)) {
            if (in_array('low-stock', $filters)) {
                $products->where(function ($q) {
                    $q->whereRaw("
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
            $products = $products->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
 
        
        $products = $products->orderBy('updated_at', 'desc')->paginate(15);

 
        $sellerId = Auth::id();
 
        $activeTodaysDealPromotion = SellerAdminPromotion::activeForSeller($sellerId, 'todays_deal');
        $activeFeaturedPromotion   = SellerAdminPromotion::activeForSeller($sellerId, 'featured_products');
        $activeFlashSalePromotion  = SellerAdminPromotion::activeForSeller($sellerId, 'flash_sale');
 
        $participateProductsRaw = SellerAdminPromotionParticipateProduct::whereHas('participate', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->get()
            ->groupBy('product_id');
 
        $participateProducts = $participateProductsRaw->map(function ($rows) {
            return (object) [
                'todays_deal'   => $rows->contains(fn($r) => $r->todays_deal == 1),
                'featured'      => $rows->contains(fn($r) => $r->featured == 1),
                'flash_sale'    => $rows->contains(fn($r) => $r->flash_sale == 1),
                'discount'      => optional($rows->firstWhere('flash_sale', 1))->discount,
                'flash_sale_id' => optional($rows->firstWhere('flash_sale', 1))->flash_sale_id,
            ];
        });
 
        $data = $products->getCollection()->map(function ($product) use (
            $participateProducts,
            $activeTodaysDealPromotion,
            $activeFeaturedPromotion,
            $activeFlashSalePromotion
        ) {
            $participateEntry = $participateProducts->get($product->id);
            $shop = optional(optional($product->user)->shop);
 
            $totalReviews = $product->reviews->where('status', 1)->count();
 
            return [
                'id'            => $product->id,
                'thumbnail_img' => uploaded_asset($product->thumbnail_img),
                'name'          => $product->getTranslation('name'),
 
                'brand' => $product->brand ? [
                    'id'   => $product->brand->id,
                    'name' => translate($product->brand->name),
                ] : null,
 
                'owner' => [
                    'shop_id'   => $shop->id ?? null,
                    'shop_name' => $shop->name ?? translate('Inhouse'),
                ],
 
                'main_category' => translate($product->main_category->name ?? ''),
 
                'ratings' => [
                    'average'      => (float) $product->rating,
                    'total_reviews' => $totalReviews,
                ],
 
                'price' => [
                    'unit_price'          => single_price($product->unit_price),
                    'discount_percentage' => discount_in_percentage($product),
                ],
 
                'marketing' => [
                    'flash_sale'    => $participateEntry->flash_sale ?? false,
                    'flash_sale_discount' => $participateEntry->discount ?? null,
                    'flash_sale_id' => $participateEntry->flash_sale_id ?? null,
                    'todays_deal'   => $participateEntry->todays_deal ?? false,
                    'featured'      => $participateEntry->featured ?? false,
                ],
 
                'available_actions' => [
                    'can_mark_todays_deal' => (bool) ($activeTodaysDealPromotion && !($participateEntry && $participateEntry->todays_deal)),
                    'can_mark_featured'    => (bool) ($activeFeaturedPromotion && !($participateEntry && $participateEntry->featured)),
                    'can_mark_flash_sale'  => (bool) ($activeFlashSalePromotion && !($participateEntry && $participateEntry->flash_sale)),
                ],
            ];
        })->values();
 
        return response()->json([
            'status' => 'success',
            'data'    => $data,
            'meta' => [
                'seller_type' => $request->seller_type,
                'product_type' => $request->product_type,
                'sort' => [
                    'column' => $col_name,
                    'order'  => $query,
                ],
                'search' => $sort_search,
            ],
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    public function update_promotional_product(Request $request)
    {
        $type = $request->type;
        $checkedIds = $request->checked_ids ?? [];

        if ($type == 'add') {
            if (!empty($checkedIds)) {
                Product::whereIn('id', $checkedIds)
                    ->update(['promotional' => 1]);
            }
        }

        if ($type == 'remove') {
            if (!empty($checkedIds)) {
                Product::whereIn('id', $checkedIds)
                    ->update(['promotional' => 0, 'todays_deal' => 0]);

                FlashDealProduct::whereIn('product_id', $checkedIds)->delete();
            }
        }

        return response()->json(['success' => true]);
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

        return response()->json(['status' => 'success', 'message' => translate('Product marked for Flash Sale')]);
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

        return response()->json(['status' => 'success', 'message' => translate('Product marked as Today\'s Deal')]);
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

        return response()->json(['status' => 'success', 'message' => translate('Product marked as Featured')]);
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
            'status' => 'success',
            'flash_sales' => $flashSales->map(function ($fs) {
                return [
                    'id'    => $fs->id,
                    'title' => $fs->getTranslation('title'),
                ];
            }),
        ]);
    }
}