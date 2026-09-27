@if(count($product_ids) > 0)
<table class="table table-bordered aiz-table">
    <thead>
        <tr>
            <td width="50%">
                <span>{{translate('Product')}}</span>
            </td>
            <td data-breakpoints="lg" width="20%">
                <span>{{translate('Base Price')}}</span>
            </td>
            <td data-breakpoints="lg" width="30%">
                <span>{{translate('Added By')}}</span>
            </td>
        </tr>
    </thead>
    <tbody>
        @foreach ($product_ids as $key => $id)
        @php
        $product = \App\Models\Product::findOrFail($id);
        $sale_alert_product = \App\Models\CustomSaleAlert::where('product_id', $product->id)->first();
        @endphp
        <tr>
            <td class="py-1">
                <div class="d-flex align-items-center py-0">
                    <div class="mr-2">
                        <img src="{{ uploaded_asset($product->thumbnail_img)}}" class="size-60px img-fit">
                    </div>
                    <div class="text-truncate-2">
                        <span>{{ $product->getTranslation('name') }}</span>
                    </div>
                </div>
            </td>
            <td class="align-middle">
                <span>{{ $product->unit_price }}</span>
            </td>
            <td class="align-middle">
                {{ optional($product->user)->name }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif