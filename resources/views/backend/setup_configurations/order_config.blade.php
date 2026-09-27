@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="mb-3 border border-1 border-gray-300 rounded-1 p-4 d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between"
            style="gap: 18px">
            <div class="d-flex flex-wrap flex-md-nowrap align-items-center" style="gap: 12px">
                <div
                    class="w-60px h-60px rounded-1 d-flex align-items-center justify-content-center overflow-hidden bg-soft-warning flex-shrink-0">
                    <img src="{{ static_asset('assets/img/business-settings/order-configuration.svg') }}"
                        alt="Setting Icon">
                </div>
                <div>
                    <h6 class="fw-semibold text-dark">{{translate('Order Configuration')}}</h6>
                    <span
                        class="fs-12 fw-400 d-block text-gray">{{translate('Manage core business information, store identity and essential configurations that define how your store operates.')}}</span>
                </div>
            </div>
            <a href="{{ route('business_settings.index') }}"
                class="fs-14 fw-500 text-reset text-blue hov-text-white has-transition bg-soft-blue hov-bg-blue rounded-pill py-2 px-3 flex-shrink-0">
                <i class="las la-angle-left fs-14"></i>
                {{translate('Back to Business Settings')}}
            </a>
        </div>
        <div class="card">

            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="hidden" name="types[]" value="minimum_order_amount_check">

                                <input type="checkbox" id="minimum_order_amount_check" name="minimum_order_amount_check"
                                    value="1" @if (get_setting('minimum_order_amount_check') == 1) checked @endif>

                                <span></span>
                            </label>

                            <span class="d-block" style="margin-top: -6px">
                                {{ translate('Minimum Order Amount Check') }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>
                            {{ translate('Set Minimum Order Amount') }}
                        </label>

                        <input type="hidden" name="types[]" value="minimum_order_amount">

                        <input type="number" min="0" step="0.01" class="form-control" id="minimum_order_amount"
                            name="minimum_order_amount" placeholder="{{ translate('Enter amount (e.g. 100,200)') }}"
                            value="{{ get_setting('minimum_order_amount') }}" @if (get_setting('minimum_order_amount_check') != 1) disabled @endif>
                    </div>
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ translate('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {

        $('#minimum_order_amount_check').on('change', function () {

            if ($(this).is(':checked')) {
                $('#minimum_order_amount').prop('disabled', false);
            } else {
                $('#minimum_order_amount').prop('disabled', true);
            }

        });

    });
</script>
@endsection