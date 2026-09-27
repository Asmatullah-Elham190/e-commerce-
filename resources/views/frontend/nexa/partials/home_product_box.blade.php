@php
    $cart_added = [];
@endphp

<div class="position-relative overflow-hidden rounded-2 img-aspect-ratio-320px product-card-wrapper">
    @php
        $product_url = route('product', $product->slug);
        if ($product->auction_product == 1) {
            $product_url = route('auction-product', $product->slug);
        }
    @endphp
    <a href="{{ $product_url }}" class="d-block w-100 h-100">
        <img class="w-100 h-100 lazyload has-transition product-main-image"
            src="{{ get_image($product->thumbnail) }}" data-src="" alt="{{ $product->getTranslation('name') }}"
            title="{{ $product->getTranslation('name') }}"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <img class="w-100 h-100 lazyload has-transition product-hover-image position-absolute"
            src="{{ get_first_product_image($product->thumbnail, $product->photos) }}" title="{{ $product->getTranslation('name') }}" 
            alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
    </a>
    @php
        $badgeIndex = 0;
        $singleCustomLabel = get_custom_label('202');
        $singleCustomLabelWholesale = get_custom_label('203');
    @endphp
    <!-- Badges -->
    <div class="position-absolute d-flex flex-column align-items-start badges-wrapper">
        @if (discount_in_percentage($product) > 0)
            <span class="fs-11 fw-600 text-center rounded-pill w-auto"
                style="padding: 2px 8px; top:{{ 25 * $badgeIndex }}px; background-color:{{ $singleCustomLabel->background_color }}; color:{{ $singleCustomLabel->text_color }};">-{{ discount_in_percentage($product) }}%</span>
            @php $badgeIndex++; @endphp        
        @endif
        @if ($product->wholesale_product)
            <span class="fs-11 fw-600 rounded-pill w-auto"
                style="background-color: {{ $singleCustomLabelWholesale->background_color }}; color:{{ $singleCustomLabelWholesale->text_color }}; padding: 2px 8px; top:{{ 25 * $badgeIndex }}px;">
                    {{ translate('Wholesale') }}</span>
                @php $badgeIndex++; @endphp
        @endif
        @php
            $customLabels = get_custom_labels($product->custom_label_id);
        @endphp
        @if ($customLabels)
            @foreach ($customLabels as $key => $customLabel)
                <span class="fs-11 fw-600 rounded-pill w-auto"
                    style="background-color:{{ $customLabel->background_color }}; color:{{ $customLabel->text_color }}; padding: 2px 8px; top:{{ 25 * $badgeIndex }}px;">{{ $customLabel->text }}</span>
                    @php $badgeIndex++; @endphp
            @endforeach
        @endif
    </div>
    @if ($product->auction_product == 0)
        <!-- Wishlist & Compare (Desktop on Hover) -->
        <div class="product-action-icons d-none d-sm-flex flex-column position-absolute">
            <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})"
                class="action-icon-btn w-35px h-35px rounded-1 d-flex align-items-center justify-content-center text-gray hov-text-white bg-white hov-bg-blue has-transition"
                data-toggle="tooltip" data-placement="left" title="Add to Wishlist">
                <i class="las la-heart fs-18"></i>
            </a>
            <a href="javascript:void(0)"  onclick="addToCompare({{ $product->id }})"
                class="action-icon-btn w-35px h-35px rounded-1 d-flex align-items-center justify-content-center text-gray hov-text-white bg-white hov-bg-blue has-transition"
                data-toggle="tooltip" data-placement="left" title="Compare">
                <i class="las la-exchange-alt fs-18"></i>
            </a>
        </div>

        <!-- Add to Cart, Wishlist & Compare (Mobile) -->
        <div class="product-action-icons d-flex d-sm-none position-absolute">
            @php
                $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
                $attributes = is_string($product->attributes) ? json_decode($product->attributes, true) : $product->attributes;
            @endphp
            @if ( (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0) )
                <a href="javascript:void(0)" onclick="showAddToCartRightCanvas({{ $product->id }})"
                    class="action-icon-btn w-35px h-35px rounded-1 d-flex align-items-center justify-content-center text-gray hov-text-white bg-white hov-bg-blue has-transition"
                    data-toggle="tooltip" data-title="{{ translate('Select Variant') }}">
                    <i class="las la-sliders-h d-block fs-20"></i>
                </a>
            @else
                <a href="javascript:void(0)" @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartSingleProduct({{ $product->id }})" @else onclick="showLoginModal()" @endif
                    class="action-icon-btn w-35px h-35px rounded-1 d-flex align-items-center justify-content-center text-gray hov-text-white bg-white hov-bg-blue has-transition"
                    data-toggle="tooltip" title="{{ translate('Add to Cart') }}">
                    <i class="las la-shopping-cart fs-20"></i>
                </a>
            @endif
            <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})"
                class="action-icon-btn w-35px h-35px rounded-1 d-flex align-items-center justify-content-center text-gray hov-text-white bg-white hov-bg-blue has-transition"
                data-toggle="tooltip" title="{{ translate('Add to wishlist') }}">
                <i class="las la-heart fs-18"></i>
            </a>
            <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})"
                class="action-icon-btn w-35px h-35px rounded-1 d-flex align-items-center justify-content-center text-gray hov-text-white bg-white hov-bg-blue has-transition"
                data-toggle="tooltip" title="{{ translate('Add to compare') }}">
                <i class="las la-exchange-alt fs-18"></i>
            </a>
        </div>

        <!-- Add to Cart (Desktop on Hover) -->
        @if ( (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0) )
            <a class="product-cart-btn position-absolute w-100 h-40px text-white fs-13 fw-700 d-none d-sm-flex justify-content-center align-items-center bottom-0 right-0 left-0 z-3 bg-dark @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)" onclick="showAddToCartRightCanvas({{ $product->id }})">
                <span class="cart-btn-text fs-14 fw-600">{{ translate('Select Option') }}</span>
                <span class="cart-btn-icon">
                    <i class="las la-sliders-h" style="font-size: 1.4rem;"></i>
                </span>
            </a>
        @else 
            <a class="product-cart-btn position-absolute w-100 h-40px text-white fs-13 fw-700 d-none d-sm-flex justify-content-center align-items-center bottom-0 right-0 left-0 z-3 bg-dark @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)" @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartSingleProduct({{ $product->id }})" @else onclick="showLoginModal()" @endif>
                <span class="cart-btn-text fs-14 fw-600">{{ translate('Add to Cart') }}</span>
                <span class="cart-btn-icon">
                    <i class="las la-shopping-cart fs-20"></i>
                </span>
            </a>
        @endif

        @if ($product->auction_product == 1 && $product->auction_start_date <= strtotime('now') && $product->auction_end_date >= strtotime('now'))
            @php
                $carts = get_user_cart();
                if (count($carts) > 0) {
                    $cart_added = $carts->pluck('product_id')->toArray();
                }
                $highest_bid = $product->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                $gst_rate = gst_applicable_product_rate($product->id);
            @endphp
            <a class="product-cart-btn position-absolute w-100 h-40px text-white fs-13 fw-700 d-none d-sm-flex justify-content-center align-items-center bottom-0 right-0 left-0 z-3 bg-dark @if (in_array($product->id, $cart_added)) active @endif"
                href="javascript:void(0)" onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }}, {{ $gst_rate }})">
                <span class="cart-btn-text fs-14 fw-600">{{ translate('Place Bid') }}</span>
                <span class="cart-btn-icon">
                    <i class="las la-2x la-gavel"></i>
                </span>
            </a>    
        @endif

    @endif
</div>

<!-- Product Name -->
<a href="{{ $product_url }}" class="fs-14 fw-400 text-reset d-block mt-3 product-title hov-text-blue has-transition" title="{{ $product->getTranslation('name') }}">
    {{ $product->getTranslation('name') }}
</a>
<!-- Price -->
@if ($product->auction_product == 0)
    <p class="mt-2 mb-0">
        <span class="fs-13 fs-md-16 text-dark fw-bold mr-1">{{ home_discounted_base_price($product) }}</span>
        @if (home_base_price($product) != home_discounted_base_price($product))
            <del class="fs-11 fs-md-14 text-gray fw-400">{{ home_base_price($product) }}</del>
        @endif
    </p>
@endif
@if ($product->auction_product == 1)
    <p class="mt-2 mb-0">
        <span class="fs-13 fs-md-16 text-dark fw-bold mr-1">{{ single_price($product->starting_bid) }}</span>
    </p>
@endif