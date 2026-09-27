@extends('frontend.layouts.user_panel')

@section('panel_content')
    <!-- Order id -->
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-20 fw-700 text-dark">{{ translate('Order id') }}: {{ $order->code }}</h1>
            </div>
        </div>
    </div>

    @php
        $first_order = $order->orderDetails->first();
        $gstin = get_seller_gstin($order);
    @endphp

    <!-- Order Summary -->
    <div class="card rounded-0 shadow-none border mb-4">
        <div class="card-header border-bottom-0">
            <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Summary') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order Code') }}:</td>
                            <td>{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Customer') }}:</td>
                            <td>{{ json_decode($order->shipping_address)->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Email') }}:</td>
                            @if ($order->user_id != null)
                                <td>{{ $order->user->email }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping address') }}:</td>
                            <td>{{ json_decode($order->shipping_address)->address }},
                                {{ json_decode($order->shipping_address)->city }},
                                @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif
                                {{ json_decode($order->shipping_address)->postal_code }},
                                {{ json_decode($order->shipping_address)->country }}
                            </td>
                        </tr>
                        @if ( json_decode($order->billing_address) != null)
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Billing address') }}:</td>
                                <td>{{ json_decode($order->billing_address)->address }},
                                    {{ json_decode($order->billing_address)->city }},
                                    @if(isset(json_decode($order->billing_address)->state)) {{ json_decode($order->billing_address)->state }} - @endif
                                    {{ json_decode($order->billing_address)->postal_code }},
                                    {{ json_decode($order->billing_address)->country }}
                                </td>
                            </tr>
                        @endif
                        @if ($gstin != null && is_numeric($first_order->gst_amount))
                            <tr>
                                <td class="w-50 fw-600">{{ translate('GSTIN') }}:</td>
                                <td>
                                    {{$gstin}}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="w-50 fw-600">{{ translate('Seller Address') }}:</td>
                            <td>{{ get_seller_address($order) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order date') }}:</td>
                            <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Order status') }}:</td>
                            <td>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Total order amount') }}:</td>
                            <td>{{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Shipping method') }}:</td>
                            <td>{{ translate('Flat shipping rate') }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">{{ translate('Payment method') }}:</td>
                            <td>{{ ucfirst(translate(str_replace('_', ' ', $order->payment_type))) }}</td>
                        </tr>
                        <tr>
                            <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                            <td class="">{{ $order->additional_info }}</td>
                        </tr>
                        @if ($order->tracking_code)
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Tracking code') }}:</td>
                                <td>{{ $order->tracking_code }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="row gutters-16">
        <div class="col-md-9">
            <div class="card rounded-0 shadow-none border mt-2 mb-4">
                <div class="card-header border-bottom-0">
                    <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Details') }}</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="aiz-table table">
                        <thead class="text-gray fs-12">
                            <tr>
                                <th class="pl-0">#</th>
                                <th width="30%">{{ translate('Product') }}</th>
                                <th data-breakpoints="md">{{ translate('Variation') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                <th data-breakpoints="md">{{ translate('Delivery Type') }}</th>
                                <th>{{ translate('Price') }}</th>
                                @if (addon_is_activated('refund_request'))
                                    <th data-breakpoints="md">{{ translate('Refund Status') }}</th>
                                @endif
                                @if(get_setting('enable_ratings_and_review_section') == 1)
                                    <th data-breakpoints="md" class="text-right pr-0">{{ translate('Review') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="fs-14">
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td class="pl-0">{{ sprintf('%02d', $key+1) }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                        @elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $orderDetail->variation }}
                                    </td>
                                    <td>
                                        {{ $orderDetail->quantity }}
                                    </td>
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->name }} ({{ translate('Pickip Point') }})
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
                                    <td class="fw-700">{{ single_price($orderDetail->price) }}</td>
                                    @if (addon_is_activated('refund_request'))
                                        @php
                                            $no_of_max_day = $orderDetail->refund_days;

                                            $last_refund_date = null;
                                            if ($order->delivered_date && $no_of_max_day > 0) {
                                                $last_refund_date = Carbon\Carbon::parse($order->delivered_date)->addDays($no_of_max_day);
                                            }
                                            
                                            $today_date = Carbon\Carbon::now();
                                            
                                        @endphp
                                        <td>
                                            @if (
                                                    $orderDetail->product != null &&
                                                    $orderDetail->refund_request == null &&
                                                    $last_refund_date &&
                                                    $today_date <= $last_refund_date &&
                                                    $order->payment_status == 'paid' &&
                                                    $order->delivery_status == 'delivered'
                                                )

                                                <a href="{{ route('refund_request_send_page', $orderDetail->id) }}"
                                                    class="btn btn-outline-dark btn-sm rounded-0">
                                                    {{ translate('Send') }}
                                                </a>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0)
                                                <b class="text-info">{{ translate('Pending') }}</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 2)
                                                @if($orderDetail->refund_request->dispute_refund_status == 1)
                                                    <b class="text-info">{{ translate('Dispute Pending') }}</b>
                                                @elseif($orderDetail->refund_request->dispute_refund_status == 2)
                                                    <b class="text-success">{{ translate('Dispute Approved') }}</b>
                                                @elseif($orderDetail->refund_request->dispute_refund_status == 3)
                                                    <b class="text-danger">{{ translate('Dispute Rejected') }}</b>
                                                @else
                                                    <b class="text-danger">{{ translate('Rejected') }}</b>
                                                @endif
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1)
                                                <b class="text-success">{{ translate('Approved') }}</b>
                                            @elseif ($orderDetail->product != null && $orderDetail->refund_days != 0)
                                                <b>{{ translate('N/A') }}</b>
                                            @else
                                                <b>{{ translate('Non-refundable') }}</b>
                                            @endif
                                        </td>
                                    @endif
                                    @if(get_setting('enable_ratings_and_review_section') == 1)
                                    <td class="text-xl-right pr-0">
                                        @if ($orderDetail->delivery_status == 'delivered')
                                            @php
                                                $hasReview = \App\Models\Review::where('product_id', $orderDetail->product_id)
                                                    ->where('user_id', auth()->id())
                                                    ->exists();
                                            @endphp
                                            @if ($hasReview)
                                                <a href="javascript:void(0);"
                                                    onclick="openReviewOffcanvas('{{ $orderDetail->product_id }}', '{{ $order->id }}')"
                                                    class="btn btn-sm rounded-0 bg-light px-2">
                                                    {{ translate('Reviewed') }}
                                                </a>
                                            @else
                                                <a href="javascript:void(0);"
                                                    onclick="openReviewOffcanvas('{{ $orderDetail->product_id }}', '{{ $order->id }}')"
                                                    class="btn btn-orange btn-sm rounded-0">
                                                    {{ translate('Review') }}
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-danger">{{ translate('Not Delivered Yet') }}</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Ammount -->
        <div class="col-md-3">
            <div class="card rounded-0 shadow-none border mt-2">
                <div class="card-header border-bottom-0">
                    <b class="fs-16 fw-700 text-dark">{{ translate('Order Ammount') }}</b>
                </div>
                <div class="card-body pb-0">
                    <table class="table-borderless table">
                        <tbody>
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Subtotal') }}</td>
                                <td class="text-right">
                                    <span class="strong-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Shipping') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                </td>
                            </tr>
                            @if(is_numeric($first_order->gst_amount))
                            
                            @if(same_state_shipping($order))
                             <tr>
                                <td class="w-50 fw-600">{{ translate('CGST') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('gst_amount')/2) }}</span>
                                </td>
                            </tr>
                             <tr>
                                <td class="w-50 fw-600">{{ translate('SGST') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('gst_amount')/2) }}</span>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td class="w-50 fw-600">{{ translate('IGST') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('gst_amount')) }}</span>
                                </td>
                            </tr>
                            @endif

                            @else
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Tax') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Coupon') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->coupon_discount) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600">{{ translate('Total') }}</td>
                                <td class="text-right">
                                    <strong>{{ single_price($order->grand_total) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($order->payment_status == 'unpaid' && $order->delivery_status == 'pending' && $order->manual_payment == 0)
                <button
                    @if(addon_is_activated('offline_payment'))
                        onclick="select_payment_type({{ $order->id }})"
                    @else
                        onclick="online_payment({{ $order->id }})"
                    @endif
                    class="btn btn-block btn-primary">
                    {{ translate('Make Payment') }}
                </button>
            @endif
        </div>
    </div>
@endsection

@section('modal')
    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Select Payment Type Modal -->
    <div class="modal fade" id="payment_type_select_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Select Payment Type') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
                    <div class="row">
                        <div class="col-md-2">
                            <label>{{ translate('Payment Type') }}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control aiz-selectpicker rounded-0" onchange="payment_modal(this.value)"
                                    data-minimum-results-for-search="Infinity">
                                    <option value="">{{ translate('Select One') }}</option>
                                    <option value="online">{{ translate('Online payment') }}</option>
                                    <option value="offline">{{ translate('Offline payment') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1"
                            id="payment_select_type_modal_cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Online payment Modal -->
    <div class="modal fade" id="online_payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Make Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body gry-bg px-3 pt-3" style="overflow-y: inherit;">
                    <form class="" action="{{ route('order.re_payment') }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{ translate('Payment Method') }}</label>
                            </div>
                            <div class="col-md-10">
                            <div class="mb-3">
                                <select class="form-control selectpicker rounded-0" data-live-search="true" name="payment_option" required>
                                    @include('partials.online_payment_options')
                                    @if (get_setting('wallet_system') == 1 && (auth()->user()->balance >= $order->grand_total))
                                        <option value="wallet">{{ translate('Wallet') }}</option>
                                    @endif
                                </select>
                            </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-secondary rounded-0 transition-3d-hover mr-1"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1">{{ translate('Confirm') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- offline payment Modal -->
    <div class="modal fade" id="offline_order_re_payment_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Order Payment') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_order_re_payment_modal_body"></div>
            </div>
        </div>
    </div>

    <div class="modal fade uploadModal" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

                <!-- Header -->
                <div class="modal-header bg-dark text-white py-2 rounded-0">
                    <h6 class="modal-title mb-0">Image Preview</h6>
                    <button type="button" class="close text-white fs-3 border-0 bg-transparent" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center bg-light p-3">
                    <img id="modalImage"
                        src=""
                        class="img-fluid rounded shadow-sm"
                        style="max-height: 80vh;">
                </div>

            </div>
        </div>
    </div>

@endsection


@section('script')
<script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <script type="text/javascript">

        function select_payment_type(id) {
            $('#payment_type_select_modal').modal('show');
        }

        function payment_modal(type) {
            if (type == 'online') {
                $("#payment_select_type_modal_cancel").click();
                online_payment();
            } else if (type == 'offline') {
                $("#payment_select_type_modal_cancel").click();
                $.post('{{ route('offline_order_re_payment_modal') }}', {
                    _token: '{{ csrf_token() }}',
                    order_id: '{{ $order->id }}'
                }, function(data) {
                    $('#offline_order_re_payment_modal_body').html(data);
                    $('#offline_order_re_payment_modal').modal('show');
                });
            }
        }

        function online_payment() {
            $('input[name=customer_package_id]').val();
            $('#online_payment_modal').modal('show');
        }

        function openReviewOffcanvas(product_id, order_id) {
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');
            
            if (rightOffcanvas) rightOffcanvas.classList.add('active');
            if (overlay) overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            
            if (rightOffcanvas) {
                rightOffcanvas.innerHTML = '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';
            }
            
            $.ajax({
                type: "POST",
                url: "{{ route('product_review_modal') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: product_id,
                    order_id: order_id
                },
                success: function(html) {
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = html;

                        if (typeof AIZ !== 'undefined' && AIZ.extra && AIZ.extra.inputRating) {
                            AIZ.extra.inputRating();
                        }

                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.aizUploader) {
                            AIZ.plugins.aizUploader();
                        }
                    }
                },
                error: function() {
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = '<div class="p-4 text-center text-danger">{{ translate("Failed to load review form") }}</div>';
                    }
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        }

        $(document).on('click', '#review-store', function(e) {
            e.preventDefault();
            
            const btn = $(this);
            const formData = new FormData();
            
            const product_id = $('#modal_product_id').val(); 
            const order_id = $('#modal_order_id').val();
            const rating = $('input[name="rating"]:checked').val();
            const comment = $('textarea[name="comment"]').val();

            if (!product_id) {
                AIZ.plugins.notify('danger', 'Product ID is missing!');
                return;
            }

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('product_id', product_id);
            formData.append('order_id', order_id);
            formData.append('rating', rating);
            formData.append('comment', comment);
            
            const input = document.querySelector('input[name="photos[]"]');
            if (input && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    formData.append('photos[]', input.files[i]);
                }
            }
            
            if (!$('input[name="rating"]:checked').val()) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a rating") }}');
                return;
            }
            if (!$('textarea[name="comment"]').val()) {
                AIZ.plugins.notify('warning', '{{ translate("Please write a comment") }}');
                return;
            }
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span> {{ translate("Submitting...") }}');
            
            // AJAX Submit
            $.ajax({
                url: "{{ route('reviews.store') }}", 
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    AIZ.plugins.notify('success', '{{ translate("Review submitted successfully") }}');
                    
                    if (typeof closeOffcanvas === 'function') {
                        closeOffcanvas();
                    } else {
                        const rightOffcanvas = document.getElementById('rightOffcanvas');
                        const overlay = document.getElementById('rightOffcanvasOverlay');
                        if (rightOffcanvas) rightOffcanvas.classList.remove('active');
                        if (overlay) overlay.classList.remove('active');
                        document.body.classList.remove('body-no-scroll');
                    }
                    
                    setTimeout(() => {
                        if (typeof getOrderData === 'function') {
                            getOrderData(currentTab);
                        } else {
                            location.reload();
                        }
                    }, 1000);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('{{ translate("Confirm") }}');
                    const errorMsg = xhr.responseJSON?.message || '{{ translate("Something went wrong") }}';
                    AIZ.plugins.notify('danger', errorMsg);
                }
            });
        });

        function openReviewImageModal(src) {
            document.getElementById('modalImage').src = src;
            var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
            myModal.show();
        }
    </script>
@endsection
