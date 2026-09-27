<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Note') }}
        </h6>
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <div class="form-group mb-3">
        <label class="">{{ translate('Type') }}</label>
        <select name="note_type" class="form-control aiz-selectpicker mb-2 mb-md-0" required disabled>
            @foreach ($types as $type)
                <option value="{{ $type->value }}" class="text-uppercase" @if(isset($note_type) && $note_type === $type->value) selected @endif>{{ translate($type->name) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <label class="">
            {{ translate('Description') }}
            <p class="fs-10">({{ translate('Max 900 Character') }})</p>
        </label>
        <textarea name="note_description" rows="8" class="form-control"></textarea>
        @error('description')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="add-note">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>