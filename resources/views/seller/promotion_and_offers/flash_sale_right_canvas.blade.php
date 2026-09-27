<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mr-2 mt-0 mb-0 p-0">{{ translate('Mark as Flash Sale') }}</h6>
        <button onclick="closeRightcanvas()" class="border-0 bg-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                <path d="M228.588-716.31l-.9-.9,7.1-7.1-7.1-7.1.9-.9,7.1,7.1,7.1-7.1.9.9-7.1,7.1,7.1,7.1-.9.9-7.1-7.1Z"
                    transform="translate(-227.69 732.31)" fill="#a5a5b8" />
            </svg>
        </button>
    </div>
</div>

<div class="right-offcanvas-body position-absolute h-100 px-30px">
    <input type="hidden" id="flash_sale_product_id" value="">

    <div class="form-group mt-3">
        <label class="fs-13 fw-500">{{ translate('Select Flash Sale') }}</label>
        <select class="form-control aiz-selectpicker" id="flash_sale_select" data-live-search="true">
            <option value="">{{ translate('Choose Flash Sale') }}</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <label class="fs-13 fw-500">{{ translate('Discount (%)') }}</label>
        <input type="number" min="0" step="0.01" class="form-control" id="flash_sale_discount" placeholder="{{ translate('Enter discount') }}">
    </div>
</div>

<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px" id="offcanvas-btn">
    <div class="d-flex justify-content-end footer-btn">
        <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel" onclick="closeRightcanvas()">{{ translate('Cancel') }}</button>
        <button type="button" class="d-block fs-14 fw-700 py-10px save action-btn" id="flash-sale-submit-btn">{{ translate('Submit') }}</button>
    </div>
</div>