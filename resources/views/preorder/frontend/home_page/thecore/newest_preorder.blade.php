@php
    $newest_preorder_products = \App\Models\PreorderProduct::where('is_published', 1)
    ->where(function ($query) {
        $query->whereHas('user', function ($q) {
            $q->where('user_type', 'admin');
        })->orWhereHas('user.shop', function ($q) {
            $q->where('verification_status', 1);
        });
    })
    ->latest()
    ->limit(12)
    ->get()
    ->shuffle()->values();
@endphp
@if (count($newest_preorder_products) > 0)
    <section class="py-4">
        <div class="container">
            <div class="border border-2 border-light rounded-75 pt-32px pb-4">
                <!-- Top Section -->
                <div class="d-flex mb-3 ml-2 align-items-baseline justify-content-between px-3">
                    <!-- Title -->
                    <h3 class="fs-16 fw-700 mb-0">
                        <span class="">{{ translate('Newest Preorder Products') }}</span>
                    </h3>
                    <!-- Links -->
                    <a type="button" class="arrow-next text-white bg-dark view-more-slide-btn d-flex align-items-center" href="{{ route('all_preorder_products') }}">
                        <span><i class="las la-angle-right fs-20 fw-600"></i></span>
                        <span class="fs-12 mr-2 text">View All</span>
                    </a> 
                </div>
                <div class="d-sm-flex bg-white mb-1">
                    <!-- Banner -->
                    @php
                        $newest_preorder_banner_image = get_setting('newest_preorder_banner_image', null, $lang);
                    @endphp
                    <div class="px-3 px-sm-4">
                        <div class="w-sm-270px h-100 mx-auto">
                            <a href="{{ route('all_preorder_products') }}" class="d-block w-100 w-xl-auto hov-scale-img overflow-hidden rounded-2">
                                <img src="{{ uploaded_asset($newest_preorder_banner_image) }}"
                                    alt="{{ translate('Newest Preorder Products') }}"
                                    class="img-fit h-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                        </div>
                    </div>
                    <!-- Products -->
                    <div class="px-0 px-sm-2 w-100 overflow-hidden">
                        <div class="aiz-carousel arrow-x-0 arrow-inactive-none bg-white border border-white" data-items="5"
                            data-xxl-items="5" data-xl-items="3.5" data-lg-items="3" data-md-items="2" data-sm-items="1"
                            data-xs-items="2" data-arrows='false' data-infinite='false'>
                            @foreach ($newest_preorder_products as $key => $product)
                                @include('preorder.frontend.product_box4',['product' => $product])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif