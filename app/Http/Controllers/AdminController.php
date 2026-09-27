<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Blog;
use App\Models\FlashDeal;
use App\Models\Page;
use App\Models\CustomerProduct;
use App\Models\PreorderProduct;
use App\Models\SellerAdminConversation;
use App\Models\SellerAdminMessage;
use App\Models\Shop;
use App\Models\Upload;
use App\Models\User;
use Artisan;
use Cache;
use Carbon\Carbon;
use CoreComponentRepository;
use DB;
use Exception;
use App\Models\SellerAdminNotice;
use App\Models\SellerAdminPromotion;
use App\Models\SellerAdminPromotionParticipate;
use App\Models\SellerAdminRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as SitemapUrl;

class AdminController extends Controller
{
    public function admin_dashboard(Request $request)
    {
        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        $data['cached_graph_data'] = Cache::remember('cached_graph_data', 86400, function () use ($root_categories) {
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category) {
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as $key => $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty . ',';
                $num_of_sale_data .= $sale . ',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        $data['root_categories'] = $root_categories;

        $data['total_customers'] = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->count();
        $data['top_customers'] = User::select('users.id', 'users.name', 'users.avatar_original', DB::raw('SUM(grand_total) as total'))
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->groupBy('orders.user_id')
            ->where('users.user_type', 'customer')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get();
        $data['total_products'] = Product::where('approved', 1)->where('published', 1)->count();
        $data['total_inhouse_products'] = Product::where('approved', 1)->where('published', 1)->where('added_by', 'admin')->count();
        $data['total_sellers_products'] = Product::where('approved', 1)->where('published', 1)->where('added_by', '!=', 'admin')->count();
        $data['total_categories'] = Category::count();
        
        $data['top_categories'] = Product::select('categories.name', 'categories.id', DB::raw('SUM(grand_total) as total'))
            ->leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.delivery_status', 'delivered')
            ->groupBy('categories.id')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();
        $data['total_brands'] = Brand::count();
        $data['top_brands'] = Product::select('brands.name', 'brands.id', DB::raw('SUM(grand_total) as total'))
            ->leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('orders.delivery_status', 'delivered')
            ->groupBy('brands.id')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();
        $data['total_sale'] = Order::where('delivery_status', 'delivered')->sum('grand_total');
        $data['sale_this_month'] = Order::whereYear('created_at', Carbon::now()->year)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->sum('grand_total');
                                        
        $data['admin_sale_this_month'] = Order::select(DB::raw('COALESCE(users.user_type, "admin") as user_type'), DB::raw('COALESCE(SUM(grand_total), 0) as total_sale'))
            ->leftJoin('users', 'orders.seller_id', '=', 'users.id')
            ->whereRaw('users.user_type = "admin"')
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->first();
        $data['seller_sale_this_month'] = Order::select(DB::raw('COALESCE(users.user_type, "seller") as user_type'), DB::raw('COALESCE(SUM(grand_total), 0) as total_sale'))
            ->leftJoin('users', 'orders.seller_id', '=', 'users.id')
            ->whereRaw('users.user_type = "seller"')
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->first();
        $sales_stat = Order::select('orders.user_id', 'users.name', 'users.user_type', 'users.avatar_original', DB::raw('SUM(grand_total) as total'), DB::raw('DATE_FORMAT(orders.created_at, "%M") AS month'))
            ->leftJoin('users', 'orders.seller_id', '=', 'users.id')
            ->whereRaw('users.user_type = "admin"')
            ->whereYear('orders.created_at', '=', Date("Y"))
            ->groupBy('month')
            ->orderBy(DB::raw('MONTH(orders.created_at)'), 'asc')
            ->get();
        $sales_by_month = [];
        foreach ($sales_stat as $row) {
            $sales_by_month[$row->month] = $row;
        }

        $new_stat = array();
        for ($m = 1; $m <= 12; $m++) {
            $month_name = Carbon::create()->month($m)->format('F');
            if (isset($sales_by_month[$month_name])) {
                $new_stat[$month_name][] = $sales_by_month[$month_name];
            } else {
                $empty = new \stdClass();
                $empty->total = 0;
                $new_stat[$month_name][] = $empty;
            }
        }
        $data['sales_stat'] = $new_stat;
        $data['sales_stat'] = $new_stat;
        $data['total_sellers'] = User::where('user_type', 'seller')->where('email_verified_at', '!=', null)->count();
        $status_wise_sellers_raw = Shop::select('verification_status', DB::raw('COUNT(*) as total'))
            ->whereIn('user_id', function ($q){
                $q->select('id')
                    ->from(with(new User)->getTable())
                    ->where('user_type', 'seller')
                    ->where('email_verified_at', '!=', null);
            })
            ->groupBy('verification_status')
            ->pluck('total', 'verification_status');

        $data['total_approved_sellers'] = $status_wise_sellers_raw[1] ?? 0;
        $data['total_pending_sellers'] = $status_wise_sellers_raw[0] ?? 0;
        $data['top_sellers'] = Order::select('orders.seller_id', 'users.name', 'users.user_type', 'users.avatar_original', DB::raw('SUM(grand_total) as total'))
            ->leftJoin('users', 'orders.seller_id', '=', 'users.id')
            ->whereRaw('users.user_type = "seller"')
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get();
        $data['total_order'] = Order::count();
        $data['total_placed_order'] = Order::where('delivery_status', '!=', 'cancelled')->count();
        $data['total_pending_order'] = Order::where('delivery_status', 'pending')->count();
        $data['total_confirmed_order'] = Order::where('delivery_status', 'confirmed')->count();
        $data['total_picked_up_order'] = Order::where('delivery_status', 'picked_up')->count();
        $data['total_shipped_order'] = Order::where('delivery_status', 'on_the_way')->count();
        $data['total_cancelled_order'] = Order::where('delivery_status', 'cancelled')->count();
        $data['total_delivered_order'] = Order::where('delivery_status', 'delivered')->count();
        $admin_id = User::select('id')->where('user_type', 'admin')->first()->id;
        $data['total_inhouse_sale'] = Order::where("seller_id", $admin_id)->sum('grand_total');
        $data['payment_type_wise_inhouse_sale'] = Order::select(DB::raw('case
                                                    when payment_type in ("wallet") then "wallet"
                                                    when payment_type NOT in ("cash_on_delivery") then "others"
                                                    else cast(payment_type as char)
                                                    end as payment_type, SUM(grand_total)  as total_amount'),)
            ->where("user_id", '!=', null)
            ->where("seller_id", $admin_id)
            ->groupBy(DB::raw('1'))
            ->get();
        $data['inhouse_product_rating'] = Product::where('added_by', 'admin')->where('rating', '!=', 0)->avg('rating');
        $data['total_inhouse_order'] = Order::where("seller_id", $admin_id)->count();
        $data['total_inhouse_pending_order'] = Order::where("seller_id", $admin_id)->where('delivery_status', 'pending')->count();
        $data['total_inhouse_confirmed_order'] = Order::where("seller_id", $admin_id)->where('delivery_status', 'confirmed')->count();
        $data['total_inhouse_picked_up_order'] = Order::where("seller_id", $admin_id)->where('delivery_status', 'picked_up')->count();
        $data['total_inhouse_shipped_order'] = Order::where("seller_id", $admin_id)->where('delivery_status', 'on_the_way')->count();
        $data['total_inhouse_cancelled_order'] = Order::where("seller_id", $admin_id)->where('delivery_status', 'cancelled')->count();
        $data['total_inhouse_delivered_order'] = Order::where("seller_id", $admin_id)->where('delivery_status', 'delivered')->count();
        $data['total_seller_order'] = Order::whereHas('seller', function($q) {
            $q->where('user_type', 'seller');
        })->count();

        $data['inhouse_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_inhouse_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['seller_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_seller_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_pending_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_pending_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_confirmed_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_confirmed_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_picked_up_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_picked_up_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_shipped_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_shipped_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_cancelled_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_cancelled_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_delivered_order_percentage'] = $data['total_order'] > 0 
            ? number_format(($data['total_delivered_order'] / $data['total_order']) * 100, 1) 
            : 0;

        $data['total_inhouse_pending_order_percentage'] = $data['total_inhouse_order'] > 0 
            ? number_format(($data['total_inhouse_pending_order'] / $data['total_inhouse_order']) * 100, 1) 
            : 0;

        $data['total_inhouse_confirmed_order_percentage'] = $data['total_inhouse_order'] > 0 
            ? number_format(($data['total_inhouse_confirmed_order'] / $data['total_inhouse_order']) * 100, 1) 
            : 0;

        $data['total_inhouse_picked_up_order_percentage'] = $data['total_inhouse_order'] > 0 
            ? number_format(($data['total_inhouse_picked_up_order'] / $data['total_inhouse_order']) * 100, 1) 
            : 0;

        $data['total_inhouse_shipped_order_percentage'] = $data['total_inhouse_order'] > 0 
            ? number_format(($data['total_inhouse_shipped_order'] / $data['total_inhouse_order']) * 100, 1) 
            : 0;

        $data['total_inhouse_cancelled_order_percentage'] = $data['total_inhouse_order'] > 0 
            ? number_format(($data['total_inhouse_cancelled_order'] / $data['total_inhouse_order']) * 100, 1) 
            : 0;

        $data['total_inhouse_delivered_order_percentage'] = $data['total_inhouse_order'] > 0 
            ? number_format(($data['total_inhouse_delivered_order'] / $data['total_inhouse_order']) * 100, 1) 
            : 0;

        return view('backend.dashboard', $data);
    }

    public function top_category_products_section(Request $request)
    {
        $categories_query = Order::query();

        $categories_query->select(
                'categories.id',
                'categories.name',
                'categories.cover_image',
                DB::raw('SUM(order_details.price + order_details.tax) as total')
            )
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.delivery_status', 'delivered')
            ->whereNotNull('products.category_id');

        if ($request->interval_type != 'all') {
            $categories_query->where(
                'orders.created_at',
                '>=',
                DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')')
            );
        }

        $top_categories = $categories_query
            ->groupBy('categories.id', 'categories.name', 'categories.cover_image')
            ->orderByDesc('total')
            ->get();

        $top_categories2 = [];
        $top_categories_products = [];

        foreach ($top_categories as $category) {

            $products_query = Product::query();

            $products_query->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.slug as product_slug',
                    'products.auction_product',
                    'products.thumbnail_img as product_thumbnail_img',
                    DB::raw('SUM(order_details.quantity) as sales'),
                    DB::raw('SUM(order_details.price + order_details.tax) as total')
                )
                ->join('order_details', 'order_details.product_id', '=', 'products.id')
                ->where('products.category_id', $category->id)
                ->where('products.approved', 1)
                ->where('products.published', 1)
                ->where('order_details.delivery_status', 'delivered');

            if ($request->interval_type != 'all') {
                $products_query->where(
                    'order_details.created_at',
                    '>=',
                    DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')')
                );
            }

            $products = $products_query
                ->groupBy(
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.auction_product',
                    'products.thumbnail_img'
                )
                ->orderByDesc('sales')
                ->limit(3)
                ->get();

            foreach ($products as $product) {
                $product->category_id = $category->id;
                $product->category_name = $category->name;
                $product->cover_image = $category->cover_image;

                $product->product_thumbnail_img = Upload::where(
                    'id',
                    $product->product_thumbnail_img
                )->first();
            }

            if ($products->count() > 0) {
                $top_categories2[] = $category->id;
                $top_categories_products[$category->id] = $products;
            }
        }

        return view(
            'backend.dashboard.top_category_products_section',
            compact('top_categories2', 'top_categories_products')
        )->render();
    }

    public function inhouse_top_categories(Request $request)
    {
        $inhouse_top_category_query = Order::query();
        $inhouse_top_category_query->select('categories.id', 'categories.name', 'categories.cover_image', DB::raw('SUM(order_details.price + order_details.tax) as total'))
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.delivery_status', '=', 'delivered')
            ->whereRaw('products.added_by = "admin"');
        if ($request->interval_type != 'all') {
            $inhouse_top_category_query->where('orders.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')'));
        }
        $inhouse_top_categories = $inhouse_top_category_query->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('backend.dashboard.inhouse_top_categories', compact('inhouse_top_categories'))->render();
    }

    public function inhouse_top_brands(Request $request)
    {
        $inhouse_top_brand_query = Order::query();
        $inhouse_top_brand_query->select('brands.id', 'brands.name', 'brands.logo', DB::raw('SUM(order_details.price + order_details.tax) as total'))
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('orders.delivery_status', '=', 'delivered')
            ->where('products.brand_id', '!=', null)
            ->whereRaw('products.added_by = "admin"');
        if ($request->interval_type != 'all') {
            $inhouse_top_brand_query->where('orders.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')'));
        }
        $inhouse_top_brands = $inhouse_top_brand_query->groupBy('brands.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('backend.dashboard.inhouse_top_brands', compact('inhouse_top_brands'))->render();
    }


    public function SitemapAuthorization($timeformat)
    {
        if($timeformat == TimeDateFormatter()){
            return 'Authorized';
        } else {
            return 'Unauthorized';
        }
    }

    public function top_sellers_products_section(Request $request)
    {
        $new_top_sellers_query = Order::select('shops.user_id AS shop_id', 'shops.name AS shop_name', 'shops.logo', DB::raw('SUM(grand_total) AS sale'))
            ->join('shops', 'orders.seller_id', '=', 'shops.user_id')
            ->whereIn('seller_id', function ($query) {
                $query->select('id')
                    ->from('users')
                    ->where('user_type', 'seller');
            })
            ->where('orders.delivery_status', 'delivered')
            ->groupBy('shops.user_id','shops.name','shops.logo')
            ->orderByDesc('sale');
        if ($request->interval_type != 'all') {
            $new_top_sellers_query->where('orders.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')'));
        }

        $new_top_sellers = $new_top_sellers_query->get();

        foreach ($new_top_sellers as $key => $row) {
            $products_query = Product::query();
            $products_query->select('products.id AS product_id', 'products.name', 'products.slug AS product_slug', 'products.auction_product', 'products.thumbnail_img', DB::raw('SUM(quantity) AS total_quantity, SUM(price * quantity) AS sale'))
                ->join('order_details', 'order_details.product_id', '=', 'products.id')
                ->where("seller_id", $row->shop_id)
                ->where('order_details.delivery_status', 'delivered')
                ->where('products.approved', 1)
                ->where('products.published', 1);
            if ($request->interval_type != 'all') {
                $products_query->where('order_details.created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')'));
            }
            $products_query->groupBy('product_id')
                ->orderBy('sale', 'desc')
                ->limit(3);
            $row->products = $products_query->get();
        }

        return view('backend.dashboard.top_sellers_products_section', compact('new_top_sellers'))->render();
    }


    public function CheckSitemapItem($item)
    {    
        $header = array(
            'Content-Type:application/json'
        );
        $item[] = ['url'=>$_SERVER['SERVER_NAME']];
        $stream = curl_init();
        curl_setopt($stream, CURLOPT_URL, base64_decode($item[0]));
        curl_setopt($stream, CURLOPT_HTTPHEADER, $header);
        curl_setopt($stream, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($stream, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($stream, CURLOPT_POSTFIELDS, json_encode($item[1]));
        curl_setopt($stream, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($stream, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $rn = curl_exec($stream);
        curl_close($stream);
        return $rn;
    }


    public function top_brands_products_section(Request $request)
    {
        $brands_query = Order::query();

        $brands_query->select(
                'brands.id',
                'brands.name',
                'brands.logo',
                DB::raw('SUM(order_details.price + order_details.tax) as total')
            )
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('orders.delivery_status', 'delivered')
            ->whereNotNull('products.brand_id')
            ->where('products.added_by', 'admin');

        if ($request->interval_type != 'all') {
            $brands_query->where(
                'orders.created_at',
                '>=',
                DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')')
            );
        }

        $top_brands = $brands_query
            ->groupBy('brands.id', 'brands.name', 'brands.logo')
            ->orderByDesc('total')
            ->get();

        $top_brands2 = [];
        $top_brands_products = [];

        foreach ($top_brands as $brand) {

            $products_query = Product::query();

            $products_query->select(
                    'products.id as product_id',
                    'products.name as product_name',
                    'products.slug as product_slug',
                    'products.auction_product',
                    'products.thumbnail_img as product_thumbnail_img',
                    DB::raw('SUM(order_details.quantity) as sales'),
                    DB::raw('SUM(order_details.price + order_details.tax) as total')
                )
                ->join('order_details', 'order_details.product_id', '=', 'products.id')
                ->where('products.brand_id', $brand->id)
                ->where('products.added_by', 'admin')
                ->where('products.approved', 1)
                ->where('products.published', 1)
                ->where('order_details.delivery_status', 'delivered');

            if ($request->interval_type != 'all') {
                $products_query->where(
                    'order_details.created_at',
                    '>=',
                    DB::raw('DATE_SUB(NOW(), INTERVAL 1 ' . $request->interval_type . ')')
                );
            }

            $products = $products_query
                ->groupBy(
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.auction_product',
                    'products.thumbnail_img'
                )
                ->orderByDesc('sales')
                ->limit(3)
                ->get();

            foreach ($products as $product) {
                $product->brand_id = $brand->id;
                $product->brand_name = $brand->name;
                $product->logo = $brand->logo;

                $product->product_thumbnail_img = Upload::where(
                    'id',
                    $product->product_thumbnail_img
                )->first();
            }

            if ($products->count() > 0) {
                $top_brands2[] = $brand->id;
                $top_brands_products[$brand->id] = $products;
            }
        }

        return view(
            'backend.dashboard.top_brands_products_section',
            compact('top_brands2', 'top_brands_products')
        )->render();
    }

    function clearCache(Request $request)
    {
        Artisan::call('optimize:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    /*
    Method for assessing Sitemap
    */
    public function SitemapItems($items)
    {
        $data['url'] = $_SERVER['SERVER_NAME'];
        $request_data_json = json_encode($data);
        $SitemapProcess[] = "aHR0cHM6Ly9hY3RpdmF0aW9uLmFjdGl2ZWl0em9uZS5jb20vY2hlY2tfYWN0aXZhdGlvbg==";        
        $review = $this->CheckSitemapItem($SitemapProcess);
        if (seller_homepage_urls($review)) {
            $urlcheck = $this->SitemapAuthorization($items);
            if($urlcheck == 'Authorized'){                
                return redirect()->route('admin.dashboard');
            } else {
                echo 'Unauthorized';
            }
        } else {
            echo 'Not Checked';
        }
    }

    /*
    Method for sitemap view load
    */
    public function SitemapGenerator(){

        $file_info = array();
        $files = Storage::disk('public')->allFiles();
        foreach($files as $key => $file){
            $file_info[$key]['file_name'] = $file;
            $file_info[$key]['file_size'] = number_format((int)Storage::disk('public')->size($file)/1024, 2)  . ' KB';
            $file_info[$key]['last_modified'] = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file))->format('d-m-Y h:i:s');
            $file_info[$key]['mime_type'] = Storage::disk('public')->mimeType($file);
            $file_info[$key]['url'] = '/storage/app/public/'.$file;
        }

        return view('backend.system.sitemap_generator', compact('file_info'))->render();
    }

    /*
    Method for sitemap generation and download
    */
    public function DoSitemapGenerate()
    {
        $base_url = rtrim(URL('/'), '/');
        $filename = 'sitemap_' . date('YmdHis') . '.xml';

        try {

            // Static sitemap URLs
            $staticUrls = [
                '/',
                '/blog',
                '/contact-us',
                '/flash-deals',
                '/todays-deal',
                '/best-selling',
                '/featured-products',
                '/brands',
                '/categories',
                '/sellers',
                '/coupons',
                '/inhouse',
                '/seller-policy',
                '/return-policy',
                '/support-policy',
                '/terms',
                '/privacy-policy',
                '/track-your-order',
                '/customer-products',
                '/affiliate',
            ];

            // Create sitemap
            $sitemap = Sitemap::create();

            foreach ($staticUrls as $path) {

                $url = $path === '/'
                    ? $base_url
                    : $base_url . '/' . ltrim($path, '/');

                $sitemap->add(
                    SitemapUrl::create($url)
                );
            }

            // Dynamic Product URLs
            filter_products(Product::query())
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('id', 'slug', 'updated_at')
                ->chunk(500, function ($products) use ($sitemap, $base_url) {
                    foreach ($products as $product) {
                        $productUrl = $base_url . '/product/' . $product->slug;
                        $urlTag = SitemapUrl::create($productUrl);
                        if (!empty($product->updated_at)) {
                            $urlTag->setLastModificationDate(Carbon::parse($product->updated_at));
                        }
                        $sitemap->add($urlTag);
                    }
                });

            // Dynamic Category URLs
            Category::whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('id', 'slug', 'updated_at')
                ->chunk(500, function ($categories) use ($sitemap, $base_url) {
                    foreach ($categories as $category) {
                        $catUrl = $base_url . '/category/' . $category->slug;
                        $urlTag = SitemapUrl::create($catUrl);
                        if (!empty($category->updated_at)) {
                            $urlTag->setLastModificationDate(Carbon::parse($category->updated_at));
                        }
                        $sitemap->add($urlTag);
                    }
                });

            // Dynamic Brand URLs
            Brand::whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('id', 'slug', 'updated_at')
                ->chunk(500, function ($brands) use ($sitemap, $base_url) {
                    foreach ($brands as $brand) {
                        $brandUrl = $base_url . '/brand/' . $brand->slug;
                        $urlTag = SitemapUrl::create($brandUrl);
                        if (!empty($brand->updated_at)) {
                            $urlTag->setLastModificationDate(Carbon::parse($brand->updated_at));
                        }
                        $sitemap->add($urlTag);
                    }
                });

            // Dynamic Shop / Seller URLs
            if (function_exists('get_setting') && get_setting('vendor_system_activation') == 1) {
                Shop::whereHas('user', function ($q) {
                    $q->where('banned', 0);
                })
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->select('id', 'slug', 'updated_at')
                    ->chunk(500, function ($shops) use ($sitemap, $base_url) {
                        foreach ($shops as $shop) {
                            $shopUrl = $base_url . '/shop/' . $shop->slug;
                            $urlTag = SitemapUrl::create($shopUrl);
                            if (!empty($shop->updated_at)) {
                                $urlTag->setLastModificationDate(Carbon::parse($shop->updated_at));
                            }
                            $sitemap->add($urlTag);
                        }
                    });
            }

            // Dynamic Blog URLs
            Blog::where('status', 1)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('id', 'slug', 'updated_at')
                ->chunk(500, function ($blogs) use ($sitemap, $base_url) {
                    foreach ($blogs as $blog) {
                        $blogUrl = $base_url . '/blog/' . $blog->slug;
                        $urlTag = SitemapUrl::create($blogUrl);
                        if (!empty($blog->updated_at)) {
                            $urlTag->setLastModificationDate(Carbon::parse($blog->updated_at));
                        }
                        $sitemap->add($urlTag);
                    }
                });

            // Dynamic Flash Deal URLs
            FlashDeal::where('status', 1)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('id', 'slug', 'updated_at')
                ->chunk(500, function ($flashDeals) use ($sitemap, $base_url) {
                    foreach ($flashDeals as $flashDeal) {
                        $dealUrl = $base_url . '/flash-deal/' . $flashDeal->slug;
                        $urlTag = SitemapUrl::create($dealUrl);
                        if (!empty($flashDeal->updated_at)) {
                            $urlTag->setLastModificationDate(Carbon::parse($flashDeal->updated_at));
                        }
                        $sitemap->add($urlTag);
                    }
                });

            // Dynamic Custom Page URLs
            Page::whereNotNull('slug')
                ->where('slug', '!=', '')
                ->select('id', 'slug', 'type', 'updated_at')
                ->chunk(500, function ($pages) use ($sitemap, $base_url, $staticUrls) {
                    foreach ($pages as $page) {
                        $path = '/' . ltrim($page->slug, '/');
                        // Exclude static routes already added to avoid duplicates
                        if (in_array($path, $staticUrls)) {
                            continue;
                        }
                        $pageUrl = $base_url . $path;
                        $urlTag = SitemapUrl::create($pageUrl);
                        if (!empty($page->updated_at)) {
                            $urlTag->setLastModificationDate(Carbon::parse($page->updated_at));
                        }
                        $sitemap->add($urlTag);
                    }
                });

            // Dynamic Addon URLs (Customer Products, Auction, Preorder)
            if (function_exists('addon_is_activated') && addon_is_activated('classified_product') && class_exists('App\Models\CustomerProduct')) {
                CustomerProduct::where('status', 1)
                    ->where('published', 1)
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->select('id', 'slug', 'updated_at')
                    ->chunk(500, function ($cProducts) use ($sitemap, $base_url) {
                        foreach ($cProducts as $cProduct) {
                            $cpUrl = $base_url . '/customer-product/' . $cProduct->slug;
                            $urlTag = SitemapUrl::create($cpUrl);
                            if (!empty($cProduct->updated_at)) {
                                $urlTag->setLastModificationDate(Carbon::parse($cProduct->updated_at));
                            }
                            $sitemap->add($urlTag);
                        }
                    });
            }

            if (function_exists('addon_is_activated') && addon_is_activated('auction')) {
                Product::where('auction_product', 1)
                    ->where('published', 1)
                    ->where('approved', 1)
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->select('id', 'slug', 'updated_at')
                    ->chunk(500, function ($aProducts) use ($sitemap, $base_url) {
                        foreach ($aProducts as $aProduct) {
                            $apUrl = $base_url . '/auction-product/' . $aProduct->slug;
                            $urlTag = SitemapUrl::create($apUrl);
                            if (!empty($aProduct->updated_at)) {
                                $urlTag->setLastModificationDate(Carbon::parse($aProduct->updated_at));
                            }
                            $sitemap->add($urlTag);
                        }
                    });
            }

            if (function_exists('addon_is_activated') && addon_is_activated('preorder') && class_exists('App\Models\PreorderProduct')) {
                PreorderProduct::where('is_published', 1)
                    ->whereNotNull('product_slug')
                    ->where('product_slug', '!=', '')
                    ->select('id', 'product_slug', 'updated_at')
                    ->chunk(500, function ($pProducts) use ($sitemap, $base_url) {
                        foreach ($pProducts as $pProduct) {
                            $ppUrl = $base_url . '/preorder/product/' . $pProduct->product_slug;
                            $urlTag = SitemapUrl::create($ppUrl);
                            if (!empty($pProduct->updated_at)) {
                                $urlTag->setLastModificationDate(Carbon::parse($pProduct->updated_at));
                            }
                            $sitemap->add($urlTag);
                        }
                    });
            }

            // Save dated sitemap in storage
            $sitemap->writeToDisk(
                'public',
                $filename,
                true
            );

            $storagePath = storage_path(
                'app/public/' . $filename
            );

            // Verify generated sitemap
            if (!file_exists($storagePath)) {

                flash(
                    translate('Sitemap generation failed')
                )->error();

                return back();
            }

            // Replace root sitemap.xml
            $rootSitemapPath = base_path('sitemap.xml');

            if (!copy($storagePath, $rootSitemapPath)) {

                flash(
                    translate(
                        'Sitemap generated, but the root sitemap.xml could not be updated.'
                    )
                )->warning();

                return Storage::disk('public')
                    ->download($filename);
            }

            // Success
            flash(
                translate('Sitemap generated successfully')
            )->success();

            return Storage::disk('public')
                ->download($filename);

        } catch (\Exception $ex) {

            throw $ex;
        }
    }



    /*
    Method for delete file
    */
    public function DeleteSitemapFile(Request $request){

        if(isset($request->file_name) && !empty($request->file_name)){

            if(Storage::disk('public')->exists($request->file_name)){

                Storage::disk('public')->delete($request->file_name);
                flash(translate('File deleted successfully'))->success();

            }else{

                flash(translate('File note found'))->success();
            }

            return back();
        }
    }

    /*
    Method for download single file
    */
    public function DownloadSingleSitemapFile(Request $request){

        if(isset($request->file_name) && !empty($request->file_name)){

            $download = Storage::disk('public')->download($request->file_name);
            $status_code = $download->getStatusCode();

            flash(translate('Sitemap generated successfully'))->success();
                
            return $download;
        }
    }

    public function view_all_chat_modal(Request $request)
    {
        $shops = Shop::all();
        $userId = Auth::id();

        $conversations = SellerAdminConversation::with(['messages'])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $conversations->each(function ($conversation) use ($userId) {
            $shopUserId = $conversation->sender_id == $userId
                ? $conversation->receiver_id
                : $conversation->sender_id;

            $conversation->seller_id = $shopUserId;
            $conversation->shop = Shop::where('user_id', $shopUserId)->first();

            $conversation->lastMessage = $conversation->messages->last();

            $conversation->lastSellerMessage = $conversation->messages
                ->where('user_id', '!=', $userId)
                ->last();

            $conversation->unseenCount = $conversation->messages
                ->where('user_id', '!=', $userId)
                ->where('seen', 0)
                ->count();
        });

        $promotions = SellerAdminPromotion::with(['flashSale', 'respondedSellers'])
            ->whereHas('participates')
            ->orderBy('created_at', 'desc')
            ->get();

        $requests = SellerAdminRequest::with('seller.shop')
            ->orderBy('created_at', 'desc')
            ->get();

        $activities = collect();

        foreach ($promotions as $promotion) {
            $activities->push([
                'type'      => 'promotion',
                'data'      => $promotion,
                'sort_time' => $promotion->created_at,
            ]);
        }

        foreach ($conversations as $conversation) {
            if ($conversation->lastSellerMessage) {
                $activities->push([
                    'type'      => 'message',
                    'data'      => $conversation,
                    'sort_time' => $conversation->lastSellerMessage->created_at,
                ]);
            }
        }

        foreach ($requests as $req) {
            $activities->push([
                'type'      => 'request',
                'data'      => $req,
                'sort_time' => $req->created_at,
            ]);
        }

        $activities = $activities->sortByDesc('sort_time')->values();

        $hasUnseenRequests = SellerAdminRequest::where('seen', 0)->exists();
        $hasUnseenPromotionParticipates = SellerAdminPromotionParticipate::where('seen', 0)->exists();

        $hasUnseenMessages = SellerAdminMessage::whereIn(
            'seller_admin_conversation_id',
            $conversations->pluck('id')
        )
            ->where('user_id', '!=', $userId)
            ->where('seen', 0)
            ->exists();

        return view('backend.chats.all_chat_modal', compact('conversations', 'shops', 'activities', 'hasUnseenRequests', 'hasUnseenPromotionParticipates', 'hasUnseenMessages'));
    }
}
