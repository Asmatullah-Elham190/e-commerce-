@if (get_setting('smart_bar_status') != 0)

        @php
            $bg_color = get_setting('smart_bar_background_color') ?? '#fff';
            $bg_design = get_setting('smart_bar_background_design') ?? 'plain';
            $opacityStyle = $bg_design == 'plain' ? 'opacity: 1;' : 'opacity: .95;';
            $button_color = get_setting('smart_bar_button_color') ?? '#0080ff';
            $button_text_color = get_setting('smart_bar_button_text_color') ?? '#fff';
            $text_color = get_setting('smart_bar_text_color') ?? '#000';
            $colors = is_string($detailedProduct->colors) ? json_decode($detailedProduct->colors, true) : $detailedProduct->colors;
            $attributes = is_string($detailedProduct->attributes) ? json_decode($detailedProduct->attributes, true) : $detailedProduct->attributes;
        @endphp

        <div id="smart-bar" class="fixed-bottom smart-bar smart-bar-mobile hidden"
                style="background-color: {{ $bg_color }}; color: {{ $text_color }}; padding: 0.8rem 1rem; {{ $opacityStyle }}">

            <div class="container {{-- -fluid smart-bar-container pr-8 pl-8 --}}">
                <div class="d-flex align-items-center justify-content-between" style="gap: 0.6rem;">

                    <!-- Product image -->
                    <div class="flex-shrink-0">
                        <img src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                            alt="{{ $detailedProduct->getTranslation('name') }}"
                            class="img-fluid img-fit w-50px h-50px w-sm-40px h-sm-40px w-md-60px h-md-60px rounded-2">
                    </div>

                    <!-- Product title -->
                    <div class="d-none d-lg-inline flex-grow-1 overflow-hidden">
                        <h6 class="mb-0 text-truncate-1 mr-2" style="color: {{ $text_color }}; font-size: 14px; font-weight:500;">
                            {{ $detailedProduct->getTranslation('name') }}
                        </h6>
                    </div>

                    <!-- Price -->
                    <div class="flex-shrink-0 d-flex align-items-center" style="gap: 0.3rem;">
                        <div class="fs-16 fs-md-18 fw-700 d-block d-sm-none"
                            style="color: {{ $text_color }}">
                            {{ home_discounted_price($detailedProduct) }}
                        </div>

                        <div class="d-none d-sm-flex align-items-center" style="gap: 0.3rem;">
                            @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                                <div class="fw-700" style="font-size: 20px; font-weight:700; color: {{ $text_color }}">
                                    {{ home_discounted_price($detailedProduct) }}
                                </div>
                                <div class="text-center d-flex flex-wrap flex-md-nowrap" style="font-size: 14px; color: {{ $text_color }}">
                                    <del class="opacity-70 d-block mx-2 flex-shrink-0">{{ home_price($detailedProduct) }}</del>
                                    <span class="fw-600 d-block flex-shrink-0">{{ discount_in_percentage($detailedProduct) }}% OFF</span>
                                </div>
                            @else
                                <div class="fw-700" style="font-size: 20px; font-weight:700; color: {{ $text_color }}">
                                    {{ home_discounted_price($detailedProduct) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Add to cart button -->
                    <div class="flex-shrink-0">
                        @if ($detailedProduct->digital == 0)
                            @if (((get_setting('product_external_link_for_seller') == 1) && ($detailedProduct->added_by == "seller") && ($detailedProduct->external_link != null)) ||
                                (($detailedProduct->added_by != "seller") && ($detailedProduct->external_link != null)))
                            @else
                                @if ( (is_array($colors) && count($colors) > 0) || (is_array($attributes) && count($attributes) > 0) )
                                    <a href="javascript:void(0)"
                                    class="btn py-1 py-sm-2 px-2 px-sm-4 mr-2 add-to-cart fw-600 rounded-0" style="background-color: {{ $button_color }}; color: {{ $button_text_color }};"
                                    @if (Auth::check() || get_Setting('guest_checkout_activation')==1) onclick="scrollToTopSection()" @else onclick="showLoginModal()" @endif>
                                        
                                        <span class="d-none d-sm-inline">{{ translate('Select Variant') }}</span>
                                        <i class="las la-sliders-h d-sm-none" style="font-size: 1.4rem;"></i>
                                    </a>
                                @else
                                    <button type="button"
                                            class="btn py-1 py-sm-2 px-2 px-sm-4 mr-2 add-to-cart fw-600 rounded-0" style="background-color: {{ $button_color }}; color: {{ $button_text_color }};"
                                            @if (Auth::check() || get_Setting('guest_checkout_activation')==1) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                                       
                                        <span class="d-none d-sm-inline">{{ translate('Add to cart') }}</span>
                                        <i class="las la-shopping-bag d-sm-none"></i>
                                    </button>
                                @endif
                            @endif

                            <button type="button" class="btn py-1 py-sm-2 px-2 px-sm-4 out-of-stock fw-600 d-none rounded-0" disabled style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                                
                                <span class="d-none d-sm-inline">{{ translate('Out of Stock') }}</span>
                                <i class="la la-cart-arrow-down d-sm-none"></i>
                            </button>
                        @elseif ($detailedProduct->digital == 1)
                            <button type="button"
                                    class="btn py-1 py-sm-2 px-2 px-sm-4 mr-2 add-to-cart fw-600 rounded-0" style="background-color: {{ $button_color }}; color: {{ $button_text_color }};"
                                    @if (Auth::check() || get_Setting('guest_checkout_activation')==1) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                                
                                <span class="d-none d-sm-inline">{{ translate('Add to cart') }}</span>
                                <i class="las la-shopping-bag d-sm-none"></i>
                            </button>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <!-- Close button -->
                        <a href="javascript:void(0)" onclick="closeSmartBar()">
                            <i style="color: {{ $button_color }};" class="la la-close la-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    @endif