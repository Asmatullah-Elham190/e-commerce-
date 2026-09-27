@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }},{{ $detailedProduct->meta_keywords }}@stop

@section('meta')
    @php
        $availability = "out of stock";
        $qty = 0;
        if($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if($qty > 0){
            $availability = "in stock";
        }
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency"
        content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection


@section('content')
    <div class="product-details">
        <!--PRODUCT DETAILS TOP SECTION START-->
        <div class="container">
            <div class="pt-30px pb-6">
                <div class="row">
                    <!--LEFT SIDE SLIDER-->
                    <div class="col-sm-12 col-lg-6">
                        <div class="product-slider-wrapper mb-2rem mb-lg-0">
                            <!--BREADCRUMB-->
                            <ul class="breadcrumb bg-transparent pt-0 px-0 pb-10px d-flex align-items-center">
                                <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                                </li>
                                <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                                <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                    <a class="text-reset" href="{{route('products.category', $detailedProduct->main_category->slug)}}">{{translate($detailedProduct->main_category->name ?? '')}}</a>
                                </li>
                                <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                                <li class="fs-12 fw-400 has-transition  text-reset">
                                    {{ strlen($detailedProduct->getTranslation('name')) > 50
                                        ? substr($detailedProduct->getTranslation('name'), 0, 50).'...'
                                        : $detailedProduct->getTranslation('name')
                                    }}
                                </li>
                            </ul>
                            @include('frontend.product_details.image_gallery')
                        </div>
                    </div>
                    <!--RIGHT SIDE-->
                     @include('frontend.product_details.details')
                </div>
            </div>
        </div>
        <!--PRODUCT DETAILS TOP SECTION END-->


        <!-- ======== PRODUCT DETAILS NAV TAB START ======== -->
        <div id="smart-bar-trigger"></div>
        <div class="product-details-nav-tab mb-5">
            <div class="nav-tab-header bg-white">
                <div class="container mb-32px">
                    <div class="tab-scroll-wrapper">
                        <ul id="tabLinks" class="m-0 p-0 d-flex position-relative" type="none">
                            @if(get_setting('enable_product_description_section') == 1)
                                <li class="mr-2rem"><a href="#description"
                                        class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Description')}}</a>
                                </li>
                            @endif
                            @if($detailedProduct->auction_product != 1 && get_setting('enable_product_related_section') == 1)
                                <li class="mr-2rem"><a href="#relatedProduct"
                                        class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Related Products')}}</a></li>
                            @endif
                            @if(get_setting('enable_ratings_and_review_section') == 1)
                                <li class="mr-2rem"><a href="#reviewsRatings"
                                        class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Reviews & Ratings')}}</a></li>
                            @endif
                            @if(get_setting('product_query_activation') == 1 && get_setting('enable_product_queries_section') == 1)
                                <li class="mr-2rem"><a href="#product_query"
                                        class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Product Queries')}} ({{ count($detailedProduct->product_queries) }})</a></li>
                            @endif
                            @if($detailedProduct->auction_product != 1)
                                @if(get_setting('enable_frequently_bought_section') == 1)
                                    <li class="mr-2rem"><a href="#frequentlyBought"
                                            class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Frequently Bought')}}</a></li>
                                @endif
                                @if(get_setting('enable_more_from_this_seller_section') == 1)
                                    <li class="mr-2rem"><a href="#fromThisSeller"
                                            class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('More from this Seller')}}</a></li>
                                @endif
                            @endif
                            <span class="tab-underline"></span>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="container d-flex flex-column">
                @if(get_setting('enable_product_description_section') == 1)
                    <!--DESCRIPTION SECTION START-->
                    <section id="description">
                        <div class="py-30px px-30px border  bg-white border-light-gray rounded-2">
                            <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                <?php echo $detailedProduct->getTranslation('description'); ?>
                            </div>
                        </div>
                    </section>
                @endif
                <!--DESCRIPTION SECTION END-->

                @if($detailedProduct->auction_product != 1 && get_setting('enable_product_related_section') == 1)
                    <!--RELATED PRODUCTS SECTION START-->
                    <section id="relatedProduct">
                        @include('frontend.product_details.related_products')
                    </section>
                    <!--RELATED PRODUCTS SECTION END-->
                @endif

                @if(get_setting('enable_ratings_and_review_section') == 1)
                    <!--REVIEWS & RATINGS SECTION START-->
                    @include('frontend.product_details.review_section')
                    <!--REVIEWS & RATINGS SECTION END-->
                @endif

                @if(get_setting('product_query_activation') == 1 && get_setting('enable_product_queries_section') == 1)
                    <!--PRODUCT QUERIES START-->
                    <section id="product_query">
                    @include('frontend.product_details.product_queries')
                    </section>
                    <!--PRODUCT QUERIES END-->
                @endif


                @if($detailedProduct->auction_product != 1)
                    @if(get_setting('enable_frequently_bought_section') == 1)
                        <!--FREQUENT BOUGTH TOGETHER START-->
                        <section id="frequentlyBought">
                            @include('frontend.product_details.frequently_bought_together')
                        </section>
                        <!--FREQUENT BOUGTH TOGETHER END-->
                    @endif
                    @if(get_setting('enable_more_from_this_seller_section') == 1)
                        <!--FROM THIS SELLER START-->
                        <section id="fromThisSeller">
                            @include('frontend.product_details.from_this_seller_products')
                        </section>
                        <!--FROM THIS SELLER END-->
                    @endif
                @endif
            </div>
        </div>
        <!-- ======== PRODUCT DETAILS NAV TAB END ======== -->
    </div>
    @if ($detailedProduct->auction_product != 1)
    @include('frontend.smart_bar')
    @endif
@endsection

@section('modal')

    @include('frontend.partials.image_viewer')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-1" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-1" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-1"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-1 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if($detailedProduct->auction_product == 1)
        @php 
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
            $gst_rate = gst_applicable_product_rate($detailedProduct->id);
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ').$min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{translate('Place Bid Price')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                    @if($gst_rate != null)
                                        <small class="text-danger">{{ translate('An') }} {{ $gst_rate }}% {{ translate('GST will be applied if you win the bid and proceed with the purchase') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Warranty Modal -->
    <div class="modal fade" id="warranty-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Warranty Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->warranty_note_id != null)
                        <p>{{ $detailedProduct->warrantyNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Refund Modal -->
    <div class="modal fade" id="refund-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Refund Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->refund_note_id != null)
                        <p>{{ $detailedProduct->refundNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Shipping Modal -->
    <div class="modal fade" id="shipping-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Shipping Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->shipping_note_id != null)
                        <p>{{ $detailedProduct->shippingNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product COD Modal -->
    <div class="modal fade" id="cod-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delivery Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->delivery_note_id != null)
                        <p>{{ $detailedProduct->codNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

   <!-- Product Share Modal -->
    <div class="modal fade" id="social-share-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header flex-column pb-0 border-0">
                    <div class="w-80px h-80px rounded-circle bg-light border border-white border-width-3 link-circle-box d-flex align-items-center justify-content-center"> 
                        <i class="las la-link fs-28"></i>
                    </div>
                    <button type="button" class="close fs-10 w-25px h-25px rounded-circle bg-white hov-bg-soft-light has-transition d-flex align-items-center justify-content-center mr-1 mb-1" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    <div class="col-12 col-lg-10 col-xl-8 mx-auto text-center">
                        <h4 class="fs-20 fw-700 text-dark">{{translate('Share with Friends')}}</h4>
                        <span class="fs-14 text-gray fw-400">{{translate('Trading is more effective when you share products with friends!')}}</span>
                    </div>
                    <div class="my-4 my-lg-5">
                        <h5 class="fs-16 fw-600 text-dark">{{translate('Share you link')}}</h5>
                        <div class="py-3 px-3 bg-light rounded-2 border-0 d-flex align-items-center justify-content-between share-link">
                            <span class="fs-14 text-gray fw-400 flex-grow-1 text-truncate-1 has-transition">{{route('product', $detailedProduct->slug)}}</span>
                            <button type="button" id="link-cpurl-btn" data-attrcpy="{{ translate('Copied') }}" data-url="{{route('product', $detailedProduct->slug)}}" onclick="CopyToClipboard(this)" class="border-0 bg-transparent flex-shrink-0 copy-link-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                                    <path id="Path_45213" data-name="Path 45213" d="M124.8-867.2a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-9.6a1.541,1.541,0,0,1,.47-1.13,1.541,1.541,0,0,1,1.13-.47H132a1.541,1.541,0,0,1,1.13.47,1.541,1.541,0,0,1,.47,1.13v9.6a1.541,1.541,0,0,1-.47,1.13,1.541,1.541,0,0,1-1.13.47Zm0-1.6H132v-9.6h-7.2Zm-3.2,4.8a1.541,1.541,0,0,1-1.13-.47,1.541,1.541,0,0,1-.47-1.13v-11.2h1.6v11.2h8.8v1.6Zm3.2-4.8v0Z" transform="translate(-120 880)" fill="#919199"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                     <div class="pb-3">
                        <h5 class="fs-16 fw-600 text-dark">{{translate('Share to')}}</h5>
                        <div class="aiz-share text-center"></div>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade uploadModal" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

                <!-- Header -->
                <div class="modal-header bg-dark text-white py-2 rounded-0">
                    <h6 class="modal-title mb-0">Image Preview</h6>
                    <button type="button" class="close text-white fs-3 border-0 bg-transparent" data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center bg-light p-3">
                    <img id="modalImage"
                        src=""
                        class="img-fluid rounded shadow-sm"
                        style="max-height: 80vh;">
                </div>

            </div>
        </div>
    </div>
@endsection




@section('script')
<script src="https://cdn.jsdelivr.net/npm/exif-js"></script>

   <!-- ======================== Message Seller Start ================== -->
    <script>

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }
    </script>
    
   <!-- ======================== Message Seller End ================== -->

   <!-- ======================== Product Variant Height Controll Start ================== -->
    <script type="text/javascript">
        $(document).on('click', '#toggleHeight', function () {

            var $btn = $(this);
            var $variant = $btn.closest('.product-variant');
            var $text = $btn.find('.toggle-text');
            var isCollapsed = $variant.hasClass('collapsed');

            // Toggle state
            $variant.toggleClass('collapsed');

            // Change text (HTML allowed)
            $text.html(
                isCollapsed
                    ? $btn.data('less') + ' <i class="las la-angle-up ms-1"></i>'
                    : $btn.data('more') + ' <i class="las la-angle-down ms-1"></i>'
            );

        });
    </script>

    <!-- ======================== Product Variant Height Controll End ================== -->

    <!-- ======================== Product Swipper Slide Start ================== -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            /*------ Thumbnails Swiper ------*/
            var thumbSwiper = new Swiper(".thumb-slider", {
                direction: "vertical",
                slidesPerView: 5,
                spaceBetween: 16,
                watchSlidesProgress: true,

                breakpoints: {
                    0: {
                        direction: "horizontal",
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        direction: "vertical",
                        slidesPerView: 5,
                    }
                }
            });

            /*------ Product Main Swiper Slide ------ */
            var mainSwiper = new Swiper(".main-slider", {
                spaceBetween: 10,
                thumbs: {
                    swiper: thumbSwiper
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                }
            });

            /*------ Manual Scroll Thumbnail Slider Buttons ------ */
           const thumbBtnUp = document.querySelector(".thumb-btn-up");
            const thumbBtnDown = document.querySelector(".thumb-btn-down");

            if (thumbBtnUp && thumbBtnDown) {

                thumbBtnUp.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    thumbSwiper.slidePrev();
                });

                thumbBtnDown.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    thumbSwiper.slideNext();
                });

                function updateThumbButtons(swiper) {
                    thumbBtnUp.classList.toggle("disabled", swiper.isBeginning);
                    thumbBtnDown.classList.toggle("disabled", swiper.isEnd);
                }

                updateThumbButtons(thumbSwiper);

                thumbSwiper.on("slideChange", function () {
                    updateThumbButtons(this);
                });

                thumbSwiper.on("reachBeginning", function () {
                    updateThumbButtons(this);
                });

                thumbSwiper.on("reachEnd", function () {
                    updateThumbButtons(this);
                });
            }

        });
    </script>
    <!-- ======================== Product Swipper Slide End ================== -->


    <!-- ======================== Flash Sale Timer Start ================== -->
    <script type="text/javascript">
        

        function startSimpleCountdown(endDate, countdownEl) {
            // Select flashBox once here so the update function can see it
            const flashBox = document.getElementById("flashSaleBox");

            function update() {
                const now = new Date();
                const diff = endDate - now;

                if (diff > 0) {
                    const totalSeconds = Math.floor(diff / 1000);
                    const days = Math.floor(totalSeconds / (60 * 60 * 24));
                    const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
                    const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
                    const secs = totalSeconds % 60;
                    countdownEl.innerHTML = `${days}D : ${hours}H : ${mins}M : ${secs}S`;
                } else {
                    countdownEl.innerHTML = "Expired";
                    if (flashBox) {
                        flashBox.classList.add("expired");
                        flashBox.style.animation = "none";
                    }
                    clearInterval(timer);
                }
            }

            update();
            const timer = setInterval(update, 1000);
        }

        document.addEventListener("DOMContentLoaded", function() {
            const countdownEl = document.querySelector('.flashSaleCountdown');
            
            if (countdownEl && countdownEl.dataset.endDate) {
                const endDateStr = countdownEl.dataset.endDate;
                const parsedEndDate = isNaN(endDateStr) 
                    ? new Date(endDateStr.replace(/-/g, '/')) 
                    : new Date(parseInt(endDateStr) * 1000);

                startSimpleCountdown(parsedEndDate, countdownEl);
            }
        });
                </script>
    <!-- ======================== Flash Sale Timer End ================== -->

    <!-- ======================== Product Details Nav Tab Start ================== -->
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll("#tabLinks .nav-link");
        const underline = document.querySelector(".tab-underline");
        const tabWrapper = document.querySelector(".tab-scroll-wrapper");
        const sections = document.querySelectorAll("section");

        function moveUnderline(activeLink) {
            const rect = activeLink.getBoundingClientRect();
            const parentRect = activeLink.parentElement.parentElement.getBoundingClientRect();

            underline.style.width = rect.width + "px";
            underline.style.left = (rect.left - parentRect.left) + "px";

            // Auto scroll to keep active tab in view
            tabWrapper.scrollTo({
                left: activeLink.offsetLeft - 50,
                behavior: "smooth"
            });
        }

        // Smooth scroll on click
        navLinks.forEach(function(link) {
            link.addEventListener("click", function(e) {
                e.preventDefault();

                const href = this.getAttribute("href");
                const target = document.querySelector(href);

                if (!target) return;

                const targetOffset =
                    target.getBoundingClientRect().top + window.pageYOffset - 150;

                window.scrollTo({
                    top: targetOffset,
                    behavior: "smooth"
                });

                moveUnderline(this);
            });
        });


        // Scrollspy
        window.addEventListener("scroll", () => {
            const scrollPos = window.scrollY + 120;

            sections.forEach(sec => {
                const top = sec.offsetTop;
                const bottom = top + sec.offsetHeight;

                // if current scroll inside this section
                if (scrollPos >= top && scrollPos < bottom) {
                    const activeLink = document.querySelector(`a[href="#${sec.id}"]`);
                    if (activeLink) moveUnderline(activeLink);
                }
            });
        });


        // initial underline
        window.addEventListener("load", () => {
            moveUnderline(navLinks[0]);
        });
     });
    </script>

    <!-- ======================== Product Details Nav Tab End ================== -->

    <!-- ======================== Filter Rating Selector Start ================== -->
    <script type="text/javascript">
       $(document).on('click', '.rating-point', function (e) {
            if ($(e.target).is('input')) {
                return;
            }
            $('.rating-point').removeClass('active');
            $(this).addClass('active');
            let val = $(this).find('input').val();
            $(this).find('input').prop('checked', true);
            getReviews(0, val);
        });
        
    </script>
    <!-- ======================== Filter Rating Selector End ================== -->

   <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
                //reset temp input value
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
        }


        function SKUCopyToClipboard() {
            // Directly get the text currently shown in the SKU span
            var skuText = $('#variant_sku').text().trim();

            if (skuText === "") {
                AIZ.plugins.notify('warning', 'No SKU to copy');
                return;
            }

            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(skuText).select();

            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('SKU copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            
            $temp.remove();
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }


        $(document).on('click', '.see-more-btn', function () {
            getReviews(1, null);
        });
        // Pagination end

        function reviewBySort() {
            getReviews();
        }

        function reviewByImages(){
            getReviews(null, null, 1);
        }


        function getReviews(seemore= false, rating = null, images= null) {
            let sortBy = $('#sortBy').val();
            let limit = parseInt($('.see-more-btn').attr('data-limit')) || 0;
            if (seemore) {
                limit += 3;
            }
            if(images!= null){
                rating =null;
                sortBy = images;
            }
            if (rating == null) {
                $('.rating-point').removeClass('active');
            }
            const loaderHtml = `
                <div class="h-500px w-100 d-flex justify-content-center align-items-center loader-wrapper">
                    <div class="footable-loader" style="height: 100vh !important;">
                        <span class="fooicon fooicon-loader"></span>
                    </div>
                </div>`;
            //alert(limit);
            $('.reviews-area').html(loaderHtml);
            $.ajax({
                url: `{{ route('products.reviews') }}`,
                method: 'GET',
                data: {
                    slug: '{{ $detailedProduct->slug }}',
                    limit: limit,
                    sort_by: sortBy,
                    rating: rating,
                    images: images
                },
                beforeSend: function () {
                    // Show loader
                    $('.reviews-area').html(loaderHtml);
                },
                success: function (res) {

                    // Remove loader
                    $('.reviews-area').empty();

                    if (res.html && res.html.trim() !== '') {
                        $('.reviews-area').append(res.html);
                        $('#see-more-btn').attr('data-limit', limit);
                    }

                    // Hide button if no more reviews
                    if (!res.has_more) {
                        $('#seeMoreReviews').hide();
                    }
                },
                error: function () {
                    $('.reviews-area').html('<p class="text-danger text-center">Failed to load reviews.</p>');
                }
            });
        }


    //    document.addEventListener('click', function(e) {
    //         const label = e.target.closest('.rating-point-select');
    //         if (!label) return;
    //         if (label.dataset.listenerAdded === 'true') return;
    //         label.dataset.listenerAdded = 'true';
    //         document.querySelectorAll('.rating-point-select')
    //             .forEach(l => l.classList.remove('active', 'border-primary'));
    //         label.classList.add('active', 'border-primary');
    //         const rating = label.dataset.value;
    //         getReviews(0, rating);
    //     });


        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
          	@elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers & Sellers can Bid.") }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function updateViewerCount() {
            const countElement = document.querySelector('#live-product-viewing-visitors .count');
            const min = parseInt(`{{ get_setting('min_custom_product_visitors') }}`);
            const max = parseInt(`{{ get_setting('max_custom_product_visitors') }}`);
            const randomNumber = getRandomNumber(min, max);
            countElement.textContent = randomNumber;
            const randomTime = getRandomNumber(5000, 10000);
            setTimeout(updateViewerCount, randomTime);
        }
        
    </script>
    @if(get_setting('show_custom_product_visitors')==1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateViewerCount();
            getReviews();
        });
    </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            getReviews();
        });
    </script>
    <!-- ======================== Custom Viewer End  ================== -->


    
    <!-- ========================Push view_item event when product page loads ================== -->
    @if (get_setting('google_analytics') == 1)

    <script>
        window.dataLayer = window.dataLayer || [];
        
        // View Item Event
        dataLayer.push({ ecommerce: null });
        dataLayer.push({
            event: "view_item",
            ecommerce: {
                currency: "{{ get_system_currency()->code }}",
                value: {{ home_discounted_price($detailedProduct) }},
                items: [{
                    item_id: "{{ $detailedProduct->id }}",
                    item_name: "{{ addslashes($detailedProduct->getTranslation('name')) }}",
                    @if($detailedProduct->brand)
                    item_brand: "{{ addslashes($detailedProduct->brand->name) }}",
                    @endif
                    @if($detailedProduct->main_category)
                    item_category: "{{ addslashes($detailedProduct->main_category->getTranslation('name')) }}",
                    @endif
                    price: home_discounted_price($detailedProduct),
                    quantity: 1
                }]
            }
        });
    </script>
    @endif
    <script>
        function openReviewOffcanvas(product_id, order_id) {
            @if (isCustomer())
                @if ($review_status == 1)
                    const rightOffcanvas = document.getElementById('rightOffcanvas');
                    const overlay = document.getElementById('rightOffcanvasOverlay');
                    
                    if (rightOffcanvas) rightOffcanvas.classList.add('active');
                    if (overlay) overlay.classList.add('active');
                    document.body.classList.add('body-no-scroll');
                    
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';
                    }
                    
                    $.ajax({
                        type: "POST",
                        url: "{{ route('product_review_modal') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: product_id,
                            order_id: order_id
                        },
                        success: function(html) {
                            if (rightOffcanvas) {
                                rightOffcanvas.innerHTML = html;

                                if (typeof AIZ !== 'undefined' && AIZ.extra && AIZ.extra.inputRating) {
                                    AIZ.extra.inputRating();
                                }

                                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.aizUploader) {
                                    AIZ.plugins.aizUploader();
                                }
                            }
                        },
                        error: function() {
                            if (rightOffcanvas) {
                                rightOffcanvas.innerHTML = '<div class="p-4 text-center text-danger">{{ translate("Failed to load review form") }}</div>';
                            }
                            AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                        }
                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate("Sorry, You need to buy this product to give review.") }}');
                @endif    
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers can give review.") }}');
            @else
                $('#login_modal').modal('show');
            @endif    
        }

        $(document).on('click', '#review-store', function(e) {
            e.preventDefault();
            
            const btn = $(this);
            const formData = new FormData();
            
            const product_id = $('#modal_product_id').val(); 
            const order_id = $('#modal_order_id').val();
            const rating = $('input[name="rating"]:checked').val();
            const comment = $('textarea[name="comment"]').val();

            if (!product_id) {
                AIZ.plugins.notify('danger', 'Product ID is missing!');
                return;
            }

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('product_id', product_id);
            formData.append('order_id', order_id);
            formData.append('rating', rating);
            formData.append('comment', comment);
            
            const input = document.querySelector('input[name="photos[]"]');
            if (input && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    formData.append('photos[]', input.files[i]);
                }
            }
            
            if (!$('input[name="rating"]:checked').val()) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a rating") }}');
                return;
            }
            if (!$('textarea[name="comment"]').val()) {
                AIZ.plugins.notify('warning', '{{ translate("Please write a comment") }}');
                return;
            }
            
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span> {{ translate("Submitting...") }}');
            
            // AJAX Submit
            $.ajax({
                url: "{{ route('reviews.store') }}", 
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    AIZ.plugins.notify('success', '{{ translate("Review submitted successfully") }}');
                    
                    if (typeof closeOffcanvas === 'function') {
                        closeOffcanvas();
                    } else {
                        const rightOffcanvas = document.getElementById('rightOffcanvas');
                        const overlay = document.getElementById('rightOffcanvasOverlay');
                        if (rightOffcanvas) rightOffcanvas.classList.remove('active');
                        if (overlay) overlay.classList.remove('active');
                        document.body.classList.remove('body-no-scroll');
                    }
                    
                    setTimeout(() => {
                        if (typeof getOrderData === 'function') {
                            getOrderData(currentTab);
                        } else {
                            location.reload();
                        }
                    }, 1000);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('{{ translate("Confirm") }}');
                    const errorMsg = xhr.responseJSON?.message || '{{ translate("Something went wrong") }}';
                    AIZ.plugins.notify('danger', errorMsg);
                }
            });
        });

        function openReviewImageModal(src) {
            document.getElementById('modalImage').src = src;
            var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
            myModal.show();
        }
    </script>
@endsection
