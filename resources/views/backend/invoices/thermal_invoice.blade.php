@php
    $invoice_config = json_decode(get_setting('invoice_config'), true) ?? [];
    $thermal_printer = json_decode(get_setting('thermal_printer'), true) ?? [];

    $logo                = $invoice_config['invoice_logo'] ?? null;
    $invoice_title       = $invoice_config['invoice_title'] ?? null;
    $custom_invoice_title= $invoice_config['custom_invoice_title'] ?? null;
    $company_name_and_address = $invoice_config['company_name_and_address'] ?? null;
    $company_name        = $invoice_config['company_name'] ?? null;
    $custom_company_name = $invoice_config['custom_company_name'] ?? null;
    $address             = $invoice_config['address'] ?? null;
    $custom_address      = $invoice_config['custom_address'] ?? null;
    $phone_email         = $invoice_config['phone_email'] ?? null;
    $phone               = $invoice_config['phone'] ?? null;
    $custom_phone        = $invoice_config['custom_phone'] ?? null;
    $email               = $invoice_config['email'] ?? null;
    $custom_email        = $invoice_config['custom_email'] ?? null;
    $footer_text         = $invoice_config['footer_text'] ?? null;
    $barcode_type        = $invoice_config['barcode_type'] ?? null;
    $barcode_encode      = $invoice_config['barcode_encode'] ?? null;
    $custom_barcode_value= $invoice_config['custom_barcode_value'] ?? null;

    $show_logo   = $thermal_printer['fields']['show_logo'] ?? null;
    $show_platform_contact   = $thermal_printer['fields']['show_platform_contact'] ?? null;
    $show_seller_contact     = $thermal_printer['fields']['show_seller_contact'] ?? null;
    $show_tracking_code     = $thermal_printer['fields']['show_tracking_code'] ?? null;
    $show_customer_name      = $thermal_printer['fields']['show_customer_name'] ?? null;
    $show_billing_address    = $thermal_printer['fields']['show_billing_address'] ?? null;
    $show_order_notes        = $thermal_printer['fields']['show_order_notes'] ?? null;
    $show_sku                = $thermal_printer['fields']['show_sku'] ?? null;
    $show_product_variation  = $thermal_printer['fields']['show_product_variation'] ?? null;
    $show_barcode                = $thermal_printer['fields']['show_barcode'] ?? null;
    $show_qrcode                = $thermal_printer['fields']['show_qr_code'] ?? null;
    $show_human_readable_text_below_barcode = $invoice_config['show_human_readable_text_below_barcode'] ?? null;
    $show_qr_code_alongside_barcode         = $invoice_config['show_qr_code_alongside_barcode'] ?? null;

    if ($barcode_encode == 'custom_value')
        $bval = $custom_barcode_value ?? $order->code ?? '';
    elseif ($barcode_encode == 'tracking_code')
        $bval = $order->tracking_code ?? $order->code ?? '';
    else
        $bval = $order->code ?? '';

    $shipping    = json_decode($order->shipping_address);
    $billing     = json_decode($order->billing_address) ?? $shipping;
    $first_order = $order->orderDetails->first();
    $removedXML  = '<?xml version="1.0" encoding="UTF-8"?>';

    $code39map = [
        '0'=>'101001101101','1'=>'110100101011','2'=>'101100101011',
        '3'=>'110110010101','4'=>'101001101011','5'=>'110100110101',
        '6'=>'101100110101','7'=>'101001011011','8'=>'110100101101',
        '9'=>'101100101101','A'=>'110101001011','B'=>'101101001011',
        'C'=>'110110100101','D'=>'101011001011','E'=>'110101100101',
        'F'=>'101101100101','G'=>'101010011011','H'=>'110101001101',
        'I'=>'101101001101','J'=>'101011001101','K'=>'110101010011',
        'L'=>'101101010011','M'=>'110110101001','N'=>'101011010011',
        'O'=>'110101101001','P'=>'101101101001','Q'=>'101010110011',
        'R'=>'110101011001','S'=>'101101011001','T'=>'101011011001',
        'U'=>'110010101011','V'=>'100110101011','W'=>'110011010101',
        'X'=>'100101101011','Y'=>'110010110101','Z'=>'100110110101',
        '-'=>'100101011011','.'=>'110010101101',' '=>'100110101101',
        '$'=>'100100100101','/'=>'100100101001','+'=>'100101001001',
        '%'=>'101001001001','*'=>'100101101101',
    ];
    $c128map = [
        ' '=>'212222','!'=>'222122','"'=>'222221','#'=>'121223','$'=>'121322',
        '%'=>'131222','&'=>'122213',"'"=>'122312','('=>'132212',')'=>'221213',
        '*'=>'221312','+'=>'231212',','=>'112232','-'=>'122132','.'=>'122231',
        '/'=>'113222','0'=>'123122','1'=>'123221','2'=>'223211','3'=>'221132',
        '4'=>'221231','5'=>'213212','6'=>'223112','7'=>'312131','8'=>'311222',
        '9'=>'321122',':'=>'321221',';'=>'312212','<'=>'322112','='=>'322211',
        '>'=>'212123','?'=>'212321','@'=>'232121','A'=>'111323','B'=>'131123',
        'C'=>'131321','D'=>'112313','E'=>'132113','F'=>'132311','G'=>'211313',
        'H'=>'231113','I'=>'231311','J'=>'112133','K'=>'112331','L'=>'132131',
        'M'=>'113123','N'=>'113321','O'=>'133121','P'=>'313121','Q'=>'211331',
        'R'=>'231131','S'=>'213113','T'=>'213311','U'=>'213131','V'=>'311123',
        'W'=>'311321','X'=>'331121','Y'=>'312113','Z'=>'312311','['=>'332111',
        '\\'=>'314111',']'=>'221411','^'=>'431111','_'=>'111224','`'=>'111422',
        'a'=>'121124','b'=>'121421','c'=>'141122','d'=>'141221','e'=>'112214',
        'f'=>'112412','g'=>'122114','h'=>'122411','i'=>'142112','j'=>'142211',
        'k'=>'241211','l'=>'221114','m'=>'413111','n'=>'241112','o'=>'134111',
        'p'=>'111242','q'=>'121142','r'=>'121241','s'=>'114212','t'=>'124112',
        'u'=>'124211','v'=>'411212','w'=>'421112','x'=>'421211','y'=>'212141',
        'z'=>'214121','{'=>'412121','|'=>'111143','}'=>'111341','~'=>'131141',
    ];

    $makeSvg = function(string $value, string $type) use ($code39map, $c128map): string {
        $h = 52; $quiet = 8; $u = 2;
        $v = strtoupper($value);
        if ($type === 'code39') {
            $encoded = '*' . $v . '*';
            $bars = []; $x = $quiet;
            foreach (str_split($encoded) as $ci => $ch) {
                if (!isset($code39map[$ch])) continue;
                if ($ci > 0) $x += $u;
                foreach (str_split($code39map[$ch]) as $bi => $bit) {
                    $w = ($bit === '1') ? $u * 2 + 1 : $u;
                    if ($bi % 2 === 0) $bars[] = ['x' => $x, 'w' => $w];
                    $x += $w;
                }
            }
            $tw = $x + $quiet;
            $r = '';
            foreach ($bars as $b) $r .= "<rect x=\"{$b['x']}\" y=\"0\" width=\"{$b['w']}\" height=\"{$h}\" fill=\"#000\"/>";
            return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 {$tw} {$h}\" style=\"display:block;max-width:100%;height:{$h}px;\">{$r}</svg>";
        }
        if ($type === 'code128') {
            $bars = []; $x = $quiet; $isBar = true;
            $dp = function(string $pat) use (&$bars, &$x, &$isBar, $u, $h) {
                foreach (str_split($pat) as $d) {
                    $w = (int)$d * $u;
                    if ($isBar && $w > 0) $bars[] = ['x' => $x, 'w' => $w];
                    $x += $w; $isBar = !$isBar;
                }
            };
            $dp('2114'); $ck = 104;
            foreach (str_split($value) as $idx => $ch) {
                $ck += ($idx + 1) * (ord($ch) - 32);
                if (isset($c128map[$ch])) $dp($c128map[$ch]);
            }
            $ck %= 103; $cc = chr($ck + 32);
            if (isset($c128map[$cc])) $dp($c128map[$cc]);
            $dp('2331112');
            $tw = $x + $quiet; $r = '';
            foreach ($bars as $b) $r .= "<rect x=\"{$b['x']}\" y=\"0\" width=\"{$b['w']}\" height=\"{$h}\" fill=\"#000\"/>";
            return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 {$tw} {$h}\" style=\"display:block;max-width:100%;height:{$h}px;\">{$r}</svg>";
        }
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"200\" height=\"{$h}\"><rect width=\"200\" height=\"{$h}\" fill=\"#eee\"/><text x=\"100\" y=\"30\" text-anchor=\"middle\" font-size=\"11\" fill=\"#555\">{$value}</text></svg>";
    };
@endphp

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <style media="all">
        @page {
            margin: 0;
            padding:0;
        }
        body{
            font-size: 10px;
            font-family: "monospace", "DejaVu Sans Mono", monospace;
            font-weight: normal;
            direction: <?php echo  $pdf_style_data['direction'] ?>;
            text-align: <?php echo  $pdf_style_data['text_align'] ?>;
            padding:0;
            margin:0;
            line-height: 1.2;
        }
        .currency-icons{
            font-family: '<?php echo  $font_family ?>';
         }
        .container {
            margin: 0 auto;
            padding: 3mm;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .text-left {
            text-align: left !important;
        }
        .bold {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        .double-divider {
            border-top: 2px solid #000;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .section {
            margin: 6px 0;
        }
        .product-item {
            border-bottom: 1px dotted #ccc;
            padding: 4px 0;
        }
        .gst-section {
            background: #f8f9fa;
            padding: 4px;
            margin: 4px 0;
            border-left: 3px solid #666;
        }
        .gst-table {
            width: 100%;
            background: transparent;
        }
        .gst-table td {
            padding: 1px 0;
            border: none;
        }
        .sku {
            font-size: 8px;
            color: #666;
            font-style: italic;
        }
        .grand-total {
            font-size: 12px;
            font-weight: bold;
            background: #000;
            color: white;
            padding: 4px;
            margin: 6px 0;
            text-align: center;
        }
        .summary-table {
            width: 100%;
            margin: 4px 0;
        }
        .summary-table td {
            padding: 2px 0;
            border: none;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <table class="text-center section">
            @if($logo != null && $show_logo == 1)
                <tr>
                    <td class="bold" style="font-size: 11px;">
						<img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
                    </td>
                </tr>
            @endif
            <tr>
                <td class="bold" style="font-size: 11px;">
                    @if($company_name_and_address == 'get_from_general_settings')
						{{ $company_name }}
					@elseif($company_name_and_address == 'custom')
						{{ $custom_company_name }}
					@else
						{{ get_setting('site_name') }}	
					@endif
                </td>
            </tr>
            <tr>
                <td style="font-size: 9px;">
                    @if($company_name_and_address == 'get_from_general_settings')
						{{ $address }}
					@elseif($company_name_and_address == 'custom')
						{{ $custom_address }}
					@else
						{{ get_setting('contact_address') }}	
					@endif
                </td>
            </tr>
            @if($show_platform_contact == 1)
                <tr>
                    <td style="font-size: 9px;">
                        {{ translate('Ph') }}: 
                        @if($phone_email == 'get_from_general_settings')
							{{ $phone }}
						@elseif($phone_email == 'custom')
							{{ $custom_phone }}
						@else
							{{ get_setting('contact_phone') }}	
						@endif
                        <br>
                        {{ translate('Email') }}: 
                        @if($phone_email == 'get_from_general_settings')
							{{ $email }}
						@elseif($phone_email == 'custom')
							{{ $custom_email }}
						@else
							{{ get_setting('contact_email') }}
						@endif
                    </td>
                </tr>
            @endif
        </table>
        
        <div class="double-divider"></div>
        
        <!-- Invoice Header -->
        <div class="section">
            <table class="text-center">
                <tr>
                    @if($show_qrcode == 1)
                    <td>
                        {!! str_replace($removedXML,"", QrCode::size(100)->generate($bval)) !!}
                    </td>
                    @endif
                    @if($show_barcode == 1)
                        <td>
                            <div style="line-height: 0;">
                                @php
                                    $resolved_barcode_type = (isset($barcode_type) && !empty($barcode_type) && in_array($barcode_type, ['code39', 'code128']))
                                        ? $barcode_type
                                        : 'code128';
                                @endphp
                                {!! $makeSvg($bval, $resolved_barcode_type) !!}
                            </div>
                        </td>
                    @endif
                </tr>
            </table>
            
            <table class="text-center">
                <tr>
                    <td class="bold" style="font-size: 12px;">{{ translate('RETAIL INVOICE') }}</td>
                </tr>
            </table>
            
            <table class="info-table">
                <tr>
                    <td width="60%">
                        @if($order->invoice_number != null)
                        <span class="bold">{{ translate('Invoice No') }}:</span><br>
                        <span>{{ $order->invoice_number }}</span><br>
                        @endif
                        <span class="bold">{{ translate('Order Id') }}:</span><br>
                        <span>{{ $order->code }}</span><br>
                        @if($show_tracking_code == 1)
                        <span class="bold">{{ translate('Tracking Code') }}:</span><br>
                        <span>{{ $order->tracking_code }}</span><br>
                        @endif
                    </td>
                    <td width="40%" class="text-right">
                        <span class="bold">{{ translate('Date') }}:</span><br>
                        <span>{{ date('d/m/Y h:i A', $order->date) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="bold">{{ translate('Payment') }}:</span> {{ strtoupper(str_replace('_', ' ', $order->payment_type)) }}
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="divider"></div>
        
        <!-- Sold BY -->
        <div class="section">
            <table>
                <tr>
                    <td class="bold">{{ translate('SOLD BY') }}</td>
                </tr>
                <tr>
                    <td>{{ $order->shop->name ?? get_setting('site_name') }}</td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">
                        {{ get_seller_address($order) }}
                    </td>
                </tr>
                @if($show_seller_contact == 1 && $order->shop?->phone)
                    <tr>
                        <td style="font-size: 9px;">
                            {{ translate('Phone') }}: {{ $order->shop->phone }} 
                        </td>
                    </tr>
                @endif
                @php 
                    $gstin = get_seller_gstin($order);
                @endphp
                @if($gstin != null && addon_is_activated('gst_system'))
                <tr>
                    <td style="font-size: 9px;">
                        {{ translate('GSTIN') }}: {{ $gstin }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 9px;">
                        {{ translate('Operator') }}: {{ $order->operatorUser ? $order->operatorUser->name : translate('N/A') }}
                    </td>
                </tr>
                @endif
						
            </table>
        </div>

        <div class="divider"></div>
        
        <!-- Products Section -->
        <div class="section">
            <table>
                <tr>
                    <td class="bold">{{ translate('PRODUCTS') }}</td>
                </tr>
            </table>
            
            @foreach ($order->orderDetails as $key => $orderDetail)
                @if ($orderDetail->product != null)
                    <div class="product-item">
                        <table>
                            <!-- Product Name -->
                            <tr>
                                <td class="bold" style="font-size: 9px;">
                                    {{ $orderDetail->product->name }}
                                    @if($show_product_variation == 1 && $orderDetail->variation != null)
                                        <br><span style="font-size: 8px;">({{ $orderDetail->variation }})</span>
                                    @endif
                                </td>
                            </tr>
                            
                            <!-- SKU -->
                            @php
                                $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                @endphp
                            @if($show_sku == 1)
                            <tr>
                                <td class="sku">
                                    {{ translate('SKU') }}: {{ $product_stock['sku'] }}
                                </td>
                            </tr>
                            @endif
                            
                            <!-- Basic Info -->
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td width="40%">{{ translate('Qty') }}: {{ $orderDetail->quantity }}</td>
                                            <td width="60%" class="text-right currency-icons">
                                                {{ translate('Price') }}: {{ single_price($orderDetail->price) }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            @php
                                $first_order = $order->orderDetails->first();
                            @endphp
                            
                            @if(is_numeric($first_order->gst_amount))
                                <!-- GST Details -->
                                <tr>
                                    <td>
                                        <div class="gst-section">
                                            <table class="gst-table">
                                                <tr>
                                                    <td width="60%">{{ translate('Gross Amt') }}:</td>
                                                    <td width="40%" class="text-right currency-icons">{{ single_price($orderDetail->price) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('Discount') }}:</td>
                                                    <td class="text-right currency-icons">{{ single_price($orderDetail->coupon_discount) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('Taxable Value') }}:</td>
                                                    <td class="text-right currency-icons">{{ single_price($orderDetail->price - $orderDetail->coupon_discount) }}</td>
                                                </tr>
                                                
                                                @php 
                                                    $gst_amount = get_gst_by_price_and_rate($orderDetail->price - $orderDetail->coupon_discount , $orderDetail->gst_rate);
                                                    $shipping_gst = get_gst_by_price_and_rate($orderDetail->shipping_cost, $orderDetail->gst_rate);
                                                @endphp
                                                
                                                @if(same_state_shipping($order))
                                                    <tr>
                                                        <td>{{ translate('CGST') }} ({{ $orderDetail->gst_rate/2 }}%):</td>
                                                        <td class="text-right currency-icons">{{ single_price($gst_amount/2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ translate('SGST') }} ({{ $orderDetail->gst_rate/2 }}%):</td>
                                                        <td class="text-right currency-icons">{{ single_price($gst_amount/2) }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ translate('IGST') }} ({{ $orderDetail->gst_rate }}%):</td>
                                                        <td class="text-right currency-icons">{{ single_price($gst_amount) }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Shipping with GST -->
                                <tr>
                                    <td>
                                        <div class="gst-section" style="background: #e9ecef;">
                                            <table class="gst-table">
                                                <tr>
                                                    <td colspan="2" class="bold">{{ translate('Shipping Details') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('Cost') }}:</td>
                                                    <td class="text-right currency-icons">{{ single_price($orderDetail->shipping_cost) }}</td>
                                                </tr>

                                                 @if(same_state_shipping($order))
                                                    <tr>
                                                        <td>{{ translate('CGST') }}:</td>
                                                        <td class="text-right currency-icons">{{ single_price($shipping_gst/2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ translate('SGST') }}:</td>
                                                        <td class="text-right currency-icons">{{ single_price($shipping_gst/2) }}</td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <td>{{ translate('IGST') }}:</td>
                                                        <td class="text-right currency-icons">{{ single_price($shipping_gst) }}</td>
                                                    </tr>
                                                    @endif
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Item Total -->
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td width="60%" class="bold">{{ translate('Item Total') }}:</td>
                                                <td width="40%" class="text-right currency-icons">
                                                    {{ single_price($orderDetail->price - $orderDetail->coupon_discount + $gst_amount + $orderDetail->shipping_cost + $shipping_gst) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                            @else
                                <!-- Non-GST Item Total -->
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td width="60%">{{ translate('Tax') }}:</td>
                                                <td width="40%" class="text-right currency-icons">{{ single_price($orderDetail->tax) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td width="60%" class="bold">{{ translate('Item Total') }}:</td>
                                                <td width="40%" class="text-right currency-icons">
                                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
        
        <div class="double-divider"></div>
        
        <!-- Summary Section -->
        <div class="section">
            <table>
                <tr>
                    <td class="bold">{{ translate('ORDER SUMMARY') }}</td>
                </tr>
            </table>
            
            <table class="summary-table">
                @if(is_numeric($first_order->gst_amount))
                    <tr>
                        <td width="70%">{{ translate('Sub Total (Taxable)') }}:</td>
                        <td width="30%" class="text-right currency-icons">{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->orderDetails->sum('coupon_discount')) }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Total GST') }}:</td>
                        <td class="text-right currency-icons">{{ single_price($order->orderDetails->sum('gst_amount')) }}</td>
                    </tr>
                @else
                    <tr>
                        <td width="70%">{{ translate('Sub Total') }}:</td>
                        <td width="30%" class="text-right currency-icons">{{ single_price($order->orderDetails->sum('price')) }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Shipping') }}:</td>
                        <td class="text-right currency-icons">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Tax') }}:</td>
                        <td class="text-right currency-icons">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                    </tr>
                    @if($order->coupon_discount > 0)
                        <tr>
                            <td>{{ translate('Coupon Discount') }}:</td>
                            <td class="text-right currency-icons">-{{ single_price($order->coupon_discount) }}</td>
                        </tr>
                    @endif
                @endif
            </table>
            
            <div class="double-divider"></div>
            
            <table>
                <tr>
                    <td width="70%" class="bold">{{ translate('GRAND TOTAL') }}</td>
                    <td width="30%" class="text-right currency-icons">{{ single_price($order->grand_total) }}</td>
                </tr>
            </table>
            
        </div>
        
        <!-- Footer -->
        <table class="text-center" style="margin-top: 10px;">
            <tr>
                <td style="font-size: 8px; color: #666;">{{ translate('Thank you for your purchase!') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>