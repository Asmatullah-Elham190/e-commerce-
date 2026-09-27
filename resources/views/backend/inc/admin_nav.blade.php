<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start ml-0 mr-2"
            data-toggle="aiz-mobile-nav">
            <a class="btn btn-topbar has-transition btn-icon p-0 d-flex align-items-center" href="javascript:void(0)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="8" viewBox="0 0 16 8">
                    <g id="Group_39938" data-name="Group 39938" transform="translate(-278 -30)">
                        <rect id="Rectangle_24892" data-name="Rectangle 24892" width="16" height="2"
                            transform="translate(278 30)" fill="#232734" />
                        <rect id="Rectangle_24893" data-name="Rectangle 24893" width="8" height="2"
                            transform="translate(278 36)" fill="#232734" />
                    </g>
                </svg>
            </a>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-1">
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            @canany(['view_product_management_dashboard', 'view_all_orders', 'pos_manager', 'view_promotion_and_offers_dashboard', 'manage_marketing_dashboard', 'manage_marketing_analytics_dashboard', 'view_website_dashboard', 'business_settings'])
                <div class="d-flex justify-content-around align-items-center align-items-stretch mr-3">
                    <div class="aiz-topbar-item d-none d-sm-block">
                        <div class="d-flex align-items-center h-100">
                            <a data-toggle="collapse" href="#collapseQuickMenu" role="button" aria-expanded="false"
                                aria-controls="collapseQuickMenu">
                                <span
                                    class="bg-dark text-white btn-sm d-flex align-items-center rounded-pill hov-svg-white hov-opacity-80 has-transition">
                                    <span class="fw-500 mr-2 mr-0 d-none d-md-block">{{ translate('Quick Menu') }}</span>
                                    <i class="las la-angle-down fs-14"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            @endcanany
            @if (addon_is_activated('pos_system') && auth()->user()->can('pos_manager'))
                <div class="aiz-topbar-item mr-3">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-topbar has-transition btn-icon btn-circle btn-light p-0 hov-bg-primary hov-svg-white d-flex align-items-center justify-content-center"
                            href="{{ route('poin-of-sales.index') }}" target="_blank" data-toggle="tooltip"
                            data-title="{{ translate('POS') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13.79" height="16" viewBox="0 0 13.79 16">
                                <g id="_371925cdd3f531725a9fa8f3ebf8fe9e" data-name="371925cdd3f531725a9fa8f3ebf8fe9e"
                                    transform="translate(-2.26 0)">
                                    <path id="Path_40673" data-name="Path 40673"
                                        d="M10.69,7H3.26a1.025,1.025,0,0,0-1,1V18.45a1.03,1.03,0,0,0,1,1.05h7.43a1.03,1.03,0,0,0,1.03-1.03V8A1.025,1.025,0,0,0,10.69,7ZM4.94,17.86H3.995v-.95H4.94Zm0-2.355H3.995v-.95H4.94Zm0-2.355H3.995V12.2H4.94Zm2.5,4.71H6.5v-.95h.955Zm0-2.355H6.5v-.95h.955Zm0-2.355H6.5V12.2h.955Zm2.5,4.71H8.99v-.95h.95Zm0-2.355H8.99v-.95h.95Zm0-2.355H8.99V12.2h.95Zm.325-3a.17.17,0,0,1-.165.17H3.835a.17.17,0,0,1-.165-.17V8.795a.165.165,0,0,1,.165-.165H10.13a.165.165,0,0,1,.165.165Zm5.09-1.45H15.13v9.09h.25a.67.67,0,0,0,.67-.67V9.375a.67.67,0,0,0-.695-.675Z"
                                        transform="translate(0 -3.5)" fill="#717580" />
                                    <rect id="Rectangle_20842" data-name="Rectangle 20842" width="1.465" height="9.095"
                                        transform="translate(12.185 5.2)" fill="#717580" />
                                    <rect id="Rectangle_20843" data-name="Rectangle 20843" width="0.63" height="9.095"
                                        transform="translate(14.06 5.2)" fill="#717580" />
                                    <path id="Path_40674" data-name="Path 40674"
                                        d="M13.895.895a.89.89,0,0,0-.26-.635A.91.91,0,0,0,13,0a.895.895,0,0,0-.91.895v.53h1.79Zm-2.2,0a.76.76,0,0,1,0-.145.68.68,0,0,1,0-.1h.01A.5.5,0,0,1,11.755.5.43.43,0,0,1,11.79.4a1.2,1.2,0,0,1,.145-.26.5.5,0,0,1,.04-.055L12.045,0H7.995A.815.815,0,0,0,7.18.81V3.03h4.5Z"
                                        transform="translate(-2.46)" fill="#717580" />
                                </g>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
            <div class="aiz-topbar-item mr-2 d-none d-xl-block">
                <div class="d-flex align-items-center h-100">
                    @can('admin_dashboard')
                        <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['admin.dashboard']) }}"
                            href="{{ route('admin.dashboard') }}">{{ translate('Dashboard') }}</a>
                    @endcan    
                    @can('view_all_orders')
                        <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['all_orders.index']) }}"
                            href="{{ route('all_orders.index') }}">{{ translate('Sales') }}</a>
                    @endcan
                    @if (addon_is_activated('preorder'))
                        <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['preorder-order.show']) }}"
                            href="{{ route('all_preorder.list') }}">{{ translate('Preorders') }}</a>
                    @endif
                    @can('earning_report')
                        <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['earning_payout_report.index']) }}"
                            href="{{ route('earning_payout_report.index') }}">{{ translate('Earnings') }}</a>
                    @endcan
                    @can('view_website_dashboard')
                        <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['website.dashboard']) }}"
                            href="{{ route('website.dashboard') }}">{{ translate('Design Studio') }}</a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            @canany(['add_new_product', 'add_product_category', 'add_brand'])
                <div class="d-flex justify-content-around align-items-center align-items-stretch mr-3">
                    <div class="aiz-topbar-item d-none d-sm-block">
                        <div class="d-flex align-items-center h-100 dropdown">
                            <a class="dropdown-toggle no-arrow h-100 text-reset" data-toggle="dropdown"
                                href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                <span
                                    class="bg-light text-reset hov-text-white hov-bg-blue btn-sm d-flex align-items-center rounded-pill has-transition">
                                    <i class="las fs-18 la-plus"></i>
                                    <span class="fw-500 ml-2 mr-0 d-none d-md-block">{{ translate('Add New') }}</span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left dropdown-menu-animated dropdown-menu-md"
                                style="top: 15px !important;">
                                @can('add_new_product')
                                    <a href="{{ route('products.create') }}" class="dropdown-item">
                                        <i class="las la-plus"></i>
                                        <span>{{ translate('New Product') }}</span>
                                    </a>
                                @endcan
                                @can('add_product_category')
                                    <a href="{{ route('categories.create') }}" class="dropdown-item">
                                        <i class="las la-plus"></i>
                                        <span>{{ translate('New Category') }}</span>
                                    </a>
                                @endcan
                                @can('add_brand')
                                    <a href="{{ route('brands.create') }}" class="dropdown-item">
                                        <i class="las la-plus"></i>
                                        <span>{{ translate('New Brand') }}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endcanany    
            <div class="aiz-topbar-item mr-3">
                <div class="d-flex align-items-center">
                    <a class="btn btn-topbar has-transition w-35px h-35px btn-circle p-0 border border-1 border-gray-400 d-flex align-items-center justify-content-center hov-bg-primary hov-svg-white"
                        href="{{ route('home') }}" target="_blank" data-toggle="tooltip"
                        data-title="{{ translate('Browse Website') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14.4" height="14.4" viewBox="0 0 14.4 14.4"
                            style="margin-top: 2px;">
                            <path id="_754bac7463b8b1afad8e10a2355d1700" data-name="754bac7463b8b1afad8e10a2355d1700"
                                d="M55.2,48a7.2,7.2,0,1,0,7.2,7.2A7.2,7.2,0,0,0,55.2,48Zm-.746,13.327A6.172,6.172,0,0,1,50.5,51.2a6.839,6.839,0,0,1,.07.837,2.669,2.669,0,0,0,.344,2.034,3.356,3.356,0,0,1,.325.972c.089.306.447.467.693.656.5.381.973.824,1.5,1.159.348.221.565.331.463.756a2.682,2.682,0,0,1-.281.856,1.735,1.735,0,0,0,.289.775c.26.26.517.5.8.731C55.144,60.335,54.663,60.806,54.454,61.327Zm5.11-1.763a6.127,6.127,0,0,1-3.2,1.7,2.56,2.56,0,0,1,.758-1.016A2.579,2.579,0,0,0,57.8,59.4a5.856,5.856,0,0,1,.47-.8c.245-.377-.6-.946-.878-1.065a9.046,9.046,0,0,1-1.632-1.017c-.391-.275-1.186.144-1.628-.049a8.516,8.516,0,0,1-1.63-1.119c-.543-.409-.517-.885-.517-1.488.425.016,1.03-.118,1.312.224.089.108.4.59.6.419.167-.14-.124-.7-.18-.833-.173-.406.395-.564.685-.839.379-.359,1.193-.921,1.129-1.179s-.814-.986-1.255-.872c-.066.017-.647.626-.759.722q0-.3.009-.6c0-.126-.234-.254-.223-.335.028-.2.6-.576.739-.739-.1-.062-.438-.353-.541-.31-.248.1-.529.175-.777.278a1.58,1.58,0,0,0-.023-.247A6.113,6.113,0,0,1,54.27,49.1l.488.2.344.409.344.354.3.1.477-.45-.123-.321v-.289a6.1,6.1,0,0,1,2.614,1.032c-.14.012-.293.033-.466.055a1.551,1.551,0,0,0-.241-.091c.226.486.462.965.7,1.445.256.512.824,1.062.923,1.6.117.637.036,1.217.1,1.967a3.359,3.359,0,0,0,.814,1.543,1.63,1.63,0,0,0,.636.077A6.133,6.133,0,0,1,59.564,59.564Z"
                                transform="translate(-48 -48)" fill="#1b2133" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="aiz-topbar-item mr-3">
                <div class="d-flex align-items-center">
                    <a class="btn btn-topbar has-transition w-35px h-35px btn-circle p-0 border border-1 border-gray-400 d-flex align-items-center justify-content-center hov-bg-primary hov-svg-white"
                        href="{{ route('cache.clear') }}" data-toggle="tooltip"
                        data-title="{{ translate('Clear Cache') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14.576" height="14.576"
                            viewBox="0 0 14.576 14.576">
                            <path id="_74846e5be5db5b666d3893933be03656" data-name="74846e5be5db5b666d3893933be03656"
                                d="M7.3,8.3H8.374v1.08H7.3v1.08H6.224V9.376H5.149V8.3H6.224V7.216H7.3ZM5.149,12.615H6.224v1.08H5.149v1.08H4.075v-1.08H3v-1.08H4.075v-1.08H5.149ZM17.563,10.1H9.5v-.54a1.077,1.077,0,0,1,1.075-1.08h2.149V2h1.612V8.478h2.149a1.077,1.077,0,0,1,1.075,1.08Zm-.537,6.478H14.883a8.435,8.435,0,0,0,.53-2.7.537.537,0,1,0-1.075,0,7.005,7.005,0,0,1-.63,2.7h-2.05a8.435,8.435,0,0,0,.53-2.7.537.537,0,1,0-1.075,0,7.005,7.005,0,0,1-.63,2.7H8.427a20.793,20.793,0,0,0,1.059-5.4h8.08A17.421,17.421,0,0,1,17.025,16.576Z"
                                transform="translate(-3 -2)" fill="#1b2133" />
                        </svg>
                    </a>
                </div>
            </div>
            @can('view_notifications')
                <div class="aiz-topbar-item mr-3">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0)" role="button" id="view_all_notification"
                            aria-label="{{ translate('Open Notifications') }}" aria-expanded="false">
                            <span
                                class="btn btn-topbar has-transition w-35px h-35px btn-circle p-0 border border-1 border-light d-flex align-items-center justify-content-center btn-light"
                                data-toggle="tooltip" data-title="{{ translate('Notification') }}">
                                <span class="d-flex align-items-center position-relative">
                                    <div class="px-2 hov-svg-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11.52" height="14.4"
                                            viewBox="0 0 11.52 14.4">
                                            <path id="Path_54477" data-name="Path 54477"
                                                d="M160.72-867.76a.7.7,0,0,1-.513-.207.7.7,0,0,1-.207-.513.7.7,0,0,1,.207-.513.7.7,0,0,1,.513-.207h.72v-5.04a4.216,4.216,0,0,1,.9-2.655,4.153,4.153,0,0,1,2.34-1.521v-.5a1.041,1.041,0,0,1,.315-.765,1.041,1.041,0,0,1,.765-.315,1.041,1.041,0,0,1,.765.315,1.041,1.041,0,0,1,.315.765v.5a4.153,4.153,0,0,1,2.34,1.521,4.216,4.216,0,0,1,.9,2.655v5.04h.72a.7.7,0,0,1,.513.207.7.7,0,0,1,.207.513.7.7,0,0,1-.207.513.7.7,0,0,1-.513.207Zm5.04,2.16a1.387,1.387,0,0,1-1.017-.423,1.387,1.387,0,0,1-.423-1.017h2.88a1.387,1.387,0,0,1-.423,1.017A1.387,1.387,0,0,1,165.76-865.6Z"
                                                transform="translate(-160 880)" fill="#232734" />
                                        </svg>
                                    </div>
                                    @if (auth()->user()->unreadNotifications->count() > 0)
                                        <span
                                            class="badge badge-sm badge-dot badge-circle badge-danger position-absolute absolute-top-right"
                                            style="top: -3px!important; right: -2px!important;"></span>
                                    @endif
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            @endcan
            <div class="aiz-topbar-item mr-3">
                <div class="d-flex align-items-center">
                    <a href="javascript:void(0)" role="button" id="view_all_chat" aria-label="{{ translate('Open Seller Hub') }}"
                        aria-expanded="false">
                        <span
                            class="btn btn-topbar has-transition w-35px h-35px btn-circle p-0 border border-1 border-dark bg-dark d-flex align-items-center justify-content-center"
                            data-toggle="tooltip" data-title="{{ translate('Seller Hub') }}">
                            <span class="d-flex align-items-center position-relative">
                                <div class="px-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.4" viewBox="0 0 16 14.4">
                                        <path id="f2cd9334b7a17bee252e9299db3517fa"
                                            d="M9.8,6A5.8,5.8,0,0,0,4,11.8c0,7.2,9.6,8.6,9.6,8.6V17.6h.6A5.8,5.8,0,0,0,14.2,6ZM12,13a1,1,0,1,0-1-1A1,1,0,0,0,12,13Zm4.2-1a1,1,0,1,1-1-1A1,1,0,0,1,16.2,12ZM8.8,13a1,1,0,1,0-1-1A1,1,0,0,0,8.8,13Z"
                                            transform="translate(-4 -6)" fill="#fff" fill-rule="evenodd" />
                                    </svg>
                                </div>
                                @if ($hasAnyUnseen ?? false)
                                    <span
                                        class="badge badge-sm badge-dot badge-circle badge-danger position-absolute absolute-top-right"
                                        style="top: -5px!important; right: -1px!important;"></span>
                                @endif
                            </span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="aiz-topbar-item">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="size-40px rounded-content overflow-hidden border border-2 border-blue">
                                <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" class="img-fit"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span>{{ translate('Profile') }}</span>
                        </a>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{ translate('Logout') }}</span>
                        </a>
                        @php
                            if (Session::has('locale')) {
                                $locale = Session::get('locale', Config::get('app.locale'));
                            } else {
                                $locale = env('DEFAULT_LANGUAGE');
                            }
                        @endphp
                        <div class="custom-dropdown-submenu">
                            <a href="javascript:void(0);"
                                class="dropdown-item custom-submenu-toggle d-flex align-items-center justify-content-between"
                                aria-haspopup="true" aria-expanded="false">
                                <span>
                                    <i class="las la-language"></i>
                                    <span>{{ translate('Language') }}</span>
                                </span>
                                <i class="las la-angle-right custom-submenu-arrow"></i>
                            </a>
                            <div class="dropdown-menu custom-submenu-nested" id="lang-change">
                                @foreach (\App\Models\Language::where('status', 1)->get() as $key => $language)
                                    <a class="dropdown-item d-flex align-items-center @if ($locale == $language->code) active @endif"
                                        href="javascript:void(0);" data-flag="{{ $language->code }}">
                                        <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                            class="flex-shrink-0 mr-3" alt="en" />
                                        <span class="d-block flex-grow-1">{{ $language->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="collapse position-fixed" id="collapseQuickMenu">
    <div class="card mb-0 rounded-0 c-scrollbar-light" style="max-height: 50vh; overflow-y: auto;">
        <div class="card-body p-xl-5">
            <div class="row align-items-center">
                <div class="col-xl-9 col-lg-8 col-md-8">
                    <div class="row">
                        @can('view_product_management_dashboard')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{route('products.dashboard')}}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/product-management-home.svg') }}" class="flex-shrink-0"
                                        alt="Product Management" />
                                    <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Product Management') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('Create, setup and manage all your products') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('view_all_orders')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{ route('all_orders.index') }}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/sales.svg') }}" class="flex-shrink-0" alt="Sales" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Sales') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('View and manage all types of orders') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @if (addon_is_activated('pos_system') && auth()->user()->can('pos_manager'))
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{route('poin-of-sales.index')}}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/pos.svg') }}" class="flex-shrink-0" alt="POS" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('POS') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('View and manage all types of orders') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @can('view_promotion_and_offers_dashboard')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{ route('promotion_and_offers_dashboard') }}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/promotion-offers-home.svg') }}" class="flex-shrink-0"
                                        alt="Promotion & Offers" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Promotion & Offers') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('Create and manage all promotions, offers and discounted products') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('manage_marketing_dashboard')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{route('marketing_dashboard')}}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/marketing-home.svg') }}" class="flex-shrink-0" alt="Marketing Home" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Marketing Home') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('Manage marketing needs for your site') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('manage_marketing_analytics_dashboard')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{ route('marketing_analytics_dashboard') }}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/marketing-analytics-home.svg') }}" class="flex-shrink-0"
                                        alt="Marketing Analytics" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Marketing Analytics') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('Connect track and optimize your store marketing') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('view_website_dashboard')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{ route('website.dashboard') }}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/design-studio-home.svg') }}" class="flex-shrink-0" alt="Design Studio" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Design Studio') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate("Manage your site's look, layout and content") }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('earning_report')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{ route('earning_payout_report.index') }}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/qcm-reports.svg') }}" class="flex-shrink-0" alt="Reports" />
                                    <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Reports') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate("See your sells earning and performance") }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('manage_ai_configuration')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{ route('ai-add_edit_products') }}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/qcm-ai-studio.svg') }}" class="flex-shrink-0" alt="AI Studio" />
                                    <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('AI Studio') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate("Generate and edit with AI") }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('business_settings')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{route('business_settings.index')}}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/qcm-business-settings.svg') }}" class="flex-shrink-0" alt="Business Settings" />
                                     <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Business Settings') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate('Manage core business operations orders, invoicing and delivery') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('features_activation')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{route('activation.index')}}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/qcm-feature-activation.svg') }}" class="flex-shrink-0" alt="Feature Activation" />
                                    <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Feature Activation') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate("Customize how the business operates") }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('manage_addons')
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-4">
                                <a href="{{route('addons.index')}}"
                                    class="d-flex align-items-center text-reset hov-text-blue has-transition">
                                    <img src="{{ static_asset('assets/img/qcm-addon-manager.svg') }}" class="flex-shrink-0" alt="Addon Manager" />
                                    <div class="ml-3 flex-grow-1">
                                        <span class="fs-13 fw-500 text-reset d-block mb-1">{{ translate('Addon Manager') }}</span>
                                        <span class="fs-12 fw-400 text-secondary d-block text-truncate-breakpoint">{{ translate("Manage & Updates your add-ons") }}</span>
                                    </div>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4">
                    <div class="p-4 bg-light rounded-2 d-flex flex-column" style="gap: 12px;">
                        <h3 class="fs-13 fw-600 text-reset">{{ translate('Quick Links') }}</h3>
                        <ul class="list-unstyled d-flex flex-column" style="gap: 9px;">
                            @can('view_all_customers')
                                <li>
                                    <a href="{{ route('customers.index') }}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('View & Manage All Customers') }}</a>
                                </li>
                            @endcan    
                            @can('view_all_seller')
                                <li>
                                    <a href="{{ route('sellers.index') }}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('View & Manage All Sellers') }}</a>
                                </li>
                            @endcan
                        </ul>
                        <ul class="list-unstyled d-flex flex-column" style="gap: 9px;">
                            @can('view_top_banner')
                                <li>
                                    <a href="{{route('top_banner.index')}}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('Top Bar Setting') }}</a>
                                </li>
                            @endcan    
                            @can('authentication_layout_settings')
                                <li>
                                    <a href="{{ route('website.authentication-layout-settings') }}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('Authentication Layout & Settings') }}</a>
                                </li>
                            @endcan    
                        </ul>
                        <ul class="list-unstyled d-flex flex-column" style="gap: 9px;">
                            @can('header_setup')
                                <li>
                                    <a href="{{ route('website.header') }}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('Header Settings') }}</a>
                                </li>
                            @endcan
                            @can('edit_website_page')
                                <li>
                                    <a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('Homepage Settings') }}</a>
                                </li>
                            @endcan
                            @can('footer_setup')
                                <li>
                                    <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}" class="fs-12 fw-500 text-blue hov-opacity-80 has-transition">{{ translate('Footer Settings') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>