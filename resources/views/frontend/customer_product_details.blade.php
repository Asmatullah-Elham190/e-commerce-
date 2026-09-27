@extends('frontend.layouts.app')
@section('meta')
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
<meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
<meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

<!-- Twitter Card data -->
<meta name="twitter:card" content="product">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
<meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
<meta name="twitter:creator"
    content="@author_handle">
<meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
<meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
<meta name="twitter:label1" content="Price">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
<meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
<meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
<meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
<meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
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
                        <ul class="breadcrumb bg-transparent pt-0 px-0 pb-10px  d-flex align-items-center">
                            <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                            </li>
                            <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                            <li class="fs-12 fw-400 has-transition opacity-50 hov-opacity-100">
                                {{translate($detailedProduct->category->name ?? '')}}
                            </li>
                            <i class="las la-angle-right fs-12 fw-600 text-gray hide_cat1"></i>
                            <li class="fs-12 fw-400 has-transition  text-reset text-truncate-1">
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
                <div class="col-sm-12 col-lg-6">
                    <div class="d-flex align-items-center justify-content-end pb-20px">

                        <div>
                            <button type="button" data-toggle="modal" data-target="#social-share-modal"
                                class="p-0 d-flex align-item bg-transparent fs-12 fw-400 text-gray border-0 hov-text-dark has-transition">
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

                    <P class="fs-16 fw-bold text-dark">{{ $detailedProduct->getTranslation('name') }}
                    </P>

                     <!--Brand Name and Ask about Start-->
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <!--LEFT-->
                        @if ($detailedProduct->brand != null)
                        <div class="d-flex align-items-center">
                            <span class="fs-14 fw-400 text-dark pr-2">{{ translate('Brand') }}</span>
                            <span class="fs-14 fw-400 text-blue"><a href="{{route('products.brand', $detailedProduct->brand->slug)}}">{{ $detailedProduct->brand->name }}</a></span>
                        </div>
                        @endif
                        <!--RIGHT-->
                        
                    </div>

                    <!--Watching Product Start-->
                    <div class="d-flex flex-wrap align-items-center py-10px">
                        <div class="d-flex align-items-center" id="live-product-viewing-visitors">
                            <span
                                class="people-view pulse position-relative d-inline-flex align-items-center justify-content-center w-15px h-15px">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#e3e3e3"><path d="M480.28-96Q401-96 331-126t-122.5-82.5Q156-261 126-330.96t-30-149.5Q96-560 126-629.5q30-69.5 82.5-122T330.96-834q69.96-30 149.5-30t149.04 30q69.5 30 122 82.5T834-629.28q30 69.73 30 149Q864-401 834-331t-82.5 122.5Q699-156 629.28-126q-69.73 30-149 30Zm-.28-72q130 0 221-91t91-221q0-130-91-221t-221-91q-130 0-221 91t-91 221q0 130 91 221t221 91Zm0-72q-100 0-170-70t-70-170q0-100 70-170t170-70q100 0 170 70t70 170q0 100-70 170t-170 70Zm0-72q70 0 119-49t49-119q0-70-49-119t-119-49q-70 0-119 49t-49 119q0 70 49 119t119 49Zm-.21-96Q450-408 429-429.21t-21-51Q408-510 429.21-531t51-21Q510-552 531-530.79t21 51Q552-450 530.79-429t-51 21Z"/></svg>
                                <span class="pulse-layer"></span>
                            </span>
                            <span class="fs-14 fw-bold text-dark mx-2  count"></span>
                            <span class="fs-14 fw-400 text-dark">{{ translate('People are viewing right now')}}</span>
                        </div>

                    </div>
                    <!--Watching Product End-->
                    <!-- In Stock Start -->
                    <div class="border  border-light bg-light rounded-2 mt-3 d-flex align-items-center justify-content-between px-20px py-3">
                        <!-- Price -->
                        <h6 class="m-0 fs-18 fw-700 text-dark">
                            {{ single_price($detailedProduct->unit_price) }}
                            @if ($detailedProduct->unit != null)
                            <span class="opacity-70 fs-14">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                        </h6>

                        <!-- Download Button -->
                        @if ($detailedProduct->pdf != null)
                        <a href="{{ uploaded_asset($detailedProduct->pdf) }}" target="_blank" class="fs-14 fw-400 text-blue cursor-pointer"> <i class="las la-download mr-1"></i>
                            {{ translate('Download product specifation') }}
                        </a>
                        @endif

                    </div>
                    <!-- In Stock End -->
                    
                    


                    <div class="py-10px px-20px bg-white border border-soft-light rounded-2 mt-4">
                        <ul class="list-group bg-transparent">
                             <li class="list-group-item pb-3 border-0 px-0 border-bottom-dashed bg-transparent">
                                <p class="mb-1 fs-14 fw-600 text-dark">Product Condition</p>
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                    @if ($detailedProduct->conditon == 'new')
                                    <span class="fs-14 fw-400 text-white bg-blue px-3 px-md-4 py-1 rounded-pill">{{translate('New')}}</span>
                                    @else
                                    <span class="fs-14 fw-400 text-white bg-danger px-3 px-md-4 py-1 rounded-pill">{{translate('Used')}}</span>
                                    @endif
                                </div>
                             </li>
                              <li class="list-group-item pb-3 border-0 px-0 border-bottom-dashed bg-transparent">
                                <p class="mb-1 fs-14 fw-600 text-dark">{{ translate('Sold by') }}</p>
                                <span class="fs-14 fw-400 text-dark">{{ $detailedProduct->user->name }}</span>
                             </li>
                               <li class="list-group-item pb-3 border-0 px-0 border-bottom-dashed bg-transparent">
                                <p class="mb-1 fs-14 fw-600 text-dark">{{translate('Location')}}</p>
                                <span class="fs-14 fw-400 text-dark"> {{ $detailedProduct->location }}</span>
                             </li>
                                 <li class="list-group-item pb-3 border-0 px-0 bg-transparent">
                                <p class="mb-1 fs-14 fw-600 text-dark">{{translate('Tags')}}</p>
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                    @if(!empty($detailedProduct->tags))
                                        @foreach(explode(',', $detailedProduct->tags) as $tag)
                                            <span
                                                class="fs-14 fw-400 text-dark border border-soft-light bg-light hov-bg-soft-light has-transition cursor-pointer rounded-pill px-3 py-1">
                                                {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                             </li>
                        </ul>  
                    </div> 
                    <div class="mt-4">
                        <button type="button"
                            class="d-block fs-12 fs-md-14 fw-600 w-100 py-3 py-md-4 px-3 text-center bg-dark text-white border-0 rounded-2 hov-opacity-80 has-transition show-contact-btn"
                            onclick="show_number(this)">

                            <i class="las la-user border border-white rounded-1 fs-16"></i>
                            <span class="pl-1 contact-text">
                                <span class="dummy">
                                    {{ str_replace(substr($detailedProduct->user->phone, 3), 'XXXXXXXX', $detailedProduct->user->phone) }}
                                </span>
                                <span class="real d-none">
                                    @if(!empty($detailedProduct->user->phone))
                                        {{ $detailedProduct->user->phone }}
                                    @elseif(!empty($detailedProduct->user->email))
                                        {{ $detailedProduct->user->email }}
                                    @else
                                        {{ translate('Contact not available') }}
                                    @endif
                                </span>
                            </span>
                        </button>
                    </div>

                    <!-- Customer Info End -->

                </div>
            </div>
        </div>
    </div>
    <!--PRODUCT DETAILS TOP SECTION END-->


    <!-- ======== PRODUCT DETAILS NAV TAB START ======== -->
    <div class="product-details-nav-tab mb-5">
        <div class="nav-tab-header bg-white">
            <div class="container mb-32px">
                <div class="tab-scroll-wrapper">
                    <ul id="tabLinks" class="m-0 p-0 d-flex position-relative" type="none">
                        <li class="mr-2rem"><a href="#description"
                                class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Description')}}</a>
                        </li>
                        <li class="mr-2rem"><a href="#relatedProduct"
                                class="nav-link d-inline-block px-0 pt-20px pb-20px fs-16 fw-700 text-gray hov-text-dark has-transition">{{translate('Other Classified
                                Products')}}</a>
                        </li>
                        <span class="tab-underline"></span>
                    </ul>
                </div>
            </div>
        </div>


        <div class="container d-flex flex-column">
            <!--DESCRIPTION SECTION START-->
            <section id="description">
                <div class="py-30px px-30px border  bg-white border-light-gray rounded-2">
                    <?php echo $detailedProduct->getTranslation('description'); ?>
                </div>
            </section>
            <!--DESCRIPTION SECTION END-->

            <!--RELATED PRODUCTS SECTION START-->

            @php
            $products = get_similiar_classified_products($detailedProduct->category_id, $detailedProduct->id, 10);
            @endphp
            <section class="mb-4" id="relatedProduct">
                <div class="py-30px px-30px border  bg-white border-light-gray rounded-2">
                    <div class="d-flex mb-3 align-items-baseline border-bottom pb-3">
                        <h3 class="fs-16 fw-600 mb-0">
                            {{ translate('Other Ads of') }} {{ $detailedProduct->category->getTranslation('name') }}
                        </h3>
                        @if ($products->count() > 0)
                        <a class="ml-auto mr-0 text-blue fs-12 fw-700 hov-text-primary" href="{{ route('customer_products.category', $detailedProduct->category->slug) }}">{{ translate('View More') }}</a>
                        @endif
                    </div>
                    <div class="p-3">
                        <div class="aiz-carousel gutters-16 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                            @php
                            $products = get_similiar_classified_products($detailedProduct->category_id, $detailedProduct->id, 10);
                            @endphp
                            @forelse ($products as $key => $product)
                            <div class="carousel-box overflow-hidden has-transition hov-shadow-out z-1 border-right border-top border-bottom @if ($key == 0) border-left @endif">
                                <div class="aiz-card-box my-3">
                                    <div class="position-relative">
                                        <a href="{{ route('customer.product', $product->slug) }}" class="d-block">
                                            <img class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                alt="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </a>
                                        <div class="absolute-top-left">
                                            @if ($product->conditon == 'new')
                                            <span class="badge badge-inline badge-info fs-13 fw-700 p-3 text-white" style="border-radius: 20px;">{{ translate('New') }}</span>
                                            @elseif($product->conditon == 'used')
                                            <span class="badge badge-inline badge-secondary-base fs-13 fw-700 p-3 text-white" style="border-radius: 20px;">{{ translate('Used') }}</span> @endif
                                        </div>
                                    </div>
                                    <div class="p-md-3 p-2 text-center">
                                        <h3 class="fw-400 fs-14 text-truncate-2 lh-1-4 mb-0 h-35px">
                                            <a href="{{ route('customer.product', $product->slug) }}"
                                                class="d-block text-reset hov-text-primary">{{ $product->getTranslation('name') }}</a>
                                        </h3>
                                        <div class="fs-15 mt-2">
                                            <span class="fw-700 text-primary">{{ single_price($product->unit_price) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center w-100">
                                <h5 class="fs-16 fw-bold text-dark">{{ translate('No related products found!') }}</h5>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#e3e3e3"><path d="M626-533q22.5 0 38.25-15.75T680-587q0-22.5-15.75-38.25T626-641q-22.5 0-38.25 15.75T572-587q0 22.5 15.75 38.25T626-533Zm-292 0q22.5 0 38.25-15.75T388-587q0-22.5-15.75-38.25T334-641q-22.5 0-38.25 15.75T280-587q0 22.5 15.75 38.25T334-533Zm146.17 116Q413-417 358.5-379.5T278-280h53q22-42 62.17-65 40.18-23 87.5-23 47.33 0 86.83 23.5T630-280h52q-25-63-79.83-100-54.82-37-122-37ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 340q142.38 0 241.19-98.81Q820-337.63 820-480q0-142.38-98.81-241.19T480-820q-142.37 0-241.19 98.81Q140-622.38 140-480q0 142.37 98.81 241.19Q337.63-140 480-140Z"/></svg>
                                </span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
            <!--RELATED PRODUCTS SECTION END-->
        </div>
    </div>
    <!-- ======== PRODUCT DETAILS NAV TAB END ======== -->
</div>
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
                        <span class="fs-14 text-gray fw-400 flex-grow-1 text-truncate-1 has-transition">{{route('customer.product', $detailedProduct->slug)}}</span>
                        <button type="button" id="link-cpurl-btn" data-attrcpy="{{ translate('Copied') }}" data-url="{{route('customer.product', $detailedProduct->slug)}}" onclick="linkCopyToClipboard(this)" class="border-0 bg-transparent flex-shrink-0 copy-link-btn">
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
@endsection




@section('script')



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


<script type="text/javascript">
    function CopyToClipboard(e) {
        var url = $(e).data('url');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(url).select();
        try {
            document.execCommand("copy");
            AIZ.plugins.notify('success', '{{ translate('
                Link copied to clipboard ') }}');
        } catch (err) {
            AIZ.plugins.notify('danger', '{{ translate('
                Oops, unable to copy ') }}');
        }
        $temp.remove();
    }

    function linkCopyToClipboard(e) {
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

    function showImage(photo) {
        $('#image_modal img').attr('src', photo);
        $('#image_modal img').attr('data-src', photo);
        $('#image_modal').modal('show');
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

    function show_number(el) {
        $(el).find('.dummy').addClass('d-none');
        $(el).find('.real').removeClass('d-none').addClass('d-block');
    }
</script>
@if(get_setting('show_custom_product_visitors')==1)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateViewerCount();
    });
</script>
@endif
<!-- ======================== Custom Viewer End  ================== -->
@endsection