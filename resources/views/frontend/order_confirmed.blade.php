@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32.001" viewBox="0 0 32 32.001" class="cart-rotate mb-3 mt-1">
                                    <g id="Group_23976" data-name="Group 23976" transform="translate(-282 -404.889)">
                                      <path class="cart-ok has-transition" id="Path_28723" data-name="Path 28723" d="M313.283,409.469a1,1,0,0,0-1.414,0l-14.85,14.85-5.657-5.657a1,1,0,1,0-1.414,1.414l6.364,6.364a1,1,0,0,0,1.414,0l.707-.707,14.85-14.849A1,1,0,0,0,313.283,409.469Z" fill="#ffffff"/>
                                      <g id="LWPOLYLINE">
                                        <path id="Path_28724" data-name="Path 28724" d="M313.372,416.451,311.72,418.1a14,14,0,1,1-5.556-8.586l1.431-1.431a16,16,0,1,0,5.777,8.365Z" fill="#d43533"/>
                                      </g>
                                    </g>
                                </svg>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first()
                    @endphp
                    <!-- Order Confirmation Text-->
                    <div class="text-center py-4 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" class=" mb-3">
                            <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                              <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18" transform="translate(978 481)" fill="#85b567"/>
                              <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3" rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18" rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                              </g>
                            </g>
                        </svg>
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 text-soft-dark">{{  translate('A copy or your order summary has been sent to') }} <strong>{{ json_decode($first_order->shipping_address)->email }}</strong></p>
                    </div>
                    <!-- Order Summary -->
                    <div class="mb-4 bg-white p-4 border">
                        <h5 class="fw-600 mb-3 fs-16 text-soft-dark pb-2 border-bottom">{{ translate('Order Summary')}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table fs-14 text-soft-dark">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Order date')}}:</td>
                                        <td class="border-top-0 py-2">{{ date('d-m-Y H:i A', $first_order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Name')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Email')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->email }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $shipping = json_decode($first_order->shipping_address);
                                            $billing = json_decode($first_order->billing_address);
                                        @endphp
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Shipping address')}}:</td>
                                        <td class="border-top-0 py-2">
                                            {{ $shipping->address }}, 
                                            {{ $shipping?->city ? $shipping->city . ', ' : '' }}
                                            {{ isset($shipping->state) ? $shipping->state . ', ' : '' }}
                                            {{ $shipping->country }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Billing address')}}:</td>
                                        <td class="border-top-0 py-2">
                                            {{ $billing->address }}, 
                                            {{ $billing?->city ? $billing->city . ', ' : '' }}
                                            {{ isset($billing->state) ? $billing->state . ', ' : '' }}
                                            {{ $billing->country }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Order status')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Total order amount')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ single_price($combined_order->grand_total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Shipping')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Payment method')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @foreach ($combined_order->orders as $order)
                        <div class="card shadow-none border rounded-0">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="h5 fs-20">{{ translate('Order Code:')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                    <h2 class="h6 fs-16">{{ translate('Tracking Code:')}} <span class="fw-700 text-primary">{{ $order->tracking_code }}</span></h2>
                                    <h5 class="h5 fs-14">{{ translate('Delivery Type:')}} 
                                        <span class="fw-700">
                                            @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                {{  translate('Home Delivery') }}
                                            @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                {{  translate('Carrier') }}
                                            @elseif ($order->shipping_type == 'pickup_point')
                                                @if ($order->pickup_point != null)
                                                    {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
                                                @endif
                                            @endif
                                        </span>
                                    </h5>
                                   {{-- Sold BY --}}

                                    <h5 class="h5 fs-14">{{ translate('Sold By')}}:
                                        <span class="fw-700">
                                            @if($order->seller_id != null)
                                                {{ $order->shop->name ?? get_setting('site_name') }}
                                            @else
                                                {{ get_setting('site_name') }}
                                            @endif
                                        </span>
                                    </h5>


                                    @if(get_seller_gstin($order) != null)
                                    <h5 class="h5 fs-14">{{ translate('GSTIN')}}: 
                                        <span class="fw-700">
                                            {{get_seller_gstin($order)}}
                                        </span>
                                    </h5>
                                    @endif

                                    <h5 class="h5 fs-14"> <span class="fw-700">{{ get_seller_address($order) }}</span>
                                    </h5>
                                </div>
                                <!-- Order Details -->
                                <div>
                                    <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">{{ translate('Order Details')}}</h5>
                                    <!-- Product Details -->
                                    <div>
                                        <table class="table table-responsive-md text-soft-dark fs-14">
                                            <thead>
                                                <tr>
                                                    <th class="opacity-60 border-top-0 pl-0">#</th>
                                                    <th class="opacity-60 border-top-0" width="30%">{{ translate('Product')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Qty')}}</th>
                                                    @if(addon_is_activated('gst_system'))
                                                    <th class="opacity-60 border-top-0">{{ translate('Gross Amount')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Discount/ Coupon')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Taxable Value')}}</th>
                                                    @if(same_state_shipping($order))
                                                    <th class="opacity-60 border-top-0">{{ translate('CGST')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('SGST')}}</th>
                                                    @else
                                                    <th class="opacity-60 border-top-0">{{ translate('IGST')}}</th>
                                                    @endif
                                                    @endif

                                                    <th class="text-right opacity-60 border-top-0 pr-0">{{ translate('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                    @php
                                                                        if($orderDetail->combo_id != null) {
                                                                            $combo = \App\ComboProduct::findOrFail($orderDetail->combo_id);

                                                                            echo '('.$combo->combo_title.')';
                                                                        }
                                                                    @endphp
                                                                </a>
                                                                <p class="fs-12">{{ $orderDetail->variation }}</p>
                                                            @else
                                                                <strong>{{  translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        @if(addon_is_activated('gst_system'))
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->price) }}
                                                        </td>

                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->coupon_discount) }}
                                                        </td>

                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->price - $orderDetail->coupon_discount) }}
                                                        </td>

                                                        @php 
                                                            $gst_amount = get_gst_by_price_and_rate($orderDetail->price - $orderDetail->coupon_discount , $orderDetail->gst_rate);
                                                            $shipping_gst = get_gst_by_price_and_rate($orderDetail->shipping_cost, $orderDetail->gst_rate);
                                                            @endphp
                                                        @if(same_state_shipping($order))
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($gst_amount/2) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($gst_amount/2) }}
                                                        </td>
                                                        @else
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($gst_amount) }}
                                                        </td>
                                                        @endif
                                                        @endif
                                                        @if(addon_is_activated('gst_system'))
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price - $orderDetail->coupon_discount + $gst_amount) }}</td>
                                                        @else
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                        @endif
                                                    </tr>
                                                    @if(addon_is_activated('gst_system'))
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0"></td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{translate('Shipping')}}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            1
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->shipping_cost) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price(0) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->shipping_cost) }}
                                                        </td>
                                                        @if(same_state_shipping($order))
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($shipping_gst/2) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($shipping_gst/2) }}
                                                        </td>
                                                        @else
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($shipping_gst) }}
                                                        </td>
                                                        @endif
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->shipping_cost + (($orderDetail->shipping_cost* $orderDetail->gst_rate)/100)) }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Order Amounts -->
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                     @if(addon_is_activated('gst_system'))
                                                     <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Subtotal')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span class="fw-600">{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->orderDetails->sum('coupon_discount')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('GST Amount')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('gst_amount')) }}</span>
                                                        </td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Subtotal')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Shipping -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Shipping')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Tax -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Tax')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Coupon Discount -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Coupon Discount')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->coupon_discount) }}</span>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <!-- Total -->
                                                    <tr>
                                                        <th class="py-2"><span class="fw-600">{{ translate('Total')}}</span></th>
                                                        <td class="text-right pr-0">
                                                            <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    @if (get_setting('facebook_pixel') == 1)
    <!-- Facebook Pixel purchase Event -->
    <script>
        $(document).ready(function(){
            var currend_code = '{{ get_system_currency()->code }}';
            var amount = 'single_price($combined_order->grand_total) }}';
            fbq('track', 'Purchase',
                {
                    value: amount,
                    currency: currend_code,
                    content_type: 'product'
                }
            );
        });
    </script>
    <!-- Facebook Pixel purchase Event -->
    @endif

    @if (get_setting('google_analytics') == 1)
    <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "purchase",
            ecommerce: {
                transaction_id: "{{ $first_order->code }}",
                value: {{ (float) $combined_order->grand_total }},
                tax: {{ (float) $combined_order->grand_total - $combined_order->orders->sum(function($order) { 
                    return $order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->coupon_discount; 
                }) }},
                shipping: {{ (float) $combined_order->orders->sum(function($order) {
                    return $order->orderDetails->sum('shipping_cost');
                }) }},
                currency: "{{ get_system_currency()->code }}",
                items: [
                    @foreach($combined_order->orders as $order)
                        @foreach($order->orderDetails as $item)
                        {
                            item_id: "{{ $item->product_id }}",
                            item_name: "{{ addslashes($item->product->name ?? 'Product') }}",
                            @if($item->product && $item->product->category)
                            item_category: "{{ addslashes($item->product->category->name ?? '') }}",
                            @endif
                            price: {{ (float) $item->price }},
                            quantity: {{ (int) $item->quantity }}
                        }@if(!$loop->last),@endif
                        @endforeach
                    @endforeach
                ]
            }
        });
    </script>
    @endif
@endsection
        
