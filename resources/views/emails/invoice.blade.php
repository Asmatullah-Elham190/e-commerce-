<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        * {
            margin: 0;
            padding: 0;
            line-height: 1.4;
            font-family: 'Roboto', sans-serif;
            color: #333542;
        }
        body {
            font-size: 0.875rem;
            direction: {{ $direction ?? 'ltr' }};
            text-align: {{ $text_align ?? 'left' }};
        }
        .gry-color, .gry-color * {
            color: #555;
        }
        table {
            width: 100%;
        }
        table th {
            font-weight: normal;
        }
        .padding th, .padding td {
            padding: 0.4rem 0.6rem;
        }
        .sm-padding td {
            padding: 0.15rem 0.6rem;
        }
        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #e5e7eb;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .strong {
            font-weight: bold;
        }
        .small {
            font-size: 0.82rem;
        }
        .currency {

        }
    </style>
</head>
<body>

<div style="padding: 0 4rem;">

    <!-- Header with logo + Invoice title -->
    <div style="padding: 2rem 0 1.2rem 0;">
        <table>
            <tr>
                <td>
                    @php $logo = get_setting('header_logo'); @endphp
                    @if($logo != null)
                        <img loading="lazy" src="{{ uploaded_asset($logo) }}" height="36" style="display:inline-block;">
                    @else
                        <img loading="lazy" src="{{ static_asset('assets/img/logo.png') }}" height="36" style="display:inline-block;">
                    @endif
                </td>
                <td class="text-right strong" style="font-size: 1.5rem;">
                    @if($order->order_from == 'pos')
                        {{ translate('POS INVOICE') }}
                    @else
                        {{ translate('INVOICE') }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Main content area -->
    <div style="padding-bottom: 1rem;">
        <table width="100%">
            <tr>
                <!-- LEFT COLUMN -->
                <td width="50%" valign="top">

                    <!-- Company Info -->
                    <table class="small">
                        <tr>
                            <td class="strong" style="font-size: 1.1rem;">{{ get_setting('site_name') }}</td>
                        </tr>
                        <tr><td class="gry-color">{{ get_setting('contact_address') }}</td></tr>
                        <tr><td class="gry-color">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td></tr>
                        <tr><td class="gry-color">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td></tr>
                    </table>

                    <br>

                    <!-- Sold By -->
                    <table class="small">
                        <tr>
                            <td class="strong" style="font-size: 1.1rem;">{{ translate('Sold By') }}:</td>
                        </tr>
                        <tr>
                            <td class="gry-color strong">
                                {{ $order->shop->name ?? get_setting('site_name') }}
                            </td>
                        </tr>
                        <tr><td class="gry-color">{{ get_seller_address($order) }}</td></tr>
                    </table>

                    <br>

                    <!-- GSTIN -->
                    @php $gstin = get_seller_gstin($order); @endphp
                    @if($gstin && is_numeric($order->orderDetails->first()->gst_amount ?? null))
                        <table class="small">
                            <tr>
                                <td class="gry-color"><span class="strong">{{ translate('GSTIN') }}:</span> {{ $gstin }}</td>
                            </tr>
                        </table>
                        <br>
                    @endif

                    <!-- Payment & Delivery -->
                    <table class="small">
                        <tr>
                            <td>
                                <span class="gry-color strong">{{ translate('Payment method') }}:</span>
                                {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="gry-color strong">{{ translate('Delivery Type') }}:</span>
                                @if ($order->shipping_type == 'home_delivery')
                                    {{ translate('Home Delivery') }}
                                @elseif ($order->shipping_type == 'pickup_point')
                                    @if ($order->pickup_point)
                                        {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                    @else
                                        {{ translate('Pickup Point') }}
                                    @endif
                                @elseif ($order->shipping_type == 'carrier')
                                    @if ($order->carrier)
                                        {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                        <br>{{ translate('Transit Time') }} - {{ $order->carrier->transit_time }}
                                    @else
                                        {{ translate('Carrier') }}
                                    @endif
                                @else
                                    {{ translate('N/A') }}
                                @endif
                            </td>
                        </tr>
                    </table>

                </td>

                <!-- RIGHT COLUMN -->
                <td width="50%" valign="top" class="text-right">

                    <!-- Order Info -->
                    <table class="small">
                        <tr><td class="gry-color strong">{{ translate('Order ID') }}: {{ $order->code }}</td></tr>
                        <tr><td class="gry-color strong">{{ translate('Order Date') }}: {{ date('d-m-Y', $order->date) }}</td></tr>
                    </table>

                    <br>

                    <!-- Bill To -->
                    @php
                        $billing = json_decode($order->billing_address) ?? json_decode($order->shipping_address);
                        $shipping = json_decode($order->shipping_address);
                    @endphp
                    <table class="small">
                        <tr><td class="strong gry-color" style="font-size: 1.1rem;">{{ translate('Bill to') }}:</td></tr>
                        <tr><td class="strong">{{ $billing->name }}</td></tr>
                        <tr>
                            <td class="gry-color">
                                {{ $billing->address ?? '' }}{{ $billing->address ? ', ' : '' }}
                                {{ $billing->city ?? '' }}{{ $billing->city ? ', ' : '' }}
                                {{ $billing->state ?? '' }}{{ !empty($billing->state) ? ' - ' : '' }}
                                {{ $billing->postal_code ?? '' }}{{ $billing->postal_code ? ', ' : '' }}
                                {{ $billing->country ?? '' }}
                            </td>
                        </tr>
                        @if(!empty($billing->email))
                            <tr><td class="gry-color">{{ translate('Email') }}: {{ $billing->email }}</td></tr>
                        @endif
                        @if(!empty($billing->phone))
                            <tr><td class="gry-color">{{ translate('Phone') }}: {{ $billing->phone }}</td></tr>
                        @endif
                    </table>

                    <br>

                    <!-- Ship To -->
                    <table class="small">
                        <tr><td class="strong gry-color" style="font-size: 1.1rem;">{{ translate('Ship to') }}:</td></tr>
                        <tr><td class="strong">{{ $shipping->name }}</td></tr>
                        <tr>
                            <td class="gry-color">
                                {{ $shipping->address ?? '' }}{{ $shipping->address ? ', ' : '' }}
                                {{ $shipping->city ?? '' }}{{ $shipping->city ? ', ' : '' }}
                                {{ $shipping->state ?? '' }}{{ !empty($shipping->state) ? ' - ' : '' }}
                                {{ $shipping->postal_code ?? '' }}{{ $shipping->postal_code ? ', ' : '' }}
                                {{ $shipping->country ?? '' }}
                            </td>
                        </tr>
                        @if(!empty($shipping->email))
                            <tr><td class="gry-color">{{ translate('Email') }}: {{ $shipping->email }}</td></tr>
                        @endif
                        @if(!empty($shipping->phone))
                            <tr><td class="gry-color">{{ translate('Phone') }}: {{ $shipping->phone }}</td></tr>
                        @endif
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <!-- Products Table -->
    <div style="padding: 1.5rem 0;">
        <table class="padding text-left small border-bottom">
            <thead>
                <tr style="background: #f3f4f6;" class="gry-color">
                    <th width="38%" class="text-left">{{ translate('Product Name') }}</th>
                    <th width="8%" class="text-left">{{ translate('Qty') }}</th>

                    @if(is_numeric($order->orderDetails->first()->gst_amount ?? null))
                        <th width="14%" class="text-left">{{ translate('Gross Amount') }}</th>
                        <th width="12%" class="text-left">{{ translate('Discount/Coupon') }}</th>
                        <th width="14%" class="text-left">{{ translate('Taxable Value') }}</th>
                        @if(same_state_shipping($order))
                            <th width="7%" class="text-left">{{ translate('CGST') }}</th>
                            <th width="7%" class="text-left">{{ translate('SGST') }}</th>
                        @else
                            <th width="14%" class="text-left">{{ translate('IGST') }}</th>
                        @endif
                    @else
                        <th width="14%" class="text-left">{{ translate('Unit Price') }}</th>
                        <th width="10%" class="text-left">{{ translate('Tax') }}</th>
                    @endif

                    <th width="15%" class="text-right">{{ translate('Total') }}</th>
                </tr>
            </thead>
            <tbody class="strong">
                @foreach ($order->orderDetails as $orderDetail)
                    @if ($orderDetail->product != null)
                        <tr>
                            <td>
                                {{ $orderDetail->product->getTranslation('name') }}
                                @if($orderDetail->variation) ({{ $orderDetail->variation }}) @endif
                                <br>
                                <small class="gry-color">
                                    {{ translate('SKU') }}: 
                                    @php
                                        $stock = json_decode($orderDetail->product->stocks->first() ?? '{}', true);
                                        echo $stock['sku'] ?? 'N/A';
                                    @endphp
                                </small>
                            </td>
                            <td>{{ $orderDetail->quantity }}</td>

                            @if(is_numeric($orderDetail->gst_amount ?? null))
                                <td>{{ single_price($orderDetail->price) }}</td>
                                <td>{{ single_price($orderDetail->coupon_discount ?? 0) }}</td>
                                <td>{{ single_price($orderDetail->price - ($orderDetail->coupon_discount ?? 0)) }}</td>
                                @php
                                    $taxable = $orderDetail->price - ($orderDetail->coupon_discount ?? 0);
                                    $gst_amount = get_gst_by_price_and_rate($taxable, $orderDetail->gst_rate);
                                    $shipping_gst = get_gst_by_price_and_rate($orderDetail->shipping_cost, $orderDetail->gst_rate);
                                @endphp
                                @if(same_state_shipping($order))
                                    <td>{{ single_price($gst_amount / 2) }}</td>
                                    <td>{{ single_price($gst_amount / 2) }}</td>
                                @else
                                    <td>{{ single_price($gst_amount) }}</td>
                                @endif
                            @else
                                <td class="currency">{{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                <td class="currency">{{ single_price($orderDetail->tax / $orderDetail->quantity) }}</td>
                            @endif

                            @if(is_numeric($orderDetail->gst_amount ?? null))
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price - ($orderDetail->coupon_discount ?? 0) + $gst_amount) }}
                                </td>
                            @else
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}
                                </td>
                            @endif
                        </tr>

                        <!-- Shipping line when GST is enabled -->
                        @if(is_numeric($orderDetail->gst_amount ?? null))
                            <tr>
                                <td class="gry-color">{{ translate('Shipping') }}</td>
                                <td>1</td>
                                <td>{{ single_price($orderDetail->shipping_cost) }}</td>
                                <td>{{ single_price(0) }}</td>
                                <td>{{ single_price($orderDetail->shipping_cost) }}</td>
                                @if(same_state_shipping($order))
                                    <td>{{ single_price($shipping_gst / 2) }}</td>
                                    <td>{{ single_price($shipping_gst / 2) }}</td>
                                @else
                                    <td>{{ single_price($shipping_gst) }}</td>
                                @endif
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->shipping_cost + $shipping_gst) }}
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals + QR -->
    <div style="padding: 1rem 0;">
        <table>
            <tr>
                <td width="50%">
                    {!! str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', QrCode::size(100)->generate($order->code)) !!}
                </td>
                <td width="50%">
                    <table class="sm-padding small text-right">
                        @if(is_numeric($order->orderDetails->first()->gst_amount ?? null))
                            <tr>
                                <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
                                <td class="currency">
                                    {{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->orderDetails->sum('coupon_discount')) }}
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="gry-color text-left">{{ translate('Total GST') }}</th>
                                <td class="currency">{{ single_price($order->orderDetails->sum('gst_amount')) }}</td>
                            </tr>
                        @else
                            <tr>
                                <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
                                <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
                            </tr>
                            <tr>
                                <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
                                <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
                                <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
                                <td class="currency">{{ single_price($order->coupon_discount) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="text-left strong" style="font-size: 1.05rem;">{{ translate('Grand Total') }}</th>
                            <td class="currency strong" style="font-size: 1.05rem;">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>