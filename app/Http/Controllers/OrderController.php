<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AffiliateController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CombinedOrder;
use App\Models\SmsTemplate;
use Auth;
use Illuminate\Support\Str;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Models\OrdersExport;
use App\Utility\NotificationUtility;
use CoreComponentRepository;
use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use App\Services\FacebookConversionService;
use App\Utility\EmailUtility;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders|view_all_offline_payment_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
    }

    // All Orders


    public function all_orders(Request $request){
       
        $order_from = '';
        $seller_types =[];
        $shipping_type ='';
        $manual_payment = '';
        $col = '';
        $status = '';

        $orders = Order::orderBy('id', 'desc')->where('order_from','pos');

        $seller_types = ['All', 'Inhouse', 'Seller'];
        $order_types ='All Orders';
         if (Route::currentRouteName() == 'inhouse_orders.index' && Auth::user()->can('view_inhouse_orders')) {
            $seller_types = ['Inhouse'];
            $order_types = 'Inhouse Orders';
        }
        elseif (Route::currentRouteName() == 'seller_orders.index' && Auth::user()->can('view_seller_orders')) {
            $seller_types = ['Seller'];
            $order_types = 'Seller Orders';
        }
        elseif (Route::currentRouteName() == 'pick_up_point.index' && Auth::user()->can('view_pickup_point_orders')) {
            $seller_types = ['All', 'Inhouse', 'Seller'];
            $order_types ='Pick-up Point Orders';
            $col = 'shipping_type';
            $status = 'pickup_point';
            if (get_setting('vendor_system_activation') != 1) {
                $seller_types = ['Inhouse'];
            }
        }
        elseif (Route::currentRouteName() == 'all_orders.index' && Auth::user()->can('view_all_orders')) {
            $seller_types = ['All', 'Inhouse', 'Seller'];
            $order_types ='All Orders';
            if (get_setting('vendor_system_activation') != 1) {
                $seller_types = ['Inhouse'];
            }
        }
        elseif (Route::currentRouteName() == 'offline_payment_orders.index' && Auth::user()->can('view_all_offline_payment_orders')) {
            $orders = $orders->where('orders.manual_payment', 1);
            $seller_types = ['All', 'Inhouse', 'Seller'];
            $order_types ='Offline Payment Orders';
            $col = 'manual_payment';
            $status = 1;
        }
        elseif (Route::currentRouteName() == 'unpaid_orders.index' && Auth::user()->can('view_all_unpaid_orders')) {
            $orders = $orders->where('orders.payment_status', 'unpaid');
            $seller_types = ['All', 'Inhouse', 'Seller'];
            $order_types = 'Unpaid Orders';
            $col = 'payment_status';
            $status = 'unpaid';
        }
        else {
            abort(403);
        }

        $unpaid_order_payment_notification = get_notification_type('complete_unpaid_order_payment', 'type');

        return view('backend.sales.index', compact('orders','seller_types','order_from','order_types','unpaid_order_payment_notification', 'col', 'status'));

    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
                ->where('user_type', 'delivery_boy')
                ->get();
                
        if(env('DEMO_MODE') != 'On') {
            $order->viewed = 1;
            $order->save();
        }

        return view('backend.sales.show', compact('order', 'delivery_boys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->active()->get();

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $address = Address::where('id', $carts[0]['address_id'])->first();
        if($carts[0]['billing_address'] != null){
             $billing_address = Address::where('id', $carts[0]['billing_address'])->first();
        }else{
             $billing_address = Address::where('id', $request->billing_address_id)->first();
        }
       

        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = Auth::user()->name;
            $shippingAddress['email']       = Auth::user()->email;
            $shippingAddress['address']     = $address->address. (isset($address->area) ? ', ' . $address->area->name : '');
            $shippingAddress['country']     = $address->country->name;
            if(get_setting('has_state') == 1){
            $shippingAddress['state']       = $address->state->name;
            }
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $billingAddress=[];
        if ($billing_address != null) {
            $billingAddress['name']        = Auth::user()->name;
            $billingAddress['email']       = Auth::user()->email;
            $billingAddress['address']     = $billing_address->address. (isset($billing_address->area) ? ', ' . $billing_address->area->name : '');
            $billingAddress['country']     = $billing_address->country->name;
            if(get_setting('has_state') == 1){
            $billingAddress['state']       = $billing_address->state->name;
            }
            $billingAddress['city']        = $billing_address->city->name;
            $billingAddress['postal_code'] = $billing_address->postal_code;
            $billingAddress['phone']       = $billing_address->phone;
            if ($billing_address->latitude || $billing_address->longitude) {
                $billingAddress['lat_lang'] = $billing_address->latitude . ',' . $billing_address->longitude;
            }
        }else {
            $billingAddress = $shippingAddress;
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::user()->id;
        $combined_order->shipping_address = json_encode($shippingAddress);

        $combined_order->save();

        $seller_products = array();
        foreach ($carts as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = Auth::user()->id;
            $order->shipping_address = $combined_order->shipping_address;
            $order->billing_address = json_encode($billingAddress);
            $order->additional_info = $request->additional_info;
            $order->payment_type = $request->payment_option;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');

            do {
                $tracking = 'TRK-' . strtoupper(Str::random(6)) . substr(time(), -5);
            } while (Order::where('tracking_code', $tracking)->exists());

            $order->tracking_code = $tracking;

            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;
            $gst =0;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax +=  cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];

                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                    flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                    $order->delete();
                    return redirect()->route('cart')->send();
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $order_detail->tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                $order_detail->shipping_cost = $cartItem['shipping_cost'];
                $order_detail->coupon_discount = $cartItem['discount'];
                if (addon_is_activated('gst_system')) {
                $order_detail->gst_rate = $product->gst_rate;
                $order_detail->gst_amount = (($order_detail->shipping_cost + $order_detail->tax + $order_detail->price - $cartItem['discount'])*$product->gst_rate)/100;
                $gst+=$order_detail->gst_amount;
                }
                
                if (addon_is_activated('refund_request')) {

                    $refund_type = get_setting('refund_type');

                    if($refund_type == 'global_refund' && $product->refundable != 0){

                        $refund_days = get_setting('refund_request_time');
                        $order_detail->refund_days = (int) $refund_days;

                        if(get_setting('enable_dispute_refund') == 1){
                            $dispute_refund_days = get_setting('dispute_refund_request_time');
                            $order_detail->dispute_refund_days = (int) $dispute_refund_days;
                        }

                    }elseif($refund_type == 'category_based_refund' && $product->refundable != 0){

                        $refund_days = $product->main_category->refund_request_time;
                        $order_detail->refund_days = (int) $refund_days;

                        if(get_setting('enable_dispute_refund') == 1){
                            $dispute_refund_days = get_setting('dispute_refund_request_time');
                            $order_detail->dispute_refund_days = (int) $dispute_refund_days;
                        }
                        
                    }
                }
                
                $shipping += $order_detail->shipping_cost;
                //End of storing shipping cost
                $order_detail->quantity = $cartItem['quantity'];

                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }

                $order_detail->save();

                $product->num_of_sale += $cartItem['quantity'];
                $product->save();

                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem['shipping_type'];

                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                if ($cartItem['shipping_type'] == 'carrier') {
                    $order->carrier_id = $cartItem['carrier_id'];
                }

                if ($product->added_by == 'seller' && $product->user->seller != null) {
                    $seller = $product->user->seller;
                    $seller->num_of_sale += $cartItem['quantity'];
                    $seller->save();
                }

                if (addon_is_activated('affiliate_system')) {
                    if ($order_detail->product_referral_code) {
                        $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                    }
                }
            }

            $order->grand_total = $subtotal + $tax + $shipping + $gst;

            if ($seller_product[0]->coupon_code != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }

            $combined_order->grand_total += $order->grand_total;

            $invoice_config = json_decode(get_setting('invoice_config'), true);

            if (
                isset($invoice_config['generate_invoice_number']) &&
                $invoice_config['generate_invoice_number'] == 1
            ) {

                $prefix = 'INV-' . date('m') . date('y');

                $lastOrder = Order::where('invoice_number', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastOrder && $lastOrder->invoice_number) {

                    $lastSerial = (int) substr($lastOrder->invoice_number, -6);

                    $newSerial = $lastSerial + 1;

                } else {

                    $newSerial = 1;
                }

                $serial = str_pad($newSerial, 6, '0', STR_PAD_LEFT);

                $invoice_no = $prefix . $serial;

                $order->invoice_number = $invoice_no;
            }
            $order->save();
        }

        $combined_order->save();

        $request->session()->put('combined_order_id', $combined_order->id);
        if(get_setting('facebook_pixel_capi') == 1){
            $fb = new FacebookConversionService();
            $fb->sendPurchase($combined_order);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->single_order_delete($id);
        return $result;
    }

    public function single_order_delete($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            $order->commissionHistory()->delete();
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {
                    product_restock($orderDetail);
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->single_order_delete($order_id);
            }
        }

        return 1;
    } 

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;

        $datetimes = [];
        if (!empty($order->delivery_status_update_datetime)) {
            $datetimes = json_decode($order->delivery_status_update_datetime, true) ?? [];
        }
        $datetimes[$request->status] = now()->toDateTimeString();
        $order->delivery_status_update_datetime = json_encode($datetimes);
        
        $order->save();

        if($request->status == 'delivered'){
            $order->delivered_date = date("Y-m-d H:i:s");
            if($order->payment_type == 'cash_on_delivery'){
                $order->payment_status = 'paid';
            }
            $order->save();
        }

        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }
        
        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        // If the order is cancelled and the seller commission is calculated, deduct seller earning
        if($request->status == 'cancelled' && $order->user->user_type == 'seller' && $order->payment_status == 'paid' && $order->commission_calculated == 1){
            $sellerEarning = $order->commissionHistory->seller_earning;
            $shop = $order->shop;
            $shop->admin_to_pay -= $sellerEarning;
            $shop->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    product_restock($orderDetail);
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    product_restock($orderDetail);
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        // Delivery Status change email notification to Admin, seller, Customer
        EmailUtility::order_email($order, $request->status);  

        // Delivery Status change SMS notification
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {}
        }

        //Send web Notifications to user
        NotificationUtility::sendNotification($order, $request->status);

        //Sends Firebase Notifications to user
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function updateNote(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->order_note = $request->order_note;
        $order->save();  

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';

        $datetimes = [];
        if (!empty($order->delivery_status_update_datetime)) {
            $datetimes = json_decode($order->delivery_status_update_datetime, true) ?? [];
        }
        $datetimes['payment_time'] = now()->toDateTimeString();
        $order->delivery_status_update_datetime = json_encode($datetimes);

        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        // Payment Status change email notification to Admin, seller, Customer
        if($request->status == 'paid'){
            EmailUtility::order_email($order, $request->status);  
        }

        //Sends Web Notifications to Admin, seller, Customer
        NotificationUtility::sendNotification($order, $request->status);

        //Sends Firebase Notifications to Admin, seller, Customer
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {
                }
            }
        }

        return 1;
    }

    public function orderBulkExport(Request $request)
    {
        if($request->id){
          return Excel::download(new OrdersExport($request->id), 'orders.xlsx');
        }
        return back();
    }

    public function unpaid_order_payment_notification_send(Request $request){
        if($request->order_ids != null){
            $notificationType = get_notification_type('complete_unpaid_order_payment', 'type');
            foreach (explode(",",$request->order_ids) as $order_id) {
                $order = Order::where('id', $order_id)->first();
                $user = $order->user;
                if($notificationType->status == 1 && $order->payment_status == 'unpaid'){
                    $order_notification['order_id']     = $order->id;
                    $order_notification['order_code']   = $order->code;
                    $order_notification['user_id']      = $order->user_id;
                    $order_notification['seller_id']    = $order->seller_id;
                    $order_notification['status']       = $order->payment_status;
                    $order_notification['notification_type_id'] = $notificationType->id;
                    Notification::send($user, new OrderNotification($order_notification));
                }
            }
            flash(translate('Notification Sent Successfully.'))->success();
        }
        else{
            flash(translate('Something went wrong!.'))->warning();
        }
        return back();
    }

    public function get_filter_orders(Request $request)
    {
        $date= $request->date;
        $col_name = null;
        $query = null;
        $sort_search = null;
        $orders = Order::orderBy('id', 'desc');
        $admin_id = get_admin()->id;
        if ($request->seller_type == 'inhouse') {
            $orders = $orders->where('seller_id', $admin_id);
        } elseif ($request->seller_type == 'seller') {
             $orders = $orders->where('seller_id', '!=', $admin_id);
        }
        if ($request->order_from) {
            $orders= $orders->where('order_from','pos');
        }

        if($request->col && $request->status){
            $orders= $orders->where($request->col,$request->status);
            if($request->status == 'pickup_point'){
                if (Auth::user()->user_type == 'staff' && Auth::user()->staff->pick_up_point != null ) {
                    $orders->where('shipping_type', 'pickup_point')->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
                }
            }
        }
        
        if ($request->search != null) {
            $sort_search = $request->search;

            $orders = $orders->where(function ($query) use ($sort_search) {
                $query->where('code', 'like', '%' . $sort_search . '%')

                    ->orWhereHas('shop', function ($q) use ($sort_search) {
                        $q->where('name', 'like', '%' . $sort_search . '%');
                    })

                    ->orWhereHas('user', function ($q) use ($sort_search) {
                        $q->where('name', 'like', '%' . $sort_search . '%');
                    });
            });
        }

        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }

        if ($request->payment_status != null) {
            $var = explode(",", $request->payment_status);
            $col_name = $var[0];
            $query = $var[1];
            $orders = $orders->where($col_name, $query);
        }

        $filters = $request->selected_filter ?? [];
        if (!empty($filters)) {
            if (!empty($filters)) {
                $orders->whereIn('delivery_status', $filters);
            }
        }

        $orders = $orders->paginate(15);
        $type = $request->seller_type;
        $unpaid_order_payment_notification = get_notification_type('complete_unpaid_order_payment', 'type');

        $view = view('backend.sales.orders_table',
            compact('orders', 'type', 'col_name', 'query', 'sort_search','unpaid_order_payment_notification')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function manage_order_modal(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        // if(env('DEMO_MODE') != 'On') {
        //     $order->viewed = 1;
        //     $order->save();
        // }
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
                ->where('user_type', 'delivery_boy')
                ->get();
        return view('backend.sales.manage_order_modal', [
            'order_id' => $request->order_id,
            'order' => $order,
            'delivery_boys' => $delivery_boys
        ]);
    }

}
