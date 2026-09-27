<!-- FOOTER V2 START-->
<footer class="footer-design-two" id="footer-v-two">
    <div class="pt-lg-3 text-light footer-widget" style="background-color: {{ get_setting('footer_bg_color') }}">
        <!-- footer widgets ========== [Accordion Fotter widgets are bellow from this]-->
        <div class="border-bottom mb-2" style="border-color: rgba(255, 255, 255, 0.2)!important;">
            <div class="@if (get_setting('show_full_width_footer') == 1) layout-container mx-auto px-3 @else container @endif d-none d-lg-block">
                <div class="row pb-2">
                    <!-- Quick links -->
                    <div class="col-lg-5 col-md-4 col-sm-6">
                        <div class="text-center text-sm-left mt-4">
                            <!-- footer logo -->
                            <div class="mt-3 mb-4">
                                <a href="#" class="d-block">
                                    <img class="lazyload h-45px"
                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="Logo"
                                        height="45">
                                </a>
                            </div>
                            <p class="fs-13 fw-400" style="color: {{ get_setting('footer_text_color') }}">{!! get_setting('about_us_description', null, App::getLocale()) !!}</p>
                        </div>
                    </div>

                    <!-- Contacts -->
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center text-sm-left mt-4" style="color: {{ get_setting('footer_text_color') }}">
                            <h4 class="fs-14 opacity-50  text-uppercase fw-500 mb-3">
                                {{ translate('Contacts') }}
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <p class="fs-13 opacity-50 mb-1">{{ translate('Address') }}</p>
                                    <p class="fs-13 ">{{ get_setting('contact_address', null, App::getLocale()) }}</p>
                                </li>
                                <li class="mb-2">
                                    <p class="fs-13 opacity-50 mb-1">{{ translate('Phone') }}</p>
                                    <p class="fs-13 ">{{ get_setting('contact_phone') }}</p>
                                </li>
                                <li class="mb-2">
                                    <p class="fs-13 opacity-50 mb-1">{{ translate('Email') }}</p>
                                    <p class="">
                                        <a href="mailto:{{ get_setting('contact_email') }}" style="color: {{ get_setting('footer_text_color') }}"
                                            class="fs-13 hov-text-primary">{{ get_setting('contact_email') }}</a>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- My Account -->
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="text-center text-sm-left mt-4" style="color: {{ get_setting('footer_text_color') }}">
                            <h4 class="fs-14 opacity-50 text-uppercase fw-500 mb-3">
                                {{ translate('My Account') }}
                            </h4>
                            <ul class="list-unstyled">
                                @if (Auth::check())
                                    <li class="mb-2">
                                        <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('logout') }}" style="color: {{ get_setting('footer_text_color') }}">
                                            {{ translate('Logout') }}
                                        </a>
                                    </li>
                                @else
                                    <li class="mb-2">
                                        <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('user.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                            {{ translate('Login') }}
                                        </a>
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('purchase_history.index') }}" style="color: {{ get_setting('footer_text_color') }}">
                                        {{ translate('Order History') }}
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('wishlists.index') }}" style="color: {{ get_setting('footer_text_color') }}">
                                        {{ translate('My Wishlist') }}
                                    </a>
                                </li>
                                @php
                                    $order_tracking = json_decode(get_setting('order_tracking'), true);
                                @endphp
                                @if(isset($order_tracking['enable_order_tracking']) && $order_tracking['enable_order_tracking'] == 1)
                                    <li class="mb-2">
                                        <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('orders.track') }}" style="color: {{ get_setting('footer_text_color') }}">
                                            {{ translate('Track Order') }}
                                        </a>
                                    </li>
                                @endif
                                @if (addon_is_activated('affiliate_system'))
                                <li class="mb-2">
                                    <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('affiliate.apply') }}" style="color: {{ get_setting('footer_text_color') }}">
                                        {{ translate('Be an affiliate partner') }}
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Seller & Delivery Boy -->
                    @if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
                        <div class="col-lg-3 col-md-4 col-sm-6" style="color: {{ get_setting('footer_text_color') }}">
                            <div class="text-center text-sm-left mt-4">
                                @if (get_setting('vendor_system_activation') == 1)
                                    <h4 class="fs-14 opacity-50 text-uppercase fw-500 mb-3">
                                        {{ translate('Seller Zone') }}
                                    </h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <a href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}" style="color: {{ get_setting('footer_text_color') }}"
                                                class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif ">{{ translate('Become A Seller') }}</a>
                                        </li>
                                        @guest
                                            <li class="mb-2">
                                                <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('seller.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                                    {{ translate('Login to Seller Panel') }}
                                                </a>
                                            </li>
                                        @endguest
                                        @if (get_setting('seller_app_link') && get_setting('enable_seller_app_link') == 1)
                                            <li class="mb-2">
                                                <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " target="_blank" style="color: {{ get_setting('footer_text_color') }}"
                                                    href="{{ get_setting('seller_app_link') }}">
                                                    {{ translate('Download Seller App') }}
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                                @if (addon_is_activated('delivery_boy'))
                                    @if (get_setting('delivery_boy_app_link') && get_setting('enable_delivery_app_link') == 1)
                                        <h4 class="fs-14 opacity-50 text-uppercase fw-500 mt-4 mb-3">
                                            {{ translate('Delivery Boy') }}</h4>
                                        <ul class="list-unstyled">
                                            @guest
                                                <li class="mb-2">
                                                    <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('deliveryboy.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                                        {{ translate('Login to Delivery Boy Panel') }}
                                                    </a>
                                                </li>
                                            @endguest
                                            <li class="mb-2">
                                                <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " target="_blank" style="color: {{ get_setting('footer_text_color') }}"
                                                    href="{{ get_setting('delivery_boy_app_link') }}">
                                                    {{ translate('Download Delivery Boy App') }}
                                                </a>
                                            </li>
                                        </ul>
                                    @else   
                                        @guest
                                            <h4 class="fs-14 opacity-50 text-uppercase fw-500 mt-4 mb-3">
                                                {{ translate('Delivery Boy') }}</h4>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <a class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('deliveryboy.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                                        {{ translate('Login to Delivery Boy Panel') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        @endguest
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="@if (get_setting('show_full_width_footer') == 1) layout-container mx-auto px-3 @else container @endif d-none d-lg-block">
            
            <!-- Follow & Apps -->
            <div class="d-flex justify-content-between py-4" style="color: {{ get_setting('footer_text_color') }}">
                <!-- Social -->
                @if (get_setting('show_social_links'))
                    <div>
                        <h5 class="fs-14 fw-500 opacity-50 text-uppercase">
                            {{ translate('Follow Us') }}</h5>
                        <ul class="list-inline social colored mt-3">
                            @if (!empty(get_setting('facebook_link')))
                                <li class="list-inline-item mr-2 pl-0">
                                    <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i class="lab la-facebook-f"></i></a>
                                </li>
                            @endif
                            @if (!empty(get_setting('twitter_link')))
                                <li class="list-inline-item ml-2 mr-2">
                                    <a href="{{ get_setting('twitter_link') }}" target="_blank" class="x-twitter">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff"
                                            viewBox="0 0 16 16" class="mb-2 pb-1">
                                            <path
                                                d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0
                                                            .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                                        </svg>
                                    </a>
                                </li>
                            @endif
                            @if (!empty(get_setting('instagram_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i class="lab la-instagram"></i></a>
                            </li>
                            @endif
                            @if (!empty(get_setting('youtube_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i class="lab la-youtube"></i></a>
                            </li>
                            @endif
                            @if (!empty(get_setting('linkedin_link')))
                            <li class="list-inline-item ml-2 mr-2">
                                <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin"><i class="lab la-linkedin-in"></i></a>
                            </li>
                            @endif
                        </ul>
                    </div>
                @endif

                <!-- Apps link -->
                @if (get_setting('enable_play_store_link') == 1 || get_setting('enable_app_store_link') == 1)
                    <div style="margin-right: 17px!important;">
                        <h5 class="fs-14 fw-500 opacity-50 text-uppercase" style="color: {{ get_setting('footer_text_color') }}">
                            {{ translate('Mobile Apps') }}
                        </h5>
                        <div class="d-flex flex-wrap mt-3" style="gap: 8px;">
                            @if (get_setting('play_store_link') != null && get_setting('enable_play_store_link') == 1)
                                <div class="">
                                    <a href="{{ get_setting('play_store_link') }}" target="_blank" class="mb-2 overflow-hidden hov-scale-img">
                                        <img class="lazyload has-transition"
                                            src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                            data-src="{{ static_asset('assets/img/play.png') }}"
                                            alt="{{ env('APP_NAME') }}" height="44">
                                    </a>
                                </div>
                            @endif
                            @if ( get_setting('app_store_link') != null && get_setting('enable_app_store_link') == 1)
                                <div class="">
                                    <a href="{{ get_setting('app_store_link') }}" target="_blank" class="overflow-hidden hov-scale-img">
                                        <img class="lazyload has-transition"
                                            src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                            data-src="{{ static_asset('assets/img/app.png') }}"
                                            alt="{{ env('APP_NAME') }}" height="44">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @if (get_setting('newsletter_activation'))
                <div class="d-none d-lg-block newsletter-container mb-4 p-4 rounded-2"
                    style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);">
                    <div class="row align-items-center">
                        <div class="col-lg-6 d-flex">
                            <div class="w-60px h-60px rounded-1 overflow-hidden flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56"
                                    viewBox="0 0 64 64.001" fill="{{ get_setting('footer_text_color') }}">
                                    <path id="Union_81" data-name="Union 81"
                                        d="M4430-13436a2,2,0,0,1-2-2v-36h0a2,2,0,0,1,1.253-1.856l8.745-3.5V-13498a2,2,0,0,1,2-2h40a2,2,0,0,1,2,2v18.645l8.745,3.5A1.994,1.994,0,0,1,4492-13474h0v36a2,2,0,0,1-2,2Zm2-4h56v-30.028l-26.942,16.727a2.008,2.008,0,0,1-2.11,0L4432-13470.026Zm10-28.527,18,11.174,18-11.174V-13496h-36Zm40-2.483,3.957-2.457-3.957-1.578Zm-47.951-2.457,3.954,2.457v-4.035ZM4448-13472a2,2,0,0,1-2-2,2,2,0,0,1,2-2h24a2,2,0,0,1,2,2,2,2,0,0,1-2,2Zm0-8a2,2,0,0,1-2-2,2,2,0,0,1,2-2h24a2,2,0,0,1,2,2,2,2,0,0,1-2,2Zm16-8a2,2,0,0,1-2-2,2,2,0,0,1,2-2h8a2,2,0,0,1,2,2,2,2,0,0,1-2,2Z"
                                        transform="translate(-4428 13500.001)" />
                                </svg>

                                </a>
                            </div>
                            <div class="ml-3">
                                <h5 class="m-0 fs-20 fw-700" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Sign Up to our Newsletter') }}
                                </h5>
                                <p class="mt-2 mb-0 fs-13 fw-400" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Subscribe to our newsletter for regular updates about Offers, Coupons & more') }}</p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <form class="d-flex justify-content-end" method="POST" action="{{ route('subscribers.store') }}">
                                @csrf
                                <input class="form-control mr-sm-2 flex-shrink-0 fs-14 fw-400" type="email" name="email"
                                    placeholder="Your email address" style="width: 100%; max-width: 320px; background-color: {{ get_setting('footer_text_color') }}; @if(get_setting('footer_text_color') == 'white' || get_setting('footer_text_color') == '#ffffff' ) color: black; @else color: white; @endif"
                                    aria-label="Email">
                                <button class="btn btn-primary flex-shrink-0 fs-14 fw-400"
                                    type="submit">{{ translate('Sign Up') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="">
                <div class="text-center mt-4 mb-3 row" style="color: {{ get_setting('footer_text_color') }}">
                    <h4 class="fs-14 opacity-50  text-uppercase fw-500 ml-3 pr-2" style="margin-top: .05rem">
                        {{ translate('Policy') }}
                    </h4>
                    @if (get_setting('widget_one_labels', null, App::getLocale()) != null)
                        @foreach (json_decode(get_setting('widget_one_labels', null, App::getLocale()), true) as $key => $value)
                            @php
                                $widget_one_links = '';
                                if (isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
                                    $widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
                                }
                            @endphp
                            <a href="{{ $widget_one_links }}" style="color: {{ get_setting('footer_text_color') }}"
                                class="fs-13  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif ">
                                {{ $value }}   
                            </a>
                            @if (!$loop->last)
                                <span class="pr-2 pl-2">|</span>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

        <!-- Accordion Fotter widgets -->
        <div class="d-lg-none bg-transparent" style="background-color: {{ get_setting('footer_bg_color') }}">
            
            <div class="aiz-accordion-wrap">
                <div class="aiz-accordion-heading container">
                    <button
                        class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Company Summary') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <!-- footer logo -->
                        <div class="mt-3 mb-4">
                            <a class="d-block" style="color: {{ get_setting('footer_text_color') }}">
                                <img class="lazyload h-45px"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="Logo"
                                    height="45">
                            </a>
                        </div>
                        <p class="" style="color: {{ get_setting('footer_text_color') }}">{!! get_setting('about_us_description', null, App::getLocale()) !!}</p>
                    </div>
                </div>
            </div>

            <div class="aiz-accordion-wrap">
                <div class="aiz-accordion-heading container">
                    <button
                        class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Policy')}}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            @if (get_setting('widget_one_labels', null, App::getLocale()) != null)
                                @foreach (json_decode(get_setting('widget_one_labels', null, App::getLocale()), true) as $key => $value)
                                    @php
                                        $widget_one_links = '';
                                        if (isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
                                            $widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
                                        }
                                    @endphp
                                    <li class="mb-2 pb-2 @if (url()->current() == $widget_one_links) active @endif">
                                        <a href="{{ $widget_one_links }}" style="color: {{ get_setting('footer_text_color') }}"
                                            class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif ">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Contacts -->
            <div class="aiz-accordion-wrap">
                <div class="aiz-accordion-heading container">
                    <button class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Contacts') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3" style="color: {{ get_setting('footer_text_color') }}">
                            <li class="mb-2">
                                <p class="fs-13 mb-1">{{ translate('Address') }}</p>
                                <p class="fs-13">
                                    {{ get_setting('contact_address', null, App::getLocale()) }}</p>
                            </li>
                            <li class="mb-2">
                                <p class="fs-13 mb-1">{{ translate('Phone') }}</p>
                                <p class="fs-13">{{ get_setting('contact_phone') }}</p>
                            </li>
                            <li class="mb-2">
                                <p class="fs-13 mb-1">{{ translate('Email') }}</p>
                                <p class="">
                                    <a href="mailto:{{ get_setting('contact_email') }}" style="color: {{ get_setting('footer_text_color') }}"
                                        class="fs-13 hov-text-primary">{{ get_setting('contact_email') }}</a>
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- My Account -->
            <div class="aiz-accordion-wrap">
                <div class="aiz-accordion-heading container">
                    <button class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('My Account') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            @auth
                                <li class="mb-2 pb-2">
                                    <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                        href="{{ route('logout') }}">
                                        {{ translate('Logout') }}
                                    </a>
                                </li>
                            @else
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['user.login'], ' active') }}">
                                    <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                        href="{{ route('user.login') }}">
                                        {{ translate('Login') }}
                                    </a>
                                </li>
                            @endauth
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['purchase_history.index'], ' active') }}">
                                <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                    href="{{ route('purchase_history.index') }}">
                                    {{ translate('Order History') }}
                                </a>
                            </li>
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                                <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                    href="{{ route('wishlists.index') }}">
                                    {{ translate('My Wishlist') }}
                                </a>
                            </li>
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['orders.track'], ' active') }}">
                                <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                    href="{{ route('orders.track') }}">
                                    {{ translate('Track Order') }}
                                </a>
                            </li>
                            @if (addon_is_activated('affiliate_system'))
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['affiliate.apply'], ' active') }}">
                                    <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                        href="{{ route('affiliate.apply') }}">
                                        {{ translate('Be an affiliate partner') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Seller -->
            @if (get_setting('vendor_system_activation') == 1)
                <div class="aiz-accordion-wrap">
                    <div class="aiz-accordion-heading container">
                        <button
                            class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Seller Zone') }}</button>
                    </div>
                    <div class="aiz-accordion-panel bg-transparent">
                        <div class="container">
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['shops.create'], ' active') }}">
                                    <a style="color: {{ get_setting('footer_text_color') }}" href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}"
                                        class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif ">{{ translate('Become A Seller') }}</a>
                                </li>
                                @guest
                                    <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                        <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                            href="{{ route('seller.login') }}">
                                            {{ translate('Login to Seller Panel') }}
                                        </a>
                                    </li>
                                @endguest
                                @if (get_setting('seller_app_link') && get_setting('enable_seller_app_link') == 1)
                                    <li class="mb-2 pb-2">
                                        <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                            target="_blank" href="{{ get_setting('seller_app_link') }}">
                                            {{ translate('Download Seller App') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Delivery Boy -->
            @if (addon_is_activated('delivery_boy'))
                @if (get_setting('delivery_boy_app_link') && get_setting('enable_delivery_app_link') == 1)
                    <div class="aiz-accordion-wrap">
                        <div class="aiz-accordion-heading container">
                            <button
                                class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Delivery Boy') }}</button>
                        </div>
                        <div class="aiz-accordion-panel bg-transparent">
                            <div class="container">
                                <ul class="list-unstyled mt-3">
                                    @guest
                                        <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                            <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                                href="{{ route('deliveryboy.login') }}">
                                                {{ translate('Login to Delivery Boy Panel') }}
                                            </a>
                                        </li>
                                    @endguest
                                    @if (get_setting('delivery_boy_app_link'))
                                        <li class="mb-2 pb-2">
                                            <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                                target="_blank" href="{{ get_setting('delivery_boy_app_link') }}">
                                                {{ translate('Download Delivery Boy App') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @else   
                    @guest 
                        <div class="aiz-accordion-wrap">
                            <div class="aiz-accordion-heading container">
                                <button
                                    class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Delivery Boy') }}</button>
                            </div>
                            <div class="aiz-accordion-panel bg-transparent">
                                <div class="container">
                                    <ul class="list-unstyled mt-3">
                                        <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                            <a style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                                href="{{ route('deliveryboy.login') }}">
                                                {{ translate('Login to Delivery Boy Panel') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endguest
                @endif
            @endif

            <!-- Follow & Apps -->
            @if (get_setting('show_social_links') || get_setting('enable_play_store_link') == 1 || get_setting('enable_app_store_link') == 1)
                <div class="aiz-accordion-wrap">
                    <div class="aiz-accordion-heading container">
                        <button
                            class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Follow & Apps') }}</button>
                    </div>
                    <div class="aiz-accordion-panel bg-transparent">
                        <div class="container">
                            <div class="col-xxl-3 col-xl-4 col-lg-4">
                                @if (get_setting('show_social_links'))
                                    <!-- Social -->
                                    <h5 class="fs-14 fw-700 text-secondary text-uppercase mt-3 mt-lg-0">
                                        {{ translate('Follow Us') }}</h5>
                                    <ul class="list-inline social colored mb-4">
                                        @if (!empty(get_setting('facebook_link')))
                                            <li class="list-inline-item mr-2 pl-0">
                                                <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i
                                                        class="lab la-facebook-f"></i></a>
                                            </li>
                                        @endif
                                        @if (!empty(get_setting('twitter_link')))
                                            <li class="list-inline-item ml-2 mr-2">
                                                <a href="{{ get_setting('twitter_link') }}" target="_blank" class="x-twitter">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                        fill="#ffffff" viewBox="0 0 16 16" class="mb-2 pb-1">
                                                        <path
                                                            d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0
                                                            .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                                                    </svg>
                                                </a>
                                            </li>
                                        @endif
                                        @if (!empty(get_setting('instagram_link')))
                                            <li class="list-inline-item ml-2 mr-2">
                                                <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i
                                                        class="lab la-instagram"></i></a>
                                            </li>
                                        @endif
                                        @if (!empty(get_setting('youtube_link')))
                                            <li class="list-inline-item ml-2 mr-2">
                                                <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i
                                                        class="lab la-youtube"></i></a>
                                            </li>
                                        @endif
                                        @if (!empty(get_setting('linkedin_link')))
                                            <li class="list-inline-item ml-2 mr-2">
                                                <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin"><i
                                                        class="lab la-linkedin-in"></i></a>
                                            </li>
                                        @endif
                                    </ul>
                                @endif    

                                @if (get_setting('enable_play_store_link') == 1 || get_setting('enable_app_store_link') == 1)
                                    <h5 class="fs-14 fw-700 text-secondary text-uppercase mt-3">
                                        {{ translate('Mobile Apps') }}</h5>
                                    <div class="d-flex mt-3 mb-3">
                                        @if (get_setting('play_store_link') != null)
                                            <div class="">
                                                <a href="{{ get_setting('play_store_link') }}" target="_blank"
                                                    class="mr-2 mb-2 overflow-hidden hov-scale-img">
                                                    <img class="lazyload has-transition"
                                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                        data-src="{{ static_asset('assets/img/play.png') }}"
                                                        alt="{{ env('APP_NAME') }}" height="44">
                                                </a>
                                            </div>
                                        @endif
                                        @if (get_setting('app_store_link') != null)
                                            <div class="">
                                                <a href="{{ get_setting('app_store_link') }}" target="_blank" class="overflow-hidden hov-scale-img">
                                                    <img class="lazyload has-transition"
                                                        src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                        data-src="{{ static_asset('assets/img/app.png') }}"
                                                        alt="{{ env('APP_NAME') }}" height="44">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif    
                            </div>
                        </div>
                    </div>
                </div>
            @endif    

            <!-- Newsletter -->
            @if (get_setting('newsletter_activation'))
                <div class="aiz-accordion-wrap">
                    <div class="aiz-accordion-heading container">
                        <button
                            class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Newsletter') }}</button>
                    </div>
                    <div class="aiz-accordion-panel bg-transparent">
                        <div class="container">
                            <div class="d-lg-none newsletter-container mt-3 mb-3 p-4 rounded-2"
                                style="background-color: rgba(119, 118, 119, 0.2); backdrop-filter: blur(10px);">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5 class="m-0 fs-20 fw-700" style="color: {{ get_setting('footer_text_color') }}">
                                            {{ translate('Sign Up to our Newsletter') }}</h5>
                                        <p style="color: {{ get_setting('footer_text_color') }}" class="mt-2 mb-0 fs-13 fw-400">{{ translate('Subscribe to our newsletter for regular updates about Offers, Coupons & more') }}</p>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <form class="d-flex flex-wrap" style="gap: 8px;" method="POST" action="{{ route('subscribers.store') }}">
                                            <input class="form-control mr-sm-2 flex-shrink-0 fs-14 fw-400" type="email"
                                                placeholder="Your email address" style="width: 100%; max-width: 400px; background-color: {{ get_setting('footer_text_color') }}; @if(get_setting('footer_text_color') == 'white' || get_setting('footer_text_color') == '#ffffff' ) color: black; @else color: white; @endif"
                                                aria-label="Email">
                                            <button class="btn btn-primary flex-shrink-0 fs-14 fw-400"
                                                type="submit">{{ translate('Sign Up') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>

        <!-- Copyright & Payment Methods -->
        <div class="pt-3 pb-7 pb-xl-3 border-top"
            style="background-color: rgba(0, 0, 0, 0.1)!important; border-color: rgba(255, 255, 255, 0.2)!important">
            <div class="@if (get_setting('show_full_width_footer') == 1) layout-container mx-auto px-3 @else container @endif">
                <div class="row align-items-center py-3">
                    <!-- Copyright -->
                    <div class="col-lg-6 order-1 order-lg-0">
                        <div class="text-center text-lg-left fs-14" current-verison="" style="color: {{ get_setting('footer_text_color') }}">
                            {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}
                        </div>
                    </div>

                    <!-- Payment Method Images -->
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="text-center text-lg-right">
                            <ul class="list-inline mb-0">
                                @if (get_setting('payment_method_images') != null)
                                    @foreach (explode(',', get_setting('payment_method_images')) as $key => $value)
                                        <li class="list-inline-item mr-3">
                                            <img src="{{ uploaded_asset($value) }}" height="20"
                                                class="mw-100 h-auto" style="max-height: 20px"
                                                alt="{{ translate('payment_method') }}">
                                        </li>
                                    @endforeach
                                @endif        
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER V2 END-->