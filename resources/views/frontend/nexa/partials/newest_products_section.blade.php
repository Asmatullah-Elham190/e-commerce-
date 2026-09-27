@if (count($newest_products) > 0)

    @if(request()->page == null || request()->page == 1)
        <div class="products-wrapper-grid" id="newest-products-list">
    @endif

        @foreach ($newest_products as $index => $new_product)
            <div class="grid-item single-product-item">
                @include('frontend.' . get_setting('homepage_select') . '.partials.home_product_box', [
                    'product' => $new_product
                ])
            </div>
        @endforeach

    @if(request()->page == null || request()->page == 1)
        </div>
    @endif

@endif