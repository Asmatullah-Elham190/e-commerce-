<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left" style="background-color: {{ Auth::user()->shop->navbar_bg_color ?? '#5B346C' }}">
        <div class="aiz-side-nav-logo-wrap px-20px">
            <div class="d-block my-3">
                @if (optional(Auth::user()->shop)->logo != null)
                    <img class="mw-100 mb-3" src="{{ uploaded_asset(optional(Auth::user()->shop)->logo) }}"
                        class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100 mb-3" src="{{ uploaded_asset(get_setting('header_logo')) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @endif
                <h3 class="fs-16 m-0" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                    {{ optional(Auth::user()->shop)->name }}
                </h3>
                <p class="" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                    {{ Auth::user()->email }}
                </p>
            </div>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-3 mb-3">
                <div class="px-2 px-lg-3 rounded-2 d-flex align-items-center justify-content-between seller-sidenav-search"
                    @if (Auth::user()->shop->navbar_text_color == 'black')
                        style="border: 1px solid rgba(0, 0, 0, 0.3); color: {{ Auth::user()->shop->navbar_text_color }};"
                    @else
                        style="border: 1px solid rgba(255, 255, 255, 0.3);  color: {{ Auth::user()->shop->navbar_text_color }};"
                    @endif>
                    <input class="px-0 form-control bg-transparent border-0 flex-grow-1 seller-search-menu-placeholder"
                        type="text" name="" placeholder="{{ translate('Search in menu') }}" id="menu-search"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }};"
                        onkeyup="menuSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="16" height="16"
                        viewBox="0 0 16 16">
                        <path id="search_FILL0_wght200_GRAD0_opsz20"
                            d="M176.921-769.231l6.255-6.255a5.991,5.991,0,0,0,1.733.949,5.687,5.687,0,0,0,1.885.329,5.317,5.317,0,0,0,3.9-1.608,5.31,5.31,0,0,0,1.609-3.9,5.322,5.322,0,0,0-1.608-3.9,5.306,5.306,0,0,0-3.9-1.611,5.321,5.321,0,0,0-3.9,1.609,5.311,5.311,0,0,0-1.611,3.9,5.554,5.554,0,0,0,.35,1.946,6.044,6.044,0,0,0,.929,1.672l-6.255,6.255Zm9.874-5.82a4.51,4.51,0,0,1-3.317-1.352,4.51,4.51,0,0,1-1.352-3.317,4.51,4.51,0,0,1,1.352-3.317,4.51,4.51,0,0,1,3.317-1.352,4.51,4.51,0,0,1,3.317,1.352,4.51,4.51,0,0,1,1.352,3.317,4.51,4.51,0,0,1-1.352,3.317A4.51,4.51,0,0,1,186.8-775.051Z"
                            transform="translate(-176.307 785.231)"
                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" opacity="0.5" />
                    </svg>

                </div>
            </div>
            <ul class="aiz-side-nav-list" id="search-menu"></ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.dashboard') }}" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="_3d6902ec768df53cd9e274ca8a57e401"
                                    data-name="3d6902ec768df53cd9e274ca8a57e401"
                                    d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z"
                                    transform="translate(-2 -2)" fill-rule="evenodd"
                                    fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Products') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 13.714">
                                <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -4)">
                                    <path id="Path_40719" data-name="Path 40719"
                                        d="M17.429,4H2.571A.571.571,0,0,0,2,4.571V8a.571.571,0,0,0,.571.571h.571v8.571a.571.571,0,0,0,.571.571H16.286a.571.571,0,0,0,.571-.571V8.571h.571A.571.571,0,0,0,18,8V4.571A.571.571,0,0,0,17.429,4ZM15.714,16.571H4.286v-8H15.714Zm1.143-9.143H3.143V5.143H16.857Z"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    <path id="Path_40720" data-name="Path 40720"
                                        d="M12.571,15.143H16A.571.571,0,0,0,16,14H12.571a.571.571,0,0,0,0,1.143Z"
                                        transform="translate(-4.286 -4.286)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Product Management') }}</span>
                        <span class="aiz-side-nav-arrow"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Products')}}</span>
                                <span class="aiz-side-nav-arrow"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.products') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.products', 'seller.products.create', 'seller.products.edit', 'seller.digitalproducts.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('All Products') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.products.create') }}" class="aiz-side-nav-link"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                        <span class="aiz-side-nav-text">{{ translate('Add New Product') }}</span>
                                    </a>
                                </li>
                                @if (get_setting('digital_product_manage_by_seller') == 1)
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller.digitalproducts.create') }}"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.digitalproducts', 'seller.digitalproducts.create']) }}">
                                            <span
                                                class="aiz-side-nav-text">{{ translate('Add New Digital Product') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Product Setup')}}</span>
                                <span class="aiz-side-nav-arrow"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="#" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Notes')}}</span>
                                        <span class="aiz-side-nav-arrow"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-4">
                                        @if(get_setting('seller_can_add_note'))
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link" href="{{route('seller.note.create')}}"
                                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                                    <span class="aiz-side-nav-text">{{translate('Add New Note')}}</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="aiz-side-nav-item">
                                            <a href="{{route('seller.note.index')}}"
                                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.note.edit']) }}">
                                                <span class="aiz-side-nav-text">{{translate('Note List')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.size-charts.index') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.size-charts.index', 'seller.size-charts.edit','seller.size-charts.create']) }}">
                                        <span
                                            class="aiz-side-nav-text">{{ translate('Size Chart') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Product Operation')}}</span>
                                <span class="aiz-side-nav-arrow"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.product_bulk_upload.index') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['product_bulk_upload.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Bulk Upload') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.product-reviews') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.product-reviews', 'seller.detail-reviews']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Reviews') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('seller.custom_label.index')}}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.custom_label.edit', 'seller.custom_label.create'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Custom Label')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @if(addon_is_activated('auction') && get_setting('seller_auction_product') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 15.964 16">
                                    <path id="_608087065a4e8d47000761280c315020"
                                        data-name="608087065a4e8d47000761280c315020"
                                        d="M4.993,20.456a.456.456,0,0,0,.456.456h8.389a.456.456,0,0,0,.456-.456V19.009a1.256,1.256,0,0,0-1.254-1.254h-.2V16.32a.456.456,0,0,0-.456-.456H6.9a.456.456,0,0,0-.456.456v1.435h-.2a1.255,1.255,0,0,0-1.254,1.254Zm2.363-3.68H11.93v.979H7.356ZM5.905,19.009a.342.342,0,0,1,.342-.342H13.04a.342.342,0,0,1,.342.342V20H5.905Zm13.717-1.79a1.405,1.405,0,0,0,1.334-1.4,1.411,1.411,0,0,0-.461-1.042l-4.466-4.009L17.6,9.031a.831.831,0,0,0-.06-1.172L14.513,5.127a.816.816,0,0,0-.6-.213.824.824,0,0,0-.574.272L8.27,10.8a.83.83,0,0,0,.059,1.173L11.354,14.7a.83.83,0,0,0,1.172-.06l1.622-1.795,4.464,4.008a1.392,1.392,0,0,0,1.011.361ZM13.779,11.9l0,0,0,0L11.9,13.972,9,11.35l4.961-5.492,2.9,2.622L13.779,11.9Zm.981.275.658-.728,4.466,4.008a.492.492,0,1,1-.661.728Z"
                                        transform="translate(-4.993 -4.912)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Auction Products') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_product_create.seller') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Auction Product') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_products.seller.index') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['auction_products.seller.index', 'auction_product_create.seller', 'auction_product_edit.seller', 'product_bids.seller']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Auction Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('auction_products_orders.seller') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['auction_products_orders.seller']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Auction Product Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(addon_is_activated('wholesale') && get_setting('seller_wholesale_product') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Union_48" data-name="Union 48"
                                        d="M1.2,14.236a1.762,1.762,0,0,1,1.2-1.657V2c0-.325-.268-.823-.6-.823H.6C.268,1.176,0,1.031,0,.7V.647A.645.645,0,0,1,.6,0H2.4A1.407,1.407,0,0,1,3.6,1.41v9.65h10a1.4,1.4,0,0,1,1.2,1.518,1.757,1.757,0,0,1,1.165,2.01,1.8,1.8,0,0,1-3.566-.353,1.761,1.761,0,0,1,1.2-1.656v-.342H3.6v.342a1.754,1.754,0,0,1,1.165,2.01A1.784,1.784,0,0,1,3.338,15.97,1.927,1.927,0,0,1,3,16,1.782,1.782,0,0,1,1.2,14.236Zm12.4,0a.594.594,0,0,0,.6.588h0a.6.6,0,0,0,.6-.589c0-.389-.272-.5-.6-.617C13.872,13.732,13.6,13.846,13.6,14.235Zm-11.2,0a.6.6,0,0,0,.6.588H3a.6.6,0,0,0,.6-.589c0-.389-.272-.5-.6-.617C2.671,13.732,2.4,13.846,2.4,14.235Zm4.216-4.158A1.615,1.615,0,0,1,5,8.462V6.692A1.615,1.615,0,0,1,6.615,5.077h5.77A1.616,1.616,0,0,1,14,6.692V8.462a1.616,1.616,0,0,1-1.616,1.615ZM6.234,6.311a.542.542,0,0,0-.157.382V8.462A.538.538,0,0,0,6.615,9h5.77a.538.538,0,0,0,.538-.538V6.692a.536.536,0,0,0-.538-.538H6.612A.535.535,0,0,0,6.234,6.311ZM5.473,3.527A1.617,1.617,0,0,1,5,2.385V1.616A1.615,1.615,0,0,1,6.615,0H9.384A1.616,1.616,0,0,1,11,1.616v.769A1.615,1.615,0,0,1,9.384,4H6.612A1.614,1.614,0,0,1,5.473,3.527Zm.761-2.293a.542.542,0,0,0-.157.382v.769a.538.538,0,0,0,.538.538H9.384a.538.538,0,0,0,.539-.538V1.616a.542.542,0,0,0-.157-.382.536.536,0,0,0-.382-.157H6.612A.535.535,0,0,0,6.234,1.234Z"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Wholesale Products') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wholesale_product_create.seller') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Wholesale Product') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.wholesale_products_list') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_create.seller', 'wholesale_product_edit.seller']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Wholesale Products') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Orders & Sales') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 15.997 16">
                                <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -1.994)">
                                    <path id="Path_40726" data-name="Path 40726"
                                        d="M4.857,12.571H3.714A1.714,1.714,0,0,0,2,14.285V20.57a1.714,1.714,0,0,0,1.714,1.714H4.857A1.714,1.714,0,0,0,6.571,20.57V14.285a1.714,1.714,0,0,0-1.714-1.714Zm.571,8a.571.571,0,0,1-.571.571H3.714a.571.571,0,0,1-.571-.571V14.285a.571.571,0,0,1,.571-.571H4.857a.571.571,0,0,1,.571.571Zm5.142-6.284H9.427A1.714,1.714,0,0,0,7.713,16V20.57a1.714,1.714,0,0,0,1.714,1.714H10.57a1.714,1.714,0,0,0,1.714-1.714V16A1.714,1.714,0,0,0,10.57,14.285Zm.571,6.284a.571.571,0,0,1-.571.571H9.427a.571.571,0,0,1-.571-.571V16a.571.571,0,0,1,.571-.571H10.57a.571.571,0,0,1,.571.571ZM16.283,12H15.14a1.714,1.714,0,0,0-1.714,1.714V20.57a1.714,1.714,0,0,0,1.714,1.714h1.143A1.714,1.714,0,0,0,18,20.57V13.714A1.714,1.714,0,0,0,16.283,12Zm.571,8.57a.571.571,0,0,1-.571.571H15.14a.571.571,0,0,1-.571-.571V13.714a.571.571,0,0,1,.571-.571h1.143a.571.571,0,0,1,.571.571Z"
                                        transform="translate(0 -4.289)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    <path id="Path_40727" data-name="Path 40727"
                                        d="M17.947,2.548a.571.571,0,0,0-.366-.24l-1.588-.3a.571.571,0,1,0-.213,1.122l.093.018L11.233,5.932l-5.45-2.18a.572.572,0,1,0-.424,1.062L11.072,7.1a.571.571,0,0,0,.506-.041L16.68,4l-.067.354a.571.571,0,0,0,.457.668.579.579,0,0,0,.107.01.571.571,0,0,0,.56-.465l.3-1.588A.568.568,0,0,0,17.947,2.548Z"
                                        transform="translate(-1.286)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Sales')}}</span>
                        <span class="aiz-side-nav-arrow"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.orders.index') }}"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.orders.index', 'seller.orders.show']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @if (addon_is_activated('pos_system') && get_setting('pos_activation_for_seller') != null && get_setting('pos_activation_for_seller') != 0)
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 13.79 16">
                                    <g id="_371925cdd3f531725a9fa8f3ebf8fe9e" data-name="371925cdd3f531725a9fa8f3ebf8fe9e"
                                        transform="translate(-2.26 0)">
                                        <path id="Path_40673" data-name="Path 40673"
                                            d="M10.69,7H3.26a1.025,1.025,0,0,0-1,1V18.45a1.03,1.03,0,0,0,1,1.05h7.43a1.03,1.03,0,0,0,1.03-1.03V8A1.025,1.025,0,0,0,10.69,7ZM4.94,17.86H3.995v-.95H4.94Zm0-2.355H3.995v-.95H4.94Zm0-2.355H3.995V12.2H4.94Zm2.5,4.71H6.5v-.95h.955Zm0-2.355H6.5v-.95h.955Zm0-2.355H6.5V12.2h.955Zm2.5,4.71H8.99v-.95h.95Zm0-2.355H8.99v-.95h.95Zm0-2.355H8.99V12.2h.95Zm.325-3a.17.17,0,0,1-.165.17H3.835a.17.17,0,0,1-.165-.17V8.795a.165.165,0,0,1,.165-.165H10.13a.165.165,0,0,1,.165.165Zm5.09-1.45H15.13v9.09h.25a.67.67,0,0,0,.67-.67V9.375a.67.67,0,0,0-.695-.675Z"
                                            transform="translate(0 -3.5)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        <rect id="Rectangle_20842" data-name="Rectangle 20842" width="1.465" height="9.095"
                                            transform="translate(12.185 5.2)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        <rect id="Rectangle_20843" data-name="Rectangle 20843" width="0.63" height="9.095"
                                            transform="translate(14.06 5.2)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        <path id="Path_40674" data-name="Path 40674"
                                            d="M13.895.895a.89.89,0,0,0-.26-.635A.91.91,0,0,0,13,0a.895.895,0,0,0-.91.895v.53h1.79Zm-2.2,0a.76.76,0,0,1,0-.145.68.68,0,0,1,0-.1h.01A.5.5,0,0,1,11.755.5.43.43,0,0,1,11.79.4a1.2,1.2,0,0,1,.145-.26.5.5,0,0,1,.04-.055L12.045,0H7.995A.815.815,0,0,0,7.18.81V3.03h4.5Z"
                                            transform="translate(-2.46)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('POS System') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('poin-of-sales.seller_index') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.seller_index']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('POS Manager') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('pos.configuration') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{ translate('POS Configuration') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('seller.pos.orders')}}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{translate('POS Orders')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('seller.pos.products')}}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{translate('POS Products')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (addon_is_activated('refund_request'))
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="_4436b8ef9250481406399210799cb7f1"
                                        data-name="4436b8ef9250481406399210799cb7f1"
                                        d="M19.25,11.25a8.031,8.031,0,0,1-15.995,1,.688.688,0,0,1,1.365-.169A6.643,6.643,0,1,0,7.112,6.039h.866a.686.686,0,1,1,0,1.371H5.384A.687.687,0,0,1,4.7,6.724V4.138a.688.688,0,0,1,1.376,0v.987A8.024,8.024,0,0,1,19.25,11.25ZM11.278,6.907a.687.687,0,0,0-.688.686v.253a2.053,2.053,0,0,0-1.824,2.247,2.146,2.146,0,0,0,2.175,1.842h.8a.686.686,0,1,1,0,1.371h-1.6a.686.686,0,1,0,0,1.371h.458v.229a.688.688,0,0,0,1.376,0v-.26a2.113,2.113,0,0,0,1.824-1.811,2.062,2.062,0,0,0-2.053-2.272h-.917a.686.686,0,1,1,0-1.371h1.609a.686.686,0,1,0,0-1.371h-.462V7.593A.687.687,0,0,0,11.278,6.907Z"
                                        transform="translate(-3.25 -3.25)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Refunds') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.vendor_refund_request') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.vendor_refund_request', 'reason_show']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Received Refund Request') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.refund_configuration') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.refund_configuration']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Refund Configuration') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (addon_is_activated('preorder') && (get_setting('seller_preorder_product') == 1))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16.002">
                                    <path id="Union_63" data-name="Union 63"
                                        d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z"
                                        transform="translate(-14072 -885.998)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Preorder') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.preorder.dashboard') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{ translate('Preorder Home') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.preorder-product.create') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Preorder Product') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.preorder-product.index') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.preorder-product.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Preorder Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{translate('Orders (Preorder)')}}</span>
                                    <span class="aiz-side-nav-arrow"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller.all_preorder.list') }}"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['seller.preorder-order.show'])}} }}">
                                            <span class="aiz-side-nav-text">{{translate('All Orders')}}</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller.delayed_prepayment_preorders.list') }}"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                            class="aiz-side-nav-link">
                                            <span
                                                class="aiz-side-nav-text">{{translate('Delayed Prepayment Orders')}}</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller.delayed_final_orders.list') }}" class="aiz-side-nav-link"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                            <span class="aiz-side-nav-text">{{translate('Delayed Final Orders')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.preorder-settings') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{translate("Preorder Settings")}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.preorder-commission-history') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text">{{translate("Preorder Commission History")}}</span>
                                </a>
                            </li>
                            @if (get_setting('conversation_system') == 1)
                                <li class="aiz-side-nav-item">
                                    @php
                                        $preorderConversation = get_non_viewed_preorder_conversations();
                                    @endphp
                                    <a href="{{ route('seller.preorder-conversations.index') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.preorder-conversations.index', 'seller.preorder-conversations.show']) }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Product Conversations') }}</span>
                                        @if ($preorderConversation > 0)
                                            <span class="badge badge-danger">({{ $preorderConversation }})</span>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('product_query_activation') == 1)
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.preorder_product_query.index') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['preorder_product_query.index', 'preorder_product_query.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Queries') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.preorder_product_reviews') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.preorder_product_detail_reviews']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Product Reviews') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Finance') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.money_withdraw_requests.index') }}"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.money_withdraw_requests.index']) }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 640 512"
                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                <path
                                    d="M96 96C60.7 96 32 124.7 32 160V352c0 35.3 28.7 64 64 64H544c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H96zm0 32H544c17.7 0 32 14.3 32 32v192c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32zm224 48c-53 0-96 43-96 96s43 96 96 96s96-43 96-96s-43-96-96-96zm0 32c35.3 0 64 28.7 64 64s-28.7 64-64 64s-64-28.7-64-64s28.7-64 64-64zM128 176c0 17.7-14.3 32-32 32v32c35.3 0 64-28.7 64-64H128zm384 0c0 35.3 28.7 64 64 64V208c-17.7 0-32-14.3-32-32H512zM96 304v32c17.7 0 32 14.3 32 32h32c0-35.3-28.7-64-64-64zm480 0c-35.3 0-64 28.7-64 64h32c0-17.7 14.3-32 32-32V304z" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Money Withdraw') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.payments.index') }}"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.payments.index']) }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="16" height="16"
                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                <path
                                    d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Payment History') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.commission-history.index') }}" class="aiz-side-nav-link"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_28285" data-name="Group 28285">
                                    <path id="Path_40728" data-name="Path 40728"
                                        d="M12.406,9.375h-.625v-.84a3.28,3.28,0,0,0,1.406-2.691V4.375h2.344a.469.469,0,0,0,0-.937H13.5a3.594,3.594,0,0,0-7.184.156v.313a.469.469,0,0,0,.313.442v1.5A3.28,3.28,0,0,0,8.031,8.535v.84H7.406a3.605,3.605,0,0,0-2.113.688H1.406a.469.469,0,0,0-.419.259L.049,12.2h0a.466.466,0,0,0-.05.209v3.125A.469.469,0,0,0,.469,16H15.531A.469.469,0,0,0,16,15.531V12.969A3.6,3.6,0,0,0,12.406,9.375ZM9.906.938a2.66,2.66,0,0,1,2.652,2.5h-5.3A2.66,2.66,0,0,1,9.906.938ZM7.562,5.844V4.375H12.25V5.844a2.344,2.344,0,0,1-4.688,0ZM9.906,9.125a3.271,3.271,0,0,0,.938-.137V10a.938.938,0,0,1-1.875,0V8.988A3.27,3.27,0,0,0,9.906,9.125ZM1.7,11H5.554l.469.938h-4.8ZM.937,12.875H6.312v2.188H.937Zm14.125,2.188H7.25V12.406A.466.466,0,0,0,7.2,12.2h0l-.836-1.672a2.638,2.638,0,0,1,1.042-.212h.652a1.875,1.875,0,0,0,3.7,0h.652a2.659,2.659,0,0,1,2.656,2.656Z"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    <path id="Path_40729" data-name="Path 40729"
                                        d="M376.719,405h-1.25a.469.469,0,0,0,0,.938h1.25a.469.469,0,0,0,0-.937Z"
                                        transform="translate(-363.281 -392.344)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Commission') }}</span>
                    </a>
                </li>
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Communication') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_28286" data-name="Group 28286" transform="translate(0)">
                                    <path id="Path_40743" data-name="Path 40743"
                                        d="M16,9.125a3.122,3.122,0,0,0-1.255-2.5,6.9,6.9,0,0,0-1.94-4.6,6.725,6.725,0,0,0-9.61,0,6.9,6.9,0,0,0-1.94,4.6,3.124,3.124,0,0,0,1.87,5.627h1.25A.625.625,0,0,0,5,11.625v-5A.625.625,0,0,0,4.375,6H3.125a3.129,3.129,0,0,0-.569.052,5.487,5.487,0,0,1,10.887,0A3.129,3.129,0,0,0,12.875,6h-1.25A.625.625,0,0,0,11,6.625v5a.625.625,0,0,0,.625.625h.625v.625a1.877,1.877,0,0,1-1.875,1.875H8A.625.625,0,0,0,8,16h2.375A3.129,3.129,0,0,0,13.5,12.875v-.688A3.13,3.13,0,0,0,16,9.125ZM3.75,7.25V11H3.125a1.875,1.875,0,0,1,0-3.75ZM12.875,11H12.25V7.25h.625a1.875,1.875,0,1,1,0,3.75Z"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    <path id="Path_40744" data-name="Path 40744"
                                        d="M197.875,113.25a.626.626,0,0,1,.625.625.618.618,0,0,1-.137.391,4.365,4.365,0,0,0-1.113,2.746v.613a.625.625,0,0,0,1.25,0v-.613a3.186,3.186,0,0,1,.838-1.964A1.875,1.875,0,1,0,196,113.875a.625.625,0,0,0,1.25,0A.626.626,0,0,1,197.875,113.25Z"
                                        transform="translate(-189.875 -108.5)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    <circle id="Ellipse_891" data-name="Ellipse 891" cx="0.625" cy="0.625" r="0.625"
                                        transform="translate(7.375 11)"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Support & Communication')}}</span>
                        <span class="aiz-side-nav-arrow"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @if (get_setting('conversation_system') == 1)
                            @php
                                $conversation = \App\Models\Conversation::where('sender_id', Auth::user()->id)
                                    ->where('sender_viewed', 0)
                                    ->get();
                            @endphp
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.conversations.index') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.conversations.index', 'seller.conversations.show']) }}">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Conversations') }}</span>
                                    @if (count($conversation) > 0)
                                        <span class="badge badge-success">({{ count($conversation) }})</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @if (get_setting('product_query_activation') == 1)
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.product_query.index') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.product_query.index']) }}">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Product Queries') }}</span>

                                </a>
                            </li>
                        @endif
                        @php
                            $support_ticket = DB::table('tickets')
                                ->where('client_viewed', 0)
                                ->where('user_id', Auth::user()->id)
                                ->count();
                        @endphp
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.support_ticket.index') }}"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller.support_ticket.index']) }}">
                                <span class="aiz-side-nav-text"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Support Ticket') }}</span>
                                @if ($support_ticket > 0)
                                    <span class="badge badge-inline badge-success">{{ $support_ticket }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Marketing & Promotions') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 -960 960 960"
                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                <path
                                    d="M160-80v-440H80v-240h208q-5-9-6.5-19t-1.5-21q0-50 35-85t85-35q23 0 43 8.5t37 23.5q17-16 37-24t43-8q50 0 85 35t35 85q0 11-2 20.5t-6 19.5h208v240h-80v440H160Zm371.5-748.5Q520-817 520-800t11.5 28.5Q543-760 560-760t28.5-11.5Q600-783 600-800t-11.5-28.5Q577-840 560-840t-28.5 11.5ZM360-800q0 17 11.5 28.5T400-760q17 0 28.5-11.5T440-800q0-17-11.5-28.5T400-840q-17 0-28.5 11.5T360-800ZM160-680v80h280v-80H160Zm280 520v-360H240v360h200Zm80 0h200v-360H520v360Zm280-440v-80H520v80h280Z" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{translate('Promotion & Offers')}}</span>
                        <span class="aiz-side-nav-arrow"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.promotional_products.index') }}" class="aiz-side-nav-link"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                <span class="aiz-side-nav-text"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Promotional Product') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller.categories_wise_product_discount') }}" class="aiz-side-nav-link"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                <span class="aiz-side-nav-text"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Category Wise Discount') }}</span>
                            </a>
                        </li>
                        @if (get_setting('coupon_system') == 1)
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.coupon.index') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.coupon.index', 'seller.coupon.create', 'seller.coupon.edit']) }}">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Coupon') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Content & Design') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.shop.design') }}"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 14 14">
                                <g id="Group_28317" data-name="Group 28317" transform="translate(-19315 1976)">
                                    <g id="layer1" transform="translate(19315 -1976)">
                                        <path id="path3159"
                                            d="M3.029.53a2.5,2.5,0,0,0-2.5,2.5v9a2.507,2.507,0,0,0,2.5,2.5h9a2.511,2.511,0,0,0,2.5-2.5v-9a2.507,2.507,0,0,0-2.5-2.5Zm0,1h9a1.488,1.488,0,0,1,1.5,1.5v9a1.491,1.491,0,0,1-1.5,1.5h-9a1.488,1.488,0,0,1-1.5-1.5v-9A1.484,1.484,0,0,1,3.029,1.53Z"
                                            transform="translate(-0.53 -0.53)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    </g>
                                    <g id="Group_28316" data-name="Group 28316"
                                        transform="translate(19317.232 -1973.449)">
                                        <g id="LWPOLYLINE" transform="translate(0 3.708)">
                                            <path id="Path_25666" data-name="Path 25666"
                                                d="M194.007,143.129a.44.44,0,0,0,0,.873h1.336a.44.44,0,0,0,0-.873Z"
                                                transform="translate(-193.625 -143.129)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                        <g id="LWPOLYLINE-2" data-name="LWPOLYLINE" transform="translate(3.205)">
                                            <path id="Path_25667" data-name="Path 25667"
                                                d="M199.926,137.186a.385.385,0,1,1,.763,0v1.527a.385.385,0,1,1-.763,0Z"
                                                transform="translate(-199.926 -136.75)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                        <g id="LWPOLYLINE-3" data-name="LWPOLYLINE" transform="translate(4.584 1.075)">
                                            <path id="Path_25668" data-name="Path 25668"
                                                d="M204.235,139.345a.481.481,0,0,0,0-.617.349.349,0,0,0-.54,0l-.944,1.079a.481.481,0,0,0,0,.617.349.349,0,0,0,.54,0Z"
                                                transform="translate(-202.638 -138.6)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                        <g id="LWPOLYLINE-4" data-name="LWPOLYLINE" transform="translate(0.96 1.075)">
                                            <path id="Path_25669" data-name="Path 25669"
                                                d="M195.624,139.345a.481.481,0,0,1,0-.617.349.349,0,0,1,.54,0l.944,1.079a.481.481,0,0,1,0,.617.349.349,0,0,1-.54,0Z"
                                                transform="translate(-195.512 -138.6)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                        <g id="LWPOLYLINE-5" data-name="LWPOLYLINE" transform="translate(0.96 5.261)">
                                            <path id="Path_25670" data-name="Path 25670"
                                                d="M195.624,147.008a.481.481,0,0,0,0,.617.349.349,0,0,0,.54,0l.944-1.079a.482.482,0,0,0,0-.617.349.349,0,0,0-.54,0Z"
                                                transform="translate(-195.512 -145.8)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                        <path id="Path_25671" data-name="Path 25671"
                                            d="M205.952,148.144l-1.64-1.875.756-.471a.47.47,0,0,0,.153-.592.4.4,0,0,0-.191-.195l-4.972-2.322a.367.367,0,0,0-.5.239.5.5,0,0,0,0,.33l2.031,5.682a.367.367,0,0,0,.5.239.4.4,0,0,0,.191-.2l.412-.864,1.64,1.875a.349.349,0,0,0,.54,0l1.079-1.233A.482.482,0,0,0,205.952,148.144Zm-1.351.913-1.73-1.977a.349.349,0,0,0-.54,0,.442.442,0,0,0-.065.1l-.272.57-1.383-3.868,3.385,1.58-.5.311a.472.472,0,0,0-.087.691l1.73,1.977Z"
                                            transform="translate(-196.528 -139.223)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Shop Design') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.uploaded-files.index') }}"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.uploaded-files.index', 'seller.uploads.create']) }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="layer1" transform="translate(-0.53 -0.53)">
                                    <path id="path3159"
                                        d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673ZM8.12,3.557,5.34,6.37a.572.572,0,0,0,0,.809.564.564,0,0,0,.81,0l1.8-1.824V10.8a.571.571,0,0,0,1.143,0V5.347l1.8,1.829a.571.571,0,0,0,.81-.806L8.935,3.557a.511.511,0,0,0-.815,0Zm-4.156,8.97a.571.571,0,0,0,0,1.143h9.128a.571.571,0,0,0,0-1.143Z"
                                        fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li>
                <li class="px-25px pt-2 pb-1 mt-3">
                    <span class="aiz-side-nav-text fs-12 fw-400 text-uppercase opacity-50"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Settings') }}</span>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('seller.shop.index') }}"
                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['seller.shop.index']) }}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Path_40779" data-name="Path 40779"
                                    d="M7.688,16h.625a1.877,1.877,0,0,0,1.875-1.875V13.81a.209.209,0,0,1,.133-.191l.011,0a.209.209,0,0,1,.23.041l.223.223a1.875,1.875,0,0,0,2.652,0l.442-.442a1.875,1.875,0,0,0,0-2.652l-.223-.223a.209.209,0,0,1-.041-.23l0-.012a.209.209,0,0,1,.191-.133h.315A1.877,1.877,0,0,0,16,8.313V7.688a1.877,1.877,0,0,0-1.875-1.875H13.81a.209.209,0,0,1-.191-.133l0-.011a.209.209,0,0,1,.041-.23l.223-.223a1.875,1.875,0,0,0,0-2.652l-.442-.442a1.875,1.875,0,0,0-2.652,0l-.223.223a.21.21,0,0,1-.23.041l-.012,0a.209.209,0,0,1-.133-.191V1.875A1.877,1.877,0,0,0,8.312,0H7.687A1.877,1.877,0,0,0,5.812,1.875V2.19a.209.209,0,0,1-.133.191l-.012,0a.209.209,0,0,1-.23-.041l-.223-.223a1.875,1.875,0,0,0-2.652,0l-.442.442a1.875,1.875,0,0,0,0,2.652l.223.223a.209.209,0,0,1,.041.23l0,.011a.209.209,0,0,1-.191.133H1.875A1.877,1.877,0,0,0,0,7.687v.625a1.874,1.874,0,0,0,1.407,1.816.625.625,0,1,0,.312-1.211.624.624,0,0,1-.468-.605V7.688a.626.626,0,0,1,.625-.625H2.19a1.455,1.455,0,0,0,1.347-.906l0-.011a1.455,1.455,0,0,0-.312-1.591l-.223-.223a.625.625,0,0,1,0-.884l.442-.442a.625.625,0,0,1,.884,0l.223.223a1.456,1.456,0,0,0,1.593.311l.009,0A1.455,1.455,0,0,0,7.063,2.19V1.875a.626.626,0,0,1,.625-.625h.625a.626.626,0,0,1,.625.625V2.19a1.455,1.455,0,0,0,.906,1.347l.009,0a1.455,1.455,0,0,0,1.593-.311l.223-.223a.625.625,0,0,1,.884,0l.442.442a.625.625,0,0,1,0,.884l-.223.223a1.455,1.455,0,0,0-.311,1.593l0,.009a1.455,1.455,0,0,0,1.347.906h.315a.626.626,0,0,1,.625.625v.625a.626.626,0,0,1-.625.625H13.81a1.455,1.455,0,0,0-1.347.906l0,.009a1.455,1.455,0,0,0,.311,1.593l.223.223a.625.625,0,0,1,0,.884l-.442.442a.625.625,0,0,1-.884,0l-.223-.223a1.456,1.456,0,0,0-1.593-.311l-.009,0a1.455,1.455,0,0,0-.906,1.347v.315a.626.626,0,0,1-.625.625H7.688a.622.622,0,0,1-.6-.437.625.625,0,1,0-1.193.375A1.867,1.867,0,0,0,7.688,16ZM.536,15.433a1.829,1.829,0,0,1,0-2.586h0L4.589,8.811a3.234,3.234,0,0,1-.308-1.259,2.97,2.97,0,0,1,.9-2.141A4.228,4.228,0,0,1,8.13,4.255h.007a3.322,3.322,0,0,1,1.086.188A.625.625,0,0,1,9.47,5.473L7.964,7.01l.188.811L8.95,8,10.479,6.47a.625.625,0,0,1,1.034.24,3.472,3.472,0,0,1,.2,1.121,4.373,4.373,0,0,1-.8,2.556,3.047,3.047,0,0,1-2.49,1.3H8.417A3.414,3.414,0,0,1,7.159,11.4L3.122,15.433a1.829,1.829,0,0,1-2.586,0Zm6.876-5.311a2.1,2.1,0,0,0,1.007.316,1.818,1.818,0,0,0,1.487-.792,2.988,2.988,0,0,0,.528-1.361l-.843.845A.625.625,0,0,1,9.01,9.3L7.494,8.953a.625.625,0,0,1-.471-.468L6.669,6.959a.625.625,0,0,1,.162-.579l.823-.84A2.844,2.844,0,0,0,6.067,6.3,1.723,1.723,0,0,0,5.531,7.55a2.123,2.123,0,0,0,.342,1,.625.625,0,0,1-.065.809L1.419,13.731a.579.579,0,1,0,.819.818l4.368-4.361a.625.625,0,0,1,.806-.066Z"
                                    fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Shop Settings') }}</span>
                    </a>
                </li>
                @if (addon_is_activated('seller_subscription'))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28314" data-name="Group 28314" transform="translate(-19299 2175)">
                                        <path id="Path_40774" data-name="Path 40774"
                                            d="M87.867,3.07H84.133V1.72A.716.716,0,0,0,83.422,1H80.578a.716.716,0,0,0-.711.72V3.07H76.133A2.149,2.149,0,0,0,74,5.229V14.84A2.149,2.149,0,0,0,76.133,17H87.867A2.149,2.149,0,0,0,90,14.84V5.229A2.149,2.149,0,0,0,87.867,3.07Zm-6.578-.63h1.422V3.79a.711.711,0,1,1-1.422,0Zm7.289,12.4a.716.716,0,0,1-.711.72H76.133a.716.716,0,0,1-.711-.72V5.229a.716.716,0,0,1,.711-.72h3.856a2.124,2.124,0,0,0,4.022,0h3.856a.716.716,0,0,1,.711.72Z"
                                            transform="translate(19225 -2176)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        <g id="Group_28312" data-name="Group 28312"
                                            transform="translate(19305.07 -2169.197)">
                                            <path id="Path_40775" data-name="Path 40775"
                                                d="M199.864,197.932a1.932,1.932,0,1,0-1.932,1.932A1.934,1.934,0,0,0,199.864,197.932Zm-1.932.644a.644.644,0,1,1,.644-.644A.645.645,0,0,1,197.932,198.576Z"
                                                transform="translate(-196 -196)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                        <g id="Group_28313" data-name="Group 28313" transform="translate(19303.779 -2165)">
                                            <path id="Path_40776" data-name="Path 40776"
                                                d="M160.508,316h-2.576A1.934,1.934,0,0,0,156,317.932v1.288a.644.644,0,1,0,1.288,0v-1.288a.645.645,0,0,1,.644-.644h2.576a.645.645,0,0,1,.644.644v1.288a.644.644,0,1,0,1.288,0v-1.288A1.934,1.934,0,0,0,160.508,316Z"
                                                transform="translate(-156 -316)"
                                                fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Package') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.seller_packages_list') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Packages') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.packages_payment_list') }}" class="aiz-side-nav-link"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Purchase Packages') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (addon_is_activated('gst_system'))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link"
                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28315" data-name="Group 28315">
                                        <circle id="Ellipse_893" data-name="Ellipse 893" cx="0.625" cy="0.625" r="0.625"
                                            transform="translate(7.375 6.125)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        <path id="Path_40777" data-name="Path 40777"
                                            d="M13.5,0H2.5A2.5,2.5,0,0,0,0,2.5V11a2.5,2.5,0,0,0,2.5,2.5H7.375v1.25H5.5A.625.625,0,0,0,5.5,16h5a.625.625,0,0,0,0-1.25H8.625V12.875A.625.625,0,0,0,8,12.25H2.5A1.251,1.251,0,0,1,1.25,11V2.5A1.251,1.251,0,0,1,2.5,1.25h11A1.251,1.251,0,0,1,14.75,2.5V11a1.251,1.251,0,0,1-1.25,1.25h-3a.625.625,0,0,0,0,1.25h3A2.5,2.5,0,0,0,16,11V2.5A2.5,2.5,0,0,0,13.5,0Z"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                        <path id="Path_40778" data-name="Path 40778"
                                            d="M120.375,84.75a.625.625,0,0,0,.625-.625v-.688a3.107,3.107,0,0,0,1.1-.456l.487.487a.625.625,0,0,0,.884-.884l-.487-.487a3.108,3.108,0,0,0,.456-1.1h.688a.625.625,0,1,0,0-1.25h-.688a3.108,3.108,0,0,0-.456-1.1l.487-.487a.625.625,0,0,0-.884-.884l-.487.487a3.107,3.107,0,0,0-1.1-.456v-.688a.625.625,0,0,0-1.25,0v.688a3.108,3.108,0,0,0-1.1.456l-.487-.487a.625.625,0,0,0-.884.884l.487.487a3.108,3.108,0,0,0-.456,1.1h-.688a.625.625,0,0,0,0,1.25h.688a3.108,3.108,0,0,0,.456,1.1l-.487.487a.625.625,0,0,0,.884.884l.487-.487a3.107,3.107,0,0,0,1.1.456v.688A.625.625,0,0,0,120.375,84.75ZM118.5,80.375a1.875,1.875,0,1,1,1.875,1.875A1.877,1.877,0,0,1,118.5,80.375Z"
                                            transform="translate(-112.375 -73.625)"
                                            fill="{{ Auth::user()->shop->navbar_text_color ?? 'white' }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('GST System') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">Addon</span>
                            @endif
                            <span class="aiz-side-nav-arrow"
                                style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('seller.products.hsn-gst.assigns') }}"
                                    style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller.products.hsn-gst.assigns']) }}">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('HSN Assign') }}</span>
                                </a>
                            </li>
                            @if (addon_is_activated('wholesale'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.products.wholesale-hsn-gst.assigns') }}"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Wholesale Products') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (addon_is_activated('preorder'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.products.preorder-hsn-gst.assigns') }}" class="aiz-side-nav-link"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Preorder Products') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (addon_is_activated('auction'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller.products.auction-hsn-gst.assigns') }}" class="aiz-side-nav-link"
                                        style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Auction Products') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>