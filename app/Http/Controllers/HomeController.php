<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Cache;
use Cookie;
use App\Models\Page;
use App\Models\Shop;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\OrderDetail;
use App\Models\ProductQuery;
use Illuminate\Http\Request;
use App\Models\AffiliateConfig;
use App\Models\Blog;
use App\Models\BusinessSetting;
use App\Models\CustomerPackage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\Cart;
use App\Models\Preorder;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;
use App\Models\PreorderProduct;
use App\Models\RegistrationVerificationCode;
use App\Models\SmsTemplate;
use App\Services\FacebookConversionService;
use App\Services\SendSmsService;
use App\Utility\EmailUtility;
use Artisan;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use ZipArchive;
use Carbon\Carbon;
use Session;

class HomeController extends Controller
{
    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lang = get_system_language() ? get_system_language()->code : null;
        $featured_categories = Cache::rememberForever('featured_categories', function () {
            return Category::with('bannerImage')->where('featured', 1)->get();
        });
        $hot_categories = Cache::rememberForever('hot_categories', function () {
            return Category::with('bannerImage')->where('hot_category', '1')->get();
        });

        $authUser = Auth::user();
        if (addon_is_activated('portfolio_system')) {
            $goingons = Blog::where('status', 1)->where('going_on', 1)->latest()->get();
            if (!auth()->check()) {
                return view('frontend.portfolio.index', compact('lang','goingons'));
            }
            //dd($authUser->shop);
            if (($authUser->verification_status == 0) ||( $authUser->shop && $authUser->shop->verification_status == 0)) {
                return view('frontend.portfolio.index', compact('lang','goingons'));
            }
        }


        return view('frontend.' . get_setting('homepage_select') . '.index', compact('featured_categories','hot_categories', 'lang'));
    }

    public function load_todays_deal_section()
    {
        $todays_deal_products = filter_products(Product::where('todays_deal', '1'))->orderBy('id', 'desc')->get()->shuffle();
        return view('frontend.' . get_setting('homepage_select') . '.partials.todays_deal', compact('todays_deal_products'));
    }

    public function load_newest_product_section(Request $request)
    {
        $limit = $request->limit ?? 12;

        if ($request->has('page') && is_numeric($request->page)) {

            $page = max(1, (int)$request->page);
            $offset = ($page - 1) * $limit;

            $newest_products = filter_products(Product::latest())
                ->skip($offset)
                ->take($limit)
                ->get()
                ->shuffle();

            return view(
                'frontend.' . get_setting('homepage_select') . '.partials.newest_products_section',
                compact('newest_products')
            );
        }

        $newest_products = Cache::remember(
            'newest_products_' . $limit,
            3600,
            function () use ($limit) {
                return filter_products(Product::latest())
                    ->take($limit)
                    ->get()
                    ->shuffle();
            }
        );

        return view(
            'frontend.' . get_setting('homepage_select') . '.partials.newest_products_section',
            compact('newest_products')
        );
    }

    public function load_featured_section()
    {
        return view('frontend.' . get_setting('homepage_select') . '.partials.featured_products_section');
    }

    public function load_best_selling_section()
    {
        return view('frontend.' . get_setting('homepage_select') . '.partials.best_selling_section');
    }

    public function load_auction_products_section()
    {
        if (!addon_is_activated('auction')) {
            return;
        }
        $lang = get_system_language() ? get_system_language()->code : null;
        return view('auction.frontend.' . get_setting('homepage_select') . '.auction_products_section', compact('lang'));
    }

    public function load_home_categories_section()
    {
        return view('frontend.' . get_setting('homepage_select') . '.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.' . get_setting('homepage_select') . '.partials.best_sellers_section');
    }
    public function load_preorder_featured_products_section()
    {

        // $preorder_products = Cache::remember('preorder_products', 3600, function () {
            $preorder_products = PreorderProduct::where('is_published', 1)->where('is_featured',1)
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('user_type', 'admin');
                })->orWhereHas('user.shop', function ($q) {
                    $q->where('verification_status', 1);
                });
            })
            ->latest()
            ->limit(12)
            ->get()
            ->shuffle();
        // });
        return view('frontend.' . get_setting('homepage_select') . '.partials.preorder_products_section', compact('preorder_products'));
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        if (Route::currentRouteName() == 'seller.login' && get_setting('vendor_system_activation') == 1) {
            return view('auth.' . get_setting('authentication_layout_select') . '.seller_login');
        } else if (Route::currentRouteName() == 'deliveryboy.login' && addon_is_activated('delivery_boy')) {
            return view('auth.' . get_setting('authentication_layout_select') . '.deliveryboy_login');
        }
        return view('auth.' . get_setting('authentication_layout_select') . '.user_login');
    }


    // public function verifyRegEmailorPhone(){
    //     $type = 'customer';
    //     if (Auth::check()) {
    //         if ((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller')) {
    //             flash(translate('Admin or seller cannot be a customer'))->error();
    //             return back();
    //         }
    //         if (Auth::user()->user_type == 'customer') {
    //             flash(translate('This user already a customer'))->error();
    //             return back();
    //         }
    //     } else {
    //         return view('auth.' . get_setting('authentication_layout_select') . '.customer_reg_verification', compact('type'));
    //     }
    // }

    public function registration(Request $request)
    {
        // if(get_setting('customer_registration_verify') === '1' ){
        //     abort(404);
        // }

        if (Auth::check()) {
            return redirect()->route('home');
        }


        if ($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }
        $email = null;
        $phone = null;
        return view('auth.' . get_setting('authentication_layout_select') . '.user_registration', compact('email','phone'));
    }

    public function cart_login(Request $request)
    {
        $user = null;
        if ($request->get('phone') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        } elseif ($request->get('email') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        } else {
            flash(translate('Invalid email or password!'))->warning();
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            $users_cart = Cart::where('user_id', auth()->user()->id)->first();
            if ($users_cart) {
                flash(translate('You had placed your items in the shopping cart. Try to order before the product quantity runs out.'))->warning();
            }
            return view('frontend.user.customer.dashboard');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.profile.index');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.profile');
        } else {
            return view('frontend.user.profile');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;
        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function userVerifyInfoUpdate(Request $request)
    {
        //dd($request->all());
        $user = Auth::user();
        $verification_info = json_decode($user->verification_info, true) ?? [];

            if ($request->hasFile('id_card')) {
                if (!empty($verification_info['id_card']) &&
                    file_exists(public_path($verification_info['id_card']))) {
                    unlink(public_path($verification_info['id_card']));
                }
                $verification_info['id_card'] =
                $request->file('id_card')->store('uploads/verification_form');
            }
            if ($request->hasFile('customer_photo')) {
                if (!empty($verification_info['customer_photo']) &&
                    file_exists(public_path($verification_info['customer_photo']))) {
                    unlink(public_path($verification_info['customer_photo']));
                }
                $verification_info['customer_photo'] =
                $request->file('customer_photo')->store('uploads/verification_form');
            }

            if ($request->live_selfie) {
                if (!empty($verification_info['customer_selfie']) &&
                    file_exists(public_path($verification_info['customer_selfie']))) {
                    unlink(public_path($verification_info['customer_selfie']));
                }
                $image = $request->live_selfie;  // your base64 encoded
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'uploads/verification_form/customer_selfie_' . time() . '.png';
                \File::put(public_path($imageName), base64_decode($image));
                $verification_info['customer_selfie'] = $imageName;
            }

        $user->verification_info = json_encode($verification_info);


        if ($user->save()) {
            flash(translate('Documents submitted successfully!, Please wait for verification.'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function flash_deal_details($slug)
    {
        $today = strtotime(date('Y-m-d H:i:s'));
        $flash_deal = FlashDeal::where('slug', $slug)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $searchCode = session('search_code');

        if ($request->tracking_code) {
            $searchCode = $request->tracking_code;

            return redirect()
                ->route('orders.track')
                ->with('search_code', $searchCode);
        }

        if ($searchCode) {
            $query = Order::where(function($q) use ($searchCode) {
                $q->where('tracking_code', $searchCode);
            });

            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            }

            $order = $query->first();
        }
        $searched = !is_null($searchCode);

        return view('frontend.track_order', compact('order', 'searched'));
    }

    public function product(Request $request, $slug)
    {
        if (!Auth::check()) {
            session(['link' => url()->current()]);
        }

        $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first();

        if ($detailedProduct != null && $detailedProduct->published) {
            if ((get_setting('vendor_system_activation') != 1) && $detailedProduct->added_by == 'seller') {
                abort(404);
            }

            if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->banned == 1) {
                abort(404);
            }

            if (!addon_is_activated('wholesale') && $detailedProduct->wholesale_product == 1) {
                abort(404);
            }

            $product_queries = ProductQuery::where('product_id', $detailedProduct->id)->where('customer_id', '!=', Auth::id())->latest('id')->paginate(3);
            $total_query = ProductQuery::where('product_id', $detailedProduct->id)->count();
            $reviews = $detailedProduct->reviews()->where('status', 1)->orderBy('created_at', 'desc')->paginate(3);

            // Pagination using Ajax
            if (request()->ajax()) {
                if ($request->type == 'query') {
                    return Response::json(View::make('frontend.partials.product_query_pagination', array('product_queries' => $product_queries))->render());
                }
                if ($request->type == 'review') {
                    return Response::json(View::make('frontend.product_details.reviews', array('reviews' => $reviews))->render());
                }
            }

            // review status
            $review_status = 0;
            $order_id = '';
            if (Auth::check()) {
                $OrderDetail = OrderDetail::with(['order' => function ($q) {
                    $q->where('user_id', Auth::id());
                }])->where('product_id', $detailedProduct->id)->where('delivery_status', 'delivered')->first();
                $review_status = $OrderDetail ? 1 : 0;
                $order_id = $OrderDetail->order->id ?? null ;
            }
            if ($request->has('product_referral_code') && addon_is_activated('affiliate_system')) {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }
                Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
                Cookie::queue('referred_product_id', $detailedProduct->id, $cookie_minute);

                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            }

            if(get_setting('last_viewed_product_activation') == 1 && Auth::check() && auth()->user()->user_type == 'customer'){
                lastViewedProducts($detailedProduct->id, auth()->user()->id);
            }

            if(get_setting('facebook_pixel_capi') == 1){
                $fb = new FacebookConversionService();
                $fb->sendViewContent($detailedProduct);
            }
            return view('frontend.product_details', compact('detailedProduct', 'product_queries', 'total_query', 'reviews', 'review_status', 'order_id'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            if ($shop->user->banned == 1) {
                abort(404);
            }
            if ($shop->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop'));
            }
        }
        abort(404);
    }

    public function filter_shop(Request $request, $slug, $type)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {
            if ($shop->user->banned == 1) {
                abort(404);
            }
            if ($type == 'all-products') {
                $sort_by = $request->sort_by;
                $min_price = $request->min_price;
                $max_price = $request->max_price;
                $selected_categories = array();
                $brand_id = null;
                $rating = null;

                $conditions = ['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1];

                if ($request->brand != null) {
                    $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
                    $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
                }

                $products = Product::where($conditions);

                if ($request->has('selected_categories')) {
                    $selected_categories = $request->selected_categories;
                    $products->whereIn('category_id', $selected_categories);
                }

                if ($min_price != null && $max_price != null) {
                    $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
                }

                if ($request->has('rating')) {
                    $rating = $request->rating;
                    $products->where('rating', '>=', $rating);
                }

                switch ($sort_by) {
                    case 'newest':
                        $products->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $products->orderBy('created_at', 'asc');
                        break;
                    case 'price-asc':
                        $products->orderBy('unit_price', 'asc');
                        break;
                    case 'price-desc':
                        $products->orderBy('unit_price', 'desc');
                        break;
                    default:
                        $products->orderBy('id', 'desc');
                        break;
                }

                $products = $products->paginate(24)->appends(request()->query());

                return view('frontend.seller_shop', compact('shop', 'type', 'products', 'selected_categories', 'min_price', 'max_price', 'brand_id', 'sort_by', 'rating'));
            }

            if ($type == 'all-preorder-products') {
                $sort_by = $request->sort_by;
                $min_price = $request->min_price;
                $max_price = $request->max_price;
                $selected_categories = array();
                $is_available = array();
                $brand_id = null;
                $rating = null;

                $conditions = ['user_id' => $shop->user->id, 'is_published' => 1];

                if ($request->brand != null) {
                    $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
                    $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
                }

                $products = PreorderProduct::where('is_published',1)->where('user_id' , $shop->user->id);

                if ($request->has('is_available') && $request->is_available !== null) {
                    $availability = $request->is_available;
                    $currentDate = Carbon::now()->format('Y-m-d');
                    $products->where(function ($query) use ($availability, $currentDate) {
                        if ($availability == 1) {
                            $query->where('is_available', 1)->orWhere('available_date', '<=', $currentDate);
                        } else {
                            $query->where(function ($query) {
                                $query->where('is_available', '!=', 1)
                                    ->orWhereNull('is_available');
                            })
                                ->where(function ($query) use ($currentDate) {
                                    $query->whereNull('available_date')
                                        ->orWhere('available_date', '>', $currentDate);
                                });
                        }
                    });

                    $is_available = $availability;
                } else {
                    $is_available = null;

                }


                if ($request->has('selected_categories')) {
                    $selected_categories = $request->selected_categories;
                    $products->whereIn('category_id', $selected_categories);
                }

                if ($min_price != null && $max_price != null) {
                    $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
                }

                if ($request->has('rating')) {
                    $rating = $request->rating;
                    $products->where('rating', '>=', $rating);
                }

                switch ($sort_by) {
                    case 'newest':
                        $products->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $products->orderBy('created_at', 'asc');
                        break;
                    case 'price-asc':
                        $products->orderBy('unit_price', 'asc');
                        break;
                    case 'price-desc':
                        $products->orderBy('unit_price', 'desc');
                        break;
                    default:
                        $products->orderBy('id', 'desc');
                        break;
                }

                $products = $products->paginate(24)->appends(request()->query());

                return view('frontend.seller_shop', compact('shop', 'type', 'products', 'selected_categories', 'min_price', 'max_price', 'brand_id', 'sort_by', 'rating','is_available'));
            }

            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::with('childrenCategories')->where('parent_id', 0)->orderBy('order_level', 'desc')->get();

        // dd($categories);
        return view('frontend.all_category', compact('categories'));
    }

    public function all_brands(Request $request)
    {
        $brands = Brand::all();
        return view('frontend.all_brand', compact('brands'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if (json_decode($product->choice_options) != null) {
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        $product_stock = $product->stocks->where('variant', $str)->first();

        $price = $product_stock->price;
        $image = $product_stock->image ?? '';


        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $quantity = $product_stock->qty;
        $max_limit = $product_stock->qty;

        if ($quantity >= 1 && $product->min_qty <= $quantity) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }

        //Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($quantity >= 1 && $product->min_qty < $quantity) {
                $quantity = translate('In Stock');
            } else {
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        // taxes
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;
        if (addon_is_activated('gst_system')) {
        $price += ($price * $product->gst_rate) / 100;
        }

        $sku= $product_stock->sku ?? 'N/A';

        return array(
            'price' => single_price($price * $request->quantity),
            'quantity' => $quantity,
            'digital' => $product->digital,
            'variation' => $str,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock,
            'sku'      => $sku,
            'image' => $image
        );
    }

    public function sellerpolicy()
    {
        $page =  Page::where('type', 'seller_policy_page')->first();
        return view("frontend.policies.sellerpolicy", compact('page'));
    }

    public function returnpolicy()
    {
        $page =  Page::where('type', 'return_policy_page')->first();
        return view("frontend.policies.returnpolicy", compact('page'));
    }

    public function supportpolicy()
    {
        $page =  Page::where('type', 'support_policy_page')->first();
        return view("frontend.policies.supportpolicy", compact('page'));
    }

    public function terms()
    {
        $page =  Page::where('type', 'terms_conditions_page')->first();
        return view("frontend.policies.terms", compact('page'));
    }

    public function privacypolicy()
    {
        $page =  Page::where('type', 'privacy_policy_page')->first();
        return view("frontend.policies.privacypolicy", compact('page'));
    }


    public function get_category_items(Request $request)
    {
        $categories = Category::with('childrenCategories')->findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('categories'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }


    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = translate('Email already exists!');
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $customerVerification = RegistrationVerificationCode::where('code', $request->code);
            $customerVerification = $customerVerification->where('email', $email);
            $customerVerification = $customerVerification->first();
            if ($customerVerification == null) {
                flash(translate('Verification code do not matched'))->error();
                return back();
            } else {
                $this->send_email_change_verification_mail($request, $email);
                flash(translate('A verification mail has been sent to the new email address you provided.'))->success();
                return back();
            }
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $user = auth()->user();
        $response['status'] = 0;
        $response['message'] = 'Unknown';
        try {
            EmailUtility::change_email_verification($user, $user->user_type, $email);
            $response['status'] = 1;
            $response['message'] = translate("A verification mail has been sent to your new mail you provided us with.");
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                if ($user->user_type == 'seller') {
                    return redirect()->route('seller.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }

    public function reset_password_with_code(Request $request)
    {
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                flash(translate("Password and confirm password didn't match"))->warning();
                $email = $user->email;
                return view('auth.'.get_setting('authentication_layout_select').'.reset_password', compact('email'));
            }
        } else {
            flash(translate("Verification code mismatch"))->error();
            $email = $request->email;
            return view('auth.'.get_setting('authentication_layout_select').'.reset_password', compact('email'));
        }
    }


    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function todays_deal()
    {
        $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
            return filter_products(Product::with('thumbnail')->where('todays_deal', '1'))->get();
        });

        return view("frontend.todays_deal", compact('todays_deal_products'));
    }

    public function best_selling()
    {
        $title= translate('Best Selling');
        $best_selling_products =  filter_products(Product::orderBy('num_of_sale', 'desc'))->take(18)->get();
        return view("frontend.best_selling", compact('best_selling_products','title'));
    }

    public function same_sellers_products($slug)
    {
        $has_pagination =1;
        if ($slug === 'in-house') {
            $best_selling_products = filter_products(
            Product::where('added_by', 'admin'))->paginate(18);
            $title = translate('In-House');
            return view('frontend.best_selling', compact(
                'best_selling_products',
                'title','has_pagination'
            ));
        }
        $shop = Shop::where('slug', $slug)->firstOrFail();
        $best_selling_products = filter_products(
        Product::where('user_id', $shop->user_id))->paginate(18);
        $title = $shop->name;
        return view('frontend.best_selling', compact(
            'best_selling_products',
            'title','has_pagination'
        ));
    }

    public function featured_products()
    {
        $featured_products =  filter_products(Product::where('featured', '1'))->latest()->limit(12)->get();
        return view("frontend.featured_products", compact('featured_products'));
    }


    public function all_seller(Request $request)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }
        $shops = Shop::whereIn('user_id', verified_sellers_id())
            ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('status', 1)->where(function ($query) {
            $query->where('type', 'welcome_base')->orWhere(function ($query) {
                $query->where('type', '!=', 'welcome_base')->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')));
            });
        })->paginate(15);

        return view('frontend.coupons', compact('coupons'));
    }

    public function inhouse_products(Request $request)
    {
        $products = filter_products(Product::where('added_by', 'admin'))->with('taxes')->paginate(12)->appends(request()->query());
        return view('frontend.inhouse_products', compact('products'));
    }

    public function sendRegVerificationCode(Request $request)
    {
         $request->validate([
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1, ['required', new Recaptcha()], ['sometimes'])
            ],
        ]);

        $email = $request->email ?? null;
        $phone = $request->phone != null ? '+' . $request->country_code . preg_replace('/\D+/', '', $request->phone) : null;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $email)->first() != null) {                
                return response()->json(['status' => 0, 'message' => translate('Email already exists.')]);
            }
        } elseif (User::where('phone', $phone)->first() != null) {
            return response()->json(['status' => 0, 'message' => translate('Phone already exists.')]);
        }

        $verificationCode = rand(100000, 999999);
        $customerVerification = RegistrationVerificationCode::updateOrCreate(
            ['email' => $email, 'phone' => $phone],
            ['code' => $verificationCode]
        );
        $success = 1;

        if ($email) {
            try {
                EmailUtility::email_verification_for_registration_customer('email_verification_for_registration_customer', $email, $verificationCode);
            } catch (\Exception $e) {
                $success = 0;
            }
        } else {
            if (addon_is_activated('otp_system')) {
                $sms_template   = SmsTemplate::where('identifier', 'phone_number_verification')->first();
                $sms_body       = $sms_template->sms_body;
                $sms_body       = str_replace('[[code]]', $verificationCode, $sms_body);
                $sms_body       = str_replace('[[site_name]]', env('APP_NAME'), $sms_body);
                $template_id    = $sms_template->template_id;

                (new SendSmsService())->sendSMS($phone, env('APP_NAME'), $sms_body, $template_id);

            }
        }

        // if ($success) {
        //     return redirect()->route('customer-reg.verify_code', encrypt($customerVerification->id));
        // } else {
        //     flash(translate('Something went wrong!'))->error();
        //     return back();
        // }
        if ($success) {
            return response()->json(['status' => 1, 'message' => translate('Verification code sent successfully.')]);
        } else {
            return response()->json(['status' => 0, 'message' => translate('Verification code sending failed.')]);
        }
    }

    public function regVerifyCode($id)
    {
        // $customerVerification = $id;
        $customerVerification = RegistrationVerificationCode::whereId(decrypt($id))->first();
        return view('auth.' . get_setting('authentication_layout_select') . '.customer_verify_confirmation', compact('customerVerification'));
    }


    public function wallet_recharge_success()
    {
        if (Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.wallet_recharge_success');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('frontend.user.customer.dashboard');
        } else {
            abort(404);
        }
    }

    public function regVerifyCodeConfirmation(Request $request)
    {
        $email = isset($request->email) ? $request->email : null;
         $phone = $request->phone != null ? '+' . $request->country_code . $request->phone : null;

        $customerVerification = RegistrationVerificationCode::where('code', $request->verification_code);
        $customerVerification = $request->email != null ?
            $customerVerification->where('email', $email) :
            $customerVerification->where('phone', $phone);
        $customerVerification = $customerVerification->first();
        if ($customerVerification == null) {
            return response()->json(['status' => 0, 'message' => translate('Verification Code did not match')]);
        } else {
            $customerVerification->is_verified = 1;
            $customerVerification->save();
            return response()->json(['status' => 1, 'message' => translate('Verification Successful')]);
        }
    }

    public function sendEmailUpdateVerificationCode(Request $request)
    {
        $user = auth()->user();
        $phone = $request->phone != null ? '+' . $request->country_code . $request->phone : null;
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = translate('Email already exists!');
            return json_encode($response);
        }

        $verificationCode = rand(100000, 999999);
        $customerVerification = RegistrationVerificationCode::updateOrCreate(
            ['email' => $email, 'phone' => $phone],
            ['code' => $verificationCode]
        );

        try {
            EmailUtility::email_otp_verification_for_update_email($user, $user->user_type, $verificationCode, $email);
            $response['status'] = 1;
            $response['message'] = translate("We've sent a verification code to your previous email address.");
        } catch (\Exception $e) {
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }
        return json_encode($response);



    }

    
    public function product_reviews(Request $request) {
        $detailedProduct = Product::where('slug', $request->slug)
            ->where('approved', 1)
            ->firstOrFail();
        $query = $detailedProduct->reviews()->where('status', 1);
        if ($request->images != null) {
            $query->orderByRaw("
                CASE 
                    WHEN photos IS NOT NULL AND photos != '' THEN 0 
                    ELSE 1 
                END ASC")
            ->orderBy('created_at', 'desc');
        }else{
            switch ($request->sort_by) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'higest':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'lowest':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $limit = (int) $request->limit ?: 3;
        //Log:: info('Limit: ' . $limit);
        $totalReviews = $detailedProduct->reviews()->where('status', 1)->count();
        // Get the results
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }
        $reviews = $query->take($limit)->get();

        return response()->json([
            'html' => view('frontend.product_details.reviews', compact('reviews'))->render(),
            'has_more' => $totalReviews > $limit
        ]);
    }

    public function markOtpAlertSeen(Request $request)
    {
        $user = auth()->user();
        $user->otp_alert_seen = 1;
        $user->save();

        return response()->json([
            'status' => true
        ]);
    }

    public function resetSellerMonthlyTokens()
    {
        $limit = BusinessSetting::where('type','seller_monthly_token_limit')->value('value');

        $updated = User::whereRaw("DATE_ADD(seller_monthly_token_limit_setup_date, INTERVAL 1 MONTH) <= NOW()")
            ->update([
                'seller_monthly_token_limit' => $limit,
                'seller_monthly_token_limit_setup_date' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Monthly token reset completed',
            'updated_users' => $updated
        ]);
    }

}
