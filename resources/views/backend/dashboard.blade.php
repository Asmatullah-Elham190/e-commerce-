@extends('backend.layouts.app')

@section('content')
    @if (auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
        <div class="">
            <div class="alert alert-info d-flex align-items-center">
                {{ translate('Please Configure SMTP Setting to work all email sending functionality') }},
                <a
                    class="alert-link ml-2"
                    href="{{ route('smtp_settings.index') }}"
                >{{ translate('Configure Now') }}</a>
            </div>
        </div>
    @endif
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fs-18 fw-600 text-dark mb-1">{{ translate('Dashboard') }}</h2>
            <span class="fs-12 fw-400 text-dark">{{ translate('Central hub of your business') }}</span>
        </div>
        <div class="d-flex align-items-center">
            <div
                class="fs-16 fw-bold d-flex align-items-center justify-content-center w-40px h-40px rounded-circle bg-light mr-2">
                {{ now()->format('d') }}</div>
            <span class="fs-12 fw-400">{{ now()->format('l') }}</span>
            <span class="fs-12 fw-400">{{ now()->format(', F Y') }}</span>
        </div>
    </div>
    @can('admin_dashboard')
        <div class="row gutters-16">
            <div class="col-lg-6">
                <div class="row gutters-16">
                    <div class="col-sm-6">
                        <div class="dashboard-box h-220px mb-2rem overflow-hidden bg-white">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h1 class="fs-30 fw-600 text-dark mb-1">
                                            {{ $total_customers }}
                                        </h1>
                                        <h3 class="fs-13 fw-400 text-reset mb-0">{{ translate('Registered Customer') }}</h3>
                                    </div>
                                    <div class="mt-2">
                                        <img
                                            src="{{ static_asset('assets/img/total-customer.svg') }}"
                                            alt="Total Customer"
                                        >
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-13 fw-400 mb-2">
                                        <span class="badge badge-md badge-dot badge-circle badge-danger mr-2"></span>
                                        {{ translate('Top Customers') }}
                                    </h3>
                                    <div class="symbol-group">
                                        @foreach ($top_customers as $top_customer)
                                            <div
                                                class="symbol size-40px rounded-content overflow-hidden"
                                                title="{{ $top_customer->name }}"
                                            >
                                                <img
                                                    src="{{ uploaded_asset($top_customer->avatar_original) }}"
                                                    alt="{{ translate('customer') }}"
                                                    class="h-100 img-fit lazyload"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dashboard-box h-220px mb-2rem overflow-hidden bg-white">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h1 class="fs-30 fw-600 text-dark mb-1">{{ $total_products }}</h1>
                                        <h3 class="fs-13 fw-400 text-reset mb-0">{{ translate('Total Products') }}</h3>
                                    </div>
                                    <div class="mt-2">
                                        <img
                                            src="{{ static_asset('assets/img/total-products.svg') }}"
                                            alt="Total Products"
                                        >
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <h3 class="fs-13 fw-400 text-truncate mb-0 mr-2">
                                            <span class="badge badge-md badge-dot badge-circle badge-success mr-2"></span>
                                            {{ translate('In-house Products') }}
                                        </h3>
                                        <h3 class="fs-13 fw-600 mb-0">
                                            {{ $total_inhouse_products }}
                                        </h3>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h3 class="fs-13 fw-400 text-truncate mr-2">
                                            <span class="badge badge-md badge-dot badge-circle badge-primary mr-2"></span>
                                            {{ translate('Sellers Products') }}
                                        </h3>
                                        <h3 class="fs-13 fw-600 mb-0">
                                            {{ $total_sellers_products }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dashboard-box h-220px mb-2rem overflow-hidden bg-white">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h1 class="fs-30 fw-600 text-dark mb-1">{{ $total_categories }}</h1>
                                        <h3 class="fs-13 fw-400 text-reset mb-0">{{ translate('Total Category') }}</h3>
                                    </div>
                                    <div class="mt-2">
                                        <img
                                            src="{{ static_asset('assets/img/total-category.svg') }}"
                                            alt="Total Category"
                                        >
                                    </div>
                                </div>
                                <div>
                                    @foreach ($top_categories as $key => $top_category)
                                        <div class="d-flex justify-content-between mt-2">
                                            @php
                                                $badge = 'badge-danger';
                                                if ($key == 1) {
                                                    $badge = 'badge-warning';
                                                }
                                                if ($key == 2) {
                                                    $badge = 'badge-primary';
                                                }
                                                $lang = App::getLocale();
                                                $category = App\Models\CategoryTranslation::where(
                                                    'category_id',
                                                    $top_category->id,
                                                )
                                                    ->where('lang', $lang)
                                                    ->first();
                                            @endphp
                                            <h3
                                                class="fs-13 fw-400 text-reset d-flex align-items-center text-truncate mb-0 mr-2"
                                                title="{{ $category ? $category->name : translate('Not Found') }}"
                                            >
                                                <span
                                                    class="badge badge-sm badge-dot {{ $badge }} mr-2"
                                                    style="height:4px !important; width:20px !important;"
                                                ></span>
                                                {{ $category ? $category->name : translate('Not Found') }}
                                            </h3>
                                            <h3 class="fs-13 fw-400 mb-0">
                                                {{ single_price($top_category->total) }}
                                            </h3>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dashboard-box h-220px mb-2rem overflow-hidden bg-white">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h1 class="fs-30 fw-600 text-dark mb-1">{{ $total_brands }}</h1>
                                        <h3 class="fs-13 fw-400 text-reset mb-0">{{ translate('Total Brands') }}</h3>
                                    </div>
                                    <div class="mt-2">
                                        <img
                                            src="{{ static_asset('assets/img/total-brands.svg') }}"
                                            alt="Total Brands"
                                        >
                                    </div>
                                </div>
                                <div>
                                    @foreach ($top_brands as $key => $top_brand)
                                        <div class="d-flex justify-content-between mb-0 mt-2">
                                            @php
                                                $badge = 'badge-success';
                                                if ($key == 1) {
                                                    $badge = 'badge-primary';
                                                }
                                                if ($key == 2) {
                                                    $badge = 'badge-info';
                                                }
                                                $lang = App::getLocale();
                                                $brand = App\Models\BrandTranslation::where('brand_id', $top_brand->id)
                                                    ->where('lang', $lang)
                                                    ->first();
                                            @endphp
                                            <h3
                                                class="fs-13 fw-400 text-truncate mb-0 mr-2"
                                                title="{{ $brand ? $brand->name : translate('Not Found') }}"
                                            >
                                                <span
                                                    class="badge badge-md badge-dot badge-circle {{ $badge }} mr-2"></span>
                                                {{ $brand ? $brand->name : translate('Not Found') }}
                                            </h3>
                                            <h3 class="fs-13 fw-400 text-reset mb-0">
                                                {{ single_price($top_brand->total) }}
                                            </h3>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row gutters-16">
                    <div class="col-sm-6">
                        <div
                            class="dashboard-box mb-2rem overflow-hidden bg-white"
                            style="height: 470px;"
                        >
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h1 class="fs-30 fw-600 text-dark mb-1">
                                        {{ number_format_short($total_sale) }}
                                    </h1>
                                    <h3 class="fs-13 fw-400 text-reset mb-0">{{ translate('All Time Sales') }}</h3>
                                </div>
                                <div
                                    class="d-flex align-items-center justify-content-between rounded-2 bg-primary mr-2 p-3 text-white">
                                    <h3 class="fs-13 fw-600 mb-0">
                                        {{ translate('Sales this month') }}
                                    </h3>
                                    <h3 class="fs-13 fw-600 mb-0">
                                        {{ single_price($sale_this_month) }}
                                    </h3>
                                </div>
                                <div>
                                    <h3 class="fs-13 fw-400 text-reset mb-0">{{ translate('Sales State') }}</h3>
                                </div>
                                <div
                                    class="w-100"
                                    style="box-sizing: border-box;"
                                >
                                    <div style="width: 100%; height: 140px; position: relative;">
                                        <canvas id="graph-3"></canvas>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <h3 class="fs-13 fw-400 mb-0">
                                            <span
                                                class="badge badge-md badge-dot badge-circle badge-info text-truncate mr-2"></span>
                                            {{ translate('In-house Sales') }}
                                        </h3>
                                        <h3 class="fs-13 fw-600 mb-0">
                                            {{ single_price($admin_sale_this_month->total_sale) }}
                                        </h3>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h3 class="fs-13 fw-400 mb-0">
                                            <span
                                                class="badge badge-md badge-dot badge-circle badge-success text-truncate mr-2"
                                            ></span>
                                            {{ translate('Sellers Sales') }}
                                        </h3>
                                        <h3 class="fs-13 fw-600 mb-0">
                                            {{ single_price($seller_sale_this_month->total_sale) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div
                            class="dashboard-box mb-2rem overflow-hidden bg-white"
                            style="height: 470px;"
                        >
                            @if (get_setting('vendor_system_activation') == 1)
                                <div class="d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <h1 class="fs-30 fw-600 text-dark mb-1">
                                            {{ $total_sellers }}
                                        </h1>
                                        <h3 class="fs-13 fw-400 text-reset mb-0">
                                            {{ translate('Sellers in the Platform') }}</h3>
                                    </div>
                                    <div>
                                        <div
                                            class="d-flex justify-content-between mb-2">
                                            <h3 class="fs-13 fw-400 mb-0">
                                                <span
                                                    class="badge badge-md badge-dot badge-circle badge-success text-truncate mr-2"
                                                ></span>
                                                {{ translate('Approved Sellers') }}
                                            </h3>
                                            <h3 class="fs-13 fw-600 mb-0">
                                                {{ $total_approved_sellers  }}
                                            </h3>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between">
                                            <h3 class="fs-13 fw-400 mb-0">
                                                <span
                                                    class="badge badge-md badge-dot badge-circle badge-danger text-truncate mr-2"
                                                ></span>
                                                {{ translate('Pending Seller') }}
                                            </h3>
                                            <h3 class="fs-13 fw-600 mb-0">
                                                {{ $total_pending_sellers  }}
                                            </h3>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="fs-13 fw-400 mb-2">
                                            <span class="badge badge-md badge-dot badge-circle badge-warning mr-2"></span>
                                            {{ translate('Top Sellers') }}
                                        </h3>
                                        <div class="symbol-group">
                                            @foreach ($top_sellers as $top_seller)
                                                <div
                                                    class="symbol size-40px rounded-content overflow-hidden"
                                                    title="{{ $top_seller->name }}"
                                                >
                                                    <img
                                                        src="{{ uploaded_asset($top_seller->avatar_original) }}"
                                                        alt="{{ translate('seller') }}"
                                                        class="h-100 img-fit lazyload"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </div>
                                            @endforeach
                                        </div>
                                        <hr style="border: 1px dashed #dbdfe9;">
                                    </div>
                                    @canany(['view_all_seller', 'view_pending_seller'])
                                        <div class="">
                                            @can('view_all_seller')
                                                <a
                                                    href="{{ route('sellers.index') }}"
                                                    class="btn btn-md fs-13 fw-bold btn-block rounded-2 all-seller-btn mb-3"
                                                >{{ translate('All Sellers') }}</a>
                                            @endcan
                                            @can('view_pending_seller')
                                                <a
                                                    href="{{ route('sellers.index') }}?approved_status=0"
                                                    class="btn btn-md fs-13 fw-bold btn-soft-danger btn-block rounded-2"
                                                >{{ translate('Pending Sellers') }}</a>
                                            @endcan
                                        </div>
                                    @endcanany
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <div class="h-200px">
                                        <img
                                            src="{{ static_asset('assets/img/multivendor.jpg') }}"
                                            alt="{{ translate('multivendor') }}"
                                            class="h-100 img-fit"
                                        >
                                    </div>
                                    <a
                                        href="{{ route('activation.index') }}"
                                        class="fs-13 fw-600 text-info hov-text-primary animate-underline-primary mt-4"
                                    >
                                        {{ translate('Activate Vendor System') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard-box mb-2rem py-2rem overflow-hidden bg-white px-0">
                    <div class="d-flex align-items-center justify-content-between px-2rem">
                        <h2 class="fs-16 fw-700 text-soft-dark mb-0">{{ translate('Orders') }}</h2>
                        <div
                            class="d-flex align-items-center rounded-pill px-3 py-2"
                            style="background-color: rgba(11, 128, 253, 0.2);"
                        >
                            <span class="d-block w-10px h-10px bg-blue border-blue rounded-circle"></span>
                            <span class="fs-13 fw-500 text-blue pl-2 pr-3">{{ translate('Fulfilment Rate') }}</span>
                            <span class="fs-13 fw-700 text-blue">{{ $total_delivered_order_percentage }}%</span>
                        </div>
                    </div>
                    <div class="row pl-2rem gutters-12">
                        <div class="col-md-6">
                            <div class="mb-4 mt-3">
                                <h3 class="fs-30 fw-600 text-dark mb-1">{{ $total_order }}</h3>
                                <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('All Types of Orders') }}</h4>
                            </div>
                            <div class="card card-no-shadow bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="w-10px h-10px rounded-circle bg-success mr-2 border-0"></div>
                                        <span class="fs-13 fw-400 text-dark mr-4">{{ translate('In-house Orders') }}</span>
                                    </div>
                                    <div class="fs-20 fw-600 text-dark pb-1 pt-2"> {{ $total_inhouse_order }}</div>
                                    <span class="fs-13 fw-400 text-dark d-block">{{ $inhouse_order_percentage }} %
                                        {{ translate('of Total') }}</span>
                                </div>
                            </div>
                            <div class="card card-no-shadow bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="w-10px h-10px rounded-circle bg-blue mr-2 border-0"></div>
                                        <span class="fs-13 fw-400 text-dark mr-4">{{ translate('Sellers Orders') }}</span>
                                    </div>
                                    <div class="fs-20 fw-600 text-dark pb-1 pt-2"> {{ $total_seller_order }}</div>
                                    <span class="fs-13 fw-400 text-dark d-block">{{ $seller_order_percentage }} %
                                        {{ translate('of Total') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                class="d-flex flex-column mt-md-5 pt-md-1 pr-2rem c-scrollbar-light h-330px h-lg-320px h-xl-280px h-xxl-340px mt-3"
                                style="gap: 24px; overflow-y: auto;"
                            >
                                <div class="d-flex align-items-end">
                                    <img
                                        src="{{ static_asset('assets/img/pending-order.svg') }}"
                                        class="flex-shrink-0"
                                        alt="Pending Order"
                                    />
                                    <div class="flex-grow-1 ml-2">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                            style="gap: 12px;"
                                        >
                                            <div class="d-flex align-items-center">
                                                <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Pending Order') }} </h3>
                                                <span
                                                    class="fs-13 fw-400 text-secondary">({{ $total_pending_order_percentage }}
                                                    %)</span>
                                            </div>
                                            <strong class="fs-13 fw-700 text-dark"> {{ $total_pending_order }}</strong>
                                        </div>
                                        <div
                                            class="progress h-5px mt-1"
                                            style=""
                                        >
                                            <div
                                                class="progress-bar h-100 rounded-pill"
                                                role="progressbar"
                                                style="width: {{ $total_pending_order_percentage }}%; background-color: #F74396;"
                                                aria-valuenow="30"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <img
                                        src="{{ static_asset('assets/img/confirmed-order.svg') }}"
                                        class="flex-shrink-0"
                                        alt="Confirmed Order"
                                    />
                                    <div class="flex-grow-1 ml-2">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                            style="gap: 12px;"
                                        >
                                            <div class="d-flex align-items-center">
                                                <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Confirmed Order') }} </h3>
                                                <span
                                                    class="fs-13 fw-400 text-secondary">({{ $total_confirmed_order_percentage }}
                                                    %)</span>
                                            </div>
                                            <strong class="fs-13 fw-700 text-dark"> {{ $total_confirmed_order }}</strong>
                                        </div>
                                        <div
                                            class="progress h-5px mt-1"
                                            style=""
                                        >
                                            <div
                                                class="progress-bar h-100 rounded-pill"
                                                role="progressbar"
                                                style="width: {{ $total_confirmed_order_percentage }}%; background-color: #24BF85;"
                                                aria-valuenow="20"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <img
                                        src="{{ static_asset('assets/img/order-processed.svg') }}"
                                        class="flex-shrink-0"
                                        alt="Order Processed"
                                    />
                                    <div class="flex-grow-1 ml-2">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                            style="gap: 12px;"
                                        >
                                            <div class="d-flex align-items-center">
                                                <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Processed') }} </h3>
                                                <span
                                                    class="fs-13 fw-400 text-secondary">({{ $total_shipped_order_percentage }}
                                                    %)</span>
                                            </div>
                                            <strong class="fs-13 fw-700 text-dark"> {{ $total_shipped_order }}</strong>
                                        </div>
                                        <div
                                            class="progress h-5px mt-1"
                                            style=""
                                        >
                                            <div
                                                class="progress-bar h-100 rounded-pill"
                                                role="progressbar"
                                                style="width: {{ $total_shipped_order_percentage }}%; background-color: #4E9FF4;"
                                                aria-valuenow="10"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <img
                                        src="{{ static_asset('assets/img/order-shipped.svg') }}"
                                        class="flex-shrink-0"
                                        alt="Order Shipped"
                                    />
                                    <div class="flex-grow-1 ml-2">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                            style="gap: 12px;"
                                        >
                                            <div class="d-flex align-items-center">
                                                <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Shipped') }} </h3>
                                                <span
                                                    class="fs-13 fw-400 text-secondary">({{ $total_picked_up_order_percentage }}
                                                    %)</span>
                                            </div>
                                            <strong class="fs-13 fw-700 text-dark"> {{ $total_picked_up_order }}</strong>
                                        </div>
                                        <div
                                            class="progress h-5px mt-1"
                                            style=""
                                        >
                                            <div
                                                class="progress-bar h-100 rounded-pill"
                                                role="progressbar"
                                                style="width: {{ $total_picked_up_order_percentage }}%; background-color: #65D3E6;"
                                                aria-valuenow="60"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <img
                                        src="{{ static_asset('assets/img/order-delivered.svg') }}"
                                        class="flex-shrink-0"
                                        alt="Order Delivered"
                                    />
                                    <div class="flex-grow-1 ml-2">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                            style="gap: 12px;"
                                        >
                                            <div class="d-flex align-items-center">
                                                <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Delivered') }} </h3>
                                                <span
                                                    class="fs-13 fw-400 text-secondary">({{ $total_delivered_order_percentage }}
                                                    %)</span>
                                            </div>
                                            <strong class="fs-13 fw-700 text-dark"> {{ $total_delivered_order }}</strong>
                                        </div>
                                        <div
                                            class="progress h-5px mt-1"
                                            style=""
                                        >
                                            <div
                                                class="progress-bar h-100 rounded-pill"
                                                role="progressbar"
                                                style="width: {{ $total_delivered_order_percentage }}%; background-color: #FFBB32;"
                                                aria-valuenow="70"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <img
                                        src="{{ static_asset('assets/img/order-cancelled.svg') }}"
                                        class="flex-shrink-0"
                                        alt="Order Cancelled"
                                    />
                                    <div class="flex-grow-1 ml-2">
                                        <div
                                            class="d-flex align-items-center justify-content-between"
                                            style="gap: 12px;"
                                        >
                                            <div class="d-flex align-items-center">
                                                <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Cancelled') }} </h3>
                                                <span
                                                    class="fs-13 fw-400 text-secondary">({{ $total_cancelled_order_percentage }}%)</span>
                                            </div>
                                            <strong class="fs-13 fw-700 text-dark"> {{ $total_cancelled_order }}</strong>
                                        </div>
                                        <div
                                            class="progress h-5px mt-1"
                                            style=""
                                        >
                                            <div
                                                class="progress-bar h-100 rounded-pill"
                                                role="progressbar"
                                                style="width: {{ $total_cancelled_order_percentage }}%; background-color: #F76464;"
                                                aria-valuenow="40"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('view_all_orders')
                        <div class="mt-md-0 px-2rem mt-4">
                            <a
                                href="{{ route('all_orders.index') }}"
                                class="fs-13 fw-400 text-blue hov-opacity-80 has-transition"
                            >
                                {{ translate('View All Orders') }}
                                <i class="las la-arrow-right fs-14"></i>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="col-lg-6">
                <div
                    class="dashboard-box mb-2rem p-2rem overflow-hidden bg-white"
                    style="height: 510px;"
                >
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h2 class="fs-16 fw-700 text-soft-dark mb-2">{{ translate('Top Category & Products') }}</h2>
                            <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('By Sales') }}</h4>
                        </div>
                        <ul
                            class="nav nav-tabs dashboard-tab dashboard-tab-warning border-0"
                            role="tablist"
                            aria-orientation="vertical"
                        >
                            <li class="nav-item">
                                <a
                                    class="nav-link top_category_products_tab rounded-pill"
                                    id="today-tab"
                                    href="#today"
                                    data-toggle="tab"
                                    data-target="DAY"
                                    type="button"
                                    role="tab"
                                    aria-controls="today"
                                    aria-selected="true"
                                >
                                    {{ translate('Today') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link top_category_products_tab rounded-pill"
                                    id="week-tab"
                                    href="#week"
                                    data-toggle="tab"
                                    data-target="WEEK"
                                    type="button"
                                    role="tab"
                                    aria-controls="week"
                                    aria-selected="true"
                                >
                                    {{ translate('Week') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link top_category_products_tab rounded-pill"
                                    id="month-tab"
                                    href="#month"
                                    data-toggle="tab"
                                    data-target="MONTH"
                                    type="button"
                                    role="tab"
                                    aria-controls="month"
                                    aria-selected="true"
                                >
                                    {{ translate('Month') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link top_category_products_tab rounded-pill active"
                                    id="all-tab"
                                    href="#all"
                                    data-toggle="tab"
                                    data-target="all"
                                    type="button"
                                    role="tab"
                                    aria-controls="all"
                                    aria-selected="true"
                                >
                                    {{ translate('All') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="top-category-products-section"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="dashboard-box mb-2rem h-xl-475px overflow-hidden bg-white">
                    <div
                        class="d-flex align-items-start justify-content-between flex-wrap"
                        style="gap: 16px;"
                    >
                        <h2 class="fs-16 fw-700 text-soft-dark mb-0">{{ translate('In-house Store') }}</h2>
                        <div
                            class="d-flex align-items-center rounded-pill ihouse-store-fulfillment-rate px-3 py-2"
                            style="background-color: rgba(11, 128, 253, 0.2);"
                        >
                            <span class="d-block w-10px h-10px bg-blue border-blue rounded-circle"></span>
                            <span class="fs-13 fw-500 text-blue pl-2 pr-3">{{ translate('Fulfilment Rate') }}</span>
                            <span class="fs-13 fw-700 text-blue">{{ $total_inhouse_delivered_order_percentage }}%</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="fs-20 fw-bold text-dark mb-1">
                            {{ single_price($total_inhouse_sale) }}
                        </h1>
                        <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('Total Sales') }}</h4>
                    </div>
                    <div class="row gutters-5 mt-4">
                        <div class="col-lg-4">
                            <div class="card card-no-shadow bg-soft-light h-lg-130px h-xl-auto border-0">
                                <div class="card-body">
                                    <h1 class="fs-20 fw-bold text-dark">
                                        {{ $total_inhouse_order }}
                                    </h1>
                                    <p class="fs-13 fw-500 text-dark mb-0">{{ translate('Total Orders') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-no-shadow bg-light h-lg-130px h-xl-auto border-0">
                                <div class="card-body">
                                    <h1 class="fs-20 fw-bold text-dark">
                                        {{ $total_inhouse_products }}
                                    </h1>
                                    <p class="fs-13 fw-500 text-dark mb-0">{{ translate('In-house product') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-no-shadow bg-light h-lg-130px h-xl-auto border-0">
                                <div class="card-body">
                                    <h1 class="fs-20 fw-bold text-dark">
                                        {{ number_format($inhouse_product_rating, 2) }}
                                    </h1>
                                    <p class="fs-13 fw-500 text-dark mb-0">{{ translate('Ratings') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters-12 mt-3">
                        <div class="col-md-6">
                            <div
                                class="d-flex flex-column"
                                style="gap: 19px"
                            >
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Pending Order') }} </h3>
                                            <span
                                                class="fs-13 fw-400 text-secondary">({{ $total_inhouse_pending_order_percentage }}%)</span>
                                        </div>
                                        <strong class="fs-13 fw-700 text-dark"> {{ $total_inhouse_pending_order }}</strong>
                                    </div>
                                    <div
                                        class="progress h-5px mt-2"
                                        style=""
                                    >
                                        <div
                                            class="progress-bar h-100 rounded-pill"
                                            role="progressbar"
                                            style="width: {{ $total_inhouse_pending_order_percentage }}%; background-color: #F74396;"
                                            aria-valuenow="30"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Confirmed Order') }} </h3>
                                            <span
                                                class="fs-13 fw-400 text-secondary">({{ $total_inhouse_confirmed_order_percentage }}%)</span>
                                        </div>
                                        <strong class="fs-13 fw-700 text-dark"> {{ $total_inhouse_confirmed_order }}</strong>
                                    </div>
                                    <div
                                        class="progress h-5px mt-2"
                                        style=""
                                    >
                                        <div
                                            class="progress-bar h-100 rounded-pill"
                                            role="progressbar"
                                            style="width: {{ $total_inhouse_confirmed_order_percentage }}%; background-color: #24BF85;"
                                            aria-valuenow="20"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Processed') }} </h3>
                                            <span
                                                class="fs-13 fw-400 text-secondary">({{ $total_inhouse_shipped_order_percentage }}%)</span>
                                        </div>
                                        <strong class="fs-13 fw-700 text-dark"> {{ $total_inhouse_shipped_order }}</strong>
                                    </div>
                                    <div
                                        class="progress h-5px mt-2"
                                        style=""
                                    >
                                        <div
                                            class="progress-bar h-100 rounded-pill"
                                            role="progressbar"
                                            style="width: {{ $total_inhouse_shipped_order_percentage }}%; background-color: #4E9FF4;"
                                            aria-valuenow="20"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div
                                class="d-flex flex-column"
                                style="gap: 19px"
                            >
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Shipped') }} </h3>
                                            <span
                                                class="fs-13 fw-400 text-secondary">({{ $total_inhouse_picked_up_order_percentage }}%)</span>
                                        </div>
                                        <strong class="fs-13 fw-700 text-dark"> {{ $total_inhouse_picked_up_order }}</strong>
                                    </div>
                                    <div
                                        class="progress h-5px mt-2"
                                        style=""
                                    >
                                        <div
                                            class="progress-bar h-100 rounded-pill"
                                            role="progressbar"
                                            style="width: {{ $total_inhouse_picked_up_order_percentage }}%; background-color: #65D3E6;"
                                            aria-valuenow="15"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Delivered') }} </h3>
                                            <span
                                                class="fs-13 fw-400 text-secondary">({{ $total_inhouse_delivered_order_percentage }}%)</span>
                                        </div>
                                        <strong class="fs-13 fw-700 text-dark"> {{ $total_inhouse_delivered_order }}</strong>
                                    </div>
                                    <div
                                        class="progress h-5px mt-2"
                                        style=""
                                    >
                                        <div
                                            class="progress-bar h-100 rounded-pill"
                                            role="progressbar"
                                            style="width: {{ $total_inhouse_delivered_order_percentage }}%; background-color: #FFBB32;"
                                            aria-valuenow="35"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <h3 class="fs-13 fw-500 mb-0 mr-3"> {{ translate('Order Cancelled') }} </h3>
                                            <span
                                                class="fs-13 fw-400 text-secondary">({{ $total_inhouse_cancelled_order_percentage }}%)</span>
                                        </div>
                                        <strong class="fs-13 fw-700 text-dark"> {{ $total_inhouse_cancelled_order }}</strong>
                                    </div>
                                    <div
                                        class="progress h-5px mt-2"
                                        style=""
                                    >
                                        <div
                                            class="progress-bar h-100 rounded-pill"
                                            role="progressbar"
                                            style="width: {{ $total_inhouse_cancelled_order_percentage }}%; background-color: #F76464;"
                                            aria-valuenow="10"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('view_inhouse_orders')
                        <div class="mt-4">
                            <a
                                href="{{ route('inhouse_orders.index') }}"
                                class="fs-13 fw-400 text-blue hov-opacity-80 has-transition"
                            >
                                {{ translate('All In-house Orders') }}
                                <i class="las la-arrow-right fs-14"></i>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row gutters-16">
                    <div class="col-sm-6">
                        <div
                            class="dashboard-box mb-2rem overflow-hidden px-0"
                            style="height: 474px;"
                        >
                            <div class="px-2rem mb-3">
                                <h2 class="fs-16 fw-600 text-primary mb-2">{{ translate('In-house Top Category') }}
                                </h2>
                                <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('By Sales') }}</h4>
                            </div>
                            <ul
                                class="nav nav-tabs dashboard-tab dashboard-tab-primary px-2rem mb-3 border-0"
                                role="tablist"
                                aria-orientation="vertical"
                            >
                                <li class="nav-item">
                                    <a
                                        class="nav-link active inhouse_top_categories rounded-pill"
                                        id="all-tab"
                                        href="#all"
                                        data-toggle="tab"
                                        data-target="all"
                                        type="button"
                                        role="tab"
                                        aria-controls="all"
                                        aria-selected="true"
                                    >
                                        {{ translate('All') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link inhouse_top_categories rounded-pill"
                                        id="today-tab"
                                        href="#today"
                                        data-toggle="tab"
                                        data-target="DAY"
                                        type="button"
                                        role="tab"
                                        aria-controls="today"
                                        aria-selected="true"
                                    >
                                        {{ translate('Today') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link inhouse_top_categories rounded-pill"
                                        id="week-tab"
                                        href="#week"
                                        data-toggle="tab"
                                        data-target="WEEK"
                                        type="button"
                                        role="tab"
                                        aria-controls="week"
                                        aria-selected="true"
                                    >
                                        {{ translate('Week') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link inhouse_top_categories rounded-pill"
                                        id="month-tab"
                                        href="#month"
                                        data-toggle="tab"
                                        data-target="MONTH"
                                        type="button"
                                        role="tab"
                                        aria-controls="month"
                                        aria-selected="true"
                                    >
                                        {{ translate('Month') }}
                                    </a>
                                </li>
                            </ul>
                            <div
                                class="h-290px h-lg-280px h-xxl-310px c-scrollbar-light px-2rem mt-4"
                                style="overflow-x: hidden;"
                                id="inhouse-top-categories"
                            >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div
                            class="dashboard-box mb-2rem overflow-hidden px-0"
                            style="height: 474px;"
                        >
                            <div class="px-2rem mb-3">
                                <h2 class="fs-16 fw-600 text-danger mb-2">{{ translate('In-house Top Brands') }}</h2>
                                <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('By Sales') }}</h4>
                            </div>
                            <ul
                                class="nav nav-tabs dashboard-tab dashboard-tab-danger px-2rem mb-3 border-0"
                                role="tablist"
                                aria-orientation="vertical"
                            >
                                <li class="nav-item">
                                    <a
                                        class="nav-link active inhouse_top_brands rounded-pill"
                                        id="all-tab"
                                        href="#all"
                                        data-toggle="tab"
                                        data-target="all"
                                        type="button"
                                        role="tab"
                                        aria-controls="all"
                                        aria-selected="true"
                                    >
                                        {{ translate('All') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link inhouse_top_brands rounded-pill"
                                        id="today-tab"
                                        href="#today"
                                        data-toggle="tab"
                                        data-target="DAY"
                                        type="button"
                                        role="tab"
                                        aria-controls="today"
                                        aria-selected="true"
                                    >
                                        {{ translate('Today') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link inhouse_top_brands rounded-pill"
                                        id="week-tab"
                                        href="#week"
                                        data-toggle="tab"
                                        data-target="WEEK"
                                        type="button"
                                        role="tab"
                                        aria-controls="week"
                                        aria-selected="true"
                                    >
                                        {{ translate('Week') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link inhouse_top_brands rounded-pill"
                                        id="month-tab"
                                        href="#month"
                                        data-toggle="tab"
                                        data-target="MONTH"
                                        type="button"
                                        role="tab"
                                        aria-controls="month"
                                        aria-selected="true"
                                    >
                                        {{ translate('Month') }}
                                    </a>
                                </li>
                            </ul>
                            <div
                                class="h-290px h-lg-280px h-xxl-310px c-scrollbar-light px-2rem mt-4"
                                style="overflow-x: hidden;"
                                id="inhouse-top-brands"
                            >

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (get_setting('vendor_system_activation') == 1)
                <div class="col-lg-6">
                    <div
                        class="dashboard-box mb-2rem p-2rem overflow-hidden bg-white"
                        style="height: 474px;"
                    >
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h2 class="fs-16 fw-600 text-soft-dark mb-2">{{ translate('Top Seller & Products') }}</h2>
                                <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('By Sales') }}</h4>
                            </div>
                            <ul
                                class="nav nav-tabs dashboard-tab dashboard-tab-warning border-0"
                                role="tablist"
                                aria-orientation="vertical"
                            >
                                <li class="nav-item">
                                    <a
                                        class="nav-link top_sellers_products_tab rounded-pill"
                                        id="today-tab"
                                        href="#today"
                                        data-toggle="tab"
                                        data-target="DAY"
                                        type="button"
                                        role="tab"
                                        aria-controls="today"
                                        aria-selected="true"
                                    >
                                        {{ translate('Today') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link top_sellers_products_tab rounded-pill"
                                        id="week-tab"
                                        href="#week"
                                        data-toggle="tab"
                                        data-target="WEEK"
                                        type="button"
                                        role="tab"
                                        aria-controls="week"
                                        aria-selected="true"
                                    >
                                        {{ translate('Week') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link top_sellers_products_tab rounded-pill"
                                        id="month-tab"
                                        href="#month"
                                        data-toggle="tab"
                                        data-target="MONTH"
                                        type="button"
                                        role="tab"
                                        aria-controls="month"
                                        aria-selected="true"
                                    >
                                        {{ translate('Month') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link top_sellers_products_tab rounded-pill active"
                                        id="all-tab"
                                        href="#all"
                                        data-toggle="tab"
                                        data-target="all"
                                        type="button"
                                        role="tab"
                                        aria-controls="all"
                                        aria-selected="true"
                                    >
                                        {{ translate('All') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="top-sellers-products-section"></div>
                    </div>
                </div>
            @endif
            <div class="col-lg-6">
                <div
                    class="dashboard-box mb-2rem p-2rem overflow-hidden bg-white"
                    style="height: 474px;"
                >
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h2 class="fs-16 fw-600 text-soft-dark mb-2">{{ translate('Top Brands & Products') }}</h2>
                            <h4 class="fs-13 fw-400 text-reset mb-0">{{ translate('By Sales') }}</h4>
                        </div>
                        <ul
                            class="nav nav-tabs dashboard-tab dashboard-tab-warning border-0"
                            role="tablist"
                            aria-orientation="vertical"
                        >
                            <li class="nav-item">
                                <a
                                    class="nav-link top_brands_products_tab rounded-pill"
                                    id="today-tab"
                                    href="#today"
                                    data-toggle="tab"
                                    data-target="DAY"
                                    type="button"
                                    role="tab"
                                    aria-controls="today"
                                    aria-selected="true"
                                >
                                    {{ translate('Today') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link top_brands_products_tab rounded-pill"
                                    id="week-tab"
                                    href="#week"
                                    data-toggle="tab"
                                    data-target="WEEK"
                                    type="button"
                                    role="tab"
                                    aria-controls="week"
                                    aria-selected="true"
                                >
                                    {{ translate('Week') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link top_brands_products_tab rounded-pill"
                                    id="month-tab"
                                    href="#month"
                                    data-toggle="tab"
                                    data-target="MONTH"
                                    type="button"
                                    role="tab"
                                    aria-controls="month"
                                    aria-selected="true"
                                >
                                    {{ translate('Month') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link top_brands_products_tab rounded-pill active"
                                    id="all-tab"
                                    href="#all"
                                    data-toggle="tab"
                                    data-target="all"
                                    type="button"
                                    role="tab"
                                    aria-controls="all"
                                    aria-selected="true"
                                >
                                    {{ translate('All') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="top-brands-products-section"></div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    @include('backend.dashboard.dashboard_js')
    <script type="text/javascript">
        AIZ.plugins.chart('#graph-3', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($sales_stat as $month => $row)
                        "{{ $month }}",
                    @endforeach
                ],
                datasets: [{
                    label: "{{ translate('Yearly Sales') }}",
                    data: [
                        @foreach ($sales_stat as $row)
                            {{ $row[0]->total ?? 0 }},
                        @endforeach
                    ],
                    backgroundColor: '#0B80FD',
                    borderWidth: 0,
                    borderRadius: 2,
                    borderSkipped: 'bottom',
                    barThickness: 8,
                    maxBarThickness: 8,
                    minBarLength: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        align: 'start',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            font: {
                                family: 'sans-serif',
                                size: 13
                            },
                            color: "#6C757D"
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    x: {
                        display: false,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: false,
                        grid: {
                            display: false
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        AIZ.plugins.chart('#graph-2', {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach ($payment_type_wise_inhouse_sale as $row)
                        "{{ ucwords(str_replace('_', ' ', $row->payment_type)) }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Total Sales',
                    data: [
                        @foreach ($payment_type_wise_inhouse_sale as $row)
                            {{ $row->total_amount }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            usePointStyle: true,
                            fontSize: 11,
                            boxWidth: 30
                        },
                    },
                }
            }
        })

        function top_category_products(category_id, e) {
            $(".top_category_products").removeClass("active");
            e.classList.add("active");
            $(".top_category_product_table").removeClass("show");
            $("#top_category_product_table_" + category_id).addClass("show");
        }

        function top_sellers_products(seller_id, e) {
            $(".top_sellers_products").removeClass("active");
            e.classList.add("active");
            $(".top_sellers_product_table").removeClass("show");
            $("#top_sellers_product_table_" + seller_id).addClass("show");
        }

        function top_brands_products(brand_id, e) {
            $(".top_brands_products").removeClass("active");
            e.classList.add("active");
            $(".top_brands_product_table").removeClass("show");
            $("#top_brands_product_table_" + brand_id).addClass("show");
        }
    </script>
@endsection
