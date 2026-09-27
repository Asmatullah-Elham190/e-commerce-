<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Edit Note') }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <ul class="nav nav-tabs nav-fill language-bar">
        @foreach (get_all_active_language() as $language)
            <li class="nav-item">
                <a href="javascript:void(0)"
                    class="nav-link text-reset note-lang-switch {{ $language->code == $lang ? 'active' : '' }}"
                    data-id="{{ $note->id }}" data-lang="{{ $language->code }}">
                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11" class="mr-1">
                    <span>{{ $language->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <input type="hidden" id="edit_note_id" value="{{ $note->id }}">
    <input type="hidden" id="lang" value="{{ $lang }}">
    <div class="form-group">
        <label class="col-form-label">{{ translate('Type') }}</label>
        <select name="note_type" required class="form-control aiz-selectpicker mb-2 mb-md-0">
            @foreach ($types as $type)
                <option value="{{ $type->value }}" class="text-uppercase" @selected($type->value == $note->note_type)>
                    {{ translate($type->name) }}
                </option>
            @endforeach
        </select>

    </div>

    <!-- Description -->
    <div class="form-group">
        <label class="col-from-label">
            {{ translate('Description') }} <i class="las la-language text-danger"
                title="{{ translate('Translatable') }}"></i>
            <p class="fs-10">({{ translate('Max 900 Character') }})</p>
        </label>
        <textarea name="description" rows="8" class="form-control">{{ $note->getTranslation('description', $lang) }}</textarea>
        @error('description')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="update-note">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>
