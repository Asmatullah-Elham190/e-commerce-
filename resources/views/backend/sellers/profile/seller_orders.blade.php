<h5 class=" mb-0 fw-semibold mt-2">{{translate('Orders')}}</h5>
<div class=" mt-2">
    <form class="" id="sort_orders" action="" method="GET">
        <div>
            <table class="table aiz-table inv-table-2 mb-0">
                <thead>
                    <tr>
                        <th class="place-th-checkbox">
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>

                        <th>{{translate('Order ID')}}</th>
                        <th>{{translate('Order Date')}}</th>
                        <th data-breakpoints="sm">{{translate('Amount')}}</th>
                        <th data-breakpoints="md">{{translate('Due')}}</th>
                        <th data-breakpoints="lg">{{translate('Status')}}</th>
                        <th class="text-right">{{translate('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order_id)
                    @php
                    $order = \App\Models\Order::find($order_id->id);
                    @endphp
                    @if ($order != null)

                    <tr class="row-item" data-id="{{ $order->id }}">
                        <td>
                            <div class="form-group d-inline-block mt-2">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-one" name="id[]" value="{{ $order->id }}">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </td>
                        <td>

                            <a class="font-weight-bold text-primary" href="{{route('seller_orders.show', encrypt($order->id))}}">{{ $order->code }}</a>
                        </td>
                        <td>
                            <b>{{ date('d M, Y', $order->date) }}</b>
                        </td>
                        <td>
                            <b> {{ single_price($order->grand_total) }}</b>
                        </td>
                        <td>
                            @if ($order->payment_status == 'unpaid')
                            <b> {{ single_price($order->grand_total) }}</b>
                            @else
                            <b> {{ single_price(0.00) }}</b>
                            @endif
                        </td>
                        <td>
                            @if ($order->delivery_status == 'delivered')
                            <span class="status-delivered">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
                            @elseif ($order->delivery_status == 'cancelled')
                            <span class="status-cancelled">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
                            @elseif ($order->delivery_status == 'pending')
                            <span class="status-pending">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
                            @else
                            <span class="status-processing">{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end"> <!-- Flex to push button to right -->
                                <div class="dropdown">
                                    <button type="button"
                                        class="btn p-0 border-0 bg-transparent"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        style="box-shadow: none;">
                                        <i class="las la-ellipsis-v" style="font-size: 1.5rem; color: #8c9196ff;"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                        @can('view_order_details')
                                        @php
                                            $order_detail_route = route('orders.show', encrypt($order->id));
                                            if (Route::currentRouteName() == 'seller_orders.index') {
                                                $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                            } elseif (Route::currentRouteName() == 'pick_up_point.index') {
                                                $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                            }
                                            if (Route::currentRouteName() == 'inhouse_orders.index') {
                                                $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                            }
                                        @endphp
                                        <a href="{{ $order_detail_route }}" class="dropdown-item fs-13">
                                            {{ translate('View') }}
                                        </a>
                                        @endcan
                                        <a href="{{ route('invoice.download', $order->id) }}" class="dropdown-item fs-13">
                                            {{ translate('Download Invoice') }}
                                        </a>

                                        <a href="javascript:void(0);" onclick="printInvoice({{ $order->id }})" class="dropdown-item fs-13">
                                            {{ translate('Print') }}
                                        </a>
                                        @if(auth()->user()->can('unpaid_order_payment_notification_send') && $order->payment_status == 'unpaid' && $unpaid_order_payment_notification->status == 1)
                                        <a href="javascript:void();" class="dropdown-item confirm-delete fs-13"
                                            onclick="unpaid_order_payment_notification('{{ $order->id }}');">
                                            {{ translate('Payment Notification') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif

                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination inv-pagination mt-4">
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>