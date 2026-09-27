@if(count($products) > 0)
<table class="table table-bordered aiz-table">
    <tbody>
      @php 
      $select_type = $single_select  ? 'radio' : 'checkbox'; 
      $name = $single_select  ? 'selected_product_id' : 'selected_product_id[]'; 
      $todays_deal = isset($todays_deal) ? $todays_deal : 0;
      $todays_deal_check = $todays_deal == 1 ? 1 : 0;
      @endphp
        @foreach ($products as $key => $product)
            <tr @if($todays_deal_check == 1 && $product->todays_deal == 1) class="todays_deal-selected-tr" @endif>
              <td class="py-2">
                <div class="from-group row align-items-center">
                  <div class="col-auto">
                    <label class="aiz-checkbox">
                      <input type="{{ $select_type }}" @if($todays_deal_check == 1 && $product->todays_deal == 1) checked disabled @endif class="check-one product-select" name="{{$name}}" value="{{ $product->id }}">
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
                  <span>{{ ucfirst($product->added_by) }}</span>
              </td>
              <td class="py-2" style="vertical-align: middle;">
                  <span>{{ single_price($product->unit_price) }}</span>
              </td>
            </tr>
        @endforeach
    </tbody>
  </table>
@endif