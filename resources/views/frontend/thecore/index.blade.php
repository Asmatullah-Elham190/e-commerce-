@extends('frontend.layouts.app')

@section('content')
<style>
    @media (max-width: 767px) {
        #flash_deal .flash-deals-baner {
            height: 203px !important;
        }
    }
</style>
@php $lang = get_system_language()->code; @endphp

<div class="pt-32px pb-26px" style="background: {{ get_setting('hero_bg_color', '#f5f5f5') }}">
    <div class="container">
        <div class="row">
            <!-- Sliders -->
            <div class="@if (get_setting('enable_featured_products') == 1 || get_setting('enable_flash_deal') == 1 || get_setting('enable_hot_categories') == 1) col-lg-5 @else col-lg-12 @endif col-md-7 col-12">
                @if (get_setting('home_slider_images', null, $lang) != null)
                <div class="aiz-carousel dots-inside-bottom thecore-hero-slider" data-autoplay="true" data-infinite="true">
                    @php
                    $decoded_slider_images = json_decode(
                    get_setting('home_slider_images', null, $lang),
                    true,
                    );
                    $sliders = get_slider_images($decoded_slider_images);
                    $home_slider_links = get_setting('home_slider_links', null, $lang);
                    @endphp
                    @foreach ($sliders as $key => $slider)
                    <div class="carousel-box">
                        <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
                            <div class="thecore-square-box overflow-hidden h-400px h-xl-500px h-xxl-516px">
                                <img class="img-fluid rounded-75 border border-light h-100"
                                    src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                    alt="{{ env('APP_NAME') }} promo"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    </div>

                    @endforeach
                </div>
                @endif
            </div>
            
            <div class="col-lg-7 col-md-5 pl-4 col-12">
                <div class="row">
                    @php
                    $flash_deal = get_featured_flash_deal();
                    @endphp
                    @if (get_setting('enable_flash_deal') == 1 && $flash_deal != null)
                    <div class=" @if (get_setting('enable_hot_categories') == 1 && count($hot_categories) > 0) col-lg-5 @else col-lg-12 @endif col-12 pl-2 pl-md-3 pl-xl-4">
                        <section class="mb-2" id="flash_deal">
                            <!-- Mobile view Countdown -->
                            <div class="mobile-countdown-simple d-md-none w-100 mb-2 mt-1"
                                data-end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}">
                                <div class="countdown-text text-center">
                                    Ends in:
                                    <span id="simple-days">00</span> days
                                    <span id="simple-hours">00</span> hrs
                                    <span id="simple-mins">00</span> min
                                    <span id="simple-secs">00</span> sec
                                </div>
                            </div>

                            <div class="gutters-md-16 pb-1">
                                <!-- Flash Deals Baner & Countdown -->
                                

                                <div class="flash-deals-baner h-md-200px h-lg-220px h-xl-300px h-xxl-316px">
                                    <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="d-block h-100 position-relative">
                                        <div class="h-100 w-100 w-xl-auto rounded-75"
                                            style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center;">
                                            </div>

                                        <div class="position-absolute bottom-0 w-100 py-3 d-none d-md-block">
                                            <div class="d-flex justify-content-center">
                                                <div class="aiz-count-down-circle rounded-2 p-0 p-xl-2 mx-3 bg-white shadow-lg"
                                                    end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>
                    @endif

                    @if (get_setting('enable_hot_categories') == 1 && count($hot_categories) > 0)
                    <!-- HOT Category -->
                    <div class="col-lg-{{ (get_setting('enable_flash_deal') == 1 && $flash_deal != null) ? '7' : '12' }} col-12 pl-0 pl-lg-4 hot-categories">
                        <div class="mb-2 mb-sm-0 pl-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 188 255" class="mb-2">
                                <path d="M187.899,164.809C185.803,214.868,144.574,254.812,94,254.812,42.085,254.812,0,211.312,0,160.812,0,154.062-.121,140.572,10,117.812c6.057-13.621,9.856-22.178,12-30,1.178-4.299,3.469-11.129,10,0,3.851,6.562,4,16,4,16s14.328-10.995,24-32c14.179-30.793,2.866-49.2-1-62-1.338-4.428-2.178-12.386,7,0,9.352,3.451,34.076,20.758,47,39,18.445,26.035,25,51,25,51s5.906-7.33,8-15c2.365-8.661,2.4-17.239,10-8.999,7.227,8.787,17.96,25.3,24,41C190.969,137.321,187.899,164.809,187.899,164.809Z" fill="#ff4c0d"/>
                                <path d="M94,254.812C58.101,254.812,29,225.711,29,189.812c0-21.661,8.729-34.812,26.896-52.646C67.528,125.747,78.415,111.722,83.042,102.172c.911-1.88,2.984-11.677,10.977-.206,4.193,6.016,10.766,16.715,14.981,25.846,7.266,15.743,9,31,9,31s7.121-4.196,12-15c1.573-3.482,4.753-16.664,13.643-3.484,6.523,9.672,15.484,27.062,15.357,49.484C159,225.711,129.898,254.812,94,254.812Z" fill="#fc9502"/>
                                <path d="M95,183.812c9.25,0,9.25,17.129,21,40,7.824,15.229-3.879,31-21,31s-26-13.879-26-31S85.75,183.812,95,183.812Z" fill="#fce202"/>
                            </svg>
                            <span class="d-inline-block fs-16 fw-700">{{ translate('Hot Categories') }}</span>
                        </div>
                        
                        <div class="aiz-carousel  arrow-inactive-transparent arrow-x-0 carousel-arrow"
                            data-rows="2" data-items="{{ (get_setting('enable_flash_deal') == 1 && $flash_deal != null) ? '4' : '6' }}" data-xxl-items="{{ (get_setting('enable_flash_deal') == 1 && $flash_deal != null) ? '4' : '6' }}" data-xl-items="{{ (get_setting('enable_flash_deal') == 1 && $flash_deal != null) ? '4' : '6' }}" data-lg-items="{{ (get_setting('enable_flash_deal') == 1 && $flash_deal != null) ? '4' : '6' }}"
                            data-md-items="{{ (get_setting('enable_flash_deal') == 1 && $flash_deal != null) ? '4' : '6' }}" data-sm-items="5" data-xs-items="4" data-arrows="false" data-dots="false" data-autoplay="true" data-infinite="true">
                        
                            @foreach ($hot_categories->shuffle()->values() as $key => $category)
                            @php
                                $category_name = $category->getTranslation('name');
                            @endphp
                            <div class="carousel-box hot-category-box mt-2 mt-md-1 mt-lg-2 mt-xl-3 mb-1 mb-md-0 mb-lg-1">
                                <div class="img h-80px w-80px h-md-60px w-md-60px h-lg-60px w-lg-60px h-xl-80px w-xl-80px h-xxl-90px w-xxl-90px rounded-2 overflow-hidden bg-white hov-scale-img">
                                    <a href="{{ route('products.category', $category->slug) }}">
                                        <img class="lazyload img-fit m-auto has-transition rounded-2"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ isset($category->banner) ? uploaded_asset($category->banner) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ $category_name }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </div>
                                <!-- Name -->
                                <div class="fs-11 mr-1 mt-1 mt-lg-2 mt-xl-3 text-center " title="{{ $category_name }}">
                                    <a href="{{ route('products.category', $category->slug) }}" class="fw-400 text-truncate-1 text-reset hov-text-primary"> {{ $category_name }}</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if (get_setting('enable_featured_products') == 1)
                    <div class="col-12 d-none d-lg-block pl-md-0 pl-4 ml-0 ml-xl-2 featured-product">
                        @include('frontend.thecore.partials.featured_products')
                    </div>
                    @endif
                </div>
            </div>
            @if (get_setting('enable_featured_products') == 1)
            <div class="col-12 d-block d-lg-none mt-3">
                @include('frontend.thecore.partials.featured_products')
            </div>
            @endif
        </div>
    </div>
</div>

<input type="hidden" id="selected_homepage" value="{{get_setting('homepage_select')}}">

@if (get_setting('enable_featured_categories') == 1 && count($featured_categories) > 0)
<!-- Featured Category -->
<div class="pt-32px" style="background: #ffffffff;">
    <div class="container">
        <div class="featured-categories rounded-75 px-3" style="background: {{ get_setting('featured_category_section_bg_color', '#ffffff') }} ; @if(get_setting('featured_category_section_outline') == 1) border: 2px solid {{ get_setting('featured_category_section_outline_color', '#000') }}; @endif">
            <div class="row pt-32px pb-26px">
                <div class="col-sm-6 col-md-4 col-lg-3 col-12 mb-3 mb-sm-0">
                    <div class="px-0 px-md-3">
                        <p class="fs-16 fw-700  font-weight-bold mb-1 mb-sm-3">{{translate('Featured Categories')}}</p>
                        <p class="fs-13 fs-lg-14 fw-400 text-truncate-2" title="{{translate('Categories catching eyes & winning hearts across our marketplace')}}">{{translate('Categories catching eyes & winning hearts across our marketplace')}}</p>
                        <a class="btn fs-10 fs-md-16 custom-hov-btn py-2" href="{{route('categories.all')}}" style="background: {{ get_setting('featured_category_btn_color', '#F94C10') }}; color: {{ get_setting('featured_category_section_btn_text_color', '#f5f5f5') }};">
                            <span class="d-inline">{{ translate('All Categories') }}</span>
                        </a>
                    </div>
                </div>

                <div class="col-sm-6 col-md-8 col-lg-9 col-12">
                    <div class="aiz-carousel  arrow-inactive-transparent arrow-x-0  carousel-arrow"
                        data-rows="1" data-items="6" data-xxl-items="6" data-xl-items="5" data-lg-items="4"
                        data-md-items="3" data-sm-items="1" data-xs-items="4" data-arrows="true" data-dots="false" data-autoplay="true" data-infinite="true">
                    
                        @foreach ($featured_categories->shuffle()->values() as $key => $category)
                        @php
                            $category_name = $category->getTranslation('name');
                        @endphp
                        <div class="carousel-box">
                            
                            <div class="img w-60px h-60px w-sm-70px h-sm-70px  h-md-100px w-md-100px h-lg-120px w-lg-120px rounded overflow-hidden mx-auto hov-scale-img">
                                <a href="{{ route('products.category', $category->slug) }}">
                                    <img class="lazyload img-fit m-auto has-transition"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ isset($category->cover_image) ? uploaded_asset($category->cover_image) : static_asset('assets/img/placeholder.jpg') }}"
                                    alt="{{ $category_name }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                            <!-- Name -->
                            <div class="fs-11 mr-1 mt-3 text-center mt-2" title="{{ $category_name }}">
                                <a class="fw-400 text-reset hov-text-primary" href="{{ route('products.category', $category->slug) }}"> {{ strlen($category_name) > 18 ? substr($category_name, 0,18).'...' : $category_name }}</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
  
<!-- Best Selling And Todays Deal -->
<section class="pt-4 pt-lg-5 pb-4">
    <div class="container">    
        <div class="d-sm-flex">
            <!-- Best Selling -->
            @php
             $best_selling_products = get_best_selling_products(20)->shuffle()->values();
            @endphp
            @if (get_setting('enable_best_selling_products') == 1 && count($best_selling_products) > 0)
            <div class="px-0 px-sm-4 w-100 overflow-hidden rounded-75 best-salling-section pt-32px pb-26px mb-4 mb-sm-0" style="background-color: {{ get_setting('best_selling_section_bg_color', '#E7EFEC') }}">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between px-3 px-md-2">
                    <!-- Title -->
                    <h3 class="fs-16 fw-600 mb-2 mb-sm-0">
                        <span class="">{{ translate('Best Selling') }}</span>
                    </h3>
                    <a type="button" class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center" href="{{route('best-selling')}}">
                        <span><i class="las la-angle-right fs-20 fw-600"></i></span>
                        <span class="fs-12 mr-2 text">{{translate('View All')}}</span>
                    </a>
                </div>
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5"
                    data-xxl-items="5" data-xl-items="5" data-lg-items="5" data-md-items="3" data-sm-items="1"
                    data-xs-items="3" data-arrows="false" data-dots="false" data-autoplay="false" data-infinite="true">
                    @foreach ($best_selling_products as $key => $product)
                        <div class="px-3">
                            <div class="img h-80px w-80px h-lg-100px w-lg-100px  h-xl-130px w-xl-130px h-xxl-170px w-xxl-170px rounded overflow-hidden mx-auto position-relative image-hover-effect">
                                <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                    <img class="lazyload img-fit m-auto has-transition product-main-image"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ get_image($product->thumbnail) }}"
                                    alt="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                    <img
                                    class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute"
                                    src="{{ get_first_product_image($product->thumbnail, $product->photos) }}"
                                    alt="{{ $product->getTranslation('name') }}"
                                    title="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>

                            <!-- Name -->
                            <div class="fs-13 mr-sm-1 mt-3 text-center mt-2 px-xs-0 px-sm-4" title="{{ $product->getTranslation('name') }}">
                                <a class="fw-400 text-truncate-2 hov-text-primary text-reset" href="{{ route('product', $product->slug) }}">{{ $product->getTranslation('name') }}</a>
                            </div>

                            <!-- Price -->
                            <div class="fs-14 mr-1 mt-1 text-center">
                                <span class="d-block fw-700">{{ home_discounted_base_price($product) }}</span>
                                @if (home_base_price($product) != home_discounted_base_price($product))
                                    <del class="d-block text-secondary fs-12 fw-400">{{ home_base_price($product) }}</del>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Todays Deal -->
            @endif
            @php
             $todays_deal_products = get_todays_deal_products(20)->shuffle()->values();
            @endphp
            @if (get_setting('enable_todays_deal') == 1 && count($todays_deal_products) > 0)
            <div class="px-0  mt-sm-0 @if (get_setting('enable_best_selling_products') == 1 && count($best_selling_products) > 0) ml-sm-4 w-md-50 w-lg-35  @else ml-sm-0 w-md-100 w-lg-100 @endif w-100  overflow-hidden border border-2 border-dark rounded-75 todays-deal pt-32px pb-26px" style="background-color: {{ get_setting('todays_deal_bg_color', '#ffffff') }}">
                <div class="d-flex mx-3 mb-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fw-600 mb-2 mb-sm-0">
                        <span class="">{{ translate('Todays Deal') }}</span>
                    </h3>
                    <!-- Links -->
                    <a type="button" class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center" href="{{ route('todays-deal') }}">
                        <span><i class="las la-angle-right fs-20 fw-600"></i></span>
                        <span class="fs-12 mr-2 text">View All</span>
                    </a>
                </div>  
        
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="1"
                    data-xxl-items="1" data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1"
                    data-xs-items="1" data-arrows="true" data-dots="false" data-autoplay="true" data-infinite="true">
                    @foreach ($todays_deal_products as $key => $product)
                        <div class="px-3">
                            <div class="img h-80px w-80px h-lg-100px w-lg-100px  h-xl-130px w-xl-130px h-xxl-170px w-xxl-170px rounded overflow-hidden mx-auto position-relative image-hover-effect">
                                <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                    <img class="lazyload img-fit m-auto has-transition product-main-image"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ get_image($product->thumbnail) }}"
                                    alt="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                    <img
                                    class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute"
                                    src="{{ get_first_product_image($product->thumbnail, $product->photos) }}"
                                    alt="{{ $product->getTranslation('name') }}"
                                    title="{{ $product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>

                            <!-- Name -->
                            <div class="fs-13 mr-1 mt-3 text-center px-xs-0 px-sm-4" title="{{ $product->getTranslation('name') }}">
                                <a class="fw-400 text-truncate-2 hov-text-primary text-reset" href="{{ route('product', $product->slug) }}">{{ $product->getTranslation('name') }}</a>
                            </div>

                            <!-- Price -->
                            <div class="fs-14 mr-1 mt-1 text-center">
                                <span class="d-block fw-700">{{ home_discounted_base_price($product) }}</span>
                                @if (home_base_price($product) != home_discounted_base_price($product))
                                    <del
                                        class="d-block text-secondary fs-12 fw-400">{{ home_base_price($product) }}</del>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Banner section 1 -->
@php $homeBanner1Images = get_setting('home_banner1_images', null, $lang); @endphp
@if (get_setting('enable_banner_1') == 1 && $homeBanner1Images != null)
<div class="pt-3 pt-lg-4 pb-2 pb-lg-3 mb-1">
    <div class="container">
        @php
        $banner_1_imags = json_decode($homeBanner1Images);
        $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
        $home_banner1_links = get_setting('home_banner1_links', null, $lang);
        @endphp
        <div class="w-100 pr-3 pr-md-0">
            <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15 home-banner-1"
                data-items="{{ count($banner_1_imags) }}" data-xxl-items="{{ count($banner_1_imags) }}"
                data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}"
                data-md-items="2.5" data-sm-items="2.5" data-xs-items="1.5" data-arrows="false"
                data-dots="false" data-autoplay="true" data-infinite="true">
                @foreach ($banner_1_imags as $key => $value)
                <div class="carousel-box overflow-hidden hov-scale-img">
                    <a href="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}"
                        class="d-block text-reset overflow-hidden rounded-75 h-100">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                            class="lazyload img-fit h-100 has-transition"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif



<!-- Auction Product -->
@if (addon_is_activated('auction') && get_setting('enable_auction_products') == 1)
<div id="auction_products">

</div>
@endif



<!-- Classified Product -->
@if (get_setting('classified_product') == 1 && get_setting('enable_classified_products_sections') == 1)
    @php
        $classified_products = get_home_page_classified_products()->shuffle()->values();
    @endphp
    @if (count($classified_products) > 0)
        <section class="pt-32px pb-26px my-4" style="background: {{ get_setting('classified_bg_color', '#f5f5f5') }}">
            <div class="container">
                    <div class="d-sm-flex">
                        <div class=" w-100 overflow-hidden">
                            <!-- Top Section -->
                            <div class="d-flex align-items-baseline justify-content-between">
                                <!-- Title -->
                                <div class="mb-sm-0 ml-3 pb-2">
                                    <h4 class="fs-16 fw-700 mb-0">{{ translate('Classified Ads') }}</h4>
                                    <p class="fs-12 mb-0 fw-400">{{translate('products')}} ({{count($classified_products)}})</p>
                                </div>
                                <a type="button" class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center" href="{{ route('customer.products') }}">
                                    <span><i class="las la-angle-right fs-20 fw-600"></i></span>
                                    <span class="fs-12 mr-2 text">View All</span>
                                </a>
                            </div>
                            <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="7"
                                data-xxl-items="7" data-xl-items="6" data-lg-items="5" data-md-items="4" data-sm-items="4"
                                data-xs-items="3" data-arrows="false" data-dots="false" data-autoplay="true" data-infinite="true">
                                @foreach ($classified_products as $key => $product)
                                    <div class="px-3">
                                        <div class="img h-100px w-100px h-md-150px w-md-150px h-lg-170px w-lg-170px rounded-2 overflow-hidden mx-auto position-relative image-hover-effect">
                                            <a href="{{ route('customer.product', $product->slug) }}"title="{{ $product->getTranslation('name') }}">
                                                <img class="lazyload img-fit m-auto has-transition product-main-image"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ get_image($product->thumbnail) }}"
                                                alt="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                                <img
                                                class="lazyload img-fit m-auto has-transition product-main-image product-hover-image position-absolute"
                                                src="{{ get_first_product_image($product->thumbnail, $product->photos) }}"
                                                alt="{{ $product->getTranslation('name') }}"
                                                title="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                        </div>

                                        <div class="text-center mt-2">
                                            <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-1 h-35px">
                                                <a href="{{ route('customer.product', $product->slug) }}"
                                                    class="text-reset hov-text-primary hov-text-primary">{{ $product->getTranslation('name') }}</a>
                                            </h3>
                                            <div class="fw-700 fs-14 mb-1 mt-2">
                                                {{ single_price($product->unit_price) }}
                                            </div>
                                            <div class="m-2">
                                                @if ($product->conditon == 'new')
                                                <span
                                                    class="badge-sm badge-dark fs-13 fw-600 px-2 py-1 text-white rounded">{{ translate('New') }}</span>
                                                @elseif($product->conditon == 'used')
                                                <span
                                                    class="badge-sm badge-soft-primary fs-13 fw-600 px-2 py-1 text-primary rounded">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    @endif
@endif

@if (addon_is_activated('preorder') && get_setting('enable_newest_preorder_products') == 1)
<!-- Newest Preorder Products -->
@include('preorder.frontend.home_page.thecore.newest_preorder')
@endif


<!-- Banner Section 2 -->
@php $homeBanner2Images = get_setting('home_banner2_images', null, $lang); 
$homeBanner2SmallImages = get_setting('home_banner2_sm_images', null, $lang); 
@endphp
@if (get_setting('enable_banner_2') == 1 && $homeBanner2Images != null)
<div class="py-32px mt-2 mb-32px">
    <div class="container">
        @php
        $banner_2_imags = json_decode($homeBanner2Images, true) ?? [];
        $banner_2_small_imags = json_decode($homeBanner2SmallImages, true) ?? [];
        $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
        $data_small_md = count($banner_2_small_imags) >= 2 ? 2 : 1;
        $home_banner2_links = get_setting('home_banner2_links', null, $lang);
        @endphp
        <div class="d-none d-md-block aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
            data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
            data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
            data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
            data-dots="false">
            @foreach ($banner_2_imags as $key => $value)
            <div class="carousel-box overflow-hidden hov-scale-img">
                <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}"
                    class="d-block text-reset overflow-hidden rounded-75">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                        class="img-fluid lazyload w-100 has-transition"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </a>
            </div>
            @endforeach
        </div>


        <div class="d-md-none aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
            data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
            data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_small_md }}"
            data-md-items="{{ $data_small_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
            data-dots="false">
            @foreach ($banner_2_small_imags as $key => $value)
            <div class="carousel-box overflow-hidden hov-scale-img">
                <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}"
                    class="d-block text-reset overflow-hidden rounded-75">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                        class="img-fluid lazyload w-100 has-transition"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- New Products -->
<div id="section_newest">
</div>
<div class="text-center d-none" id="view-more-container">
    <button type="button" class="btn btn-lg py-19px w-20 bg-light fs-12 fs-md-16 my-32px" id="view-more-btn">
        {{ translate('Load More') }}
        <i id="spinner-icon" class="las la-lg la-spinner la-spin d-none"></i>
    </button>
</div>

@endsection

@section('script')
<script>
    // Countdown for mobile view
    function startSimpleCountdown(endDate) {
        function update() {
            const now = new Date();
            const diff = endDate - now;
            if (diff > 0) {
                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / (60 * 60 * 24));
                const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
                const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
                const secs = totalSeconds % 60;

                document.getElementById("simple-days").textContent = days.toString().padStart(2, '0');
                document.getElementById("simple-hours").textContent = hours.toString().padStart(2, '0');
                document.getElementById("simple-mins").textContent = mins.toString().padStart(2, '0');
                document.getElementById("simple-secs").textContent = secs.toString().padStart(2, '0');
            } else {
                document.querySelector(".mobile-countdown-simple").textContent = "Sale ended";
                clearInterval(timer);
            }
        }

        update();
        const timer = setInterval(update, 1000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const countdownEl = document.querySelector('.mobile-countdown-simple');
        if (!countdownEl) return;

        const endDateStr = countdownEl.dataset.endDate;
        if (endDateStr) {
            const parsedEndDate = new Date(endDateStr.replace(/-/g, '/'));
            startSimpleCountdown(parsedEndDate);
        }
    });



    let page = 1;        
    $(document).on('click', '#view-more-btn', function() {
        const $button = $(this);
        const originalText = $button.html(); 

        page++;
        $button.html('{{ translate("Loading...") }} <i id="spinner-icon" class="las la-lg la-spinner la-spin"></i>');
        $button.prop('disabled', true); 

        $.post('{{ route('home.section.newest_products') }}', {
            _token: '{{ csrf_token() }}',
            page: page
        }, function(data) {
            $button.prop('disabled', false);
            $button.html(originalText);
            
            if ($.trim(data) === '') {
                $button.prop('disabled', true).text('{{ translate("No More Products") }}');
            } else {
                $('#newest-products-list').append(data);
                AIZ.plugins.slickCarousel();
            }
        }).fail(function() {
            $button.prop('disabled', false);
            $button.html('{{ translate("Error, Try Again") }} <i id="spinner-icon" class="las la-lg la-spinner la-spin d-none"></i>');
        });
    });

    $(window).on('load', function() {
        $('.hot-category-box').addClass('d-flex flex-column justify-content-center align-items-center');
    });

    function toggleViewMoreButton() {
        if ($.trim($('#section_newest').html()).length > 0) {
            $('#view-more-container').removeClass('d-none').addClass('d-block');
        } else {
            $('#view-more-container').removeClass('d-block').addClass('d-none');
        }
    }

</script>
@endsection