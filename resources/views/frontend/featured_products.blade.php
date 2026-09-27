@extends('frontend.layouts.app')

@section('content')
    <section class="mb-5" style="margin-top: 2rem;">
        <div class="container">
            <h1 class="fw-700 fs-20 fs-md-24 text-dark">{{ translate('Featured Products') }}</h1>
            <!-- Products Section -->
            <div class="px-3">
                <div class="row row-cols-xxl-6 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 gutters-16 border-top border-left">
                    @foreach ($featured_products as $key => $product)
                        <div class="col text-center border-right border-bottom has-transition hov-shadow-out z-1">
                            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
