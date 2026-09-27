@php
    $topHeaderTextColor = get_setting('top_header_text_color');
    $middleHeaderTextColor = get_setting('middle_header_text_color');
    $bottomHeaderTextColor = get_setting('bottom_header_text_color');
@endphp

<div class="top-navbar z-1035 h-35px h-sm-auto top-background-color-visibility"
    style="background-color: {{ get_setting('top_header_bg_color') }}">
    <div class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif">
        <div class="row">
            <div class="col-lg-6 col">
                <ul class="list-inline d-flex justify-content-between justify-content-lg-start mb-0">
                    <!-- Language switcher -->
                    @if (get_setting('show_language_switcher') == 'on')
                        <li class="list-inline-item dropdown mr-4 lang-visibility" id="lang-change">

                            <a href="javascript:void(0)" class="dropdown-toggle fs-12 py-2 top-text-color-visibility"
                                style="color: {{ $topHeaderTextColor }}" data-toggle="dropdown" data-display="static">
                                <span class="">{{ $system_language->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                @foreach (get_all_active_language() as $key => $language)
                                    <li>
                                        <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                            class="dropdown-item @if ($system_language->code == $language->code) active @endif text-dark">
                                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                                class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                                            <span class="language">{{ $language->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    <!-- Currency Switcher -->
                    @if (get_setting('show_currency_switcher') == 'on')
                        <li class="list-inline-item dropdown ml-auto ml-lg-0 mr-0 currency-visibility"
                            id="currency-change">
                            @php
                                $system_currency = get_system_currency();
                            @endphp

                            <a href="javascript:void(0)" class="dropdown-toggle fs-12 py-2 top-text-color-visibility"
                                style="color: {{ $topHeaderTextColor }}" data-toggle="dropdown" data-display="static">
                                {{ $system_currency->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                                @foreach (get_all_active_currency() as $key => $currency)
                                    <li>
                                        <a class="dropdown-item text-dark @if ($system_currency->code == $currency->code) active @endif"
                                            href="javascript:void(0)"
                                            data-currency="{{ $currency->code }}">{{ $currency->name }}
                                            ({{ $currency->symbol }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>

            <div class="col-6 text-right d-none d-lg-block top-text-color-visibility"
                style="color: {{ $topHeaderTextColor }}">
                <ul class="list-inline mb-0 h-100 d-flex justify-content-end align-items-center">
                    @if (get_setting('vendor_system_activation') == 1)
                        <!-- Seller Dropdown and Helpline -->
                        <li class="list-inline-item d-flex">

                            <!-- Become a Seller Dropdown -->
                            <div class="dropdown">
                                <a href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}"
                                    class="fs-12 dropdown-toggle top-text-color-visibility"
                                    style="color: {{ $topHeaderTextColor }}" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    {{ translate('Become a Seller !') }}
                                </a>

                                <!-- Dropdown Menu -->
                                <div class="dropdown-menu fs-12 p-0 mt-1">
                                    <a class="dropdown-item py-2 px-2 text-dark" href="{{ route('seller.login') }}">
                                        {{ translate('Login to Seller') }}
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if (get_setting('helpline_number'))
                        <li class="list-inline-item ml-3 pl-3 mr-0 pr-0 top-text-color-visibility"
                            style="color: {{ $topHeaderTextColor }}">
                            <a href="tel:{{ get_setting('helpline_number') }}"
                                class="fs-12 d-inline-block py-2 top-text-color-visibility"
                                style="color: {{ $topHeaderTextColor }}">
                                <span class="helpline-label">{{ translate('Helpline') }}:</span>
                                <span class="helpline-number-preview">{{ get_setting('helpline_number') }}</span>
                            </a>
                        </li>
                    @else
                        <li class="list-inline-item ml-3 p-2 mr-0 pr-0 top-text-color-visibility"
                            style="color: {{ $topHeaderTextColor }}">
                            <a class="fs-12 d-inline-block py-2 top-text-color-visibility"
                                style="color: {{ $topHeaderTextColor }}">
                                <span class="helpline-label" style="display:none;">{{ translate('Helpline') }}:</span>
                                <span class="helpline-number-preview"></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<header
    class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 violet-cart middle-background-color-visibility stikcy-header-visibility"
    style="background-color: {{ get_setting('middle_header_bg_color') }}">
    <!-- Search Bar -->
    <div class="position-relative logo-bar-area border-md-nonea z-1025">
        <div class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif">
            <div class="d-flex align-items-center">
                <!-- top menu sidebar button -->
                <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                    data-target=".aiz-top-menu-sidebar">
                    <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg"
                        width="16" height="16" viewBox="0 0 16 16">
                        <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                            transform="translate(0 7)" fill="#919199" />
                        <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                            fill="#919199" />
                        <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                            transform="translate(0 14)" fill="#919199" />
                    </svg>

                </button>
                <!-- Header Logo -->
                <div class="col-auto pl-0 pr-3 d-flex align-items-center">
                    <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img id="header-logo-preview" src="{{ uploaded_asset($header_logo) }}"
                                alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-40px" height="40">
                        @else
                            <img id="header-logo-preview" src="{{ static_asset('assets/img/logo.png') }}"
                                alt="{{ env('APP_NAME') }}" class="mw-100 h-30px h-md-40px" height="40">
                        @endif
                    </a>
                </div>
                <!-- Search Icon for small device -->
                <div class="d-lg-none ml-auto mr-0">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>
                <!-- Search field -->
                <div
                    class="front-header-search flex-grow-1 rounded-corner-8px d-flex align-items-center bg-white mx-xl-5">
                    <div class="position-relative flex-grow-1 px-3 px-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i
                                            class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="search-input-box">
                                    <input type="text"
                                        class="border border-soft-light form-control fs-14 hov-animate-outline rounded-corner-8px"
                                        id="search" name="keyword"
                                        @isset($query) value="{{ $query }}" @endisset
                                        placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                    <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                        width="20.001" height="20" viewBox="0 0 20.001 20">
                                        <path id="Path_3090" data-name="Path 3090"
                                            d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                            transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                        <path id="Path_3091" data-name="Path 3091"
                                            d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                            transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                    </svg>
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Search box -->
                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>

                @if (Auth::check() && auth()->user()->user_type == 'customer')
                    <!-- Compare -->
                    <div class="d-none d-lg-block ml-3 mr-0">
                        <div class="" id="compare">
                            @include('frontend.partials.compare')
                        </div>
                    </div>
                    <!-- Wishlist -->
                    <div class="d-none d-lg-block mr-3" style="margin-left: 36px;">
                        <div class="" id="wishlist">
                            @include('frontend.partials.wishlist')
                        </div>
                    </div>
                    <!-- Notifications -->
                    <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                        <li class="list-inline-item ml-3 mr-3 pr-3 pl-0 dropdown">
                            <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                href="javascript:void(0);" role="button" aria-haspopup="false"
                                aria-expanded="false" onclick="nonLinkableNotificationRead()">
                                <span class="position-relative d-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                                        viewBox="0 0 14.668 16">
                                        <path id="_26._Notification" data-name="26. Notification"
                                            d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                            transform="translate(-0.999)" fill="#91919b" />
                                    </svg>
                                    @if (Auth::check() && count($user->unreadNotifications) > 0)
                                        <span
                                            class="badge badge-primary badge-inline badge-pill absolute-top-right--10px unread-notification-count">{{ count($user->unreadNotifications) }}</span>
                                    @endif
                                </span>
                            </a>
                            @auth
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                    </div>
                                    <div class="c-scrollbar-light overflow-auto" style="max-height:300px;">
                                        <ul class="list-group list-group-flush">
                                            @forelse($user->unreadNotifications as $notification)
                                                @php
                                                    $showNotification = true;
                                                    if (
                                                        $notification->type ==
                                                            'App\Notifications\PreorderNotification' &&
                                                        !addon_is_activated('preorder')
                                                    ) {
                                                        $showNotification = false;
                                                    }
                                                @endphp
                                                @if ($showNotification)
                                                    @php
                                                        $isLinkable = true;
                                                        $notificationType = get_notification_type(
                                                            $notification->notification_type_id,
                                                            'id',
                                                        );
                                                        $notifyContent = $notificationType->getTranslation(
                                                            'default_text',
                                                        );
                                                        $notificationShowDesign = get_setting('notification_show_type');
                                                        if (
                                                            $notification->type ==
                                                                'App\Notifications\customNotification' &&
                                                            $notification->data['link'] == null
                                                        ) {
                                                            $isLinkable = false;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex">
                                                            @if ($notificationShowDesign != 'only_text')
                                                                <div class="size-35px mr-2">
                                                                    @php
                                                                        $notifyImageDesign = '';
                                                                        if ($notificationShowDesign == 'design_2') {
                                                                            $notifyImageDesign = 'rounded-1';
                                                                        } elseif (
                                                                            $notificationShowDesign == 'design_3'
                                                                        ) {
                                                                            $notifyImageDesign = 'rounded-circle';
                                                                        }
                                                                    @endphp
                                                                    <img src="{{ uploaded_asset($notificationType->image) }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/notification.png') }}';"
                                                                        class="img-fit h-100 {{ $notifyImageDesign }}">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                @if ($notification->type == 'App\Notifications\OrderNotification')
                                                                    @php
                                                                        $orderCode = $notification->data['order_code'];
                                                                        $route = route(
                                                                            'purchase_history.details',
                                                                            encrypt($notification->data['order_id']),
                                                                        );
                                                                        $orderCode =
                                                                            "<span class='text-blue'>" .
                                                                            $orderCode .
                                                                            '</span>';
                                                                        $notifyContent = str_replace(
                                                                            '[[order_code]]',
                                                                            $orderCode,
                                                                            $notifyContent,
                                                                        );
                                                                    @endphp
                                                                @elseif($notification->type == 'App\Notifications\OrderTrackingNotification')
                                                                    @php
                                                                        $data =
                                                                            $notification->data['data'] ??
                                                                            $notification->data;

                                                                        $orderCode = $data['order_code'] ?? '';
                                                                        $trackingCode = $data['tracking_code'] ?? '';

                                                                        $route = route(
                                                                            'purchase_history.details',
                                                                            encrypt($data['order_id']),
                                                                        );

                                                                        $notifyContent =
                                                                            "Tracking code for order <span class='text-blue'>" .
                                                                            $orderCode .
                                                                            "</span> is <span class='text-blue'>" .
                                                                            $trackingCode .
                                                                            '</span>';
                                                                    @endphp
                                                                @elseif($notification->type == 'App\Notifications\PreorderNotification')
                                                                    @php
                                                                        $orderCode = $notification->data['order_code'];
                                                                        $route = route(
                                                                            'preorder.order_details',
                                                                            encrypt($notification->data['preorder_id']),
                                                                        );
                                                                        $orderCode =
                                                                            "<span class='text-blue'>" .
                                                                            $orderCode .
                                                                            '</span>';
                                                                        $notifyContent = str_replace(
                                                                            '[[order_code]]',
                                                                            $orderCode,
                                                                            $notifyContent,
                                                                        );
                                                                    @endphp
                                                                @endif

                                                                @if ($isLinkable = true)
                                                                    <a
                                                                        href="{{ route('notification.read-and-redirect', encrypt($notification->id)) }}">
                                                                @endif
                                                                <span
                                                                    class="fs-12 text-dark text-truncate-2">{!! $notifyContent !!}</span>
                                                                @if ($isLinkable = true)
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="py-4 text-center fs-16">
                                                        {{ translate('No notification found') }}
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="text-center border-top">
                                        <a href="{{ route('customer.all-notifications') }}"
                                            class="text-secondary fs-12 d-block py-2">
                                            {{ translate('View All Notifications') }}
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </li>
                    </ul>
                @endif

                <div class="d-none d-xl-block ml-auto mr-0">
                    @auth
                        <span
                            class="d-flex align-items-center nav-user-info py-20px @if (isAdmin()) ml-5 @endif"
                            id="nav-user-info">
                            <!-- Image -->
                            <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                @if ($user->avatar_original != null)
                                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" class="img-fit h-100"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>
                            <!-- Name -->
                            <h4 class="h5 fs-14 fw-700 ml-2 mb-0 middle-text-color-visibility"
                                style="color: {{ $middleHeaderTextColor }}">{{ $user->name }}</h4>
                        </span>
                    @else
                        <!--Login & Registration -->
                        <span class="d-flex align-items-center nav-user-info ml-3">
                            <!-- Image -->
                            <span
                                class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                                    viewBox="0 0 19.902 20.012">
                                    <path id="fe2df171891038b33e9624c27e96e367"
                                        d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                        transform="translate(-2.064 -1.995)" fill="#91919b" />
                                </svg>
                            </span>
                            @if (env('DEMO_MODE') == 'On')
                                <a href="{{ route('handleDemoLogin') }}" style="color: {{ $middleHeaderTextColor }}"
                                    class="middle-text-color-visibility opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                            @else
                                <a href="{{ route('user.login') }}" style="color: {{ $middleHeaderTextColor }}"
                                    class="middle-text-color-visibility opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                            @endif
                            <a href="{{ route('user.registration') }}" style="color: {{ $middleHeaderTextColor }}"
                                {{-- <a href="{{ route('user.registration') }}" --}}
                                class="middle-text-color-visibility opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
                        </span>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Loged in user Menus -->
        <div class="hover-user-top-menu position-absolute top-100 left-0 right-0 z-3">
            <div class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            @if (isAdmin())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (isCustomer())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>

                                @if (addon_is_activated('preorder'))
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('preorder.order_list') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.002"
                                                viewBox="0 0 16 16.002">
                                                <path id="Union_63" data-name="Union 63"
                                                    d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z"
                                                    transform="translate(-14072 -885.998)" fill="#b5b5bf" />
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Preorder List') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('digital_purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                            viewBox="0 0 16.001 16">
                                            <g id="Group_25262" data-name="Group 25262"
                                                transform="translate(-1388.154 -562.604)">
                                                <path id="Path_2963" data-name="Path 2963"
                                                    d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                    transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                <path id="Path_2964" data-name="Path 2964"
                                                    d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                    transform="translate(1324 486)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                    </a>
                                </li>
                                @if (get_setting('conversation_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('conversations.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_25263" data-name="Group 25263"
                                                    transform="translate(1053.151 256.688)">
                                                    <path id="Path_3012" data-name="Path 3012"
                                                        d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                        transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                    <path id="Path_3013" data-name="Path 3013"
                                                        d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                        transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                    <path id="Path_3014" data-name="Path 3014"
                                                        d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                    <path id="Path_3015" data-name="Path 3015"
                                                        d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                        transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (get_setting('wallet_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('wallet.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                                height="16" viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                            width="16" height="16" fill="#b5b5bf" />
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102"
                                                    clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('support_ticket.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                            viewBox="0 0 16 16.001">
                                            <g id="Group_25259" data-name="Group 25259"
                                                transform="translate(-316 -1066)">
                                                <path id="Subtraction_184" data-name="Subtraction 184"
                                                    d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                    transform="translate(-16096 180)" fill="#b5b5bf" />
                                                <path id="Union_12" data-name="Union 12"
                                                    d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                    transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503"
                                            transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Bar -->
    <div class="d-none d-lg-block position-relative h-50px border-bottom bottom-background-color-visibility"
        style="background-color: {{ get_setting('bottom_header_bg_color') }}">
        <div class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif h-100">
            <div class="d-flex h-100">
                @if (get_setting('megamenu_element') == 11 ||
                        get_setting('megamenu_element') == 13 ||
                        get_setting('megamenu_element') == 14)
                    <!-- Megamenu Start -->
                    <div class="mega-menu-wrapper d-none d-xl-flex align-items-center">
                        <a class="nav-link dropdown-toggle fs-13 fw-semibold bottom-text-color-visibility p-0"
                            href="#" id="mega-menu" style="color: {{ $bottomHeaderTextColor }}"
                            role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-bars fs-13"></i>
                            <span class="ml-1 mr-2">{{ translate('All Category') }}</span>
                        </a>
                        <div class="dropdown-menu mega-dropdown-menu bg-white w-100 left-0 right-0 c-scrollbar-light global-megamenu"
                            aria-labelledby="mega-menu">
                            <div
                                class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif py-4">
                                <!-- Megamenu Design 2 Start -->
                                @if (get_setting('megamenu_element') == 11)
                                    <div class="megamenu-desingn-two">
                                        @php
                                            $mega_categories = get_level_zero_categories()->take(6);
                                        @endphp
                                        <div class="row">
                                            @foreach ($mega_categories as $mega_cat)
                                                @php
                                                    $mega_cat_name = $mega_cat->getTranslation('name');
                                                    $sub_categories = $mega_cat->childrenCategories ?? collect();
                                                @endphp
                                                <div class="col-3 col-xl-2">
                                                    {{-- Category Banner Image --}}
                                                    <div
                                                        class="img-aspect-ratio-200px overflow-hidden border border-gray-300">
                                                        <a href="{{ route('products.category', $mega_cat->slug) }}">
                                                            <img src="{{ isset($mega_cat->banner) ? uploaded_asset($mega_cat->banner) : static_asset('assets/img/placeholder-rect.jpg') }}"
                                                                alt="{{ $mega_cat_name }}"
                                                                class="img-fit w-100 h-100 lazyload"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                        </a>
                                                    </div>
                                                    {{-- Category Title + Recursive Subcategories --}}
                                                    <ul class="list-unstyled mt-2 mb-3">
                                                        <li class="fs-16 fw-700 mb-2">
                                                            <a class="text-reset hov-text-primary"
                                                                href="{{ route('products.category', $mega_cat->slug) }}">
                                                                {{ $mega_cat_name }}
                                                            </a>
                                                        </li>
                                                        @if ($sub_categories->count())
                                                            @include('frontend.partials.mega_sub_items', [
                                                                'categories' => $sub_categories,
                                                                'depth' => 0,
                                                            ])
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- More Items Button -->
                                        <div class="mt-1 d-flex justify-content-end">
                                            <a href="{{ route('categories.all') }}"
                                                class="btn btn-primary text-blue fs-16 fw-bold bg-transparent border-0 hov-opacity-70 has-transition px-0">
                                                {{ translate('More Items') }}
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Megamenu Design 2 End -->
                                @elseif(get_setting('megamenu_element') == 13)
                                    <div class="megamenu-desingn-three">
                                        @php
                                            $mega_categories_three = get_level_zero_categories()->take(7);
                                        @endphp
                                        <div class="row border-bottom pb-3">
                                            @foreach ($mega_categories_three->slice(0, 6) as $mega_cat)
                                                @php
                                                    $mega_cat_name = $mega_cat->getTranslation('name');
                                                    $sub_categories = $mega_cat->childrenCategories ?? collect();
                                                @endphp
                                                <div class="col-2">
                                                    <ul class="list-unstyled mb-3">
                                                        <li class="fs-16 fw-700 mb-2">
                                                            <a class="text-reset hov-text-primary"
                                                                href="{{ route('products.category', $mega_cat->slug) }}">
                                                                {{ $mega_cat_name }}
                                                            </a>
                                                        </li>
                                                        @if ($sub_categories->count())
                                                            @include('frontend.partials.mega_sub_items', [
                                                                'categories' => $sub_categories,
                                                                'depth' => 0,
                                                            ])
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row pt-4">
                                            <div class="col-4">
                                                <ul class="list-unstyled mb-3">
                                                    <li class="fs-14 fw-700 mb-2">
                                                        <a class="text-reset hov-text-primary"
                                                            href="{{ route('flash-deals') }}">
                                                            {{ translate('Flash Sales') }}
                                                        </a>
                                                    </li>
                                                    <li class="fs-14 fw-700 mb-2">
                                                        <a class="text-reset hov-text-primary"
                                                            href="{{ route('todays-deal') }}">
                                                            {{ translate("Today's Deals") }}
                                                        </a>
                                                    </li>
                                                    <li class="fs-14 fw-700 mb-2">
                                                        <a class="text-reset hov-text-primary"
                                                            href="{{ route('brands.all') }}">
                                                            {{ translate('Brands') }}
                                                        </a>
                                                    </li>
                                                    <li class="fs-14 fw-700 mb-2">
                                                        <a class="text-reset hov-text-primary"
                                                            href="{{ route('sellers') }}">
                                                            {{ translate('Sellers') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-4">
                                                <div class="w-100 h-160px overflow-hidden rounded-2">
                                                    <img src="{{ uploaded_asset(get_setting('megamenu_banner1')) }}"
                                                        alt="Logo" class="img-fit w-100 h-100">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="w-100 h-160px overflow-hidden rounded-2">
                                                    <img src="{{ uploaded_asset(get_setting('megamenu_banner1')) }}"
                                                        alt="Logo" class="img-fit w-100 h-100">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- More Items Button -->
                                        <div class="mt-1 d-flex justify-content-end">
                                            <a href="{{ route('categories.all') }}"
                                                class="btn btn-primary text-blue fs-16 fw-bold bg-transparent border-0 hov-opacity-70 has-transition px-0">
                                                {{ translate('More Items') }}
                                            </a>
                                        </div>
                                    </div>
                                @elseif(get_setting('megamenu_element') == 14)
                                    <div class="megamenu-desingn-four">
                                        @php
                                            $mega_categories_four = get_level_zero_categories()->take(8);
                                        @endphp
                                        <div class="row">
                                            @foreach ($mega_categories_four->slice(0, 4) as $mega_cat)
                                                @php
                                                    $mega_cat_name = $mega_cat->getTranslation('name');
                                                    $sub_categories = $mega_cat->childrenCategories ?? collect();
                                                @endphp
                                                <div class="col-2">
                                                    <ul class="list-unstyled mb-3">
                                                        <li class="fs-16 fw-700 mb-2">
                                                            <a class="text-reset hov-text-primary"
                                                                href="{{ route('products.category', $mega_cat->slug) }}">
                                                                {{ $mega_cat_name }}
                                                            </a>
                                                        </li>
                                                        @if ($sub_categories->count())
                                                            @include('frontend.partials.mega_sub_items', [
                                                                'categories' => $sub_categories,
                                                                'depth' => 0,
                                                            ])
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                            <div class="col-4">
                                                <div class="w-100 h-160px overflow-hidden rounded-2">
                                                    <img src="{{ uploaded_asset(get_setting('megamenu_banner1')) }}"
                                                        alt="Logo" class="img-fit w-100 h-100">
                                                </div>
                                            </div>
                                            @foreach ($mega_categories_four->slice(4, 4) as $mega_cat)
                                                @php
                                                    $mega_cat_name = $mega_cat->getTranslation('name');
                                                    $sub_categories = $mega_cat->childrenCategories ?? collect();
                                                @endphp
                                                <div class="col-2">
                                                    <ul class="list-unstyled mb-3">
                                                        <li class="fs-16 fw-700 mb-2">
                                                            <a class="text-reset hov-text-primary"
                                                                href="{{ route('products.category', $mega_cat->slug) }}">
                                                                {{ $mega_cat_name }}
                                                            </a>
                                                        </li>
                                                        @if ($sub_categories->count())
                                                            @include('frontend.partials.mega_sub_items', [
                                                                'categories' => $sub_categories,
                                                                'depth' => 0,
                                                            ])
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                            <div class="col-4">
                                                <div class="w-100 h-160px overflow-hidden rounded-2">
                                                    <img src="{{ uploaded_asset(get_setting('megamenu_banner1')) }}"
                                                        alt="Logo" class="img-fit w-100 h-100">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- More Items Button -->
                                        <div class="mt-1 d-flex justify-content-end">
                                            <a href="{{ route('categories.all') }}"
                                                class="btn btn-primary text-blue fs-16 fw-bold bg-transparent border-0 hov-opacity-70 has-transition px-0">
                                                {{ translate('More Items') }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Categoty Menu Button -->
                    <div class="d-none d-xl-block all-category has-transition" id="category-menu-bar">
                        <div class="h-100"
                            style="padding-top: 12px;padding-bottom: 12px; width:270px; cursor: pointer;">
                            <div class="d-flex align-items-center justify-content-between bottom-text-color-visibility"
                                style="color: {{ $bottomHeaderTextColor }}">
                                <div>
                                    <span class="fw-700 fs-16 mr-3">{{ translate('Categories') }}</span>
                                    <a href="{{ route('categories.all') }}" class="text-reset categoriesAll">
                                        <span
                                            class="d-none d-lg-inline-block animate-underline-white">({{ translate('See All') }})</span>
                                    </a>
                                </div>
                                <i class="las la-angle-down has-transition" id="category-menu-bar-icon"
                                    style="font-size: 1.2rem !important"></i>
                            </div>
                        </div>
                    </div>

                @endif
                <!-- Header Menus -->

                <div class="ml-xl-4 w-100 overflow-hidden">
                    <div class="d-flex align-items-center justify-content-center justify-content-xl-start h-100">
                        <ul class="list-inline mb-0 pl-0 hor-swipe c-scrollbar-light">
                            @if (get_setting('header_menu_labels') != null)
                                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                    <li class="list-inline-item mr-0 animate-underline-white">
                                        <a href="{{ resolve_internal_menu_url(json_decode(get_setting('header_menu_links'), true)[$key]) }}"
                                            class="fs-13 px-3 py-3 d-inline-block fw-700 header_menu_links hov-bg-black-10 bottom-text-color-visibility
                                                                            @if (url()->current() == resolve_internal_menu_url(json_decode(get_setting('header_menu_links'), true)[$key])) active @endif"
                                            style="color: {{ $bottomHeaderTextColor }}">
                                            {{ translate($value) }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <!-- Cart -->
                <div class="d-none d-xl-block align-self-stretch ml-5 mr-0 has-transition" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items" style="width: max-content;">
                        @include('frontend.partials.cart.cart')
                    </div>
                </div>
            </div>
        </div>
        <!-- Categoty Menus -->
        <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 z-3 d-none"
            id="click-category-menu">
            <div class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif">
                <div class="d-flex position-relative">
                    <div class="position-static">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.category_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
