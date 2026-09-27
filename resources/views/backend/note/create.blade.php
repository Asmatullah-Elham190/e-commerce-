<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Note') }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px">
    <div class="form-group">
        <label class="col-form-label">{{ translate('Type') }}</label>
        <select name="note_type" class="form-control aiz-selectpicker mb-2 mb-md-0" required>
            @foreach ($types as $type)
                <option value="{{ $type->value }}" class="text-uppercase">{{ translate($type->name) }}</option>
            @endforeach
        </select>
    </div>

    <!-- Description -->
    <div class="form-group">
        <label class="col-from-label">
            {{ translate('Description') }}
            <p class="fs-10">({{ translate('Max 900 Character') }})</p>
        </label>
        <textarea name="description" rows="8" class="form-control">{{ old('description') }}</textarea>
        @error('description')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="store-new-note">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>