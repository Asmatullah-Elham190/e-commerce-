@extends('backend.layouts.app')

@section('content')

<div class="dashboard-content-wrapper pt-4">
    <div class="col-11 col-xxl-9 mx-auto px-25px">
           <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Promotion & Offers') }}</h1>
        <span
            class="fs-12 fw-400 ">{{ translate('Create and manage all promotions, offers and discounted products from one place.') }}</span>
        <div class="row gutters-12 mt-3">
            <div class="col-md-6">
                <div class="card border border-2 border-gray-300 card-no-shadow p-4">
                    <div class="card-header justify-content-start align-items-start border-0 p-0">
                        <div
                            class="w-48px h-48px d-flex align-items-center justify-content-center rounded-1 bg-soft-blue overflow-hidden flex-shrink-0">
                            <img src="{{ static_asset('assets/img/bogo/bulhorn.svg') }}" alt="Bulhorn Icon">
                        </div>
                        <div class="flex-grow-1 ml-3 mt-m-6px min-w-0">
                            <div class="d-flex align-items-center justify-content-between gap-12">
                                <h5 class="fs-16 fs-md-20 fw-semibold mb-0 text-truncate">
                                    {{translate('Promotional Products')}}</h5>
                                    <a class="text-dark" href="{{ route('promotional_products.index') }}"><i class="las la-arrow-right opacity-0 fs-24 fs-md-30 has-transition"></i></a>
                            </div>
                            <span class="fs-12 fw-400 text-gray d-inline-block w-100 sub-title">
                                {{translate('Select & Import products for active promotional channels in one place')}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex gap-16 flex-wrap flex-lg-nowrap p-0 mt-4">
                        <div class="bg-light w-100 w-lg-50 rounded-1 p-3">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{$totalProducts}}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('Total Products')}}</span>
                        </div>
                        <div class="bg-soft-blue w-100 w-lg-50 rounded-1 p-3">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{$promotionalProducts}}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('Assigned for Promotion')}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border border-2 border-gray-300 card-no-shadow p-4 position-relative overflow-hidden">
                    <div class="card-header justify-content-start align-items-start border-0 p-0">
                        <div
                            class="w-48px h-48px d-flex align-items-center justify-content-center rounded-1 overflow-hidden flex-shrink-0" style="background-color: #FFEBFF;">
                            <img src="{{ static_asset('assets/img/bogo/category-wise-discount.svg') }}" alt="Category Wise Discount Icon">
                        </div>
                        <div class="flex-grow-1 ml-3 mt-m-6px min-w-0">
                            <div class="d-flex align-items-center justify-content-between gap-12">
                                <h5 class="fs-16 fs-md-20 fw-semibold mb-0 text-truncate">
                                    {{translate('Category-Wise Discounts')}}</h5>
                                <a class="text-dark" href="{{route('categories_wise_product_discount')}}"><i class="las la-arrow-right opacity-0 fs-24 fs-md-30 has-transition"></i></a>
                            </div>
                            <span class="fs-12 fw-400 text-gray d-inline-block w-100 sub-title">
                                {{translate('Apply automatic discounts to products based on their category')}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex gap-16 flex-wrap flex-lg-nowrap p-0 mt-4">
                        <div class="w-100 w-lg-50 bg-light rounded-1 p-3">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{$all_categories}}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('All Category')}}</span>
                        </div>
                        <div class="w-100 w-lg-50 rounded-1 p-3 bg-soft-warning">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{$main_categories}}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('Main Category')}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border border-2 border-gray-300 card-no-shadow p-4">
                    <div class="card-header justify-content-start align-items-start border-0 p-0">
                        <div
                            class="w-48px h-48px d-flex align-items-center justify-content-center rounded-1 bg-soft-danger overflow-hidden flex-shrink-0">
                            <img src="{{ static_asset('assets/img/bogo/flash.svg') }}" alt="Flash Icon">
                        </div>
                        <div class="flex-grow-1 ml-3 mt-m-6px min-w-0">
                            <div class="d-flex align-items-center justify-content-between gap-12">
                                <h5 class="fs-16 fs-md-20 fw-semibold mb-0 text-truncate">{{translate('Flash Sale')}}
                                </h5>
                                <a class="text-dark" href="{{ route('flash_deals.index') }}"><i class="las la-arrow-right opacity-0 fs-24 fs-md-30 has-transition"></i></a>
                            </div>
                            <span class="fs-12 fw-400 text-gray d-inline-block w-100 sub-title">
                                {{translate('Create Campaign for limited time sale with countdown. Create Campaign for limited time sale with countdown.')}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex gap-16 flex-wrap flex-lg-nowrap p-0 mt-4">
                        <div class="w-100 w-lg-50 bg-light rounded-1 p-3">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{ $totalFlashDeals }}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('Total Campaign Created')}}</span>
                        </div>
                        <div class="w-100 w-lg-50 bg-soft-danger rounded-1 p-3">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{ $activeFlashDeals }}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('Campaign Active')}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border border-2 border-gray-300 card-no-shadow p-4">
                    <div class="card-header justify-content-start align-items-start border-0 p-0">
                        <div class="w-48px h-48px d-flex align-items-center justify-content-center rounded-1 overflow-hidden flex-shrink-0"
                            style="background-color: #FFF4EB;">
                            <img src="{{ static_asset('assets/img/bogo/clock.svg') }}" alt="Clock Icon">
                        </div>
                        <div class="flex-grow-1 ml-3 mt-m-6px min-w-0">
                            <div class="d-flex align-items-center justify-content-between gap-12">
                                <h5 class="fs-16 fs-md-20 fw-semibold mb-0 text-truncate">{{translate("Today's Deal")}}
                                </h5>
                                <a class="text-dark" href="{{ route('todays_deal_products.index') }}"><i class="las la-arrow-right opacity-0 fs-24 fs-md-30 has-transition"></i></a>
                            </div>
                            <span class="fs-12 fw-400 text-gray d-inline-block w-100 sub-title">
                                {{translate('Handpick daily spotlight products with exclusive markdown prices')}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body d-flex gap-16 flex-wrap p-0 mt-4">
                        <div class="flex-grow-1 rounded-1 p-3 todays-deals-number-box" style="background-color: #FFF4EB;">
                            <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{ $todaysDeal }}</h6>
                            <span class="fs-12 fw-400 text-gray">{{translate('Currently Featured in the deal')}}</span>
                        </div>
                    </div>
                </div>
            </div>
            @if (get_setting('coupon_system') == 1 )
                <div class="col-md-6">
                    <div class="card border border-2 border-gray-300 card-no-shadow p-4">
                        <div class="card-header justify-content-start align-items-start border-0 p-0">
                            <div class="w-48px h-48px d-flex align-items-center justify-content-center rounded-1 overflow-hidden flex-shrink-0"
                                style="background-color: #FFEBFA;">
                                <img src="{{ static_asset('assets/img/bogo/cupon.svg') }}" alt="Cupon Icon">
                            </div>
                            <div class="flex-grow-1 ml-3 mt-m-6px min-w-0">
                                <div class="d-flex align-items-center justify-content-between gap-12">
                                    <h5 class="fs-16 fs-md-20 fw-semibold mb-0 text-truncate">{{translate('Coupon')}}</h5>
                                    <a class="text-dark" href="{{ route('coupon.index') }}"><i class="las la-arrow-right opacity-0 fs-24 fs-md-30 has-transition"></i></a>
                                </div>
                                <span class="fs-12 fw-400 text-gray d-inline-block w-100 sub-title">
                                    {{translate('Create coupon with codes for customers to use for discounts.')}}
                                </span>
                            </div>
                        </div>
                        <div class="card-body d-flex gap-16 flex-wrap flex-lg-nowrap p-0 mt-4">
                            <div class="w-100 w-lg-50 bg-light rounded-1 p-3">
                                <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{$totalCoupons}}</h6>
                                <span class="fs-12 fw-400 text-gray">{{translate('Total Coupon')}}</span>
                            </div>
                            <div class="w-100 w-lg-50 rounded-1 p-3" style="background-color: #FFEBFA;">
                                <h6 class="fs-16 fs-md-20 fw-700 aiz-number-counter mb-1">{{$activeCoupons}}</h6>
                                <span class="fs-12 fw-400 text-gray">{{translate('Active Coupon')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection



@section('script')
<script>
$(document).ready(function() {
    $('.aiz-number-counter').each(function() {
        var $this = $(this);
        var countTo = parseFloat($this.text().replace(/[^0-9.]/g, '')) || 0;

        $({
            countNum: 0
        }).animate({
            countNum: countTo
        }, {
            duration: 2000,
            easing: 'swing',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
});
</script>
@endsection