{{-- plus blade --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="px-20px py-20px">
            <h6 class="fs-13 fw-700 text-dark mb-1">{{ translate('New Request') }}</h6>
            <span class="fs-11 fw-400 text-gray">{{ translate('Create your desired request from here') }}</span>

            <h6 class="fs-13 fw-700 text-reset mb-1 mt-4">
                {{ translate('Select Item') }}
                <span class="text-danger">*</span>
            </h6>
            <span class="fs-11 fw-400 text-gray">{{ translate('Select item for create request') }}</span>
            <div class="mt-1 d-flex flex-column">
                <div class="form-check form-check-inline mt-3">
                    <select class="form-control aiz-selectpicker fs-13 fw-400 text-gray js-request-items"
                        data-live-search="true" name="items" title="{{ translate('Select an item') }}">
                        <option value="category">{{ translate('Category') }}</option>
                        <option value="brand">{{ translate('Brand') }}</option>
                        <option value="color">{{ translate('Color') }}</option>
                        <option value="attribute">{{ translate('Attribute') }}</option>
                        <option value="unit">{{ translate('Unit') }}</option>
                        <option value="measurement_point">{{ translate('Measurement Point') }}</option>
                        <option value="warranty">{{ translate('Warranty') }}</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 py-3">
                <h6 class="fs-13 fw-700 text-reset mb-1">
                    {{ translate('Item Name') }}
                    <span class="text-danger">*</span>
                </h6>
                <span class="fs-11 fw-400 text-gray">{{ translate('Write item name here') }}</span>
                <input type="text" name="item_name"
                    class="js-request-item-name mt-3 form-control fs-11 fw-400 border border-1 border-gray-300 rounded-1"
                    placeholder="{{ translate('name') }}">
            </div>

            <div class="mt-4 py-3">
                <h6 class="fs-13 fw-700 text-reset mb-1">
                    {{ translate('Item Description') }}
                </h6>
                <span class="fs-11 fw-400 text-gray">{{ translate('Write item description here') }}</span>
                <textarea name="message" rows="4"
                    class="js-request-message mt-3 form-control fs-11 fw-400 border border-1 border-gray-300 rounded-1"
                    placeholder="Write description"></textarea>
            </div>

        </div>

    </div>

    <div class="bg-white px-20px py-4 border-top mt-auto">
        <button type="button" class="js-request-submit-btn
            fs-13 fw-700 text-dark bg-white hov-bg-light has-transition d-block text-center border border-1 border-gray-300 px-3 py-2 w-100 rounded-2">
            {{ translate('Post') }}</button>
    </div>
    
</div>