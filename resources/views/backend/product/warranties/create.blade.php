<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Warranty') }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px">
    <div class="form-group mb-3">
        <label for="name">{{ translate('Warranty Text') }}</label>
        <input type="text" placeholder="{{ translate('Name') }}" id="text" name="text" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="name">{{translate('Logo')}} <small>({{ translate('40x40') }})</small></label>
        <div class="input-group" data-toggle="aizuploader" data-type="image">
            <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
            </div>
            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
            <input type="hidden" name="logo" class="selected-files">
        </div>
        <div class="file-preview box sm">
        </div>
        <small class="text-muted">{{ translate('Minimum dimensions required: 40px width X 40px height.') }}</small>
    </div>
</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="store-new-warranty">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>