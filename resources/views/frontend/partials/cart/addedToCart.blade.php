<div class="modal-body px-4 py-5 c-scrollbar-light">
    <!-- Item added to your cart -->
    <div class="d-flex align-items-center justify-content-center mb-4 mt-3 py-3 px-3 rounded-1" style="background: #e8fff3;">
        <span>
        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#50cd89"><path d="M480-62q-87 0-162.99-32.58-75.98-32.59-132.91-89.52-56.93-56.93-89.52-132.91Q62-393 62-480q0-88 32.58-163.49 32.59-75.48 89.52-132.41 56.93-56.93 132.91-89.52Q393-898 480-898q63 0 120 17t107 50l-87 87q-31-18-66.34-27-35.33-9-73.66-9-125.01 0-212.51 87Q180-606 180-480t87.49 213q87.49 87 212.5 87t212.51-87Q780-354 780-480q0-14.66-2-28.82-2-14.17-5-28.18l96-96q15 35 22 73.45 7 38.46 7 79.12 0 87.43-32.58 163.42-32.59 75.98-89.52 132.91-56.93 56.93-132.41 89.52Q568-62 480-62Zm-58-225L246-463l79-80 98 98 396-396 79 79-476 475Z"/></svg>
    </span>
        <span class="fs-16 fw-600 pl-2" style="color: #50cd89; margin-top: 3px;">{{ translate('Item successfully added to your cart!')}}</span>
    </div>

    <!-- Product Info -->
    <div class="media pb-4 border-bottom-dashed">
        <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($product->thumbnail_img) }}"
            class="mr-4 lazyload size-120px img-fit rounded-1" alt="Product Image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <div class="media-body text-left d-flex flex-column justify-content-between">
            <h6 class="fs-14 fw-400 text-truncate-2 lh-1-5 m-0">
                {{  $product->getTranslation('name')  }}
            </h6>
            <div class="row mt-1">
                <div class="col-12">
                    <p class="m-0 py-1">
                        <span class="fs-14 fw-400 text-gray">{{ translate('Quantity') }}</span>
                        <span class="fs-14 fw-600 text-dark">1</span>
                    </p>
                </div>
                <div class="col-sm-12">
                    <span class="fs-14 text-gray fw-400">{{ translate('Price')}}</span>
                </div>
                <div class="col-sm-12">
                    <div class="fs-16 fw-600 text-dark">
                        <strong>
                            {{ single_price(cart_product_price($cart, $product, false) * $cart->quantity) }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to shopping & Checkout buttons -->
    <div class="pt-4">
        <div class="row gutters-5">
            <div class="col-sm-6">
                <a href="{{ route('cart') }}" class="btn btn-secondary-base mb-2 mb-sm-0 btn-block rounded-1 text-white">{{ translate('View Cart')}}</a>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('cart') }}" class="btn btn-primary btn-block rounded-1">{{ translate('Proceed to Checkout')}}</a>
            </div>
            <div class="col-12">
                <button class="btn border border-gray-300  mt-2 mb-sm-0 btn-block rounded-1 text-gray-dark hov-bg-light has-transition" data-dismiss="modal">{{ translate('Back to shopping')}}</button>
            </div>
        </div>
    </div>
</div>