<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Add New Category') }}
        </h6>
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            ✕
        </button>
    </div>
</div>

<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute h-100 px-30px pt-20px">

    <div class="form-group mb-3">
        <label class="col-form-label">{{translate('Name')}}</label>
        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" maxlength="255"
            class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label">{{ translate('Type') }}</label>
        <div class="d-flex justify-content-center align-items-center">
            <!-- Physical Option -->
            <div class="form-control d-flex align-items-center justify-content-start mr-3 type-option border-primary"
                data-value="0">
                <input type="radio" name="digital" value="0" class="mr-2" checked
                    onchange="categoriesByType(this.value)">
                <label class="mb-0 fs-14">{{ translate('Physical') }}</label>
            </div>
            <!-- Digital Option -->
            <div class="form-control d-flex align-items-center justify-content-start type-option" data-value="1">
                <input type="radio" name="digital" value="1" class="mr-2" onchange="categoriesByType(this.value)">
                <label class="mb-0 fs-14">{{ translate('Digital') }}</label>
            </div>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class=" col-form-label">{{translate('Parent Category')}}</label>
        <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2"
            data-placeholder="Choose ..." data-live-search="true">
            @include('backend.product.categories.categories_option', ['categories' => $categories])
        </select>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label">{{translate('Ordering Number')}}</label>
        <input type="number" integer-only name="order_level" class="form-control" id="order_level"
            placeholder="{{translate('Order Level')}}">
        <small>{{translate('Higher number has high priority')}}</small>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label" for="signinSrEmail">{{translate('Banner')}}</label>
        <div class="input-group" data-toggle="aizuploader" data-type="image">
            <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
            </div>
            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
            <input type="hidden" name="banner" class="selected-files">
        </div>
        <div class="file-preview box sm">
        </div>
        <small class="text-muted">{{ translate('Minimum dimensions required: 150px width X 150px height.') }}</small>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label" for="signinSrEmail">{{translate('Icon')}}</label>
        <div class="input-group" data-toggle="aizuploader" data-type="image">
            <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
            </div>
            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
            <input type="hidden" name="icon" class="selected-files">
        </div>
        <div class="file-preview box sm">
        </div>
        <small class="text-muted">{{ translate('Minimum dimensions required: 16px width X 16px height.') }}</small>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label" for="signinSrEmail">{{translate('Cover Image')}}</label>
        <div class="input-group" data-toggle="aizuploader" data-type="image">
            <div class="input-group-prepend">
                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
            </div>
            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
            <input type="hidden" name="cover_image" class="selected-files">
        </div>
        <div class="file-preview box sm">
        </div>
        <small class="text-muted">{{ translate('Minimum dimensions required: 260px width X 260px height.') }}</small>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label">{{translate('Meta Title')}}</label>
        <input type="text" class="form-control" name="cat_meta_title" placeholder="{{translate('Meta Title')}}">
    </div>

    <div class="form-group mb-3">
        <label class="col-form-label">{{translate('Meta Description')}}</label>
        <textarea name="cat_meta_description" rows="5" class="form-control"></textarea>
    </div>
    <div class="form-group mb-3">
        <label class="col-form-label">{{translate('Meta Keywords')}}</label>
        <textarea name="meta_keywords" class="resize-off form-control"
            placeholder="{{translate('Keyword, Keyword')}}"></textarea>
        <small class="text-muted">{{ translate('Separate with coma') }}</small>
    </div>
    <div class="form-group mb-5">
        <label class="col-form-label">{{translate('Filtering Attributes')}}</label>
        <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]" data-toggle="select2"
            data-placeholder="Choose ..." data-live-search="true" multiple>
            @foreach (\App\Models\Attribute::all() as $attribute)
                <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
            @endforeach
        </select>
    </div>

</div>

<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <div class="d-flex justify-content-end">
        <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="add-category">
            {{ translate('Confirm') }}
        </button>
    </div>
</div>

<script type="text/javascript">
    function categoriesByType(val) {
        $('.type-option').removeClass('border-primary');
        $('.type-option[data-value="' + val + '"]').addClass('border-primary');
        $('select[name="parent_id"]').html('');
        AIZ.plugins.bootstrapSelect('refresh');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '{{ route('categories.categories-by-type') }}',
            data: {
                digital: val
            },
            success: function (data) {
                $('select[name="parent_id"]').html(data);
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }
</script>