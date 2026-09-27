<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add Payment Information !') }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px">
    @php
        $bank_payment_methods = \App\Models\OfflinePayoutMethod::where('type', 'bank_payment')->get();
        $payment_methods = \App\Models\OfflinePayoutMethod::where('type', 'others')->get();
    @endphp

    <!-- Type -->
    <div class="form-group mb-3">
        <label>{{ translate('Type')}} <span class="text-danger">*</span></label>
        <select name="payment_type" class="form-control aiz-selectpicker" data-live-search="true">
            <option value="">{{ translate('Select Payment Type') }}</option>
            <option value="bank_transfer" {{ isset($payment_information) && $payment_information->payment_type == 'bank_transfer' ? 'selected' : '' }}>
                {{ ucfirst(translate('Bank Transfer'))  }}
            </option>
            <option value="others" {{ isset($payment_information) && $payment_information->payment_type == 'others' ? 'selected' : '' }}>{{ ucfirst(translate('Others'))  }}</option>
        </select>
    </div>
    <div
        class="basic-fields {{ isset($payment_information) && $payment_information->payment_type == 'others' ? '' : 'd-none' }}">
        <!-- Name -->
        <div class="form-group mb-3">
            <label>{{ translate('Name')}} <span class="text-danger">*</span></label>
            <select name="payment_name" class="form-control aiz-selectpicker">

                <option value="">{{ translate('Select Method') }}</option>

                @foreach ($payment_methods as $payment_method)
                    <option value="{{ $payment_method->id }}" {{ is_numeric($payment_information->payment_name) && $payment_information->payment_name == $payment_method->id ? 'selected' : '' }}>
                        {{ $payment_method->name }}
                    </option>
                @endforeach

                <option value="other_method" {{ !is_numeric($payment_information->payment_name) ? 'selected' : '' }}>
                    {{ translate('Others') }}
                </option>
            </select>
        </div>

        <div id="other-methods-field"
            class="form-group mb-3 {{ !is_numeric($payment_information->payment_name) ? '' : 'd-none' }}">

            <input type="text" name="other_payment_method" class="form-control"
                value="{{ !is_numeric($payment_information->payment_name) ? $payment_information->payment_name : '' }}"
                placeholder="Enter Payment Method">
        </div>

        <!-- Payment Instructions -->
        <div class="form-group mb-3">
            <label>{{ translate('Payment Instructions')}} <span class="text-danger">*</span></label>
            <textarea class="form-control mb-3 rounded-0" rows="3"
                name="payment_instructions">{{ $payment_information->payment_instruction }}</textarea>
        </div>
    </div>
    <div
        class="bank-fields {{ isset($payment_information) && $payment_information->payment_type == 'bank_transfer' ? '' : 'd-none' }}">
        <div class="form-group mb-3">
            <label>{{ translate('Bank Name')}} <span class="text-danger">*</span></label>
            <select name="bank_name" class="form-control aiz-selectpicker" data-live-search="true">

                <option value="">{{ translate('Select Bank') }}</option>

                @foreach ($bank_payment_methods as $bank_payment_method)
                    <option value="{{ $bank_payment_method->id }}" {{ is_numeric($payment_information->bank_name) && $payment_information->bank_name == $bank_payment_method->id ? 'selected' : '' }}>
                        {{ $bank_payment_method->name }}
                    </option>
                @endforeach

                <option value="other_bank" {{ !is_numeric($payment_information->bank_name) ? 'selected' : '' }}>
                    {{ translate('Others') }}
                </option>
            </select>
        </div>

        <div id="other-bank-name-field"
            class="form-group mb-3 {{ !is_numeric($payment_information->bank_name) ? '' : 'd-none' }}">

            <input type="text" name="other_bank_name" class="form-control"
                value="{{ !is_numeric($payment_information->bank_name) ? $payment_information->bank_name : '' }}"
                placeholder="Enter Bank Name">
        </div>

        <div class="form-group mb-3">
            <label>{{ translate('Account Name')}} <span class="text-danger">*</span></label>
            <input class="form-control mb-3 rounded-0" name="account_name"
                value="{{ $payment_information->account_name }}">
        </div>

        <div class="form-group mb-3">
            <label>{{ translate('Account Number')}} <span class="text-danger">*</span></label>
            <input class="form-control mb-3 rounded-0" name="account_number"
                value="{{ $payment_information->account_number }}">
        </div>

        <div class="form-group mb-3">
            <label>{{ translate('Routing Number')}} <span class="text-danger">*</span></label>
            <input class="form-control mb-3 rounded-0" name="routing_number"
                value="{{ $payment_information->routing_number }}">
        </div>
    </div>

</div>

<!-- Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary"
            data-id="{{ $payment_information->id }}" id="edit-payment-information">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>