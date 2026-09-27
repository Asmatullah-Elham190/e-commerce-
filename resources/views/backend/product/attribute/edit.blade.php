<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Edit Attribute') }}
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
                class="nav-link text-reset attribute-lang-switch {{ $language->code == $lang ? 'active' : '' }}"
                data-id="{{ $attribute->id }}"
                data-lang="{{ $language->code }}">
                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                        height="11" class="mr-1">
                    <span>{{ $language->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <input type="hidden" id="edit_attribute_id" value="{{ $attribute->id }}">
    <input type="hidden" id="lang" value="{{ $lang }}">
    <div class="form-group mb-3 mt-3">
        <label for="name">{{ translate('Attribute Name') }}</label>
        <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name" class="form-control" required
            value="{{ $attribute->getTranslation('name', $lang) }}">
    </div>

    <div class="form-group mb-3">
        <label>{{ translate('Attribute Value') }}</label>
        <div id="attribute-wrapper">
            @forelse ($attribute->attribute_values as $value)
                <div class="row gutters-5 align-items-center mb-2">
                    <div class="col">
                        <div class="form-group">
                            <input type="hidden" name="attribute_value_ids[]" value="{{ $value->id }}">
                            <input type="text" class="form-control" name="attribute_values[]" maxlength="60"
                                value="{{ $value->value }}" placeholder="{{ translate('Enter Attribute Value') }}">
                        </div>
                    </div>

                    @if (!$loop->first)
                        <div class="col-auto">
                            <button type="button" class="mb-3 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger remove-row">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="row attribute-row gutters-5 align-items-center mb-2">
                    <div class="col">
                        <div class="form-group">
                            <input type="hidden" name="attribute_value_ids[]" value="">
                            <input type="text" class="form-control" placeholder="{{ translate('Enter Attribute Value') }}"
                                name="attribute_values[]" maxlength="60" value="">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mb-3 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger remove-row">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
            @endforelse

            <button type="button" id="attribute-add-row-btn"
                class="btn btn-block border border-dashed hov-bg-soft-secondary mb-5 fs-14 rounded-0 d-flex align-items-center justify-content-center">
                <i class="las la-plus"></i>
                <span class="ml-2">Add More</span>
            </button>
        </div>
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="update-attribute">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>
