@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="aiz-titlebar mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="fs-20 fw-700 text-dark">{{ translate('Manage Profile') }}</h1>
        </div>
    </div>
</div>

<!-- Basic Info-->
<div class="card rounded-0 shadow-none border">
    <div class="card-header pt-4 border-bottom-0">
        <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Basic Info')}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Name-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label fs-14 fs-14">{{ translate('Your Name') }}</label>
                <div class="col-md-10">
                    <input type="text" class="form-control rounded-0" placeholder="{{ translate('Your Name') }}" name="name" value="{{ Auth::user()->name }}">
                </div>
            </div>
            <!-- Phone-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label fs-14">{{ translate('Your Phone') }}</label>
                <div class="col-md-10">
                    <input type="text" class="form-control rounded-0" placeholder="{{ translate('Your Phone')}}" name="phone" value="{{ Auth::user()->phone }}">
                </div>
            </div>
            <!-- Photo-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label fs-14">{{ translate('Photo') }}</label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium rounded-0">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="photo" value="{{ Auth::user()->avatar_original }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            <!-- Password-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label fs-14">{{ translate('Your Password') }}</label>
                <div class="col-md-10">
                    <input type="password" class="form-control rounded-0" placeholder="{{ translate('New Password') }}" name="new_password">
                </div>
            </div>
            <!-- Confirm Password-->
            <div class="form-group row">
                <label class="col-md-2 col-form-label fs-14">{{ translate('Confirm Password') }}</label>
                <div class="col-md-10">
                    <input type="password" class="form-control rounded-0" placeholder="{{ translate('Confirm Password') }}" name="confirm_password">
                </div>
            </div>
            <!-- Submit Button-->
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary rounded-0 w-150px mt-3">{{translate('Update Profile')}}</button>
            </div>
        </form>
    </div>
</div>

@if (addon_is_activated('otp_system'))
    <!-- Otp Activation -->
    <div class="card rounded-0 shadow-none border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('OTP Activation')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-6 col-form-label fs-14">{{ translate('Do you want to activate OTP for cash on delivery and wallet payment?') }}</label>
                <div class="col-xxl-6 mt-2">
                    <div class="input-group">
                        <label class="aiz-switch aiz-switch-success">
                            <input type="checkbox"
                                id="activate_otp_for_cashOnDelivery_and_wallet"
                                @if(get_setting('admin_controll_cod_and_wallet_payment_with_otp') == 1)
                                    disabled
                                    checked
                                @else
                                    @checked(Auth::user()->otp_activation_purchase_cod_wallet == 1)
                                @endif>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Address -->
<div class="card rounded-0 shadow-none border">
    <div class="card-header pt-4 border-bottom-0">
        <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Address')}}</h5>
    </div>
    <div class="card-body">
        @foreach (Auth::user()->addresses as $key => $address)
        <div class="">
            <div class="border p-4 mb-4 position-relative">
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary">{{ translate('Address') }}:</span>
                    <span class="col-md-8 text-dark">{{ $address->address }}</span>
                </div>
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary">{{ translate('Postal Code') }}:</span>
                    <span class="col-md-10 text-dark">{{ $address->postal_code }}</span>
                </div>
                @if ($address->area)
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary">{{ translate('Area') }}</span>
                    <span class="col-md-10 text-dark">{{ optional($address->area)->name }}</span>
                </div>
                @endif
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary">{{ translate('City') }}:</span>
                    <span class="col-md-10 text-dark">{{ optional($address->city)->name }}</span>
                </div>
                @if (get_setting('has_state') == 1)
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary">{{ translate('State') }}:</span>
                    <span class="col-md-10 text-dark">{{ optional($address->state)->name }}</span>
                </div>
                @endif
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary">{{ translate('Country') }}:</span>
                    <span class="col-md-10 text-dark">{{ optional($address->country)->name }}</span>
                </div>
                <div class="row fs-14 mb-2 mb-md-0">
                    <span class="col-md-2 text-secondary text-secondary">{{ translate('Phone') }}:</span>
                    <span class="col-md-10 text-dark">{{ $address->phone }}</span>
                </div>
                
                <div class="absolute-md-top-right pt-2 pt-md-4 pr-md-5">
                    @if ($address->set_default)
                    <span class="badge badge-inline badge-secondary-base text-white p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;">{{ translate('Default Shipping') }}</span>
                     @endif
                      @if ($address->set_billing)
                    <span class="badge badge-inline badge-primary text-white p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;">{{ translate('Default Billing') }}</span>
                     @endif
                </div>

                <div class="dropdown position-absolute right-0 top-0 pt-4 mr-1">
                    <button class="btn bg-gray text-white px-1 py-1" type="button" data-toggle="dropdown">
                        <i class="la la-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" onclick="edit_address('{{$address->id}}')">
                            {{ translate('Edit') }}
                        </a>
                        @if (!$address->set_default)
                        <a class="dropdown-item" href="{{ route('addresses.set_default', $address->id) }}">{{ translate('Make This Default Shipping') }}</a>
                        @endif
                         @if (!$address->set_billing)
                        <a class="dropdown-item" href="{{ route('addresses.set_billing', $address->id) }}">{{ translate('Make This Default Billing') }}</a>
                        @endif
                        <a class="dropdown-item" href="{{ route('addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- Add New Address -->
        <div class="" onclick="add_new_address()">
            <div class="border p-3 mb-3 c-pointer text-center bg-light has-transition hov-bg-soft-light">
                <i class="la la-plus la-2x"></i>
                <div class="alpha-7 fs-14 fw-700">{{ translate('Add New Address') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Information -->
@if (addon_is_activated('offline_payment') && addon_is_activated(identifier: 'refund_request'))
    <div class="card rounded-0 shadow-none border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Payment Information')}}</h5>
        </div>
        <div class="card-body">
            @foreach (Auth::user()->payment_informations as $key => $payment_information)
            <div class="">
                <div class="border p-4 mb-4 position-relative">
                    @if ($payment_information->payment_type == 'others')
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ translate('Name') }}:</span>
                            <span class="col-md-8 text-dark">{{ optional($payment_information->other_payout_method)->name ?? $payment_information->payment_name }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ translate('Instructions') }}:</span>
                            <span class="col-md-10 text-dark">{{ $payment_information->payment_instruction }}</span>
                        </div>
                    @else
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ translate('Bank Name') }}:</span>
                            <span class="col-md-8 text-dark">{{ optional($payment_information->payout_method)->name ?? $payment_information->bank_name }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ translate('Account Name') }}:</span>
                            <span class="col-md-10 text-dark">{{ $payment_information->account_name }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ translate('Account Number') }}</span>
                            <span class="col-md-10 text-dark">{{ $payment_information->account_number }}</span>
                        </div>
                        <div class="row fs-14 mb-2 mb-md-0">
                            <span class="col-md-2 text-secondary">{{ translate('Routing Number') }}:</span>
                            <span class="col-md-10 text-dark">{{ $payment_information->routing_number }}</span>
                        </div>
                    @endif
                    <div class="absolute-md-top-right pt-2 pt-md-4 pr-md-5">
                        @if ($payment_information->set_default)
                        <span class="badge badge-inline badge-secondary-base text-white p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;">{{ translate('Default') }}</span>
                        @endif
                    </div>

                    <div class="dropdown position-absolute right-0 top-0 pt-4 mr-1">
                        <button class="btn bg-gray text-white px-1 py-1" type="button" data-toggle="dropdown">
                            <i class="la la-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" onclick="openPaymentInfomrationEditOffcanvas('{{$payment_information->id}}')">
                                {{ translate('Edit') }}
                            </a>
                            @if (!$payment_information->set_default)
                            <a class="dropdown-item" href="{{ route('payment_informations.set_default', $payment_information->id) }}">{{ translate('Make This Default') }}</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('payment_informations.destroy', $payment_information->id) }}">{{ translate('Delete') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @php
                $payment_count = Auth::user()->payment_informations->count();
            @endphp

            @if($payment_count < 2)
                <!-- Add New Payment Information -->
                <div onclick="openPaymentInfomrationAddOffcanvas()">
                    <div class="border p-3 mb-3 c-pointer text-center bg-light has-transition hov-bg-soft-light">
                        <i class="la la-plus la-2x"></i>
                        <div class="alpha-7 fs-14 fw-700">{{ translate('Add New Payment Information') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Change Email -->
<form action="{{ route('user.change.email') }}" method="POST">
    @csrf
    <div class="card rounded-0 shadow-none border">
        <div class="card-header pt-4 border-bottom-0">
            <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Change your email')}}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label class="fs-14">{{ translate('Your New Email') }}</label>
                </div>
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control rounded-0" placeholder="{{ translate('Your Email')}}" name="email" value="{{ Auth::user()->email }}" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary new-email-verification">
                                <span class="d-none loading">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ translate('Sending Email...') }}
                                </span>
                                <span class="default">{{ translate('Verify') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row update-div">
                <div class="col-md-2">
                    <label class="fs-14">{{ translate('Verification Code') }}</label>
                </div>
                <div class="col-md-10">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control rounded-0" placeholder="{{ translate('Enter Your Verification Code')}}" name="code" value="" disabled />
                        <div class="input-group-append">
                           
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary rounded-0 w-150px mt-3" disabled >{{translate('Update Email')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('modal')
    <!-- Address modal -->
    @include('frontend.partials.address.address_modal')

    <!-- confirm trigger Modal -->
    <div id="confirm-trigger-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-2 mb-2 fs-16 fw-700" id="confirm_text"></p>
                    <p class="fs-13" id="confirm_detail_text"></p>
                    <a href="javascript:void(0)" id="trigger_btn" data-value="" data-status="" data-clicked="" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px"></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@include('frontend.partials.address.address_js')

<script type="text/javascript">
    $('.new-email-verification').on('click', function() {
        $(this).find('.loading').removeClass('d-none');
        $(this).find('.default').addClass('d-none');
        var email = $("input[name=email]").val();

        $.post('{{ route('user.email.update.verify.code') }}', {
                _token: '{{ csrf_token() }}',
                email: email
            },
            function(data) {
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if (data.status == 2){
                    AIZ.plugins.notify('warning', data.message);
                }
                else if (data.status == 1){
                    AIZ.plugins.notify('success', data.message);
                    $('input[name="code"]').prop('disabled', false);
                    $('button[type="submit"]').prop('disabled', false);
                }
                else{
                    AIZ.plugins.notify('danger', data.message);
                }
            });
    });

    $(document).ready(function() {
        @if(get_setting('has_state') == 1)
            get_states(@json(get_active_countries()[0]->id));
        @else
            get_city_by_country(@json(get_active_countries()[0]->id));
        @endif
    });

    $('#trigger_btn').on('click', function() {
        const actionType = $(this).attr('data-action-type');
        if (actionType === 'activate_otp_for_cashOnDelivery_and_wallet') {
            updateSettings();
        }
        $(this).attr('data-clicked', 1);
        $('#confirm-trigger-modal').modal('hide');
    });

    function updateSettings() {
        var value = $('#trigger_btn').attr('data-value');
        var type = $('#trigger_btn').attr('data-type');
        $.post('{{ route('activate_otp_for_cashOnDelivery_and_wallet') }}', {
            _token: '{{ csrf_token() }}',
            type: type,
            value: value
        }, function(data) {
            if (data.status == 1) {
                AIZ.plugins.notify('success', data.message);
            } else {
                AIZ.plugins.notify('danger', data.message);
            }
        }).fail(function() {
            AIZ.plugins.notify('danger', '{{ translate('Network error') }}');
        });
    }

    $('#activate_otp_for_cashOnDelivery_and_wallet').on('change', function() {
        if('{{ env('DEMO_MODE') }}' == 'On') {
            AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
            $(this).prop('checked', !$(this).is(':checked'));
            return;
        }
        const isChecked = $(this).is(':checked');
        const confirmText = isChecked 
            ? "{{ translate('Are you sure you want to activate OTP for cash on delivery and wallet payment?') }}"
            : "{{ translate('Are you sure you want to deactivate OTP for cash on delivery and wallet payment?') }}";
        const detailText = isChecked 
            ? "{{ translate('If you activate this, an OTP will be sent to your phone number when placing an order using Cash on Delivery or Wallet payment.') }}"
            : "{{ translate('If this is deactivated, no OTP will be sent to your phone number when placing an order using Cash on Delivery or Wallet payment.') }}";
        const btnText = isChecked 
            ? "{{ translate('Allow') }}"
            : "{{ translate('Disable') }}";
        $('#confirm_text').text(confirmText);
        $('#confirm_detail_text').text(detailText);
        $('#trigger_btn')
            .text(btnText)
            .attr('data-action-type', 'activate_otp_for_cashOnDelivery_and_wallet')
            .attr('data-type', 'activate_otp_for_cashOnDelivery_and_wallet')
            .attr('data-value', isChecked ? 1 : 0);
        $('#confirm-trigger-modal')
            .data('action-type', 'activate_otp_for_cashOnDelivery_and_wallet')
            .modal('show');
    });  
    
    
    $('#confirm-trigger-modal').on('hidden.bs.modal', function () {
        const actionType = $(this).data('action-type');
        if ($('#trigger_btn').attr('data-clicked') == 1) {
            $('#trigger_btn').attr('data-clicked', '');
            $(this).removeData('action-type');
        } else {
            if (actionType === 'activate_otp_for_cashOnDelivery_and_wallet') {
                const current = $('#activate_otp_for_cashOnDelivery_and_wallet').is(':checked');
                $('#activate_otp_for_cashOnDelivery_and_wallet').prop('checked', !current);
            } 
            else if (actionType === 'activate_otp_for_cashOnDelivery_and_wallet') {
                var id = $('#trigger_btn').attr('data-value');
                if (id) {
                    var activate_otp_for_cashOnDelivery_and_wallet = $('#trigger_btn').attr('data-activate_otp_for_cashOnDelivery_and_wallet') == 1 ? false : true;
                    $('#trigger_alert_' + id).prop('checked', activate_otp_for_cashOnDelivery_and_wallet);
                }
            }
            $(this).removeData('action-type');
            $('#trigger_btn').removeAttr('data-action-type data-type data-value data-activate_otp_for_cashOnDelivery_and_wallet');
        }
    });
    @if(Auth::check())
        function openPaymentInfomrationAddOffcanvas() {
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');
            
            if (rightOffcanvas) rightOffcanvas.classList.add('active');
            if (overlay) overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            
            if (rightOffcanvas) {
                rightOffcanvas.innerHTML = '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';
            }
            
            $.ajax({
                type: "POST",
                url: "{{ route('payment_informations.create') }}",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(html) {
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = html;
                    }
                },
                error: function() {
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = '<div class="p-4 text-center text-danger">{{ translate("Failed to load") }}</div>';
                    }
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        }

        $(document).on('change', 'select[name="payment_type"]', function () {
            let type = $(this).val();

            if (type === 'bank_transfer') {
                $('.bank-fields').removeClass('d-none');
                $('.basic-fields').addClass('d-none');
            } else if (type === 'others') {
                $('.basic-fields').removeClass('d-none');
                $('.bank-fields').addClass('d-none');
            } else {
                $('.basic-fields, .bank-fields').addClass('d-none');
            }
        });

        $(document).on('change', 'select[name="bank_name"]', function() {
            let value = $(this).val();

            if (value === 'other_bank') {
                $('#other-bank-name-field').removeClass('d-none');
            } else {
                $('#other-bank-name-field').addClass('d-none');
                $('#other-bank-name-field input').val(''); 
            }
        });

        $(document).on('change', 'select[name="payment_name"]', function() {
            let value = $(this).val();

            if (value === 'other_method') {
                $('#other-methods-field').removeClass('d-none');
            } else {
                $('#other-methods-field').addClass('d-none');
                $('#other-methods-field input').val(''); 
            }
        });

        $(document).on('click', '#create-payment-information', function () {
            const btn = $(this);
            if (!validatePaymentForm()) {
                return;
            }
            btn.prop('disabled', true);

            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2"></span>');
            }

            let formData = {
                _token: AIZ.data.csrf,
                payment_type: $('select[name="payment_type"]').val(),
                payment_name: $('select[name="payment_name"]').val(),
                other_payment_method: $('input[name="other_payment_method"]').val(),
                payment_instructions: $('textarea[name="payment_instructions"]').val(),
                bank_name: $('select[name="bank_name"]').val(),
                other_bank_name: $('input[name="other_bank_name"]').val(),
                account_name: $('input[name="account_name"]').val(),
                account_number: $('input[name="account_number"]').val(),
                routing_number: $('input[name="routing_number"]').val(),
            };

            $.ajax({
                url: "{{ route('payment_informations.store') }}",
                type: "POST",
                data: formData,

                success: function (res) {
                    AIZ.plugins.notify('success', 'Payment Information added successfully');
                    closeOffcanvas();
                    location.reload();
                },

                error: function (xhr) {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();

                    console.log(xhr.responseText);
                }
            });
        });

        window.openPaymentInfomrationEditOffcanvas = function(paymentInformationId) {
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');
            
            if (rightOffcanvas) rightOffcanvas.classList.add('active');
            if (overlay) overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            
            if (rightOffcanvas) {
                rightOffcanvas.innerHTML = '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';
            }
            
            $.ajax({
                type: "POST",
                url: "{{ route('payment_informations.edit') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_information_id: paymentInformationId 
                },
                success: function(html) {
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = html;
                        setTimeout(function () {
                            $('select[name="payment_type"]').trigger('change');
                            $('select[name="bank_name"]').trigger('change');
                            $('select[name="payment_name"]').trigger('change');
                        }, 100);
                    }
                },
                error: function() {
                    if (rightOffcanvas) {
                        rightOffcanvas.innerHTML = '<div class="p-4 text-center text-danger">{{ translate("Failed to load") }}</div>';
                    }
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        };

        $(document).on('click', '#edit-payment-information', function () {
            const btn = $(this);
            const paymentInformationId = btn.data('id'); 

            if (!validatePaymentForm()) {
                return;
            }

            btn.prop('disabled', true);

            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2"></span>');
            }

            let formData = {
                _token: AIZ.data.csrf,
                payment_information_id: paymentInformationId, 
                payment_type: $('select[name="payment_type"]').val(),
                payment_name: $('select[name="payment_name"]').val(),
                other_payment_method: $('input[name="other_payment_method"]').val(),
                payment_instructions: $('textarea[name="payment_instructions"]').val(),
                bank_name: $('select[name="bank_name"]').val(),
                other_bank_name: $('input[name="other_bank_name"]').val(),
                account_name: $('input[name="account_name"]').val(),
                account_number: $('input[name="account_number"]').val(),
                routing_number: $('input[name="routing_number"]').val(),
            };

            $.ajax({
                url: "{{ route('payment_informations.update') }}",
                type: "POST",
                data: formData,

                success: function (res) {
                    AIZ.plugins.notify('success', 'Payment Information added successfully');
                    closeOffcanvas();
                    location.reload();
                },

                error: function (xhr) {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();

                    console.log(xhr.responseText);
                }
            });
        });

        function validatePaymentForm() {
            const paymentType = $('select[name="payment_type"]').val();

            if (!paymentType) {
                AIZ.plugins.notify('danger', 'Please select a payment type.');
                return false;
            }

            if (paymentType === 'bank_transfer') {
                const bankName = $('select[name="bank_name"]').val();

                if (!bankName) {
                    AIZ.plugins.notify('danger', 'Please select a bank name.');
                    return false;
                }

                if (bankName === 'other_bank') {
                    const otherBankName = $('input[name="other_bank_name"]').val().trim();
                    if (!otherBankName) {
                        AIZ.plugins.notify('danger', 'Please enter the bank name.');
                        return false;
                    }
                }

                if (!$('input[name="account_name"]').val().trim()) {
                    AIZ.plugins.notify('danger', 'Please enter the account name.');
                    return false;
                }

                if (!$('input[name="account_number"]').val().trim()) {
                    AIZ.plugins.notify('danger', 'Please enter the account number.');
                    return false;
                }

                if (!$('input[name="routing_number"]').val().trim()) {
                    AIZ.plugins.notify('danger', 'Please enter the routing number.');
                    return false;
                }
            }

            if (paymentType === 'others') {
                const paymentName = $('select[name="payment_name"]').val();

                if (!paymentName) {
                    AIZ.plugins.notify('danger', 'Please select a payment method.');
                    return false;
                }

                if (paymentName === 'other_method') {
                    const otherMethod = $('input[name="other_payment_method"]').val().trim();
                    if (!otherMethod) {
                        AIZ.plugins.notify('danger', 'Please enter the payment method name.');
                        return false;
                    }
                }

                if (!$('textarea[name="payment_instructions"]').val().trim()) {
                    AIZ.plugins.notify('danger', 'Please enter the payment instructions.');
                    return false;
                }
            }

            return true;
        }
    @endif
</script>
@if (get_setting('google_map') == 1)
@include('frontend.partials.google_map')
@endif

@endsection