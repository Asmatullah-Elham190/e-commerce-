@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="col-12 col-xl-10 ml-auto px-0">
                <div class="row gutters-5 justify-content-end">
                    @php
                        $delivery_status = $order->delivery_status;
                        $payment_status = $order->payment_status;
                        $admin_user_id = get_admin()->id;
                        $first_order = $order->orderDetails->first();
                        $shipping_method = $order->shipping_method ?? null;
                    @endphp
                    @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)

                        <!--Assign Delivery Boy-->
                        @if (addon_is_activated('delivery_boy'))
                            @if ($shipping_method != 'shiprocket' && $shipping_method != 'steadfast' && $shipping_method != 'pathao' && $shipping_method != 'redx')
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                                    @if (($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders'))
                                        <select class="form-control aiz-selectpicker" data-live-search="true"
                                            data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                            <option value="">{{ translate('Select Delivery Boy') }}</option>
                                            @foreach ($delivery_boys as $delivery_boy)
                                                <option value="{{ $delivery_boy->id }}"
                                                    @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                                    {{ $delivery_boy->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                            disabled>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                            <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                            @if (auth()->user()->can('update_order_payment_status') && $payment_status == 'unpaid' && $order->payment_type != 'cash_on_delivery')
                                {{-- <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status"> --}}
                                <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status" onchange="confirm_payment_status()">
                                    <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                        {{ translate('Unpaid') }}
                                    </option>
                                    <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                        {{ translate('Paid') }}
                                    </option>
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ ucfirst($payment_status) }}" disabled>
                            @endif
                        </div>
                        <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                            <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                            @if ($order->shipping_method == 'shiprocket' || $order->shipping_method == 'steadfast' || $order->shipping_method == 'pathao' || $order->shipping_method == 'redx')
                                <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}" disabled>
                            @elseif (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                                <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                    id="update_delivery_status">
                                    <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                        {{ translate('Pending') }}
                                    </option>
                                    <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                        {{ translate('Confirmed') }}
                                    </option>
                                    <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                        {{ translate('Picked Up') }}
                                    </option>
                                    <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                        {{ translate('On The Way') }}
                                    </option>
                                    <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                        {{ translate('Delivered') }}
                                    </option>
                                    <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                        {{ translate('Cancel') }}
                                    </option>
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                            @endif
                        </div>
                        @if (addon_is_activated('shiprocket') || addon_is_activated('steadfast') || addon_is_activated('pathao') || addon_is_activated('redx'))
                            @php
                                $addons = [];
                                $availableAddons = ['shiprocket', 'steadfast', 'pathao', 'redx'];
                                foreach ($availableAddons as $addon) {
                                    if (addon_is_activated($addon)) {
                                        $addons[] = $addon;
                                    }
                                }
                                $shipping_systems = App\Models\ShippingSystem::where('active', 1)
                                    ->whereIn('name', $addons)
                                    ->get();
                            @endphp
                            <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                <label for="select_shipping_info">{{ translate('Shipping System') }}</label>
                                @if ($order->delivery_status == 'pending' || $order->delivery_status == 'confirmed')
                                    @if ($shipping_method)
                                        <input type="text" class="form-control" value="{{ ucfirst(translate($shipping_method)) }}" disabled>
                                    @else
                                        <select class="form-control aiz-selectpicker" id="select_shipping_info" name="shipping_system">
                                            <option value="">
                                                {{ translate('Select Shipping System') }}
                                            </option>
                                            @foreach ($shipping_systems as $shipping_system)  
                                            <option value="{{$shipping_system->name}}">
                                                {{ ucfirst($shipping_system->name) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    @endif
                                @else
                                    <input type="text" class="form-control" value="{{ ucfirst(translate($shipping_method)) }}" disabled>    
                                @endif
                            </div>
                            @if ($order->shipping_method == 'shiprocket')
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Shiprocket Order Status') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->shiprocket_order_status)) }}" disabled>
                                </div>
                                @if ($order->shiprocket_awb != null)
                                    <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                        <label for="">{{ translate('Shiprocket Delivery Status') }}</label>
                                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->shiprocket_status)) }}" disabled>
                                    </div>
                                @endif
                            @elseif ($order->shipping_method == 'steadfast')
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Steadfast Status') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->steadfast_status)) }}" disabled>
                                </div>
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Steadfast Consignment Id') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->steadfast_consignment_id)) }}" disabled>
                                </div>
                            @elseif ($order->shipping_method == 'pathao')
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Pathao Status') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->pathao_status)) }}" disabled>
                                </div>
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Pathao Consignment Id') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->pathao_consignment_id)) }}" disabled>
                                </div>
                            @elseif ($order->shipping_method == 'redx')
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Redx Status') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->redx_status)) }}" disabled>
                                </div>
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label for="">{{ translate('Redx Tracking Id') }}</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->redx_tracking_id)) }}" disabled>
                                </div>     
                            @endif
                            @if ($order->shipping_method == 'shiprocket' && $order->shiprocket_shipment_id)
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label>{{ translate('Courier') }}</label>

                                    @if($order->shiprocket_courier_id)
                                        <input type="text" class="form-control" value="{{ $order->shiprocket_courier_name }}" disabled>
                                    @else
                                        <select
                                            class="form-control aiz-selectpicker"
                                            id="shiprocket_courier"
                                            data-live-search="true">
                                            <option value="">{{ translate('Loading...') }}</option>
                                        </select>
                                    @endif
                                </div>
                            @endif

                            @if($order->shiprocket_awb && !$order->pickup_scheduled_at)
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label class="d-none d-md-block mb-2"></label>
                                    <button
                                        class="btn btn-warning form-control d-block"
                                        id="request-pickup-btn">
                                        {{ translate('Request Pickup') }}
                                    </button>
                                </div>
                            @endif

                        @endif
                        @if ($shipping_method === 'shiprocket')
                            @if($order->pickup_scheduled_at)
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label>{{ translate('Pickup Scheduled') }}</label>
                                    <input type="text" class="form-control"
                                        value="{{ $order->pickup_scheduled_at }}"
                                        disabled>
                                </div>
                            @endif
                            <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                <label >
                                    {{ translate('AWB Code') }}
                                </label>
                                <input type="text" class="form-control"
                                    value="{{ $order->shiprocket_awb }}" disabled>
                            </div>
                        @elseif($shipping_method === 'steadfast')
                            <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                <label>
                                    {{ translate('Steadfast Tracking Code') }}
                                </label>
                                <input type="text" class="form-control"
                                    value="{{ $order->steadfast_tracking_code }}" disabled>
                            </div>
                        @elseif($shipping_method === 'pathao')
                            <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                <label>
                                    {{ translate('Pathao Delivery Fee') }}
                                </label>
                                <input type="text" class="form-control"
                                    value="{{ $order->pathao_delivery_fee }} TK" disabled>
                            </div>
                        @elseif($shipping_method === 'redx')
                            <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                <label>
                                    {{ translate('Redx Delivery Charge') }}
                                </label>
                                <input type="text" class="form-control"
                                    value="{{ $order->redx_charge }} TK" disabled>
                            </div>        
                        @endif    
                        @if($order->shipping_method === 'shiprocket' && $order->shiprocket_awb) 
                            <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                <label>
                                    {{ translate('Download Label') }}
                                </label>
                                    <a href="{{ route('shiprocket.download.label', $order->id) }}"
                                class="btn btn-sm btn-install w-auto h-auto d-block " title="Download Label">
                                <i class="las la-2x la-download"></i>
                                </a>
                            </div>
                            @if($delivery_status != 'cancelled')
                                <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                                    <label>
                                        {{ translate('Download Manifest') }}
                                    </label>
                                    <a href="{{ route('shiprocket.download.manifest', $order->id) }}"
                                    class="btn btn-sm btn-install w-auto h-auto d-block" title="Download Manifest">
                                        <i class="las la-2x la-download"></i>
                                    </a>
                                </div>
                            @endif
                        @endif
                        <div class="col-12 col-md-4 col-xl-4 col-xxl-2 mb-2">
                            <label for="update_tracking_code">
                                {{ translate('Tracking Code') }}
                            </label>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control"
                                    id="update_tracking_code"
                                    value="{{ $order->tracking_code }}"
                                    readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary"
                                        type="button"
                                        id="copy_tracking_code">
                                        <i class="las la-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            <div class="mb-3 mt-3">
                @php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                                {{ $order->user->name }}
                            </strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                            <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100">
                        </a>
                        <br>
                    @endif

                     <!-- Sold BY -->
                    <strong class="text-main">{{ translate('Sold By') }}: </strong>
                    <br>
                    {{ $order->shop->name ?? get_setting('site_name') }}
                    
                    @php 
                        $gstin = get_seller_gstin($order);
                    @endphp

                    @if($gstin && is_numeric($first_order->gst_amount))
                        <br>
                        <strong class="text-main">{{ translate('GSTIN') }}:</strong> {{ $gstin }}
                    @endif

                    <br>
                    <strong class="text-main"> {{ get_seller_address($order) }} </strong>

                    
                </div>
                <div class="col-md-4">
                    <table class="ml-auto">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span class="badge badge-inline badge-success">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @else
                                        <span class="badge badge-inline badge-info">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>

                                @if(is_numeric($first_order->gst_amount))
                                <th data-breakpoints="lg">{{ translate('Gross Amount')}}</th>
                                <th data-breakpoints="lg">{{ translate('Discount/ Coupon')}}</th>
                                <th data-breakpoints="lg">{{ translate('Taxable Value')}}</th>

                                @if(same_state_shipping($order))
                                <th data-breakpoints="lg">{{ translate('CGST') }}</th>
                                <th data-breakpoints="lg">{{ translate('SGST') }}</th>
                                @else
                                <th data-breakpoints="lg">{{ translate('IGST') }}</th>
                                @endif

                                @else
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                @endif
                                
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong>
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </a>
                                            </strong>
                                            <small>
                                                {{ $orderDetail->variation }}
                                            </small>
                                            <br>
                                            <small>
                                                @php
                                                    $product_stock = $orderDetail->product->stocks->where('variant', $orderDetail->variation)->first();
                                                @endphp
                                                {{translate('SKU')}}: {{ $product_stock['sku'] ?? '' }}
                                            </small>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong>
                                                <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </a>
                                            </strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $orderDetail->quantity }}
                                    </td>
                                    @if(is_numeric($first_order->gst_amount))
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price) }}
                                    </td>

                                    <td class="text-center">
                                        {{ single_price($orderDetail->coupon_discount) }}
                                    </td>

                                    <td class="text-center">
                                        {{ single_price($orderDetail->price - $orderDetail->coupon_discount) }}
                                    </td>
                                    
                                    @php 
                                        $gst_amount = get_gst_by_price_and_rate($orderDetail->price - $orderDetail->coupon_discount , $orderDetail->gst_rate);
                                        $shipping_gst = get_gst_by_price_and_rate($orderDetail->shipping_cost, $orderDetail->gst_rate);
                                    @endphp

                                    @if(same_state_shipping($order))
                                    <td class="text-center">
                                        {{ single_price($gst_amount/2) }}
                                    </td>
                                    <td class="text-center">
                                        {{ single_price($gst_amount/2) }}
                                    </td>
                                    @else
                                    <td class="text-center">
                                        {{ single_price($gst_amount) }}
                                    </td>	
                                    @endif

                                    @else
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    @endif

                                    @if(is_numeric($first_order->gst_amount))
                                    <td class="text-center">{{ single_price($orderDetail->price - $orderDetail->coupon_discount + $gst_amount) }}</td>
                                    @else
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price) }}
                                    </td>
                                    @endif
                                </tr>

                                @if(is_numeric($first_order->gst_amount))
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                        {{translate('Shipping')}}
                                    </td>
                                    <td></td>
                                    <td class="text-center">
                                        1
                                    </td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->shipping_cost) }}
                                    </td>
                                    <td class="text-center">
                                        {{ single_price(0) }}
                                    </td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->shipping_cost) }}
                                    </td>
                                    @if(same_state_shipping($order))
                                    <td class="text-center">
                                        {{ single_price($shipping_gst/2) }}
                                    </td>
                                    <td class="text-center">
                                        {{ single_price($shipping_gst/2) }}
                                    </td>
                                    @else
                                    <td class="text-center">
                                        {{ single_price($shipping_gst) }}
                                    </td>
                                    @endif
                                    <td class="text-center">{{ single_price($orderDetail->shipping_cost + (($orderDetail->shipping_cost* $orderDetail->gst_rate)/100)) }}
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>

                        @if(is_numeric($first_order->gst_amount))

                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->orderDetails->sum('coupon_discount')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Total GST') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('gst_amount')) }}
                            </td>
                        </tr>
                        
                        @else
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                            class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('modal')

    <!-- confirm payment Status Modal -->
    <div id="confirm-payment-status" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700">{{translate('Are you sure you want to change the payment status?')}}</p>
                    <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" onclick="update_payment_status()" class="btn btn-success rounded-2 mt-2 fs-13 fw-700 w-150px">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="shipping-info" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Shipping Info')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="shipping_info">
                    <div class="modal-body" id="shipping_info">
                        <div id="address-list"></div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal" id="close-button">{{translate('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-styled btn-base-1" id="confirm-address" data-dismiss="modal">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-awb-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content p-3">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                        <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                        </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700">{{ translate('Would you like to assign a courier and generate the AWB code for this order?') }}</p>
                    <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" id="confirm-awb-btn" class="btn btn-success rounded-2 mt-2 fs-13 fw-700 w-150px">{{ translate('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-pickup-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content p-3">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>

                    <p class="mt-3 mb-3 fs-16 fw-700">
                        {{ translate('Would you like to request a pickup for this order? The courier will be notified upon confirmation.') }}
                    </p>

                    <button type="button"
                            class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px"
                            data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>

                    <button type="button"
                            id="confirm-pickup-btn"
                            class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-150px">
                        {{ translate('Confirm Pickup') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="steadfastConfirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content p-3">
                <div class="modal-body text-center">
                    <!-- Icon (warning style like your other modals) -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>

                    <!-- Text -->
                    <p class="mt-3 mb-3 fs-16 fw-700">
                        {{ translate('Would you like to create this order in Steadfast Courier?') }}
                    </p>

                    <!-- Buttons -->
                    <button type="button"
                            class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px"
                            id="steadfastCancelBtn"
                            data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>

                    <button type="button"
                            class="btn btn-success rounded-2 mt-2 fs-13 fw-700 w-150px"
                            id="steadfastConfirmBtn">
                        {{ translate('Confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="pathao-info" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6">{{translate('Select Store Name')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="pathao_info">
                    <div class="modal-body" id="pathao_info">
                        <div id="store-list"></div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal" id="close-button">{{translate('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-styled btn-base-1" id="confirm-store" data-dismiss="modal">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas -->
    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        <!-- content will here -->
    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>

@endsection


@section('script')
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
                location.reload();
            });
        });

        // Payment Status Update
        function confirm_payment_status(value){
            $('#confirm-payment-status').modal('show');
        }

        function update_payment_status(){
            $('#confirm-payment-status').modal('hide');
            var order_id = {{ $order->id }};
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: 'paid'
            }, function(data) {
                $('#update_payment_status').prop('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                location.reload();
            });
        }

        $(document).on('click', '#copy_tracking_code', function () {

            let trackingCode = $('#update_tracking_code').val();

            navigator.clipboard.writeText(trackingCode);

            AIZ.plugins.notify('success', 'Tracking code copied');
        });

    </script>

    @if (addon_is_activated('shiprocket'))
    <script type="text/javascript">

        function loadShippingInfoForShiprocket(sellerId) {
            $('#address-list').html('<p class="text-muted">{{ translate("Loading...") }}</p>');

            $.when(
                $.ajax({
                    url: "{{ route('pickup.addresses.list') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': AIZ.data.csrf },
                    data: { user_id: sellerId, shipping_system: 'shiprocket' }
                }),
                $.ajax({
                    url: "{{ route('box.sizes.list') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': AIZ.data.csrf },
                    data: { user_id: sellerId, shipping_system: 'shiprocket' }
                })
            ).done(function(pickupResponse, boxResponse) {
                let html = '';

                const pickupAddresses = pickupResponse[0] || [];
                html += `<label class="fw-700 d-block mb-2">{{ translate('Address Nickname') }}</label>`;
                if (pickupAddresses.length > 0) {
                    pickupAddresses.forEach(addr => {
                        html += `
                            <div class="border p-3 mb-3 rounded">
                                <div class="form-check">
                                    <input class="magic-radio" type="radio" name="pickup_address_id" value="${addr.id}" id="addr_${addr.id}">
                                    <label class="form-check-label" for="addr_${addr.id}">
                                        ${addr.address_nickname || '{{ translate('No location') }}'}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html += `<p class="text-muted mb-4">{{ translate('No pickup address found.') }}</p>`;
                }

                const boxSizes = boxResponse[0] || [];
                html += `<label class="fw-700 d-block mb-2">{{ translate('Box Size (Length × Breadth × Height)') }}</label>`;
                if (boxSizes.length > 0) {
                    boxSizes.forEach(box => {
                        const dims = `${box.length} × ${box.breadth} × ${box.height} cm`;
                        html += `
                            <div class="border p-3 mb-3 rounded">
                                <div class="form-check">
                                    <input class="magic-radio" type="radio" name="box_size_id" value="${box.id}" id="box_${box.id}">
                                    <label class="form-check-label" for="box_${box.id}">
                                        ${dims}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html += `<p class="text-muted">{{ translate('No box sizes found.') }}</p>`;
                }

                $('#address-list').html(html);
            }).fail(function() {
                $('#address-list').html('<p class="text-danger">{{ translate("Failed to load shipping information.") }}</p>');
            });
        }

        $(document).ready(function () {

            const $select = $('#select_shipping_info');

            if ($select.length) {
                $select.on('change', function () {
                    if ($(this).val() === 'shiprocket') {
                        loadShippingInfoForShiprocket({{ Auth::id() }});
                        $('#shipping-info').modal('show');
                    }
                });
            }

            $('#confirm-address').on('click', function () {
                const pickupId = $('input[name="pickup_address_id"]:checked').val();
                const boxId = $('input[name="box_size_id"]:checked').val();

                if (!pickupId || !boxId) {
                    AIZ.plugins.notify('warning', '{{ translate("Please select pickup address and box size.") }}');
                    return;
                }

                $.post("{{ route('orders.confirm_shiprocket_info') }}", {
                    _token: AIZ.data.csrf,
                    order_id: {{ $order->id }},
                    pickup_address_id: pickupId,
                    shipping_box_size_id: boxId
                }, function (response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                        location.reload();
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                });
            });
        });

        $(document).ready(function () {
        
            const orderId = {{ $order->id }};
            const shipmentId = "{{ $order->shiprocket_shipment_id }}";
            const courierAssigned = {{ $order->shiprocket_courier_id ? 'true' : 'false' }};
        
            if (!shipmentId || courierAssigned) return;
        
            $.post("{{ route('shiprocket.couriers') }}", {
                _token: AIZ.data.csrf,
                order_id: orderId
            }).done(function (res) {
        
                if (!res.success) {
                    AIZ.plugins.notify('danger', res.message);
                    return;
                }
        
                let html = '<option value="">{{ translate("Select Courier") }}</option>';
        
                res.couriers.forEach(c => {
                    html += `<option value="${c.id}">${c.name}</option>`;
                });
        
                $('#shiprocket_courier')
                    .html(html)
                    .selectpicker('refresh');
            });
        
            $('#shiprocket_courier').on('change', function () {
                if ($(this).val()) {
                    $('#confirm-awb-modal').modal('show');
                }
            });
        
        $('#confirm-awb-btn').on('click', function () {
        
            const selectedCourierId = $('#shiprocket_courier').val();
        
            if (!selectedCourierId) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a courier first.") }}');
                return;
            }
        
            $('#confirm-awb-modal').modal('hide');
        
            $.post("{{ route('shiprocket.assign.awb') }}", {
                _token: AIZ.data.csrf,
                order_id: orderId,
                courier_id: selectedCourierId
            }).done(function (res) {
                if (res.success) {
                    AIZ.plugins.notify('success', res.message);
                    location.reload();
                } else {
                    AIZ.plugins.notify('danger', res.message);
                }
            });
        });
        
        
        $('#confirm-awb-modal').on('hidden.bs.modal', function () {
            $('#shiprocket_courier').selectpicker('val', '');
        });
        
        });


        $(document).ready(function () {

            let pickupOrderId = {{ $order->id }};

            $('#request-pickup-btn').on('click', function () {
                $('#confirm-pickup-modal').modal('show');
            });

            $('#confirm-pickup-btn').on('click', function () {

                $('#confirm-pickup-modal').modal('hide');

                $.post("{{ route('shiprocket.request.pickup') }}", {
                    _token: AIZ.data.csrf,
                    order_id: pickupOrderId
                }).done(function (res) {

                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        location.reload();
                    } else {
                        AIZ.plugins.notify('danger', res.message);
                    }
                });
            });

        });
    </script>
    @endif

    @if (addon_is_activated('steadfast'))
        <script>
            let previousShippingSystem = null;
            let selectedOrderId = {{ $order->id }};

            $('#select_shipping_info').on('focus', function () {
                previousShippingSystem = $(this).val();
            });

            $('#select_shipping_info').on('change', function () {
                let shippingSystem = $(this).val();

                if (shippingSystem === 'steadfast') {
                    $('#steadfastConfirmModal').modal('show');
                }
            });

            // Cancel Button
            $('#steadfastCancelBtn').on('click', function () {
                $('#steadfastConfirmModal').modal('hide');
                $('#select_shipping_info')
                    .val(previousShippingSystem)
                    .selectpicker('refresh');
            });

            // Confirm Button
            $('#steadfastConfirmBtn').on('click', function () {

                $('#steadfastConfirmBtn').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('steadfast.create.order') }}",
                    method: "POST",
                    data: {
                        _token: AIZ.data.csrf,
                        order_id: selectedOrderId
                    },
                    success: function (res) {
                        if (res.success) {
                            AIZ.plugins.notify('success', res.message);
                            location.reload();
                        } else {
                            AIZ.plugins.notify('danger', res.message);
                        }
                    },
                    error: function () {
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    },
                    complete: function () {
                        $('#steadfastConfirmBtn')
                            .prop('disabled', false)
                            .text('Confirm');
                        $('#steadfastConfirmModal').modal('hide');
                    }
                });

            });
        </script>
    @endif

    @if (addon_is_activated('pathao'))
        <script type="text/javascript">

            function loadStoreInfoForPathao() {
                $('#store-list').html('<p class="text-muted">{{ translate("Loading...") }}</p>');

                $.ajax({
                    url: "{{ route('pathao.all.store') }}",
                    type: 'GET',
                    headers: { 'X-CSRF-TOKEN': AIZ.data.csrf },
                    success: function (res) {

                        if (!res.success) {
                            $('#store-list').html('<p class="text-danger">'+res.message+'</p>');
                            return;
                        }

                        let html = '';
                        const stores = res.stores || [];

                        html += `<label class="fw-700 d-block mb-2">{{ translate('Store Name') }}</label>`;

                        if (stores.length > 0) {
                            stores.forEach(store => {
                                html += `
                                    <div class="border p-3 mb-2 rounded">
                                        <div class="form-check">
                                            <input class="magic-radio" type="radio"
                                                name="store_id"
                                                value="${store.store_id}"
                                                id="store_${store.store_id}">
                                            <label class="form-check-label" for="store_${store.store_id}">
                                                ${store.store_name}
                                            </label>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html += `<p class="text-muted">{{ translate('No store found.') }}</p>`;
                        }

                        $('#store-list').html(html);
                    },
                    error: function () {
                        $('#store-list').html('<p class="text-danger">API Error</p>');
                    }
                });
            }

            $(document).ready(function () {

                $('#select_shipping_info').on('change', function () {

                    if ($(this).val() === 'pathao') {
                        loadStoreInfoForPathao();
                        $('#pathao-info').modal('show');
                    }
                });

                $('#confirm-store').on('click', function () {

                    let storeId = $('input[name="store_id"]:checked').val();

                    if (!storeId) {
                        AIZ.plugins.notify('warning', '{{ translate("Please select store") }}');
                        return;
                    }

                    $.post("{{ route('pathao.create.order') }}", {
                        _token: AIZ.data.csrf,
                        order_id: {{ $order->id }},
                        store_id: storeId
                    }, function (res) {

                        if (res.success) {
                            AIZ.plugins.notify('success', res.message);
                            location.reload();
                        } else {
                            AIZ.plugins.notify('danger', res.message);
                        }
                    });
                });

            });

        </script>
    @endif

    @if (addon_is_activated('redx'))
        <script>
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');

            $(document).on('change', '#select_shipping_info', function () {
                if ($(this).val() === 'redx') {
                    openRightcanvas();
                }
            });

            function openRightcanvas() {
                rightOffcanvas.classList.add('active');
                overlay.classList.add('active');
                document.body.classList.add('body-no-scroll');
                rightOffcanvas.innerHTML= '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';

                $.ajax({
                    type: "GET",
                    url: "{{ route('redx.all_pickup_store.delivery_area') }}",
                    success: function (html) {
                        rightOffcanvas.innerHTML = html;
                        AIZ.plugins.bootstrapSelect('refresh');
                    },
                    error: function () {
                        rightOffcanvas.innerHTML =
                            '<p class="text-danger p-3">{{ translate("Failed to load RedX data") }}</p>';
                    }
                });
            }

            function closeRightcanvas() {
                rightOffcanvas.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('body-no-scroll');
            }

            function closeOffcanvas() {
                closeRightcanvas();
            }

            if (overlay) {
                overlay.addEventListener('click', closeRightcanvas);
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeRightcanvas();
                }
            });

            $(document).on('click', '#confirm-redx', function () {
                const btn = $(this);
                const pickupStoreId = $('select[name="pickup_store_id"]').val();
                const areaId = $('select[name="delivery_area_id"]').val();
                const areaName = $('select[name="delivery_area_id"] option:selected').text();

                if (!pickupStoreId || !areaId) {
                    AIZ.plugins.notify('warning', 'Please select pickup store and delivery area');
                    return;
                }

                btn.prop('disabled', true);
                if (!btn.find('.spinner-border').length) {
                    btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
                }

                $.ajax({
                    url: "{{ route('redx_order.create') }}",
                    type: "POST",
                    data: {
                        _token: AIZ.data.csrf,
                        order_id: {{ $order->id }},
                        shipping_system: 'redx',
                        pickup_store_id: pickupStoreId,
                        delivery_area_id: areaId,
                        delivery_area: areaName
                    },
                    success: function (res) {
                        if (res.success) {
                            AIZ.plugins.notify('success', res.message);
                            location.reload();
                        } else {
                            AIZ.plugins.notify('danger', res.message);
                        }
                    },
                    error: function () {
                        AIZ.plugins.notify('danger', 'RedX API error');
                    }
                });
            });
        </script>
    @endif
@endsection
