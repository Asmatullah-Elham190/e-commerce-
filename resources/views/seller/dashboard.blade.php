@extends('seller.layouts.app')

@section('panel_content')
    @if(addon_is_activated('gst_system') && !auth()->user()->shop->gst_verification)
        <div class="aiz-titlebar mt-2 mb-4">
            <div class="row align-items-center">

                <div class="col-md-12 alert alert-info text-center ">
                    <p class="font-weight-bold text-danger m-0">
                        {{ translate('GST is being enabled on our platform. Please upload and complete the required documents from ') }}
                        <a class="text-info" href="{{ route('seller.shop.index') }}">{{ translate('here') }}</a>.
                        {{translate('Products will not be published unless the form is completed and HSN codes and GST values are properly assigned.')}}
                    </p>
                </div>
            </div>
        </div>
    @endif
    @php $authUser = auth()->user(); @endphp
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4" style="background-color: {{ Auth::user()->shop->navbar_bg_color ?? '#5B346C' }}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="small mb-0" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14">{{ translate('Total Sales') }}</span>
                            </p>
                            <h3 class="mb-0 fs-30" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                @php
                                    $orderDetails = \App\Models\OrderDetail::where('seller_id', $authUser->id)->get();
                                    $total = 0;
                                    foreach ($orderDetails as $key => $orderDetail) {
                                        if ($orderDetail->order != null && $orderDetail->order->payment_status == 'paid') {
                                            $total += $orderDetail->price;
                                        }
                                    }
                                @endphp
                                {{ single_price($total) }}
                            </h3>
                        </div>
                        <div class="col-auto text-right" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <img src="{{ static_asset('assets/img/sp-total-sale.svg') }}" alt="Total Sale" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <a href="{{ route('seller.orders.index') }}" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="d-flex align-items-center">
                                <p class="fs-12 my-1 ml-1"> {{ translate('Top Monthly Earning (All time) ') }}
                                    {{ single_price($previous_month_sold_amount) }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4" style="background-color: {{ Auth::user()->shop->navbar_bg_color ?? '#5B346C' }}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <p class="small mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14 ">{{ translate('Products') }}</span>
                            </p>
                            <h3 class="mb-0 fs-30">
                                {{ \App\Models\Product::where('user_id', $authUser->id)->count() }}
                            </h3>
                        </div>
                        <div class="col-auto text-right">
                            <img src="{{ static_asset('assets/img/sp-add-new-product.svg') }}" alt="Products" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <a href="{{ route('seller.products.create') }}" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="d-flex align-items-center">
                                <i class="las la-plus la-1x"></i>
                                <p class="fs-12 my-1 ml-1">{{ translate('Add New Product') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4" style="background-color: {{ Auth::user()->shop->navbar_bg_color ?? '#5B346C' }}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <p class="small mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14">{{ translate('Total Orders') }}</span>
                            </p>
                            <h3 class="mb-0 fs-30">
                                {{ \App\Models\Order::where('seller_id', $authUser->id)->where('delivery_status', 'delivered')->count() }}
                            </h3>
                        </div>
                        <div class="col-auto text-right" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <img src="{{ static_asset('assets/img/sp-total-order.svg') }}" alt="Total Order" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <a href="{{ route('seller.orders.index') }}" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="d-flex align-items-center">
                                <p class="fs-12 my-1 ml-1">{{ translate('View All Order') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xxl-3">
            <div class="card shadow-none mb-4" style="background-color: {{ Auth::user()->shop->navbar_bg_color ?? '#5B346C' }}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <p class="small mb-0">
                                <span class="fe fe-arrow-down fe-12"></span>
                                <span class="fs-14">{{ translate('Rating') }}</span>
                            </p>
                            <h3 class="mb-0 fs-30">
                                {{ $authUser->shop?->rating }}
                            </h3>
                        </div>
                        <div class="col-auto text-right" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <img src="{{ static_asset('assets/img/sp-rating.svg') }}" alt="Rating" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <div class="d-flex align-items-center pt-1">
                            <p class="fs-12 my-1 ml-1">
                                {{ translate('Followers') . ' ' . $authUser->shop?->followers()->count() }}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <p class="fs-12 my-1 ml-1">
                                {{ translate('Custom Followers') . ' ' . $authUser->shop?->custom_followers }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
            <div class="card shadow-none mb-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="card-title text-primary fs-16 fw-600">
                            {{ translate('Sales State') }}
                        </div>
                        <span style="color: #B5B5C3; font-size: 12px; font-weight: 500;">
                            {{ now()->subDays(6)->format('d M') }} - {{ now()->format('d M, Y') }}
                        </span>
                    </div>
                    <canvas id="graph-1" class="w-100" height="150"></canvas>
                </div>
            </div>
            <div class="card shadow-none bg-light mb-0  sp-sales-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2 pb-2" style="gap: 16px;">
                        <div>
                            <div class="card-title text-primary fs-16 fw-bold mb-0">
                                {{ translate('Sales') }}
                            </div>
                            <p class="m-0 fs-13 fw-400 text-dark opacity-80">{{ translate('This Month') }}</p>
                        </div>
                        <p class="m-0">
                            {{ translate('Last Month') }}: {{ single_price($previous_month_sold_amount) }}
                        </p>
                    </div>
                    <h3 class="text-primary fw-600 fs-30 mt-3 mb-3">
                        {{ single_price($this_month_sold_amount) }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
            <div class="card shadow-none h-450px mb-0 h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <h3 class="card-title text-primary fs-16 fw-600 mb-1">{{ translate('Category wise Products') }}</h3>
                        <span class="fs-13 fw-400 text-dark opacity-70">{{ translate('On Enlisted Categories') }}</span>
                    </div>
                    <ul class="list-group">
                        @foreach (\App\Models\Category::all() as $key => $category)
                            @php
                                $cat_products = \App\Models\Product::where('user_id', $authUser->id)->where('category_id', $category->id)->count();
                            @endphp
                            @if ($cat_products > 0)
                                <li class="d-flex justify-content-between align-items-center my-2 text-primary fs-13">
                                    {{ $category->getTranslation('name') }}
                                    <span class="">
                                        {{ $cat_products }}
                                    </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
            <div class="card h-450px mb-0 h-100 py-20px">
                <div class="px-25px">
                    <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 4px;">
                        <div>
                            <h2 class="fs-16 fw-bold text-dark mb-1">{{ translate('Orders') }}</h2>
                            <span class="fs-13 fw-400 text-dark opacity-70">{{ translate('All Time') }}</span>
                        </div>

                        <div class="d-flex align-items-center bg-light rounded-pill px-3 py-2">
                            <span class="d-block w-10px h-10px bg-dark rounded-circle"></span>
                            <span class="fs-13 fw-500 text-dark pl-2 pr-3">{{ translate('Fulfilment Rate') }}</span>
                            <span class="fs-13 fw-bold text-dark">{{ $deliveredPercent }}%</span>
                        </div>
                    </div>
                </div>
                <div class="px-25px  d-flex flex-column mt-4 pt-1 c-scrollbar-light h-340px h-xl-280px h-xxl-340px"
                    style="gap: 24px; overflow-y: auto;">
                    <div class="d-flex align-items-end">
                        <img src="{{ static_asset('assets/img/pending-order.svg') }}" class="flex-shrink-0"
                            alt="Pending Order" />
                        <div class="ml-2 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Pending Order') }} </h3>
                                    <span class="fs-13 fw-400 text-secondary">({{ $pendingPercent }}%)</span>
                                </div>
                                <strong class="fs-13 fw-600 text-dark"> {{ $pendingOrder }}</strong>
                            </div>
                            <div class="progress mt-1 h-5px" style="">
                                <div class="progress-bar h-100 rounded-pill" role="progressbar"
                                    style="width: {{ $pendingPercent }}%; background-color: #F74396;" aria-valuenow="30"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end">
                        <img src="{{ static_asset('assets/img/confirmed-order.svg') }}" class="flex-shrink-0"
                            alt="Confirmed Order" />
                        <div class="ml-2 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Confirmed Order') }} </h3>
                                    <span class="fs-13 fw-400 text-secondary">({{ $confirmedPercent }}%)</span>
                                </div>
                                <strong class="fs-13 fw-600 text-dark"> {{ $confirmedOrder }}</strong>
                            </div>
                            <div class="progress mt-1 h-5px" style="">
                                <div class="progress-bar h-100 rounded-pill" role="progressbar"
                                    style="width: {{ $confirmedPercent }}%; background-color: #24BF85;" aria-valuenow="20"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end">
                        <img src="{{ static_asset('assets/img/order-processed.svg') }}" class="flex-shrink-0"
                            alt="Order Processed" />
                        <div class="ml-2 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Processed') }} </h3>
                                    <span class="fs-13 fw-400 text-secondary">({{ $processedPercent }}%)</span>
                                </div>
                                <strong class="fs-13 fw-600 text-dark"> {{ $processedOrder }}</strong>
                            </div>
                            <div class="progress mt-1 h-5px" style="">
                                <div class="progress-bar h-100 rounded-pill" role="progressbar"
                                    style="width: {{ $processedPercent }}%; background-color: #4E9FF4;" aria-valuenow="10"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end">
                        <img src="{{ static_asset('assets/img/order-shipped.svg') }}" class="flex-shrink-0"
                            alt="Order Shipped" />
                        <div class="ml-2 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Shipped') }} </h3>
                                    <span class="fs-13 fw-400 text-secondary">({{ $shippedPercent }}%)</span>
                                </div>
                                <strong class="fs-13 fw-600 text-dark"> {{ $shippedOrder }}</strong>
                            </div>
                            <div class="progress mt-1 h-5px" style="">
                                <div class="progress-bar h-100 rounded-pill" role="progressbar"
                                    style="width: {{ $shippedPercent }}%; background-color: #65D3E6;" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end">
                        <img src="{{ static_asset('assets/img/order-delivered.svg') }}" class="flex-shrink-0"
                            alt="Order Delivered" />
                        <div class="ml-2 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Delivered') }} </h3>
                                    <span class="fs-13 fw-400 text-secondary">({{ $deliveredPercent }}%)</span>
                                </div>
                                <strong class="fs-13 fw-600 text-dark"> {{ $deliveredOrder }}</strong>
                            </div>
                            <div class="progress mt-1 h-5px" style="">
                                <div class="progress-bar h-100 rounded-pill" role="progressbar"
                                    style="width: {{ $deliveredPercent }}%; background-color: #FFBB32;" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end">
                        <img src="{{ static_asset('assets/img/order-cancelled.svg') }}" class="flex-shrink-0"
                            alt="Order Cancelled" />
                        <div class="ml-2 flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Cancelled') }} </h3>
                                    <span class="fs-13 fw-400 text-secondary">({{ $cancelledPercent }}%)</span>
                                </div>
                                <strong class="fs-13 fw-600 text-dark"> {{ $cancelledOrder }}</strong>
                            </div>
                            <div class="progress mt-1 h-5px" style="">
                                <div class="progress-bar h-100 rounded-pill" role="progressbar"
                                    style="width: {{ $cancelledPercent }}%; background-color: #F76464;" aria-valuenow="40"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
            <div class="card shadow-none h-450px mb-0 h-100" style="border-color: #5B346C!important;">
                <div class="card-body px-0">
                    <div class="d-flex align-items-center justify-content-between px-25px">
                        <div class="card-title text-primary fs-16 fw-600 mb-0">{{ translate('Seller Hub') }}</div>
                        <a href="javascript:void(0);" class="js-notice-board-view-all text-blue fs-13 fw-500">{{ translate('View All') }}</a>
                    </div>

                    <ul class="nav nav-tabs sp-noticeboard-tabs border-0 d-flex align-items-center mt-3 px-25px" id="sp-noticeboard" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 px-0 fs-10 fw-400 text-dark opacity-70 hov-opacity-100 active has-transition" id="all-notice-tab" data-toggle="tab" href="#all-noitce" role="tab">{{ translate('All') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 px-0 fs-10 fw-400 text-dark opacity-70 hov-opacity-100 has-transition" id="notices-tab" data-toggle="tab" href="#notices" role="tab">
                                <span class="d-flex align-items-center position-relative">
                                    {{ translate('Notices') }}
                                    @if ($hasUnseenNotices)
                                        <span class="badge badge-sm badge-dot badge-circle badge-danger position-absolute" style="top: -2px; right: -6px; width: 4px; height: 4px;"></span>
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 px-0 fs-10 fw-400 text-dark opacity-70 hov-opacity-100 has-transition" id="requests-tab" data-toggle="tab" href="#requests" role="tab">{{ translate('Requests') }}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 px-0 fs-10 fw-400 text-dark opacity-70 hov-opacity-100 has-transition position-relative" id="promotions-tab" data-toggle="tab" href="#promotions" role="tab">
                                <span class="d-flex align-items-center position-relative">
                                    {{ translate('Promotions') }}
                                    @if ($hasUnseenPromotions)
                                        <span class="badge badge-sm badge-dot badge-circle badge-danger position-absolute" style="top: -2px; right: -6px; width: 4px; height: 4px;"></span>
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 px-0 fs-10 fw-400 text-dark opacity-70 hov-opacity-100 has-transition position-relative" id="messages-tab" data-toggle="tab" href="#messages" role="tab">
                                <span class="d-flex align-items-center position-relative">
                                    {{ translate('Messages') }}
                                    @if ($hasUnseenMessages)
                                        <span class="badge badge-sm badge-dot badge-circle badge-danger position-absolute" style="top: -2px; right: -6px; width: 4px; height: 4px;"></span>
                                    @endif
                                </span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3 pl-25px pr-15px" id="sp-noticeboard-tab-content">

                        {{-- All --}}
                        <div class="tab-pane fade show active" id="all-noitce" role="tabpanel" aria-labelledby="all-notice-tab">
                            <div class="d-flex flex-column pr-10px c-scrollbar-light" style="gap: 12px; max-height: 300px; overflow-y: auto;">
                                @forelse ($activities->take(8) as $activity)
                                    @if ($activity['type'] === 'notice')
                                        @php $notice = $activity['data']; @endphp
                                        <div class="rounded-1 p-2" style="background-color: {{ $notice->bg_color_hex }};">
                                            <span class="fs-13 fw-400">{{ $notice->message }}</span>
                                            @if ($notice->id === 1)
                                                <div>
                                                    <a href="{{ route('seller.shop.verify') }}" class="fs-14 fw-bold hov-text-blue has-transition">{{ translate('here.') }}</a>
                                                </div>
                                            @elseif ($notice->id === 2)
                                                <a href="{{ route('seller.shop.index') }}" class="fs-14 fw-bold hov-text-blue has-transition">{{ translate('this.') }}</a>
                                            @elseif ($notice->id === 3 && !empty($notice->expiry_date))
                                                <span class="fs-13 fw-400 text-danger"> {{ $notice->expiry_date }}</span>
                                            @endif
                                        </div>

                                    @elseif ($activity['type'] === 'promotion')
                                        @php $promotion = $activity['data']; @endphp
                                        <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                            <span class="fs-13 fw-400">
                                                {{ translate('Admin created a promotion offer for') }}
                                                @if ($promotion->flash_sale_id && $promotion->flashSale)
                                                    <a class="fs-13 fw-700 text-reset has-transition">{{ $promotion->flashSale->title }}</a>
                                                    <span class="fs-13 fw-400 text-dark">{{ translate('for') }}</span>
                                                    <a class="fs-13 fw-700 text-reset has-transition">{{ translate('FLASH DEALS') }}</a>
                                                @else
                                                    <a class="fs-13 fw-700 text-reset has-transition">{{ $promotion->promo_type_label }}</a>
                                                @endif
                                            </span>
                                        </div>

                                    @elseif ($activity['type'] === 'message')
                                        @php $conversation = $activity['data']; @endphp
                                        <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                            <span class="fs-13 fw-400">{{ translate('Admin sent a message:') }} {{ \Illuminate\Support\Str::limit($conversation->lastSellerMessage->message, 40) }}</span>
                                            <a href="javascript:void(0);" class="js-goto-messages fs-14 fw-bold hov-text-blue has-transition d-block">{{ translate('View') }}</a>
                                        </div>

                                    @elseif ($activity['type'] === 'request')
                                        @php $req = $activity['data']; $item = $req->item_label; @endphp
                                        <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                            <span class="fs-13 fw-400">
                                                @if ($item)
                                                    {{ translate('You requested to add a') }} <a class="fs-13 fw-700 text-reset has-transition">{{ translate($item['type']) }}</a> {{ translate('named') }} <a class="fs-13 fw-700 text-reset has-transition">{{ $item['value'] }}</a>.
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                @empty
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">{{ translate('No activity found') }}</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Notices --}}
                        <div class="tab-pane fade" id="notices" role="tabpanel" aria-labelledby="notices-tab">
                            <div class="d-flex flex-column pr-10px c-scrollbar-light" style="gap: 12px; max-height: 300px; overflow-y: auto;">
                                @forelse ($allNotices as $notice)
                                    <div class="rounded-1 p-2" style="background-color: {{ $notice->bg_color_hex }};">
                                        <span class="fs-13 fw-400">{{ $notice->message }}</span>
                                        @if ($notice->id === 1)
                                            <div>
                                                <a href="{{ route('seller.shop.verify') }}" class="fs-14 fw-bold hov-text-blue has-transition">{{ translate('here.') }}</a>
                                            </div>
                                        @elseif ($notice->id === 2)
                                            <a href="{{ route('seller.shop.index') }}" class="fs-14 fw-bold hov-text-blue has-transition">{{ translate('this.') }}</a>
                                        @elseif ($notice->id === 3 && !empty($notice->expiry_date))
                                            <span class="fs-13 fw-400 text-danger"> {{ $notice->expiry_date }}</span>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">{{ translate('No notices found') }}</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Requests --}}
                        <div class="tab-pane fade" id="requests" role="tabpanel" aria-labelledby="requests-tab">
                            <div class="d-flex flex-column pr-10px c-scrollbar-light" style="gap: 12px; max-height: 300px; overflow-y: auto;">
                                @forelse ($requests as $req)
                                    @php $item = $req->item_label; @endphp
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">
                                            @if ($item)
                                                {{ translate('You requested to add a') }} <a class="fs-13 fw-700 text-reset has-transition">{{ translate($item['type']) }}</a> {{ translate('named') }} <a class="fs-13 fw-700 text-reset has-transition">{{ $item['value'] }}</a>.
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">{{ translate('No requests found') }}</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Promotions --}}
                        <div class="tab-pane fade" id="promotions" role="tabpanel" aria-labelledby="promotions-tab">
                            <div class="d-flex flex-column pr-10px c-scrollbar-light" style="gap: 12px; max-height: 300px; overflow-y: auto;">
                                @forelse ($promotions as $promotion)
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">
                                            {{ translate('Admin created a promotion offer for') }}
                                            @if ($promotion->flash_sale_id && $promotion->flashSale)
                                                <a class="fs-13 fw-700 text-reset has-transition">{{ $promotion->flashSale->title }}</a>
                                                <span class="fs-13 fw-400 text-dark">{{ translate('for') }}</span>
                                                <a class="fs-13 fw-700 text-reset has-transition">{{ translate('FLASH DEALS') }}</a>
                                            @else
                                                <a class="fs-13 fw-700 text-reset has-transition">{{ $promotion->promo_type_label }}</a>
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">{{ translate('No promotions found') }}</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        {{-- Messages --}}
                        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                            <div class="d-flex flex-column pr-10px c-scrollbar-light" style="gap: 12px; max-height: 300px; overflow-y: auto;">
                                @if ($conversations->isNotEmpty() && $conversations->first()->lastSellerMessage)
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">{{ translate('Admin sent a message:') }} {{ \Illuminate\Support\Str::limit($conversations->first()->lastSellerMessage->message, 60) }}</span>
                                        <a href="javascript:void(0);" class="js-goto-messages fs-14 fw-bold hov-text-blue has-transition d-block">{{ translate('View') }}</a>
                                    </div>
                                @else
                                    <div class="rounded-1 p-2" style="background-color: #F7F7FA;">
                                        <span class="fs-13 fw-400">{{ translate('No messages yet') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(get_setting('vendor_commission_activation') == 1)
            <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
                <div class="card shadow-none mb-0 h-100">
                    @if(get_setting('seller_commission_type') == 'fixed_rate')
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="card-title text-primary fs-16 fw-bold">
                                    {{ translate('Commission Type & Rate') }}
                                </div>
                                <p class="fs-13 fw-400 m-0">{{ translate('You are under') }}
                                    <b>{{ translate('Fixed Commission Based') }}</b> {{ translate('system.') }} </p>
                                <p class="fs-13 fw-400 m-0">{{ translate('Your commission rate is') }}
                                    <b>{{ get_setting('vendor_commission') }}%</b></p>
                            </div>
                        </div>
                    @elseif(get_setting('seller_commission_type') == 'seller_based')
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="card-title text-primary fs-16 fw-bold">
                                    {{ translate('Commission Type & Rate') }}
                                </div>
                                <p class="fs-13 fw-400 m-0">{{ translate('You are under') }}
                                    <b>{{ translate('Seller Based Commission.') }}</b> {{ translate('system') }}</p>
                                <p class="fs-13 fw-400 m-0">{{ translate('Your commission rate is') }}
                                    <b>{{ \App\Models\Shop::where('user_id', $authUser->id)->first()->commission_percentage }}%</b>
                                </p>
                            </div>
                        </div>
                    @elseif(get_setting('seller_commission_type') == 'category_based')
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="card-title text-primary fs-16 fw-bold">
                                    {{ translate('Commission Type & Rate') }}
                                </div>
                                <p class="fs-13 fw-400 m-0">{{ translate('You are under') }}
                                    <b>{{ translate('Category Based Commission') }}</b> {{ translate('system.') }} </p>
                            </div>
                            <div>
                                <a href="{{ route('seller.categories-wise-commission') }}"
                                    class="fs-13 fw-500 text-blue hov-opacity-80  has-transition">{{ translate('View Commission Details') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
                <div class="card shadow-none mb-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="card-title text-primary fs-16 fw-bold">
                                {{ translate('Commission Type & Rate') }}
                            </div>
                            <p class="fs-13 fw-400 m-0">{{ translate('Currently no commission system is set by Admin.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (addon_is_activated('seller_subscription'))
            <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
                <div class="card shadow-none mb-0 h-100">
                    <div class="card-body d-flex flex-column @if(!$authUser->shop?->seller_package) justify-content-between @endif" style="gap: 16px;">
                        <div class="card-title text-primary fs-16 fw-bold">
                            {{ translate('Purchased Package') }}
                        </div>
                        @if ($authUser->shop?->seller_package)
                            <div class="d-flex align-items-center justify-content-between" style="gap: 12px;">
                                <div>
                                    <p class="fs-13 fw-400 m-0 pb-1">{{ translate('Current Package') }}</p>
                                    <strong class="fs-13">{{ $authUser->shop->seller_package->name }}</strong>
                                    <span class="fs-12 fw-400 text-danger d-block mt-2">{{ translate('Expires on ') }} {{ $authUser->shop->package_invalid_at }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0"
                                    style="width: 64px; height: 64px;">
                                    <img src="{{ uploaded_asset($authUser->shop->seller_package->logo) }}" class="img-fit" alt="">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <a class="fs-13 fw-500 text-blue hov-opacity-80 has-transition"></a>
                                <a href="{{ route('seller.seller_packages_list') }}" class="fs-13 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('Upgrade Package') }}</a>
                            </div>
                        @else 
                            <div class="d-flex align-items-center justify-content-between" style="gap: 12px;">
                                <div>
                                    <strong class="fs-13">{{ translate('Package Not Found') }}</strong>
                                </div>
                            </div>
                        @endif   
                    </div>
                </div>
            </div>
        @endif
        <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
            <a href="{{ route('seller.shop.index') }}" class="d-block w-100">
                <div class="card bg-light shadow-none h-lg-110px h-xl-90px hov-opacity-80 has-transition">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="card-title text-center text-primary fs-16 fw-bold mb-0">
                            {{ translate('Go to Shop Settings') }}
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('seller.money_withdraw_requests.index') }}" class="d-block w-100">
                <div class="card bg-light shadow-none h-lg-110px h-xl-90px mb-0 hov-opacity-80 has-transition">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="card-title text-center text-primary fs-16 fw-bold mb-0">
                            {{ translate('Withdraw Money Now') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-6 col-xl-3 mb-4">
            <a href="{{ route('seller.profile.index') }}" class="d-block w-100">
                <div class="card bg-light shadow-none h-lg-110px h-xl-90px hov-opacity-80 has-transition">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="card-title text-center text-primary fs-16 fw-bold mb-0">
                            {{ translate('Configure Payment Settings') }}
                        </div>
                    </div>
                </div>
            </a>
            <a href="{{ route('seller.products.create') }}" class="d-block w-100">
                <div class="card bg-white hov-bg-light border-dashed shadow-none h-lg-110px h-xl-90px has-transition">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="card-title text-center text-primary fs-16 fw-bold mb-0">
                            {{ translate('+ Add New Product') }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-title text-primary mb-4">
                <h6 class="mb-1 fs-16 fw-600">{{ translate('Top Products') }}</h6>
                <span class="fs-13 fw-400 opacity-70">{{ translate('Top 12 Products by Sales') }}</span>
            </div>
            <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="9.5" data-xl-items="9.5" data-lg-items="5.3"
                data-md-items="4.3" data-sm-items="2.2" data-arrows='false'>
                @foreach ($products as $key => $product)
                    <div class="carousel-box">
                        <div class="aiz-card-box bg-white">
                            <div class="position-relative">
                                <a href="{{ route('product', $product->slug) }}" class="d-block">
                                    <img class="img-fit lazyload mx-auto h-140px rounded-2"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                            <div class="py-3 text-left">
                                <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-0 hov-text-blue has-transition">
                                    <a href="{{ route('product', $product->slug) }}"
                                        class="d-block text-reset">{{ $product->getTranslation('name') }}</a>
                                </h3>
                                <div class="fs-14 mt-2">
                                    @if (home_base_price($product) != home_discounted_base_price($product))
                                        <del class="fw-bold opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                    @endif
                                    <span class="fw-bold text-primary">{{ home_discounted_base_price($product) }}</span>
                                </div>
                                {{-- <div class="rating rating-sm mt-1">
                                    {{ renderStarRating($product->rating) }}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal fade" id="varification_pending_modal" tabindex="-1" role="dialog" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content text-center p-4">
                @if($authUser->shop?->business_info == null || $authUser->shop?->business_info == '')
                    <h4 class="mb-3">{{ translate('Complete your verification') }}</h4>
                    <p class="text-muted">
                        {{ translate('Your account is pending verification. Please complete verification to continue.') }}
                    </p>
                    <a href="javascript:void(0)" class="btn btn-primary" id="openSellerVerification">
                        Click here to verify
                    </a>
                @else
                    <h4 class="mb-3">{{ translate('Verification is under review') }}</h4>
                    <p class="text-muted">
                        {{ translate('Your submitted verification documents are under review. We will notify you once the review is complete.') }}
                    </p>
                @endif
                <div class="mt-3 d-flex justify-content-center gap-2">
                    <a href="{{ route('home') }}" class="mr-2">
                        {{ translate('Go to Home') }}
                    </a> |
                    <a href="{{ route('custom-pages.show_custom_page', 'contact-us') }}" class="ml-2">
                        {{ translate('Contact Us') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
    <div id="seller_verification_modal" class="modal fade">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold h6">{{ translate('Seller Verification') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light seller-verification-form">
                    <form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data"
                        id="seller-verification-form">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ auth()->user()->shop->id }}">
                        <div class="form-group">
                            <label for="company-type"
                                class="fs-13 fw-bold">{{ translate('Company Type (Dropdown Selection):') }}</label>
                            <select class="form-control aiz-selectpicker bg-soft-light rounded-2" data-live-search="true"
                                name="shop_type" required>
                                <option value="installer">Installer</option>
                                <option value="developer">Developer</option>
                                <option value="om">O&M</option>
                                <option value="supplier">Supplier / Reseller</option>
                                <option value="distributor">Distributor</option>
                                <option value="manufacturer">Manufacturer</option>
                                <option value="broker">Broker</option>
                                <option value="other">Other</option>
                            </select>
                            <small id="type-error" class="text-danger d-none">
                                {{ translate('Please select a company type') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="certificate-number" class="fs-13 fw-bold">{{ translate('Licence Number') }}</label>
                            <input type="text" name="certificate_number"
                                class="form-control text-gray fs-13 fw-300 rounded-2 bg-soft-light" id="certificate-number"
                                placeholder="Licence number" required>
                        </div>
                        <div class="form-group">
                            <label for="tax-id-documents"
                                class="fs-13 fw-bold">{{translate('Tax Identification Document')}}</label>
                            <div class="input-group rounded-2 bg-soft-light overflow-hidden">
                                <div class="input-group-prepend rounded-2 bg-soft-light">
                                    <span class="input-group-text  bg-soft-light">{{translate('Browse')}}</span>
                                </div>
                                <div class="custom-file">
                                    <label class="custom-file-label  cursor-pointer">
                                        <input type="file" class="custom-file-input preview-input "
                                            data-preview="#certificate_preview" name="certificate" id="certificate"
                                            accept=".jpg,.jpeg,.png,.bmp,application/pdf">
                                        <span class="custom-file-name  cursor-pointer">{{ translate('Choose file') }}</span>
                                    </label>
                                </div>
                            </div>
                            <small id="certificate-error" class="text-danger d-none">
                                {{ translate('Please upload a valid certificate') }}</small>
                        </div>
                        <div id="certificate_preview" class="mb-2"></div>
                        <div class="form-group">
                            <label for="valid-id-card" class="fs-13 fw-bold">{{translate('Valid ID Card')}}</label>
                            <div class="input-group rounded-2 bg-soft-light overflow-hidden">
                                <div class="input-group-prepend rounded-2 bg-soft-light">
                                    <span class="input-group-text  bg-soft-light">{{translate('Browse')}}</span>
                                </div>
                                <div class="custom-file">
                                    <label class="custom-file-label  cursor-pointer">
                                        <input type="file" class="custom-file-input preview-input "
                                            data-preview="#id_card_preview" name="id_card" id="id_card"
                                            accept=".jpg,.jpeg,.png,.bmp,application/pdf">
                                        <span class="custom-file-name  cursor-pointer">{{ translate('Choose file') }}</span>
                                    </label>
                                </div>
                            </div>
                            <small id="id_card-error" class="text-danger d-none">
                                {{ translate('Please upload a valid ID card') }}</small>
                        </div>
                        <div id="id_card_preview" class="mb-2"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid-id-card" class="fs-13 fw-bold">{{translate('Photo')}}</label>
                                    <div class="input-group rounded-2 bg-soft-light overflow-hidden">
                                        <div class="input-group-prepend rounded-2 bg-soft-light">
                                            <span class="input-group-text  bg-soft-light">{{translate('Browse')}}</span>
                                        </div>
                                        <div class="custom-file">
                                            <label class="custom-file-label  cursor-pointer">
                                                <input type="file" class="custom-file-input preview-input "
                                                    data-preview="#seller_photo_preview" name="seller_photo"
                                                    id="seller_photo" accept=".jpg,.jpeg,.png,.bmp,application/pdf">
                                                <span
                                                    class="custom-file-name  cursor-pointer">{{ translate('Choose file') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="seller_photo_preview" class="mb-2"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="fs-13 fw-bold">{{translate('Live Selfie Photo')}}</label>
                                    <video id="selfieVideo" class="w-100 rounded d-none" autoplay playsinline></video>
                                    <canvas id="selfieCanvas" class="w-100 rounded d-none mt-2"></canvas>
                                    <input type="hidden" name="live_selfie" id="liveSelfieInput">
                                    <div class="text-center">
                                        <button type="button" id="openCameraBtn" class="btn btn-outline-primary w-100">
                                            {{translate('Take Selfie')}}
                                        </button>
                                        <button type="button" id="captureSelfieBtn" class="btn btn-success d-none mt-2">
                                            {{translate('Capture')}}
                                        </button>
                                        <button type="button" id="retakeSelfieBtn" class="btn btn-warning d-none mt-2">
                                            {{translate('Retake')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger d-none mb-4" id="photoSelfieRequiredAlert">
                            {{ translate('At least one of Photo or Live Selfie is required!') }}
                        </div>
                        <div class="form-group mt-5">
                            <button type="submit" value="Create Account"
                                class="d-block w-100 border-0 fs-13 fw-bold btn btn-primary rounded-2 py-3">
                                {{ translate('Submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="rightOffcanvas" class="right-offcanvas-md position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        <div class="border-bottom pb-15px px-30px">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="fs-16 fw-700 m-0 text-truncate">Lorem ipsum dolor sit amet.</h5>
                <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
                    <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
                </button>
            </div>
        </div>
        <div class="right-offcanvas-body position-absolute h-100 px-30px">
            <span>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aliquam, deleniti.</span>
        </div>
        <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
            <div class="d-flex justify-content-end footer-btn">
                <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel"
                    onclick="closeOffcanvas()">{{translate('Cancel')}}</button>
                <button type="button" class="d-block fs-14 fw-700 py-10px save">{{translate('Save')}}</button>
            </div>
        </div>
    </div>
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection



@section('script')
    <script type="text/javascript">
        var salesData = [
            @foreach ($last_7_days_sales as $date => $amount)
                {{ $amount }},
            @endforeach
            ];

        var maxVal = Math.max(...salesData);
        var yMax = maxVal > 0 ? Math.ceil(maxVal / 1000) * 1000 : 4000;

        AIZ.plugins.chart('#graph-1', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($last_7_days_sales as $date => $amount)
                        '{{ $date }}',
                    @endforeach
                    ],
                datasets: [{
                    label: '',
                    data: salesData,
                    backgroundColor: '#5B346C',
                    borderWidth: 0,
                    borderRadius: 2,
                    borderSkipped: 'bottom',
                    barThickness: 12,
                    maxBarThickness: 12
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        min: 0,
                        max: yMax,
                        grid: {
                            display: true,
                            color: '#EAEAEA',
                            drawBorder: false,
                            lineWidth: 1
                        },
                        ticks: {
                            color: "#AFAFAF",
                            font: { family: 'Roboto, sans-serif', size: 11 },
                            callback: function (value) {
                                if (value === 0) return '0';
                                if (value >= 1000) return (value / 1000) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: "#AFAFAF",
                            font: { family: 'Roboto, sans-serif', size: 11 }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Right Offcanvas JS Start -->
    <script>
        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        // Open function
        function openRightcanvas() {
            // content.textContent = data;
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            // rightOffcanvas.innerHTML = '';

        }
        // Close function
        function closeRightcanvas() {
            rightOffcanvas.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
        }

        function closeOffcanvas() {
            closeRightcanvas();
        }

        if (overlay) {
            overlay.addEventListener('click', closeRightcanvas);
        }
        //  close with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeRightcanvas();
        });
    </script>
    <!-- Right Offcanvas JS End -->


    @if(addon_is_activated('portfolio_system') && ($authUser->shop?->verification_status == 0))
        <script>
            $(document).ready(function () {
                $('body').addClass('verification-lock');

                $('#varification_pending_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#varification_pending_modal').modal('show');
            });
        </script>
    @endif

    <script>
        // Verification modal handling
        @if(addon_is_activated('portfolio_system') && ($authUser->shop?->verification_status == 0))
            $('body').addClass('verification-lock');
            $('#varification_pending_modal').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        @endif

        // Open verification form
        $('#openSellerVerification').on('click', function () {
            $('#varification_pending_modal').modal('hide');
            $('#seller_verification_modal').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');
        });

        // File preview function
        $(document).on('change', '.preview-input', function () {
            const input = this;
            const previewBox = $($(this).data('preview'));
            const file = input.files[0];

            $(this).next('.custom-file-label').html(file?.name || '');
            previewBox.html('');

            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewBox.html(
                            `<img src="${e.target.result}" class="preview-img img-fluid h-100px w-100-px">`
                        );
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    previewBox.html(`
                        <div class="pdf-preview d-flex align-items-end justify-content-center border rounded text-center" 
                            style="width:100px; height:100px; background-color:#f8f9fa; position:relative;">
                            <i class="las la-file-pdf" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); opacity:0.2; font-size:50px; color:#e74c3c;"></i>
                            <small class="text-truncate-1 fs-10" style="position:absolute; bottom:5px; left:0;" title="${file.name}">${file.name}</small>
                        </div>
                    `);
                }
            }
        });

        // Camera functionality
        let selfieStream;
        const video = document.getElementById('selfieVideo');
        const canvas = document.getElementById('selfieCanvas');
        const ctx = canvas.getContext('2d');

        $('#openCameraBtn').on('click', takeSelfie);
        $('#captureSelfieBtn').on('click', captureSelfie);
        $('#retakeSelfieBtn').on('click', retakeSelfie);

        function captureSelfie() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);

            stopCamera();

            const imageData = canvas.toDataURL('image/png');
            $('#liveSelfieInput').val(imageData);

            video.classList.add('d-none');
            canvas.classList.remove('d-none');

            $('#captureSelfieBtn').addClass('d-none');
            $('#retakeSelfieBtn').removeClass('d-none');

            // Mark as valid
            $('#photoSelfieRequiredAlert').addClass('d-none');
        }

        function retakeSelfie() {
            $('#retakeSelfieBtn').addClass('d-none');
            $('#openCameraBtn').removeClass('d-none');
            $('#liveSelfieInput').val('');

            canvas.classList.add('d-none');
            takeSelfie();
        }

        async function takeSelfie() {
            try {
                selfieStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "user" },
                    audio: false
                });

                video.srcObject = selfieStream;
                video.classList.remove('d-none');

                $('#openCameraBtn').addClass('d-none');
                $('#captureSelfieBtn').removeClass('d-none');
            } catch (e) {
                alert('Camera access denied or not supported!');
            }
        }

        function stopCamera() {
            if (selfieStream) {
                selfieStream.getTracks().forEach(track => track.stop());
                selfieStream = null;
            }
        }

        // Handle modal close - stop camera
        $('#seller_verification_modal').on('hide.bs.modal', function () {
            stopCamera();
            if (video && video.srcObject) {
                video.srcObject = null;
            }
        });

        // Form validation
        const form = document.getElementById('seller-verification-form');

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();

            let isValid = true;

            // Reset states
            $('#photoSelfieRequiredAlert').addClass('d-none');

            // Check photo or selfie
            const hasPhoto = $('#seller_photo')[0].files.length > 0;
            const hasSelfie = $('#liveSelfieInput').val().trim() !== '';

            if (!hasPhoto && !hasSelfie) {
                isValid = false;
                $('#photoSelfieRequiredAlert').removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $("#seller_photo").offset().top - 100
                }, 400);
            }

            // Required file validations
            const requiredFields = [
                { id: 'certificate', errorId: '#certificate-error' },
                { id: 'id_card', errorId: '#id_card-error' }
            ];

            requiredFields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input.files.length === 0) {
                    isValid = false;
                    $(field.errorId).removeClass('d-none');
                }
            });

            if (isValid) {
                form.submit();
            }
        });

        // Real-time validation for photo/selfie
        $('#seller_photo, #liveSelfieInput').on('change input', function () {
            const hasPhoto = $('#seller_photo')[0].files.length > 0;
            const hasSelfie = $('#liveSelfieInput').val().trim() !== '';

            if (hasPhoto || hasSelfie) {
                $('#photoSelfieRequiredAlert').addClass('d-none');
            }
        });

        $(document).on('shown.bs.tab', '#notices-tab', function () {
            $('#notices-tab .badge-danger').remove();
            $.post('{{ route("seller.admin_seller_hub.notices") }}', { _token: '{{ csrf_token() }}' });
        });

        $(document).on('shown.bs.tab', '#promotions-tab', function () {
            $('#promotions-tab .badge-danger').remove();
            $.post('{{ route("seller.admin_seller_hub.promotions") }}', { _token: '{{ csrf_token() }}' });
        });

        $(document).on('shown.bs.tab', '#messages-tab', function () {
            $('#messages-tab .badge-danger').remove();
            $.post('{{ route("seller.admin_seller_chat.show") }}', { _token: '{{ csrf_token() }}' });
        });

        $(document).on('shown.bs.tab', '#all-notice-tab', function () {
            $('#notices-tab .badge-danger').remove();
            $('#promotions-tab .badge-danger').remove();
            $.post('{{ route("seller.admin_seller_hub.notices") }}', { _token: '{{ csrf_token() }}' });
            $.post('{{ route("seller.admin_seller_hub.promotions") }}', { _token: '{{ csrf_token() }}' });
        });

        $(document).on('shown.bs.tab', '#notices-tab, #promotions-tab, #messages-tab, #all-notice-tab', function () {
            $('#view_all_chat .badge-danger').remove();
        });
    </script>

@endsection