<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Flash Sale') }}
        </h6>
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <div class="form-group mb-3">
        <label class="" for="name">{{translate('Title')}}</label>
        <input type="text" placeholder="{{translate('Title')}}" id="name" name="title" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label class="" for="start_date">{{translate('Date')}}</label>
        <input type="text" class="form-control aiz-date-range" name="date_range"
            placeholder="{{ translate('Select Date') }}" data-time-picker="true"
            data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
            required>    
    </div>
    <div class="form-group mb-3">
        <label class="" for="signinSrEmail">{{translate('Banner')}}</label>
        <div class="col-12 pl-10px">
            <div class="img-upload-container">
                <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                    data-toggle="aizuploader" data-type="image" style="margin-left: -10px;">
                    <div
                        class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                        <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                            class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                    </div>
                    <input type="hidden" name="fs_thumbnail_img" class="selected-files">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
        <span
            class="small text-muted">{{ translate('This image is shown as cover banner in flash deal details page. Minimum dimensions required: 436px width X 443px height.') }}</span>
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="add-flash-sale-confirm">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>