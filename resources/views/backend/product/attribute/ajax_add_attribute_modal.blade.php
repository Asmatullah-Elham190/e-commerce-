<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Attribute') }}
        </h6>
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <div class="form-group mb-3">
        <label for="name">{{ translate('Attribute Name') }}</label>
        <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label>{{ translate('Attribute Value') }}</label>
        <div id="attribute-wrapper">
            <div class="row gutters-5 mb-2 attribute-row">
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="{{translate('Enter Attribute Value')}}"
                            name="attribute_values[]" maxlength="60" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" id="attribute-remove-row-btn" 
                        class="my-1 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger">
                        <i class="las la-times"></i>
                    </button>
                </div>
            </div>

            <button type="button" id="attribute-add-row-btn"
                class="btn btn-block border border-dashed hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center">
                <i class="las la-plus"></i>
                <span class="ml-2">Add More</span>
            </button>
        </div>
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="add-attribute">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>
