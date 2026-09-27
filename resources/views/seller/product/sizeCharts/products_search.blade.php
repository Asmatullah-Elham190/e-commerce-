@if(count($products) > 0)
<table class="table table-bordered aiz-table">
    <tbody>
      @php
      $select_type = $single_select ? 'radio' : 'checkbox';
      $name = $single_select ? 'selected_product_id' : 'selected_product_id[]';
      $readonly = $readonly ?? 0;
      @endphp
        @foreach ($products as $key => $product)
            <tr>
              <td class="py-2">
                <div class="from-group row align-items-center">
                  <div class="col-auto">
                    <label class="aiz-checkbox">
                      <input type="{{ $select_type }}"
                             {{ $readonly ? 'disabled checked' : '' }}
                             class="check-one product-select"
                             name="{{ $name }}"
                             value="{{ $product->id }}">
                      <span class="aiz-square-check"></span>
                    </label>
                    <img class="size-48px img-fit" src="{{ uploaded_asset($product->thumbnail_img)}}">
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
@else
  <div class="text-secondary fs-13 py-2">{{ translate('No products found') }}</div>
@endif