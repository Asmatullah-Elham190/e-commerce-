@extends('backend.layouts.app')

@section('content')
<div class="row seller-page">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center pb-3 mx-4">
            <h5 class="mb-2 mb-lg-0 font-weight-bold">{{ translate('Select Shipping Method') }}</h5>
            <a class="font-weight-bold" href="{{route('shipping_configuration.index')}}">{{ translate('Go to Shipping Configuration Page') }}</a>
        </div>

        <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="shipping_type">

            <div class="row mx-1 shipping-card">
                <!-- Area Wise Shipping Cost -->
                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card text-center px-3 py-4 w-100" data-shipping="Area Wise Shipping Cost">
                        <img src="{{ static_asset('assets/img/shipping/area_wise_flat_shipping.png') }}" class="card-img-top mx-auto" alt="Shipping Icon">

                        <div class="text-left mx-4">
                            <div class="d-flex align-items-left justify-content-left mb-2">
                                <input type="radio" hidden id="areaWiseFlatShipping" class="mr-2" name="shipping_type" value="area_wise_shipping" @if(get_setting('shipping_type')=='area_wise_shipping' ) checked @endif>
                                <p for="areaWiseFlatShipping" class="mb-0 font-weight-bold">
                                    {{ translate('Area Wise Shipping Cost') }}
                                </p>
                            </div>
                            <p class="text-muted mb-0 " style="font-size: 12px;">
                                {{ translate('Fixed rate for each area. If customers purchase multiple products from one seller shipping cost is calculated by the customer shipping area.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card text-center px-3 py-4 w-100" data-shipping="Product Wise Shipping Cost">
                        <img src="{{ static_asset('assets/img/shipping/product_wise_shipping.png') }}" class="card-img-top mx-auto" alt="Shipping Icon">

                        <div class="text-left mx-4">
                            <div class="d-flex align-items-left justify-content-left mb-2">
                                <input type="radio" hidden id="productWiseShipping" class="mr-2" name="shipping_type" value="product_wise_shipping" @if(get_setting('shipping_type')=='product_wise_shipping' ) checked @endif>
                                <p for="productWiseShipping" class="mb-0 font-weight-bold">
                                    {{ translate('Product Wise Shipping Cost') }}
                                </p>
                            </div>
                            <p class="text-muted mb-0 " style="font-size: 12px; ">
                                {{ translate('Shipping cost is calculated by adding the shipping cost of each product.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card text-center px-3 py-4 w-100" data-shipping="Flat Rate Shipping Cost">
                        <img src="{{ static_asset('assets/img/shipping/flat_rate_shipping.png') }}" class="card-img-top mx-auto" alt="Shipping Icon">

                        <div class="text-left mx-4">
                            <div class="d-flex align-items-left justify-content-left mb-2">
                                <input type="radio" hidden id="flatRateShipping" class="mr-2" name="shipping_type" value="flat_rate" @if(get_setting('shipping_type')=='flat_rate' ) checked @endif>
                                <p for="flatRateShipping" class="mb-0 font-weight-bold">
                                    {{ translate('Flat Rate Shipping Cost') }}
                                </p>
                            </div>
                            <p class="text-muted mb-0 " style="font-size: 12px;">
                                {{ translate('Shipping cost stays the same no matter how many products are purchased.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card text-center px-3 py-4 w-100" data-shipping="Seller Wise Shipping Cost">
                        <img src="{{ static_asset('assets/img/shipping/seller_wise_flat_shipping.png') }}" class="card-img-top mx-auto" alt="Shipping Icon">

                        <div class="text-left mx-4">
                            <div class="d-flex align-items-left justify-content-left mb-2">
                                <input type="radio" hidden id="sellerWiseFlatShipping" class="mr-2" name="shipping_type" value="seller_wise_shipping" @if(get_setting('shipping_type')=='seller_wise_shipping' ) checked @endif>
                                <p for="sellerWiseFlatShipping" class="mb-0 font-weight-bold">
                                    {{ translate('Seller Wise Shipping Cost') }}
                                </p>
                            </div>
                            <p class="text-muted mb-0 " style="font-size: 12px;">
                                {{ translate('A fixed rate is set for each seller. If a customer buys products from two sellers, the total shipping cost is the sum of each seller’s rate.') }}
                            </p>
                        </div>
                    </div>
                </div>


                <!-- Carrier Wise Shipping Cost -->
                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card text-center px-3 py-4 w-100" data-shipping="Carrier Wise Shipping Cost">
                        <img src="{{ static_asset('assets/img/shipping/carrier_wise_shipping.png') }}" class="card-img-top mx-auto" alt="Shipping Icon">

                        <div class="text-left mx-4">
                            <div class="d-flex align-items-left justify-content-left mb-2">
                                <input type="radio" hidden name="shipping_type" value="carrier_wise_shipping" @if(get_setting('shipping_type')=='carrier_wise_shipping' ) checked @endif class="mr-2">
                                <p for="carrierWiseFlatShipping" class="mb-0 font-weight-bold">
                                    {{ translate('Carrier Wise Shipping Cost') }}
                                </p>
                            </div>
                            <p class="text-muted mb-0 " style="font-size: 12px;">
                                {{ translate('Shipping cost calculate in addition with carrier. In each carrier you can set free shipping cost or can set weight range or price range shipping cost.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mx-1 mb-4">
                <div class="col-xl-8 ">
                    <button class="btn bg-blue-color2 text-primary w-100 "><small class="font-weight-bold">You have selected <span id="dynamic-text"> ... </span></small></button>
                </div>
                <div class="col-xl-4 mt-2 mt-xl-0">
                    <button class="btn btn-primary w-100" type="submit">{{ translate('SET SHIPPING METHOD') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-2"></div>
</div>
@endsection

@section('script')
<script>
    $('.shipping-card .card').click(function(e) {
        if (!$(e.target).is('input[type=radio]')) {
            $(this).find('input[type=radio]').prop('checked', true).trigger('change');
        }
    });
    $('input[name="shipping_type"]').change(function() {
        $('.shipping-card .card').removeClass('border border-primary border-2');
        $(this).closest('.shipping-card .card').addClass('border border-primary border-2');
        $('#dynamic-text').text($(this).closest('.shipping-card .card').data('shipping'));
    });

    var selected = $('input[name="shipping_type"]:checked');
    if (selected.length) {
        selected.closest('.shipping-card .card').addClass('border border-primary border-2');
        $('#dynamic-text').text(selected.closest('.shipping-card .card').data('shipping'));
    }

    $(document).ready(function() {
        $('.shipping-card .card').css('cursor', 'pointer');
    });
</script>
@endsection