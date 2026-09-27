@extends('frontend.layouts.app')

@php
    $form_all_preorder_page = session('preorder_all_page');
    session()->forget('preorder_all_page');
@endphp

@if (isset($category_id))
    @php
        $category_search = $category;
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
        $meta_keywords = $category->meta_keywords;
    @endphp
@elseif (isset($brand_id))
    @php
        $brand_name = get_single_brand($brand_id)->name;
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
        $meta_keywords = get_single_brand($brand_id)->meta_keywords;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop
@section('meta_keywords'){{ $meta_keywords ?? '' }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')

    <section class="mb-1">
        <div class="container sm-px-0 pt-1">
            <form class="" id="search-form" action="" method="GET">
                @if (isset($brand_id) && $brand_id != null)
                    <input type="hidden" name="brand_id" value="{{ $brand_id }}">
                @endif
                <div class="row">

                    <!-- Sidebar Filters -->
                    <div class="col-xl-3">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                                data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar scroll-bar-show c-scrollbar-light text-left">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb"
                                        data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>

                                <!-- Categories -->
                                <div class="bg-white border-bottom-listing-sidebar">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#collapse_1"
                                            class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse">

                                            {{ translate('Categories') }}
                                        </a>
                                    </div>
                                    <div class="collapse show" id="collapse_1">
                                        <!-- Product Category -->
                                        <div class="">
                                            <div class=" @if ($errors->has('category_ids') || $errors->has('category_id')) border border-danger @endif">
                                                @php
                                                    if ($category_id) {
                                                        $old_categories = [$category_id];
                                                    } else {
                                                        $old_categories = [];
                                                    }
                                                @endphp
                                                {{-- general category list  --}}
                                                <div class="px-20px pb-10px display-none" id="general_cagegories_box">
                                                    <div id="category_filter" class="h-300px overflow-auto no-scrollbar">
                                                        <ul class="hummingbird-treeview-converter2 list-unstyled"
                                                            data-checkbox-name="categories[]">
                                                            @foreach ($categories as $category)
                                                                {{-- @if ($category->products_count > 0) --}}
                                                                <li d-item="{{ $category->products_count }}"
                                                                    id="generel_{{ $category->id }}">
                                                                    {{ $category->getTranslation('name') }}
                                                                    @if ($category->products_count > 0)
                                                                        {{ '   (' . $category->products_count . ')' }}
                                                                    @endif
                                                                </li>
                                                                {{-- @endif --}}
                                                                @foreach ($category->childrenCategories as $childCategory)
                                                                    @include(
                                                                        'frontend.product_listing_page_child_category',
                                                                        ['child_category' => $childCategory]
                                                                    )
                                                                @endforeach
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- preorder category list  --}}
                                                <div class="px-20px pb-10px display-none" id="preorder_cagegories_box">
                                                    <div id="category_filter_preorder"
                                                        class="h-300px overflow-auto no-scrollbar">
                                                        <ul class="hummingbird-treeview-converter2 list-unstyled"
                                                            data-checkbox-name="categories_preorder[]">
                                                            @foreach ($preorder_categories as $category)
                                                                @if ($category->products_count > 0)
                                                                    <li d-item="{{ $category->products_count }}"
                                                                        id="preorder_{{ $category->id }}">
                                                                        {{ $category->getTranslation('name') }}{{ '   (' . $category->products_count . ')' }}
                                                                    </li>
                                                                @endif
                                                                @foreach ($category->childrenCategories as $childCategory)
                                                                    @include(
                                                                        'frontend.product_listing_page_child_category_preorder',
                                                                        ['child_category' => $childCategory]
                                                                    )
                                                                @endforeach
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Price range -->
                                <div class="bg-white border-bottom-listing-sidebar">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#collapse_price"
                                            class="dropdown-toggle collapsed filter-section text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse" data-target="#collapse_price">
                                            {{ translate('Price range') }}
                                        </a>
                                    </div>
                                    <div class="collapse" id="collapse_price">
                                        <div class="px16px py22px hover-effect">
                                            @php
                                                $product_count = get_products_count();
                                            @endphp

                                            <div class="aiz-range-slider">


                                                <div id="input-slider-range"
                                                    data-range-value-min="@if (true) 0 @else {{ get_product_min_unit_price() }} @endif"
                                                    data-range-value-max="@if ($product_count < 1) 0 @else {{ get_product_max_unit_price() }} @endif">
                                                    <div
                                                        style="width: 4px; height: 16px; background-color: #DFDFE6; position: absolute; top: -7px; left: -1px;  ">
                                                    </div>
                                                    <div
                                                        style="width: 4px; height: 16px; background-color: #DFDFE6; position: absolute; top: -7px; right: -1px;  ">
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                            {{-- @if (isset($min_price)) data-range-value-low="{{ $min_price }}"
                                                            @elseif($products->min('unit_price') > 0)
                                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                            @else --}} data-range-value-low="0"
                                                            {{-- @endif --}} id="input-slider-range-value-low">0</span>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                            {{-- @if (isset($max_price)) data-range-value-high="{{ $max_price }}"
                                                            @elseif($products->max('unit_price') > 0)
                                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                            @else --}}
                                                            data-range-value-high="{{ get_product_max_unit_price() / 2 }}"
                                                            {{-- @endif --}} id="input-slider-range-value-high"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Hidden Items -->
                                        <input type="hidden" name="min_price" value="">
                                        <input type="hidden" name="max_price" value="">
                                    </div>
                                </div>


                                <!-- Attributes -->
                                @foreach ($attributes as $attribute)
                                    @if ($attribute->product_count > 0)
                                        <div class="bg-white preorder-time-hide border-bottom-listing-sidebar">
                                            <div class="fs-16 fw-700 p-3">
                                                <a href="#"
                                                    class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                    data-toggle="collapse"
                                                    data-target="#collapse_{{ str_replace(' ', '_', preg_replace('/[^a-zA-Z]/', '', $attribute->name)) }}"
                                                    style="white-space: normal;">
                                                    {{ $attribute->getTranslation('name') }}
                                                </a>
                                            </div>
                                            @php
                                                $show = '';
                                                foreach ($attribute->attribute_values as $attribute_value) {
                                                    if (in_array($attribute_value->value, $selected_attribute_values)) {
                                                        $show = 'show';
                                                    }
                                                }
                                            @endphp
                                            <div class="collapse {{ $show }}"
                                                id="collapse_{{ str_replace(' ', '_', preg_replace('/[^a-zA-Z]/', '', $attribute->name)) }}">
                                                <div class="px-3 aiz-checkbox-list">
                                                    @foreach ($attribute->attribute_values as $attribute_value)
                                                        @if ($attribute_value->product_count > 0)
                                                            <label class="aiz-checkbox mb-3 d-flex align-items-center ">
                                                                <input type="checkbox" name="selected_attribute_values[]"
                                                                    value="{{ $attribute_value->value }}"
                                                                    @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                                    onchange="filter(event)">
                                                                <span class="aiz-square-check border_black"></span>
                                                                <span
                                                                    class="fs-14 fw-400 text-dark hover-effect-list-item  @if (in_array($attribute_value->value, $selected_attribute_values)) fw-bold @endif">{{ $attribute_value->value }}
                                                                    {{ '(' . $attribute_value->product_count . ')' }}</span>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-link p-0 m-0 mb-3 font-weight-bold see_more_toggle_btn">
                                                        See More <i class="las la-angle-down fs-12 fw-600 "></i></button>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-link p-0 m-0 mb-3 font-weight-bold less_toggle_btn">See
                                                        Less <i class="las la-angle-up fs-12 fw-600 "></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Color -->
                                @if (get_setting('color_filter_activation'))
                                    <div class="bg-white  mb-3 preorder-time-hide">
                                        <div class="fs-16 fw-700 p-3">
                                            <a href="#"
                                                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                data-toggle="collapse" data-target="#collapse_color">
                                                {{ translate('Filter by color') }}
                                            </a>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach ($colors as $key => $color) {
                                                if (isset($selected_color) && $selected_color == $color->code) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div class="collapse {{ $show }}" id="collapse_color">
                                            <div class="px-3 aiz-checkbox-list">
                                                @foreach ($colors as $key => $color)
                                                    @if ($color->product_count > 0)
                                                        <label class="aiz-checkbox mb-3 d-flex align-items-center ">
                                                            <input type="checkbox" name="colors[]"
                                                                value="{{ $color->code }}"
                                                                @if (isset($selected_color) && $selected_color == $color->code) checked @endif
                                                                onchange="filter(event)">
                                                            <span class="aiz-square-check border_black"></span>
                                                            <div class="d-flex">

                                                                <div
                                                                    style="width: 20px; height: 20px; background-color: {{ $color->code }};border-radius: 50%; margin-right: 10px;">
                                                                </div>
                                                                <span
                                                                    class="fs-14 text-dark hover-effect-list-item">{{ $color->name }}
                                                                    {{ '(' . $color->product_count . ')' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-link p-0 m-0 mb-3 font-weight-bold see_more_toggle_btn">
                                                    See More <i class="las la-angle-down fs-12 fw-600 "></i></button>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-link p-0 m-0 mb-3 font-weight-bold less_toggle_btn">See
                                                    Less <i class="las la-angle-up fs-12 fw-600 "></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Attributes for preorder product -->
                                <div
                                    class="bg-white  mb-3 mt-3 preorder-time-show display-none border-bottom-listing-sidebar">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#"
                                            class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                            data-toggle="collapse" data-target="#collapse_availability_filter"
                                            style="white-space: normal;">
                                            {{ translate('Filter by Availability') }}
                                        </a>
                                    </div>
                                    @php
                                        $show = $is_available !== null ? 'show' : '';
                                    @endphp
                                    <div class="collapse {{ $show }}" id="collapse_availability_filter">
                                        <div class="p-3 aiz-checkbox-list">
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value="1"
                                                    @if ($is_available == 1) checked @endif
                                                    onchange="filter(event)">
                                                <span class="aiz-square-check border_black"
                                                    style="--primary: var(--black-50);"></span>
                                                <span
                                                    class="fs-14 fw-400 text-dark hover-effect-list-item">{{ translate('Available Now') }}</span>
                                            </label>
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value="0"
                                                    @if ($is_available === '0') checked @endif
                                                    onchange="filter(event)">
                                                <span class="aiz-square-check border_black"></span>
                                                <span
                                                    class="fs-14 fw-400 text-dark hover-effect-list-item">{{ translate('Upcoming') }}</span>
                                            </label>
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value=""
                                                    @if ($is_available === null) checked @endif
                                                    onchange="filter(event)">
                                                <span class="aiz-square-check border_black"></span>
                                                <span
                                                    class="fs-14 fw-400 text-dark hover-effect-list-item">{{ translate('All') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Contents -->
                    <div class="col-xl-9">
                        @if (addon_is_activated('preorder') && Route::currentRouteName() == 'search')
                            <div class="product-tab mt-2">
                                @php
                                    $activeClasses = 'bg-soft-dark text-white';
                                    $inActiveClasses = 'preorder-border-dashed  text-muted  fw-600';
                                @endphp
                                <div class="p-0 aiz-radio-inline">
                                    <label class="aiz-megabox pl-0 mr-2 " data-toggle="tooltip"
                                        data-title="{{ translate('General Products') }}">
                                        <input type="radio" name="product_type" value="general_product"
                                            onchange="filter(event)">
                                        <span id="product_type_badge_general"
                                            class="badge badge-inline fs-12 p-3 rounded-3 preorder-border-dashed my-2 text-muted  fw-600">
                                            {{ translate('General Products') }}
                                            <span class="badge badge-inline bg-soft-dark fs-12  p-1 rounded-3 text-white"
                                                style="background: {{ translate('General Products') }};"></span>
                                        </span>
                                    </label>
                                    <label class="aiz-megabox pl-0 " data-toggle="tooltip"
                                        data-title="{{ translate('Preorder Products') }}">
                                        <input type="radio" name="product_type" value="preorder_product"
                                            onchange="filter(event)">
                                        <span id="product_type_badge_preorder"
                                            class="badge badge-inline fs-12 p-3 rounded-3  preorder-border-dashed my-2 text-muted  fw-600">
                                            {{ translate('Preorder Products') }}
                                            <span
                                                class="badge badge-inline bg-soft-dark fs-12  my-2 p-1 rounded-3 text-white"
                                                style="background: {{ translate('Preorder Products') }};"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endif
                        <!-- Breadcrumb -->
                        <ul class="breadcrumb mb-0 bg-transparent py-0 px-0 mt-2 d-flex align-items-center">
                            <li class=" has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                            </li>
                            @if (!isset($category_id) && !isset($brand_id))
                                <i class="las la-angle-right fs-12 fw-600"></i>
                                <li class=" fw-700  text-dark fs-12">
                                    "{{ translate('All Categories') }}"
                                </li>
                            @else
                                <i class="las la-angle-right fs-12 fw-600 show_cat1 d-none"></i>
                                <li class=" fw-700  text-dark fs-12 show_cat1 d-none">
                                    "{{ translate('All Categories') }}"
                                </li>

                                @if (!isset($brand_id))
                                    <i class="las la-angle-right fs-12 fw-600 hide_cat1"></i>
                                    <li class=" opacity-50 hov-opacity-100 fs-12 hide_cat1">
                                        <a class="text-reset"
                                            href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                                    </li>
                                @endif
                            @endif
                            @if (isset($brand_id))
                                <i class="las la-angle-right fs-12 fw-600 hide_cat1 "></i>
                                <li class=" fw-700  text-dark opacity-50 hov-opacity-100 fs-12 hide_cat1">
                                    {{ translate('Brand') }}
                                </li>

                                <i class="las la-angle-right fs-12 fw-600 hide_cat1"></i>
                                <li class=" fw-700  text-dark  fs-12 hide_cat1">
                                    "{{ $brand_name }}"
                                </li>
                            @endif

                            @if (isset($category_id))
                                <i class="las la-angle-right fs-12 fw-600 d-flex hide_cat1"></i>
                                <li class="text-dark fw-600 fs-12 hide_cat1">
                                    "{{ $category_search->getTranslation('name') }}"
                                </li>
                            @endif
                        </ul>

                        <!-- Top Filters -->
                        <div class="text-left mb-3">
                            <div class="row gutters-5 flex-wrap align-items-center">
                                <div class="col-lg col-10">
                                    <h1 class="fs-18 fs-md-20 fw-700 text-dark line-height_0_7">
                                        @if (isset($category_id))
                                            {{-- {{ $category_search->getTranslation('name') }} --}}
                                            {{ translate('Showing results') }}
                                        @elseif(isset($query))
                                            {{ translate('Search result for ') }} "{{ $query }}"
                                        @else
                                            {{ translate('Showing results') }}
                                        @endif
                                    </h1>
                                    <div class="fs-12 display-none" id="search_product_count"><span class="fw-bold"
                                            id="total_product_count">{{ $products->total() }}</span><span
                                            class="product-name-color "> {{ translate('Products Found') }}</span></div>
                                    <div class="display-none fs-12 product-name-color" id="searching_product">{{ translate('Searching...') }}
                                    </div>
                                    <input type="hidden" name="keyword" value="{{ $query }}">
                                </div>
                                <div class="col-2 col-lg-auto d-xl-none mb-lg-3 text-right">
                                    <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle"
                                        data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>

                                <div class="col-6 col-lg-auto mb-3 w-lg-200px d-flex align-items-center gap-2">
                                    <div id="select_option_svg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="13.5"
                                            viewBox="0 0 17.5 13.5">
                                            <g id="Group_38743" data-name="Group 38743"
                                                transform="translate(-3444 2590)">
                                                <g id="Group_38742" data-name="Group 38742"
                                                    transform="translate(2089 -2895)">
                                                    <path id="Path_45144" data-name="Path 45144"
                                                        d="M20.522,10.663a.75.75,0,0,1-1.061-.016l-2.712-2.8V18a.75.75,0,0,1-1.5,0V7.851l-2.712,2.8A.75.75,0,1,1,11.462,9.6l4-4.125a.75.75,0,0,1,1.077,0l4,4.125A.75.75,0,0,1,20.522,10.663Z"
                                                        transform="translate(1351.75 299.75)" fill="#aaa"
                                                        fill-rule="evenodd" />
                                                    <path id="Path_45145" data-name="Path 45145"
                                                        d="M12.522,13.337a.75.75,0,0,0-1.061.016l-2.712,2.8V6a.75.75,0,0,0-1.5,0V16.149l-2.712-2.8A.75.75,0,0,0,3.462,14.4l4,4.125a.75.75,0,0,0,1.077,0l4-4.125A.75.75,0,0,0,12.522,13.337Z"
                                                        transform="translate(1351.75 299.75)" fill="#111"
                                                        fill-rule="evenodd" />
                                                    <path id="Path_45144-2" data-name="Path 45144"
                                                        d="M20.522,10.663a.75.75,0,0,1-1.061-.016l-2.712-2.8V18a.75.75,0,0,1-1.5,0V7.851l-2.712,2.8A.75.75,0,1,1,11.462,9.6l4-4.125a.75.75,0,0,1,1.077,0l4,4.125A.75.75,0,0,1,20.522,10.663Z"
                                                        transform="translate(1351.75 299.75)" fill="#aaa"
                                                        fill-rule="evenodd" />
                                                    <path id="Path_45145-2" data-name="Path 45145"
                                                        d="M12.522,13.337a.75.75,0,0,0-1.061.016l-2.712,2.8V6a.75.75,0,0,0-1.5,0V16.149l-2.712-2.8A.75.75,0,0,0,3.462,14.4l4,4.125a.75.75,0,0,0,1.077,0l4-4.125A.75.75,0,0,0,12.522,13.337Z"
                                                        transform="translate(1351.75 299.75)" fill="#111"
                                                        fill-rule="evenodd" />
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <select id="select_option"
                                        class="form-control select_btn_border_none form-control-sm text-center border-0 form-control-sm aiz-selectpicker rounded-0 "
                                        name="sort_by" onchange="filter(event)">
                                        <option value="">
                                            {{ translate('Sort by') }}</option>
                                        <option value="newest"
                                            @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>
                                            {{ translate('Newest') }}</option>
                                        <option value="oldest"
                                            @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>
                                            {{ translate('Oldest') }}</option>
                                        <option value="price-asc"
                                            @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>
                                            {{ translate('Price low to high') }}</option>
                                        <option value="price-desc"
                                            @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>
                                            {{ translate('Price high to low') }}</option>
                                    </select>
                                </div>


                                <div class="d-flex gap-2 mb-3 " style="gap: 8px;">
                                    <button type="button" class="btn-col-filter view-2-hide" data-cols="2">
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                    </button>

                                    <button type="button" class="btn-col-filter view-3-hide"data-cols="3">
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                    </button>

                                    <div class="btn-col-filter view-4-hide" data-cols="4">
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                    </div>

                                    <div class="btn-col-filter view-6-hide" data-cols="6">
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                        <div class="block_btn"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="px-3">

                            <div class="row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 border-top border-left"
                                id="products-row">
                                {{-- @foreach ($products as $key => $product)
                                    <div class="col border-right border-bottom has-transition hov-shadow-out z-1 ">
                                        @if (isset($product_type) && $product_type == 'preorder_product')
                                            @include('preorder.frontend.product_box3', [
                                                'product' => $product,
                                            ])
                                        @else
                                            @include('frontend.product_box_for_listing_page', [
                                                'product' => $product,
                                            ])
                                        @endif
                                    </div>
                                @endforeach --}}
                            </div>
                        </div>

                        <div class="aiz-pagination mt-4" id="pagination"></div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        let category_page_first_time = true;
        let brand_page_first_time = true;
        let session_data_first_time = true;

        function filter(e) {
            // alert("working or not")
            if (e) e.preventDefault();

            const target = e ? e.target : null;

            if (target && target.type === 'checkbox') {
                const parent = target.parentElement;
                if (parent) {
                    const children = parent.children;
                    if (children.length > 0) {
                        const lastSibling = children[children.length - 1];

                        if (target.checked) {
                            lastSibling.classList.add('fw-bold');
                        } else {
                            lastSibling.classList.remove('fw-bold');
                        }
                    }
                }
            }

            filter_data();
        }


        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter_data();
        }

        function filter_data(page = 1) {
            $("#search_product_count").hide();
            $("#searching_product").show();
            var formData = $('#search-form').serialize();
            formData += '&page=' + page;

            // preoerder route to search page time
            if (session_data_first_time) {
                const form_all_preorder_page = @json($form_all_preorder_page);
                // alert(form_all_preorder_page);
                if (form_all_preorder_page && form_all_preorder_page === 'preorder_product') {
                    formData = formData.replace(/(&|^)product_type=[^&]*/g, '');
                    formData += '&product_type=' + 'preorder_product';
                    $('input[name="product_type"][value="preorder_product"]').prop('checked', true);
                    // alert(formData)
                    session_data_first_time = false;
                }
            }

            // category & brand filter page logic
            let category_id = <?php echo $category_id ?? 'null'; ?>;
            let brand_id = <?php echo $brand_id ?? 'null'; ?>;
            if (category_page_first_time && category_id !== null && category_id !== 0 && category_id !== undefined) {
                formData += '&categories[]=' + category_id;
                category_page_first_time = false;
            }
            if (brand_id !== null && brand_id !== 0 && brand_id !== undefined && !formData.includes('brand_id=')) {
                formData += "&brand_id=" + brand_id;
            }

            // alert(formData);

            // product types ways some action this page
            if (formData.includes('product_type=preorder_product')) {
                $('#product_type_badge_preorder').removeClass('preorder-border-dashed my-2 text-muted  fw-600');
                $('#product_type_badge_preorder').addClass('bg-soft-dark  my-2 text-white');
                $('#product_type_badge_general').removeClass('bg-soft-dark my-2  text-white');
                $('#product_type_badge_general').addClass('preorder-border-dashed  text-muted my-2 fw-600');

                $('#preorder_cagegories_box').slideDown(300);
                $('#general_cagegories_box').slideUp(300);

                $('.preorder-time-hide').fadeOut(400);
                $('.preorder-time-show').slideDown(400);
            } else {
                $('#product_type_badge_general').removeClass('preorder-border-dashed my-2  text-muted  fw-600');
                $('#product_type_badge_general').addClass('bg-soft-dark my-2  text-white');
                $('#product_type_badge_preorder').removeClass('bg-soft-dark  my-2 text-white');
                $('#product_type_badge_preorder').addClass('preorder-border-dashed my-2 text-muted  fw-600');

                $('#preorder_cagegories_box').slideUp(300);
                $('#general_cagegories_box').slideDown(300);

                $('.preorder-time-hide').fadeIn(400);
                $('.preorder-time-show').slideUp(400);
            }

            // alert(JSON.stringify(formData));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('suggestion.search2') }}",
                type: 'get',
                data: formData,
                success: function(response) {
                    // alert(JSON.stringify(response))
                    $("#search_product_count").show();
                    $("#searching_product").hide();
                    $('#products-row').html(response.product_html);
                    $('#pagination').html(response.pagination_html);
                    $('#total_product_count').text(response.total_product_count);

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        // Handle page button click
        $(document).on('click', '.page-btn', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            filter_data(page);
        });
    </script>




    <script type="text/javascript">
        $(document).ready(function() {

            const path = window.location.pathname;
            if (path.includes('/search')) {
                filter_data();
            } else {
                filter_data();
            }

            function setActiveButtonByWidth() {
                var width = $(window).width();
                var cols = 4;

                if (width < 576) {
                    cols = 2;
                } else if (width >= 576 && width < 768) {
                    cols = 3;
                } else if (width >= 768 && width < 1200) {
                    cols = 4;
                } else {
                    cols = 4;
                }

                $('.btn-col-filter').removeClass('active-cols');
                $('.btn-col-filter[data-cols="' + cols + '"]').addClass('active-cols');
                $('.row.gutters-16').removeClass('row-cols-2 row-cols-3 row-cols-4 row-cols-6')
                    .addClass('row-cols-' + cols);
            }


            setActiveButtonByWidth();

            $(window).resize(function() {
                setActiveButtonByWidth();
            });

            $('.btn-col-filter').on('click', function() {

                $('.btn-col-filter').removeClass('active-cols');
                $(this).addClass('active-cols');

                var colValue = $(this).data('cols');

                var $row = $('#products-row');

                $row.removeClass(function(index, className) {
                    return (className.match(/(^|\s)row-cols-\S+/g) || []).join(' ');
                });

                $row.addClass('row-cols-xxl-' + colValue);
                $row.addClass('row-cols-xl-' + colValue);
                $row.addClass('row-cols-lg-' + colValue);
                $row.addClass('row-cols-md-' + colValue);
                $row.addClass('row-cols-2');

            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.see_more_toggle_btn').forEach((btn) => {
                const lessBtn = btn.closest('div').nextElementSibling.querySelector('.less_toggle_btn');
                const element_list = btn.parentElement.previousElementSibling;
                const children = Array.from(element_list.children);


                let visibleCount = 5;

                // first five element show
                children.forEach((child, index) => {
                    // console.log(child)
                    if (index < visibleCount) {
                        child.style.setProperty('display', 'block', 'important');
                    } else {
                        child.style.setProperty('display', 'none', 'important');
                    }
                });
                if (children.length <= 5) {
                    btn.style.display = 'none';
                }

                // click to add more element
                btn.addEventListener('click', () => {

                    visibleCount += 5;

                    children.forEach((child, index) => {
                        if (index < visibleCount) {
                            child.style.setProperty('display', 'block', 'important');
                        }
                    });

                    if (visibleCount >= children.length) {
                        btn.style.display = 'none';
                        lessBtn.style.display = 'inline-block';
                    }
                });


                lessBtn.addEventListener('click', () => {
                    visibleCount = 5;

                    children.forEach((child, index) => {
                        child.style.setProperty('display', index < visibleCount ? 'block' :
                            'none', 'important');
                    });

                    // Toggle buttons
                    lessBtn.style.display = 'none';
                    btn.style.display = 'inline-block';
                });

                lessBtn.style.display = 'none';


            });


        });
    </script>
    <!-- Treeview js -->
    <script src="{{ static_asset('assets/js/hummingbird-treeview2.js') }}"></script>

    <script>
        $(document).ready(function() {

            // $("#treeview2").hummingbird();
            var $tree = $('#treeview2');

            var oldShow = $.fn.show;
            var oldHide = $.fn.hide;

            // Override show for smooth animation
            $.fn.show = function(speed, oldCallback) {
                if ($(this).closest($tree).length) {
                    return this.stop(true, true).slideDown(400, oldCallback);
                } else {
                    return oldShow.apply(this, arguments);
                }
            };

            // Override hide for smooth animation
            $.fn.hide = function(speed, oldCallback) {
                if ($(this).closest($tree).length) {
                    return this.stop(true, true).slideUp(400, oldCallback);
                } else {
                    return oldHide.apply(this, arguments);
                }
            };

            // Initialize Hummingbird treeview2
            $tree.hummingbird();

            var selected_ids = '{{ implode(',', $old_categories) }}';
            if (selected_ids != '') {
                const myArray = selected_ids.split(",");
                for (let i = 0; i < myArray.length; i++) {
                    const element = myArray[i];

                    $('#category_checkidgenerel_' + element).prop('checked', true);
                    $('#category_checkid_textgenerel_' + element).addClass('fw-bold');
                    $('#category_checkidgenerel_' + element).parents("ul").css("display", "block");
                }
            }
        });


        function showLabels() {
            document.querySelectorAll('.slider-value-text').forEach(label => {
                label.style.display = 'block';
            });
        }

        function hideLabels() {
            document.querySelectorAll('.slider-value-text').forEach(label => {
                label.style.display = 'none';
            });
        }


        document.querySelectorAll('.noUi-connect, .noUi-touch-area').forEach((element) => {
            // Desktop 
            element.addEventListener('mouseenter', showLabels);
            element.addEventListener('mouseleave', function() {
                setTimeout(() => {
                    hideLabels();
                }, 2000);
            });

            // Mobile 
            element.addEventListener('touchstart', showLabels);
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    hideLabels();
                }, 2000);
            });
        });
        document.getElementById('input-slider-range').addEventListener('click', function() {
            showLabels();

            setTimeout(function() {
                hideLabels();
            }, 2000);
        });
    </script>



    <script>
        window.onload = function() {
            setTimeout(function() {

                const mainUl = $('#category_filter div ul');

                if (mainUl.length === 0) {
                    return alert("Main UL not found!");
                }


                function processUl($ul) {
                    $ul.addClass('ul_is_empty');

                    $ul.children('li').each(function() {
                        const $li = $(this);


                        const $nestedUl = $li.children('ul');
                        if ($nestedUl.length > 0) {

                            processUl($nestedUl);



                            if ($nestedUl.children('li').length === 0) {
                                $nestedUl.prev('i.las.pt-3px.la-angle-right').remove();
                                $nestedUl.remove();
                            }
                        } else {
                            const countAttr = $li.attr('count');
                            if (countAttr === "0") {
                                $li.remove();
                            }
                        }
                    });
                }

                processUl(mainUl);

                $('.ul_is_empty').each(function() {
                    const $ul = $(this);

                    if ($ul.children('li').length === 0) {
                        $ul.prev('i.las.pt-3px.la-angle-right').remove();
                        $ul.remove();
                    }
                });

            }, 0000);

            setTimeout(function() {

                const mainUl = $('#category_filter_preorder div ul');

                if (mainUl.length === 0) {
                    return alert("Main UL not found!");
                }


                function processUl($ul) {
                    $ul.addClass('ul_is_empty');


                    $ul.children('li').each(function() {
                        const $li = $(this);


                        const $nestedUl = $li.children('ul');
                        if ($nestedUl.length > 0) {

                            processUl($nestedUl);



                            if ($nestedUl.children('li').length === 0) {
                                $nestedUl.prev('i.las.pt-3px.la-angle-down').remove();
                                $nestedUl.remove();
                            }
                        } else {
                            const countAttr = $li.attr('count');
                            if (countAttr === "0") {
                                $li.remove();
                            }
                        }
                    });
                }

                processUl(mainUl);

                $('.ul_is_empty').each(function() {
                    const $ul = $(this);

                    if ($ul.children('li').length === 0) {
                        $ul.prev('i.las.pt-3px.la-angle-right').remove();
                        $ul.remove();
                    }
                });

            }, 0000);

        };
    </script>

@endsection
