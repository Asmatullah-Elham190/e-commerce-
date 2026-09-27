@extends('frontend.layouts.app')

@section('content')

    @php $lang = get_system_language()->code; @endphp
        <!-- Home Banner Start -->
        <div class="aiz-carousel arrow-x-0 arrow-inactive-none hero-banner-carousel d-none  d-md-block" data-items="1" data-full-hd-items="1" data-xxl-items="1"
            data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows='false'
            data-autoplay="true" data-infinite="true">
            @if (get_setting('home_slider_images', null, $lang) != null)
                @php
                    $decoded_slider_images = json_decode(
                        get_setting('home_slider_images', null, $lang),
                        true,
                    );
                    $sliders = get_slider_images($decoded_slider_images);
                    $home_slider_links = get_setting('home_slider_links', null, $lang);
                    $home_slider_colors = get_setting('home_slider_colors', null, $lang);
                @endphp
                @foreach ($sliders as $key => $slider)
                    <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}" class="d-block w-100 hero-banner-wrapper">
                        <div class="hero-banner-container hov-scale-img overflow-hidden" >
                            <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <div class="aiz-carousel arrow-x-0 arrow-inactive-none hero-banner-carousel d-md-none" data-items="1" data-full-hd-items="1" data-xxl-items="1"
            data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows='false'
            data-autoplay="true" data-infinite="true">
            @if (get_setting('small_home_slider_images', null, $lang) != null)
                @php
                    $small_decoded_slider_images = json_decode(
                        get_setting('small_home_slider_images', null, $lang),
                        true,
                    );
                    $small_sliders = get_slider_images($small_decoded_slider_images);
                    $home_slider_links = get_setting('home_slider_links', null, $lang);
                    $home_slider_colors = get_setting('home_slider_colors', null, $lang);
                @endphp
                @foreach ($small_sliders as $key => $small_slider)
                    <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}" class="d-block w-100 hero-banner-wrapper">
                        <div class="hero-banner-container hov-scale-img overflow-hidden" >
                            <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                src="{{ $small_slider ? my_asset($small_slider->file_name) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <!-- Home Banner End -->

        <!-- Flash & Todays Deals Start -->
        @if (get_setting('enable_flash_deal') == 1 || get_setting('enable_todays_deal') == 1)
            <div class="border-bottom">
                <div class="layout-container mx-auto px-3">
                    <div class="row">
                        <!-- Flash Deal -->
                        @php
                            $flash_deal = get_featured_flash_deal();
                        @endphp
                        @if (get_setting('enable_flash_deal') == 1 )
                            @if ($flash_deal != null)
                                <div class="@if (get_setting('enable_flash_deal') == 1 && get_setting('enable_todays_deal') == 1) col-xxl-6 @else col-xxl-12 @endif py-30px flash-deals-border-right">
                                    <div class="row d-flex">
                                        <div class="col-12 col-md-auto">
                                            <a href="{{ route('flash-deal-details', $flash_deal->slug) }}">
                                                <div
                                                    class="img-fit w-100 h-270px w-md-200px w-xl-270px rounded-2 overflow-hidden fd-banner-container align-self-stretch hov-scale-img">
                                                    <img class="img-fit w-100 h-100 has-transition" style="object-position: center;"
                                                        src="{{ $flash_deal->banner ? uploaded_asset($flash_deal->banner) : static_asset('assets/img/placeholder.jpg') }}" alt="Flash Deal Banner">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col mt-4 mt-md-0">
                                            <!-- Heading -->
                                            <div class="d-flex flex-wrap align-items-start justify-content-between" style="gap: 12px">
                                                <div>
                                                    <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Flash Deals') }}</h5>
                                                    <div class="aiz-count-down align-items-center mb-2 mb-lg-0" data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                                        class="fs-12 fw-bold text-reset hov-text-blue has-transition mr-3">{{ translate('View All Products') }}</a>
                                                    <a href="{{ route('flash-deals') }}"
                                                        class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('All Deals') }}</a>
                                                </div>
                                            </div>

                                            <!-- Slider -->
                                            @php
                                                $flash_deal_products = get_flash_deal_products($flash_deal->id)->shuffle();
                                            @endphp
                                            <div class="aiz-carousel arrow-x-0 arrow-inactive-none mt-4 fd-product-slider overflow-hidden"
                                                
                                                @if (get_setting('enable_flash_deal') == 1 && get_setting('enable_todays_deal') == 1) 
                                                    data-items="5" data-full-hd-items="5" data-xxl-items="4" data-xl-items="7" data-lg-items="3.5" 
                                                @else 
                                                    data-items="12" data-full-hd-items="12" data-xxl-items="8" data-xl-items="6" data-lg-items="4" 
                                                @endif 
                                                data-md-items="3" data-sm-items="4" data-xs-items="3" data-arrows='false' data-autoplay="true" data-infinite="true">
                                                @foreach ($flash_deal_products as $key => $flash_deal_product)
                                                    @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                                        @php
                                                            $product_url = route('product', $flash_deal_product->product->slug);
                                                            if ($flash_deal_product->product->auction_product == 1) {
                                                                $product_url = route('auction-product', $flash_deal_product->product->slug);
                                                            }
                                                        @endphp    
                                                        <div class="text-center">
                                                            <a href="{{ $product_url }}" title="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                class="d-block overflow-hidden text-center mx-auto hov-scale-img rounded-2 img-aspect-ratio-200px">
                                                                <img class="w-100 lazyload  has-transition"
                                                                    src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                                                    data-src="" alt="{{ $flash_deal_product->product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                            </a>
                                                            @if ($flash_deal_product->auction_product == 0)
                                                                <p class="mt-2 mb-0 text-center">
                                                                    <span class="fs-13 fs-md-14 text-dark fw-bold">{{ home_discounted_base_price($flash_deal_product->product) }}</span>
                                                                    @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                                        <del class="fs-11 fs-md-14 text-gray fw-400 ">{{ home_base_price($flash_deal_product->product) }}</del>
                                                                    @endif
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif    
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @php
                            $todays_deal_products = filter_products(App\Models\Product::where('todays_deal', '1'))->inRandomOrder()->get();
                            $todays_deal_title_sub_text = get_setting('todays_deal_title_sub_text', null);
                        @endphp
                        @if (get_setting('enable_todays_deal') == 1 && $todays_deal_products != null)
                            <div class="@if (get_setting('enable_flash_deal') == 1 && get_setting('enable_todays_deal') == 1) col-xxl-6 @else col-xxl-12 @endif py-30px ">
                                <!-- Heading -->
                                <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                    <div class="flex-grow-1 text-xxl-center">
                                        <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate("Today's Deals") }}</h5>
                                        <span class="fs-14 fw-400 text-reset">{{ $todays_deal_title_sub_text }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('todays-deal') }}"
                                            class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                    </div>
                                </div>

                                <!-- Slider -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none  td-product-slider overflow-hidden"
                                    
                                    @if (get_setting('enable_flash_deal') == 1 && get_setting('enable_todays_deal') == 1) 
                                        data-items="7" data-full-hd-items="7" data-xxl-items="6" data-xl-items="8" data-lg-items="5"   
                                    @else 
                                        data-items="12" data-full-hd-items="12" data-xxl-items="10" data-xl-items="6" data-lg-items="5"  
                                    @endif 
                                    data-md-items="4" data-sm-items="4" data-xs-items="3" data-arrows='false'
                                    data-autoplay="true" data-infinite="true">
                                    @if(count($todays_deal_products) > 0)
                                        @foreach ($todays_deal_products as $key => $product)
                                            <div class="text-center">
                                                <a href="{{ route('product', $product->slug) }}" title="{{  $product->getTranslation('name')  }}"
                                                    class="d-block overflow-hidden text-center mx-auto hov-scale-img rounded-2 img-aspect-ratio-200px">
                                                    <img class="w-100 lazyload  has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ get_image($product->thumbnail) }}"
                                                        alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                                <p class="mt-2 mb-0 text-center">
                                                    <span class="fs-13 fs-md-14 text-dark fw-bold">{{ home_discounted_base_price($product) }}</span>
                                                    @if(home_base_price($product) != home_discounted_base_price($product))
                                                        <del class="fs-11 fs-md-14 text-gray fw-400 ">{{ home_base_price($product) }}</del>
                                                    @endif
                                                </p>
                                            </div>
                                        @endforeach    
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif
        <!-- Flash & Todays Deals End -->

        <!-- Featured Categories Start -->
        @php
            $featured_category_texts = json_decode(get_setting('featured_category_texts'), true) ?? [];
            $featured_category_title_sub_text = get_setting('featured_categories_title_sub_text', null);
        @endphp
        @if (get_setting('enable_featured_categories') == 1)
            @if (count($featured_categories) > 0)
                <div class="layout-container mx-auto px-3 py-30px">
                    <div class="row gutters-16">
                        <div class="col-12 col-md-auto  mt-lg-4 pt-2 mb-2 mb-md-0">
                            <h5 class="fs-20 fw-bold text-dark m-0"> {{ translate('Featured Categories') }} </h5>
                            <p class="fs-14 fw-400 text-dark mt-1 mb-4 mb-md-5"> {{ $featured_category_title_sub_text }} </p>
                            <a href="{{ route('categories.all') }}"
                                class="fs-12 fw-bold text-white bg-dark rounded-pill px-3 py-3 hov-opacity-80 has-transition">{{ translate('View All Categories') }}
                            </a>
                        </div>
                        <div class="col mt-4 mt-md-0">
                            <!-- Slider -->
                            <div class="aiz-carousel arrow-x-0 arrow-inactive-none featured-categories-slider" data-items="10"
                                data-full-hd-items="10" data-xxl-items="8" data-xl-items="5.5" data-lg-items="4.2"
                                data-md-items="3.2" data-sm-items="3" data-xs-items="3" data-arrows='false'
                                data-autoplay="true" data-infinite="true">
                                @foreach ($featured_categories->shuffle() as $key => $category)
                                    @php
                                        $category_name = $category->getTranslation('name');
                                    @endphp
                                    <div class="">
                                        <a href="{{ route('products.category', $category->slug) }}"
                                            class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-250px">
                                            <img class="w-100 lazyload  has-transition"
                                                src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                                alt="{{ $category->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </a>
                                        <div class="mt-3 mb-0 text-center">
                                            <a href="{{ route('products.category', $category->slug) }}" title="{{ $category_name }}"
                                                class="fs-13 fs-md-16 text-reset hov-text-blue fw-semibold d-block text-center mb-1 has-transition text-truncate">{{ $category_name }}</a>
                                            <span class="fs-11 fs-md-14 text-muted fw-400 d-block text-center">
                                                {{ $featured_category_texts[$category->id] ?? null }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <!-- Featured Categories End -->

        <!-- Banner Section Start -->
        @if (get_setting('enable_banner_1') == 1)
            @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang); @endphp
            @if ($homeBanner1Images != null)
                @php
                    $banner_1_imags = json_decode($homeBanner1Images);
                    $home_banner1_links = get_setting('home_banner1_links', null, $lang);
                @endphp
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="2" data-full-hd-items="2" data-xxl-items="2"
                    data-xl-items="2" data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows='false'
                    data-autoplay="true" data-infinite="true">
                    @foreach ($banner_1_imags as $key => $value)
                        <a href="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}" class="d-block">
                            <div class="banner-lg-container hov-scale-img overflow-hidden">
                                <img class="img-fit w-100 h-100 lazyload  has-transition"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
        <!-- Banner Section End -->
 
        <!-- Featured Product & Best Selling Start -->
        @if (get_setting('enable_featured_products') == 1 || get_setting('enable_best_selling_products') == 1)
            <div class="border-bottom">
                <div class="layout-container mx-auto px-3 feactured-best-selling-product-section">
                    <div class="row">
                        @php
                            $featured_products_title_sub_text = get_setting('featured_products_title_sub_text', null);
                            $best_selling_products_title_sub_text = get_setting('best_selling_products_title_sub_text', null);
                        @endphp
                        <!-- Featured Products Start -->
                        @if (get_setting('enable_featured_products') == 1 && count(get_featured_products()) > 0)
                            <div class="@if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) col-lg-6 @else col-lg-12 @endif py-30px featured-products-wrapper">
                                <!-- Heading -->
                                <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                    <div class="flex-grow-1">
                                        <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Featured Products') }}</h5>
                                        <span class="fs-14 fw-400 text-reset">{{ $featured_products_title_sub_text }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{route('featured-products')}}"
                                            class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                    </div>
                                </div>

                                <!-- Slider -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden mt-4"
                                    @if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) 
                                        data-items="4.2" data-full-hd-items="4.2" data-xxl-items="3" data-xl-items="3" data-lg-items="3" 
                                    @else 
                                        data-items="8" data-full-hd-items="8" data-xxl-items="6" data-xl-items="5" data-lg-items="4" 
                                    @endif
                                    data-md-items="4" data-sm-items="3" data-xs-items="2" data-arrows='false' data-autoplay="true" data-infinite="true">
                                    @foreach (get_featured_products()->shuffle() as $key => $product)
                                        <div class="">
                                            <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-300px">
                                                <img class="w-100 lazyload  has-transition"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ get_image($product->thumbnail) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                class="fs-14 fw-400 text-reset d-block mt-3 product-title hov-text-blue has-transition">{{ $product->getTranslation('name') }}
                                            </a>
                                            <p class="mt-2 mb-0">
                                                <span class="fs-13 fs-md-16 text-dark fw-bold mr-1">{{ home_discounted_base_price($product) }}</span>
                                                @if (home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="fs-11 fs-md-14 text-gray fw-400 ">{{ home_base_price($product) }}</del>
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- Featured Products End -->

                        <!-- Best Selling Start -->
                        @php
                            $best_selling_products = get_best_selling_products(20)->shuffle();
                        @endphp
                        @if (get_setting('best_selling') == 1 && count($best_selling_products) > 0 && get_setting('enable_best_selling_products') == 1)
                            <div class="@if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) col-lg-6 @else col-lg-12 @endif py-30px best-selling-products-wrapper">
                                <!-- Heading -->
                                <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                    <div class="flex-grow-1">
                                        <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Best Selling') }}</h5>
                                        <span class="fs-14 fw-400 text-reset">{{ $best_selling_products_title_sub_text }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{route('best-selling')}}"
                                            class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                    </div>
                                </div>

                                <!-- Slider -->
                                <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden mt-4"
                                    @if (get_setting('enable_featured_products') == 1 && get_setting('enable_best_selling_products') == 1) 
                                        data-items="4.2" data-full-hd-items="4.2" data-xxl-items="3" data-xl-items="3" data-lg-items="3" 
                                    @else 
                                        data-items="8" data-full-hd-items="8" data-xxl-items="6" data-xl-items="5" data-lg-items="4" 
                                    @endif
                                    data-md-items="4" data-sm-items="3" data-xs-items="2" data-arrows='false'
                                    data-autoplay="true" data-infinite="true">
                                    @foreach ($best_selling_products as $key => $product)
                                        <div class="">
                                            <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-300px">
                                                <img class="w-100 lazyload  has-transition"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ get_image($product->thumbnail) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <a href="{{ route('product', $product->slug) }}"
                                                class="fs-14 fw-400 text-reset d-block mt-3 product-title hov-text-blue has-transition">{{ $product->getTranslation('name') }}
                                            </a>
                                            <p class="mt-2 mb-0">
                                                <span class="fs-13 fs-md-16 text-dark fw-bold mr-1">{{ home_discounted_base_price($product) }}</span>
                                                @if (home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="fs-11 fs-md-14 text-gray fw-400 ">{{ home_base_price($product) }}</del>
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- Best Selling End -->
                    </div>
                </div>
            </div>
        @endif    
        <!-- Featured Product & Best Selling End -->

        <!-- Categories -->
        @php
            $mainCategories = json_decode(get_setting('main_categories'), true) ?? [];
            $childCategories = json_decode(get_setting('child_categories'), true) ?? [];

             // Preserve the relationship between main and child categories
            $combinedCategories = [];
            foreach ($mainCategories as $key => $mainCategoryId) {
                $combinedCategories[] = [
                    'key' => $key,
                    'mainCategoryId' => $mainCategoryId,
                ];
            }

            $combinedCategories = collect($combinedCategories)->shuffle();
        @endphp

        @if (get_setting('enable_categories') == 1 && count($mainCategories) > 0)

            <div class="border-bottom">
                <div class="layout-container mx-auto px-3 mf-w-wf-bc">

                    <div class="row gutters-0 mf-w-wf-bc-grid">

                        @foreach ($combinedCategories as $item)

                            @php
                                $key = $item['key'];
                                $mainCategoryId = $item['mainCategoryId'];

                                $mainCategory = \App\Models\Category::find($mainCategoryId);

                                if (!$mainCategory) {
                                    continue;
                                }

                                $selectedChildIds = $childCategories[$key] ?? [];

                                $selectedChildren = \App\Models\Category::whereIn('id', $selectedChildIds)->inRandomOrder()->get();
                            @endphp

                            <div class="col-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3">

                                <!-- Main Category Name -->
                                <h5 class="fs-20 fs-md-20 fw-bold mb-3">
                                    {{ $mainCategory->getTranslation('name') }}
                                </h5>

                                <div class="row gutters-16 d-flex">

                                    <!-- Big Image -->
                                    <div class="col-6 col-md-12 col-lg-6 col-xl-6 pr-lg-0 img-container-col-6">

                                        <a href="{{ route('products.category', $mainCategory->slug) }}"
                                            class="d-block w-100 h-100 overflow-hidden hov-scale-img rounded-2 align-items-stretch img-container-lg  border border-gray-300">

                                            <img class="img-fit w-100 h-100 lazyload has-transition"
                                                src="{{ uploaded_asset($mainCategory->cover_image) }}"
                                                alt="{{ $mainCategory->getTranslation('name') }}">

                                        </a>

                                    </div>

                                    <!-- Child Categories -->
                                    <div class="col-6 col-md-12 col-lg-6 col-xl-6 mt-md-3 mt-lg-0">

                                        <div class="row">

                                            @foreach ($selectedChildren as $childCategory)

                                                <div class="col-12 {{ !$loop->first ? 'mt-3' : '' }}">

                                                    <a href="{{ route('products.category', $childCategory->slug) }}"
                                                        class="text-reset hov-text-blue has-transition">

                                                        <div class="bg-light rounded-2 d-flex justify-content-between overflow-hidden category-card p-3 p-lg-3">

                                                            <h5 class="fs-14 fs-md-16 fw-semibold text-reset m-0 w-xl-110px overflow-hidden text-truncate-2 mr-2 text-break child-cate-title" title="{{ $childCategory->getTranslation('name') }}">

                                                                {{ $childCategory->getTranslation('name') }}

                                                            </h5>

                                                            <div class="w-60px h-60px w-sm-80px h-sm-80px w-md-100px h-md-100px w-xl-80px h-xl-80px overflow-hidden hov-scale-img flex-shrink-0 d-flex align-items-center justify-content-center">

                                                                <img class="img-fluid w-100 h-100 has-transition"
                                                                    src="{{ uploaded_asset($childCategory->cover_image) }}"
                                                                    alt="{{ $childCategory->getTranslation('name') }}">

                                                            </div>

                                                        </div>

                                                    </a>

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>
            </div>

        @endif
        <!-- Categories -->

        <!-- Auction Start -->
        @if (get_setting('enable_auction_products') == 1)
            @if (addon_is_activated('auction'))
                <div id="auction_products"></div>
            @endif
        @endif
        <!-- Auction Product End -->


        <!-- Classified Adds Start -->
        @if (get_setting('enable_classified_products') == 1)
            @if (get_setting('classified_product') == 1)
                @php
                    $classified_products = get_home_page_classified_products()->shuffle();
                    $classified_title_sub_text = get_setting('classified_title_sub_text', null);
                @endphp
                @if (count($classified_products) > 0)
                    <div class="border-bottom">
                        <div class="layout-container mx-auto px-3 py-30px">
                            <!-- Heading -->
                            <div class="d-flex flex-wrap  align-items-start justify-content-between mb-1" style="gap: 12px">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Classified Ads') }}</h5>
                                    <span
                                        class="d-block w-100 fs-14 fw-400 text-reset text-truncate">{{ $classified_title_sub_text }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('customer.products') }}"
                                        class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                </div>
                            </div>
                            <!-- Banner & Slider -->
                            <div class="row d-flex mt-3">
                                <div class="col-12 col-md-auto">
                                    <!-- MD Screen Only -->
                                    <div class="d-none d-md-block h-100">
                                        <a href="{{ route('customer.product', $product->slug) }}"class="d-block w-100 h-100">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch  classified-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset(get_setting('classified_banner_image', null, get_system_language()->code)) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ env('APP_NAME') }} promo">
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Mobile Screen Only  (Upload Antother Image For height 180px) -->
                                    <div class="d-md-none">
                                        <a href="{{ route('customer.product', $product->slug) }}" class="d-block w-100 h-180px">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch  classified-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset(get_setting('classified_banner_image_small', null, get_system_language()->code)) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ env('APP_NAME') }} promo">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col mt-3 mt-md-0">
                                    <!-- Slider -->
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none custom-product-slider overflow-hidden"
                                        id="auction-product-slider" data-items="4" data-rows="2" data-full-hd-items="4"
                                        data-xxl-items="4" data-xl-items="3" data-lg-items="2" data-md-items="1" data-sm-items="1.1"
                                        data-xs-items="1.1" data-arrows='false' data-autoplay="true" data-infinite="true">
                                        @foreach ($classified_products as $key => $product)
                                            <div class="d-flex">
                                                <a href="{{ route('customer.product', $product->slug) }}" title="{{ $product->getTranslation('name') }}"
                                                    class="d-block overflow-hidden hov-scale-img rounded-2 w-150px h-150px mr-2 mr-lg-3  flex-shrink-0">
                                                    <img class="img-fit w-100 h-100 lazyload  has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ get_image($product->thumbnail) }}"
                                                        alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                                <div class="pl-1">
                                                    <a href="{{ route('customer.product', $product->slug) }}"
                                                        class="fs-14 fw-400 text-reset hov-text-blue has-transition text-truncate-2">
                                                        {{ $product->getTranslation('name') }}
                                                    </a>
                                                    <p class="fs-16 fw-bold text-dark mt-3 mb-3">{{ single_price($product->unit_price) }}</p>
                                                    @if ($product->conditon == 'new')
                                                        <button type="button" class="border-0 fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('New') }}</button>
                                                    @elseif($product->conditon == 'used')
                                                        <button type="button" class="border-0 fs-12 fw-bold text-white bg-danger px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('Used') }}</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif
        <!-- Classified Adds End -->


        <!-- Banner Section Start -->
        @if (get_setting('enable_banner_2') == 1)
            @php 
                $homeBanner2Images = get_setting('home_banner2_images', null, $lang); 
            @endphp
            @if ($homeBanner2Images != null)
                @php
                    $banner_2_images = json_decode($homeBanner2Images, true) ?? [];
                    $data_md = count($banner_2_images) >= 2 ? 2 : 1;
                    $home_banner2_links = get_setting('home_banner2_links', null, $lang);
                @endphp
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="4" data-full-hd-items="4" data-xxl-items="3"
                    data-xl-items="3" data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="1"
                    data-arrows='false' data-autoplay="true" data-infinite="true">
                    @foreach ($banner_2_images as $key => $value)
                        <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}" class="d-block">
                            <div class="banner-lg-container-two hov-scale-img overflow-hidden">
                                <img class="img-fit w-100 h-100 lazyload  has-transition"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}"
                                    alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
        <!-- Banner Section End -->

        <!-- Pre Order Adds Start -->
        @if (get_setting('enable_preorder_products') == 1)
            @if (addon_is_activated('preorder'))
                @php
                    $newest_preorder_products = \App\Models\PreorderProduct::where('is_published', 1)
                        ->where(function ($query) {
                            $query->whereHas('user', function ($q) {
                                $q->where('user_type', 'admin');
                            })->orWhereHas('user.shop', function ($q) {
                                $q->where('verification_status', 1);
                            });
                        })
                        ->latest()
                        ->limit(12)
                        ->get()
                        ->shuffle();
                    $preorder_title_sub_text = get_setting('preorder_title_sub_text', null);    
                @endphp
                @if (count($newest_preorder_products) > 0)
                    <div class="border-bottom">
                        <div class="layout-container mx-auto px-3 py-30px">
                            <!-- Heading -->
                            <div class="d-flex flex-wrap  align-items-start justify-content-between mb-1" style="gap: 12px">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Preorder Products') }}</h5>
                                    <span
                                        class="d-block w-100 fs-14 fw-400 text-reset text-truncate">{{ $preorder_title_sub_text }}
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('all_preorder_products') }}"
                                        class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                </div>
                            </div>
                            <!-- Banner & Slider -->
                            @php
                                $newest_preorder_banner_image = get_setting('newest_preorder_banner_image', null, $lang);
                                $newest_preorder_banner_image_small = get_setting('newest_preorder_banner_image_small', null, $lang);
                            @endphp
                            <div class="row d-flex mt-3">
                                <div class="col-12 col-md-auto">
                                    <!-- MD Screen Only -->
                                    <div class="d-none d-md-block h-100">
                                        <a href="{{ route('all_preorder_products') }}" class="d-block w-100 h-100">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch preorder-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset($newest_preorder_banner_image) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ translate('Newest Preorder Products') }}">
                                            </div>
                                        </a>
                                    </div>
                                     <!-- Mobile Screen Only (Upload Antother Image For height 180px) -->
                                    <div class="d-md-none">
                                        <a href="{{ route('all_preorder_products') }}" class="d-block w-100 h-180px">
                                            <div
                                                class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch preorder-banner-main hov-scale-img">
                                                <img class="img-fit w-100 h-100 has-transition"
                                                    src="{{ uploaded_asset($newest_preorder_banner_image_small) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    alt="{{ translate('Newest Preorder Products') }}">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col mt-3 mt-md-0  pre-order-product-wrapper">
                                    <!-- Slider -->
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden"
                                        data-items="7" data-full-hd-items="7" data-xxl-items="4.6" data-xl-items="4.4"
                                        data-lg-items="4" data-md-items="3" data-sm-items="3" data-xs-items="2"
                                        data-arrows='false' data-autoplay="true" data-infinite="true">
                                        @foreach ($newest_preorder_products as $key => $product)
                                            <div class="">
                                                <a href="{{ route('preorder-product.details', $product->product_slug) }}"
                                                    class="d-block overflow-hidden text-center hov-scale-img rounded-2 img-aspect-ratio-300px">
                                                    <img class="w-100 lazyload  has-transition"
                                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                        data-src="{{ uploaded_asset($product->thumbnail) }}"
                                                        alt="{{ $product->product_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                </a>
                                                <a href="{{ route('preorder-product.details', $product->product_slug) }}"
                                                    class="fs-14 fw-400 text-reset d-block mt-3 product-title hov-text-blue has-transition text-truncate-2">{{ $product->product_name }}
                                                </a>
                                                <div class="rating rating-mr-2 mt-2 d-flex" style="gap: 8px">
                                                    {{ renderStarRating($product->rating) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif
        <!-- Pre Order Product End -->

        <!-- Shop by Sellers, Shop by Brands Start -->
        @if (get_setting('enable_shop_by_seller') == 1 || get_setting('enable_shop_by_brand') == 1)
            <div class="border-bottom">
                <div class="layout-container mx-auto px-3 seller-brand-shop-section">
                    <div class="row">
                        <!-- Shop by Sellers Start -->
                        @if (get_setting('vendor_system_activation') == 1 && get_setting('enable_shop_by_seller') == 1 )
                            @php
                                $best_selers = get_best_sellers(6)->shuffle();
                                $shop_by_seller_title_sub_text = get_setting('shop_by_seller_title_sub_text', null);
                            @endphp
                            @if (count($best_selers) > 0)
                                <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-lg-6 @else col-lg-12 border-0 @endif py-30px featured-products-wrapper">
                                    <!-- Heading -->
                                    <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Shop by Sellers') }}</h5>
                                            <span
                                                class="fs-14 fw-400 text-reset d-block text-truncate">{{ $shop_by_seller_title_sub_text }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('sellers') }}"
                                                class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                        </div>
                                    </div>
                                    {{-- <div class="row gutters-16 mt-4 shop-by-seller">
                                        @foreach ($best_selers as $key => $seller)
                                            <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-6 col-md-4 col-lg-6 col-xl-4 col-xxl-3 @else col-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 @endif mb-3">
                                                <a href="{{ route('shop.visit', $seller->slug) }}"
                                                    class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                    <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($seller->logo) }}"
                                                        alt="{{ $seller->name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                </a>
                                                <a href="{{ route('shop.visit', $seller->slug) }}"
                                                    class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition">{{ $seller->name }}
                                                </a>
                                                <div class="rating rating-mr-1 mt-1 mb-1 d-flex" style="gap: 8px">
                                                    {{ renderStarRating($seller->rating) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('shop.visit', $seller->slug) }}"
                                                        class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center w-100 py-2">
                                                        <span class="pr-2">
                                                            {{ translate('Visit Store') }}
                                                        </span>
                                                        <i class="las la-arrow-right fs-18"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div> --}}

                                    <!-- Slider -->
                                   <div class="mt-4 shop-by-seller">
                                        <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden mt-4"
                                            @if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) 
                                                    data-items="4" data-full-hd-items="4" data-xxl-items="4" data-xl-items="3" data-lg-items="2" data-md-items="2"
                                            @else 
                                                data-items="6" data-full-hd-items="6" data-xxl-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3"
                                            @endif
                                            data-sm-items="2" data-xs-items="2" data-arrows='false'
                                            data-autoplay="false" data-infinite="true">
                                                @foreach ($best_selers as $key => $seller)
                                                    <div>
                                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                                            class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                            <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                                src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($seller->logo) }}"
                                                                alt="{{ $seller->name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                        </a>
                                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                                            class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition">{{ $seller->name }}
                                                        </a>
                                                        <div class="rating rating-mr-1 mt-1 mb-1 d-flex" style="gap: 8px">
                                                            {{ renderStarRating($seller->rating) }}
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('shop.visit', $seller->slug) }}"
                                                                class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center w-100 py-2">
                                                                <span class="pr-2">
                                                                    {{ translate('Visit Store') }}
                                                                </span>
                                                                <i class="las la-arrow-right fs-18"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                        </div>
                                   </div>

                                </div>
                            @endif    
                        @endif    
                        <!-- Shop by Sellers End -->

                        <!-- Shop by Brands Start -->
                        @if (get_setting('top_brands') != null && get_setting('enable_shop_by_brand') == 1 )
                            @php
                                $shop_by_brand_title_sub_text = get_setting('shop_by_brand_title_sub_text', null);
                            @endphp
                            <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-lg-6 @else col-lg-12 @endif py-30px best-selling-products-wrapper">
                                <!-- Heading -->
                                <div class="d-flex flex-wrap  align-items-start justify-content-between" style="gap: 12px">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Shop by Brands') }}</h5>
                                        <span
                                            class="fs-14 fw-400 text-reset d-block text-truncate">{{ $shop_by_brand_title_sub_text }}
                                        </span>
                                    </div>
                                    <div>
                                        <a href="{{ route('brands.all') }}"
                                            class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
                                    </div>
                                </div>
                                <!-- Products -->
                                {{-- <div class="row gutters-16 mt-4 shop-by-brand"> --}}
                                    @php
                                        $top_brands = json_decode(get_setting('top_brands'));
                                        $brands = get_brands($top_brands)->shuffle();
                                        $shop_by_brand_title_sub_text = get_setting('shop_by_brand_title_sub_text', null);
                                    @endphp
                                    {{-- @foreach ($brands as $brand)
                                        <div class="@if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) col-6 col-md-4 col-lg-6 col-xl-4 col-xxl-3 @else col-6 col-md-4 col-lg-3 col-xl-2 col-xxl-2 @endif mb-3">
                                            <a href="{{ route('products.brand', $brand->slug) }}"
                                                class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                    src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                                    alt="{{ $brand->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </a>
                                            <a href="{{ route('products.brand', $brand->slug) }}"
                                                class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition text-center">{{ $brand->getTranslation('name') }}
                                            </a>
                                            <div class="text-center">
                                                <a href="{{ route('products.brand', $brand->slug) }}"
                                                    class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center justify-content-center w-100 py-2 w-100">
                                                    <span class="pr-2">
                                                        {{ translate('View All Products') }}
                                                    </span>
                                                    <i class="las la-arrow-right fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach --}}
                                {{-- </div> --}}

                                
                                <!-- Product Slider -->
                                <div class="mt-4 shop-by-brand">
                                    <div class="aiz-carousel arrow-x-0 arrow-inactive-none  custom-product-slider overflow-hidden mt-4"
                                        @if (get_setting('enable_shop_by_seller') == 1 && get_setting('enable_shop_by_brand') == 1) 
                                                data-items="4" data-full-hd-items="4" data-xxl-items="4" data-xl-items="3" data-lg-items="2" data-md-items="2"
                                        @else 
                                            data-items="6" data-full-hd-items="6" data-xxl-items="5" data-xl-items="4" data-lg-items="4" data-md-items="3"
                                        @endif
                                        data-sm-items="2" data-xs-items="2" data-arrows='false'
                                        data-autoplay="false" data-infinite="true">
                                            @foreach ($brands as $brand)
                                                <div>
                                                    <a href="{{ route('products.brand', $brand->slug) }}"
                                                        class="d-block overflow-hidden hov-scale-img rounded-2 w-100 h-140px h-xxl-170px border border-gray-300">
                                                        <img class="img-fit w-100 h-100 lazyload has-transition" style="object-position: center;"
                                                            src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}" data-src=""
                                                            alt="{{ $brand->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </a>
                                                    <a href="{{ route('products.brand', $brand->slug) }}"
                                                        class="fs-16 fw-bold text-reset d-block mt-3 text-truncate hov-text-blue has-transition text-center">{{ $brand->getTranslation('name') }}
                                                    </a>
                                                    <div class="text-center">
                                                        <a href="{{ route('products.brand', $brand->slug) }}"
                                                            class="fs-12 fw-bold text-reset hov-text-blue has-transition d-flex align-items-center justify-content-center w-100 py-2 w-100">
                                                            <span class="pr-2">
                                                                {{ translate('View All Products') }}
                                                            </span>
                                                            <i class="las la-arrow-right fs-18"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Shop by Brands End -->
                    </div>
                </div>
            </div>
        @endif

        <!-- Shop by Sellers, Shop by Brands End -->

        <!-- Mega Banner Start -->
        @if (get_setting('enable_banner_3') == 1)
            @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang);   @endphp
            @if ($homeBanner3Images != null)
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none d-none d-md-block" data-items="1" data-full-hd-items="1" data-xxl-items="1"
                    data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1"
                    data-arrows='false' data-autoplay="true" data-infinite="true">
                    @php
                        $banner_3_imags = json_decode($homeBanner3Images);
                        $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                        $home_banner3_colors = get_setting('home_banner3_colors', null, $lang);
                    @endphp
                    @foreach ($banner_3_imags as $key => $value)
                        <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}" class="d-block w-100 mega-banner-wrapper">
                            <div class="mega-banner-container hov-scale-img overflow-hidden">
                                <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}"
                                    alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
            @php $smallHomeBanner3Images = get_setting('small_home_banner3_images', null, $lang);   @endphp
            @if ($smallHomeBanner3Images != null)
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none d-md-none" data-items="1" data-full-hd-items="1" data-xxl-items="1"
                    data-xl-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1"
                    data-arrows='false' data-autoplay="true" data-infinite="true">
                    @php
                        $banner_3_imags_small = json_decode($smallHomeBanner3Images);
                        $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                        $home_banner3_colors = get_setting('home_banner3_colors', null, $lang);
                    @endphp
                    @foreach ($banner_3_imags_small as $key => $value)
                        <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}" class="d-block w-100 mega-banner-wrapper">
                            <div class="mega-banner-container hov-scale-img overflow-hidden">
                                <img class="img-fit mx-auto w-100  h-100 lazyload  has-transition" style="object-position: center;"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($value) }}"
                                    alt="{{ env('APP_NAME') }} promo" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
        <!-- Mega Banner End -->

        <!-- Products Start -->
        <div class="layout-container px-3 py-5 mx-auto" id="nexa-product-wrapper" data-products-per-row="8">
            <div id="section_newest"></div>

            <!-- Load More Button -->
            <div class="mt-5 mb-5 d-flex align-items-center justify-content-center d-none" id="view-more-container">
                <button type="button" id="view-more-btn"
                    class="flex-shrink-0 border border-dashed border-gray-400 rounded-1 bg-white hov-bg-dark text-gray hov-text-white has-transition fs-16 fw-bold py-2 px-4 py-md-3 px-md-4 w-200px w-md-300px w-lg-400px">
                    {{ translate('Load More') }}
                    <i id="spinner-icon" class="las la-lg la-spinner la-spin d-none"></i>
                </button>
            </div>
        </div>
        <!-- Products End -->


        <!-- Back to Top Start -->
        <button id="backToTop" aria-label="Back to top" class="has-transition" data-toggle="tooltip" data-placement="left" title="Back to Top">
            <i class="las la-arrow-up fs-20"></i>
        </button>
         <!-- Back to Top End -->
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

        let loadMoreLimit = 18;

        @if (get_setting('homepage_select') == 'nexa')

            let perRow = parseInt($('#nexa-product-wrapper').attr('data-products-per-row')) || 4;

            loadMoreLimit = perRow * 3;

        @endif

        $.post('{{ route('home.section.newest_products') }}', {
            _token: '{{ csrf_token() }}',
            page: page,
            limit: loadMoreLimit
        }, function(data) {

            $button.prop('disabled', false);
            $button.html(originalText);

            if ($.trim(data) === '') {

                $button.prop('disabled', true)
                    .text('{{ translate("No More Products") }}');

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


<!-- Bact to Top Start -->
<script>
    {
        const btn = document.getElementById('backToTop');

        if (btn) {
            const SCROLL_THRESHOLD = 100;
            let rafId = null;

            const toggle = () => {
                btn.classList.toggle('show', window.scrollY > SCROLL_THRESHOLD);
                rafId = null;
            };

            window.addEventListener('scroll', () => {
                if (rafId) return;
                rafId = requestAnimationFrame(toggle);
            }, { passive: true });

            btn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

        
            toggle();
        }
    }
</script>
<!-- Bact to Top End -->
@endsection
