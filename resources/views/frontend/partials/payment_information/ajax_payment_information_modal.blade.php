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
            <option value="bank_transfer">{{ ucfirst(translate('Bank Transfer'))  }}</option>
            <option value="others">{{ ucfirst(translate('Others'))  }}</option>
        </select>
    </div>
    <div class="basic-fields d-none">
        <!-- Name -->
        <div class="form-group mb-3">
            <label>{{ translate('Name')}} <span class="text-danger">*</span></label>
            <select name="payment_name" class="form-control aiz-selectpicker" data-live-search="true" required>
                <option value="">{{ translate('Select Payment Method') }}</option>
                @foreach ($payment_methods as $payment_method)
                    <option value="{{ $payment_method->id }}">{{ $payment_method->name }}</option>
                @endforeach
                <option value="other_method">{{ ucfirst(translate('Others'))  }}</option>
            </select>
        </div>

        <div class="form-group mb-3 d-none" id="other-methods-field">
            <input type="text" class="form-control mb-3 rounded-0" name="other_payment_method"
                placeholder="{{ translate('Enter Payment Name') }}">
        </div>

        <!-- Payment Instructions -->
        <div class="form-group mb-3">
            <label>{{ translate('Payment Instructions')}} <span class="text-danger">*</span></label>
            <textarea class="form-control mb-3 rounded-0" rows="3" name="payment_instructions"></textarea>
        </div>
    </div>
    <div class="bank-fields d-none">
        <div class="form-group mb-3">
            <label>{{ translate('Bank Name')}} <span class="text-danger">*</span></label>
            <select name="bank_name" class="form-control aiz-selectpicker" data-live-search="true" required>
                <option value="">{{ translate('Select Bank') }}</option>
                @foreach ($bank_payment_methods as $bank_payment_method)
                    <option value="{{ $bank_payment_method->id }}">{{ $bank_payment_method->name }}</option>
                @endforeach
                <option value="other_bank">{{ ucfirst(translate('Others'))  }}</option>
            </select>
        </div>

        <div class="form-group mb-3 d-none" id="other-bank-name-field">
            <input type="text" class="form-control mb-3 rounded-0" name="other_bank_name"
                placeholder="{{ translate('Enter Bank Name') }}">
        </div>

        <div class="form-group mb-3">
            <label>{{ translate('Account Name')}} <span class="text-danger">*</span></label>
            <input class="form-control mb-3 rounded-0" name="account_name">
        </div>

        <div class="form-group mb-3">
            <label>{{ translate('Account Number')}} <span class="text-danger">*</span></label>
            <input class="form-control mb-3 rounded-0" name="account_number">
        </div>

        <div class="form-group mb-3">
            <label>{{ translate('Routing Number')}} <span class="text-danger">*</span></label>
            <input class="form-control mb-3 rounded-0" name="routing_number">
        </div>
    </div>

</div>

<!-- Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="create-payment-information">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>
