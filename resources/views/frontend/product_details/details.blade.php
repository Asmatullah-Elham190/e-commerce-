<div class="col-sm-12 col-lg-6">
    <div class="d-flex align-items-center justify-content-end pb-20px right-side-cws">
        @php
        $seller_logo = $detailedProduct->user->shop?->logo 
            ? $detailedProduct->user->shop->logo 
            : (json_decode(get_setting('business_info'), true) ?? [])['shop_logo'] ?? null;
        $unit_name = \App\Models\Unit::where('id', $detailedProduct->unit)->value('name');
        @endphp
        @if ($detailedProduct->auction_product != 1)
            <div>
                <button type="button" onclick="addToCompare({{ $detailedProduct->id }})"
                    class="p-0 d-flex align-items-center bg-transparent fs-12 fw-400 text-gray border-0 hov-text-dark has-transition">
                    <span>
                        <svg id="Group_23788" data-name="Group 23788" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                            <g id="Group_22071" data-name="Group 22071" transform="translate(0.7)">
                                <g id="LWPOLYLINE" transform="translate(0.158)">
                                    <path id="Path_25677" data-name="Path 25677" d="M304.755,426.408v-2.032a.5.5,0,1,1,.993,0v3.239a.5.5,0,0,1-.5.5h-3.216a.5.5,0,0,1,0-1h2.006a6.924,6.924,0,0,0-11.8,1,.5.5,0,0,1-.666.221.5.5,0,0,1-.219-.672,7.913,7.913,0,0,1,13.4-1.256Z" transform="translate(-291.306 -423.269)" fill="#9d9da6"/>
                                </g>
                                <g id="LWPOLYLINE-2" data-name="LWPOLYLINE" transform="translate(0 10.879)">
                                    <path id="Path_25678" data-name="Path 25678" d="M292.141,414.371V416.4a.5.5,0,1,1-.993,0v-3.238a.5.5,0,0,1,.5-.5h3.216a.5.5,0,0,1,0,1h-2.006a6.924,6.924,0,0,0,11.8-1,.493.493,0,0,1,.666-.221.5.5,0,0,1,.219.671,7.913,7.913,0,0,1-13.4,1.256Z" transform="translate(-291.148 -412.39)" fill="#9d9da6"/>
                                </g>
                            </g>
                            <rect id="Rectangle_1604" data-name="Rectangle 1604" width="16" height="16" transform="translate(0 0)" fill="none"/>
                        </svg>
                    </span>
                    <span class="ml-2 d-block compare">{{translate('Compare')}}</span>
                </button>
            </div>
            <div class="mx-3">
                <button type="button" onclick="addToWishList({{ $detailedProduct->id }})"
                    class="p-0 d-flex align-items-center bg-transparent fs-12 fw-400 text-gray border-0 hov-text-dark has-transition">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23789" data-name="Group 23789" transform="translate(-517 -979)">
                                <g id="Wooden" transform="translate(517 980)">
                                    <path id="Path_25676" data-name="Path 25676" d="M290.82,413.6a4.5,4.5,0,0,0-6.364,0l-.318.318-.318-.318a4.5,4.5,0,0,0-6.364,6.364l6.046,6.054a.9.9,0,0,0,1.272,0l6.046-6.054A4.5,4.5,0,0,0,290.82,413.6Zm-.707,5.657-5.975,5.984-5.975-5.984a3.5,3.5,0,1,1,4.95-4.95l.389.389a.9.9,0,0,0,1.272,0l.389-.389a3.5,3.5,0,0,1,4.95,4.95Z" transform="translate(-276.138 -412.286)" fill="#9d9da6"/>
                                </g>
                                <rect id="Rectangle_1603" data-name="Rectangle 1603" width="16" height="16" transform="translate(517 979)" fill="none"/>
                            </g>
                        </svg>
                    </span>
                    <span class="ml-2 d-block wishlist">{{translate('Wishlist')}}</span>
                </button>
            </div>
        @endif
        <div>
            <button type="button" data-toggle="modal" data-target="#social-share-modal"
                class="p-0 d-flex align-items-center bg-transparent fs-12 fw-400 text-gray border-0 hov-text-dark has-transition">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_39026" data-name="Group 39026" transform="translate(-1577 -240)">
                            <path id="Arrow" d="M5.121,22.366a.556.556,0,0,1-.181-.029.583.583,0,0,1-.4-.583c0-.087.6-8.547,9.083-9.211V9.5a.583.583,0,0,1,1-.408l5.75,5.873a.583.583,0,0,1,0,.816l-5.75,5.873a.583.583,0,0,1-1-.408V18.264c-5.663.216-7.985,3.787-8.008,3.831a.583.583,0,0,1-.492.271Zm9.666-11.437v2.159a.583.583,0,0,1-.562.583,8.263,8.263,0,0,0-8.157,6.208,12.051,12.051,0,0,1,8.1-2.8H14.2a.583.583,0,0,1,.583.583v2.159l4.37-4.445Z" transform="translate(1572.462 232.082)" fill="#9d9da6"/>
                            <rect id="Rectangle_24159" data-name="Rectangle 24159" width="16" height="16" transform="translate(1577 240)" fill="none"/>
                        </g>
                    </svg>
                </span>
                <span class="ml-2 d-block share">{{translate('Share')}}</span>
            </button>
        </div>
    </div>

    <h1 class="fs-20 fw-600 text-dark mb-3">{{ $detailedProduct->getTranslation('name') }}</h1>
    
    <!--Brand Name and Ask about Start-->
    <div class="d-flex flex-wrap flex-column flex-sm-row align-items-sm-center justify-content-between">
        <!--LEFT-->
        @if ($detailedProduct->brand != null)
            <div class="d-flex align-items-center">
                <span class="fs-14 fw-400 text-dark pr-2">{{ translate('Brand') }}</span>
                <span class="fs-14 fw-400 text-blue"><a href="{{route('products.brand', $detailedProduct->brand->slug)}}">{{ $detailedProduct->brand->name }}</a></span>
            </div>
        @endif
        <!--RIGHT-->
        @if(get_setting('product_query_activation') == 1)
            <div class="">
                <a href="javascript:void();" onclick="goToView('product_query')"
                    class="d-inline-flex align-items-center my-1 my-sm-0 fs-14 fw-400 text-blue border-0 bg-transparent animate-underline-blue has-transition">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                            <path id="Path_45211" data-name="Path 45211" d="M127.2-864l-.2-2.4h-.2a6.56,6.56,0,0,1-4.82-1.98A6.56,6.56,0,0,1,120-873.2a6.56,6.56,0,0,1,1.98-4.82A6.56,6.56,0,0,1,126.8-880a6.624,6.624,0,0,1,2.65.53,6.868,6.868,0,0,1,2.16,1.46,6.867,6.867,0,0,1,1.46,2.16,6.624,6.624,0,0,1,.53,2.65,8.543,8.543,0,0,1-.49,2.88,10.192,10.192,0,0,1-1.34,2.56,11.331,11.331,0,0,1-2.02,2.14A12.522,12.522,0,0,1,127.2-864Zm-.42-4.82a.79.79,0,0,0,.58-.24.79.79,0,0,0,.24-.58.79.79,0,0,0-.24-.58.79.79,0,0,0-.58-.24.79.79,0,0,0-.58.24.79.79,0,0,0-.24.58.79.79,0,0,0,.24.58A.79.79,0,0,0,126.78-868.82Zm-.58-2.54h1.2a2.059,2.059,0,0,1,.12-.84,4.744,4.744,0,0,1,.76-.88,3.692,3.692,0,0,0,.6-.78,1.785,1.785,0,0,0,.24-.9,1.78,1.78,0,0,0-.69-1.53,2.667,2.667,0,0,0-1.63-.51,2.263,2.263,0,0,0-1.48.49,2.606,2.606,0,0,0-.84,1.19l1.12.44a1.853,1.853,0,0,1,.38-.67,1.015,1.015,0,0,1,.82-.33,1.035,1.035,0,0,1,.81.3.966.966,0,0,1,.27.66,1,1,0,0,1-.2.61,4.965,4.965,0,0,1-.48.55,3.217,3.217,0,0,0-.85.95A3.562,3.562,0,0,0,126.2-871.36Z" transform="translate(-120 880)" fill="#0080fe"/>
                        </svg>
                    </span>
                    <span class="ml-2">{{ translate('Ask about this product')}}</span>
                </a>
            </div>
        @endif
    </div>
    <!--Brand Name and Ask about End-->

    <!--Rating & SKU Start-->
    <div class="d-flex flex-wrap flex-column flex-sm-row align-items-sm-center justify-content-between mt-2">
        <!--LEFT-->
        @php
            $total = 0;
            $total += $detailedProduct->reviews->where('status', 1)->count();
        @endphp
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="d-flex align-items-center pr-10px">
                <span class="rating rating-mr-2">{{ renderStarRating($detailedProduct->rating) }}</span>
            </div>
            <div class="d-flex align-items-center total-rating-count">
                <span class="fs-14 text-dark fw-bold">{{ $detailedProduct->rating }}</span>
                <span class="fs-14 text-gray fw-400">/{{ translate('5.0') }}</span>
                <span class="pl-5px fs-14 text-gray fw-400">({{ $total }} {{ translate('reviews') }})</span>
            </div>
            
        </div>
        @if ($detailedProduct->variant_product)
            <!--RIGHT-->
            <div class="d-flex align-items-center mb-2 mb-md-0" id="variant_sku_section">
                <span class="fs-14 fw-400 text-gray">{{translate('SKU')}}</span>
                <span class="fs-14 fw-500 text-dark ml-2 mr-2" id="variant_sku"></span>
                <button type="button" class="border-0 bg-transparent cursor-pointer sku-copy-btn" onclick="SKUCopyToClipboard()" data-url="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                        <path id="Path_45213" data-name="Path 45213" d="M124.8-867.2a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-9.6a1.541,1.541,0,0,1,.47-1.13,1.541,1.541,0,0,1,1.13-.47H132a1.541,1.541,0,0,1,1.13.47,1.541,1.541,0,0,1,.47,1.13v9.6a1.541,1.541,0,0,1-.47,1.13,1.541,1.541,0,0,1-1.13.47Zm0-1.6H132v-9.6h-7.2Zm-3.2,4.8a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-11.2h1.6v11.2h8.8v1.6Zm3.2-4.8v0Z" transform="translate(-120 880)" fill="#919199"/>
                    </svg>
                </button>
            </div>
        @elseif (($detailedProduct->stocks->first()->sku))
            <!--RIGHT-->
            <div class="d-flex align-items-center mb-2 mb-md-0" id="variant_sku_section">
                <span class="fs-14 fw-400 text-gray">{{translate('SKU')}}</span>
                <span class="fs-14 fw-500 text-dark ml-2 mr-2" id="variant_sku">{{($detailedProduct->stocks->first()->sku)}}</span>
                <button type="button" class="border-0 bg-transparent cursor-pointer sku-copy-btn" onclick="SKUCopyToClipboard()" data-url="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                        <path id="Path_45213" data-name="Path 45213" d="M124.8-867.2a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-9.6a1.541,1.541,0,0,1,.47-1.13,1.541,1.541,0,0,1,1.13-.47H132a1.541,1.541,0,0,1,1.13.47,1.541,1.541,0,0,1,.47,1.13v9.6a1.541,1.541,0,0,1-.47,1.13,1.541,1.541,0,0,1-1.13.47Zm0-1.6H132v-9.6h-7.2Zm-3.2,4.8a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-11.2h1.6v11.2h8.8v1.6Zm3.2-4.8v0Z" transform="translate(-120 880)" fill="#919199"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
    <!--Rating & SKU End-->

    <!--Watching Product Start-->
    <div class="d-flex flex-column align-items-start mt-3">
        @if(get_setting('show_custom_product_visitors')==1)
        <div class="d-flex align-items-center" id="live-product-viewing-visitors">
            <span class="people-view pulse position-relative d-inline-flex align-items-center justify-content-center w-15px h-15px">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#e3e3e3">
                    <path d="M480.28-96Q401-96 331-126t-122.5-82.5Q156-261 126-330.96t-30-149.5Q96-560 126-629.5q30-69.5 82.5-122T330.96-834q69.96-30 149.5-30t149.04 30q69.5 30 122 82.5T834-629.28q30 69.73 30 149Q864-401 834-331t-82.5 122.5Q699-156 629.28-126q-69.73 30-149 30Zm-.28-72q130 0 221-91t91-221q0-130-91-221t-221-91q-130 0-221 91t-91 221q0 130 91 221t221 91Zm0-72q-100 0-170-70t-70-170q0-100 70-170t170-70q100 0 170 70t70 170q0 100-70 170t-170 70Zm0-72q70 0 119-49t49-119q0-70-49-119t-119-49q-70 0-119 49t-49 119q0 70 49 119t119 49Zm-.21-96Q450-408 429-429.21t-21-51Q408-510 429.21-531t51-21Q510-552 531-530.79t21 51Q552-450 530.79-429t-51 21Z"/>
                </svg>
                <span class="pulse-layer"></span>
            </span>
            <span class="fs-14 fw-bold text-dark mx-2 count"></span>
            <span class="fs-14 fw-400 text-dark">{{ translate('People are viewing right now')}}</span>
        </div>
        @endif
        <div class="d-flex align-items-center">
            @if ($detailedProduct->auction_product)
                <span class="fs-14 fw-400 text-gray">{{ translate('Bided')}}</span>
                <span class="fs-14 fw-bold text-dark ml-2">{{$detailedProduct->bids()->count()}}</span>
            @else
                {{-- <span class="fs-14 fw-400 text-gray mr-2">{{ translate('Total Ordered')}}</span>
                <span class="fs-14 fw-bold text-dark">{{$detailedProduct->orderDetails()->count()}}</span> --}}
            @endif
        </div>
    </div>
    <!--Watching Product End-->

    <!--Todays Deal Start-->
    <div class="mt-4 mb-3">
        @if($detailedProduct->todays_deal)
            <div class="bg-dark py-10px px-20px rounded-corner-8px d-flex flex-wrap align-items-center justify-content-between">
                <span class="fs-14 fw-bold text-orange">{{ translate('Todays Deal')}}</span>
                <span class="fs-14 fw-semibold text-orange">{{ translate('Exclusive for today only')}}</span>
            </div>
        @endif
        <!--Todays Deal End-->

        <!--Flash Sale Start-->
        @php
            $flashDealEndDate = get_product_active_flash_deal_end_date($detailedProduct->id, $detailedProduct->discount_end_date);
            $flashDealnotEnd = !is_null($flashDealEndDate);
        @endphp
        @if($flashDealnotEnd)
            <div id="flashSaleBox" class="flash-sale py-10px px-20px rounded-corner-8px d-flex flex-wrap align-items-center justify-content-between mt-2">
                <div>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12.8" height="16" viewBox="0 0 12.8 16">
                            <path d="M163.2-864l.8-5.6h-4l7.2-10.4h1.6l-.8,6.4h4.8l-8,9.6Z" transform="translate(-160 880)" fill="#f5f5f8"/>
                        </svg>
                    </span>
                    <span class="fs-14 fw-bold text-white text-uppercase ml-1">{{translate('Flash Sale')}}</span>
                </div>
                <!-- Countdown: ISO 8601 UTC string -->
                <span class="fs-14 fw-400 text-white flashSaleCountdown" data-end-date="{{ date('Y/m/d H:i:s', $flashDealEndDate) }}">{{ translate('Loading...')}}</span>
            </div>
        @endif
    </div>
    <!--Flash Sale End-->

    @if ($detailedProduct->auction_product)
        <!-- For auction product Start -->
        <div class="py-10px px-20px border border-light-gray rounded-2 mt-4">
            <div class="border-bottom-dashed pt-2 pb-3">
                <p class="mb-1 fs-14 fw-bold text-dark">{{ translate('Auction Status') }}</p>
                <div class="d-flex flex-wrap align-items-center">
                    <div class="mr-2">
                        <div class="text-dark fs-14 fw-400">{{ translate('Auction Will End') }}</div>
                    </div>
                    <div class="">
                        @if ($detailedProduct->auction_end_date > strtotime('now'))
                            <div class="aiz-count-down align-items-center" data-date="{{ date('Y/m/d H:i:s', $detailedProduct->auction_end_date) }}"></div>
                        @else
                            <p class="m-0 text-dark fs-14 fw-400">{{ translate('Ended') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-bottom-dashed py-3">
                <div class="mb-1 fs-14 fw-bold text-dark">{{ translate('Starting Bid') }}</div>
                <div class="">
                    <span class="text-dark fs-20 fw-400">{{ single_price($detailedProduct->starting_bid) }}</span>
                    @if ($detailedProduct->unit != null)
                        <span class="text-secondary fs-14 fw-400">/{{ $unit_name }}</span>
                    @endif
                </div>
            </div>

            <div class="pt-3 pb-2">
                <div class="mb-1 fs-14 fw-bold text-dark">{{ translate('Highest Bid') }}</div>
                <div class="">
                    @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp
                    <span class="text-dark fs-20 fw-400">
                        @if ($highest_bid != null)
                            {{ single_price($highest_bid) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
            $gst_rate = gst_applicable_product_rate($detailedProduct->id);
        @endphp
        @if ($detailedProduct->auction_end_date >= strtotime('now'))
            <div class="mt-4">
                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                    <span class="bg-danger text-white text-center border-0 rounded-1 fs-12 py-10px px-10px d-block w-100 mb-2 mb-md-0 mr-0 mr-md-2">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                @else
                    <button type="button" onclick="bid_modal()"
                        class="text-white border-0 rounded-1 fs-14 fw-bold bg-black hov-opacity-70 has-transition py-20px px-20px d-block w-100 mb-2 mb-md-0 mr-0 mr-md-2">
                        <i class="las la-gavel"></i>
                        @if (Auth::check() && Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                            {{ translate('Change Bid') }}
                        @else
                            {{ translate('Place Bid') }}
                        @endif
                    </button>
                @endif
            </div>
        @endif

        <!--Shipping Notes Start-->
        @if ($detailedProduct->est_shipping_days && $detailedProduct->show_estimated_shipping_time ==1)
            <div class="d-flex flex-wrap align-items-center mt-3">
                <p class="my-0 ml-0 mr-4 mr-md-4 mr-lg-5 fs-14 fw-400 text-black">{{ translate('Estimate Shipping Time') }} <span class="fw-bold ml-2">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }} </span></p>
            </div>
        @endif
        <div class="py-20px d-flex flex-wrap flex-xl-nowrap align-items-center">
            <div class="d-flex align-items-center bg-light p-20px rounded-2 w-100 mb-2 mb-xl-0 mr-0 mr-xl-2">
                <div class="store-logo-container w-48px h-48px bg-white border bordre-2 border-gray rounded-1 overflow-hidden mr-3 d-flex flex-shrink-0 align-items-center justify-content-center">
                    <img src="{{ uploaded_asset($seller_logo) }}" alt="seller">
                </div>
                <div>
                    @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                        <p class="m-0 fs-14">
                            <span class="fw-400 text-gray">{{ translate('Sold by') }}</span>
                            <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="fw-bold text-blue ml-2">{{ $detailedProduct->user->shop->name }}</a>
                        </p>
                    @else
                        <p class="m-0 fs-14">
                            <span class="fw-bold text-blue">{{ translate('Inhouse product') }}</span>
                        </p>
                    @endif
                    @if (get_setting('conversation_system') == 1)
                        <a href="javascript:void();" onclick="show_chat_modal()" class="fs-14 fw-400 text-blue animate-underline-blue has-transition">{{ translate('Message Seller') }}</a>
                    @endif
                </div>
            </div>
            @if ($detailedProduct->brand != null)
                <div class="d-flex align-items-center bg-light p-20px rounded-2 w-100">
                    <div class="store-logo-container w-48px h-48px bg-white border bordre-2 border-gray rounded-1 overflow-hidden mr-3 d-flex flex-shrink-0 align-items-center justify-content-center">
                        <img src="{{ uploaded_asset($detailedProduct->brand->logo) }}"alt="{{ $detailedProduct->brand->name }}" class="img-fit">
                    </div>
                    <div>
                        <p class="m-0 fs-14">
                            <span class="fw-400 text-gray">{{ translate('Brand') }}</span>
                            <span class="fw-bold text-dark pl-5px">{{ $detailedProduct->brand->name }}</span>
                        </p>
                        <a href="{{route('products.brand', $detailedProduct->brand->slug)}}" class="fs-14 fw-400 text-blue animate-underline-blue has-transition">{{ translate('Products from this brand') }}</a>
                    </div>
                </div>
            @endif
        </div>
        <!--Shipping Notes End-->

        <!--Warranty Start-->
        <div class="warranty-section pb-20px">
            <ul class="m-0 p-0">
                @if ($detailedProduct->cash_on_delivery)
                    <li class="d-flex align-items-center">
                        <span class="d-block flex-shrink-0">
                            <img src="{{ static_asset('/assets/img/warranty-check-circle.svg') }}" alt="warranty check circle">
                        </span>
                        <span class="d-block pl-10px pr-25px fs-14 fw-400 text-dark warranty-text">{{ translate('Cash on Delivery Available')}}</span>
                    </li>
                @endif
                @if ($detailedProduct->shipping_type == 'free')
                    <li class="d-flex align-items-center">
                        <span class="d-block flex-shrink-0">
                            <img src="{{ static_asset('/assets/img/warranty-check-circle.svg') }}" alt="warranty check circle">
                        </span>
                        <span class="d-block pl-10px pr-25px fs-14 fw-400 text-dark warranty-text">{{ translate('Free Shipping') }}</span>
                    </li>
                @endif
            </ul>
        </div>
        <!--Warranty End-->
        <!-- For auction product End -->
    @else
        <form id="option-choice-form" class="product-details-page">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
            
            <!--In Stock Start-->
            <h5 class="fs-16 fw-600 text-gray">{{ translate('Pricing') }}</h5>
            <div class="bg-light overflow-hidden px-20px py-20px rounded-2">
                @if ($detailedProduct->digital == 0)
                    <div class="d-flex align-items-center justify-content-between mb-md-0">
                        <div>
                            <h6 class="m-0 fs-20 fw-600 text-dark">{{ home_discounted_price($detailedProduct) }}
                                @if ($detailedProduct->unit != null)
                                    <span class="opacity-70 fs-16 fw-400">/{{ $unit_name }}</span>
                                @endif
                            </h6>
                            @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                                <del class="m-0 fs-14 fw-400 text-gray px-3">{{ home_price($detailedProduct) }}</del>
                            @endif

                        </div>
                        <!-- Download Button -->
                        @if ($detailedProduct->pdf != null)
                        <a href="{{ uploaded_asset($detailedProduct->pdf) }}" target="_blank" class="fs-14 fw-400 text-blue cursor-pointer"> <i class="las la-download mr-1"></i>
                            {{ translate('Download product specifation') }}
                        </a>
                        @endif
                        
                    </div>
                @else
                    <input type="hidden" name="quantity" value="1">
                    <div class="d-flex flex-wrap align-items-center mb-md-0">
                        <h6 class="m-0 fs-24 fw-bold fw-700 text-dark">{{ home_discounted_price($detailedProduct) }}
                            @if ($detailedProduct->unit != null)
                                <span class="opacity-70 fs-16 fw-400">/{{ $unit_name }}</span>
                            @endif
                        </h6>
                        @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                            <del class="m-0 fs-14 fw-400 text-gray px-3">{{ home_price($detailedProduct) }}</del>
                        @endif
                    </div>
                @endif

                @if (discount_in_percentage($detailedProduct) > 0 || (addon_is_activated('club_point') && $detailedProduct->earn_point > 0))
                <div class="d-flex flex-wrap align-items-center py-5px">
                    <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                        <div class="d-flex align-items-center mt-2">
                            @if (discount_in_percentage($detailedProduct) > 0 && (home_price($detailedProduct) != home_discounted_price($detailedProduct)))
                                <span class="fs-12 fw-bold text-white py-1 py-md-2 px-10px discount-badge rounded-1">-{{ discount_in_percentage($detailedProduct) }}%</span>
                            @endif
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <span class="fs-12 fw-bold text-orange text-uppercase opacity-80 py-1 py-md-2 px-10px bg-soft-light rounded-1 ml-1">{{ translate('Club Point') }}: {{ $detailedProduct->earn_point }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!--In Stock End-->

            @if ($detailedProduct->wholesale_product == 1)
                <!-- Wholesale Start-->
                <div class="py-10px px-15px bg-white border border-light-gray rounded-2 mt-4 mb-4">
                    <table class="table m-0 p-0">
                        <thead>
                            <tr>
                                <th class="border-top-0 fs-14 fw-bold text-dark">{{ translate('Min Qty') }}</th>
                                <th class="border-top-0 fs-14 fw-bold text-dark">{{ translate('Max Qty') }}</th>
                                <th class="border-top-0 fs-14 fw-bold text-dark">{{ translate('Unit Price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                                <tr>
                                    <td>{{ $wholesalePrice->min_qty }}</td>
                                    <td>{{ $wholesalePrice->max_qty }}</td>
                                    <td>{{ single_price($wholesalePrice->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Wholesale End-->
            @else
                @if ($detailedProduct->variant_product)
                    <!--Product Variant Start-->
                    @php
                        $colorCount = ($detailedProduct->colors && count(json_decode($detailedProduct->colors)) > 0) ? 1 : 0;
                        $choiceCount = collect(json_decode($detailedProduct->choice_options ?? '[]', true))->count();
                        $product_variations = $colorCount + $choiceCount;
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                        <div class="d-inline-flex align-items-center">
                            <h5 class="fs-16 fw-600 text-gray mr-2 mb-0">{{translate('Variation')}}</h5>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#9d9da6">
                                    <path d="M454-298h52v-230h-52v230Zm25.79-290.46q11.94 0 20.23-8.08 8.29-8.08 8.29-20.02t-8.08-20.23q-8.08-8.28-20.02-8.28T459.98-637q-8.29 8.08-8.29 20.02t8.08 20.23q8.08 8.29 20.02 8.29Zm.55 472.46q-75.11 0-141.48-28.42-66.37-28.42-116.18-78.21-49.81-49.79-78.25-116.09Q116-405.01 116-480.39q0-75.38 28.42-141.25t78.21-115.68q49.79-49.81 116.09-78.25Q405.01-844 480.39-844q75.38 0 141.25 28.42t115.68 78.21q49.81 49.79 78.25 115.85Q844-555.45 844-480.34q0 75.11-28.42 141.48-28.42 66.37-78.21 116.18-49.79 49.81-115.85 78.25Q555.45-116 480.34-116Zm-.34-52q130 0 221-91t91-221q0-130-91-221t-221-91q-130 0-221 91t-91 221q0 130 91 221t221 91Zm0-312Z"/>
                                </svg>
                            </span>
                        </div>
                        @php
                            $sizeChart = \App\Models\SizeChart::findForProduct($detailedProduct);
                            $sizeChartId = $sizeChart->id ?? 0;
                            $sizeChartName = $sizeChart->name ?? null;
                        @endphp
                        @if($sizeChartId != 0)
                            <div>
                                <button type="button" onclick='showSizeChartDetail({{ $sizeChartId }}, "{{ $sizeChartName }}")' class="fs-14 fw-400 text-blue border-0 bg-transparent d-flex align-items-center hov-opacity-80 has-transition">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15.99" height="16" viewBox="0 0 15.99 16">
                                            <path id="_4" data-name="4" d="M17.988,5.988,14.676,2.675a1.17,1.17,0,0,0-.831-.345h0a1.17,1.17,0,0,0-.825.345l-.825.825h0L10.954,4.741h0L9.713,5.982h0L8.472,7.217h0L7.232,8.469h0L5.991,9.71h0L4.75,10.95h0L3.51,12.191h0l-.831.831a1.17,1.17,0,0,0,0,1.65l3.312,3.312a1.159,1.159,0,0,0,1.65,0L17.988,7.638A1.17,1.17,0,0,0,17.988,5.988ZM6.822,17.16,3.5,13.841l.392-.41,1.241,1.241a.586.586,0,1,0,.831-.825L4.745,12.607l.416-.416L6.4,13.432a.586.586,0,0,0,.831-.825L5.991,11.366l.41-.416,2.072,2.072a.586.586,0,0,0,.825-.831L7.232,10.125l.41-.416L8.888,10.95a.583.583,0,1,0,.825-.825L8.472,8.884l.416-.416L10.129,9.71a.585.585,0,1,0,.825-.825L9.713,7.638l.416-.41,2.066,2.066a.586.586,0,1,0,.831-.825L10.954,6.4l.416-.416L12.61,7.228a.586.586,0,0,0,.825-.831L12.194,5.157l.416-.416,1.235,1.247a.586.586,0,1,0,.825-.831L13.435,3.893l.41-.392,3.318,3.318Z" transform="translate(-2.338 -2.33)" fill="#0080ff"/>
                                        </svg>
                                    </span>
                                    <span class="pl-1">{{translate('Size Guide')}}</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="px-20px py-10px border border-soft-light rounded-2 product-variant position-relative collapsed">
                        <!--Variant Selection-->
                        <!-- Choice Options -->
                        @if ($detailedProduct->choice_options != null)
                            @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                                <div class="py-10px {{ $key == 0 ? '' : 'variant-item' }} {{ $key <9 ? '' : 'variant-item-none' }}">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="m-0 fs-14 fw-bold text-dark">{{ get_single_attribute_name($choice->attribute_id) }}</p>
                                    </div>
                                    <div class="variant-wrapper">
                                        @foreach ($choice->values as $key => $value)
                                            <label class="rounded-1 bg-white cursor-pointer aiz-megabox mb-1">
                                                <input type="radio" name="attribute_id_{{ $choice->attribute_id }}" value="{{ $value }}" @if ($key == 0) checked @endif>
                                                <div class="variant-item-select aiz-megabox-elem px-10px">
                                                    <span class="fs-14 fw-400 text-dark px-15px">{{ $value }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <!-- Color Options -->
                        @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                            <div class="py-10px  {{ $product_variations>1 ? 'variant-item':''}}">
                                <p class="mb-3 fs-14 fw-bold text-dark">{{ translate('Color') }}</p>
                                <div class="variant-wrapper">
                                    @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                        <label class="aiz-megabox rounded-1 bg-white cursor-pointer" data-title="{{ get_single_color_name($color) }}">
                                            <input type="radio" name="color" value="{{ get_single_color_name($color) }}" @if ($key == 0) checked @endif>
                                            <div class="d-flex align-items-center variant-item-select aiz-megabox-elem px-15px">
                                                <span class="w-15px h-15px rounded-circle" style="background-color: {{ $color }};"></span>
                                                <span class="fs-14 fw-400 text-dark pl-2">{{ get_single_color_name($color) }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- Toggle Button -->
                        @if ($product_variations > 10)
                            <button type="button" id="toggleHeight" class="d-flex align-items-center justify-content-center pt-10px pb-10px w-100 rounded-1 mb-2 fs-12 text-blue fw-400 has-transition border-0 more-toggle-btn" data-more="{{ translate('View More') }}" data-less="{{ translate('See Less') }}">
                                <span class="toggle-text">
                                    {{ translate('View More') }}
                                    <i class="las la-angle-down ms-1"></i>
                                </span>
                            </button>
                        @endif
                    </div>
                    <!--Product Variant End-->
                @endif
            @endif

            @if (((get_setting('product_external_link_for_seller') == 1) && ($detailedProduct->added_by == "seller") && ($detailedProduct->external_link != null)) || (($detailedProduct->added_by != "seller") && ($detailedProduct->external_link != null)))
            @else
                <div class="border-dashed border-1 border-soft-light rounded-2 overflow-hidden mt-4 mb-1 px-20px pt-15px pb-20px">
                    <div class="d-flex pb-10px flex-wrap align-items-center justify-content-between">
                        <div>
                            <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                                @if ($detailedProduct->digital == 0)
                                    <!-- Total Price -->
                                    <div class="no-gutters d-none mr-1 mb-2" id="chosen_price_div">
                                        <div class="product-price">
                                            <strong id="chosen_price" class="fs-24 fw-bold"> </strong>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="quantity" value="1">
                                @endif
                            </div>
                            @if ($detailedProduct->digital ==0)
                                @php
                                    $qty = 0;
                                    foreach ($detailedProduct->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                    }
                                @endphp
                                <div class="d-flex flex-column">
                                    <p class="m-0 fs-14 fw-semibold text-blue opacity-80">
                                        @if ($detailedProduct->stock_visibility_state == 'quantity')
                                            <span id="available-quantity">{{ $qty }}</span> {{translate('Available')}}
                                        @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                                            <span id="available-quantity">{{translate('In Stock')}}</span>
                                        @endif
                                    </p>
                                    <p class="m-0 fs-14 fw-400 text-gray">{{translate('Minimum order qty')}} <span class="text-dark fw-700">{{ $detailedProduct->min_qty }}</span></p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <!--Quantity-->
                            @if ($detailedProduct->digital ==0)
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="fs-14 fw-400 text-gray pr-20px">{{ translate('QTY')}}</span>
                                    <div class="d-flex align-items-center aiz-plus-minus border border border-soft-light rounded-1">
                                        <!--Decrement-->
                                        <button type="button" data-type="minus" data-field="quantity" disabled=""
                                            class="inc-btn bg-transparent ml-2 border-0 w-30px h-35px rounded-circle d-flex align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="1" viewBox="0 0 12 1">
                                                <g id="Group_38869" data-name="Group 38869" transform="translate(-1120.5 -819.5)">
                                                    <rect id="Rectangle_16983" data-name="Rectangle 16983" width="1" height="12" transform="translate(1132.5 819.5) rotate(90)" fill="#9d9da6"/>
                                                </g>
                                            </svg>
                                        </button>
                                        <input type="number" value="{{ $detailedProduct->min_qty }}" name="quantity" min="{{ $detailedProduct->min_qty ?? 1 }}" placeholder="1" max="10" lang="en" class="fs-16 text-dark fw-600 text-center w-40px h-100 mt-1 border-0 mx-2 input-number">
                                        <!--Incrrement-->
                                        <button type="button" data-type="plus" data-field="quantity"
                                            class="dec-btn bg-transparent mr-2 border-0 w-30px h-35px rounded-circle d-flex align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                <g id="Group_38868" data-name="Group 38868" transform="translate(-1124.5 -814)">
                                                    <rect id="Rectangle_16982" data-name="Rectangle 16982" width="1" height="12" transform="translate(1130 814)" fill="#9d9da6"/>
                                                    <rect id="Rectangle_16983" data-name="Rectangle 16983" width="1" height="12" transform="translate(1136.5 819.5) rotate(90)" fill="#9d9da6"/>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if (get_setting('whatsapp_order') == 1)
                                @php
                                    $storeName = env('APP_NAME');
                                    $productTitle = $detailedProduct->getTranslation('name');
                                    $productUrl = URL::to('/product') . '/' . $detailedProduct->slug;
                                    $template = get_setting('order_messege_template');
                                    $message = str_replace(
                                        ['[[storeName]]', '[[productTitle]]', '[[productUrl]]'],
                                        [$storeName, $productTitle, $productUrl],
                                        $template
                                    );
                                    $whatsappNumber = preg_replace('/[^0-9]/', '', env('WHATSAPP_NUMBER'));
                                    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
                                @endphp
                                @if (($detailedProduct->added_by == 'seller' && get_setting('whatsapp_order_seller_prods') == 1) || ($detailedProduct->added_by == 'admin'))
                                    <div class="order-via-whatsapp mt-2 text-right">
                                        <a href="{{ $whatsappUrl }}" target="_blank" class="d-inline-flex align-items-center animate-underline-green has-transition">
                                            <i class="lab la-whatsapp fs-20"></i>
                                            <span class="fs-14 fw-400 pl-1">{{ translate('Order Via WhatsApp') }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($detailedProduct->variant_product)
                        <div class="selected-variations border-top-dashed pt-10px pb-20px">
                            <p class="mb-2 fs-14 fw-semibold text-dark d-flex align-items-center">
                                <span class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path id="_48116925ae3a82eb626852f6dfabe743" data-name="48116925ae3a82eb626852f6dfabe743" d="M56,48a8,8,0,1,0,8,8A8,8,0,0,0,56,48ZM54.765,58.835a.53.53,0,0,1-.338.169.555.555,0,0,1-.342-.173l-2.154-2.154.685-.685,1.815,1.815,4.8-4.835.673.7Z" transform="translate(-48 -48)" fill="#007bff"/>
                                    </svg>
                                </span>
                                <span class="d-block mt-1 ml-2">{{translate('Selected Product Summery')}}</span>
                            </p>
                            <span class="fs-14 fw-400 text-gray">{{ strlen($detailedProduct->getTranslation('name')) > 120
                                        ? substr($detailedProduct->getTranslation('name'), 0, 120).'...'
                                        : $detailedProduct->getTranslation('name')
                                    }} - <span id="selected_variant"></span></span>
                        </div>
                    @endif
                    <!--Buttons Start-->
                    <div class="d-flex flex-wrap flex-md-nowrap align-items-center">
                        <button type="button" @if (Auth::check() || get_Setting('guest_checkout_activation')==1) onclick="buyNow()" @else onclick="showLoginModal()" @endif
                            class="text-white border-0 rounded-1 fs-14 fw-bold bg-black hov-opacity-70 has-transition py-20px px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 buy-now">{{ translate('Buy Now') }}
                        </button>
                        <button type="button" id="added_to_cart_btn" @if (Auth::check() || get_Setting('guest_checkout_activation')==1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
                            class="text-blue border-0 rounded-1 fs-14 fw-bold bg-soft-blue hov-bg-blue hov-text-white py-20px px-20px w-100 add-to-cart">{{translate('Add to Cart')}} <span id="add_to_cart_count">(01)</span>
                        </button>
                        
                    </div>
                    <div class="">
                        <button type="button" class="out-of-stock fw-600 d-none text-white bg-light bg-soft-white border-0 rounded-1 fs-14 fw-bold hov-opacity-70 has-transition py-20px px-20px w-100" disabled>
                                <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                        </button>
                    </div>

                    <!--Buttons End-->
                </div>
            @endif

            <!--Shipping Notes Start-->
            @if ($detailedProduct->est_shipping_days && $detailedProduct->show_estimated_shipping_time ==1)
                <div class="d-flex flex-wrap align-items-center mt-3">
                    <p class="my-0 ml-0 mr-4 mr-md-4 mr-lg-5 fs-14 fw-400 text-black">{{ translate('Estimate Shipping Time') }} <span class="fw-bold ml-2">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span> @if($detailedProduct->show_shipping_note==1) <a href="javascript:void(1);" data-toggle="modal" data-target="#shipping-note-modal"><i data-toggle="tooltip" data-placement="top" title="{{translate('View Notes')}}" class="las la-info-circle fs-14 text-gray hov-text-dark has-transition"></i></a>@endif</p>
                </div>
            @endif
            <div class="py-20px d-flex flex-wrap flex-xl-nowrap align-items-center">
                <div class="d-flex align-items-center bg-light p-20px rounded-2 w-100 mb-2 mb-xl-0 mr-0 mr-xl-2">
                    <div class="store-logo-container w-48px h-48px bg-white border bordre-2 border-gray rounded-1 overflow-hidden mr-3 d-flex flex-shrink-0 align-items-center justify-content-center">
                        <img src="{{ uploaded_asset($seller_logo) }}" alt="seller">
                    </div>
                    <div>
                        @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                            <p class="m-0 fs-14">
                                <span class="fw-400 text-gray">{{ translate('Sold by') }}</span>
                                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="fw-bold text-blue pl-15px">{{ $detailedProduct->user->shop->name }}</a>
                            </p>
                        @else
                            <p class="m-0 fs-14">
                                <span class="fw-bold text-dark">{{ translate('Inhouse product') }}</span>
                            </p>
                        @endif
                        @if (get_setting('conversation_system') == 1)
                            <a href="javascript:void();" onclick="show_chat_modal()" class="fs-14 fw-400 text-blue animate-underline-blue has-transition">{{ translate('Message Seller') }}</a>
                        @endif
                    </div>
                </div>
                @if ($detailedProduct->brand != null)
                    <div class="d-flex align-items-center bg-light p-20px rounded-2 w-100">
                        <div class="store-logo-container w-48px h-48px bg-white border bordre-2 border-gray rounded-1 overflow-hidden mr-3 d-flex flex-shrink-0 align-items-center justify-content-center">
                            <img src="{{ uploaded_asset($detailedProduct->brand->logo) }}"alt="{{ $detailedProduct->brand->name }}" class="img-fit">
                        </div>
                        <div>
                            <p class="m-0 fs-14">
                                <span class="fw-400 text-gray">{{ translate('Brand') }}</span>
                                <span class="fw-bold text-dark pl-5px">{{ $detailedProduct->brand->name }}</span>
                            </p>
                            <a href="{{route('products.brand', $detailedProduct->brand->slug)}}" class="fs-14 fw-400 text-blue animate-underline-blue has-transition">{{ translate('Products from this brand') }}</a>
                        </div>
                    </div>
                @endif
            </div>
            <!--Shipping Notes End-->

            <!--Warranty Start-->
            <div class="warranty-section pb-20px">
                @if ($detailedProduct->has_warranty == 1 && $detailedProduct->warranty_id != null)
                    <p class="fs-14 fw-bold text-dark mb-2">{{ translate('Warranty') }}</p>
                    <div class="d-flex pb-20px border-bottom-dashed mb-2">
                        <div class="mr-4">
                            <p class="fs-14 fw-400 text-dark m-0">{{ translate('This Product has') }} <span class="fw-bold"> {{ $detailedProduct->warranty->getTranslation('text')}}</span> {{ translate('warranty') }}</p>
                            @if($detailedProduct->warranty_note_id != null &&  $detailedProduct->show_warranty_note == 1)
                                <a href="javascript:void(1);" data-toggle="modal" data-target="#warranty-note-modal" class="fs-14 fw-400 text-blue m-0 animate-underline-blue has-transitio">{{ translate('View Details') }}</a>
                            @endif
                        </div>
                        <div class="w-40px h-40px position-relative flex-shrink-0 overflow-hidden">
                            <img src="{{ uploaded_asset($detailedProduct->warranty->logo) }}" class="img-fluid position-absolute w-100 h-100" alt="Warranty Circle">
                        </div>
                    </div>
                @endif
                <ul class="m-0 p-0">
                    @if ($detailedProduct->cash_on_delivery)
                        <li class="d-flex align-items-center">
                            <span class="d-block flex-shrink-0">
                                <img src="{{ static_asset('/assets/img/warranty-check-circle.svg') }}" alt="warranty check circle">
                            </span>
                        <span class="d-block pl-10px pr-25px fs-14 fw-400 text-dark warranty-text">{{ translate('Cash on Delivery Available')}} @if($detailedProduct->show_delivery_notes==1 && $detailedProduct->delivery_note_id ) <a href="javascript:void(1);" data-toggle="modal" data-target="#cod-note-modal"><i data-toggle="tooltip" data-placement="top" title="{{translate('View Notes')}}" class="las la-info-circle fs-14 text-gray hov-text-dark has-transition"></i></a>@endif</span>
                        </li>
                    @endif
                    @if ($detailedProduct->shipping_type == 'free')
                        <li class="d-flex align-items-center">
                            <span class="d-block flex-shrink-0">
                                <img src="{{ static_asset('/assets/img/warranty-check-circle.svg') }}" alt="warranty check circle">
                            </span>
                            <span class="d-block pl-10px pr-25px fs-14 fw-400 text-dark warranty-text">{{ translate('Free Shipping') }}</span>
                        </li>
                    @endif
                    @if (addon_is_activated('refund_request') && $detailedProduct->refundable == 1)
                        <li class="d-flex align-items-center">
                            <span class="d-block flex-shrink-0">
                                <img src="{{ static_asset('/assets/img/warranty-check-circle.svg') }}" alt="warranty check circle">
                            </span>
                            <span class="d-block pl-10px pr-25px fs-14 fw-400 text-dark warranty-text">{{ translate('Refund Available for this producte') }} </span>
                        </li>
                    @endif
                </ul>
            </div>
            <!--Warranty End-->

            <!--Active Gurantee Card Start-->
            @php
                $refund_sticker = get_setting('refund_sticker');
            @endphp
            @if (addon_is_activated('refund_request') && $detailedProduct->refundable == 1)
                <div class="bg-light py-15px px-20px d-flex flex-wrap align-items-center justify-content-between rounded-2">
                    <a href="javascript:void(1);" data-toggle="modal" data-target="#refund-note-modal">
                        <div class="d-flex align-items-center">
                            <div class="h-40px rounded-1 d-flex align-items-center justify-content-center overflow-hidden">
                                @if ($refund_sticker != null)
                                    <img src="{{ uploaded_asset($refund_sticker) }}" alt="cashback-gurantee-logo" class="img-fluid w-100 h-100">
                                @else
                                    <img src="{{ static_asset('/assets/img/cashback-gurantee-logo.svg') }}" alt="cashback-gurantee-logo" class="img-fluid w-100 h-100">
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="d-flex align-items-center flex-shrink-0">
                        @if($detailedProduct->show_refund_notes == 1)
                        <a href="javascript:void(1);" data-toggle="modal" data-target="#refund-note-modal" class="fs-14 fw-400 text-blue animate-underline-blue has-transition flex-shrink-0">{{translate('View Notes')}}</a>
                        <span class="fs-14 text-gray mx-2">|</span>
                        @endif
                        <a href="{{ route('returnpolicy') }}" target="_blank" class="fs-14 fw-400 text-blue animate-underline-blue has-transition flex-shrink-0">{{ translate('View Policy')}}</a>
                    </div>
                </div>
            @endif
            <!--Active Gurantee Card End-->
        </form>

        @if (((get_setting('product_external_link_for_seller') == 1) && ($detailedProduct->added_by == "seller") && ($detailedProduct->external_link != null)) || (($detailedProduct->added_by != "seller") && ($detailedProduct->external_link != null)))
            <!-- Link Share-->
            <div class="row no-gutters mb-3">
                <div class="col-12 col-sm-12 mt-3">
                    <div class="product-quantity d-flex align-items-center">
                        <a type="button" target="_blank" class="text-white text-center border-0 rounded-1 fs-14 fw-bold bg-black hov-opacity-70 has-transition py-20px px-20px d-block w-100 mb-2 mb-md-0 mr-0 mr-md-2" href="{{ $detailedProduct->external_link }}">
                            <i class="la la-share"></i>{{ translate($detailedProduct->external_link_btn) }}
                        </a>
                    </div>
                </div>
            </div>
            <!-- Link Share End -->
        @endif

          <!-- Promote Link -->
        @if (Auth::check() &&
                addon_is_activated('affiliate_system') &&
                get_affliate_option_status() &&
                Auth::user()->affiliate_user != null &&
                Auth::user()->affiliate_user->status)
            @php
                if (Auth::check()) {
                    if (Auth::user()->referral_code == null) {
                        Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                        Auth::user()->save();
                    }
                    $referral_code = Auth::user()->referral_code;
                    $referral_code_url = URL::to('/product') . '/' . $detailedProduct->slug . "?product_referral_code=$referral_code";
                }
            @endphp
            <div class="row no-gutters mb-3">
                <div class="col-12 col-sm-12 mt-3">
                    <div class="product-quantity d-flex align-items-center">
                        <button type="button" id="ref-cpurl-btn"
                            data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                            data-url="{{ $referral_code_url }}" class="text-white text-center border-0 rounded-1 fs-14 fw-bold btn btn-secondary has-transition d-block w-100 mb-2 mb-md-0 mr-0 mr-md-2">
                            {{ translate('Copy the Promote Link') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>