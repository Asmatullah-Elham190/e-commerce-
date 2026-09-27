@php
    $best_selling_products = get_best_selling_products(20);
@endphp
@if (get_setting('best_selling') == 1 && count($best_selling_products) > 0)
    <section class="p-3 rounded-2 best-salling-section h-100">
        <!-- Top Section -->
        <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
            <!-- Title -->
            <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                <span class="">{{ translate('Best Selling') }}</span>
            </h3>
            <a type="button" class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center" >
                <span><i class="las la-angle-right fs-20 fw-600"></i></span>
                <span class="fs-12 mr-2 text">View All</span>
            </a>
        </div>
        <!-- Product Section -->
        <div class="aiz-carousel  arrow-inactive-transparent arrow-x-0 mt-2 carousel-arrow"
            data-rows="1" data-items="5" data-xxl-items="5" data-xl-items="5" data-lg-items="5"
            data-md-items="5" data-sm-items="2" data-xs-items="1" data-arrows="false" data-dots="false" data-autoplay="true" data-infinite="true">
        
           @foreach ($best_selling_products as $key => $product)
            <div class="carousel-box mt-3 mb-1">
                <div class="img h-100px w-100px h-md-170px w-md-170px rounded overflow-hidden mx-auto the-core-img position-relative image-hover-effect">
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
                <div class="fs-13 mr-1 mt-3 text-center mt-2 px-4" title="{{ $product->getTranslation('name') }}">
                    <span
                        class="fw-300 text-truncate-2"> {{ $product->getTranslation('name') }}</span>
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

        
    </section>
@endif