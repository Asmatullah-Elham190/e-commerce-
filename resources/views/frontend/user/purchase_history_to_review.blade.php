@php
    $filteredOrders = $orders->filter(function($order) use ($reviewedProducts){
        return $order->orderDetails->filter(function($detail) use ($reviewedProducts){
            return $detail->delivery_status == 'delivered'
                && !in_array($detail->product_id, $reviewedProducts);
        })->count() > 0;
    });
@endphp

@if($filteredOrders->count() > 0)

    @foreach($filteredOrders as $order)

        <div class="mb-4">

            <div class="row align-items-center mb-3">
                <div class="col-md-12">

                    @foreach($order->orderDetails as $orderDetail)

                        @if($orderDetail->delivery_status == 'delivered' && !in_array($orderDetail->product_id, $reviewedProducts))
                            
                           

                            <div class="row">
                                <div class="col-md-7 d-flex align-items-center">

                                    <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                         class="img-fluid mr-3 product-history-img">

                                    <div class="w-300px text-wrap">
                                        <div class="font-weight-semibold fs-14 product-name-color mobile-title-shift text-truncate-2"
                                             title="{{ $orderDetail->product->getTranslation('name') }}">
                                            {{ $orderDetail->product->getTranslation('name') }}
                                        </div>

                                        <div class="text-muted small mb-2 mobile-title-shift">
                                            {{ $orderDetail->variation }}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="font-weight-bold">
                                        {{ single_price($orderDetail->price) }}
                                    </div>
                                    <div class="text-muted small">
                                        Qty {{ $orderDetail->quantity }}
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <a href="javascript:void(0);"
                                       onclick="openReviewOffcanvas('{{ $orderDetail->product_id }}', '{{ $order->id }}')"
                                       class="btn btn-orange btn-sm w-120px rounded-pill py-1 text-white">
                                        {{ translate('Review') }}
                                    </a>
                                </div>
                            </div>

                            <hr>

                        @endif

                    @endforeach

                </div>
            </div>

        </div>

    @endforeach

@else

    <div class="text-center py-5">
        <img src="{{ asset('assets/img/empty.svg') }}" width="120" class="mb-3">
        <h6 class="text-muted">{{ translate('No items to review') }}</h6>
    </div>

@endif