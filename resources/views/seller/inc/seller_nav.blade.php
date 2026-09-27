<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3 ml-0"
            data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <svg class="position-absolute d-block" xmlns="http://www.w3.org/2000/svg" width="16" height="8"
                    viewBox="0 0 16 8">
                    <g id="Group_39938" data-name="Group 39938" transform="translate(-278 -30)">
                        <rect id="Rectangle_24892" data-name="Rectangle 24892" width="16" height="2"
                            transform="translate(278 30)" fill="#232734" />
                        <rect id="Rectangle_24893" data-name="Rectangle 24893" width="8" height="2"
                            transform="translate(278 36)" fill="#232734" />
                    </g>
                </svg>
            </button>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <div class="aiz-topbar-item mr-2 d-none d-xl-block">
                <div class="d-flex align-items-center h-100">
                    <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['seller.dashboard']) }}"
                        href="{{ route('seller.dashboard') }}">{{ translate('Dashboard') }}</a>
                    <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['seller.orders.index']) }}"
                        href="{{ route('seller.orders.index') }}">{{ translate('Orders') }}</a>
                    <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['seller.shop.index']) }}"
                        href="{{ route('seller.shop.index') }}">{{ translate('Shop Settings') }}</a>
                    @if (addon_is_activated('pos_system'))
                        <a class="aiz-topbar-menu fs-13 fw-600 d-flex align-items-center justify-content-center {{ areActiveRoutes(['poin-of-sales.seller_index']) }}"
                            href="{{ route('poin-of-sales.seller_index') }}" target="_blank" data-toggle="tooltip"
                            data-title="{{ translate('POS') }}">
                            {{ translate('POS') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <div class="aiz-topbar-item mr-3">
                <div class="d-flex align-items-center">
                    <a class="btn btn-topbar has-transition w-35px h-35px btn-circle p-0 border border-1 border-gray-400 d-flex align-items-center justify-content-center hov-bg-primary hov-svg-white"
                        href="{{ route('home')}}" target="_blank" data-toggle="tooltip"
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
                        href="{{ route('seller.cache.clear') }}" data-toggle="tooltip"
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
            <div class="aiz-topbar-item mr-3">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <span
                            class="btn btn-topbar has-transition w-35px h-35px btn-circle p-0 border border-1 border-light d-flex align-items-center justify-content-center btn-light">
                            <span class="d-flex align-items-center position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.52" height="14.4"
                                    viewBox="0 0 11.52 14.4">
                                    <path id="Path_54477" data-name="Path 54477"
                                        d="M160.72-867.76a.7.7,0,0,1-.513-.207.7.7,0,0,1-.207-.513.7.7,0,0,1,.207-.513.7.7,0,0,1,.513-.207h.72v-5.04a4.216,4.216,0,0,1,.9-2.655,4.153,4.153,0,0,1,2.34-1.521v-.5a1.041,1.041,0,0,1,.315-.765,1.041,1.041,0,0,1,.765-.315,1.041,1.041,0,0,1,.765.315,1.041,1.041,0,0,1,.315.765v.5a4.153,4.153,0,0,1,2.34,1.521,4.216,4.216,0,0,1,.9,2.655v5.04h.72a.7.7,0,0,1,.513.207.7.7,0,0,1,.207.513.7.7,0,0,1-.207.513.7.7,0,0,1-.513.207Zm5.04,2.16a1.387,1.387,0,0,1-1.017-.423,1.387,1.387,0,0,1-.423-1.017h2.88a1.387,1.387,0,0,1-.423,1.017A1.387,1.387,0,0,1,165.76-865.6Z"
                                        transform="translate(-160 880)" fill="#232734" />
                                </svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span
                                        class="badge badge-sm badge-dot badge-circle badge-danger position-absolute absolute-top-right"
                                        style="top: -8px!important; right: -10px!important;"></span>
                                @endif
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xl py-0">
                        <div class="notifications">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-dark active" data-toggle="tab" data-type="order"
                                        href="javascript:void(0);" data-target="#orders-notifications" role="tab"
                                        id="orders-tab">{{ translate('Orders') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="preorder"
                                        href="javascript:void(0);" data-target="#preorders-notifications" role="tab"
                                        id="preorders-tab">{{ translate('Preorders') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="seller"
                                        href="javascript:void(0);" data-target="#sellers-notifications" role="tab"
                                        id="sellers-tab">{{ translate('Products') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" data-toggle="tab" data-type="seller"
                                        href="javascript:void(0);" data-target="#payouts-notifications" role="tab"
                                        id="sellers-tab">{{ translate('Payouts') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content c-scrollbar-light overflow-auto"
                                style="height: 75vh; max-height: 400px; overflow-y: auto;">
                                <div class="tab-pane active" id="orders-notifications" role="tabpanel">
                                    <x-unread_notification
                                        :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->take(20)->get()" />
                                </div>
                                <div class="tab-pane" id="preorders-notifications" role="tabpanel">
                                    <x-unread_notification
                                        :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PreorderNotification')->take(20)->get()" />
                                </div>
                                <div class="tab-pane" id="sellers-notifications" role="tabpanel">
                                    <x-unread_notification
                                        :notifications="auth()->user()->unreadNotifications()->where('type', 'like', '%shop%')->take(20)->get()" />
                                </div>
                                <div class="tab-pane" id="payouts-notifications" role="tabpanel">
                                    <x-unread_notification
                                        :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PayoutNotification')->take(20)->get()" />
                                </div>
                            </div>
                        </div>
                        <div class="text-center border-top">
                            <a href="{{ route('seller.all-notification') }}" class="text-reset d-block py-2">
                                {{ translate('View All Notifications') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="aiz-topbar-item">
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
            @php $authUser = auth()->user(); @endphp
            <div class="aiz-topbar-item ml-3">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="avatar avatar-sm" style="border: 2px solid var(--blue);">
                                <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                <div class="position-absolute right-0 bottom-0 bg-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="right:-4px!important; bottom:-3px!important; width:20px; height:20px;">
                                    @if ($authUser->shop?->verification_status == 1)
                                        <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960"
                                            width="16px" fill="#75FB4C">
                                            <path
                                                d="m429-336 238-237-51-51-187 186-85-84-51 51 136 135Zm51 240q-79 0-149-30t-122.5-82.5Q156-261 126-331T96-480q0-80 30-149.5t82.5-122Q261-804 331-834t149-30q80 0 149.5 30t122 82.5Q804-699 834-629.5T864-480q0 79-30 149t-82.5 122.5Q699-156 629.5-126T480-96Zm0-72q130 0 221-91t91-221q0-130-91-221t-221-91q-130 0-221 91t-91 221q0 130 91 221t221 91Zm0-312Z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                            width="20px" fill="#EA3323">
                                            <path
                                                d="m768-90-72-72q-49 32-103.5 49T480-96q-79.38 0-149.19-30T208.5-208.5Q156-261 126-330.81T96-480q0-58 17-112.5T162-696l-72-72 51-51 678 678-51 51Zm-287.89-78Q524-168 565-181t79-34L476-383l-47 47-136-136 51-51 85 85-4 4-210-210q-21 38-34 79t-13 84.89q0 129.72 91.19 220.92Q350.39-168 480.11-168ZM798-264l-53-52q22-38 34.5-79t12.5-84.89q0-129.72-91.19-220.92Q609.61-792 479.89-792 436-792 395-779.5T316-745l-52-53q48-32 103-49t113.27-17q79.27 0 149 30t122.23 82.5Q804-699 834-629.27t30 149Q864-422 847-367q-17 55-49 103ZM577-484l-50-51 89-89 51 50-90 90Zm-50-51ZM420-420Z" />
                                        </svg>
                                    @endif
                                </div>
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        @if ($authUser->shop?->verification_status == 0)
                            <a href="{{ route('seller.shop.verify') }}" class="dropdown-item">
                                <i class="las la-user-check"></i>
                                <span>{{translate('Verify now')}}</span>
                            </a>
                        @endif
                        <a href="{{ route('seller.profile.index') }}" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span>{{translate('Profile')}}</span>
                        </a>
                        <a href="{{ route('logout')}}" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{translate('Logout')}}</span>
                        </a>
                        @php
                            if(Session::has('locale')){
                                $locale = Session::get('locale', Config::get('app.locale'));
                            }
                            else{
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
                                        <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" class="flex-shrink-0 mr-3"
                                            alt="en" />
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