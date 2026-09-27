@if($products->count() > 0)
<table class="table table-bordered aiz-table">
    <thead>
        <tr>
            <th>{{ translate('Product') }}</th>
            <th>{{ translate('Price') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td class="py-2">
                <div class="d-flex align-items-center">
                    <label class="aiz-checkbox mr-3" style="margin-top: -8px">
                        <input type="checkbox"
                               class="coupon-product-check"
                               value="{{ $product->id }}"
                               data-product-id="{{ $product->id }}"
                               data-product-name="{{ $product->getTranslation('name') }}"
                               data-product-img="{{ uploaded_asset($product->thumbnail_img) }}"
                               data-product-price="{{ $product->unit_price }}">
                        <span class="aiz-square-check"></span>
                    </label>
                    <img class="size-48px img-fit border border-gray-200 overflow-hidden mr-3 flex-shrink-0"
                         src="{{ uploaded_asset($product->thumbnail_img) }}"
                         onerror="this.src='{{ static_asset('assets/img/placeholder.jpg') }}'">
                    <span>{{ $product->getTranslation('name') }}</span>
                </div>
            </td>
            <td class="py-2" style="vertical-align: middle; white-space: nowrap;">
                {{ single_price($product->unit_price) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="text-center py-4 text-muted">
    <i class="las la-box fs-40"></i>
    <p>{{ translate('No products found.') }}</p>
</div>
@endif