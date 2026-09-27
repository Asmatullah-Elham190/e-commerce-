@foreach ($products as $key => $product)
    <div class="col border-right border-bottom has-transition hov-shadow-out z-1 ">
        @if (isset($product_type) && $product_type == 'preorder_product')
            @include('preorder.frontend.product_box3', [
                'product' => $product,
            ])
        @else
            @include(
                'frontend.product_box_for_listing_page',
                ['product' => $product]
            )
        @endif
    </div>
@endforeach