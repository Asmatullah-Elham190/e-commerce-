<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Color') }}
        </h6>
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <div class="form-group mb-3">
        <label for="name">{{ translate('Name') }}</label>
        <input type="text" placeholder="{{ translate('Name') }}" id="name" name="color_name" class="form-control"
             required>
    </div>
    <div class="form-group mb-3">
        <label for="name">{{ translate('Color Code') }}</label>
        <input type="text" placeholder="{{ translate('Color Code') }}" id="code" name="code" class="form-control"
            required>
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="add-color">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>