@if(count($product_ids) > 0)
    <table class="table aiz-table aiz-border-bottom-dashed-table">
        <thead>
            <th width="45%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Product') }}</span>
            </th>
            <th data-breakpoints="lg" width="20%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Added By') }}</span>
            </th>
            <th data-breakpoints="lg" width="20%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Base Price') }}</span>
            </th>
            <th data-breakpoints="lg" width="25%">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Discount') }}</span>
            </th>
            <th width="10%" class="text-center">
                <span class="text-uppercase fs-10 fs-md-12 fw-700 text-gray">{{ translate('Action') }}</span>
            </th>
        </thead>
        <tbody>
            @foreach ($product_ids as $key => $id)
                @php
                    $product = \App\Models\Product::where('promotional', 1)->findOrFail($id);
                @endphp
                <tr id="discount-row-{{ $id }}">
                    <td style="vertical-align: middle;">
                        <input type="hidden" name="products[]" value="{{ $id }}">
                        <div class="d-flex align-items-center">
                            <img class="size-60px img-fit mr-3" src="{{ uploaded_asset($product->thumbnail_img) }}">
                            <span>{{ $product->getTranslation('name') }}</span>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        <span>{{ ucfirst($product->added_by) }}</span>
                    </td>
                    <td style="vertical-align: middle;">
                        <span>{{ single_price($product->unit_price) }}</span>
                    </td>
                    <td style="vertical-align: middle;">
                        <div class="d-flex flex-wrap flex-md-nowrap align-items-center">
                            <div
                                class="custom-input-pen-clear-field pl-3 pr-2 border border-2 bg-light border-light rounded-1 has-transition w-100 w-sm-300px mr-1 position-relative">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <input type="number" lang="en" name="discount_{{ $id }}" id="discount-input-{{ $id }}"
                                            value="{{ $product->discount }}" min="0" step="0.01" data-product-id="{{ $id }}"
                                            data-unit-price="{{ $product->unit_price }}"
                                            class="form-control px-0 text-blue fs-12 fw-bold bg-transparent border-0 discount-input"
                                            required>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="border-0 bg-transparent"
                                            onclick="clearDiscountInput('{{ $id }}')">
                                            <svg id="pen-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                <path
                                                    d="M12.2,6.567l-5.949,5.95a2.49,2.49,0,0,1-1.157.655l-2.282.571a.5.5,0,0,1-.6-.6l.571-2.282A2.49,2.49,0,0,1,3.437,9.7l5.949-5.95Zm1.409-4.226a1.992,1.992,0,0,1,0,2.818l-.705.7L10.09,3.045l.705-.7A1.992,1.992,0,0,1,13.613,2.341Z"
                                                    transform="translate(-2.196 -1.758)" fill="#a5a5b8" />
                                            </svg>
                                            <span class="fs-10 fw-400 text-blue w-40px text-right">Clear</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="input-field-message bg-dark mt-1 position-absolute py-1 px-2 rounded-1"
                                    id="save-msg-{{ $id }}" style="display:none; z-index:10;">
                                    <span class="fs-12 text-white fw-300"
                                        id="save-msg-text-{{ $id }}">{{ translate('Saving...') }}</span>
                                </div>
                            </div>
                            <div class="w-sm-80px mt-2 mt-md-0">
                                <select class="form-control aiz-selectpicker border-2 border-gray-200 discount-type-select"
                                    name="discount_type_{{ $id }}" id="discount-type-{{ $id }}" data-product-id="{{ $id }}">
                                    <option value="percent" {{ $product->discount_type == 'percent' ? 'selected' : '' }}>%
                                    </option>
                                    <option value="amount" {{ $product->discount_type == 'amount' ? 'selected' : '' }}>
                                        {{ currency_symbol() }}</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td style="vertical-align: middle;" class="text-center">
                        <button type="button" class="w-35px h-35px bg-danger text-white border-0 rounded-1" onclick="removeDiscountRow('{{ $id }}')">
                            <i class="las la-trash fs-16"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif