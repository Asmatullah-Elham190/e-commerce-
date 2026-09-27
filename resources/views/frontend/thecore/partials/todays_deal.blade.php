

@if (count($todays_deal_products) > 0)
    <!-- Top Section -->
<div class="border border-2 border-dark rounded-2 h-100">
    <div class="d-flex m-3 align-items-baseline justify-content-between">
            <!-- Title -->
            <h3 class="fs-14 fs-md-16 fw-500 mb-2 mb-sm-0">
                <span class="">{{ translate('Todays Deal') }}</span>
            </h3>
            <!-- Links -->
            <a type="button" class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center" href="{{ route('todays-deal') }}">
                <span><i class="las la-angle-right fs-20 fw-600"></i></span>
                <span class="fs-12 mr-2 text">View All</span>
            </a>
        </div>  
        
        <div class="aiz-carousel  arrow-inactive-transparent arrow-x-0 mt-2 carousel-arrow"
                data-rows="1" data-items="1" data-xxl-items="1" data-xl-items="1" data-lg-items="1"
                data-md-items="1" data-sm-items="1" data-xs-items="1" data-arrows="true" data-dots="false" data-autoplay="true" data-infinite="true">
            
            @foreach ($todays_deal_products as $key => $product)
            <div class="carousel-box mt-3 mb-1">
                
                <div class="img h-120px w-120px h-md-180px w-md-180px rounded overflow-hidden mx-auto the-core-img position-relative image-hover-effect">
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
                        class="fw-300 text-truncate-1"> {{ $product->getTranslation('name') }}</span>
                </div>

                <!-- Price -->
                <div class="fs-14 mr-1 mt-3 text-center">
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
