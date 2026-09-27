<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Brand') }}
        </h6> 
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <div class="form-group mb-3">
        <label for="name">{{translate('Name')}}</label>
        <input type="text" placeholder="{{translate('Name')}}" maxlength="100" name="brand_name" class="form-control"
            required>
    </div>
    <div class="form-group mb-3">
        <label for="name">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>
        <div class="input-group" data-toggle="aizuploader" data-type="image">
            <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
            </div>
            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
            <input type="hidden" name="logo" class="selected-files">
        </div>
        <div class="file-preview box sm">
        </div>
        <small class="text-muted">{{ translate('Minimum dimensions required: 120px width X 80px height.') }}</small>
    </div>
    <div class="form-group mb-3">
        <label for="name">{{translate('Meta Title')}}</label>
        <input type="text" class="form-control" name="brand_meta_title" placeholder="{{translate('Meta Title')}}">
    </div>
    <div class="form-group mb-3">
        <label for="name">{{translate('Meta Description')}}</label>
        <textarea name="brand_meta_description" rows="5" class="form-control"></textarea>
    </div>
    <div class="form-group mb-3">
        <label for="name">{{ translate('Meta Keywords') }}</label>
        <textarea name="brand_meta_keywords" class="resize-off form-control"
            placeholder="{{translate('Keyword, Keyword')}}"></textarea>
        <small class="text-muted">{{ translate('Separate with coma') }}</small>
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="add-brand">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>