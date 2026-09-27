@php
	$logo = (json_decode(get_setting('invoice_config'), true) ?? [])['invoice_logo'] ?? null;

	$invoice_title = (json_decode(get_setting('invoice_config'), true) ?? [])['invoice_title'] ?? null;
	$custom_invoice_title = (json_decode(get_setting('invoice_config'), true) ?? [])['custom_invoice_title'] ?? null;
	
	$company_name_and_address = (json_decode(get_setting('invoice_config'), true) ?? [])['company_name_and_address'] ?? null;
	$company_name = (json_decode(get_setting('invoice_config'), true) ?? [])['company_name'] ?? null;
	$custom_company_name = (json_decode(get_setting('invoice_config'), true) ?? [])['custom_company_name'] ?? null;
	$address = (json_decode(get_setting('invoice_config'), true) ?? [])['address'] ?? null;
	$custom_address = (json_decode(get_setting('invoice_config'), true) ?? [])['custom_address'] ?? null;

	$phone_email = (json_decode(get_setting('invoice_config'), true) ?? [])['phone_email'] ?? null;
	$phone = (json_decode(get_setting('invoice_config'), true) ?? [])['phone'] ?? null;
	$custom_phone = (json_decode(get_setting('invoice_config'), true) ?? [])['custom_phone'] ?? null;
	$email = (json_decode(get_setting('invoice_config'), true) ?? [])['email'] ?? null;
	$custom_email = (json_decode(get_setting('invoice_config'), true) ?? [])['custom_email'] ?? null;

	$footer_text = (json_decode(get_setting('invoice_config'), true) ?? [])['footer_text'] ?? null;
	$barcode_type = (json_decode(get_setting('invoice_config'), true) ?? [])['barcode_type'] ?? 'qrcode';
	$barcode_encode = (json_decode(get_setting('invoice_config'), true) ?? [])['barcode_encode'] ?? null;
	$custom_barcode_value = (json_decode(get_setting('invoice_config'), true) ?? [])['custom_barcode_value'] ?? null;
	$business_info = json_decode(get_setting('business_info'), true) ?? [];

	if ($barcode_encode == 'custom_value')
		$bval = $custom_barcode_value ?? $order->code ?? '';
	elseif ($barcode_encode == 'tracking_code')
		$bval = $order->tracking_code ?? '';
	else
		$bval = $order->code ?? '';


	$show_human_readable_text_below_barcode = (json_decode(get_setting('invoice_config'), true) ?? [])['show_human_readable_text_below_barcode'] ?? null;
	$show_qr_code_alongside_barcode = (json_decode(get_setting('invoice_config'), true) ?? [])['show_qr_code_alongside_barcode'] ?? null;
	
	$show_platform_contact = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_platform_contact'] ?? null;
	$show_seller_contact = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_seller_contact'] ?? null;
	$show_tracking_code = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_tracking_code'] ?? null;
	$show_customer_name = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_customer_name'] ?? null;
	$show_billing_address = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_billing_address'] ?? null;
	$show_order_notes = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_order_notes'] ?? null;
	$show_product_image = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_product_image'] ?? null;
	$show_sku = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_sku'] ?? null;
	$show_product_variation = (json_decode(get_setting('invoice_config'), true) ?? [])['fields']['show_product_variation'] ?? null;

	$shipping = json_decode($order->shipping_address);
	$billing = json_decode($order->billing_address) ?? $shipping;
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
            $dp('2114');
            $ck = 104;
            foreach (str_split($value) as $idx => $ch) {
                $ck += ($idx + 1) * (ord($ch) - 32);
                if (isset($c128map[$ch])) $dp($c128map[$ch]);
            }
            $ck %= 103;
            $cc = chr($ck + 32);
            if (isset($c128map[$cc])) $dp($c128map[$cc]);
            $dp('2331112');
            $tw = $x + $quiet;
            $r = '';
            foreach ($bars as $b) $r .= "<rect x=\"{$b['x']}\" y=\"0\" width=\"{$b['w']}\" height=\"{$h}\" fill=\"#000\"/>";
            return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 {$tw} {$h}\" style=\"display:block;max-width:100%;height:{$h}px;\">{$r}</svg>";
        }
        return "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"200\" height=\"{$h}\"><rect width=\"200\" height=\"{$h}\" fill=\"#eee\"/><text x=\"100\" y=\"30\" text-anchor=\"middle\" font-size=\"11\" fill=\"#555\">{$value}</text></svg>";
    };
@endphp
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{  translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0; 
		}
		.gry-color *,
		.gry-color{
			color:#000;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .0rem .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
		.font-weight{
			font-weight: 900;
		}
		.strong{
			font-weight: bold;
		}
		html, body{
			height: 100%;
		}
		body{
			position: relative;
			min-height: 100%;
			padding-bottom: 80px;
			box-sizing: border-box;
		}
		.invoice-footer{
			position: absolute;
			bottom: 15px;
			left: 0;
			width: 100%;
			text-align: center;
			font-size: 12px;
			color: #000000;
		}
	</style>
</head>
<body>
	<div>
		<div style=" padding: 0 4rem;">
			<div style="padding: 2rem 0 1rem 0">
				<table>
					<tr>
						<td>
							@if($logo != null)
								<img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
							@else
								<img src="{{ static_asset('assets/img/logo.png') }}" height="30" style="display:inline-block;">
							@endif
						</td>
						<td style="font-size: 1.5rem;" class="text-right strong">
							@if($order->order_from=='pos')
								{{  translate('POS INVOICE') }}
							@elseif($invoice_title == 'custom')
								<title>{{ $custom_invoice_title }}</title>
							@elseif($invoice_title == 'invoice')
								<title>{{ translate('INVOICE') }}</title>
							@elseif($invoice_title == 'tax_invoice')
								<title>{{ translate('TAX INVOICE') }}</title>
							@else
								{{  translate('INVOICE') }}
							@endif
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div  style="padding:0.5rem 4rem; padding-bottom:0">
				<div>
					<table width="100%">
						<tr>
							<!-- LEFT COLUMN -->
							<td width="50%" valign="top">
								<table>
									<tr>
										<td style="font-size: 1.12rem;" class="strong">
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
										<td class="gry-color small">
											@if($company_name_and_address == 'get_from_general_settings')
												{{ $address }} {{ $business_info['postal_code'] ?? '' }}
											@elseif($company_name_and_address == 'custom')
												{{ $custom_address }}
											@else
												{{ get_setting('contact_address') }}	
											@endif
										</td>
									</tr>
									@if($show_platform_contact == 1)
										<tr>
											<td class="gry-color small">
												{{  translate('Email') }}: 
												@if($phone_email == 'get_from_general_settings')
													{{ $email }}
												@elseif($phone_email == 'custom')
													{{ $custom_email }}
												@else
													{{ get_setting('contact_email') }}
												@endif
											</td>
										</tr>
										<tr>
											<td class="gry-color small">
												{{  translate('Phone') }}: 
												@if($phone_email == 'get_from_general_settings')
													{{ $phone }}
												@elseif($phone_email == 'custom')
													{{ $custom_phone }}
												@else
													{{ get_setting('contact_phone') }}	
												@endif
											</td>
										</tr>
									@endif
								</table>
								<br>
								 <!-- Sold BY -->

								<table>
									<tr>
										<td style="font-size: 1.12rem;" class="strong">{{ translate('Sold By') }}:</td>
									</tr>
									<tr>
										<td class="gry-color small strong">{{ $order->shop->name ?? get_setting('site_name') }}</td>
									</tr>
									<tr>
										<td class="gry-color small">{{ get_seller_address($order) }} </td>
									</tr>
									@if($show_seller_contact == 1 && $order->shop?->phone)
										<tr>
											<td class="gry-color small">Phone: {{ $order->shop->phone ?? null}} </td>
										</tr>
									@endif	
								</table>
								<br>
								<!-- GSTIN -->
								<table>
									<tr>
										@php 
											$gstin = get_seller_gstin($order);
										@endphp
										<td class="gry-color small">@if($gstin != null && is_numeric($first_order->gst_amount)) <span class="strong">{{ translate('GSTIN') }}:</span> {{ $gstin }} @endif</td>
									</tr>
								</table>
								<br>
								<table>
									<tr>
										<td>
											<span class="gry-color small strong">
												{{  translate('Payment method') }}:
											</span> 
											<span class="">
												{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
											</span>
										</td>
									</tr>
									<tr>
										<td class="gry-color small">
											<span class="gry-color small strong">
												{{  translate('Delivery Type') }}:
											</span> 
											<span class="">
												@if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
													{{ translate('Home Delivery') }}
												@elseif ($order->shipping_type == 'pickup_point')
													@if ($order->pickup_point != null)
														{{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
													@else
														{{ translate('Pickup Point') }}
													@endif
												@elseif ($order->shipping_type == 'carrier')
													@if ($order->carrier != null)
														{{ $order->carrier->name }} ({{ translate('Carrier') }})
														<br>
														{{ translate('Transit Time').' - '.$order->carrier->transit_time }}
													@else
														{{ translate('Carrier') }}
													@endif
												@else
													{{ translate('N/A') }}

												@endif
											</span>
										</td>
									</tr>
								</table>
							</td>

							<!-- RIGHT COLUMN -->
							<td width="50%" valign="top">
								<!-- Order Detail -->
								<table>
									@if($order->invoice_number != null)
										<tr class="">
											<td class="gry-color small text-right"><span class="strong">{{ translate('Invoice No') }}:</span> {{ $order->invoice_number }}</td>
										</tr>
									@endif
									<tr class="">
										<td class="gry-color small text-right"><span class="strong">{{ translate('Order ID') }}:</span> {{ $order->code }}</td>
									</tr>
									@if($show_tracking_code == 1)
									<tr class="">
										<td class="gry-color small text-right"><span class="strong">{{ translate('Tracking Code') }}:</span> {{ $order->tracking_code }}</td>
									</tr>
									@endif
									<tr>
										<td class="gry-color small text-right"><span class="strong">{{ translate('Order Date') }}:</span> {{ date('d-m-Y', $order->date) }}</td>
									</tr>
								</table>
								@if($show_billing_address == 1)
								<table width="100%">
									<tr><td class="strong gry-color" style="font-size: 1.12rem;">{{ translate('Bill to') }}:</td></tr>
									<tr><td class="strong">{{ $billing->name }}</td></tr>
									<tr>
										<td class="gry-color small">
											{{ $billing->address ? $billing->address.',' : '' }}
											{{ $billing->city ? $billing->city.',' : '' }}
											{{ !empty($billing->state) ? $billing->state.' - ' : '' }}
											{{ $billing->postal_code ? $billing->postal_code.',' : '' }}
											{{ $billing->country }}
										</td>
									</tr>

									@if($billing->email)
									<tr><td class="gry-color small">{{ translate('Email') }}: {{ $billing->email }}</td></tr>
									@endif
									@if($billing->phone)
									<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $billing->phone }}</td></tr>
									@endif
								</table>
								<br>
								@endif

								<table width="100%">
									<tr><td class="strong gry-color" style="font-size: 1.12rem;">{{ translate('Ship to') }}:</td></tr>
									<tr><td class="strong">{{ $shipping->name }}</td></tr>
									<tr>
										<td class="gry-color small">
											{{ $shipping->address ? $shipping->address.',' : '' }}
											{{ $shipping->city ? $shipping->city.',' : '' }}
											{{ !empty($shipping->state) ? $shipping->state.' - ' : '' }}
											{{ $shipping->postal_code ? $shipping->postal_code.',' : '' }}
											{{ $shipping->country }}
										</td>

									</tr>
									@if($shipping->email)
									<tr><td class="gry-color small">{{ translate('Email') }}: {{ $shipping->email }}</td></tr>
									@endif
									@if($shipping->phone)
									<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $shipping->phone }}</td></tr>
									@endif
								</table>
							</td>
						</tr>
					</table>
				</div>
		</div>
			



		<div style="padding: 1rem 4rem;">
				<div>
					<table class="padding text-left small border-bottom">
						<thead>
							<tr class="gry-color " style="background-color: #eceff4">
								@if($show_product_image == 1)
								<th width="10%" class="text-left">{{ translate('Image') }}</th>
								@endif
								<th width="35%" class="text-left">{{ translate('Product Name') }}</th>
								<th width="10%" class="text-left">{{ translate('Qty') }}</th>
								
								@if(is_numeric($first_order->gst_amount))
								<th width="15%" class="text-left">{{ translate('Gross Amount')}}</th>
								<th width="15%" class="text-left">{{ translate('Discount/ Coupon')}}</th>
								<th width="15%" class="text-left">{{ translate('Taxable Value')}}</th>

								@if(same_state_shipping($order))
								<th width="10%" class="text-left">{{ translate('CGST') }}</th>
								<th width="10%" class="text-left">{{ translate('SGST') }}</th>
								@else
								<th width="10%" class="text-left">{{ translate('IGST') }}</th>
								@endif

								@else
								<th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
								<th width="10%" class="text-left">{{ translate('Tax') }}</th>
								@endif
								
								<th width="15%" class="text-right">{{ translate('Total') }}</th>
							</tr>
						</thead>
						<tbody class="strong">
							@foreach ($order->orderDetails as $key => $orderDetail)
								@if ($orderDetail->product != null)
									<tr class="">
										@if($show_product_image == 1)
											<td>
												@if($orderDetail->product->thumbnail_img != null)
													<img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}" alt=""  height="50" style="display:inline-block;"> 
												@else
													<img src="{{ static_asset('assets/img/logo.png') }}" height="30" style="display:inline-block;">
												@endif
											</td>
										@endif
										<td>
											{{ $orderDetail->product->name }} 
											@if($show_product_variation == 1)
											@if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif
											@endif
											@if($show_sku == 1)
											<br>
											<small>
												@php
													$product_stock = json_decode($orderDetail->product->stocks->first(), true);
												@endphp
												{{translate('SKU')}}: {{ $product_stock['sku'] ?? 'N/A' }}
											</small>
											@endif
										</td>
										<td class="">{{ $orderDetail->quantity }}</td>

										@if(is_numeric($first_order->gst_amount))
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

										@else
										<td class="currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
										<td class="currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
										@endif

										@if(is_numeric($first_order->gst_amount))
										<td class="text-right currency">{{ single_price($orderDetail->price - $orderDetail->coupon_discount + $gst_amount) }}</td>
										@else
										<td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
										@endif
										
									</tr>
									@if(is_numeric($first_order->gst_amount))
									<tr>
										<td class="border-top-0 border-bottom">
										</td>
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
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
		</div>

			
		<div style="padding:0 4rem;">
			<div>
					<table class="text-right sm-padding small">
						<thead>
							<tr>
								<th width="60%"></th>
								<th width="40%"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left">
									@if($barcode_type == 'qrcode')
										<div style="text-align: center;">
											{!! str_replace($removedXML, "", QrCode::size(100)->generate($bval)) !!}
											@if($show_human_readable_text_below_barcode == 1)
												<div style="font-size: 10px; font-family: sans-serif; margin-top: 5px;">
													{{ $bval }}
												</div>
											@endif
										</div>
									@else
										<table style="width: auto; margin-right: auto; border-collapse: collapse;">
											<tr>
												<td align="center" style="padding: 0;">
													<div style="line-height: 0;">
														{!! $makeSvg($bval, $barcode_type) !!}
													</div>
												</td>
											</tr>
											@if($show_human_readable_text_below_barcode == 1)
												<tr>
													<td align="center" style="font-size: 10px; font-family: sans-serif; padding-top: 5px;">
														{{ $bval }}
													</td>
												</tr>
											@endif
										</table>
										
										@if($show_qr_code_alongside_barcode == 1)
											<div style="margin-top: 10px;">
												{!! str_replace($removedXML, "", QrCode::size(80)->generate($bval)) !!}
												@if($show_human_readable_text_below_barcode == 1)
													<div style="font-size: 10px; font-family: sans-serif; margin-top: 2px;">
														{{ $bval }}
													</div>
												@endif
											</div>
										@endif
									@endif
								</td>
								<td>
									<table class="text-right sm-padding small">
										<tbody>
											@if(is_numeric($first_order->gst_amount))
											<tr>
												<th class="gry-color text-left">{{ translate('Sub Total') }}</th>
												<td class="currency">{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->orderDetails->sum('coupon_discount')) }}</td>
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
												<th class="text-left "><span style="font-weight: bold;">{{ translate('Grand Total') }}</span></th>
												<td class="currency"><span style="font-weight: bold;">{{ single_price($order->grand_total) }}</span></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

		</div>
		
	</div>
	@if($footer_text)
	<div class="invoice-footer">
		{!! $footer_text !!}
	</div>
	@endif
</body>
</html>
