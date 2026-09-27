@if(count($product_ids) > 0)
    <table class="table">
        <thead>
            <td width="45%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Product') }}</span>
            </td>
            <td data-breakpoints="lg" width="20%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Base Price') }}</span>
            </td>
            <td width="10%" class="text-center">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Action') }}</span>
            </td>
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
                        <button type="button" class="btn btn-sm btn-soft-danger"
                            onclick="removeCustomLabelProductRow('{{ $id }}')">
                            <i class="las la-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif