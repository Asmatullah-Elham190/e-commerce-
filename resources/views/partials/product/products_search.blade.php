@if(count($products) > 0)
<table class="table table-bordered aiz-table">
    <tbody>
        @php 
            $select_type = $single_select ? 'radio' : 'checkbox'; 
            $name = $single_select ? 'selected_product_id' : 'selected_product_id[]'; 
            $pos = isset($pos) ? $pos : 0;
            $pos_check = $pos == 1 ? 1 : 0;

            $gmc = isset($gmc) ? $gmc : 0;
            $gmc_check = $gmc == 1 ? 1 : 0;

            $facebook_catalogue = isset($facebook_catalogue) ? $facebook_catalogue : 0;
            $facebook_catalogue_check = $facebook_catalogue == 1 ? 1 : 0;
        @endphp

        @foreach ($products as $key => $product)
            <tr @if(($pos_check == 1 && $product->pos == 1) || ($gmc_check == 1 && $product->gmc == 1) || ($facebook_catalogue_check == 1 && $product->facebook_catalogue == 1)) class="pos-selected-tr" @endif >
                <td class="py-2">
                    <div class="from-group row align-items-center">
                        <div class="col-auto">
                            <label class="aiz-checkbox">

                                <input 
                                    type="{{ $select_type }}" class="check-one product-select" name="{{ $name }}" value="{{ $product->id }}"

                                    @if($pos_check == 1 && $product->pos == 1) checked  @endif

                                    @if($gmc_check == 1 && $product->gmc == 1) checked @endif

                                    @if($facebook_catalogue_check == 1 && $product->facebook_catalogue == 1) checked @endif
                                >

                                <span class="aiz-square-check"></span>
                            </label>
                            <img class="size-48px img-fit" src="{{ uploaded_asset($product->thumbnail_img) }}">
                        </div>
                        <div class="col">
                            <span>{{ $product->getTranslation('name') }}</span>
                        </div>
                    </div>
                </td>

                <td class="py-2" style="vertical-align: middle;">
                    <span>{{ single_price($product->unit_price) }}</span>
                </td>
            </tr>

        @endforeach
    </tbody>
</table>
@endif