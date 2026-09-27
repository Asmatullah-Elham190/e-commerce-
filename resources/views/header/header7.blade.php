@php
    $topHeaderbgColor = get_setting('top_header_bg_color');
    $topHeaderTextColor = get_setting('top_header_text_color');
@endphp
<header
    class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif top-0 top-background-color-visibility stikcy-header-visibility"
    style="background-color: {{ $topHeaderbgColor }}">
    <div class="header-content-wrapper py-3 bg-transparent border-bottom position-relative">
        <div class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif">
            <div class="d-flex flex-nowrap align-items-center justify-content-between" style="gap: 16px;">

                <!-- Left (Mobile Humberger and Logo) -->
                <div class="d-flex align-items-center flex-shrink-0">
                    <button type="button" onclick="openLeftCanvas()" style="color: {{ $topHeaderTextColor }}"
                        class="border-0 bg-transparent fw-700 hov-text-blue has-transition d-xl-none mr-2 pl-0">
                        <i class="las la-bars fs-24"></i>
                    </button>
                    @php
                        $header_logo = get_setting('header_logo');
                    @endphp
                    <a href="{{ route('home') }}" class="navbar-logo d-flex align-items-center overflow-hidden">
                        @if ($header_logo != null)
                            <img id="header-logo-preview" src="{{ uploaded_asset($header_logo) }}"
                                alt="{{ env('APP_NAME') }}" class="img-fluid">
                        @else
                            <img id="header-logo-preview" src="{{ static_asset('assets/img/logo.png') }}"
                                alt="{{ env('APP_NAME') }}" class="img-fluid">
                        @endif
                    </a>
                </div>


                <!-- Middle (nav menu) -->
                <div class="nav-menu-list flex-shrink-0 d-none d-xl-block">
                    <ul class="d-flex align-items-center list-unstyled m-0" style="gap: 8px;">
                        @if (get_setting('megamenu_element') == 11)
                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle fs-13 fw-semibold top-text-color-visibility"
                                    style="color: {{ $topHeaderTextColor }}" href="#" id="mega-menu"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="las la-bars fs-13"></i>
                                    <span class="ml-1 mr-2">{{ translate('All Category') }}</span>
                                </a>

                                <div class="dropdown-menu mega-dropdown-menu bg-white w-100 left-0 right-0 c-scrollbar-light"
                                    aria-labelledby="mega-menu">
                                    <div
                                        class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif py-4">
                                        <!-- Megamenu Design 2 Start -->
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
                                                                @include(
                                                                    'frontend.partials.mega_sub_items',
                                                                    [
                                                                        'categories' => $sub_categories,
                                                                        'depth' => 0,
                                                                    ]
                                                                )
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
                                    </div>
                                </div>
                            </li>
                        @elseif (get_setting('megamenu_element') == 13)
                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle fs-13 fw-semibold top-text-color-visibility"
                                    style="color: {{ $topHeaderTextColor }}" href="#" id="mega-menu"
                                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="las la-bars fs-13"></i>
                                    <span class="ml-1 mr-2">{{ translate('All Category') }}</span>
                                </a>

                                <!-- Megamenu Design 3 Start -->
                                <div class="dropdown-menu mega-dropdown-menu bg-white w-100 left-0 right-0 c-scrollbar-light"
                                    aria-labelledby="mega-menu">
                                    <div
                                        class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif py-4">
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
                                                                @include(
                                                                    'frontend.partials.mega_sub_items',
                                                                    [
                                                                        'categories' => $sub_categories,
                                                                        'depth' => 0,
                                                                    ]
                                                                )
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
                                                        <img src="{{ uploaded_asset(get_setting('megamenu_banner2')) }}"
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
                                    </div>
                                </div>
                            </li>
                        @elseif (get_setting('megamenu_element') == 14)
                            <li class="nav-item">
                                <a class="nav-link dropdown-toggle fs-13 fw-semibold top-text-color-visibility"
                                    style="color: {{ $topHeaderTextColor }}" href="#" id="mega-menu"
                                    role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="las la-bars fs-13"></i>
                                    <span class="ml-1 mr-2">{{ translate('All Category') }}</span>
                                </a>

                                <div class="dropdown-menu mega-dropdown-menu bg-white w-100 left-0 right-0 c-scrollbar-light"
                                    aria-labelledby="mega-menu">
                                    <div
                                        class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif py-4">
                                        <!-- Megamenu Design 2 Start -->
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
                                                                @include(
                                                                    'frontend.partials.mega_sub_items',
                                                                    [
                                                                        'categories' => $sub_categories,
                                                                        'depth' => 0,
                                                                    ]
                                                                )
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
                                                                @include(
                                                                    'frontend.partials.mega_sub_items',
                                                                    [
                                                                        'categories' => $sub_categories,
                                                                        'depth' => 0,
                                                                    ]
                                                                )
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
                                    </div>
                                </div>
                            </li>
                        @else
                            <li class="nav-item">
                                <!-- Menu Bar -->
                                <div class="d-flex h-100">
                                    <!-- Categoty Menu Button -->
                                    <div class="all-category has-transition bg-black-10 rounded-1"
                                        id="category-menu-bar">
                                        <div
                                            class="d-flex align-items-center justify-content-center top-text-color-visibility w-40px h-40px">
                                            <i class="las la-bars has-transition" id="category-menu-bar-icon"
                                                style="font-size: 1.2rem !important"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Categoty Menus -->
                                <div class="hover-category-menu position-absolute top-100 left-0 right-0 z-3 d-none"
                                    id="click-category-menu">
                                    <div
                                        class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif">
                                        <div class="d-flex position-relative">
                                            <div class="position-static">
                                                @include(
                                                    'frontend.' .
                                                        get_setting('homepage_select') .
                                                        '.partials.category_menu')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif

                        @php
                            $labels = json_decode(get_setting('header_menu_labels'), true);
                            $links = json_decode(get_setting('header_menu_links'), true);
                        @endphp
                        @if ($labels != null)
                            @foreach ($labels as $key => $value)
                                @if ($key < 3)
                                    <li class="nav-item">
                                        <a class="nav-link fs-13 fw-semibold top-text-color-visibility @if (url()->current() == resolve_internal_menu_url($links[$key])) active @endif"
                                            style="color: {{ $topHeaderTextColor }}"
                                            href="{{ resolve_internal_menu_url($links[$key]) }}">
                                            {{ translate($value) }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                            @if (count($labels) > 3)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle fs-13 fw-semibold top-text-color-visibility"
                                        style="color: {{ $topHeaderTextColor }}" href="#" id="more-items"
                                        role="button" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        {{ translate('More') }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-mt" aria-labelledby="more-items">
                                        @foreach ($labels as $key => $value)
                                            @if ($key >= 3)
                                                <a class="dropdown-item @if (url()->current() == resolve_internal_menu_url($links[$key])) active @endif"
                                                    href="{{ resolve_internal_menu_url($links[$key]) }}">
                                                    {{ translate($value) }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        @endif
                        <!-- Search Trigger -->
                        <li class="nav-item">
                            <button
                                class="btn-search-trigger d-flex align-items-center justify-content-center rounded-pill w-60px h-40px border-0 hov-opacity-80 has-transition"
                                style="background-color: {{ get_setting('search_bg_color') }}" type="button"
                                id="mega-search" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                    width="20.001" height="20" viewBox="0 0 20.001 20">
                                    <path id="Path_3090" data-name="Path 3090"
                                        d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                        transform="translate(-1.854 -1.854)"
                                        fill="{{ get_setting('search_text_color') }}" />
                                    <path id="Path_3091" data-name="Path 3091"
                                        d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                        transform="translate(-5.2 -5.2)"
                                        fill="{{ get_setting('search_text_color') }}" />
                                </svg>
                            </button>
                            <div class="dropdown-menu mega-dropdown-menu bg-white w-100 left-0 right-0 c-scrollbar-light"
                                aria-labelledby="mega-search">

                                <div
                                    class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif py-4">

                                    <form action="{{ route('search') }}" method="GET" class="stop-propagation">

                                        <div class="search-input-group mx-auto position-relative">

                                            <input type="text"
                                                class="form-control shadow-sm fs-14 fw-400 rounded-pill"
                                                id="search" oninput="search()" name="keyword"
                                                @isset($query) value="{{ $query }}" @endisset
                                                placeholder="What do you want to shop today…!" autocomplete="off">

                                        </div>

                                    </form>

                                    <!-- Dynamic Search Result -->
                                    <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg mt-2 mx-auto"
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
                        </li>
                    </ul>
                </div>

                <!-- Right (switcher, Cart and Profile dropdown) -->
                <div class="flex-shrink-0 d-flex align-items-center justify-content-end  header-right-content">
                    <!-- Search For Mobile View Only -->
                    <div class="d-xl-none">
                        <button
                            class="btn-search-trigger d-flex align-items-center justify-content-center rounded-circle w-40px h-40px border-0 hov-opacity-80 has-transition"
                            style="background-color: {{ get_setting('search_bg_color') }}" type="button"
                            id="mega-search" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20.65" height="20.65"
                                viewBox="0 0 20.65 20.65">
                                <g id="Group_39019" data-name="Group 39019"
                                    transform="translate(-1194.665 -6032.665)">
                                    <g id="Group_723" data-name="Group 723" transform="translate(1195 6033)">
                                        <path id="Path_3090" data-name="Path 3090"
                                            d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                            transform="translate(-2.189 -2.189)"
                                            fill="{{ get_setting('search_text_color') }}" />
                                        <path id="Path_3091" data-name="Path 3091"
                                            d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                            transform="translate(-4.886 -4.886)"
                                            fill="{{ get_setting('search_text_color') }}" />
                                    </g>
                                </g>
                            </svg>
                        </button>
                        <div class="dropdown-menu mega-dropdown-menu bg-white w-100 left-0 right-0 c-scrollbar-light"
                            aria-labelledby="mega-search">
                            <div
                                class="@if (get_setting('show_full_width_header') == 'on') layout-container mx-auto px-3 @else container @endif py-2">
                                <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                                    <div class="search-input-group mx-auto position-relative">
                                        <input type="text" class="form-control shadow-sm fs-14 fw-400 rounded-pill"
                                            id="search-mobile" name="keyword" oninput="searchMobile()"
                                            @isset($query) value="{{ $query }}" @endisset
                                            placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">
                                        <button type="submit" class="btn position-absolute p-0"
                                            style="right: 12px; top: 50%; transform: translateY(-50%);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20.001 20">
                                                <path
                                                    d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                                    transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                                <path
                                                    d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                                    transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                            </svg>
                                        </button>
                                    </div>
                                </form>

                                <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg mt-2 mx-auto"
                                    style="min-height: 200px">
                                    <div class="search-preloader absolute-top-center">
                                        <div class="dot-loader">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                        </div>
                                    </div>
                                    <div class="search-nothing d-none p-3 text-center fs-16"></div>
                                    <div id="search-content-mobile" class="text-left"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Settings (switcher) -->
                    <div class="dropdown z-1045 flex-shrink-0">
                        <button class="btn btn-link p-0" type="button" data-toggle="dropdown"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 16 16" class="top-text-color-visibility"
                                style="color: {{ $topHeaderTextColor }};">

                                <line x1="2" y1="4" x2="14" y2="4"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></line>
                                <circle cx="8" cy="4" r="1.5" fill="none" stroke="currentColor"
                                    stroke-width="1.5"></circle>

                                <line x1="2" y1="8" x2="14" y2="8"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></line>
                                <circle cx="4" cy="8" r="1.5" fill="none" stroke="currentColor"
                                    stroke-width="1.5"></circle>

                                <line x1="2" y1="12" x2="14" y2="12"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></line>
                                <circle cx="12" cy="12" r="1.5" fill="none" stroke="currentColor"
                                    stroke-width="1.5"></circle>
                            </svg>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right py-0  switcher-dropdown-menu"
                            style="min-width: 200px;">
                            @if (get_setting('show_language_switcher') == 'on')
                                <div class="dropdown-submenu px-2 py-2 border-bottom border-soft-light hover-bg-light has-transition"
                                    style="min-height: 40px; position: relative;">
                                    <div class="form-control form-control-sm border-0 bg-transparent pt-0 pb-0 pr-0 pl-2 w-100 cursor-pointer hover-text-primary h-100 d-flex align-items-center justify-content-between"
                                        onclick="toggleChildDropdown(this, event)">
                                        <span>{{ $system_language->name }}</span>
                                        <i class="la la-angle-right"></i>
                                    </div>

                                    <div class="dropdown-menu dropdown-menu-right py-0 header-drop child-dropdown"
                                        style="min-width: 200px; left: 100%; top: 1px!important; margin-top: -1px;">
                                        @foreach (get_all_active_language() as $language)
                                            <div class="px-2 py-1 border-bottom border-soft-light hover-bg-light">
                                                <a href="javascript:void(0)" class="d-block text-dark"
                                                    data-flag="{{ $language->code }}"
                                                    onclick="changeLanguage('{{ $language->code }}')">
                                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                                        class="mr-1" alt="{{ $language->name }}" height="11">
                                                    {{ $language->name }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if (get_setting('show_currency_switcher') == 'on')
                                @php
                                    $system_currency = get_system_currency();
                                @endphp
                                <div class="dropdown-submenu px-2 py-2 border-bottom border-soft-light hover-bg-light"
                                    style="min-height: 40px; position: relative;">
                                    <div class="form-control form-control-sm border-0 bg-transparent pt-0 pb-0 pr-0 pl-2 w-100 cursor-pointer hover-text-primary h-100 d-flex align-items-center justify-content-between"
                                        onclick="toggleChildDropdown(this, event)">
                                        <span>
                                            {{ $system_currency->name ?? '' }}
                                            ({{ $system_currency->symbol ?? '' }})
                                        </span>
                                        <i class="la la-angle-right"></i>
                                    </div>

                                    <div class="dropdown-menu dropdown-menu-right py-0 header-drop child-dropdown"
                                        style="min-width: 200px; left: 100%; top: 1px!important; margin-top: -1px;">
                                        @foreach (get_all_active_currency() as $currency)
                                            <div class="px-2 py-1 border-bottom border-soft-light hover-bg-light">
                                                <a href="javascript:void(0)" class="d-block text-dark"
                                                    onclick="changeCurrency('{{ $currency->code }}')"
                                                    data-currency="{{ $currency->code }}">
                                                    {{ $currency->name }} ({{ $currency->symbol }})
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div>
                                @if (get_setting('vendor_system_activation') == 1)
                                    <a href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}"
                                        class="dropdown-item fs-13 py-2 px-3 hover-bg-light hover-text-primary border-bottom border-soft-light d-flex align-items-center"
                                        style="min-height: 40px;">
                                        {{ translate('Become a Seller') }}
                                    </a>
                                    <a href="{{ route('seller.login') }}"
                                        class="dropdown-item fs-13 py-2 px-3 hover-bg-light hover-text-primary border-bottom border-soft-light d-flex align-items-center"
                                        style="min-height: 40px;">
                                        {{ translate('Seller Login') }}
                                    </a>
                                @endif
                                @if (addon_is_activated('affiliate_system'))
                                    <a href="{{ route('affiliate.apply') }}"
                                        class="dropdown-item fs-13 py-2 px-3 hover-bg-light hover-text-primary border-bottom border-soft-light d-flex align-items-center"
                                        style="min-height: 40px;">
                                        {{ translate('Be an affiliate partner') }}
                                    </a>
                                @endif
                                @if (Auth::check() && auth()->user()->user_type == 'customer')
                                    <a href="{{ route('compare') }}"
                                        class="dropdown-item fs-13 py-2 px-3 hover-bg-light hover-text-primary border-bottom border-soft-light d-flex align-items-center"
                                        style="min-height: 40px;">
                                        {{ translate('Compare') }}
                                        @if (Session::has('compare'))
                                            ({{ count(Session::get('compare')) }})
                                        @endif
                                    </a>
                                    <a href="{{ route('wishlists.index') }}"
                                        class="dropdown-item fs-13 py-2 px-3 hover-bg-light hover-text-primary border-bottom border-soft-light d-flex align-items-center"
                                        style="min-height: 40px;">
                                        {{ translate('Wishlist') }}
                                        @if (Auth::check())
                                            @php $wishlistProductCount = get_wishlists()->count(); @endphp
                                            @if ($wishlistProductCount > 0)
                                                ({{ $wishlistProductCount }})
                                            @endif
                                        @endif
                                    </a>
                                @endif
                                @if (get_setting('helpline_number') != null)
                                    <a id="admin-helpline-preview"
                                        class="dropdown-item fs-13 py-2 px-3 hover-bg-light hover-text-primary border-bottom border-soft-light d-flex align-items-center"
                                        style="min-height: 40px; color: grey;">
                                        <span class="helpline-label">{{ translate('Helpline') }}</span>:&nbsp;
                                        <span
                                            class="helpline-number-preview">{{ get_setting('helpline_number') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Cart Start -->
                    <div class="d-none d-xl-flex align-items-center flex-shrink-0">
                        <div class="nav-cart-box dropdown h-100 show" id="cart_items" style="width: max-content;">
                            @include('frontend.partials.cart.cart')
                        </div>
                    </div>
                    <!-- Cart End -->

                    <!-- Notification Start (Desktop Only) -->
                    @if (Auth::check() && auth()->user()->user_type == 'customer')
                        <div class="d-none d-xl-block">
                            <div class="dropdown">
                                <a class="dropdown-toggle no-arrow fs-12" data-toggle="dropdown"
                                    href="javascript:void(0);" role="button" aria-haspopup="false"
                                    aria-expanded="false" onclick="nonLinkableNotificationRead()"
                                    style="color: {{ get_setting('top_header_text_color') }}">
                                    <span class="d-inline-block fs-12">
                                        <span
                                            class="btn btn-topbar btn-circle btn-light p-0 d-flex justify-content-center align-items-center"
                                            data-toggle="tooltip" data-title="{{ translate('Notification') }}"
                                            data-original-title="" title="">
                                            <span class="d-flex align-items-center position-relative">
                                                <div class="px-2 hov-svg-dark">
                                                    <i class="las la-bell fs-18 opacity-90"></i>
                                                </div>
                                                @if (auth()->user()->unreadNotifications->count() > 0)
                                                    <span
                                                        class="badge badge-sm badge-dot badge-circle badge-danger position-absolute absolute-top-right"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </span>
                                </a>
                                @auth
                                    <div
                                        class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0 notification-dropdownmenu">
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
                                                            $notificationShowDesign = get_setting(
                                                                'notification_show_type',
                                                            );
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
                                                                            $orderCode =
                                                                                $notification->data['order_code'];
                                                                            $route = route(
                                                                                'purchase_history.details',
                                                                                encrypt(
                                                                                    $notification->data['order_id'],
                                                                                ),
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
                                                                            $trackingCode =
                                                                                $data['tracking_code'] ?? '';
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
                                                                            $orderCode =
                                                                                $notification->data['order_code'];
                                                                            $route = route(
                                                                                'preorder.order_details',
                                                                                encrypt(
                                                                                    $notification->data['preorder_id'],
                                                                                ),
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
                            </div>
                        </div>
                    @endif
                    <!-- Notification End -->


                    @auth
                        <!--Profile Dropdown Start -->
                        <div class="dropdown flex-shrink-0 d-none d-xl-block">
                            <a href="#" style="color: {{ $topHeaderTextColor }}"
                                class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                data-toggle="dropdown">
                                @if ($user->avatar_original != null)
                                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                        alt="{{ translate('avatar') }}"
                                        class="profile-img mr-2 w-35px h-35px rounded-circle overflow-hidden"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                        alt="{{ translate('avatar') }}"
                                        class="profile-img mr-2 w-35px h-35px rounded-circle overflow-hidden"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                                <span class="fs-14 fw-bold d-inline text-truncate w-90px top-text-color-visibility"
                                    style="color: {{ $topHeaderTextColor }}">{{ $user->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown-menu">
                                @if (isAdmin())
                                    <a class="dropdown-item d-flex align-items-center has-transition"
                                        href="{{ route('admin.dashboard') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0"></path>
                                        </svg>
                                        <span
                                            class="fs-14 fw-400 text-gray has-transition">{{ translate('Dashboard') }}</span>
                                    </a>
                                @else
                                    <a class="dropdown-item d-flex align-items-center has-transition"
                                        href="{{ route('dashboard') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0"></path>
                                        </svg>
                                        <span
                                            class="fs-14 fw-400 text-gray has-transition">{{ translate('Dashboard') }}</span>
                                    </a>
                                @endif
                                @if (isCustomer())
                                    <a class="dropdown-item d-flex align-items-center has-transition"
                                        href="{{ route('purchase_history.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf"></path>
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf"></path>
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf"></path>
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf"></path>
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf"></path>
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf"></path>
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf"></path>
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf"></path>
                                            </g>
                                        </svg>
                                        <span
                                            class="fs-14 fw-400 text-gray has-transition">{{ translate('Purchase History') }}</span>
                                    </a>
                                    @if (addon_is_activated('preorder'))
                                        <a class="dropdown-item d-flex align-items-center has-transition"
                                            href="{{ route('preorder.order_list') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.002"
                                                viewBox="0 0 16 16.002">
                                                <path id="Union_63" data-name="Union 63"
                                                    d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z"
                                                    transform="translate(-14072 -885.998)" fill="#b5b5bf"></path>
                                            </svg>
                                            <span
                                                class="fs-14 fw-400 text-gray has-transition">{{ translate('Preorder List') }}</span>
                                        </a>
                                    @endif
                                    <a class="dropdown-item d-flex align-items-center has-transition"
                                        href="{{ route('digital_purchase_history.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                            viewBox="0 0 16.001 16">
                                            <g id="Group_25262" data-name="Group 25262"
                                                transform="translate(-1388.154 -562.604)">
                                                <path id="Path_2963" data-name="Path 2963"
                                                    d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                    transform="translate(1318.79 478.5)" fill="#b5b5bf"></path>
                                                <path id="Path_2964" data-name="Path 2964"
                                                    d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                    transform="translate(1324 486)" fill="#b5b5bf"></path>
                                            </g>
                                        </svg>
                                        <span
                                            class="fs-14 fw-400 text-gray has-transition">{{ translate('Downloads') }}</span>
                                    </a>
                                    @if (get_setting('conversation_system') == 1)
                                        <a class="dropdown-item d-flex align-items-center has-transition"
                                            href="{{ route('conversations.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_25263" data-name="Group 25263"
                                                    transform="translate(1053.151 256.688)">
                                                    <path id="Path_3012" data-name="Path 3012"
                                                        d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                        transform="translate(-1178 -341)" fill="#b5b5bf"></path>
                                                    <path id="Path_3013" data-name="Path 3013"
                                                        d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                        transform="translate(-1182 -337)" fill="#b5b5bf"></path>
                                                    <path id="Path_3014" data-name="Path 3014"
                                                        d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(-1181 -343.5)" fill="#b5b5bf"></path>
                                                    <path id="Path_3015" data-name="Path 3015"
                                                        d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                        transform="translate(-1181 -346.5)" fill="#b5b5bf"></path>
                                                </g>
                                            </svg>
                                            <span
                                                class="fs-14 fw-400 text-gray has-transition">{{ translate('Conversations') }}</span>
                                        </a>
                                    @endif
                                    @if (get_setting('wallet_system') == 1)
                                        <a class="dropdown-item d-flex align-items-center has-transition"
                                            href="{{ route('wallet.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                            width="16" height="16" fill="#b5b5bf"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102" clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf"></path>
                                                </g>
                                            </svg>
                                            <span
                                                class="fs-14 fw-400 text-gray has-transition">{{ translate('My Wallet') }}</span>
                                        </a>
                                    @endif
                                    <a class="dropdown-item d-flex align-items-center has-transition"
                                        href="{{ route('support_ticket.index') }}#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                            viewBox="0 0 16 16.001">
                                            <g id="Group_25259" data-name="Group 25259"
                                                transform="translate(-316 -1066)">
                                                <path id="Subtraction_184" data-name="Subtraction 184"
                                                    d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                    transform="translate(-16096 180)" fill="#b5b5bf"></path>
                                                <path id="Union_12" data-name="Union 12"
                                                    d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                    transform="translate(-16090.998 183.001)" fill="#b5b5bf"></path>
                                            </g>
                                        </svg>
                                        <span
                                            class="fs-14 fw-400 text-gray has-transition">{{ translate('Support Ticket') }}</span>
                                    </a>
                                @endif
                                <a class="dropdown-item d-flex align-items-center has-transition"
                                    href="{{ route('logout') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503" transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533"></path>
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533"></rect>
                                        </g>
                                    </svg>
                                    <span class="fs-14 fw-400 text-danger has-transition">Logout</span>
                                </a>

                            </div>
                        </div>
                        <!--Profile Dropdown End -->
                    @else
                        <!-- Login & Registration Start -->
                        <div class="d-none d-xl-flex align-items-center login-nav-item" style="gap: 16px;">
                            <div class="w-40px h-40px rounded-circle d-flex align-items-center justify-content-center border border-gray-400 user-icon-circle has-transition top-text-color-visibility"
                                style="color: {{ $topHeaderTextColor }}">
                                <i class="las la-user fs-24 has-transition"></i>
                            </div>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                @if (env('DEMO_MODE') == 'On')
                                    <a href="{{ route('handleDemoLogin') }}"
                                        class="fs-13 fw-500 hov-opacity-50 has-transition top-text-color-visibility"
                                        style="color: {{ $topHeaderTextColor }}">{{ translate('Login') }}</a>
                                @else
                                    <a href="{{ route('user.login') }}"
                                        class="fs-13 fw-500 hov-opacity-50 has-transition top-text-color-visibility"
                                        style="color: {{ $topHeaderTextColor }}">{{ translate('Login') }}</a>
                                @endif
                                <span class="text-gray">|</span>
                                <a href="{{ route('user.registration') }}"
                                    class="fs-13 fw-500 hov-opacity-50 has-transition top-text-color-visibility"
                                    style="color: {{ $topHeaderTextColor }}">{{ translate('Registration') }}</a>
                            </div>
                        </div>
                        <!-- Login & Registration End -->
                    @endauth
                </div>

            </div>
        </div>
    </div>
</header>

<!--=== Left Offcanvas Start ===-->
<div id="leftOffcanvas" class="left-offcanvas-md position-fixed top-0 bg-white overflow-hidden">
    <!-- Offcanvas Header -->
    <div class="border-bottom pb-15px pt-20px px-30px offcanvas-header-area">
        <div class="d-flex align-items-center justify-content-end">
            <button onclick="closeLeftCanvas()" class="border-0 bg-transparent p-0">
                <i class="las la-times fs-24 text-blue hov-text-danger has-transition"></i>
            </button>
        </div>
        @auth
            <!-- If Login -->
            <div class="d-flex align-items-center">
                @if ($user->avatar_original != null)
                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" alt="{{ translate('avatar') }}"
                        class="profile-img mr-2 w-35px h-35px rounded-circle overflow-hidden"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @else
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}"
                        class="profile-img mr-2 w-35px h-35px rounded-circle overflow-hidden"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @endif
                <span class="fs-14 fw-bold d-inline text-truncate w-180px"
                    title="Betty T. Niles">{{ $user->name }}</span>
            </div>
        @else
            <div class="d-flex align-items-center login-nav-item" style="gap: 16px;">
                <div
                    class="w-40px h-40px rounded-circle d-flex align-items-center justify-content-center border border-gray-400 user-icon-circle has-transition">
                    <i class="las la-user fs-24 has-transition"></i>
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    @if (env('DEMO_MODE') == 'On')
                        <a href="{{ route('handleDemoLogin') }}"
                            class="fs-13 fw-500 text-reset hov-text-blue has-transition">{{ translate('Login') }}</a>
                    @else
                        <a href="{{ route('user.login') }}"
                            class="fs-13 fw-500 text-reset hov-text-blue has-transition">{{ translate('Login') }}</a>
                    @endif
                    <span class="text-gray">|</span>
                    <a href="{{ route('user.registration') }}"
                        class="fs-13 fw-500 text-reset hov-text-blue has-transition">{{ translate('Registration') }}</a>
                </div>
            </div>
        @endauth
    </div>

    <!-- Offcanvas Body -->
    <div class="left-offcanvas-body px-30px inventory-offcanvas-body c-scrollbar-light">
        <ul class="list-unstyled m-0 py-3">
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <li>
                        <a href="{{ resolve_internal_menu_url(json_decode(get_setting('header_menu_links'), true)[$key]) }}"
                            class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">{{ translate($value) }}</a>
                    </li>
                @endforeach
            @endif
        </ul>
        <!-- If Login in -->
        <ul class="list-unstyled m-0 py-3 border-top border-bottom">
            @if (isAdmin())
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">{{ translate('Dashboard') }}</a>
                </li>
                <li>
                    <a href="{{ route('logout') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">{{ translate('Logout') }}</a>
                </li>
            @elseif(Auth::check() && auth()->user()->user_type == 'customer')
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">{{ translate('Dashboard') }}</a>
                </li>
                <li>
                    <a href="{{ route('customer.all-notifications') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">{{ translate('Notifications') }}</a>
                </li>
                <li>
                    <a href="{{ route('wishlists.index') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">
                        {{ translate('Wishlist') }}
                        @if (Auth::check())
                            @php $wishlistProductCount = get_wishlists()->count(); @endphp
                            @if ($wishlistProductCount > 0)
                                ({{ $wishlistProductCount }})
                            @endif
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('compare') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">
                        {{ translate('Compare') }}
                        @if (Session::has('compare'))
                            ({{ count(Session::get('compare')) }})
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}"
                        class="d-block py-2 fs-14 fw-500 text-reset hov-text-blue has-transition">{{ translate('Logout') }}</a>
                </li>
            @endif
        </ul>
    </div>

</div>

<!-- Overlay -->
<div id="leftOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
<!--=== Left Offcanvas End ===-->



<!--=== Left Offcanvas Start ===-->
{{-- <script>
    const leftOffcanvas = document.getElementById('leftOffcanvas');
    const leftOverlay = document.getElementById('leftOffcanvasOverlay');

    function openLeftCanvas() {
        leftOffcanvas.classList.add('active');
        leftOverlay.classList.add('active');
        document.body.classList.add('body-no-scroll');
    }

    function closeLeftCanvas() {
        leftOffcanvas.classList.remove('active');
        leftOverlay.classList.remove('active');
        document.body.classList.remove('body-no-scroll');
    }

    if (leftOverlay) {
        leftOverlay.addEventListener('click', closeLeftCanvas);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLeftCanvas();
    });
</script> --}}

<script>
    function openLeftCanvas() {
        const leftOffcanvas = document.getElementById('leftOffcanvas');
        const overlay = document.getElementById('leftOffcanvasOverlay');
        if (leftOffcanvas && overlay) {
            leftOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
        }
    }

    function closeLeftCanvas() {
        const leftOffcanvas = document.getElementById('leftOffcanvas');
        const overlay = document.getElementById('leftOffcanvasOverlay');
        if (leftOffcanvas && overlay) {
            leftOffcanvas.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('leftOffcanvasOverlay');
        if (overlay) overlay.addEventListener('click', closeLeftCanvas);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLeftCanvas();
        });
    });
</script>
<!--=== Left Offcanvas End ===-->
