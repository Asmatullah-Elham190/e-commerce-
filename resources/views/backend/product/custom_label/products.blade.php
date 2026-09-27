@if(count($product_ids) > 0)
    <table class="table aiz-table aiz-border-bottom-dashed-table">
        <thead>
            <th width="45%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Product') }}</span>
            </th>
            <th data-breakpoints="lg" width="20%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Base Price') }}</span>
            </th>
            <th width="10%" class="text-center">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Action') }}</span>
            </th>
        </thead>
        <tbody>
            @foreach ($product_ids as $key => $id)
                @php
                    $product = \App\Models\Product::findOrFail($id);
                @endphp
                <tr id="custom-label-product-row-{{ $id }}">
                    <td style="vertical-align: middle;">
                        <input type="hidden" name="products[]" value="{{ $id }}">
                        <div class="d-flex align-items-center">
                            <img class="size-60px img-fit mr-3" src="{{ uploaded_asset($product->thumbnail_img) }}">
                            <span>{{ $product->getTranslation('name') }}</span>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <span>{{ single_price($product->unit_price) }}</span>
                    </td>
                    <td style="vertical-align: middle;" class="text-center">
                        <button type="button" class="w-35px h-35px bg-danger text-white border-0 rounded-1"
                            onclick="removeCustomLabelProductRow('{{ $id }}')">
                            <i class="las la-trash fs-16"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif