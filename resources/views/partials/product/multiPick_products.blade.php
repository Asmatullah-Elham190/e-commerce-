<div class="form-group row gst-pics">
       <div class="col">
          <select name="products[]" id="products" class="form-control aiz-selectpicker"
              multiple data-placeholder="{{ translate('Choose Products') }}"
              data-live-search="true" data-selected-text-format="count">
              @foreach($products as $product)
                  <option value="{{ $product->id }}"
                      data-content='<img src="{{ uploaded_asset($product->thumbnail_img) }}" class="size-30px img-fit mr-2"> {{ $product->getTranslation("name") }}'>
                  </option>
              @endforeach
          </select>
      </div>
</div>