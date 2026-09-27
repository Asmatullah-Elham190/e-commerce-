<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            #{{ $order->code }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">✕</button>
    </div>
</div>

<input type="hidden" id="oc_order_id" value="{{ $order->id }}">

<div class="right-offcanvas-body position-absolute px-30px pt-20px"
     style="overflow-y:auto; top:60px; bottom:80px; left:0; right:0;">

    @php
        $delivery_status = $order->delivery_status;
        $payment_status  = $order->payment_status;
        $shipping_method = $order->shipping_method ?? null;
    @endphp

    @if (addon_is_activated('delivery_boy'))
        @if (!in_array($shipping_method, ['shiprocket','steadfast','pathao','redx']))
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Assign Delivery Boy') }}</label>
                @if (in_array($delivery_status, ['pending','confirmed','picked_up']) && auth()->user()->can('assign_delivery_boy_for_orders'))
                    <select class="form-control aiz-selectpicker mo-custom-select" id="oc_delivery_boy"
                            data-live-search="true" data-minimum-results-for-search="Infinity">
                        <option value="">{{ translate('Select Delivery Boy') }}</option>
                        @foreach ($delivery_boys as $db)
                            <option value="{{ $db->id }}" @if($order->assign_delivery_boy == $db->id) selected @endif>
                                {{ $db->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}" disabled>
                @endif
            </div>
        @endif
    @endif   

    <div class="form-group mb-3">
        <label class="fw-700">{{ translate('Payment Status') }}</label>
        @if (auth()->user()->can('update_order_payment_status') && $payment_status == 'unpaid' && $order->payment_type != 'cash_on_delivery')
            <select class="form-control aiz-selectpicker mo-custom-select" id="oc_payment_status"
                    data-minimum-results-for-search="Infinity">
                <option value="unpaid" @selected($payment_status == 'unpaid')>{{ translate('Unpaid') }}</option>
                <option value="paid"   @selected($payment_status == 'paid')>{{ translate('Paid') }}</option>
            </select>
        @else
            <input type="text" class="form-control" value="{{ ucfirst($payment_status) }}" disabled>
        @endif
    </div>

    <div class="form-group mb-3">
        <label class="fw-700">{{ translate('Delivery Status') }}</label>
        @if (in_array($shipping_method, ['shiprocket','steadfast','pathao','redx']))
            <input type="text" class="form-control"
                   value="{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}" disabled>
        @elseif (auth()->user()->can('update_order_delivery_status') && !in_array($delivery_status, ['delivered','cancelled']))
            <select class="form-control aiz-selectpicker  mo-custom-select" id="oc_delivery_status"
                    data-minimum-results-for-search="Infinity">
                <option value="pending"    @selected($delivery_status == 'pending')>{{ translate('Pending') }}</option>
                <option value="confirmed"  @selected($delivery_status == 'confirmed')>{{ translate('Confirmed') }}</option>
                <option value="picked_up"  @selected($delivery_status == 'picked_up')>{{ translate('Picked Up') }}</option>
                <option value="on_the_way" @selected($delivery_status == 'on_the_way')>{{ translate('On The Way') }}</option>
                <option value="delivered"  @selected($delivery_status == 'delivered')>{{ translate('Delivered') }}</option>
                <option value="cancelled"  @selected($delivery_status == 'cancelled')>{{ translate('Cancel') }}</option>
            </select>
        @else
            <input type="text" class="form-control"
                   value="{{ ucfirst(str_replace('_', ' ', $delivery_status)) }}" disabled>
        @endif
    </div>

    @if (addon_is_activated('shiprocket') || addon_is_activated('steadfast') || addon_is_activated('pathao') || addon_is_activated('redx'))
        @php
            $addons = [];
            foreach (['shiprocket','steadfast','pathao','redx'] as $addon) {
                if (addon_is_activated($addon)) $addons[] = $addon;
            }
            $shipping_systems = App\Models\ShippingSystem::where('active', 1)->whereIn('name', $addons)->get();
        @endphp

        <div class="form-group mb-3">
            <label class="fw-700">{{ translate('Shipping System') }}</label>
            @if (in_array($order->delivery_status, ['pending','confirmed']))
                @if ($shipping_method)
                    <input type="text" class="form-control" value="{{ ucfirst(translate($shipping_method)) }}" disabled>
                @else
                    <select class="form-control aiz-selectpicker  mo-custom-select" id="oc_shipping_system"
                            data-minimum-results-for-search="Infinity">
                        <option value="">{{ translate('Select Shipping System') }}</option>
                        @foreach ($shipping_systems as $ss)
                            <option value="{{ $ss->name }}">{{ ucfirst($ss->name) }}</option>
                        @endforeach
                    </select>
                @endif
            @else
                <input type="text" class="form-control" value="{{ ucfirst(translate($shipping_method)) }}" disabled>
            @endif
        </div>

        <div id="oc_shipping_extra_fields"></div>

        @if ($shipping_method == 'shiprocket')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Shiprocket Order Status') }}</label>
                <input type="text" class="form-control"
                       value="{{ ucfirst(str_replace('_',' ',$order->shiprocket_order_status)) }}" disabled>
            </div>
            @if ($order->shiprocket_awb)
                <div class="form-group mb-3">
                    <label class="fw-700">{{ translate('Shiprocket Delivery Status') }}</label>
                    <input type="text" class="form-control"
                           value="{{ ucfirst(str_replace('_',' ',$order->shiprocket_status)) }}" disabled>
                </div>
            @endif
        @elseif ($shipping_method == 'steadfast')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Steadfast Status') }}</label>
                <input type="text" class="form-control"
                       value="{{ ucfirst(str_replace('_',' ',$order->steadfast_status)) }}" disabled>
            </div>
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Steadfast Consignment Id') }}</label>
                <input type="text" class="form-control" value="{{ $order->steadfast_consignment_id }}" disabled>
            </div>
        @elseif ($shipping_method == 'pathao')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Pathao Status') }}</label>
                <input type="text" class="form-control"
                       value="{{ ucfirst(str_replace('_',' ',$order->pathao_status)) }}" disabled>
            </div>
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Pathao Consignment Id') }}</label>
                <input type="text" class="form-control" value="{{ $order->pathao_consignment_id }}" disabled>
            </div>
        @elseif ($shipping_method == 'redx')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Redx Status') }}</label>
                <input type="text" class="form-control"
                       value="{{ ucfirst(str_replace('_',' ',$order->redx_status)) }}" disabled>
            </div>
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Redx Tracking Id') }}</label>
                <input type="text" class="form-control" value="{{ $order->redx_tracking_id }}" disabled>
            </div>
        @endif

        @if ($shipping_method == 'shiprocket' && $order->shiprocket_shipment_id && !$order->shiprocket_courier_id)
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Courier') }}</label>
                <select class="form-control aiz-selectpicker  mo-custom-select" id="oc_shiprocket_courier" data-live-search="true">
                    <option value="">{{ translate('Loading...') }}</option>
                </select>
            </div>
        @elseif ($shipping_method == 'shiprocket' && $order->shiprocket_courier_id)
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Courier') }}</label>
                <input type="text" class="form-control" value="{{ $order->shiprocket_courier_name }}" disabled>
            </div>
        @endif

        @if ($shipping_method === 'shiprocket')
            @if ($order->pickup_scheduled_at)
                <div class="form-group mb-3">
                    <label class="fw-700">{{ translate('Pickup Scheduled') }}</label>
                    <input type="text" class="form-control" value="{{ $order->pickup_scheduled_at }}" disabled>
                </div>
            @endif
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('AWB Code') }}</label>
                <input type="text" class="form-control" value="{{ $order->shiprocket_awb }}" disabled>
            </div>
            @if ($order->shiprocket_awb && !$order->pickup_scheduled_at)
                <input type="hidden" id="oc_request_pickup" value="1">
                <div class="alert alert-warning py-2 fs-13">
                    {{ translate('Clicking Confirm will request a pickup for this order.') }}
                </div>
            @endif
            @if ($order->shiprocket_awb)
                <div class="form-group mb-3">
                    <label class="fw-700">{{ translate('Download Label') }}</label>
                    <a href="{{ route('shiprocket.download.label', $order->id) }}"
                       class="btn btn-sm btn-install w-auto h-auto d-block" title="Download Label">
                        <i class="las la-2x la-download"></i>
                    </a>
                </div>
                @if ($delivery_status != 'cancelled')
                    <div class="form-group mb-3">
                        <label class="fw-700">{{ translate('Download Manifest') }}</label>
                        <a href="{{ route('shiprocket.download.manifest', $order->id) }}"
                           class="btn btn-sm btn-install w-auto h-auto d-block" title="Download Manifest">
                            <i class="las la-2x la-download"></i>
                        </a>
                    </div>
                @endif
            @endif
        @elseif ($shipping_method === 'steadfast')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Steadfast Tracking Code') }}</label>
                <input type="text" class="form-control" value="{{ $order->steadfast_tracking_code }}" disabled>
            </div>
        @elseif ($shipping_method === 'pathao')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Pathao Delivery Fee') }}</label>
                <input type="text" class="form-control" value="{{ $order->pathao_delivery_fee }} TK" disabled>
            </div>
        @elseif ($shipping_method === 'redx')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Redx Delivery Charge') }}</label>
                <input type="text" class="form-control" value="{{ $order->redx_charge }} TK" disabled>
            </div>
        @endif
        @if ($order->order_from != 'pos')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Tracking Code') }}</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control tracking_code_input"
                        value="{{ $order->tracking_code }}"
                        readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary copy_tracking_code_btn"
                            type="button">
                            <i class="las la-copy"></i>
                            {{ translate('Copy') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
    
    @else
        @if ($order->order_from != 'pos')
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Tracking Code') }}</label>

                <div class="input-group">
                    <input type="text"
                        class="form-control tracking_code_input"
                        value="{{ $order->tracking_code }}"
                        readonly>

                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary copy_tracking_code_btn"
                            type="button">

                            <i class="las la-copy"></i>
                            {{ translate('Copy') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif    
    @endif

    <div class="form-group mb-3">
        <label class="fw-700">{{ translate('Notes') }}</label>
        <div class="input-group">
            <input type="text" class="form-control" id="notes" maxlength="120"
                value="{{ $order->order_note }}" name="order_note"
                placeholder="{{ translate('Notes') }}">
        </div>
    </div>

</div>

<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px" id="offcanvas-btn">
    <div class="d-flex justify-content-end footer-btn">
        <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel"
                onclick="closeOffcanvas()">
            {{ translate('Cancel') }}
        </button>
        <button type="button" class="d-block fs-14 fw-700 py-10px save action-btn"
                id="oc_confirm_btn">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>