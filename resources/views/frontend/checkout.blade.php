@extends('frontend.layouts.app')

@section('content')

@php
    /** * GA4 */
    $user = Auth::user();
    $email = $user ? trim($user->email) : '';
    $phone = $user ? trim($user->phone) : '';
    $name  = $user ? trim($user->name) : 'Guest';
    
    $nameParts = explode(' ', $name, 2);
    $fName = $nameParts[0] ?? '';
    $lName = $nameParts[1] ?? '';

    if (!function_exists('gtmHash')) {
        function gtmHash($str) {
            return hash('sha256', strtolower(trim($str)));
        }
    }

    $gtmItems = [];
    foreach ($carts as $cartItem) {
        $product = get_single_product($cartItem['product_id']);
        if($product) {
            $gtmItems[] = [
                'item_id' => (string) $product->id,
                'item_name' => $product->name,
                'item_category' => $product->main_category ? $product->main_category->name : 'General',
                'price' => (float) $cartItem['price'],
                'quantity' => (int) $cartItem['quantity']
            ];
        }
    }
@endphp

    <section class="my-4 gry-bg">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-8 mx-auto">
                    <form class="form-default" data-toggle="validator" action="{{ route('payment.checkout') }}" role="form" method="POST" id="checkout-form">
                        @csrf

                        <div class="accordion" id="accordioncCheckoutInfo">

                            <!-- Shipping Info -->
                            <div class="card rounded-0 border shadow-none" style="margin-bottom: 2rem;">
                                <div class="card-header border-bottom-0 py-3 py-xl-4" id="headingShippingInfo" type="button" data-toggle="collapse" data-target="#collapseShippingInfo" aria-expanded="true" aria-controls="collapseShippingInfo">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <path id="Path_42357" data-name="Path 42357" d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z" transform="translate(-48 -48)" fill="#9d9da6"/>
                                        </svg>
                                        <span class="ml-2 fs-19 fw-700">{{ translate('Shipping Info') }}</span>
                                    </div>
                                    <i class="las la-angle-down fs-18"></i>
                                </div>
                                <div id="collapseShippingInfo" class="collapse show" aria-labelledby="headingShippingInfo" data-parent="#accordioncCheckoutInfo">
                                    <div class="card-body" id="shipping_info">
                                       @include('frontend.partials.cart.shipping_info', ['address_id' => $address_id])
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Info -->
                            <div class="card rounded-0 border shadow-none" style="margin-bottom: 2rem; overflow: visible !important;">
                                <div class="card-header border-bottom-0 py-3 py-xl-4" id="headingDeliveryInfo" type="button" data-toggle="collapse" data-target="#collapseDeliveryInfo" aria-expanded="true" aria-controls="collapseDeliveryInfo">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <path id="Path_42357" data-name="Path 42357" d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z" transform="translate(-48 -48)" fill="#9d9da6"/>
                                        </svg>
                                        <span class="ml-2 fs-19 fw-700">{{ translate('Delivery Info') }}</span>
                                    </div>
                                    <i class="las la-angle-down fs-18"></i>
                                </div>
                                <div id="collapseDeliveryInfo" class="collapse show" aria-labelledby="headingDeliveryInfo" data-parent="#accordioncCheckoutInfo">
                                    <div class="card-body" id="delivery_info">
                                        @include('frontend.partials.cart.delivery_info', ['carts' => $carts, 'carrier_list' => $carrier_list, 'shipping_info' => $shipping_info])
                                    </div>
                                </div>
                            </div>


                            <!-- Payment Info -->
                            <div class="card rounded-0 mb-0 border shadow-none">
                                <div class="card-header border-bottom-0 py-3 py-xl-4" id="headingPaymentInfo" type="button" data-toggle="collapse" data-target="#collapsePaymentInfo" aria-expanded="true" aria-controls="collapsePaymentInfo">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <path id="Path_42357" data-name="Path 42357" d="M58,48A10,10,0,1,0,68,58,10,10,0,0,0,58,48ZM56.457,61.543a.663.663,0,0,1-.423.212.693.693,0,0,1-.428-.216l-2.692-2.692.856-.856,2.269,2.269,6-6.043.841.87Z" transform="translate(-48 -48)" fill="#9d9da6"/>
                                        </svg>
                                        <span class="ml-2 fs-19 fw-700">{{ translate('Payment') }}</span>
                                    </div>
                                    <i class="las la-angle-down fs-18"></i>
                                </div>
                                <div id="collapsePaymentInfo" class="collapse show" aria-labelledby="headingPaymentInfo" data-parent="#accordioncCheckoutInfo">
                                    <div class="card-body" id="payment_info">
                                        @include('frontend.partials.cart.payment_info', ['carts' => $carts, 'total' => $total])

                                        <!-- Agree Box -->
                                        <div class="pt-2rem fs-14">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" required id="agree_checkbox" onchange="stepCompletionPaymentInfo()">
                                                <span class="aiz-square-check"></span>
                                                <span>{{ translate('I agree to the') }}</span>
                                            </label>
                                            <a href="{{ route('terms') }}"
                                                class="fw-700">{{ translate('terms and conditions') }}</a>,
                                            <a href="{{ route('returnpolicy') }}"
                                                class="fw-700">{{ translate('return policy') }}</a> &
                                            <a href="{{ route('privacypolicy') }}"
                                                class="fw-700">{{ translate('privacy policy') }}</a>
                                        </div>

                                        <div class="row align-items-center pt-3 mb-4">
                                            <!-- Return to shop -->
                                            <div class="col-6">
                                                <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                                    <i class="las la-arrow-left fs-16"></i>
                                                    {{ translate('Return to shop') }}
                                                </a>
                                            </div>
                                            <!-- Complete Ordert -->
                                            <div class="col-6 text-right">
                                                <button type="button" onclick="submitOrder(this)" id="submitOrderBtn"
                                                    class="btn btn-primary fs-14 fw-700 rounded-0 px-4">

                                                    <span class="btn-text">
                                                        {{ translate('Complete Order') }}
                                                    </span>

                                                    <span class="btn-loader d-none">
                                                        <i class="las la-spinner la-spin"></i> {{ translate('Processing...') }}
                                                    </span>

                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <!-- Cart Summary -->
                <div class="col-lg-4 mt-4 mt-lg-0" id="cart_summary">
                    @include('frontend.partials.cart.cart_summary', ['proceed' => 0, 'carts' => $carts])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Address Modal -->
    @if(Auth::check())
        @include('frontend.partials.address.address_modal')
         @include('frontend.partials.address.billing_address_modal')
    @endif

    @if(auth()->check() && addon_is_activated('otp_system') && auth()->user()->otp_activation_purchase_cod_wallet == 1)      
        @include('otp_systems.frontend.modal_cod_wallet_otp')
    @endif

    @if(!Auth::check() && addon_is_activated('otp_system') && get_setting('guest_checkout_with_otp') == 1)
        @include('otp_systems.frontend.guest_phone_otp_modal')
    @endif

@endsection

@section('script')
    <script type="text/javascript">

        var otp_addon_active = '{{ addon_is_activated('otp_system') }}';
        var otp_setting_active = {{ auth()->check() && auth()->user()->otp_activation_purchase_cod_wallet == 1 ? 1 : 0 }};
        var guest_otp_active = {{ !Auth::check() && get_setting('guest_checkout_with_otp') == 1 ? 1 : 0 }};

       var carrierCount=0;
        $(document).ready(function() {
            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};

        function use_wallet(el) {
            activateButtonLoader(el);
            if ($('input[name="wallet_payment"]').length == 0) {
                $('#checkout-form').append('<input type="hidden" name="payment_option" value="wallet" id="wallet_payment_input">');
            }

            if ($('#agree_checkbox').is(":checked")) {
                ;
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                        deactivateButtonLoader(el);
                        return;
                } else {
                    var allIsOk = false;
                    var isOkShipping = stepCompletionShippingInfo();
                    var isOkDelivery = stepCompletionDeliveryInfo();
                    var isOkPayment = stepCompletionWalletPaymentInfo();
                    if(isOkShipping && isOkDelivery && isOkPayment) {
                        allIsOk = true;
                    }else{
                        AIZ.plugins.notify('danger', '{{ translate("Please fill in all mandatory fields!") }}');
                        $('#checkout-form [required]').each(function (i, el) {
                            if ($(el).val() == '' || $(el).val() == undefined) {
                                var is_trx_id = $('.d-none #trx_id').length;
                                if(($(el).attr('name') != 'trx_id') || is_trx_id == 0){
                                    $(el).focus();
                                    $(el).scrollIntoView({behavior: "smooth", block: "center"});
                                    return false;
                                }
                            }
                        });
                        deactivateButtonLoader(el);
                        return;
                    }

                    if (allIsOk) {
                        if (otp_addon_active == '1' && otp_setting_active == 1) {
                            openOtpModal();
                            deactivateButtonLoader(el);
                            return;
                        }
                        $('#checkout-form').submit();
                    }

                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                deactivateButtonLoader(el);
            }
        }

        function submitOrder(el) {
            $('#wallet_payment_input').remove();
            activateButtonLoader(el);
            if ($('#agree_checkbox').is(":checked")) {
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                        deactivateButtonLoader(el);
                        return;
                } else {
                    var offline_payment_active = '{{ addon_is_activated('offline_payment') }}';
                    if (offline_payment_active == '1' && $('.offline_payment_option').is(":checked") && $('#trx_id')
                        .val() == '') {
                        AIZ.plugins.notify('danger', '{{ translate('You need to put Transaction id') }}');
                        deactivateButtonLoader(el);
                        return;
                    } else {
                        var allIsOk = false;
                        var isOkShipping = stepCompletionShippingInfo();
                        var isOkDelivery = stepCompletionDeliveryInfo();
                        var isOkPayment = stepCompletionPaymentInfo();
                        if(isOkShipping && isOkDelivery && isOkPayment) {
                            allIsOk = true;
                        }else{
                            AIZ.plugins.notify('danger', '{{ translate("Please fill in all mandatory fields!") }}');
                            $('#checkout-form [required]').each(function (i, el) {
                                if ($(el).val() == '' || $(el).val() == undefined) {
                                    var is_trx_id = $('.d-none #trx_id').length;
                                    if(($(el).attr('name') != 'trx_id') || is_trx_id == 0){
                                        $(el).focus();
                                        $(el).scrollIntoView({behavior: "smooth", block: "center"});
                                        return false;
                                    }
                                }
                            });
                            deactivateButtonLoader(el); 
                            return;
                        }

                        if (allIsOk) {

                            if (otp_addon_active == '1' && guest_otp_active == 1 && $('#guest_phone_verified').val() != '1') {
                                AIZ.plugins.notify('danger', '{{ translate("Please verify your phone number with OTP") }}');
                                deactivateButtonLoader(el);
                                return;
                            }

                            if (otp_addon_active == '1' && otp_setting_active == 1) {

                                let selectedPayment = $('input[name="payment_option"]:checked').val();
                                // alert(selectedPayment);

                                if (selectedPayment == 'cash_on_delivery') {
                                    openOtpModal();
                                    deactivateButtonLoader(el);
                                    return;
                                }
                            }
                            $('#checkout-form').submit();
                            
                        }
                    }
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                deactivateButtonLoader(el);
            }
        }
        if (otp_addon_active == '1' && otp_setting_active == 1){

            function openOtpModal() {

                $.post("{{ route('checkout.send_purchase_otp') }}", {
                    _token: AIZ.data.csrf
                }, function(response){

                    if(response.status){
                        $('#cod_wallet_otp-modal').modal('show');
                        AIZ.plugins.notify('success', response.message);
                    }else{
                        AIZ.plugins.notify('danger', response.message);
                    }

                });
            }

            $(document).on('submit', '#purchaseOtpForm', function(e){
                e.preventDefault();

                let otp_code = $('input[name="otp_code"]').val();

                $.post("{{ route('checkout.verify_purchase_otp') }}", {
                    _token: AIZ.data.csrf,
                    otp_code: otp_code
                }, function(response){

                    if(response.status){
                        $('#cod_wallet_otp-modal').modal('hide');

                        if($('#wallet_payment_input').length > 0){
                            activateButtonLoader($('#walletBtn'));
                        } else {
                            activateButtonLoader($('#submitOrderBtn'));
                        }
                        
                        $('#checkout-form').submit();
                    }else{
                        AIZ.plugins.notify('danger', response.message);
                    }

                });
            });

        }

        function activateButtonLoader(el) {
            $(el).prop('disabled', true);
            $(el).find('.btn-text').addClass('d-none');
            $(el).find('.btn-loader').removeClass('d-none');
        }

        function deactivateButtonLoader(el) {
            $(el).prop('disabled', false);
            $(el).find('.btn-text').removeClass('d-none');
            $(el).find('.btn-loader').addClass('d-none');
        }

        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_description').parent().removeClass('d-none');
                $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            }
        }
        // coupon apply
        $(document).on("click", "#coupon-apply", function() {
            @if (Auth::check())
                @if(Auth::user()->user_type != 'customer')
                    AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to apply coupon code.') }}");
                    return false;
                @endif

                var data = new FormData($('#apply-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.apply_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                        $("#cart_summary").html(data.html);
                    }
                });
            @else
                $('#login_modal').modal('show');
            @endif
        });

        // coupon remove
        $(document).on("click", "#coupon-remove", function() {
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                var data = new FormData($('#remove-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.remove_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        $("#cart_summary").html(data);
                    }
                });
            @endif
        });

        function updateDeliveryAddress(id, city_id = 0, area_id=0) {
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateDeliveryAddress') }}', {
                _token: AIZ.data.csrf,
                address_id: id,
                city_id: city_id,
                area_id: area_id
            }, function(data) {
                $('#delivery_info').html(data.delivery_info);
                $('#cart_summary').html(data.cart_summary);
                $('.aiz-refresh').removeClass('active');
                carrierCount = data.carrier_count;
                checkCarrerShippingInfo();
            });
           
            AIZ.plugins.bootstrapSelect("refresh");
        }

        function updateBillingAddress(id) {
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateBillingAddress') }}', {
                _token: AIZ.data.csrf,
                billing_address_id: id
            }, function(data) {
                $('.aiz-refresh').removeClass('active');
            });
        }

        function stepCompletionShippingInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            @if (Auth::check())
                var length = $('input[name="address_id"]:checked').length;
                if (length > 0) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            @else
                var count = 0;
                var length = $('#shipping_info [required]').length;
                $('#shipping_info [required]').each(function (i, el) {
                    if ($(el).val() != '' && $(el).val() != undefined && $(el).val() != null) {
                        count += 1;
                    }
                });
                if (count == length) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            @endif

            $('#headingShippingInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        $('#shipping_info [required]').each(function (i, el) {
            $(el).change(function(){
                if ($(el).attr('name') == 'address_id') {
                    updateDeliveryAddress($(el).val());
                    setDefaultshippingAddress();
                    setBillingAddress();
                }
                @if (get_setting('shipping_type') == 'area_wise_shipping')
                    if ($(el).attr('name') == 'city_id') {
                        let country_id = $('select[name="country_id"]').length? $('select[name="country_id"]').val() : $('input[name="country_id"]').val();
                        let city_id = $(this).val();
                        updateDeliveryAddress(country_id, city_id);
                    }
                @endif
                if ($(el).attr('name') == 'billing_address_id') {
                    setBillingAddress(el);
                }
                
                
                stepCompletionShippingInfo();
            });
        });

        $('select[name="area_id"].guest-checkout').change(function () {
            let country_id = $('select[name="country_id"]').length
                ? $('select[name="country_id"]').val()
                : $('input[name="country_id"]').val();
            let city_id = $('select[name="city_id"]').val();
            let area_id = $(this).val();

            if (area_id) {
                updateDeliveryAddress(country_id, city_id, area_id);
            } else {
                updateDeliveryAddress(country_id, city_id);
            }

            stepCompletionShippingInfo();
        });

        function stepCompletionDeliveryInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            var content = $('#delivery_info [required]');
            if (content.length > 0) {
                var content_checked = $('#delivery_info [required]:checked');
                if (content_checked.length > 0) {
                    content_checked.each(function (i, el) {
                        allOk = false;
                        if($(el).val() == 'carrier'){
                            var owner = $(el).attr('data-owner');
                            if ($('input[name=carrier_id_'+owner+']:checked').length > 0) {
                                allOk = true;
                            }
                        }else if($(el).val() == 'pickup_point'){
                            var owner = $(el).attr('data-owner');
                            if ($('select[name="pickup_point_id_'+owner+'"]').val() != '') {
                                allOk = true;
                            }
                        }else{
                            allOk = true;
                        }

                        if(allOk == false) {
                            return false;
                        }
                    });

                    if (allOk) {
                        headColor = '#15a405';
                        btnDisable = false;
                    }
                }
            }else{
                allOk = true
                headColor = '#15a405';
                btnDisable = false;
            }

            $('#headingDeliveryInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        function updateDeliveryInfo(shipping_type, type_id, user_id, country_id = 0, city_id = 0) {
            @if (get_setting('shipping_type') == 'area_wise_shipping' || get_setting('shipping_type') == 'carrier_wise_shipping')
                country_id = $('select[name="country_id"]').val() != null ? $('select[name="country_id"]').val() : 0;
                city_id = $('select[name="city_id"]').val() != null ? $('select[name="city_id"]').val() : 0;
                @if(get_active_countries()->count() == 1)
                    country_id = {{ get_active_countries()[0]->id }};
                @endif
            @endif
            
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateDeliveryInfo') }}', {
                _token: AIZ.data.csrf,
                shipping_type: shipping_type,
                type_id: type_id,
                user_id: user_id,
                country_id: country_id,
                city_id: city_id
            }, function(data) {
                $('#cart_summary').html(data);
                checkCarrerShippingInfo();
                stepCompletionDeliveryInfo();
                $('.aiz-refresh').removeClass('active');
            });
            AIZ.plugins.bootstrapSelect("refresh");
        }

        function show_pickup_point(el, user_id) {
        	var type = $(el).val();
        	var target = $(el).data('target');
            var type_id = null;

        	if(type == 'home_delivery' || type == 'carrier'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
                $('.carrier_id_'+user_id).removeClass('d-none');
        	}else{
        		$(target).removeClass('d-none');
        		$('.carrier_id_'+user_id).addClass('d-none');
        	}

            if(type == 'carrier'){
                type_id = $('input[name=carrier_id_'+user_id+']:checked').val();
            }else if(type == 'pickup_point'){
                type_id = $('select[name=pickup_point_id_'+user_id+']').val();
            }
            updateDeliveryInfo(type, type_id, user_id);
        }

        function stepCompletionPaymentInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var payment = false;
            var agree = false;
            var allOk = false;
            var length = $('input[name="payment_option"]:checked').length;
            if(length > 0){
                if ($('input[name="payment_option"]:checked').hasClass('offline_payment_option')) {
                    if ($('#trx_id').val() != '' && $('#trx_id').val() != undefined && $('#trx_id').val() != null) {
                        payment = true;
                    }
                } else {
                    payment = true;
                }

                if ($('#agree_checkbox').is(":checked")){
                    agree = true;
                }

                if (payment && agree) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            }

            $('#headingPaymentInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        function stepCompletionWalletPaymentInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            if ($('#agree_checkbox').is(":checked")){
                headColor = '#15a405';
                btnDisable = false;
                allOk = true;
            }

            $('#headingPaymentInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        $('input[name="payment_option"]').change(function(){
            stepCompletionPaymentInfo();
        });

        function checkCarrerShippingInfo(){
           const shippingType = @json(get_setting('shipping_type'));
            const isDisabled = carrierCount === 0;
            let carrierSelected = false;
            let pickupSelected = false;
            $('.shipping-type-radio').each(function () {
                if ($(this).is(':checked') && $(this).val() === 'carrier') {
                    carrierSelected = true;
                }
            });
            $('.shipping-type-radio').each(function () {
                if ($(this).is(':checked') && $(this).val() === 'pickup_point') {
                    pickupSelected = true;
                }
            });
                if(shippingType == 'carrier_wise_shipping' && carrierSelected){
                    if (carrierCount === 0) {
                        if( (carrierSelected && pickupSelected) || (carrierSelected && !pickupSelected) ){
                            $('#submitOrderBtn').prop('disabled', true);
                            $('#agree_checkbox').prop('checked', false).prop('disabled', true);
                            $('.online_payment, .offline_payment_option').prop('checked', false).prop('disabled', true);
                        }
                    } else {
                        $('#agree_checkbox').prop('disabled', false);
                        $('.online_payment, .offline_payment_option').prop('disabled', false);
                    }
                }else{
                    $('#agree_checkbox').prop('disabled', false);
                    $('.online_payment, .offline_payment_option').prop('disabled', false);
                }
        }

        $(document).ready(function(){
            carrierCount = parseInt(document.getElementById('carrierCount')?.value || 0);
            checkCarrerShippingInfo();
            stepCompletionShippingInfo();
            stepCompletionDeliveryInfo();
            stepCompletionPaymentInfo();
            
            // --- GA4 begin_checkout Data Layer Push ---
            @if(get_setting('google_analytics') == 1)
            window.dataLayer = window.dataLayer || [];
            dataLayer.push({
                'event': 'begin_checkout',
                'ecommerce': {
                    'currency': '{{ get_system_currency()->code }}',
                    'value': {{ $total }},
                    'items': @json($gtmItems)
                },
                // Real data for standard GTM use
                'customer_info': {
                    'email': '{{ $email }}',
                    'phone': '{{ $phone }}',
                    'first_name': '{{ $fName }}',
                    'last_name': '{{ $lName }}'
                },
                // Hashed data for Enhanced Conversions
                'user_data': {
                    'email_address': '{{ gtmHash($email) }}',
                    'phone_number': '{{ gtmHash($phone) }}',
                    'address': {
                        'first_name': '{{ gtmHash($fName) }}',
                        'last_name': '{{ gtmHash($lName) }}'
                    }
                }
            });
            @endif
        });

        function changeShippingAddress(){
            $('#choose-address-modal').modal('hide');
        }

        function setDefaultshippingAddress() {
            let checkedAddress = $('input[name="address_id"]:checked');

            if (checkedAddress.length) {

                let selectedText = checkedAddress.closest('label').find('.address-text').html();
                $('#choose-default').html(selectedText);
                $('#default-address-change-btn').attr('onclick', "edit_address('" + checkedAddress.val() + "')");
                $('input[name="billing_address_id"]').first().val(checkedAddress.val());
                let $box = $('#default-address-box');
                if ($box.length) {
                    $box.removeClass('border-danger');
                    checkedAddress.prop('checked', true);
                    checkedAddress.prop('disabled', false);
                    $box.find('#hide-no-longer-div').remove();
                    
                }
            }
        }

        function setBillingAddress(el) {
            let type = $(el).data('type');
            let checkedAddress = $(el);
           if(type === 'billing'){
                let checkedAddress = $('input[name="billing_address_id"]:checked');
                if (checkedAddress.length) {

                    let selectedText = checkedAddress.closest('label').find('.address-text').html();
                    $('#choose-default-billing').html(selectedText);
                    $('#default-address-change-btn').attr('onclick', "edit_billing_address('" + checkedAddress.val() + "')");
                    $('input[name="billing_address_id"]').first().val(checkedAddress.val());
                    let $box = $('#default-billing-address-box');
                    if ($box.length) {
                        $box.removeClass('border-danger');
                        checkedAddress.prop('checked', true);
                        checkedAddress.prop('disabled', false);
                        $box.find('#hide-no-valid-div').remove();
                        
                    }
                }
            } else{
                let checkedAddress = $('input[name="address_id"]:checked');
                if (checkedAddress.length) {
                    let selectedText = checkedAddress.closest('label').find('.address-text').html();
                    $('#choose-default-billing').html(selectedText);
                    $('input[name="billing_address_id"]').first().val(checkedAddress.val());
                }
            }
            updateBillingAddress(checkedAddress.val());
        }

        if (otp_addon_active == '1' && guest_otp_active == 1 && $('#guest_phone_verified').val() != '1') {

            function sendGuestOtp(el) {
                var phone = $('input[name="phone"]').val();
                var country_code = $('input[name="country_code"]').val();

                if (!phone) {
                    $('input[name="phone"]').focus();
                    AIZ.plugins.notify('danger', '{{ translate("Please enter your phone number first") }}');
                    return;
                }

                activateButtonLoader(el);

                $.post("{{ route('checkout.send_guest_otp') }}", {
                    _token: AIZ.data.csrf,
                    phone: phone,
                    country_code: country_code
                }, function (response) {
                    deactivateButtonLoader(el);
                    if (response.status) {
                        $('#guest_phone_otp-modal').modal('show');
                        AIZ.plugins.notify('success', response.message);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                }).fail(function () {
                    deactivateButtonLoader(el);
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong, please try again") }}');
                });
            }

            $(document).on('submit', '#guestPhoneOtpForm', function (e) {
                e.preventDefault();
                var otp_code = $('input[name="guest_otp_code"]').val();
                var phone = $('input[name="phone"]').val();

                $.post("{{ route('checkout.verify_guest_otp') }}", {
                    _token: AIZ.data.csrf,
                    phone: phone,
                    otp_code: otp_code
                }, function (response) {
                    if (response.status) {
                        $('#guest_phone_otp-modal').modal('hide');
                        $('#guest_phone_verified').val('1');
                        $('#guest_phone_verified_badge').show();
                        $('#sendGuestOtpBtn').addClass('d-none');
                        AIZ.plugins.notify('success', response.message);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                });
            });
        }


    </script>

    @include('frontend.partials.address.address_js')

    @if(get_active_countries()->count() == 1)
    <script>
        $(document).ready(function() {
            @if(get_setting('has_state') == 1)
                get_states(@json(get_active_countries()[0]->id));
                @if(get_setting('billing_address_required') == 1)
                  get_billing_states(@json(get_active_countries()[0]->id));
                @endif
            @else
                get_city_by_country(@json(get_active_countries()[0]->id));
                @if(get_setting('billing_address_required') == 1)
                  get_billing_city_by_country(@json(get_active_countries()[0]->id));
                @endif
            @endif
        });
         @if(get_setting('shipping_type') == 'carrier_wise_shipping' && !Auth::check() )
            updateDeliveryAddress({{ get_active_countries()[0]->id }});
         @endif
    </script>
    @endif

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif

    @if(addon_is_activated('otp_system') && get_setting('guest_checkout_with_otp') == 1)
        <script>
            $(document).on('input', 'input[name="phone"]', function () {
                $('#guest_phone_verified').val('0');
                $('#guest_phone_verified_badge').hide();
                $('#sendGuestOtpBtn').text('{{ translate("Verify") }}').removeClass('btn-success');
            });
        </script>
    @endif

@endsection
