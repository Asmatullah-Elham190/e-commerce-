<!DOCTYPE html>

@php
    $rtl = get_session_language()->rtl;
@endphp

@if ($rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">

    @yield('meta')

    @if (!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
        @php
            $meta_image = uploaded_asset(get_setting('meta_image'));
        @endphp
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ get_setting('meta_title') }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ $meta_image }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator" content="@author_handle">
        <meta name="twitter:image" content="{{ $meta_image }}">

        <!-- Open Graph data -->
        <meta property="og:title" content="{{ get_setting('meta_title') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ route('home') }}" />
        <meta property="og:image" content="{{ $meta_image }}" />
        <meta property="og:description" content="{{ get_setting('meta_description') }}" />
        <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
        <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif

    <!-- Favicon -->
    @php
        $site_icon = uploaded_asset(get_setting('site_icon'));
    @endphp
    <link rel="icon" href="{{ $site_icon }}">
    <link rel="apple-touch-icon" href="{{ $site_icon }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css?v=') }}{{ get_setting('current_version') }}">
    @if ($rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000, 9999) }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=') }}{{ get_setting('current_version') }}">
    @if(get_setting('homepage_select') == 'thecore')
    <link rel="stylesheet" href="{{ static_asset('assets/css/thecore.css') }}">
    @endif

    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>

    <style>
        :root{
            --blue: #3490f3;
            --hov-blue: #2e7fd6;
            --soft-blue: rgba(0, 123, 255, 0.15);
            --secondary-base: {{ get_setting('secondary_base_color', '#ffc519') }};
            --hov-secondary-base: {{ get_setting('secondary_base_hov_color', '#dbaa17') }};
            --soft-secondary-base: {{ hex2rgba(get_setting('secondary_base_color', '#ffc519'), 0.15) }};
            --gray: #9d9da6;
            --gray-dark: #8d8d8d;
            --secondary: #919199;
            --soft-secondary: rgba(145, 145, 153, 0.15);
            --success: #85b567;
            --soft-success: rgba(133, 181, 103, 0.15);
            --warning: #f3af3d;
            --soft-warning: rgba(243, 175, 61, 0.15);
            --light: #f5f5f5;
            --soft-light: #dfdfe6;
            --soft-white: #b5b5bf;
            --dark: #292933;
            --soft-dark: #1b1b28;
            --primary: {{ get_setting('base_color', '#d43533') }};
            --hov-primary: {{ get_setting('base_hov_color', '#9d1b1a') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color', '#d43533'), 0.15) }};
        }
        body{
            font-family: {!! !empty(get_setting('system_font_family')) ? get_setting('system_font_family') : "'Public Sans', sans-serif" !!}, sans-serif;
            font-weight: 400;
        }

        .pagination .page-link,
        .page-item.disabled .page-link {
            min-width: 32px;
            min-height: 32px;
            line-height: 32px;
            text-align: center;
            padding: 0;
            border: 1px solid var(--soft-light);
            font-size: 0.875rem;
            border-radius: 0 !important;
            color: var(--dark);
        }
        .pagination .page-item {
            margin: 0 5px;
        }

        .form-control:focus {
            border-width: 2px !important;
        }
        .iti__flag-container {
            padding: 2px;
        }
        .modal-content {
            border: 0 !important;
            border-radius: 0 !important;
        }

        .tagify.tagify--focus{
            border-width: 2px;
            border-color: var(--primary);
        }

        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }

        .pac-container { z-index: 100000; }

        .home-category-banner::after {
            content: "{{ translate('View All') }}";
        }

        .footer-email-input::placeholder{
            color: {{ get_setting('footer_text_color') }} !important;
            opacity: 0.5;
        }

        .header-content-wrapper .nav-menu-list .nav-item .nav-link:hover,
        .header-content-wrapper .add-to-cart:hover{
            color: {{ get_setting('top_header_text_color') }} !important;
            opacity: 0.5;
        }

        .login-nav-item:hover .user-icon-circle{
            border-color: {{ get_setting('top_header_text_color') }} !important;
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.16);
        }

        .login-nav-item:hover .user-icon-circle i{
            color:  {{ get_setting('top_header_text_color') }} !important;
        }

    </style>

@if (get_setting('google_analytics') == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
@endif

@if (get_setting('facebook_pixel') == 1)
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@endif
@if(session()->has('ga4_event'))
<script>
    window.dataLayer = window.dataLayer || [];
    dataLayer.push(@json(session('ga4_event')));
</script>
@php
    session()->forget('ga4_event');
@endphp
@endif
@php
    echo get_setting('header_script');
    echo get_setting('header_gtm_script');
@endphp

</head>
<body>
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column bg-white aiz-{{ get_setting('homepage_select') }}">
        @php
            $user = auth()->user();
            $user_avatar = null;
            $carts = [];
            if ($user && $user->avatar_original != null) {
                $user_avatar = uploaded_asset($user->avatar_original);
            }

            $system_language = get_system_language();
        @endphp
        <!-- Header -->
        @include('frontend.inc.nav')

        @yield('content')

        <!-- footer -->
        @include('frontend.inc.footer')

    </div>

    @if(get_setting('use_floating_buttons') == 1)
        <!-- Floating Buttons -->
        @include('frontend.inc.floating_buttons')
    @endif

    <div class="aiz-refresh">
        <div class="aiz-refresh-content"><div></div><div></div><div></div></div>
    </div>


    @if (env("DEMO_MODE") == "On")
        <!-- demo nav -->
        @include('frontend.inc.demo_nav')
    @endif

    <!-- cookies agreement -->
    @php
        $alert_location = get_setting('custom_alert_location');
        $order = in_array($alert_location, ['top-left', 'top-right']) ? 'asc' : 'desc';
        $custom_alerts = App\Models\CustomAlert::where('status', 1)->orderBy('id', $order)->get();
        $hasUnreviewed = false;
        
        use App\Models\Order;
        use App\Models\OrderDetail;
        if(auth()->user()){
        $userOrderIds = Order::where('user_id', auth()->user()->id)->pluck('id');
        $hasUnreviewed = OrderDetail::whereIn('order_id', $userOrderIds)->where('delivery_status', 'delivered')->where('reviewed', 0)->exists();
        }
    @endphp

    <div class="aiz-custom-alert {{ get_setting('custom_alert_location') }}" id="aiz-custom-sale-alert">
        @foreach ($custom_alerts as $custom_alert)
            @if($custom_alert->id == 1)
                <div class="aiz-cookie-alert mb-3" style="box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                    <div class="p-3 px-lg-2rem rounded-2" style="background: {{ $custom_alert->background_color }};">
                        <div class="text-{{ $custom_alert->text_color }} mb-3">
                            {!! $custom_alert->description !!}
                        </div>
                        <button class="btn btn-block btn-primary rounded-0 aiz-cookie-accept">
                            {{ translate('Ok. I Understood') }}
                        </button>
                    </div>
                </div>
            @elseif($custom_alert->id == 200)
                @php
                    $showalert = true;
                    if(auth()->user()){
                    $showalert = $hasUnreviewed;
                    }else{
                    $showalert= false;  
                    }
                @endphp
                @if(addon_is_activated('club_point') && get_setting('set_point_for_product_review') != 0 && $showalert)
                    <div class="aiz-cookie-alert mb-3 club-point-alert" style="box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                        <div class="p-3 px-lg-2rem rounded-2" style="background: {{ $custom_alert->background_color }};">
                            <div class="text-{{ $custom_alert->text_color }} mb-3 custom-alert-for-product-club-point">
                                {!! $custom_alert->description !!}
                                @if(get_setting('set_club_point_for_sellers_product_review') == 0)
                                    {{ translate('Club points are awarded only for reviews on admin’s products.') }}
                                @endif
                            </div>
                            <button class="btn btn-block btn-primary rounded-0 aiz-cookie-accept-club-point">
                                {{ translate('Ok. I Understood') }}
                            </button>
                        </div>
                    </div>
                @endif                  
            @elseif($custom_alert->id == 300) 
                @php
                    if(auth()->check()){
                    $showcustomalert = true;
                    }else{
                    $showcustomalert= false;  
                    }
                @endphp
                @if(addon_is_activated('otp_system') && $showcustomalert && auth()->user()->otp_alert_seen == 0)
                    <div class="aiz-cookie-alert mb-3" style="box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                        <div class="p-3 px-lg-2rem rounded-0" style="background: {{ $custom_alert->background_color }};">
                            <div class="text-{{ $custom_alert->text_color }} mb-3">
                                {!! $custom_alert->description !!}
                            </div>
                            <button class="btn btn-block btn-primary rounded-0 aiz-cookie-accept">
                                {{ translate('Ok. I Understood') }}
                            </button>
                        </div>
                    </div>
                @endif                  
            @else
                <div class="mb-3 custom-alert-box removable-session rounded-2 d-none" data-key="custom-alert-box-{{ $custom_alert->id }}" data-value="removed" data-auto_hide={{ $custom_alert->auto_hide }} style="box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                    <div class=" position-relative" style="background: {{ $custom_alert->background_color }};">
                        <a href="{{ $custom_alert->link }}" class="d-block h-100 w-100">
                            <div class="@if ($custom_alert->type == 'small') d-flex @endif">
                                <img class="@if ($custom_alert->type == 'small') h-140px w-120px img-fit @else w-100 @endif" src="{{ uploaded_asset($custom_alert->banner) }}" alt="custom_alert">
                                <div class="text-{{ $custom_alert->text_color }} p-2rem">
                                    {!! $custom_alert->description !!}
                                </div>
                            </div>
                        </a>
                        <button class="absolute-top-right bg-transparent btn btn-circle btn-icon d-flex align-items-center justify-content-center text-{{ $custom_alert->text_color }} hov-text-primary set-session" data-key="custom-alert-box-{{ $custom_alert->id }}" data-value="removed" data-parent=".custom-alert-box">
                            <i class="la la-close fs-20"></i>
                        </button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- website popup -->
    @php
        $dynamic_popups = App\Models\DynamicPopup::where('status', 1)->orderBy('id', 'asc')->get();
        $popup_count = 0;
        $hideWrapper = false;
        if(count($dynamic_popups) == 1 && ($dynamic_popups[0]->id == 100 && $hasUnreviewed == false)) {
            $hideWrapper = true;
        }
    @endphp

    @if(count($dynamic_popups) > 0)
    <div class="modal website-popup removable-session d-none "
        data-key="stack-popup-main"
        data-value="removed" id="stack-popup-main-wrapper" @if($hideWrapper) style="display: none;" @endif>


        <div class="absolute-full bg-black opacity-60"></div>

        <div class="stack-container mx-auto" id="stackParent">

            @foreach ($dynamic_popups as $key => $dynamic_popup)
                
                @php
                    
                    $showPopup = true;
                    if ($dynamic_popup->id == 100 ) {
                        if(auth()->user()){
                            $showPopup = $hasUnreviewed;
                        }else{
                            $showPopup = false;
                        }
                    }
                @endphp

                @if($showPopup)
                @php $popup_count++; @endphp

                <div class="card-wrapper card-pos-{{ $popup_count-1 }}"
                    id="w{{ $dynamic_popup->id }}"
                    data-key="stack-popup-{{ $dynamic_popup->id }}">

                    <button class="btn-close-stack d-none"
                            onclick="removeTopCard('w{{ $dynamic_popup->id }}')">
                        <i class="la la-close"></i>
                    </button>

                    <div class="mirror-card">

                        <img src="{{ uploaded_asset($dynamic_popup->banner) }}"
                            class="card-img">

                        <div class="p-4 text-center">
                            <h5 class="font-weight-bold">
                                {{ $dynamic_popup->title }}
                            </h5>

                            <p class="text-muted fs-16">
                                {{ $dynamic_popup->summary }}
                            </p>

                            @if($dynamic_popup->id == 1 && $dynamic_popup->show_subscribe_form == 'on')
                                <!-- Subscription form for ID 1 -->
                                <form class="mt-3" method="POST" action="{{ route('subscribers.store') }}">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <input type="email" class="form-control rounded-0" 
                                            placeholder="{{ translate('Your Email Address') }}" 
                                            name="email" required>
                                    </div>
                                    <button type="submit" class="vote-btn w-100 set-session"
                                            data-key="stack-popup-{{ $dynamic_popup->id }}"
                                            data-value="removed">
                                        {{ $dynamic_popup->btn_text }}
                                    </button>
                                </form>
                            @elseif($dynamic_popup->btn_link)
                                <!-- Regular button for other popups -->
                                <a href="{{ $dynamic_popup->btn_link }}"
                                class="vote-btn d-block set-session w-100"
                                data-key="stack-popup-{{ $dynamic_popup->id }}"
                                data-value="removed">
                                    {{ $dynamic_popup->btn_text }}
                                </a>
                            @endif
                        </div>
                        <div class="loader-container">
                            <div class="loader-fill" id="fill-w{{ $dynamic_popup->id }}"></div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif





    @include('modals.size_chart_show_modal')

    @include('frontend.partials.modal')

    @include('frontend.partials.account_delete_modal')

    <div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader text-center p-3">
                    <i class="las la-spinner la-spin la-3x"></i>
                </div>
                <button type="button" class="close absolute-top-right btn-icon close z-1 btn-circle hov-text-blue bg-light hov-bg-gray has-transition mr-3 mt-3 d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close" style="background: #ededf2; width: calc(2rem + 2px); height: calc(2rem + 2px);">
                     <i class="la la-close fs-20 text-gray hov-text-blue has-transition"></i>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>


    <!-- Offcanvas -->
    <div id="rightOffcanvas"
        class="position-fixed top-0 fullscreen bg-white py-20px z-1045 {{ $offcanvas_class ?? 'right-offcanvas-md' }}">
    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>


    @yield('modal')

    <div id="videoModal" class="video-modal">
        <div class="modal-video-wrapper">
            <video id="popupVideo" style="width: 100%; height: 100%" controls disablePictureInPicture></video>
            <span id="closeModalBtn" class="close-modal">✖</span>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js?v=') }}{{ get_setting('current_version') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000, 9999) }}"></script>

    {{-- WhatsaApp Chat --}}
    @if (get_setting('whatsapp_chat') == 1)
        <script type="text/javascript">
            (function () {
                var options = {
                    whatsapp: "{{ env('WHATSAPP_NUMBER') }}",
                    call_to_action: "{{ translate('Message us') }}",
                    position: "right", // Position may be 'right' or 'left'
                };
                var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
                var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
                s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
                var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
            })();
        </script>
    @endif

    <style>
        .sc-q8c6tt-3 {
            bottom: 54px !important;
        }

        a[aria-label="Go to GetButton.io website"] {
            display: none !important;
        }
        
    </style>
    @if(addon_is_activated('otp_system'))
        <script>
            document.querySelector('.otp-alert-link')?.addEventListener('click', function () {

                fetch("{{ route('otp.alert.seen') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({})
                });

            });
        </script>
    @endif

    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>

    <script>
        window.POPUP_DURATION = {{ (int) get_setting('dynamic_popup_duration') ?? 5 }};
        let currentIdx = 0;
        let cardIds = [];
        let timer;

        $(document).ready(function () {

            $('.card-wrapper:visible').each(function () {
                cardIds.push($(this).attr('id'));
            });
            startTimer();
        });

    </script>

    <!-- Smooth Alert Transitions JavaScript -->
    <script>
        
        $(document).ready(function() {
            // Handle auto-hide alerts
            $('.aiz-custom-alert .custom-alert-box[data-auto_hide]').each(function() {
                const $alert = $(this);
                const seconds = parseInt($alert.attr('data-auto_hide'), 10) || 0;
                if (seconds > 0) {                    
                    setTimeout(() => {
                        smoothlyRemoveElement($alert);
                    }, seconds * 1000);
                }
            });

            // Handle cookie alert accept button
            $('.aiz-custom-alert').on('click', '.aiz-cookie-accept, .aiz-cookie-accept-club-point', function(e) {
                e.preventDefault();
                const $parent = $(this).closest('.aiz-cookie-alert');
                if ($parent.length) {
                    smoothlyRemoveElement($parent);
                }
            });

            // Handle custom alert box close button
            $('.aiz-custom-alert').on('click', '[data-parent=".custom-alert-box"]', function(e) {
                e.preventDefault();
                const $box = $(this).closest('.custom-alert-box');
                if ($box.length) {
                    smoothlyRemoveElement($box);
                }
            });
        });
    </script>

    <script>
        @if (Route::currentRouteName() == 'home' || Route::currentRouteName() == '/')

            $.post('{{ route('home.section.featured') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.todays_deal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#todays_deal').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.best_selling') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });

            function updateProductsPerRow() {

                let perRow = 8;

                if (window.innerWidth < 576) {
                    perRow = 2;
                } else if (window.innerWidth < 768) {
                    perRow = 3;
                } else if (window.innerWidth < 992) {
                    perRow = 4;
                } else if (window.innerWidth < 1700) {
                    perRow = 5;
                } else if (window.innerWidth < 2100) {
                    perRow = 6;
                } else if (window.innerWidth < 2415) {
                    perRow = 7; 
                } else if (window.innerWidth > 2415) {
                    perRow = 8;
                } 

                $('#nexa-product-wrapper').attr('data-products-per-row', perRow);
            }

            updateProductsPerRow();

            $(window).on('resize', updateProductsPerRow);

            let dynamicLimit = 12;

            @if (get_setting('homepage_select') == 'nexa')

                let perRow = parseInt($('#nexa-product-wrapper').attr('data-products-per-row')) || 4;

                dynamicLimit = perRow * 2;

            @endif

            $.post('{{ route('home.section.newest_products') }}', {
                _token: '{{ csrf_token() }}',
                limit: dynamicLimit
            }, function(data) {
                $('#section_newest').html(data);
                AIZ.plugins.slickCarousel();
                @if (get_setting('homepage_select') == 'thecore')
                 toggleViewMoreButton();
                @endif
            });

            $.post('{{ route('home.section.auction_products') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });

            var isPreorderEnabled = @json(addon_is_activated('preorder'));

            if (isPreorderEnabled) {
                $.post('{{ route('home.section.preorder_products') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('#section_featured_preorder_products').html(data);
                    AIZ.plugins.slickCarousel();
                });
            }

            $.post('{{ route('home.section.home_categories') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });

        @endif

        $(document).ready(function() {
            $('.category-nav-element').each(function(i, el) {

                $(el).on('mouseover', function(){
                    if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                        $.post('{{ route('category.elements') }}', {
                            _token: AIZ.data.csrf,
                            id:$(el).data('id'
                            )}, function(data){
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });

            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('{{ route('language.change') }}',{_token: AIZ.data.csrf, locale:locale}, function(data){
                            location.reload();
                        });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('{{ route('currency.change') }}',{_token: AIZ.data.csrf, currency_code:currency_code}, function(data){
                            location.reload();
                        });

                    });
                });
            }
        });

        $('#search').on('keyup', function(){
            search();
        });

        $('#search').on('focus', function(){
            search();
        });

        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        function searchMobile(){
            var searchKey = $('#search-mobile').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");
                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        $('#search-content-mobile').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');
                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content-mobile').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        $(".aiz-user-top-menu").on("mouseover", function (event) {
            $(".hover-user-top-menu").addClass('active');
        })
        .on("mouseout", function (event) {
            $(".hover-user-top-menu").removeClass('active');
        });

        $(document).on("click", function(event){
            var $trigger = $("#category-menu-bar");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $("#click-category-menu").slideUp("fast");;
                $("#category-menu-bar-icon").removeClass('show');
            }
        });

        function updateNavCart(view,count){
            $('.cart-count').html(count);
            $('#cart_items').html(view);
        }

        function removeFromCart(key){
            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf,
                id      :  key
            }, function(data){
                updateNavCart(data.nav_cart_view,data.cart_count);
                $('#cart-details').html(data.cart_view);
                AIZ.plugins.notify('danger', "{{ translate('Item has been removed from cart') }}");
                $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
            });
        }

        function showLoginModal() {
            $('#login_modal').modal();
        }

        function addToCompare(id){
            $.post('{{ route('compare.addToCompare') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#compare').html(data);
                AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");
                $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html())+1);
            });
        }

        function addToWishList(id){
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                $.post('{{ route('wishlists.store') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                    if(data.html){
                        $('#wishlist').html(data.html);
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                         if(data.ga4Data && "{{ get_setting('google_analytics') }}" == 1){
                            pushGA4Event(data.ga4Data);
                         }
                    }
                    else{
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @elseif(Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the WishList.') }}");
            @else
                AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function showAddToCartModal(id){
            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
            $('.c-preloader').show();
            $.post('{{ route('cart.showCartModal') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data);
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            });
        }

           // Right Offcanvas JS Start
        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        // Open function
        function showAddToCartRightCanvas(id) {
            const $social_chat = $('.sc-q8c6tt-3');
            if ($social_chat.length) {
                $social_chat.addClass('d-none');
            }
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            rightOffcanvas.innerHTML = '<div class="h-100 w-100 d-flex justify-content-center align-items-center"><div class="footable-loader" style="height: 100vh !important;"><span class="fooicon fooicon-loader"></span></div> </div>';

            $.ajax({
                url: '{{ route('cart.selectVariantCanvas') }}',
                type: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    id: id
                },
                success: function (data) {
                    rightOffcanvas.innerHTML = data;
                    AIZ.plugins.slickCarousel();
                    AIZ.plugins.zoom();
                    AIZ.extra.plusMinus();
                    getVariantPrice();
                },
                error: function () {
                    rightOffcanvas.innerHTML =
                        '<p class="text-danger">{{ translate("Failed to load stock data") }}</p>';
                }
            });
        }

            // Close function
            function closeRightcanvas() {
                rightOffcanvas.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('body-no-scroll');
                const $social_chat = $('.sc-q8c6tt-3');
                if ($social_chat.length) {
                    $social_chat.removeClass('d-none');
                }
            }
            function closeOffcanvas() {
                closeRightcanvas();
            }

            if (overlay) {
                overlay.addEventListener('click', closeRightcanvas);
            }
            // Optional: close with ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeRightcanvas();
            });
        // Right Offcanvas JS End

        
        function showSizeChartDetail(id, name){
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            if (id == 0) {
                AIZ.plugins.notify('warning', '{{ translate("Sorry, There is no size guide found for this product.") }}');
                return false;
            }
            $.ajax({
                type: "GET",
                url: "{{ route('size-charts-show', '') }}/"+id,
                data: {},
                success: function(data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }

        function showReviewImageModal(imageUrl, imagesJson) {
            try {
                var images = JSON.parse(imagesJson);
                var currentIndex = images.indexOf(imageUrl);

                $('#modalReviewImage').attr('src', imageUrl);
                $('#reviewImageModal').modal('show');

                $('#prevImageBtn').off('click').on('click', function() {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    $('#modalReviewImage').attr('src', images[currentIndex]);
                });

                $('#nextImageBtn').off('click').on('click', function() {
                    currentIndex = (currentIndex + 1) % images.length;
                    $('#modalReviewImage').attr('src', images[currentIndex]);
                });
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        }

        $('#option-choice-form input').on('change', function(){
            getVariantPrice();
        });

        $(document).on('change click', '#option-choice-form input', function () {
            getVariantPrice();
        });

        
        function getVariantPrice(){
            if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
                $.ajax({
                    type:"POST",
                    url: '{{ route('products.variant_price') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        
                        
                        const responseImage = data.image || null;

                        if (responseImage) {
                         const thumbEl = document.querySelector('.thumb-slider');
                            const mainEl = document.querySelector('.main-slider');
                            const thumbSwiper = thumbEl && thumbEl.swiper ? thumbEl.swiper : null;
                            const mainSwiper = mainEl && mainEl.swiper ? mainEl.swiper : null;

                            let found = false;
                            $('.thumb-slider .swiper-slide').each(function (index) {
                                const slideImage = $(this).data('variation-image');
                                if (String(slideImage) === String(responseImage)) {
                                    found = true;
                                    if (thumbSwiper && typeof thumbSwiper.slideTo === 'function') {
                                        thumbSwiper.slideTo(index);
                                    }
                                    if (mainSwiper && typeof mainSwiper.slideTo === 'function') {
                                        mainSwiper.slideTo(index);
                                    }
                                    $(this).trigger('click');
                                    return false;
                                }
                            });

                            // if nothing matched, reset to first slide once
                            if (!found) {
                                if (thumbSwiper && typeof thumbSwiper.slideTo === 'function') thumbSwiper.slideTo(0);
                                if (mainSwiper && typeof mainSwiper.slideTo === 'function') mainSwiper.slideTo(0);
                            }

                        }

                        $('#option-choice-form #chosen_price_div').removeClass('d-none');
                        $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                        $('#variant_sku_section #variant_sku').html(data.sku);
                        $('#option-choice-form #selected_variant').html(data.variation);
                        $('#available-quantity').html(data.quantity);
                        $('.input-number').prop('max', data.max_limit);
                        if(parseInt(data.in_stock) == 0 && data.digital  == 0){
                           $('.buy-now').addClass('d-none');
                           $('.add-to-cart').addClass('d-none');
                           $('.out-of-stock').removeClass('d-none');
                        }
                        else{
                           $('.buy-now').removeClass('d-none');
                           $('.add-to-cart').removeClass('d-none');
                           $('.out-of-stock').addClass('d-none');
                        }

                        AIZ.extra.plusMinus();
                    }
                });
                $('#add_to_cart_count').text('(' + String($('#option-choice-form input[name=quantity]').val()).padStart(2, '0') + ')');
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() { // find unique names
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                count++;
            });

            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }

            return false;
        }

        function addToCart(){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                animateAddToCartButton('#added_to_cart_btn', 'loading');
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                       animateAddToCartButton('#added_to_cart_btn', 'success');
                       $('#addToCart-modal-body').html(null);
                       $('.c-preloader').hide();
                       $('#modal-size').removeClass('modal-lg');
                       $('#addToCart-modal-body').html(data.modal_view);
                       AIZ.extra.plusMinus();
                       AIZ.plugins.slickCarousel();
                       updateNavCart(data.nav_cart_view,data.cart_count);
                       if ("{{ get_setting('google_analytics') }}" == 1){
                           pushGA4Event(data.ga4Data);
                       }
                    }
                });

                if ("{{ get_setting('facebook_pixel') }}" == 1){
                    // Facebook Pixel AddToCart Event
                    fbq('track', 'AddToCart', {content_type: 'product'});
                    // Facebook Pixel AddToCart Event
                }
            }
            else{
                animateAddToCartButton('#added_to_cart_btn', 'reset');
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function addToCartSingleProduct(productId = null){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(!productId){
                AIZ.plugins.notify('warning', "{{ translate('Product not found') }}");
                return false;
            }

            var formData = {
                id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            };

            if(checkAddToCartValidity()) {
                $('.c-preloader').show();
                $('#addToCart-modal-body').html('<div class="text-center p-5"><div class="c-preloader"></div></div>');
                $('#modal-size').removeClass('modal-lg');
                $('#addToCart').modal('show'); 

                $.ajax({
                    type: "POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: formData,
                    success: function(data){
                        $('#addToCart .c-preloader').hide(); 

                        if (data && data.modal_view) {
                            $('#addToCart-modal-body').html(data.modal_view);

                            try {
                                AIZ.extra.plusMinus();
                                AIZ.plugins.slickCarousel();
                                if (typeof updateNavCart === 'function') {
                                    updateNavCart(data.nav_cart_view, data.cart_count);
                                }
                            } catch (e) {
                                console.warn("JS init error:", e);
                            }

                            $('#addToCart .modal-body').scrollTop(0);
                        } else {
                            $('#addToCart-modal-body').html('<div class="text-center p-5 text-danger">Product details not available.</div>');
                        }
                        if ("{{ get_setting('google_analytics') }}" == 1){
                           pushGA4Event(data.ga4Data);
                        }
                    },
                    error: function() {
                        AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                        $('.c-preloader').hide();
                    }
                });

                if ("{{ get_setting('facebook_pixel') }}" == 1){
                    fbq('track', 'AddToCart', {content_type: 'product'});
                }
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function buyNow(){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        if(data.status == 1){
                            $('#addToCart-modal-body').html(data.modal_view);
                            updateNavCart(data.nav_cart_view,data.cart_count);
                            window.location.replace("{{ route('cart') }}");
                            if ("{{ get_setting('google_analytics') }}" == 1){
                                pushGA4Event(data.ga4Data);
                            }
                        }
                        else{
                            $('#addToCart-modal-body').html(null);
                            $('.c-preloader').hide();
                            $('#modal-size').removeClass('modal-lg');
                            $('#addToCart-modal-body').html(data.modal_view);
                        }
                    }
               });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function bid_single_modal(bid_product_id, min_bid_amount, gst_rate = null){
            @if (Auth::check() && (isCustomer() || isSeller()))
                var min_bid_amount_text = "({{ translate('Min Bid Amount: ') }}"+min_bid_amount+")";
                if (gst_rate !== null){
                $('#gst_applicable_alert').text("{{ translate('An') }} "+gst_rate+"%" + " {{ translate('GST will be applied if you win the bid and proceed with the purchase') }}");
                }
                $('#min_bid_amount').text(min_bid_amount_text);
                $('#bid_product_id').val(bid_product_id);
                $('#bid_amount').attr('min', min_bid_amount);
                $('#bid_for_product').modal('show');
            @elseif (Auth::check() && isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function clickToSlide(btn,id){
            $('#'+id+' .aiz-carousel').find('.'+btn).trigger('click');
            $('#'+id+' .slide-arrow').removeClass('link-disable');
            var arrow = btn=='slick-prev' ? 'arrow-prev' : 'arrow-next';
            if ($('#'+id+' .aiz-carousel').find('.'+btn).hasClass('slick-disabled')) {
                $('#'+id).find('.'+arrow).addClass('link-disable');
            }
        }

        function goToView(params) {
            document.getElementById(params).scrollIntoView({behavior: "smooth", block: "center"});
        }

        function copyCouponCode(code){
            navigator.clipboard.writeText(code);
            AIZ.plugins.notify('success', "{{ translate('Coupon Code Copied') }}");
        }

        $(document).ready(function(){
            $('.cart-animate').animate({margin : 0}, "slow");

            $({deg: 0}).animate({deg: 360}, {
                duration: 2000,
                step: function(now) {
                    $('.cart-rotate').css({
                        transform: 'rotate(' + now + 'deg)'
                    });
                }
            });

            setTimeout(function(){
                $('.cart-ok').css({ fill: '#d43533' });
            }, 2000);

        });

        function nonLinkableNotificationRead(){
            $.get('{{ route('non-linkable-notification-read') }}',function(data){
                $('.unread-notification-count').html(data);
            });
        }

        function animateAddToCartButton(btnSelector, state = 'loading') {
            const $btn = $(btnSelector);

            if (!$btn.length) return;

            // store original text once
            if (!$btn.data('original-html')) {
                $btn.data('original-html', $btn.html());
            }

            if (state === 'loading') {
                $btn
                    .addClass('adding')
                    .prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm mr-2"></span>');
            }

            if (state === 'success') {
                $btn
                    .removeClass('adding')
                    .addClass('added-success')
                    .html('<i class="las la-check"></i>');

                setTimeout(() => {
                    $btn
                        .removeClass('added-success')
                        .prop('disabled', false)
                        .html($btn.data('original-html'));
                }, 1500);
            }

            if (state === 'reset') {
                $btn
                    .removeClass('adding added-success')
                    .prop('disabled', false)
                    .html($btn.data('original-html'));
            }
        }

    </script>



    <script type="text/javascript">
        if ($('input[name=country_code]').length > 0){
            // Country Code
            var isPhoneShown = true,
                countryData = window.intlTelInputGlobals.getCountryData(),
                input = document.querySelector("#phone-code");

            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                if (country.iso2 == 'bd') {
                    country.dialCode = '88';
                }
            }

            var iti = intlTelInput(input, {
                separateDialCode: true,
                utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
                onlyCountries: @php echo get_active_countries()->pluck('code') @endphp,
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    if (selectedCountryData.iso2 == 'bd') {
                        return "01xxxxxxxxx";
                    }
                    return selectedCountryPlaceholder;
                }
            });

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

            input.addEventListener("countrychange", function(e) {
                // var currentMask = e.currentTarget.placeholder;
                var country = iti.getSelectedCountryData();
                $('input[name=country_code]').val(country.dialCode);

            });

            function toggleEmailPhone(el) {
                if (isPhoneShown) {
                    $('.phone-form-group').addClass('d-none');
                    $('.email-form-group').removeClass('d-none');
                    $('input[name=phone]').val(null);
                    isPhoneShown = false;
                    $(el).html('*{{ translate('Use Phone Number Instead') }}');
                } else {
                    $('.phone-form-group').removeClass('d-none');
                    $('.email-form-group').addClass('d-none');
                    $('input[name=email]').val(null);
                    isPhoneShown = true;
                    $(el).html('<i>*{{ translate('Use Email Instead') }}</i>');
                }
            }
        }
    </script>

    <script>
        var acc = document.getElementsByClassName("aiz-accordion-heading");
        var i;
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    </script>

    <script>
        function showFloatingButtons() {
            document.querySelector('.floating-buttons-section').classList.toggle('show');;
        }
    </script>

    @if (env("DEMO_MODE") == "On")
        <script>
            var demoNav = document.querySelector('.aiz-demo-nav');
            var menuBtn = document.querySelector('.aiz-demo-nav-toggler');
            var lineOne = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--1');
            var lineTwo = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--2');
            var lineThree = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--3');
            menuBtn.addEventListener('click', () => {
                toggleDemoNav();
            });

            function toggleDemoNav() {
                // demoNav.classList.toggle('show');
                demoNav.classList.toggle('shadow-none');
                lineOne.classList.toggle('line-cross');
                lineTwo.classList.toggle('line-fade-out');
                lineThree.classList.toggle('line-cross');
                if ($('.aiz-demo-nav-toggler').hasClass('show')) {
                    $('.aiz-demo-nav-toggler').removeClass('show');
                    demoHideOverlay();
                }else{
                    $('.aiz-demo-nav-toggler').addClass('show');
                    demoShowOverlay();
                }
            }

            $('.aiz-demos').click(function(e){
                if (!e.target.closest('.aiz-demos .aiz-demo-content')) {
                    toggleDemoNav();
                }
            });

            function demoShowOverlay(){
                $('.top-banner').removeClass('z-1035').addClass('z-1');
                $('.top-navbar').removeClass('z-1035').addClass('z-1');
                $('header').removeClass('z-1020').addClass('z-1');
                $('.aiz-demos').addClass('show');
            }

            function demoHideOverlay(cls=null){
                if($('.aiz-demos').hasClass('show')){
                    $('.aiz-demos').removeClass('show');
                    $('.top-banner').delay(800).removeClass('z-1').addClass('z-1035');
                    $('.top-navbar').delay(800).removeClass('z-1').addClass('z-1035');
                    $('header').delay(800).removeClass('z-1').addClass('z-1020');
                }
            }
        </script>
        
    @endif

    @if (get_setting('header_element') == 5 || get_setting('header_element') == 6 || get_setting('header_element') == 10)
        <script>
            // Language switcher
            function changeLanguage(code) {
                $.post('{{ route('language.change') }}', {
                    _token: '{{ csrf_token() }}',
                    locale: code
                }, function () {
                    location.reload();
                });
            }

            // Currency switcher
            function changeCurrency(code) {
                $.post('{{ route('currency.change') }}', {
                    _token: '{{ csrf_token() }}',
                    currency_code: code
                }, function () {
                    location.reload();
                });
            }
        </script>
    @endif

    <script>
    function fixSlickVisibility() {
        $('.slick-slide').css('visibility', 'visible');
        $('.slick-track').css('opacity', '1');
    }

    // Call after fullscreen exit
    $(window).on('resize', function() {
        setTimeout(function() {
            $('.product-gallery').slick('setPosition');
            $('.product-gallery-thumb').slick('setPosition');
            fixSlickVisibility();
        }, 300);
    });
    </script>
    @if(get_setting('show_custom_product_sale_alert')==1)
    <script>
    const saleAlertProducts = @json(get_all_sale_alert_products());

        function showSaleAlert() {
            if (!saleAlertProducts || saleAlertProducts.length === 0) return;
            const randomProduct = saleAlertProducts[Math.floor(Math.random() * saleAlertProducts.length)];
            const html = `
                <div class="alert  bg-white alert-dismissible rounded-2" role="alert" style="display: none; box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                    <div class="d-flex align-items-center">
                        <img src="${randomProduct.image}" class="h-50px w-50px img-fit mr-2 rounded" alt="${randomProduct.title}">
                        <div>
                            <span class="text-truncate-2">
                                <a href="${randomProduct.url}" class="text-dark font-weight-bold">${randomProduct.title}</a>
                            </span>
                             — {{ translate('ordered just now') }}!
                        </div>
                        <button type="button" class="close ml-auto hov-text-primary set-session" data-parent=".alert">
                            <i class="la la-close fs-20"></i>
                        </button>
                    </div>
                </div>
            `;
            const $container = $('#aiz-custom-sale-alert');
            @if ( get_setting('custom_alert_location')  == 'top-left' || get_setting('custom_alert_location') == 'top-right')
            const $alert = $(html).appendTo($container);
            @else
            const $alert = $(html).prependTo($container);
            @endif
            $alert.stop(true, true).fadeIn(600);

            const displayMs = 4000;
            const fadeOutTimeout = setTimeout(() => {
                smoothlyRemoveElement($alert);
            }, displayMs);

             $alert.find('.set-session').on('click', function() {
                clearTimeout(fadeOutTimeout);
                smoothlyRemoveElement($alert);
             });
        }


        function startRandomAlerts() {
            const min = parseInt(`{{ get_setting('sale_alert_min_time') }}`)  * 1000; 
            const max = parseInt(`{{ get_setting('sale_alert_max_time') }}`)  * 1000;
            const randomDelay = Math.random() * (max - min) + min;

            setTimeout(() => {
                showSaleAlert();
                startRandomAlerts();
            }, randomDelay);
        }
        if (Array.isArray(saleAlertProducts) && saleAlertProducts.length) {
            startRandomAlerts();
        }

        function pushGA4Event(ga4Data) {
            window.dataLayer = window.dataLayer || [];
            dataLayer.push(ga4Data);
        }
    </script>
    @endif

   <script>
    $(document).ready(function() {
        $('.set-session').on('click', function(e) {
            const $target = $(e.currentTarget);
            const parent = $target.data('parent');
            if (parent && !$target.closest('.aiz-custom-alert').length) {
                e.preventDefault();
                $target.closest(parent).fadeOut(600);
            }
        });
    });
    </script>

    @yield('script')

    @php
        echo get_setting('footer_script');
        echo get_setting('footer_gtm_script');
    @endphp

</body>
</html>
