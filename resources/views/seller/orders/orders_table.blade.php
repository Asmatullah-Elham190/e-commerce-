<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>

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
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Order Code') }}</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">{{ translate('Products') }}</th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Customer') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Seller') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Amount') }}</th>
                <th class="hide-xl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Delivery Status') }}</th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Payment method') }}</th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Payment Status') }}</th>
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

                    <div class="form-group d-inline-block">
                        <label class="aiz-checkbox">
                            <input type="checkbox" class="check-one" name="id[]" value="{{ $order->id }}">
                            <span class="aiz-square-check mt-1"></span>
                        </label>
                    </div>
                </td>

                <td data-label="Order-Code" class="align-middle">
                    <a class="text-blue fw-600" href="{{ route('seller.orders.show', encrypt($order->id)) }}">{{ $order->code }}</a>
                    @if ($order->shipping_method == 'shiprocket')
                    <br><span class="fw-bold">{{ translate('Shiprocket Order Id') }}:</span> {{ $order->shiprocket_order_id }}
                    @endif
                    @if ($order->shipping_method == 'steadfast')
                    <br><span class="fw-bold">{{ translate('Steadfast Consignment Id') }}:</span> {{ $order->steadfast_consignment_id }}
                    @endif
                    @if ($order->shipping_method == 'pathao')
                    <br><span class="fw-bold">{{ translate('Pathao Consignment Id') }}:</span> {{ $order->pathao_consignment_id }}
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
                            'facebook' => 'badge-info',
                            'whatsapp' => 'badge-success',
                            default    => 'badge-danger',
                        };
                    @endphp

                    <span class="badge badge-inline {{ $badgeClass }}">{{ ucfirst($order->order_source) }}</span>
                    @endif
                </td>

                <td class="align-middle" data-label="OrderCount">
                    {{ count($order->orderDetails) }}
                </td>

                <td class="hide-xs align-middle" data-label="Customer">
                    <span class="fs-12 fw-200 text-secondary d-block pt-1">
                        @if ($order->user != null)
                        {{ $order->user->name }}
                        @else
                        Guest ({{ $order->guest_id }})
                        @endif
                    </span>
                </td>

                <td class="hide-sm align-middle" data-label="Owner">
                    @php $shop = optional($order->shop); @endphp
                    <a href="{{ $shop->id ? route('sellers.profile', encrypt($shop->id)) : '#' }}" class="fs-12 fs-md-14 fw-700 d-block text-blue">
                         {{ Str::limit($shop->name, 20)?? '' }}
                    </a>
                </td>

                <td class="hide-md align-middle" data-label="Price Details">
                    <div class="border-width-3 border-left border-blue px-2 py-0 mb-1">
                        <p class="fs-16 fw-700 m-0">{{ single_price($order->grand_total) }}</p>
                    </div>
                </td>

                <td class="hide-xl align-middle" data-label="Delivery Status">
                    <p class="fs-16 fw-700 m-0 @if( $order->delivery_status == 'delivered' ) text-success @endif">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</p>

                    @if ($order->shipping_method == 'shiprocket')
                    <span class="fw-bold pt-10px">{{ translate('Shiprocket Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->shiprocket_status))) }}
                    @elseif ($order->shipping_method == 'steadfast')
                    <span class="fw-bold  pt-10px ">{{ translate('Steadfast Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->steadfast_status))) }}
                    @elseif ($order->shipping_method == 'pathao')
                    <span class="fw-bold pt-10px ">{{ translate('Pathao Status') }}:</span> {{ ucfirst(translate(str_replace('_', ' ', $order->pathao_status))) }}
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
                            @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                            <a class="dropdown-item" href="{{ route('seller.invoice.thermal_printer', $order->id) }}" target="_blank">
                                <i class="las la-print mr-2"></i> {{translate('Print')}}
                            </a>
                            @endif
                            <a href="{{ route('seller.orders.show', encrypt($order->id)) }}" class="dropdown-item">
                                <i class="las la-eye mr-2"></i> {{translate('View Order')}}
                            </a>
                            <a class="dropdown-item" href="{{ route('seller.invoice.download', $order->id) }}">
                                <i class="las la-download mr-2"></i> {{ translate('Download') }}
                            </a>
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