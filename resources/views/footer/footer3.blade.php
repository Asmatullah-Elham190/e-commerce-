<!-- FOOTER V3 START-->
<footer class="footer-design-three" id="footer-v-three">
    <div class="pt-5 text-light footer-widget" style="background-color: {{ get_setting('footer_bg_color') }}">
        @if (get_setting('newsletter_activation'))
            <div class="@if (get_setting('show_full_width_footer') == 1) layout-container mx-auto px-3 @else container @endif d-flex justify-content-center border-bottom pb-3"
                style="border-color: rgba(0, 0, 0, 0.1)!important">
                <div class="col-lg-7 col-xl-6">
                    <h4 class="fs-30 fw-bold text-center" style="color: {{ get_setting('footer_text_color') }}">{{ 'Sign Up to our Newsletter' }} </h4>
                    <h5 class="fs-14 fw-500 mt-1 mb-3 text-center" style="color: {{ get_setting('footer_text_color') }}">
                        {{ translate('Subscribe to our newsletter for regular updates about Offers, Coupons & more') }}
                    </h5>
                    <div class="mb-3">
                        <form method="POST" action="{{ route('subscribers.store') }}">
                            @csrf
                            <div class="row gutters-10">
                                <div class="col-8">
                                    <input type="email"
                                        class="form-control rounded-0 w-100 bg-transparent footer-email-input" style="color: {{ get_setting('footer_text_color') }}; border: 1px solid {{ get_setting('footer_text_color') }}"
                                        placeholder="{{ translate('Your Email Address') }}" name="email" required>
                                </div>
                                <div class="col-4">
                                    <button type="submit"
                                        class="btn btn-primary rounded-0 w-100">{{ translate('Subscribe') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        <!-- footer widgets ========== [Accordion Fotter widgets are bellow from this]-->
        <div class="@if (get_setting('show_full_width_footer') == 1) layout-container mx-auto px-3 @else container @endif d-none d-lg-block">
            <div class="row">
                <!-- Quick links -->
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="text-center text-sm-left mt-4">
                        <!-- footer logo -->
                        <div class="mt-3 mb-4">
                            <a href="#" class="d-block">
                                <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="Logo" height="45">
                            </a>
                        </div>
                        <p class="fs-13 fw-400" style="color: {{ get_setting('footer_text_color') }}">{!! get_setting('about_us_description', null, App::getLocale()) !!}
                        </p>

                        @if (get_setting('show_social_links'))
                            <ul class="list-inline social colored mb-5 mt-4">
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
                                    <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i class="lab la-youtube"></i></a>
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
                    </div>
                </div>

                <!-- Contacts -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="text-center text-sm-left mt-4" style="color: {{ get_setting('footer_text_color') }}">
                        <h4 class="fs-14 opacity-50 text-uppercase fw-500 mb-3">{{ translate('Policy') }}</h4>
                        <ul class="list-unstyled">
                            @if (get_setting('widget_one_labels', null, App::getLocale()) != null)
                                @foreach (json_decode(get_setting('widget_one_labels', null, App::getLocale()), true) as $key => $value)
                                    @php
                                        $widget_one_links = '';
                                        if (isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
                                            $widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
                                        }
                                    @endphp
                                    <li class="mb-2">
                                        <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ $widget_one_links }}">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endforeach    
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Contacts -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="text-center text-sm-left mt-4" style="color: {{ get_setting('footer_text_color') }}">
                        <h4 class="fs-14 opacity-50 text-uppercase fw-500 mb-3">{{ translate('Contacts') }}
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <p class="fs-13 opacity-50 mb-1">{{ translate('Address') }}</p>
                                <p class="fs-13 opacity-80">{{ get_setting('contact_address', null, App::getLocale()) }}</p>
                            </li>
                            <li class="mb-2">
                                <p class="fs-13 opacity-50 mb-1">{{ translate('Phone') }}</p>
                                <p class="fs-13 opacity-80">{{ get_setting('contact_phone') }}</p>
                            </li>
                            <li class="mb-2">
                                <p class="fs-13 opacity-50 mb-1">{{ translate('Email') }}</p>
                                <p class="">
                                    <a href="mailto:{{ get_setting('contact_email') }}" style="color: {{ get_setting('footer_text_color') }}"
                                        class="fs-13 opacity-80 hov-text-primary">{{ get_setting('contact_email') }}</a>
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
                                    <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ route('logout') }}">
                                        {{ translate('Logout') }}
                                    </a>
                                </li>
                            @else
                                <li class="mb-2">
                                    <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ route('user.login') }}">
                                        {{ translate('Login') }}
                                    </a>
                                </li>
                            @endif
                            <li class="mb-2">
                                <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ route('purchase_history.index') }}">
                                    {{ translate('Order History') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ route('wishlists.index') }}">
                                    {{ translate('My Wishlist') }}
                                </a>
                            </li>
                            @php
                                $order_tracking = json_decode(get_setting('order_tracking'), true);
                            @endphp
                            @if(isset($order_tracking['enable_order_tracking']) && $order_tracking['enable_order_tracking'] == 1)
                                <li class="mb-2">
                                    <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ route('orders.track') }}">
                                        {{ translate('Track Order') }}
                                    </a>
                                </li>
                            @endif    
                            @if (addon_is_activated('affiliate_system'))
                                <li class="mb-2">
                                    <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}" href="{{ route('affiliate.apply') }}">
                                        {{ translate('Be an affiliate partner') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Seller & Delivery Boy -->
                @if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
                    <div class="col-lg-2 col-md-4 col-sm-6" style="color: {{ get_setting('footer_text_color') }}">
                        <div class="text-center text-sm-left mt-4">
                            @if (get_setting('vendor_system_activation') == 1)
                                <!-- Seller -->
                                <h4 class="fs-14 opacity-50 text-uppercase fw-500 mb-3">
                                    {{ translate('Seller Zone') }}
                                </h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <a href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}"
                                            class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " style="color: {{ get_setting('footer_text_color') }}">{{ translate('Become A Seller') }}</a>
                                    </li>
                                    @guest
                                        <li class="mb-2">
                                            <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('seller.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                                {{ translate('Login to Seller Panel') }}
                                            </a>
                                        </li>
                                    @endguest
                                    @if (get_setting('seller_app_link') && get_setting('enable_seller_app_link') == 1)
                                        <li class="mb-2">
                                            <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " target="_blank" style="color: {{ get_setting('footer_text_color') }}"
                                                href="{{ get_setting('seller_app_link') }}">
                                                {{ translate('Download Seller App') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            <!-- Delivery Boy -->
                            @if (addon_is_activated('delivery_boy'))
                                @if (get_setting('delivery_boy_app_link') && get_setting('enable_delivery_app_link') == 1)
                                    <h4 class="fs-14 opacity-50 text-uppercase fw-500 mt-4 mb-3">
                                        {{ translate('Delivery Boy') }}</h4>
                                    <ul class="list-unstyled">
                                        @guest
                                            <li class="mb-2">
                                                <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('deliveryboy.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                                    {{ translate('Login to Delivery Boy Panel') }}
                                                </a>
                                            </li>
                                        @endguest
                                        <li class="mb-2">
                                            <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " target="_blank" style="color: {{ get_setting('footer_text_color') }}"
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
                                                <a class="fs-13 opacity-80  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif " href="{{ route('deliveryboy.login') }}" style="color: {{ get_setting('footer_text_color') }}">
                                                    {{ translate('Login to Delivery Boy Panel') }}
                                                </a>
                                            </li>
                                        </ul>
                                    @endguest
                                @endif
                            @endif

                            <!-- Apps link -->
                            @if (get_setting('enable_play_store_link') == 1 || get_setting('enable_app_store_link') == 1 )
                                <h5 class="fs-14 fw-500 opacity-50 text-uppercase mt-3" style="color: {{ get_setting('footer_text_color') }}">
                                    {{ translate('Mobile Apps') }}
                                </h5>
                                <div class="d-flex flex-wrap mt-3 mb-4" style="gap: 12px;">
                                    @if (get_setting('enable_play_store_link') == 1 && get_setting('play_store_link') != null)
                                        <div>
                                            <a href="{{ get_setting('play_store_link') }}" target="_blank" class="mb-2 overflow-hidden hov-scale-img">
                                                <img class="lazyload has-transition"
                                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ static_asset('assets/img/play.png') }}"
                                                    alt="{{ env('APP_NAME') }}" height="28">
                                            </a>
                                        </div>
                                    @endif
                                    @if (get_setting('enable_app_store_link') == 1 && get_setting('app_store_link') != null)
                                        <div class="">
                                            <a href="{{ get_setting('app_store_link') }}" target="_blank" class="overflow-hidden hov-scale-img">
                                                <img class="lazyload has-transition "
                                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                    data-src="{{ static_asset('assets/img/app.png') }}"
                                                    alt="{{ env('APP_NAME') }}" height="28">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Accordion Fotter widgets -->
        <div class="d-lg-none bg-transparent" style="background-color: {{ get_setting('footer_bg_color') }}">
            
            <div class="aiz-accordion-wrap" style="color: {{ get_setting('footer_text_color') }}">
                <div class="aiz-accordion-heading container">
                    <button
                        class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Company Summary') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <!-- footer logo -->
                        <div class="mt-3 mb-4">
                            <a href="#" class="d-block">
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

            <div class="aiz-accordion-wrap" style="color: {{ get_setting('footer_text_color') }}">
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
            <div class="aiz-accordion-wrap" style="color: {{ get_setting('footer_text_color') }}">
                <div class="aiz-accordion-heading container">
                    <button class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Contacts') }}</button>
                </div>
                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2">
                                <p class="fs-13 mb-1" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Address') }}</p>
                                <p class="fs-13" style="color: {{ get_setting('footer_text_color') }}">
                                    {{ get_setting('contact_address', null, App::getLocale()) }}</p>
                            </li>
                            <li class="mb-2">
                                <p class="fs-13 mb-1" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Phone') }}</p>
                                <p class="fs-13" style="color: {{ get_setting('footer_text_color') }}">{{ get_setting('contact_phone') }}</p>
                            </li>
                            <li class="mb-2">
                                <p class="fs-13 mb-1" style="color: {{ get_setting('footer_text_color') }}">{{ translate('Email') }}</p>
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
            <div class="aiz-accordion-wrap" style="color: {{ get_setting('footer_text_color') }}">
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
                                    <a href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}"
                                        style="color: {{ get_setting('footer_text_color') }}" class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif ">{{ translate('Become A Seller') }}</a>
                                </li>
                                @guest
                                    <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                        <a class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                            style="color: {{ get_setting('footer_text_color') }}" href="{{ route('seller.login') }}">
                                            {{ translate('Login to Seller Panel') }}
                                        </a>
                                    </li>
                                @endguest
                                @if (get_setting('seller_app_link') && get_setting('enable_seller_app_link') == 1)
                                    <li class="mb-2 pb-2">
                                        <a class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                            style="color: {{ get_setting('footer_text_color') }}" target="_blank" href="{{ get_setting('seller_app_link') }}">
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
                                            <a class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                                style="color: {{ get_setting('footer_text_color') }}" href="{{ route('deliveryboy.login') }}">
                                                {{ translate('Login to Delivery Boy Panel') }}
                                            </a>
                                        </li>
                                    @endguest
                                    @if (get_setting('delivery_boy_app_link'))
                                        <li class="mb-2 pb-2">
                                            <a class="fs-13 text-sm-secondary  @if(get_setting('footer_text_color') == 'black' || get_setting('footer_text_color') == '#000000') animate-underline-black @else animate-underline-white @endif "
                                                style="color: {{ get_setting('footer_text_color') }}" target="_blank" href="{{ get_setting('delivery_boy_app_link') }}">
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
                                    class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}" >{{ translate('Delivery Boy') }}</button>
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
                            class="aiz-accordion fs-14 bg-transparent" style="color: {{ get_setting('footer_text_color') }}" >{{ translate('Follow & Apps') }}</button>
                    </div>
                    <div class="aiz-accordion-panel bg-transparent">
                        <div class="container">
                            <div class="col-xxl-3 col-xl-4 col-lg-4">
                                @if (get_setting('show_social_links'))
                                    <!-- Social -->
                                    <h5 class="fs-14 fw-700 text-uppercase mt-3 mt-lg-0" style="color: {{ get_setting('footer_text_color') }}" >
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
                                    <h5 class="fs-14 fw-700 text-uppercase mt-3" style="color: {{ get_setting('footer_text_color') }}" >
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
                                            <img src="{{ uploaded_asset($value) }}"
                                                height="20" class="mw-100 h-auto" style="max-height: 20px"
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
<!-- FOOTER V3 END-->
