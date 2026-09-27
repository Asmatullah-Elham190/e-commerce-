<div class="border-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h6 class="d-flex align-items-center fs-16 fw-700 text-dark mr-2 mt-0 mb-0 p-0"
                title="{{ $product->getTranslation('name') }}">
                <span class="text-truncate-1  mr-2">{{ $product->getTranslation('name') }}</span>
            </h6>
        </div>
        <button onclick="closeOffcanvas()" class="border-0 p-0 bg-transparent">
            <i class="la la-close fs-24 text-gray hov-text-blue has-transition"></i>
        </button>
    </div>
</div>
<div class="right-offcanvas-body position-absolute h-100 px-30px pt-1">
    <form id="option-choice-form">
        @csrf
        <div class="row">
            <!-- Product Image gallery -->
            <div class="col-12">
                <div class="w-100 h-300px overflow-hidden rounded-2">
                    <img src="{{ get_image($product->thumbnail) }}" class="img-fit w-100 h-100" alt="{{ $product->getTranslation('name') }}">
                </div>
            
            </div>

            <!-- Product Info -->
            <div class="col-12">
                <div class="text-left">
                    <!-- Product Price & Club Point -->
                    @if (home_price($product) != home_discounted_price($product))
                        <div class="row no-gutters mt-3">
                            <div class="col-12 mb-1">
                                <div class="text-secondary fs-16 fw-600">{{ translate('Pricing') }}</div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded-2 px-20px py-20px">
                                    <div class="">
                                        <strong class="fs-16 fw-700 text-primary">
                                            {{ home_discounted_price($product) }}
                                        </strong>
                                        <del class="fs-14 opacity-60 ml-2">
                                            {{ home_price($product) }}
                                        </del>
                                        @if ($product->unit != null)
                                            <span class="opacity-70 ml-1">/{{ $product->getTranslation('unit') }}</span>
                                        @endif
                                    </div>


                                    <div class="d-flex align-items-center mt-2">
                                        @if (discount_in_percentage($product) > 0)
                                        <span
                                                class="fs-12 fw-bold text-white py-1 py-md-2 px-10px discount-badge rounded-1"
                                                style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($product) }}%</span>
                                        @endif
                                        <!-- Club Point -->
                                        @if (addon_is_activated('club_point') && $product->earn_point > 0)
                                            <div class="ml-1 d-flex justify-content-center align-items-center opacity-80 py-1 py-md-2 px-10px bg-soft-light rounded-1">
                                                <span class="fs-12 fw-bold text-orange text-uppercase">{{ translate('Club Point') }}:
                                                    {{ $product->earn_point }}</span>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row no-gutters mt-3">
                            <div class="col-12 mb-1">
                                <div class="text-secondary fs-16 fw-600">{{ translate('Pricing') }}</div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded-2 px-20px py-20px">
                                    <strong class="fs-16 fw-700 text-primary">
                                        {{ home_discounted_price($product) }}
                                    </strong>
                                    @if ($product->unit != null)
                                        <span class="opacity-70">/{{ $product->unit }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @php
                        $qty = 0;
                        foreach ($product->stocks as $key => $stock) {
                            $qty += $stock->qty;
                        }
                    @endphp

                    <!-- Product Choice options form -->
                    <div class="d-flex align-items-center justify-content-between mt-3 mb-1">
                        <div class="d-inline-flex align-items-center">
                            <h5 class="fs-16 fw-600 text-secondary mr-2 mb-0">{{ translate('Variation') }}</h5>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                    width="20px" fill="#9d9da6">
                                    <path
                                        d="M454-298h52v-230h-52v230Zm25.79-290.46q11.94 0 20.23-8.08 8.29-8.08 8.29-20.02t-8.08-20.23q-8.08-8.28-20.02-8.28T459.98-637q-8.29 8.08-8.29 20.02t8.08 20.23q8.08 8.29 20.02 8.29Zm.55 472.46q-75.11 0-141.48-28.42-66.37-28.42-116.18-78.21-49.81-49.79-78.25-116.09Q116-405.01 116-480.39q0-75.38 28.42-141.25t78.21-115.68q49.79-49.81 116.09-78.25Q405.01-844 480.39-844q75.38 0 141.25 28.42t115.68 78.21q49.81 49.79 78.25 115.85Q844-555.45 844-480.34q0 75.11-28.42 141.48-28.42 66.37-78.21 116.18-49.79 49.81-115.85 78.25Q555.45-116 480.34-116Zm-.34-52q130 0 221-91t91-221q0-130-91-221t-221-91q-130 0-221 91t-91 221q0 130 91 221t221 91Zm0-312Z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        @php
                            $sizeChart = \App\Models\SizeChart::findForProduct($product);
                            $sizeChartId = $sizeChart->id ?? 0;
                            $sizeChartName = $sizeChart->name ?? null;
                        @endphp
                        @if($sizeChartId != 0)
                        <div>
                            <button type="button" onclick='showSizeChartDetail({{ $sizeChartId }}, "{{ $sizeChartName }}")' 
                                class="fs-14 fw-400 text-blue border-0 bg-transparent d-flex align-items-center hov-opacity-80 has-transition">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15.99" height="16"
                                        viewBox="0 0 15.99 16">
                                        <path id="_4" data-name="4"
                                            d="M17.988,5.988,14.676,2.675a1.17,1.17,0,0,0-.831-.345h0a1.17,1.17,0,0,0-.825.345l-.825.825h0L10.954,4.741h0L9.713,5.982h0L8.472,7.217h0L7.232,8.469h0L5.991,9.71h0L4.75,10.95h0L3.51,12.191h0l-.831.831a1.17,1.17,0,0,0,0,1.65l3.312,3.312a1.159,1.159,0,0,0,1.65,0L17.988,7.638A1.17,1.17,0,0,0,17.988,5.988ZM6.822,17.16,3.5,13.841l.392-.41,1.241,1.241a.586.586,0,1,0,.831-.825L4.745,12.607l.416-.416L6.4,13.432a.586.586,0,0,0,.831-.825L5.991,11.366l.41-.416,2.072,2.072a.586.586,0,0,0,.825-.831L7.232,10.125l.41-.416L8.888,10.95a.583.583,0,1,0,.825-.825L8.472,8.884l.416-.416L10.129,9.71a.585.585,0,1,0,.825-.825L9.713,7.638l.416-.41,2.066,2.066a.586.586,0,1,0,.831-.825L10.954,6.4l.416-.416L12.61,7.228a.586.586,0,0,0,.825-.831L12.194,5.157l.416-.416,1.235,1.247a.586.586,0,1,0,.825-.831L13.435,3.893l.41-.392,3.318,3.318Z"
                                            transform="translate(-2.338 -2.33)" fill="#0080ff"></path>
                                    </svg>
                                </span>
                                <span class="pl-1">{{translate('Size Guide')}}</span>
                            </button>
                        </div>
                        @endif
                    </div>
                    
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="border rounded-2 border-soft-light px-20px py-10px">
                        @if ($product->digital != 1)
                            <!-- Product Choice options -->
                            @if ($product->choice_options != null)
                                @foreach (json_decode($product->choice_options) as $key => $choice)
                                    <div class="row no-gutters border-bottom-dashed border-soft-light py-2">
                                        <div class="col-12">
                                            <div class="text-dark fs-14 fw-700">
                                                {{ get_single_attribute_name($choice->attribute_id) }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="aiz-radio-inline">
                                                @foreach ($choice->values as $key => $value)
                                                    <label class="aiz-megabox pl-0 mr-2 my-1">
                                                        <input type="radio"
                                                            name="attribute_id_{{ $choice->attribute_id }}"
                                                            value="{{ $value }}"
                                                            @if ($key == 0) checked @endif>
                                                        <span
                                                            class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3 rounded-1">
                                                            {{ $value }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Color -->
                            @if ($product->colors && count(json_decode($product->colors)) > 0)
                                <div class="row no-gutters py-2">

                                    <div class="col-12 mb-1">
                                        <div class="text-dark fs-14 fw-700">{{ translate('Color') }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="aiz-radio-inline">
                                            @foreach (json_decode($product->colors) as $key => $color)
                                                <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip"
                                                    data-title="{{ get_single_color_name($color) }}">
                                                    <input type="radio" name="color"
                                                        value="{{ get_single_color_name($color) }}"
                                                        @if ($key == 0) checked @endif>
                                                    <span
                                                        class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1 rounded-1">
                                                        <span class="size-25px d-inline-block rounded"
                                                            style="background: {{ $color }};"></span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                    </div>  
                    @else
                    <!-- Quantity -->
                    <input type="hidden" name="quantity" value="1">
                    @endif
                    
                </div>
            </div>
        </div>

        <div class="border-dashed border-1  border-soft-light rounded-2 overflow-hidden mt-4 mb-1 px-20px pt-15px pb-20px">
            <div class="pb-10px">
                <div>
                    <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                        <!-- Total Price -->
                        <div class="no-gutters mr-1 mb-2" id="chosen_price_div">
                            <div class="product-price">
                                <strong id="chosen_price" class="fs-24 fw-bold">$0.00</strong>
                            </div>

                        </div>
                    </div>
                    @if ($product->digital ==0)
                    @php
                        $qty = 0;
                        foreach ($product->stocks as $key => $stock) {
                            $qty += $stock->qty;
                        }
                    @endphp
                    <div class="d-flex flex-column">
                        <p class="m-0 fs-14 fw-semibold text-blue opacity-80">
                            @if ($product->stock_visibility_state == 'quantity')
                                <span id="available-quantity">{{ $qty }}</span> {{translate('Available')}}
                            @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                                <span id="available-quantity">{{translate('In Stock')}}</span>
                            @endif
                        </p>
                        
                        <p class="m-0 fs-14 fw-400 text-gray">{{translate('Minimum order qty')}} <span class="text-dark fw-700">{{ $product->min_qty }}</span></p>
                    </div>
                    @endif
                </div>

                <div class="mt-2">
                    <!--Quantity-->
                    <div class="d-flex align-items-center flex-wrap">
                        <span class="fs-14 fw-400 text-gray pr-20px">{{ translate('QTY') }}</span>
                        <div class="d-flex align-items-center aiz-plus-minus border border  border-soft-light rounded-1">
                            <!--Decrement-->
                            <button type="button" data-type="minus" data-field="quantity" disabled="disabled"
                                class="inc-btn bg-transparent ml-2 border-0 w-30px h-35px rounded-circle  d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="1" viewBox="0 0 12 1">
                                    <g id="Group_38869" data-name="Group 38869" transform="translate(-1120.5 -819.5)">
                                        <rect id="Rectangle_16983" data-name="Rectangle 16983" width="1" height="12"
                                            transform="translate(1132.5 819.5) rotate(90)" fill="#9d9da6"></rect>
                                    </g>
                                </svg>
                            </button>
                            <input type="number" value="1" name="quantity" min="1" placeholder="1"
                                max="10" lang="en"
                                class="fs-16 text-dark fw-600 text-center w-40px h-100 mt-1 border-0 mx-2 input-number">
                            <!--Incrrement-->
                            <button type="button" data-type="plus" data-field="quantity"
                                class="dec-btn bg-transparent mr-2 border-0 w-30px h-35px rounded-circle d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                    <g id="Group_38868" data-name="Group 38868" transform="translate(-1124.5 -814)">
                                        <rect id="Rectangle_16982" data-name="Rectangle 16982" width="1" height="12"
                                            transform="translate(1130 814)" fill="#9d9da6"></rect>
                                        <rect id="Rectangle_16983" data-name="Rectangle 16983" width="1" height="12"
                                            transform="translate(1136.5 819.5) rotate(90)" fill="#9d9da6"></rect>
                                    </g>
                                </svg>

                            </button>
                        </div>
                    </div>

                     @if (get_setting('whatsapp_order') == 1)
                        @php
                            $storeName = env('APP_NAME');
                            $productTitle = $product->getTranslation('name');
                            $productUrl = URL::to('/product') . '/' . $product->slug;
                            $template = get_setting('order_messege_template');
                            $message = str_replace(
                                ['[[storeName]]', '[[productTitle]]', '[[productUrl]]'],
                                [$storeName, $productTitle, $productUrl],
                                $template
                            );
                            $whatsappNumber = preg_replace('/[^0-9]/', '', env('WHATSAPP_NUMBER'));
                            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
                        @endphp
                        @if (($product->added_by == 'seller' && get_setting('whatsapp_order_seller_prods') == 1) || ($product->added_by == 'admin'))

                        <div class="order-via-whatsapp mt-2">
                            <a href="{{ $whatsappUrl }}" target="_blank"class="d-inline-flex align-items-center  animate-underline-green has-transition">
                                <i class="lab la-whatsapp fs-20"></i>
                                <span class="fs-14 fw-400 pl-1">{{ translate('Order Via WhatsApp') }}</span>
                            </a>
                        </div>
                        @endif
                    @endif      
                </div>
            </div>

            <div class="selected-variations border-top-dashed pt-10px">
                <p class="mb-2 fs-14 fw-semibold text-dark d-flex align-items-center">
                    <span class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path id="_48116925ae3a82eb626852f6dfabe743" data-name="48116925ae3a82eb626852f6dfabe743"
                                d="M56,48a8,8,0,1,0,8,8A8,8,0,0,0,56,48ZM54.765,58.835a.53.53,0,0,1-.338.169.555.555,0,0,1-.342-.173l-2.154-2.154.685-.685,1.815,1.815,4.8-4.835.673.7Z"
                                transform="translate(-48 -48)" fill="#007bff"></path>
                        </svg>
                    </span>
                    <span class="d-block mt-1 ml-2">{{translate('Selected Product Summery')}}</span>
                </p>
                <span class="fs-14 fw-400 text-gray">{{ strlen($product->getTranslation('name')) > 120 ? substr($product->getTranslation('name'), 0, 120).'...': $product->getTranslation('name')}} - <span id="selected_variant"></span></span>
            </div>
        </div>
    </form>

    <div class="pb-5 mb-5"></div>


</div>

<div class="w-100 px-30px  position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px border-top border-soft-light" style="box-shadow: none!important;">
    <div class="d-flex flex-wrap flex-md-nowrap align-items-center mb-2">
        <button type="button" @if (Auth::check() || get_Setting('guest_checkout_activation')==1) onclick="buyNow()" @else onclick="showLoginModal()" @endif class="text-white border-0 rounded-1 fs-14 fw-bold bg-black hov-opacity-70 has-transition py-15px px-20px w-100 mb-2 mb-md-0 mr-0 mr-md-2 buy-now">{{ translate('Buy Now') }}</button>
        <button id="added_to_cart_btn" type="button" @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif class="text-blue border-0 rounded-1 fs-14 fw-bold bg-soft-blue hov-bg-blue hov-text-white py-15px px-20px w-100 add-to-cart">{{ translate('Add to cart') }} <span id="add_to_cart_count"></span></button>
    </div>
    <div class="">
        <button type="button" class="out-of-stock fw-600 d-none text-white bg-light bg-soft-white border-0 rounded-1 fs-14 fw-bold hov-opacity-70 has-transition py-15px px-20px w-100" disabled>
                <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
        </button>
    </div>
    <div class="text-right">
        <a href="{{route('product', $product->slug)}}" class="fs-14 fw-400 text-gray hov-text-blue animate-underline-blue has-transition border-0 py-1">
            <span>{{translate('Go to Product details page')}}</span>
            <i class="las la-angle-right ml-1"></i>
        </a>
    </div>
</div>

<script type="text/javascript">
    $('#option-choice-form input').on('change', function() {
        getVariantPrice();
    });
</script>
