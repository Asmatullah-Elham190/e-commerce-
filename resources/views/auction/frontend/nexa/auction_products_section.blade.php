<div class="border-bottom">
    <div class="layout-container mx-auto px-3 py-30px">
        <!-- Heading -->
        <div class="d-flex flex-wrap  align-items-start justify-content-between mb-1" style="gap: 12px">
            <div class="flex-grow-1">
                <h5 class="fs-20 fs-md-20 fw-bold mb-1">{{ translate('Auction') }}</h5>
                @php
                    $auction_title_sub_text = get_setting('auction_title_sub_text', null);
                @endphp
                <span class="fs-14 fw-400 text-reset text-truncate">{{ $auction_title_sub_text }}
                </span>
            </div>
            <div>
                <a href="{{ route('auction_products.all') }}"
                    class="fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('View All') }}</a>
            </div>
        </div>

        <!-- Banner & Slider -->
        <div class="row d-flex mt-3">
            <div class="col-12 col-md-auto">
                <!-- MD Screen Only -->
                <div class="d-none d-md-block h-100">
                    <a href="{{ route('auction_products.all') }}" class="d-block w-100 h-100">
                        <div
                            class="img-fit h-100 w-md-200px w-xl-320px rounded-2 overflow-hidden align-self-stretch  auction-banner-main hov-scale-img">
                            <img class="img-fit w-100 h-100 has-transition"
                                src="{{ uploaded_asset(get_setting('auction_banner_image', null, get_system_language()->code)) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                alt="{{ env('APP_NAME') }} promo">
                        </div>
                    </a>
               </div>
               <!-- Mobile Screen Only (Upload Antother Image For height 180px) -->
                <div class="d-md-none">
                    <a href="{{ route('auction_products.all') }}" class="d-block w-100 h-180px">
                        <div
                            class="img-fit w-100 h-100 rounded-2 overflow-hidden align-self-stretch  auction-banner-main hov-scale-img">
                            <img class="img-fit w-100 h-100 has-transition"
                                src="{{ uploaded_asset(get_setting('auction_banner_image_small', null, get_system_language()->code)) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                alt="{{ env('APP_NAME') }} promo">
                        </div>
                    </a>
               </div>
            </div>
            @php
                $products = get_auction_products()->shuffle();
            @endphp
            <div class="col mt-3 mt-md-0">
                <!-- Slider -->
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none custom-product-slider overflow-hidden"
                    id="auction-product-slider" data-items="4" data-rows="2" data-full-hd-items="4" data-xxl-items="4"
                    data-xl-items="3" data-lg-items="2" data-md-items="1" data-sm-items="1.1" data-xs-items="1.1"
                    data-arrows='false' data-infinite='false'>
                    @foreach ($products as $key => $product)
                        <div class="d-flex">
                            <a href="{{ route('auction-product', $product->slug) }}"
                                class="d-block overflow-hidden hov-scale-img rounded-2 w-150px h-150px mr-2 mr-lg-3  flex-shrink-0">
                                <img class="img-fit w-100 h-100 lazyload  has-transition"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                    alt="{{  $product->getTranslation('name')  }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            @php 
                                $highest_bid = $product->bids->max('amount');
                                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                                $gst_rate = gst_applicable_product_rate($product->id);
                            @endphp
                            <div class="pl-1">
                                <a href="{{ route('auction-product', $product->slug) }}" class="fs-14 fw-400 text-reset hov-text-blue has-transition text-truncate-2">
                                    {{  $product->getTranslation('name')  }}
                                </a>
                                <span class="fs-12 fw-400 d-block mt-2"> {{ translate('Starting Bid') }} </span>
                                <p class="fs-16 fw-bold text-dark mb-3">{{ single_price($product->starting_bid) }}</p>
                                <button type="button" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }},{{ $gst_rate }})" class="border-0 fs-12 fw-bold text-white bg-dark px-3 py-2 rounded-pill hov-opacity-80 has-transition">{{ translate('Place Bid') }}</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>