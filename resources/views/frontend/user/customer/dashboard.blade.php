@extends('frontend.layouts.user_panel')

@section('panel_content')

    @php
        $welcomeCoupon = ifUserHasWelcomeCouponAndNotUsed();
    @endphp
    @if($welcomeCoupon)
        <div class="alert alert-primary align-items-center border d-flex flex-wrap justify-content-between" style="border-color: #3490F3 !important;">
            @php
                $discount = $welcomeCoupon->discount_type == 'amount' ? single_price($welcomeCoupon->discount) : $welcomeCoupon->discount.'%';
            @endphp
            <div class="fw-400 fs-14" style="color: #3490F3 !important;">
                {{ translate('Welcome Coupon') }} <strong>{{ $discount }}</strong> {{ translate('Discount on your Purchase Within') }} <strong>{{ $welcomeCoupon->validation_days }}</strong> {{ translate('days of Registration') }}
            </div>
            <button class="btn btn-sm mt-3 mt-lg-0 rounded-4" onclick="copyCouponCode('{{ $welcomeCoupon->coupon_code }}')" style="background-color: #3490F3; color: white;" >{{ translate('Copy coupon Code') }}</button>
        </div>
    @endif

    <div class="row gutters-16">
        <!-- Wallet summary -->
        @if (get_setting('wallet_system') == 1)
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="h-100" style="background-image: url('{{ static_asset("assets/img/wallet-bg.png") }}'); background-size: cover; background-position: center center;">
                <div class="p-4 h-100 w-100 w-xl-50">
                    <p class="fs-14 fw-400 text-gray mb-3">{{ translate('Wallet Balance') }}</p>
                    <h1 class="fs-30 fw-700 text-white ">{{ single_price(Auth::user()->balance) }}</h1>
                    <hr class="border border-dashed border-white opacity-40 ml-0 mt-4 mb-4">
                    @php
                        $last_recharge = get_user_last_wallet_recharge();
                    @endphp
                    <p class="fs-14 fw-400 text-gray mb-1">{{ translate('Last Recharge') }} <strong>{{ $last_recharge ? date('d.m.Y', strtotime($last_recharge->created_at)) : '' }}</strong></p>
                    <h3 class="fs-20 fw-700 text-white ">{{ $last_recharge ? single_price($last_recharge->amount) : 0 }}</h3>
                    <button class="btn btn-block border border-soft-light hov-bg-dark text-white mt-4 mb-2 py-3" onclick="show_wallet_modal()" style="border-radius: 30px; background: rgba(255, 255, 255, 0.1);">
                        <i class="la la-plus fs-18 fw-700 mr-2"></i>
                        {{ translate('Recharge Wallet') }}
                    </button>
                    <a href="javascript:void(0);" class="fs-14 fw-400 text-white mb-3" onclick="show_make_wallet_recharge_modal()">
                        {{ translate('Offline Recharge Wallet') }}
                    </a>

                </div>
            </div>
        </div>
        @endif

        <div class="col mb-4">
            <div class="h-100">
                <div class="row h-100 @if(get_setting('wallet_system') != 1 && addon_is_activated('club_point')) row-cols-md-2 @endif row-cols-1">
                    <!-- Expenditure summary -->
                    <div class="col">
                        <div class="p-4 bg-primary @if(!addon_is_activated('club_point')) h-100 @endif" style="margin-bottom: 2rem;">
                            <div class="d-flex align-items-center pb-4 ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <g id="Group_25000" data-name="Group 25000" transform="translate(-926 -614)">
                                    <rect id="Rectangle_18646" data-name="Rectangle 18646" width="48" height="48" rx="24" transform="translate(926 614)" fill="rgba(255,255,255,0.5)"/>
                                    <g id="Group_24786" data-name="Group 24786" transform="translate(701.466 93)">
                                        <path id="Path_32311" data-name="Path 32311" d="M122.052,10V8.55a.727.727,0,1,0-1.455,0V10a2.909,2.909,0,0,0-2.909,2.909v.727A2.909,2.909,0,0,0,120.6,16.55h1.455A1.454,1.454,0,0,1,123.506,18v.727a1.454,1.454,0,0,1-1.455,1.455H120.6a1.454,1.454,0,0,1-1.455-1.455.727.727,0,1,0-1.455,0,2.909,2.909,0,0,0,2.909,2.909V23.1a.727.727,0,1,0,1.455,0V21.641a2.909,2.909,0,0,0,2.909-2.909V18a2.909,2.909,0,0,0-2.909-2.909H120.6a1.454,1.454,0,0,1-1.455-1.455v-.727a1.454,1.454,0,0,1,1.455-1.455h1.455a1.454,1.454,0,0,1,1.455,1.455.727.727,0,0,0,1.455,0A2.909,2.909,0,0,0,122.052,10" transform="translate(127.209 529.177)" fill="#fff"/>
                                    </g>
                                    </g>
                                </svg>
                                <div class="ml-3 d-flex flex-column justify-content-between">
                                    <span class="fs-14 fw-400 text-white mb-1">{{ translate('Total Expenditure') }}</span>
                                    <span class="fs-20 fw-700 text-white">{{ single_price(get_user_total_expenditure()) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('purchase_history.index') }}" class="fs-12 text-white">
                                {{ translate('View Order History') }}
                                <i class="las la-angle-right fs-14"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Club Point summary -->
                    @if (addon_is_activated('club_point'))
                    <div class="col">
                        <div class="p-4 bg-secondary-base">
                            <div class="d-flex align-items-center pb-4 ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                    <g id="Group_25000" data-name="Group 25000" transform="translate(-926 -614)">
                                    <rect id="Rectangle_18646" data-name="Rectangle 18646" width="48" height="48" rx="24" transform="translate(926 614)" fill="rgba(255,255,255,0.5)"/>
                                    <g id="Group_24786" data-name="Group 24786" transform="translate(701.466 93)">
                                        <path id="Path_2961" data-name="Path 2961" d="M221.069,0a8,8,0,1,0,8,8,8,8,0,0,0-8-8m0,15a7,7,0,1,1,7-7,7,7,0,0,1-7,7" transform="translate(27.466 537)" fill="#fff"/>
                                        <path id="Union_11" data-name="Union 11" d="M16425.393,420.226l-3.777-5.039a.42.42,0,0,1-.012-.482l1.662-2.515a.416.416,0,0,1,.313-.186l0,0h4.26a.41.41,0,0,1,.346.19l1.674,2.515a.414.414,0,0,1-.012.482l-3.777,5.039a.413.413,0,0,1-.338.169A.419.419,0,0,1,16425.393,420.226Zm-2.775-5.245,3.113,4.148,3.109-4.148-1.32-1.983h-3.592Z" transform="translate(-16177.195 129)" fill="#fff"/>
                                    </g>
                                    </g>
                                </svg>
                                <div class="ml-3 d-flex flex-column justify-content-between">
                                    <span class="fs-14 fw-400 text-white mb-1">{{ translate('Total Club Points') }}</span>
                                    <span class="fs-20 fw-700 text-white">{{ get_user_total_club_point() }}</span>
                                </div>
                            </div>
                            <a href="{{ route('earnng_point_for_user') }}" class="fs-12 text-white">
                                {{ translate('Convert Club Points') }}
                                <i class="las la-angle-right fs-14"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row gutters-16 mt-2">

        <!-- count summary -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="px-4 bg-white border h-100">
                <!-- Cart summary -->
                <div class="d-flex align-items-center py-4 border-bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -427)">
                        <path id="Path_32314" data-name="Path 32314" d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z" transform="translate(1367 427)" fill="#d43533"/>
                        <g id="Group_24770" data-name="Group 24770" transform="translate(1382.999 443)">
                            <path id="Path_25692" data-name="Path 25692" d="M294.507,424.89a2,2,0,1,0,2,2A2,2,0,0,0,294.507,424.89Zm0,3a1,1,0,1,1,1-1A1,1,0,0,1,294.507,427.89Z" transform="translate(-289.508 -412.89)" fill="#fff"/>
                            <path id="Path_25693" data-name="Path 25693" d="M302.507,424.89a2,2,0,1,0,2,2A2,2,0,0,0,302.507,424.89Zm0,3a1,1,0,1,1,1-1A1,1,0,0,1,302.507,427.89Z" transform="translate(-289.508 -412.89)" fill="#fff"/>
                            <g id="LWPOLYLINE">
                            <path id="Path_25694" data-name="Path 25694" d="M305.43,416.864a1.5,1.5,0,0,0-1.423-1.974h-9a.5.5,0,0,0,0,1h9a.467.467,0,0,1,.129.017.5.5,0,0,1,.354.611l-1.581,6a.5.5,0,0,1-.483.372h-7.462a.5.5,0,0,1-.489-.392l-1.871-8.433a1.5,1.5,0,0,0-1.465-1.175h-1.131a.5.5,0,1,0,0,1h1.043a.5.5,0,0,1,.489.391l1.871,8.434a1.5,1.5,0,0,0,1.465,1.175h7.55a1.5,1.5,0,0,0,1.423-1.026Z" transform="translate(-289.508 -412.89)" fill="#fff"/>
                            </g>
                        </g>
                        </g>
                    </svg>
                    <div class="ml-3 d-flex flex-column justify-content-between">
                        @php
                            $cart = get_user_cart();
                        @endphp
                        <span class="fs-20 fw-700 mb-1">{{ count($cart) > 0 ? sprintf("%02d", count($cart)) : 0 }}</span>
                        <span class="fs-14 fw-400 text-secondary">{{ translate('Products in Cart') }}</span>
                    </div>
                </div>

                <!-- Wishlist summary -->
                <div class="d-flex align-items-center py-4 border-bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -499)">
                        <path id="Path_32309" data-name="Path 32309" d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z" transform="translate(1367 499)" fill="#3490f3"/>
                        <g id="Group_24772" data-name="Group 24772" transform="translate(1383 515)">
                            <g id="Wooden" transform="translate(0 1)">
                            <path id="Path_25676" data-name="Path 25676" d="M290.82,413.6a4.5,4.5,0,0,0-6.364,0l-.318.318-.318-.318a4.5,4.5,0,1,0-6.364,6.364l6.046,6.054a.9.9,0,0,0,1.272,0l6.046-6.054A4.5,4.5,0,0,0,290.82,413.6Zm-.707,5.657-5.975,5.984-5.975-5.984a3.5,3.5,0,1,1,4.95-4.95l.389.389a.9.9,0,0,0,1.272,0l.389-.389a3.5,3.5,0,1,1,4.95,4.95Z" transform="translate(-276.138 -412.286)" fill="#fff"/>
                            </g>
                            <rect id="Rectangle_1603" data-name="Rectangle 1603" width="16" height="16" transform="translate(0)" fill="none"/>
                        </g>
                        </g>
                    </svg>
                    <div class="ml-3 d-flex flex-column justify-content-between">
                        <span class="fs-20 fw-700 mb-1">{{ count(Auth::user()->wishlists) > 0 ? sprintf("%02d", count(Auth::user()->wishlists)) : 0 }}</span>
                        <span class="fs-14 fw-400 text-secondary">{{ translate('Products in Wishlist') }}</span>
                    </div>
                </div>

                <!-- Order summary -->
                <div class="d-flex align-items-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -576)">
                        <path id="Path_32315" data-name="Path 32315" d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z" transform="translate(1367 576)" fill="#85b567"/>
                        <path id="_2e746ddacacf202af82cf4480bae6173" data-name="2e746ddacacf202af82cf4480bae6173" d="M11.483,3h-.009a.308.308,0,0,0-.1.026L4.26,6.068A.308.308,0,0,0,4,6.376V15.6a.308.308,0,0,0,.026.127v0l.009.017a.308.308,0,0,0,.157.147l7.116,3.042a.338.338,0,0,0,.382,0L18.8,15.9a.308.308,0,0,0,.189-.243q0-.008,0-.017s0-.01,0-.015,0-.01,0-.015,0,0,0,0V6.376a.308.308,0,0,0-.255-.306L11.632,3.031l-.007,0a.308.308,0,0,0-.05-.017l-.009,0-.022,0h-.062Zm.014.643L13,4.287,6.614,7.02,6.6,7.029,5.088,6.383,11.5,3.643Zm2.29.979,1.829.782L9.108,8.188a.414.414,0,0,0-.186.349v3.291l-.667-1a.308.308,0,0,0-.393-.1l-.786.392V7.493l6.712-2.87ZM16.4,5.738l1.509.645L11.5,9.124,9.99,8.48l6.39-2.733.018-.009ZM4.615,6.85l1.846.789v3.975a.308.308,0,0,0,.445.275l.987-.494,1.064,1.595v0a.308.308,0,0,0,.155.14h0l.027.009a.308.308,0,0,0,.057.012h.036l.036,0,.025,0,.018,0,.015,0a.308.308,0,0,0,.05-.022h0a.308.308,0,0,0,.156-.309V8.955l1.654.707v8.56L4.615,15.411Zm13.765,0v8.56L11.8,18.223V9.662Z" transform="translate(1379.5 588.5)" fill="#fff" stroke="#fff" stroke-width="0.25" fill-rule="evenodd"/>
                        </g>
                    </svg>
                    <div class="ml-3 d-flex flex-column justify-content-between">
                        @php
                           $total =  get_user_total_ordered_products();
                        @endphp
                        <span class="fs-20 fw-700 mb-1">{{ $total > 0 ? sprintf("%02d", $total) : 0 }}</span>
                        <span class="fs-14 fw-400 text-secondary">{{ translate('Total Products Ordered') }}</span>
                    </div>
                </div>

            </div>
        </div>

       

        <!-- Default Shipping Address -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="p-4 border h-100">
                <h6 class="fw-700 mb-3 text-dark">{{ translate('Default Shipping Address') }}</h6>
                @if(Auth::user()->addresses != null)
                    @php
                        $address = Auth::user()->addresses->where('set_default', 1)->first();
                    @endphp
                    @if($address != null)
                        <ul class="list-unstyled mb-5">
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->address }},</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span> {{ $address->area ? $address->area->name . ',' : '' }}</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->postal_code }} - {{ $address->city->name }},</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->state ? $address->state->name : '' }}</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->country->name }}.</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->phone }}</span></li>
                        </ul>
                    @endif
                @endif
                <button class="btn btn-dark btn-block fs-14 fw-500" onclick="add_new_address()" style="border-radius: 25px;">
                    <i class="la la-plus fs-18 fw-700 mr-2"></i>
                    {{ translate('Add New Address') }}
                </button>
            </div>
        </div>

        <!--Billing Address -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="p-4 border h-100">
                <h6 class="fw-700 mb-3 text-dark">{{ translate('Default Billing Address') }}</h6>
                @if(Auth::user()->addresses != null)
                    @php
                        $address = Auth::user()->addresses->where('set_billing', 1)->first();
                    @endphp
                    @if($address != null)
                        <ul class="list-unstyled mb-5">
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->address }},</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span> {{ $address->area ? $address->area->name . ',' : '' }}</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->postal_code }} - {{ $address->city->name }},</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->state ? $address->state->name : '' }}</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->country->name }}.</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->phone }}</span></li>
                        </ul>
                    @endif
                @endif
                
            </div>
        </div>

         <!-- Purchased Package -->
        @if (get_setting('classified_product'))
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="p-4 border h-100">
                <h6 class="fw-700 mb-3 text-dark">{{ translate('Purchased Package') }}</h6>
                @php
                    $customer_package = get_single_customer_package(Auth::user()->customer_package_id);
                @endphp
                @if($customer_package != null)
                    <img src="{{ uploaded_asset($customer_package->logo) }}" class="img-fluid mb-4 h-70px"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    <p class="fs-14 fw-700 mb-3 text-primary">{{ translate('Current Package') }}: {{ $customer_package->getTranslation('name') }}</p>
                    <p class="mb-2 d-flex justify-content-between">
                        <span class="text-secondary">{{ translate('Product Upload') }}</span>
                        <span class="fw-700">{{ $customer_package->product_upload }} {{ translate('Times')}}</span>
                    </p>
                    <p class="mb-3 d-flex justify-content-between">
                        <span class="text-secondary">{{ translate('Product Upload Remains') }}</span>
                        <span class="fw-700">{{ Auth::user()->remaining_uploads }} {{ translate('Times')}}</span>
                    </p>
                @else
                    <span class="fs-14 fw-700 mb-4 text-primary">{{translate('Package Not Found')}}</span>
                @endif
                <a href="{{ route('customer_packages_list_show') }}" class="btn btn-primary btn-block fs-14 fw-500" style="border-radius: 25px;">{{ translate('Upgrade Package') }}</a>
            </div>
        </div>
        @endif

    </div>

    <div class="row align-items-center mb-2 mt-1">
        <div class="col-6">
            <h3 class=" mb-0 fs-14 fs-md-16 fw-700 text-dark">{{ translate('My Wishlist')}}</h3>
        </div>
        <div class="col-6 text-right">
            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary" href="{{ route('wishlists.index') }}">{{ translate('View All') }}</a>
        </div>
    </div>
    @php
        $wishlists = get_user_wishlist();
    @endphp
    @if (count($wishlists) > 0)
        <div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 gutters-16 border-top border-left mx-1 mx-md-0 mb-4">
            @foreach($wishlists->take(5) as $key => $wishlist)
                @if ($wishlist->product != null)
                    <div class="aiz-card-box col py-3 text-center border-right border-bottom has-transition hov-shadow-out z-1" id="wishlist_{{ $wishlist->id }}">
                        <div class="position-relative h-140px h-md-200px img-fit overflow-hidden mb-3">
                            <!-- Image -->
                            <a href="{{ route('product', $wishlist->product->slug) }}" class="d-block h-100 position-relative image-hover-effect">
                                <img src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}" class="lazyload mx-auto img-fit product-main-image"
                                    title="{{ $wishlist->product->getTranslation('name') }}">

                                <img class="lazyload mx-auto img-fit product-hover-image position-absolute"
                                    src="{{ get_first_product_image($wishlist->product->thumbnail_img, $wishlist->product->photos) }}"
                                    alt="{{ $wishlist->product->getTranslation('name') }}"
                                    title="{{ $wishlist->product->getTranslation('name') }}">
                            </a>
                            <!-- Remove from wishlisht -->
                            <div class="absolute-top-right aiz-p-hov-icon">
                                <a href="javascript:void(0)" onclick="removeFromWishlist({{ $wishlist->id }})" data-toggle="tooltip" data-title="{{ translate('Remove from wishlist') }}" data-placement="left">
                                    <i class="la la-trash"></i>
                                </a>
                            </div>
                            <!-- add to cart -->
                            @php
                                $colors = is_string($wishlist->product->colors) ? json_decode($wishlist->product->colors, true) : $wishlist->product->colors;
                                $attributes = is_string($wishlist->product->attributes) ? json_decode($wishlist->product->attributes, true) : $wishlist->product->attributes;
                            @endphp
                
                            @if ( (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0) )
                                <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-none d-sm-flex flex-column justify-content-center align-items-center"
                                    href="javascript:void(0)" onclick="showAddToCartRightCanvas({{ $wishlist->product->id }})">
                                    <span class="cart-btn-text">
                                        {{ translate('Select Option') }}
                                    </span>
                                    <span><i class="las la-sliders-h" style="font-size: 1.4rem;"></i></span>
                                </a>
                            @else
                                <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-none d-sm-flex flex-column justify-content-center align-items-center"
                                    href="javascript:void(0)" @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartSingleProduct({{ $wishlist->product->id }})" @else onclick="showLoginModal()" @endif>
                                    <span class="cart-btn-text">
                                        {{ translate('Add to Cart') }}
                                    </span>
                                    <span><i class="las la-2x la-shopping-cart"></i></span>
                                </a> 
                            @endif
                        </div>
                        <!-- Product Name -->
                        <h5 class="fs-14 mb-0 lh-1-5 fw-400 text-truncate-2 mb-3">
                            <a href="{{ route('product', $wishlist->product->slug) }}" class="text-reset hov-text-primary"
                                title="{{ $wishlist->product->getTranslation('name') }}">{{ $wishlist->product->getTranslation('name') }}</a>
                        </h5>
                        <!-- Price -->
                        <div class="fs-14">
                            <span class="fw-600 text-primary">{{ home_discounted_base_price($wishlist->product) }}</span>
                            @if(home_base_price($wishlist->product) != home_discounted_base_price($wishlist->product))
                                <del class="opacity-60 ml-1">{{ home_base_price($wishlist->product) }}</del>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col">
                <div class="text-center bg-white p-4 border">
                    <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                    <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet")}}</h5>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('modal')
    <!-- Wallet Recharge Modal -->
    @include('frontend.partials.wallet_modal')

    <!-- Offline Wallet Recharge Modal -->
    <div class="modal fade" id="offline_wallet_recharge_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Recharge Wallet') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_wallet_recharge_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Address modal Modal -->
    @include('frontend.partials.address.address_modal')

    <div class="modal fade" id="varification_pending_modal" tabindex="-1" role="dialog"
        aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content text-center p-4">

                @if(addon_is_activated('portfolio_system') && auth()->user()->verification_status == 0 && get_setting('customer_verification') == 1)
                <h4 class="mb-3">{{ translate('Complete your verification') }}</h4>

                <p class="text-muted">
                    {{ translate('Your account is pending verification. Please complete verification to continue.') }}
                </p>

                <a href="javascript:void(0)"
                    class="btn btn-primary"
                    id="openCustomerVerification">
                        {{ translate('Click here to verify') }}
                </a>
                 @else
                <h4 class="mb-3">{{ translate('Verification is under review') }}</h4>
                <p class="text-muted">
                    {{ translate('Your submitted verification documents are under review. We will notify you once the review is complete.') }}
                </p>
                @endif

                <!--  go to home or contact us page -->
                <div class="mt-3 d-flex justify-content-center gap-2">
                    <a href="{{ route('home') }}" class="mr-2">
                        {{ translate('Go to Home') }}
                    </a> |
                    <a href="{{ route('custom-pages.show_custom_page', 'contact-us') }}" class="ml-2">
                        {{ translate('Contact Us') }}
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div id="customer_verification_modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold h6">{{translate('Customer Verification')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light customer-verification-form">

                    <form action="{{ route('user.verify.update') }}" method="POST" enctype="multipart/form-data" id="customer_verification_modal">
                        @csrf
                        <div class="form-group">
                            <label for="valid-id-card" class="fs-13 fw-bold">{{translate('Valid ID Card')}}</label>
                            <div class="input-group rounded-2 bg-soft-light overflow-hidden">
                                <div class="input-group-prepend rounded-2 bg-soft-light">
                                    <span class="input-group-text  bg-soft-light">{{translate('Browse')}}</span>
                                </div>
                                <div class="custom-file">
                                    <label class="custom-file-label  cursor-pointer">
                                        <input type="file" class="custom-file-input preview-input " data-preview="#id_card_preview" name="id_card" id="id_card"  accept=".jpg,.jpeg,.png,.bmp,application/pdf" >
                                        <span class="custom-file-name  cursor-pointer">{{ translate('Choose file') }}</span>
                                    </label>
                                </div>
                            </div>
                           
                        </div>
                        
                        <div id="id_card_preview" class="mb-2"></div>

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid-id-card" class="fs-13 fw-bold">{{translate('Photo')}}</label>
                                    <div class="input-group rounded-2 bg-soft-light overflow-hidden">
                                        <div class="input-group-prepend rounded-2 bg-soft-light">
                                            <span class="input-group-text  bg-soft-light">{{translate('Browse')}}</span>
                                        </div>
                                        <div class="custom-file">
                                            <label class="custom-file-label  cursor-pointer">
                                                <input type="file" class="custom-file-input preview-input " data-preview="#customer_photo_preview" name="customer_photo" id="customer_photo"  accept=".jpg,.jpeg,.png,.bmp,application/pdf" >
                                                <span class="custom-file-name  cursor-pointer">{{ translate('Choose file') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                
                                </div>

                                <div id="customer_photo_preview" class="mb-2"></div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="fs-13 fw-bold">{{translate('Live Selfie Photo')}}</label>

                                    <!-- Camera Preview -->
                                    <video id="selfieVideo" class="w-100 rounded d-none" autoplay playsinline></video>

                                    <!-- Captured Image -->
                                    <canvas id="selfieCanvas" class="w-100 rounded d-none mt-2"></canvas>

                                    <!-- Hidden input to send selfie -->
                                    <input type="hidden" name="live_selfie" id="liveSelfieInput">

                                    <div class="text-center">
                                        <button type="button" id="openCameraBtn" class="btn btn-outline-primary w-100">
                                            {{translate('Take Selfie')}}
                                        </button>

                                        <button type="button" id="captureSelfieBtn"
                                            class="btn btn-success d-none mt-2">
                                            {{translate('Capture')}}
                                        </button>

                                        <button type="button" id="retakeSelfieBtn"
                                            class="btn btn-warning d-none mt-2">
                                            {{translate('Retake')}}
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <button type="submit" value="Create Account"
                                class="d-block w-100 border-0 fs-13 fw-bold btn btn-primary rounded-2 py-3">
                            {{ translate('Submit') }}
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('frontend.partials.address.address_js')

     @if(get_active_countries()->count() == 1)
    <script>
        $(document).ready(function() {
            get_states(@json(get_active_countries()[0]->id))
        });
    </script>
    @endif

    <script type="text/javascript">
        function removeFromWishlist(id){
            $.post('{{ route('wishlists.remove') }}',{_token:'{{ csrf_token() }}', id:id}, function(data){
                $('#wishlist').html(data);
                $('#wishlist_'+id).hide();
                AIZ.plugins.notify('success', '{{ translate("Item has been renoved from wishlist") }}');
            })
        }
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif

    <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }

        function show_make_wallet_recharge_modal() {
            $.post('{{ route('offline_wallet_recharge_modal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#offline_wallet_recharge_modal_body').html(data);
                $('#offline_wallet_recharge_modal').modal('show');
            });
        }
    </script>

@if(addon_is_activated('portfolio_system') && auth()->user()->verification_status == 0 && get_setting('customer_verification') == 1)
    <script>
        $(document).ready(function () {
            $('body').addClass('verification-lock');

            $('#varification_pending_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#varification_pending_modal').modal('show');
        });
    </script>
@endif


<script>
     $(document).ready(function () {

        $('#openCustomerVerification').on('click', function () {
            // Hide first modal
            //$('#varification_pending_modal').modal('hide');
            // Show verification form modal
            $('#customer_verification_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#customer_verification_modal').modal('show');
        });

    });
</script>

<script>
    let selfieStream;
    const video = document.getElementById('selfieVideo');
    const canvas = document.getElementById('selfieCanvas');
    const ctx = canvas.getContext('2d');

    $('#openCameraBtn').on('click', function () {
        takeSelfie();
    });

    $('#captureSelfieBtn').on('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0);

        // Stop camera
        if (selfieStream) {
            selfieStream.getTracks().forEach(track => track.stop());
        }

        const imageData = canvas.toDataURL('image/png');
        $('#liveSelfieInput').val(imageData);

        video.classList.add('d-none');
        canvas.classList.remove('d-none');

        $('#captureSelfieBtn').addClass('d-none');
        $('#retakeSelfieBtn').removeClass('d-none');
    });

    $('#retakeSelfieBtn').on('click', function () {
        //canvas.classList.add('d-none');
        $('#retakeSelfieBtn').addClass('d-none');
        $('#openCameraBtn').removeClass('d-none');
        $('#liveSelfieInput').val('');
        
        takeSelfie();
    });

    async function takeSelfie() {
        try {
            selfieStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: "user" },
                audio: false
            });

            video.srcObject = selfieStream;
            
            // Ensure video is visible and canvas is hidden
            video.classList.remove('d-none');
            canvas.classList.add('d-none'); 

            $('#openCameraBtn').addClass('d-none');
            $('#captureSelfieBtn').removeClass('d-none');
            $('#retakeSelfieBtn').addClass('d-none'); // Ensure retake is hidden

        } catch (e) {
            alert('Camera access denied or not supported!');
        }
    }


 $(document).on('change', '.preview-input', function () {
        let input = this;
        let previewBox = $($(this).data('preview'));
        let fileName = input.files[0]?.name || '';

        $(this).next('.custom-file-label').html(fileName);

        previewBox.html('');

        if (input.files && input.files[0]) {
            let file = input.files[0];
            let fileType = file.type;
            if (fileType.startsWith('image/')) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    previewBox.html(
                        '<img src="' + e.target.result + '" class="preview-img img-fluid h-100px w-100-px">'
                    );
                };
                reader.readAsDataURL(file);
            }
            else if (fileType === 'application/pdf') {
                previewBox.html(`
                    <div class="pdf-preview d-flex align-items-end justify-content-center border rounded text-center" 
                        style="width:100px; height:100px; background-color:#f8f9fa; position:relative; font-size:40px; color:#e74c3c;">
                        <i class="las la-file-pdf" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); opacity:0.2; font-size:50px;"></i>
                        <small class="text-truncate-1 fs-10" style="position:absolute; bottom:5px; left:0;" title="${fileName}">${fileName}</small>
                    </div>
                `);
            }
        }
    });

     $('#customer_verification_modal').on('hide.bs.modal', function (e) {
        // This runs BEFORE modal starts closing

        // Force stop camera immediately
        if (selfieStream) {
            selfieStream.getTracks().forEach(track => track.stop());
            selfieStream = null;
        }

        // Clear video source right away
        const video = document.getElementById('selfieVideo');
        if (video && video.srcObject) {
            video.srcObject = null;
        }
    });
</script>

@endsection
