<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('product_delete'))
                <th>
                    <div class="form-group">
                        <div class="aiz-checkbox-inline">
                            <label class="aiz-checkbox pt-5px d-block">
                                <input type="checkbox" class="check-all">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                    </div>
                </th>
                @else
                <th class="hide-lg">#</th>
                @endif
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Order Code') }}</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">{{ translate('Products') }}</th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Customer') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Seller') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Amount') }}</th>
                <th class="hide-xl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Delivery Status') }}</th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Payment method') }}</th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Payment Status') }}</th>
                @if (addon_is_activated('refund_request'))
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Refund') }}</th>
                @endif
                <th class="text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Options') }}</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($orders as $key => $order)
            <tr class="data-row">
                <td class="align-middle w-40px">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('delete_order') || auth()->user()->can('export_order'))
                    <div class="form-group d-inline-block">
                        <label class="aiz-checkbox">
                            <input type="checkbox" class="check-one" name="id[]" value="{{ $order->id }}">
                            <span class="aiz-square-check mt-1"></span>
                        </label>
                    </div>
                    @else
                    <div class="form-group d-inline-block">{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</div>
                    @endif
                </td>

                <td data-label="Order-Code" class="align-middle">
                    <a href="{{ route('all_orders.show', encrypt($order->id)) }}" class="fw-600 text-primary">
                    {{ $order->code }}
                    </a>
                    @if ($order->shipping_method == 'shiprocket')
                        <br><span class="fw-bold">{{ translate('Shiprocket Order Id') }}:</span> {{ $order->shiprocket_order_id }}
                    @elseif ($order->shipping_method == 'steadfast')
                        <br><span class="fw-bold">{{ translate('Steadfast Consignment Id') }}:</span> {{ $order->steadfast_consignment_id }}
                    @elseif ($order->shipping_method == 'pathao')
                        <br><span class="fw-bold">{{ translate('Pathao Consignment Id') }}:</span> {{ $order->pathao_consignment_id }}
                    @elseif ($order->shipping_method == 'redx')
                        <br><span class="fw-bold">{{ translate('Redx Tracking Id') }}:</span> {{ $order->redx_tracking_id }}
                    @endif
                    @if ($order->viewed == 0)
                    <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                    @endif
                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                    <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                    @php
                        $source = strtolower($order->order_source);

                        $badgeClass = match($source) {
                            'walkin'   => 'badge-warning',
                            'facebook' => 'badge-primary',
                            'whatsapp' => 'badge-success',
                            default    => 'badge-danger',
                        };
                    @endphp

                    <span class="badge badge-inline {{ $badgeClass }}">{{ ucfirst($order->order_source) }}</span>
                    @endif
                    @if ($order->order_note != null)
                    <span data-toggle="tooltip" data-placement="right" title="{{ $order->order_note }}" class="">
                        <svg xmlns="http://www.w3.org/2000/svg" height="23px" viewBox="0 -960 960 960" width="24px" fill="#1492e6"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v400L600-120H200Zm360-80 200-200H560v200ZM280-400h200v-80H280v80Zm0-160h400v-80H280v80Z"/></svg>
                    </span>
                    @endif
                </td>

                <td data-label="OrderCount" class="fw-600 align-middle">
                    {{ count($order->orderDetails) }}
                </td>

                <td class="hide-xs align-middle" data-label="Customer">
                    <span class="fs-12 fw-200 d-block pt-1">
                        @if ($order->user != null)
                        {{ $order->user->name }}
                        @else
                        Guest ({{ $order->guest_id }})
                        @endif
                    </span>
                </td>

                <td class="hide-sm align-middle" data-label="Owner">
                    @php $shop = optional($order->shop); @endphp
                    {{-- max 20 charsthen ..., add title --}}
                    <a href="{{ $shop->id ? route('sellers.profile', encrypt($shop->id)) : '#' }}" title="{{ $shop->name ?? 'Inhouse Product' }}" class="fs-12 fs-md-14 fw-700 d-block">
                        {{ Str::limit($shop->name, 20)?? translate('Inhouse') }}
                    </a>
                </td>

                <td class="hide-md align-middle" data-label="Price Details">
                    <div class="border-width-3 border-left border-blue px-2 py-0 mb-1">
                        <p class="fs-14 fw-700 m-0">{{ single_price($order->grand_total) }}</p>
                    </div>
                </td>

                <td class="hide-xl align-middle" data-label="Delivery Status">
                    <p class="fs-14 fw-700 m-0  @if( $order->delivery_status == 'delivered' ) text-success @endif">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</p>

                    @if ($order->shipping_method == 'shiprocket')
                        <span class="fw-bold pt-10px">{{ translate('Shiprocket Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->shiprocket_status))) }}
                    @elseif ($order->shipping_method == 'steadfast')
                        <span class="fw-bold pt-10px">{{ translate('Steadfast Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->steadfast_status))) }}
                    @elseif ($order->shipping_method == 'pathao')
                        <span class="fw-bold pt-10px">{{ translate('Pathao Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->pathao_status))) }}
                    @elseif ($order->shipping_method == 'redx')
                        <span class="fw-bold pt-10px">{{ translate('Redx Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->redx_status))) }}
                    @endif
                </td>

                <td class="hide-xxl align-middle" data-label="Payment method">
                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                </td>

                <td class="hide-xxl align-middle" data-label="Payment Status">
                    @if ($order->payment_status == 'paid')
                    <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                    @else
                    <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                    @endif
                </td>

                @if (addon_is_activated('refund_request'))
                <td class="hide-xxl align-middle" data-label="Refund">
                    @if (count($order->refund_requests) > 0)
                    {{ count($order->refund_requests) }} {{ translate('Refund') }}
                    @else
                    {{ translate('No Refund') }}
                    @endif
                </td>
                @endif

                <td class="text-right align-middle">
                    <div class="dropdown float-right">
                        <button class="btn btn-light w-30px h-30px w-sm-35px h-sm-35px d-flex align-items-center justify-content-center action-toggle p-0" type="button"
                            data-toggle="dropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="16" viewBox="0 0 3 16">
                                <circle cx="1.5" cy="1.5" r="1.5" transform="translate(0 6.5)" />
                                <circle cx="1.5" cy="1.5" r="1.5" transform="translate(0 0)" />
                                <circle cx="1.5" cy="1.5" r="1.5" transform="translate(0 13)" />
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @php
                                $admin_user_id = get_admin()->id;
                                $thermal_printer = json_decode(get_setting('thermal_printer'), true) ?? [];
                            @endphp
                            @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)
                                @can('manage_order')
                                    <a href="#" data-order-id="{{ $order->id }}" id="manage_order" class="dropdown-item">
                                        <i class="las la-cog mr-2"></i> {{translate('Ouick Order Management')}}
                                    </a>
                                @endcan
                            @endif
                            @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                            <a class="dropdown-item" href="{{ route('admin.invoice.thermal_printer', $order->id) }}" target="_blank">
                                <i class="las la-print mr-2"></i> {{translate('Print')}}
                            </a>
                            @endif
                            @can('view_order_details')
                            <a href="{{ route('all_orders.show', encrypt($order->id)) }}" class="dropdown-item">
                                <i class="las la-eye mr-2"></i> {{translate('View Order')}}
                            </a>
                            @endcan
                            <a class="dropdown-item" href="{{ route('invoice.download', $order->id) }}" target="_blank">
                                <i class="las la-download mr-2"></i> {{ translate('Download Invoice') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('invoice.printer', $order->id) }}" target="_blank">
                                <i class="las la-download mr-2"></i> {{ translate('Print Invoice') }}
                            </a>
                            @if(isset($thermal_printer['generate_invoice_for_thermal_printer']) && $thermal_printer['generate_invoice_for_thermal_printer'] == 1)
                            <a class="dropdown-item" href="{{ route('thermal_invoice.print', $order->id) }}" target="_blank">
                                <i class="las la-download mr-2"></i> {{ translate('Print Thermal Invoice') }}
                            </a>
                            @endif
                            @can('can_download_and_print_shipping_label')
                                <a class="dropdown-item" href="{{ route('shipping_label.download', $order->id) }}" target="_blank">
                                    <i class="las la-download mr-2"></i> {{ translate('Download Shipping Label') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('shipping_label.print', $order->id) }}" target="_blank">
                                    <i class="las la-download mr-2"></i> {{ translate('Print Shipping Label') }}
                                </a>
                            @endcan
                            @if(auth()->user()->can('unpaid_order_payment_notification_send') && $order->payment_status == 'unpaid' && $unpaid_order_payment_notification->status == 1)
                            <a href="javascript:void(0);" onclick="unpaid_order_payment_notification('{{ $order->id }}');" class="dropdown-item text-warnning" title="{{ translate('Unpaid Order Payment Notification') }}">
                                <i class="las la-bell mr-2"></i> {{ translate('Payment Notification') }}
                            </a>
                            @endif
                            @can('product_delete')
                            <a href="javascript:void(0);" onclick="singleDelete('{{ $order->id }}');" class="dropdown-item text-danger">
                                <i class="las la-trash mr-2"></i> {{translate('Delete')}}
                            </a>
                            @endcan
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <div class="w-100">
                        <h5 class="fs-16 fw-bold text-gray">{{ translate('No Orders found!') }}</h5>
                        <i class="las la-frown fs-48 text-soft-white"></i>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div class="aiz-pagination mt-3" id="pagination">
        {{ $orders->links() }}
    </div>
    @endif
</div>