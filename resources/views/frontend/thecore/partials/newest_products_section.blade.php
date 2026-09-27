@if (count($newest_products) > 0)
<section class="py-0">
    <div class="container">
        <div class="row px-3" id="newest-products-list">
            @foreach ($newest_products->shuffle()->values() as $index => $new_product)
                <div class="col-md-3 col-lg-3 col-xl-2 col-sm-4 col-6 d-flex product-card hov-animate-outline-2 d-flex justify-content-center mx-auto">
                    <div class="carousel-box has-transition rounded-2">
                        @include('frontend.'.get_setting('homepage_select').'.partials.home_product_box', ['product' => $new_product])
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
