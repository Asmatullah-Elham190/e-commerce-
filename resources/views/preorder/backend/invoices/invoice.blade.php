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
			font-size: 0.875rem;
            font-family: '<?php echo $font_family ?>';
            font-weight: normal;
            direction: <?php echo $direction ?>;
            text-align: <?php echo $text_align ?>;
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
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo $text_align ?>;
		}
		.text-right{
			text-align:<?php echo $not_text_align ?>;
		}
		.font-weight{
			font-weight: 900;
		}
		.strong{
			font-weight: bold;
		}
		.pr-0{
			padding-right: 0 !important;
		}
	</style>
</head>
<body>
	<div>

		@php
			$logo = get_setting('header_logo');
			$shipping_address = json_decode($order->address);
		@endphp

		<div style="padding: 0 4rem;">
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
						<td style="font-size: 1.5rem;" class="text-right strong">{{ translate('INVOICE') }}</td>
					</tr>
				</table>
			</div>
		</div>

		<div style="padding:0.5rem 4rem; padding-bottom:0">
			<table width="100%">
				<tr>
					<!-- LEFT COLUMN -->
					<td width="50%" valign="top">
						<table>
							<tr>
								<td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ get_setting('contact_address') }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
							</tr>
						</table>
						<br>
						<!-- Sold BY -->
						<table>
							<tr>
								<td style="font-size: 1rem;" class="strong">{{ translate('Sold By') }}:</td>
							</tr>
							<tr>
								<td class="gry-color small strong">{{ $order->shop->name ?? get_setting('site_name') }}</td>
							</tr>
							<tr>
								<td class="gry-color small">{{ get_seller_address($order) }}</td>
							</tr>
						</table>
						<br>
						<!-- GSTIN -->
						<table>
							<tr>
								@php 
									$gstin = get_seller_gstin($order);
								@endphp
								<td class="gry-color small">
									@if($gstin != null && is_numeric($order->gst_amount)) 
										<span class="strong">{{ translate('GSTIN') }}:</span> {{ $gstin }} 
									@endif
								</td>
							</tr>
						</table>
						<br>
						<table>
							<tr>
								<td>
									<span class="gry-color small strong">{{ translate('Payment method') }}:</span> 
									<span class="">{{ preorder_payment_type($order) }}</span>
								</td>
							</tr>
							<tr>
								<td class="gry-color small">
									<span class="gry-color small strong">{{ translate('Delivery Type') }}:</span> 
									<span class="">
										@if ($order->delivery_type != null && $order->delivery_type == 'home_delivery')
											{{ translate('Home Delivery') }}
										@elseif ($order->delivery_type == 'pickup_point')
											@if ($order->pickup_point != null)
												{{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
											@else
												{{ translate('Pickup Point') }}
											@endif
										@elseif ($order->delivery_type == 'carrier')
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
							<tr class="">
								<td class="gry-color small text-right"><span class="strong">{{ translate('Order ID') }}:</span> {{ $order->order_code }}</td>
							</tr>
							<tr>
								<td class="gry-color small text-right"><span class="strong">{{ translate('Order Date') }}:</span> {{ date('d-m-Y', $order->date) }}</td>
							</tr>
						</table>
						<table width="100%">
							<tr><td class="strong small gry-color">{{ translate('Bill to') }}:</td></tr>
							<tr><td class="strong">{{ $order->user->name }}</td></tr>
							<tr>
								<td class="gry-color small">
									{{ $order->address?->address ?? '' }},
									{{ $order->address?->city?->name ?? '' }},
									{{ $order->address?->state->name ?? '' }},
									{{ $order->address?->country->name ?? '' }}
								</td>
							</tr>
							<tr><td class="gry-color small">{{ translate('Email') }}: {{ $order->address?->email ?? $order->user->email}}</td></tr>
							<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $order->address?->phone }}</td></tr>
						</table>
						<br>

						<table width="100%">
							<tr><td class="strong small gry-color">{{ translate('Ship to') }}:</td></tr>
							<tr><td class="strong">{{ $order->user->name }}</td></tr>
							<tr>
								<td class="gry-color small">
									{{ $order->address?->address ?? '' }},
									{{ $order->address?->city?->name ?? '' }},
									{{ $order->address?->state->name ?? '' }},
									{{ $order->address?->country->name ?? '' }}
								</td>
							</tr>
							<tr><td class="gry-color small">{{ translate('Email') }}: {{ $order->address?->email ?? $order->user->email}}</td></tr>
							<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $order->address?->phone }}</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>

		<div style="padding: 0.5rem 4rem;">
			<div>
				<table class="padding text-left small border-bottom">
					<thead>
						<tr class="gry-color" style="background-color: #eceff4">
							<th width="35%" class="text-left">{{ translate('Product Name') }}</th>
							<th width="10%" class="text-left">{{ translate('Qty') }}</th>
							
							@if(is_numeric($order->gst_amount))
							<th width="15%" class="text-left">{{ translate('Gross Amount')}}</th>
							<th width="15%" class="text-left">{{ translate('Discount/ Coupon')}}</th>
							<th width="15%" class="text-left">{{ translate('Taxable Value')}}</th>

							@if($order->address_id && preorder_same_state_shipping($order))
							<th width="10%" class="text-left">{{ translate('CGST') }}</th>
							<th width="10%" class="text-left">{{ translate('SGST') }}</th>
							@elseif($order->address_id)
							<th width="10%" class="text-left">{{ translate('IGST') }}</th>
							@else
							<th width="10%" class="text-left">{{ translate('GST') }}</th>
							@endif

							@else
							<th width="15%" class="text-left">{{ translate('Unit Price') }}</th>
							<th width="10%" class="text-left">{{ translate('Tax') }}</th>
							@endif
							
							<th width="15%" class="text-right">{{ translate('Total') }}</th>
						</tr>
					</thead>
					<tbody class="strong">
						@if ($order->preorder_product != null)
							<tr class="">
								<td>
									{{ $order->preorder_product->product_name }} 
									@if($order->preorder_product->variation != null) ({{ $order->preorder_product->variation }}) @endif
									<br>
									<small>
										{{translate('SKU')}}: {{ $order->preorder_product->sku ?? 'N/A' }}
									</small>
								</td>
								<td class="">{{ $order->quantity }}</td>

								@if(is_numeric($order->gst_amount))
								<td class="border-top-0 border-bottom">
									{{ single_price($order->unit_price * $order->quantity) }}
								</td>

								<td class="border-top-0 border-bottom">
									{{ single_price($order->coupon_discount + $order->product_discount) }}
								</td>

								<td class="border-top-0 border-bottom">
									{{ single_price($order->subtotal) }}
								</td>
								
								@php 
									$gst_amount = get_gst_by_price_and_rate($order->subtotal, $order->gst_rate);
									$shipping_gst = get_gst_by_price_and_rate($order->shipping_cost, $order->gst_rate);
								@endphp

								@if($order->address_id)
									@if(preorder_same_state_shipping($order))
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
								<td class="border-top-0 border-bottom">{{ single_price($gst_amount) }}</td>
								@endif

								@else
								<td class="currency">{{ single_price($order->preorder_product->unit_price) }}</td>
								<td class="currency">{{ single_price($order->tax) }}</td>
								@endif

								@if(is_numeric($order->gst_amount))
								<td class="text-right currency">{{ single_price($order->subtotal + $gst_amount) }}</td>
								@else
								<td class="text-right currency">{{ single_price($order->unit_price * $order->quantity) }}</td>
								@endif
							</tr>
							@if(is_numeric($order->gst_amount))
							<tr>
								<td class="border-top-0 border-bottom">
									{{translate('Shipping')}}
								</td>
								<td class="border-top-0 border-bottom">
									1
								</td>
								<td class="border-top-0 border-bottom">
									{{ single_price($order->shipping_cost) }}
								</td>
								<td class="border-top-0 border-bottom">
									{{ single_price(0) }}
								</td>
								<td class="border-top-0 border-bottom">
									{{ single_price($order->shipping_cost) }}
								</td>
								@if($order->address_id)
									@if(preorder_same_state_shipping($order))
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
								@else
								<td class="border-top-0 border-bottom">
									{{ single_price($shipping_gst) }}
								</td>
								@endif
								<td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($order->shipping_cost + $shipping_gst) }}</td>
							</tr>
							@endif
						@endif
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
								@php
									$removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
								@endphp
								{!! str_replace($removedXML,"", QrCode::size(100)->generate($order->order_code)) !!}
							</td>
							<td>
								<table class="text-right sm-padding small">
									<tbody>
										@if(is_numeric($order->gst_amount))
										<tr>
											<th class="gry-color text-left">{{ translate('Sub Total') }}</th>
											<td class="currency">{{ single_price($order->subtotal + $order->shipping_cost) }}</td>
										</tr>
										<tr class="border-bottom">
											<th class="gry-color text-left">{{ translate('Total GST') }}</th>
											<td class="currency">{{ single_price($order->gst_amount) }}</td>
										</tr>
										@else
										<tr>
											<th class="gry-color text-left">{{ translate('Sub Total') }}</th>
											<td class="currency">{{ single_price($order->unit_price * $order->quantity) }}</td>
										</tr>
										<tr>
											<th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
											<td class="currency">{{ single_price($order->shipping_cost) }}</td>
										</tr>
										<tr>
											<th class="gry-color text-left">{{ translate('Total Tax') }}</th>
											<td class="currency">{{ single_price($order->tax) }}</td>
										</tr>
										@if($order->is_coupon_applied)
										<tr>
											<th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
											<td class="currency">{{ single_price($order->coupon_discount) }}</td>
										</tr>
										@endif
										<tr>
											<th class="gry-color text-left">{{ translate('Product Discount') }}</th>
											<td class="currency">{{ single_price($order->product_discount) }}</td>
										</tr>
										@endif
										<tr>
											<th class="text-left"><span style="font-weight: bold;">{{ translate('Grand Total') }}</span></th>
											<td class="currency"><span style="font-weight: bold;">{{ single_price($order->grand_total) }}</span></td>
										</tr>
										@if($order->prepayment !== null)
										<tr>
											<th class="gry-color text-left" style="font-weight: bold;">{{ translate('Prepayment') }}</th>
											<td class="currency" style="font-weight: bold;">{{ single_price($order->prepayment) }}</td>
										</tr>
										<tr>
											<th class="gry-color text-left">{{ translate($order->final_order_status != 2 ? 'Remaining' : 'Final Payment') }}</th>
											<td class="currency">{{ single_price($order->grand_total - $order->prepayment) }}</td>
										</tr>
										@endif
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		@if($order->shipping_note != null)
		<div style="padding:0 4rem; border: 1px solid rgb(168, 168, 168); margin:10px; margin-top:30px;">
			<p class="fw-700 fs-16">{{translate('Shipping Note')}}:</p>
			<p>{{$order->shipping_note}}</p>
		</div>
		@endif
		
	</div>
</body>
</html>