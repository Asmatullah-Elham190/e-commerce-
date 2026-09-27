@php
    $payment_count = Auth::user()->payment_informations->count();
@endphp
@if (Auth::check())
    <!-- Single Start -->
    <div class="mb-2 mt-2 mt-md-3">
        @foreach (Auth::user()->payment_informations as $key => $payment_information)
            <div class="border mb-3" id="default-payment-info-box">
                <div class="row">
                    <div class="col-md-8">
                        <label class="aiz-megabox d-block bg-white mb-0">
                            <input type="radio" name="single_payment_infomation_id" value="{{ $payment_information->id }}" {{ $payment_information->id == $payment_information_id ? 'checked' : '' }}>
                            @if ($payment_information->payment_type == 'bank_transfer')
                                <span class="d-flex p-3 aiz-megabox-elem border-0">
                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="pl-3 text-left w-xl-300px"  id="choose-default">
                                            {{ optional($payment_information->payout_method)->name ?? $payment_information->bank_name }}, {{ $payment_information->account_name }}, {{ $payment_information->account_number }}, {{ $payment_information->routing_number }}
                                        </span>
                                </span>
                            @else
                                <span class="d-flex p-3 aiz-megabox-elem border-0">
                                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="pl-3 text-left w-xl-300px"  id="choose-default">
                                            {{ optional($payment_information->other_payout_method)->name ?? $payment_information->payment_name }}
                                        </span>
                                </span>
                            @endif
                        </label>
                    </div>
                    <!-- Always show Change button -->
                    <div class="col-md-4 p-3 text-right">
                        <a href="javascript:void(0)" id="default-payment-info-change-btn" class="btn btn-sm bg-secondary-base hov-opacity-70 text-white hov-text-dark mr-3 rounded-pill px-4"
                            onclick="openPaymentInfomrationEditOffcanvas('{{ $payment_information->id }}')">
                            {{ translate('Change') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
        @if($payment_count < 2)
            <div class="d-flex flex-wrap align-items-center justify-content-end">
                <!-- Add New Payment Information -->
                <div class="py-1">
                    <div class="border c-pointer text-center py-2 px-3 bg-soft-blue has-transition d-flex justify-content-center rounded-pill"
                        onclick="openPaymentInfomrationAddOffcanvas()">
                        <i class="las la-plus fs-20 fw-bold text-blue"></i>
                        <div class="alpha-7 fs-14 text-blue fw-700 ml-2">{{ translate('Add New Payment Information') }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- Single End -->
@endif