<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ShopVerificationNotification;
use App\Services\PreorderService;
use App\Utility\EmailUtility;
use Cache;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Notification;
use Log;

class SellerController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_seller|view_all_seller_rating_and_followers'])->only('index');
        $this->middleware(['permission:add_seller'])->only('create');
        $this->middleware(['permission:view_seller_profile'])->only('sellerProfile');
        $this->middleware(['permission:login_as_seller'])->only('login');
        $this->middleware(['permission:pay_to_seller'])->only('payment_modal');
        $this->middleware(['permission:edit_seller'])->only('edit');
        $this->middleware(['permission:delete_seller'])->only('destroy');
        $this->middleware(['permission:ban_seller'])->only('ban');
        $this->middleware(['permission:edit_seller_custom_followers'])->only('editSellerCustomFollowers');
        $this->middleware(['permission:view_pending_seller'])->only('pendingSellers');
        $this->middleware(['permission:mark_seller_suspected'])->only('suspicious');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = $request->search ?? null;
        $approved = $request->approved_status ?? null;
        $verification_status =  $request->verification_status ?? null;
        $seller_type = $request->seller_type ?? null;

        $shops = Shop::where('registration_approval', 1)->whereIn('user_id', function ($query) {
            $query->select('id')
                ->from(with(new User)->getTable())
                ->where('user_type', 'seller');
        })->latest();

        if ($sort_search != null || $verification_status != null || $seller_type != null) {

            $shop_user_ids = [];

            if ($sort_search != null) {
                $shop_user_ids = Shop::where('name', 'like', '%' . $sort_search . '%')
                    ->pluck('user_id')
                    ->toArray();
            }
            $user_query = User::where('user_type', 'seller');

            if ($sort_search != null) {
                $user_query = $user_query->where(function ($user) use ($sort_search) {
                    $user->where('name', 'like', '%' . $sort_search . '%')
                        ->orWhere('email', 'like', '%' . $sort_search . '%')
                        ->orWhere('phone', 'like', '%' . $sort_search . '%');
                });
            }
            if ($verification_status != null) {

                if ($verification_status == 'verified') {
                    $user_query = $user_query->whereNotNull('email_verified_at');
                } else {
                    $user_query = $user_query->whereNull('email_verified_at');
                }
            }
            if ($seller_type != null) {
                if ($seller_type == 'banned') {
                    $user_query = $user_query->where('banned',1);
                } elseif($seller_type == 'suspicious') {
                    $user_query = $user_query->where('is_suspicious',1);
                }
            }

            $user_ids = $user_query->pluck('id')->toArray();

            $all_user_ids = array_unique(array_merge($shop_user_ids, $user_ids));
            $shops = $shops->whereIn('user_id', $all_user_ids);
        }
        if ($approved != null) {
            $shops = $shops->where('verification_status', $approved);
        }
        $shops = $shops->paginate(15);
        if ($request->ajax()) {
            $view = view('backend.sellers.all_seller_table', compact('shops', 'sort_search'))->render();
            return response()->json(['html' => $view]);
        }
        $seller_types= ['All Seller' , 'Banned', 'Suspicious'];
        return view('backend.sellers.index', compact('shops', 'sort_search', 'approved', 'verification_status','seller_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.sellers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'shop_name' => 'max:200',
                'address' => 'max:500',
            ],
            [
                'name.required' => translate('Name is required'),
                'name.max' => translate('Max 255 Character'),
                'email.required' => translate('Email is required'),
                'email.email' => translate('Email must be a valid email address'),
                'email.unique' => translate('An user exists with this email'),
                'shop_name.max' => translate('Max 200 Character'),
                'address.max' => translate('Max 255 Character'),
            ]
        );


        if (User::where('email', $request->email)->first() != null) {
            flash(translate('Email already exists!'))->error();
            return back();
        }
        $password = substr(hash('sha512', rand()), 0, 8);

        $user           = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($password);

        if ($user->save()) {
            $shop           = new Shop;
            $shop->user_id  = $user->id;
            $shop->name     = $request->shop_name;
            $shop->address  = $request->address;
            $shop->slug     = 'demo-shop-' . $user->id;
            $shop->save();

            try {
                EmailUtility::selelr_registration_email('registration_from_system_email_to_seller', $user, $password);
            } catch (\Exception $e) {
                $shop->delete();
                $user->delete();
                flash(translate('Registration failed. Please try again later.'))->error();
                return back();
            }

            // Verification email send
            if (get_setting('email_verification') != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
            } else {
                EmailUtility::email_verification($user, 'seller');
            }

            // Seller Account Opening Email to Admin
            if ((get_email_template_data('seller_reg_email_to_admin', 'status') == 1)) {
                try {
                    EmailUtility::selelr_registration_email('seller_reg_email_to_admin', $user, null);
                } catch (\Exception $e) {
                }
            }

            flash(translate('Seller has been added successfully'))->success();
            return back();
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = Shop::findOrFail(decrypt($id));
        return view('backend.sellers.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $user = $shop->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            if ($shop->save()) {
                flash(translate('Seller has been updated successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result =  $this->single_seller_delete($id);
        if ($result) {
           flash(translate('Seller has been deleted successfully'))->success();
           return redirect()->route('sellers.index');
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    function single_seller_delete($id){
         $shop = Shop::findOrFail($id);

        // Seller Product and product related data delete
        $products = $shop->user->products;
        foreach ($products as $product) {
            $product_id = $product->id;
            $product->product_translations()->delete();
            $product->categories()->detach();
            $product->stocks()->delete();
            $product->taxes()->delete();
            $product->frequently_bought_products()->delete();
            $product->last_viewed_products()->delete();
            $product->flash_deal_products()->delete();

            if ($product->delete()) {
                Cart::where('product_id', $product_id)->delete();
                Wishlist::where('product_id', $product_id)->delete();
            }
        }

        $orders = Order::where('user_id', $shop->user_id)->get();

        foreach ($orders as $key => $order) {
            OrderDetail::where('order_id', $order->id)->delete();
        }
        Order::where('user_id', $shop->user_id)->delete();

        // If Preorder addon is installed, delete preorder products and related data.
        if (Addon::where('unique_identifier', 'preorder')->first()) {
            $preorderProducts = $shop->user->preorderProducts;
            foreach ($preorderProducts as $preorderProduct) {
                (new PreorderService)->productdestroy($preorderProduct->id);
            }
        }

        User::destroy($shop->user->id);

        if (Shop::destroy($id)) {
            return 1;
        }
    }

    public function ajaxDestroy($id)
    {
        $this->single_seller_delete($id);
        
        return response()->json([
            'success' => true,
            'message' => translate('Seller has been deleted successfully'),
            'data' => ['id' => $id]
        ]);
    }

    public function bulk_seller_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $shop_id) {
                $this->single_seller_delete($shop_id);
            }
        }
        return 1;
    }

    

    public function show_verification_request($id)
    {
        $shop = Shop::findOrFail($id);
        return view('backend.sellers.verification', compact('shop'));
    }

    public function approve_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 1;
        $shop->save();
        Cache::forget('verified_sellers_id');

        $users = User::findMany([$shop->user->id]);
        $data = array();
        $data['shop'] = $shop;
        $data['status'] = 'approved';
        $data['notification_type_id'] = get_notification_type('shop_verify_request_approved', 'type')->id;
        Notification::send($users, new ShopVerificationNotification($data));

        flash(translate('Seller has been approved successfully'))->success();
        return back();
    }

    public function reject_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 0;
        $shop->verification_info = null;
        $shop->save();
        Cache::forget('verified_sellers_id');

        $users = User::findMany([$shop->user->id]);
        $data = array();
        $data['shop'] = $shop;
        $data['status'] = 'rejected';
        $data['notification_type_id'] = get_notification_type('shop_verify_request_rejected', 'type')->id;
        Notification::send($users, new ShopVerificationNotification($data));

        flash(translate('Seller verification request has been rejected successfully'))->success();
        return back();
    }


    public function payment_modal(Request $request)
    {
        $shop = shop::findOrFail($request->id);
        return view('backend.sellers.payment_modal', compact('shop'));
    }

    public function verification_info_modal(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        return view('backend.sellers.verification_info_modal', compact('shop'));
    }

    public function updateApproved(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->verification_status = $request->status;
        $shop->save();
        Cache::forget('verified_sellers_id');

        $status = $request->status == 1 ? 'approved' : 'rejected';
        $users = User::findMany([$shop->user->id]);
        $data = array();
        $data['shop'] = $shop;
        $data['status'] = $status;
        $data['notification_type_id'] = $status == 'approved' ?
            get_notification_type('shop_verify_request_approved', 'type')->id :
            get_notification_type('shop_verify_request_rejected', 'type')->id;

        Notification::send($users, new ShopVerificationNotification($data));
        return 1;
    }

    public function login($id)
    {
        $shop = Shop::findOrFail(decrypt($id));
        $user  = $shop->user;
        auth()->login($user, true);

        return redirect()->route('seller.dashboard');
    }

    public function ban($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->user->banned == 1) {
            $shop->user->banned = 0;
            if ($shop->verification_info) {
                $shop->verification_status = 1;
            }
            flash(translate('Seller has been unbanned successfully'))->success();
        } else {
            $shop->user->banned = 1;
            $shop->verification_status = 0;
            flash(translate('Seller has been banned successfully'))->success();
        }
        $shop->save();
        $shop->user->save();
        return back();
    }

    // Seller Based Commission
    public function sellerBasedCommission(Request $request)
    {
        $sort_search = $request->search ?? null;
        $approved = $request->approved_status ?? null;
        $verification_status =  $request->verification_status ?? null;

        $shops = Shop::whereIn('user_id', function ($query) {
            $query->select('id')
                ->from(with(new User)->getTable())
                ->where('user_type', 'seller');
        })->latest();

        if ($sort_search != null || $verification_status != null) {
            $user_ids = User::where('user_type', 'seller');
            if ($sort_search != null) {
                $user_ids = $user_ids->where(function ($user) use ($sort_search) {
                    $user->where('name', 'like', '%' . $sort_search . '%')
                        ->orWhere('email', 'like', '%' . $sort_search . '%')
                        ->orWhere('phone', 'like', '%' . $sort_search . '%');
                });
            }
            if ($verification_status != null) {
                $user_ids = $verification_status == 'verified' ? $user_ids->where('email_verified_at', '!=', null) : $user_ids->where('email_verified_at', null);
            }
            $user_ids = $user_ids->pluck('id')->toArray();
            $shops = $shops->where(function ($shops) use ($user_ids) {
                $shops->whereIn('user_id', $user_ids);
            });
        }
        if ($approved != null) {
            $shops = $shops->where('verification_status', $approved);
        }
        $shops = $shops->paginate(15);
        return view('backend.sellers.seller_based_commission.set_commission', compact('shops', 'sort_search', 'approved', 'verification_status'));
    }



    public function setSellerBasedCommission(Request $request)
    {
        if ($request->seller_ids != null && $request->commission_percentage != null) {
            foreach (explode(",", $request->seller_ids) as $shopId) {
                $shop = Shop::where('id', $shopId)->first();
                if($shop) {
                    $shop->commission_percentage = $request->commission_percentage;
                    $shop->save();
                }
            }
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    public function setSellerCommission(Request $request)
    {
        if ($request->seller_id != null) {
            $shop = Shop::where('id', $request->seller_id)->first();
            $shop->commission_percentage = $request->commission_percentage;
            $shop->save();

            return 1;
        } else {
            return 0;
        }
    }

    // Edit Seller Custom Followers
    public function editSellerCustomFollowers(Request $request)
    {
        $shop = Shop::where('id', $request->shop_id)->first();
        $shop->custom_followers = $request->custom_followers;
        $shop->save();
        flash(translate('Seller custom follower has been updated successfully.'))->success();
        return back();
    }

    public function pendingSellers(Request $request)
    {
        $sort_search = $request->search ?? null;
        if (addon_is_activated('portfolio_system') == 1) {
            $shops = Shop::where(function ($query) {
                    $query->where('verification_status', 0)
                        ->orWhere('registration_approval', 0);
                })
                ->with('user')
                ->latest();
        }else{
            $shops = Shop::where('registration_approval', 0)->with('user')->latest();
        }

        if ($sort_search != null) {
            $shop_user_ids = Shop::where('name', 'like', '%' . $sort_search . '%')
                ->pluck('user_id')
                ->toArray();
            $user_ids = User::where('user_type', 'seller')
                ->where(function ($query) use ($sort_search) {
                    $query->where('name', 'like', '%' . $sort_search . '%')
                        ->orWhere('email', 'like', '%' . $sort_search . '%')
                        ->orWhere('phone', 'like', '%' . $sort_search . '%');
                })
                ->pluck('id')
                ->toArray();
            $all_user_ids = array_unique(array_merge($shop_user_ids, $user_ids));
            $shops = $shops->whereIn('user_id', $all_user_ids);
        }

        $shops = $shops->paginate(15);

        if ($request->ajax()) {
            $view = view('backend.sellers.pending_seller_table', compact('shops', 'sort_search'))->render();
            return response()->json(['html' => $view]);
        }

        return view('backend.sellers.pending_seller', compact('shops', 'sort_search'));
    }

    public function UpdateSellerRegistration(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->registration_approval = $request->registration_approval;
        $request->has('verification_status') ? $shop->verification_status = $request->verification_status : 0;
        if ($shop->save()) {
            try {
                EmailUtility::seller_shop_approval_email('seller_shop_approval_email', $shop);
            } catch (\Exception $e) {
            }
            return 1;
        }
        return 0;
    }

    public function sellerProfile(Request $request)
    {
        $shop_id = decrypt($request->id);
        $shop = Shop::findOrFail($shop_id);
        $shop->last_login = $this->getsellerLastLogin($shop->user_id);
        $addresses = $shop->user->addresses->where('set_default', 0);
        $default_shipping_address = $shop->user->addresses()->where('set_default', 1)->first();
        $products = Product::where('user_id', $shop->user_id)->where('digital', 0)->where('auction_product', 0)->where('wholesale_product', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(2);
        return view('backend.sellers.profile.index', compact('shop', 'addresses', 'default_shipping_address', 'products'));
    }

    public function getSellerProfileTab(Shop $shop, Request $request)
    {
        $tab = $request->get('tab', 'overview');
        $page = $request->get('page', 1);
        $addresses = $shop->user->addresses->where('set_default', 0);
        $default_shipping_address = $shop->user->addresses()->where('set_default', 1)->first();
        $shop->last_login = $this->getsellerLastLogin($shop->user_id);
        $payments = Payment::where('seller_id', $shop->user_id)->orderBy('created_at', 'desc')->paginate(15);
        $products = Product::where('user_id', $shop->user_id)->where('digital', 0)->where('auction_product', 0)->where('wholesale_product', 0)->orderBy('created_at', 'desc')->paginate(15);
        $type = 'SellerProfile';
        $unpaid_order_payment_notification = get_notification_type('complete_unpaid_order_payment', 'type');
        $orders = Order::where('seller_id', $shop->user_id)
            ->orderBy('id', 'desc')
            ->select('orders.id')
            ->distinct()->paginate(15);
        $html = view('backend.sellers.profile.seller_' . $tab, compact('products', 'shop', 'addresses', 'default_shipping_address', 'page', 'orders', 'type', 'unpaid_order_payment_notification', 'payments'))->render();
        return response()->json(['html' => $html]);
    }

    private function getsellerLastLogin($user_id)
    {
        $logFile = storage_path('logs/seller_login.log');
        $lastLoginTime = null;

        if (File::exists($logFile)) {
            $lines = array_reverse(File::lines($logFile)->toArray());

            foreach ($lines as $line) {
                if (str_contains($line, '"user_id":' . $user_id)) {

                    $jsonStart = strpos($line, '{');
                    if ($jsonStart !== false) {
                        $jsonData = json_decode(substr($line, $jsonStart), true);
                        if ($jsonData && isset($jsonData['time'])) {
                            $lastLoginTime = Carbon::parse($jsonData['time']);
                            break;
                        }
                    }
                }
            }
            return $lastLoginTime;
        }
        return null;
    }

    public function suspicious($id)
    {
        $user = User::findOrFail(decrypt($id));

        if ($user->is_suspicious == 1) {
            $user->is_suspicious = 0;
            flash(translate('Sellert unsuspected  Successfully'))->success();
        } else {
            $user->is_suspicious = 1;
            flash(translate('Seller suspected Successfully'))->success();
        }

        $user->save();

        return back();
    }

    public function deleteVerificationFile(Request $request)
    {
        try {
            $index = $request->input('index');
            $shopId = $request->input('shop_id');
            $filePath = $request->input('file_path');
            $shop = Shop::find($shopId);
            $verificationInfo = json_decode($shop->verification_info, true);
            if (file_exists(public_path($filePath))) {
                @unlink(public_path($filePath));
            }

            unset($verificationInfo[$index]);
            $verificationInfo = array_values($verificationInfo);
            $shop->verification_info = json_encode($verificationInfo);
            $shop->save();
            flash(translate('Verification file deleted successfully'))->success();
            return back();
        } catch (\Exception $e) {
            flash(translate('Failed to delete verification file. Please try again later.'))->error();
            return back();
        }
    }

    
    public function sellers_gstin_update(Request $request){
        $shop = Shop::findOrFail(decrypt($request->id));
        $business_info = json_decode($shop->business_info, true) ?? [];
        $business_info['gstin'] = $request->gstin_number;
        $shop->business_info = json_encode($business_info);
        if ($shop->save()) {
            flash(translate('Seller GSTIN has been updated successfully!'))->success();
            return back();
        }
    }
}
