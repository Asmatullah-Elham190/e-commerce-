<div class="card-header mb-2 pl-0">
    <h3 class="h6">{{translate('Add Your Product Base Coupon')}}</h3>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label" for="code">{{translate('Coupon code')}}</label>
    <div class="col-12">
        <input type="text" placeholder="{{translate('Coupon code')}}" id="code" name="code" class="form-control"
            value="{{ old('code') }}" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label">{{translate('Date')}}</label>
    <div class="col-12">
        <input type="text" class="form-control aiz-date-range" name="date_range" value="{{ old('date_range') }}"
            placeholder="{{ translate('Select Date') }}">
    </div>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label">{{translate('Discount')}}</label>
    <div class="col-md-10">
        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount"
            value="{{ old('discount') }}" class="form-control" required>
    </div>
    <div class="col-md-2 mt-2 mt-md-0">
        <select class="form-control aiz-selectpicker" name="discount_type">
            <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>{{translate('Amount')}}
            </option>
            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>{{translate('Percent')}}
            </option>
        </select>
    </div>
</div>


<div class="form-group row">
    <label class="col-12 col-from-label">{{translate('Products')}}</label>
    <div class="col-12">
        <button type="button" class="bg-transparent d-block w-100 py-2 px-3 border border-dashed border-gray-400 rounded-1
                   d-flex align-items-center justify-content-center text-reset hov-text-blue"
            onclick="openCouponCanvas()">
            <i class="las la-plus mr-1"></i> {{ translate('Add Product') }}
        </button>
    </div>
</div>

<div id="selected-coupon-products-wrapper" style="display:none;">
    <div class="col-12">
        <table class="table aiz-table aiz-border-bottom-dashed-table">
            <thead>
                <tr>
                    <th class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Product') }}</th>
                    <th class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Price') }}</th>
                    <th class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody id="selected-coupon-products-body"></tbody>
        </table>
    </div>
</div>