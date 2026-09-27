<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\SellerAdminNotice;
use App\Models\SellerAdminPromotion;
use App\Models\Shop;
use Auth;
use Carbon\Carbon;
use DB;
use Artisan;
use Illuminate\Http\Request;
use App\Services\SellerHubService;

class DashboardController extends Controller
{
    public function __construct(protected SellerHubService $sellerHubService) {}
    
    public function index()
    {
        $authUserId = auth()->user()->id;
        $data['this_month_pending_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('pending')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_cancelled_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('cancelled')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_on_the_way_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('on_the_way')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_delivered_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('delivered')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
                                    
        $data['this_month_sold_amount'] = Order::where('seller_id', Auth::user()->id)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->sum('grand_total');
        $data['previous_month_sold_amount'] = Order::where('seller_id', Auth::user()->id)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', (Carbon::now()->month-1))
                                    ->sum('grand_total');
        
        $data['products'] = filter_products(Product::where('user_id', Auth::user()->id)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('d M');
            $last7Days[$date] = 0;
        }
        $salesData = Order::where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->where('seller_id', '=', Auth::user()->id)
            ->where('delivery_status', '=', 'delivered')
            ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->get()->pluck('total', 'date');
        $data['last_7_days_sales'] = $last7Days->merge($salesData);

        $data['allOrder'] = Order::where('seller_id', '=', Auth::user()->id)->count();
        $data['pendingOrder'] = Order::where('seller_id', '=', Auth::user()->id)->where('delivery_status', '=', 'pending')->count();
        $data['confirmedOrder'] = Order::where('seller_id', '=', Auth::user()->id)->where('delivery_status', '=', 'confirmed')->count();
        $data['processedOrder'] = Order::where('seller_id', '=', Auth::user()->id)->where('delivery_status', '=', 'on_the_way')->count();
        $data['shippedOrder'] = Order::where('seller_id', '=', Auth::user()->id)->where('delivery_status', '=', 'picked_up')->count();
        $data['deliveredOrder'] = Order::where('seller_id', '=', Auth::user()->id)->where('delivery_status', '=', 'delivered')->count();
        $data['cancelledOrder'] = Order::where('seller_id', '=', Auth::user()->id)->where('delivery_status', '=', 'cancelled')->count();

        $total = $data['allOrder'] > 0 ? $data['allOrder'] : 1;

        $data['pendingPercent']   = round(($data['pendingOrder']   / $total) * 100, 1);
        $data['confirmedPercent'] = round(($data['confirmedOrder'] / $total) * 100, 1);
        $data['processedPercent'] = round(($data['processedOrder'] / $total) * 100, 1);
        $data['shippedPercent']   = round(($data['shippedOrder']   / $total) * 100, 1);
        $data['deliveredPercent'] = round(($data['deliveredOrder'] / $total) * 100, 1);
        $data['cancelledPercent'] = round(($data['cancelledOrder'] / $total) * 100, 1);

        $hubData = $this->sellerHubService->getHubData($authUserId);
        $data = array_merge($data, $hubData);

        return view('seller.dashboard', $data);
    }

    function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    public function view_all_chat_modal(Request $request)
    {
        $shops = Shop::all();
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

        return view('seller.chats.all_chat_modal', array_merge(['shops' => $shops], $hubData));
    }
}
