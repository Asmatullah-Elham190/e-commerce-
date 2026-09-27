@forelse($orders as $order)
        <div class="d-flex flex-wrap flex-md-nowrap justify-content-between align-items-center mb-2">
            <div class="flex-grow-1">
                <p class="fs-16 fw-700 deep-blue mb-0 mt-2">
                    <a class="deep-blue d-block d-md-inline" href="{{ route('purchase_history.details', encrypt($order->id)) }}">
                        {{ translate('Order ID') }} - {{ $order->code }}
                    </a>
                    <span class="fs-11 fw-400 px-2 rounded-pill btn-on-the-way mr-1">
                        {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                    </span>
                    @if ($order->payment_status == 'paid')
                        <span class="fs-11 fw-400 px-2 rounded-pill btn-paid">
                          {{ translate('Paid') }}
                        </span>
                    @else
                        <span class="fs-11 fw-400 px-2 rounded-pill btn-unpaid">
                            {{ translate('Unpaid') }}
                        </span>
                    @endif
                </p>
                <span class="text-muted d-block d-md-none fs-12 py-2 py-md-0">{{ translate('Date') }}:
                  {{ date('d-m-Y', $order->date) }}
                </span>
            </div>

            <!-- Mobile-only buttons -->
            <div class="d-flex gap-2 d-md-none">
                <a type="button" href="{{ route('re_order', encrypt($order->id)) }}"
                  class="btn btn-sm border  rounded px-4 py-1 text-muted reorder-btn">
                  {{ translate('Reorder') }}
                </a>

                <div class="dropdown">
                    <button type="button"
                        class="btn btn-sm dropdown-toggle text-white px-4 py-1 rounded btn-options ml-2"
                        data-toggle="dropdown">
                        {{ translate('Options') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item text-secondary dropdown-bg-hover"
                          href="{{ route('purchase_history.details', encrypt($order->id)) }}">
                          <i class="las la-eye mr-2"></i>
                          {{ translate('View') }}
                        </a>
                        <a class="dropdown-item text-secondary dropdown-bg-hover"
                            href="{{ route('invoice.download', $order->id) }}">
                            <i class="las la-download mr-2">
                            </i>{{ translate('Invoice') }}
                        </a>
                        @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                            <a href="javascript:void(0)"
                                class="dropdown-item text-secondary dropdown-bg-hover confirm-delete"
                                data-href="{{ route('purchase_history.destroy', $order->id) }}">
                                <i class="las la-trash mr-2"></i> 
                                {{ translate('Cancel') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop-only buttons (original position) -->
        <div class="row align-items-center mb-2 d-none d-md-flex">
            <div class="col-md-6">
                <div class="row fs-12">
                    <div class="col-auto w-200px">
                        <span
                          class="font-weight-bold light-blue">{{ get_shop_by_user_id($order->seller_id)->name ?? 'Inhouse Products' }}</span>
                    </div>
                    <div class="col">
                       <span class="text-muted">{{ translate('Date') }}: {{ date('d-m-Y', $order->date) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <a type="button" class="btn btn-sm border rounded px-4 py-1 text-muted reorder-btn"
                    href="{{ route('re_order', encrypt($order->id)) }}">
                    {{ translate('Reorder') }}
                </a>

                <div class="d-inline-block dropdown ml-1">
                    <button type="button" class="btn btn-sm dropdown-toggle text-white px-4 py-1 rounded btn-options"
                        data-toggle="dropdown">
                        {{ translate('Options') }}
                    </button>

                    <div class="dropdown-menu dropdown-menu-right ">
                        <a class="dropdown-item text-secondary dropdown-bg-hover"
                            href="{{ route('purchase_history.details', encrypt($order->id)) }}"><i
                            class="las la-eye mr-2"></i>{{ translate('View') }}
                        </a>
                        <a class="dropdown-item text-secondary dropdown-bg-hover"
                            href="{{ route('invoice.download', $order->id) }}"><i
                            class="las la-download mr-2"></i>{{ translate('Invoice') }}
                        </a>
                        @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                            <a href="javascript:void(0)"
                                class="dropdown-item text-secondary dropdown-bg-hover confirm-delete"
                                data-href="{{ route('purchase_history.destroy', $order->id) }}"><i
                                    class="las la-trash mr-2"></i> {{ translate('Cancel') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-dashed">

        <!-- image,product name,price,on the way,paid button row -->
        <!-- image,product name,price,on the way,paid button row -->


        <div class="row align-items-center">
            <div class="col-md-12">
                @foreach ($order->orderDetails as $orderDetail)
                    @if (!$loop->first)
                        <hr class="hr-split w-100">
                    @endif

                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">

                            @if ($orderDetail->product)
                            <div class="w-50px h-50px border border-gray-400 rounded-1 overflow-hidden mr-3 flex-shrink-0 d-flex align-items-center justify-content-center">
                                <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                    class="img-fluid img-fit  product-history-img">
                            </div>
                                

                                <div class="text-wrap">
                                    <div class="font-weight-semibold fs-14 product-name-color text-truncate-2"
                                        title="{{ $orderDetail->product->getTranslation('name') }}">
                                        {{ $orderDetail->product->getTranslation('name') }}
                                    </div>
  
                                    <div class="text-muted small mb-2 mobile-title-shift">
                                        {{ $orderDetail->variation }}
                                    </div>
                                </div>
                            @else
                                <div class="text-wrap">
                                    <div class="font-weight-semibold fs-14 text-danger">
                                        {{ translate('Product deleted') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $orderDetail->variation }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-3 text-right">
                            <div class="font-weight-bold">{{ single_price($orderDetail->price) }}</div>
                            <div class="text-muted small">{{ translate('Qty') }} {{ $orderDetail->quantity }}</div>
                        </div>

                        <!-- Desktop-only buttons in right column -->
                        <div class="col-md-3 text-right d-md-block align-self-start">
                            <div class="font-weight-bold">
                                @if ($orderDetail->delivery_status == 'delivered')
                                    @if(get_setting('enable_ratings_and_review_section') == 1)
                                        @php
                                            $hasReview = \App\Models\Review::where('product_id', $orderDetail->product_id)
                                                ->where('user_id', auth()->id())
                                                ->exists();
                                        @endphp

                                        @if ($hasReview)
                                            <a href="javascript:void(0);"
                                                onclick="openReviewOffcanvas('{{ $orderDetail->product_id }}', '{{ $order->id }}')"
                                                class="btn btn-on-the-way btn-sm w-120px rounded-pill">
                                                {{ translate('Reviewed') }}
                                            </a>
                                        @else
                                            <a href="javascript:void(0);"
                                                onclick="openReviewOffcanvas('{{ $orderDetail->product_id }}', '{{ $order->id }}')"
                                                class="btn btn-orange btn-sm w-120px rounded-pill py-1 text-white">
                                                {{ translate('Review') }}
                                            </a>
                                        @endif
                                    @else
                                        <span class="btn btn-success btn-sm w-120px rounded-pill py-1 text-white">
                                            {{ translate('Delivered') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-danger">{{ translate('Not Delivered') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <div>
                  <hr>
                </div>

            @empty

            <div class="text-center py-5">
                <img src="{{ asset('assets/img/empty.svg') }}" width="120" class="mb-3">
                <h6 class="text-muted">{{ translate('No orders found') }}</h6>
            </div>
        </div>
@endforelse

@if ($orders->count() > 0)
    <div class="aiz-pagination mt-4 mb-4" id="pagination">
        {{ $orders->links() }}
    </div>
@endif

