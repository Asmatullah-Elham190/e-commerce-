<!--Top Section-->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h6 class="d-flex align-items-center fs-16 fw-700 text-dark mr-2 mt-0 mb-0 p-0">
                <span class="text-truncate-1  mr-2">{{ $product->getTranslation('name') }}</span>
                @php
                    $qty = 0;
                    if($product->variant_product) {
                        foreach ($product->stocks as $key => $stock) {
                            $qty += $stock->qty;
                        }
                    }
                    else {
                        $qty = optional($product->stocks->first())->qty;
                    }
                @endphp
                @if($qty <= $product->low_stock_quantity)
                    <span class="m-0 border border-danger bg-danger text-white fs-12 py-5px px-10px rounded-pill">{{ translate('Low') }}</span>
                @endif
            </h6>
            <button type="button" onclick="enableInputField()"
                class="fs-12 fw-700 text-blue py-1 px-10px pb-0 rounded-pill border border-gray-300 bg-gray-100 ml-2 hov-bg-blue hov-text-white text-nowrap">{{ translate('Update
                Stcok')}}</button>
        </div>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                <path id="Path_45226" data-name="Path 45226"
                    d="M228.588-716.31l-.9-.9,7.1-7.1-7.1-7.1.9-.9,7.1,7.1,7.1-7.1.9.9-7.1,7.1,7.1,7.1-.9.9-7.1-7.1Z"
                    transform="translate(-227.69 732.31)" fill="#a5a5b8" />
            </svg>
        </button>
    </div>
</div>
<!--Offcanvas Body-->
<div class="right-offcanvas-body position-absolute h-100 px-30px">

    <div class="pb-5px">
        <!--Table-->
        <div class="table-responsive">
            <table class="table table-bordered right-offcanvas-table">
                @if($product->variant_product)
                <thead>
                    <tr>
                        <th scope="col" class="fs-14 fw-700 text-dark border-soft-secondary">Variant</th>
                        <th scope="col" class="fs-14 fw-700 text-dark border-soft-secondary text-center">Stock</th>
                        <th scope="col" class="fs-14 fw-700 text-dark border-soft-secondary">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->stocks as $stock)
                    <tr>
                        <td class="fs-14 fw-300 text-dark">{{ $stock->variant }}</td>
                        <td class="fs-14 fw-300 text-dark text-center"><span id="stock-quantity">{{ $stock->qty }}</span>
                            <input type="number" name="{{ $stock->id}}" value="{{ $stock->qty }}" class="stock-input fs-14 text-dark d-none text-center">
                        </td>
                        <td class="fs-14 fw-300 text-dark">{{ $stock->price }}</td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <thead>
                    <tr>
                        <th scope="col" class="fs-14 fw-700 text-dark border-soft-secondary text-center">{{ translate('Stock') }}</th>
                        <th scope="col" class="fs-14 fw-700 text-dark border-soft-secondary">{{ translate('Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fs-14 fw-300 text-dark text-center"><span id="stock-quantity">{{ $qty }}</span>
                            <input type="number" name="{{ $product->stocks->first()->id ?? ''}}" value="{{ $qty ?? '0' }}" class="stock-input fs-14 text-dark d-none text-center">
                        <td class="fs-14 fw-300 text-dark">{{ $product->unit_price }}</td>
                    </tr>
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>

<!--Offcanvas Footer-->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px d-none" id="offcanvas-btn">
    <div class="d-flex justify-content-end footer-btn">
        <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel" onclick="disableInputField()" >{{ translate('Cancel') }}</button>
        <button type="button" class="d-block fs-14 fw-700 py-10px save" onclick="updateStocks('{{$product->id}}')"> {{ translate('Save') }}</button>
    </div>
</div>