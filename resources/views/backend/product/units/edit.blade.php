<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Edit Unit') }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">✕</button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px">
    <input type="hidden" id="edit_unit_id" value="{{ $unit->id }}">
    <div class="form-group">
        <label class="col-from-label" for="edit_name">{{ translate('Name') }}</label>
        <input type="text" placeholder="{{ translate('Name') }}" id="edit_name" name="edit_name"
            class="form-control" required value="{{ $unit->name }}">
    </div>
</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="update-unit">
            {{ translate('Update') }}
        </button>
    </div>
</div>