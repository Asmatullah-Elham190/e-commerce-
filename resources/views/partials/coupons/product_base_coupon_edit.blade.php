@php
    $couponDetails    = json_decode($coupon->details, true) ?? [];
    $selectedIds      = array_column($couponDetails, 'product_id');
    $selectedProducts = \App\Models\Product::whereIn('id', $selectedIds)->get()->keyBy('id');
@endphp

<div class="card-header mb-2 pl-0">
    <h3 class="h6">{{translate('Edit Your Product Base Coupon')}}</h3>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label" for="code">{{translate('Coupon code')}}</label>
    <div class="col-12">
        <input type="text" placeholder="{{translate('Coupon code')}}" id="code" name="code"
               class="form-control" value="{{ $coupon->code }}" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label">{{translate('Products')}}</label>
    <div class="col-12">
        <button type="button"
            class="bg-transparent d-block w-100 py-2 px-3 border border-dashed border-gray-400 rounded-1
                   d-flex align-items-center justify-content-center text-reset hov-text-blue"
            onclick="openCouponCanvas()">
            <i class="las la-plus mr-1"></i> {{ translate('Add Product') }}
        </button>
    </div>
</div>

<div id="selected-coupon-products-wrapper" {{ count($selectedIds) > 0 ? '' : 'style=display:none;' }}>
    <div class="col-12 px-0">
        <table class="table table-bordered aiz-table">
            <thead>
                <tr>
                    <th class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Product') }}</th>
                    <th class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Price') }}</th>
                    <th class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody id="selected-coupon-products-body">
                @foreach($selectedIds as $pid)
                    @if(isset($selectedProducts[$pid]))
                        @php $p = $selectedProducts[$pid]; @endphp
                        <tr id="coupon-product-row-{{ $pid }}">
                            <td class="py-2">
                                <div class="d-flex align-items-center">
                                    <img class="size-48px img-fit border border-gray-200 overflow-hidden mr-3"
                                         src="{{ uploaded_asset($p->thumbnail_img) }}"
                                         onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">
                                    <span>{{ $p->getTranslation('name') }}</span>
                                </div>
                                <input type="hidden" name="product_ids[]" value="{{ $pid }}">
                            </td>
                            <td class="py-2" style="vertical-align:middle;white-space:nowrap;">
                                {{ single_price($p->unit_price) }}
                            </td>
                            <td class="py-2" style="vertical-align:middle;">
                                <button type="button" class="btn btn-sm btn-soft-danger"
                                        onclick="removeCouponProduct({{ $pid }})">
                                    <i class="las la-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label">{{translate('Date')}}</label>
    <div class="col-12">
        <input type="text" class="form-control aiz-date-range" name="date_range"
               value="{{ date('m/d/Y', $coupon->start_date) . ' - ' . date('m/d/Y', $coupon->end_date) }}"
               placeholder="{{ translate('Select Date') }}">
    </div>
</div>

<div class="form-group row">
    <label class="col-12 col-from-label">{{translate('Discount')}}</label>
    <div class="col-md-10">
        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}"
               name="discount" value="{{ $coupon->discount }}" class="form-control" required>
    </div>
    <div class="col-md-2 mt-2 mt-md-0">
        <select class="form-control aiz-selectpicker" name="discount_type">
            <option value="amount"  {{ $coupon->discount_type == 'amount'  ? 'selected' : '' }}>{{translate('Amount')}}</option>
            <option value="percent" {{ $coupon->discount_type == 'percent' ? 'selected' : '' }}>{{translate('Percent')}}</option>
        </select>
    </div>
</div>