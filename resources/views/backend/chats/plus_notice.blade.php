{{-- plus_notice --}}
<div class="mb-3">
    <button type="button"
        class="js-goto-preset-notice border-0 bg-transparent fs-13 fw-500 text-blue p-0 hov-opacity-80 has-transition">
        {{ translate('Load from Preset') }}
    </button>
</div>
<h6 class="fs-13 fw-700 text-reset mb-1">
    {{ translate('Select Audience') }}
    <span class="text-danger">*</span>
</h6>
<span class="fs-11 fw-400 text-gray">{{ translate('Select audience from all seller or select sellers') }}</span>
<div class="mt-3 d-flex flex-column">
    <div class="form-check form-check-inline">
        <input class="form-check-input js-notice-audience" type="radio" name="notice_audience"
            id="notices-all-seller" value="all" checked>
        <label class="form-check-label fs-13 fw-400 text-dark cursor-pointer"
            for="notices-all-seller">{{ translate('All Seller') }}</label>
    </div>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input flex-shrink-0 js-notice-audience" type="radio" name="notice_audience"
            id="notices-seller-form" value="specific">
        <select class="form-control aiz-selectpicker fs-13 fw-400 text-gray js-notice-sellers" multiple
            data-live-search="true" data-selected-text-format="count > 1"
            data-count-selected-text="{0} {{ translate('sellers selected') }}"
            name="seller_ids[]" disabled>
            @foreach ($shops as $shop)
                <option value="{{ $shop->user_id }}" data-content="<span class='d-flex align-items-center'>
                                <img src='{{ uploaded_asset($shop->logo) }}' class='img-fit rounded-circle mr-2' style='width:20px;height:20px;object-fit:cover;'>
                                <span>{{ $shop->name }}</span>
                            </span>">
                    {{ $shop->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<input type="hidden" name="notice_id" class="js-notice-id" value="">
<div class="mt-4 py-3 d-flex flex-column">
    <h6 class="fs-13 fw-700 text-reset mb-1">
        {{ translate('Select Notice Type') }}
        <span class="text-danger">*</span>
    </h6>
    <span class="fs-11 fw-400 text-gray">{{ translate('Select Promotion or Campaign type') }}</span>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input js-notice-type" type="radio" name="notice_type" id="permanent-notice"
            value="permanent" checked>
        <label class="form-check-label fs-13 fw-400 text-dark cursor-pointer"
            for="permanent-notice">{{ translate("Permanent Notice") }}</label>
    </div>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input js-notice-type" type="radio" name="notice_type" id="temporary-notice"
            value="temporary">
        <label class="form-check-label fs-13 fw-400 text-dark cursor-pointer"
            for="temporary-notice">{{ translate("Temporary Notice") }}</label>
    </div>
    <div class="form-check form-check-inline mt-3 ml-3">
        <input type="date" name="notice_datetime"
            class="js-notice-datetime form-check-label fs-13 fw-400 text-gray border border-1 border-gray-300 rounded-2 px-2 py-2 w-100"
            placeholder="Select Time & Date" disabled />
    </div>
</div>
<!-- Content -->
<div class="mt-4 py-3">
    <h6 class="fs-13 fw-700 text-reset mb-1">
        {{ translate('Content') }}
        <span class="text-danger">*</span>
    </h6>
    <span class="fs-11 fw-400 text-gray">{{ translate('Write content here') }}</span>
    <textarea name="message" rows="4" class="js-notice-content mt-3 form-control fs-11 fw-400 border border-1 border-gray-300 rounded-1"
        placeholder="Write message"></textarea>
</div>
<!-- Background Color -->
<div class="mt-4 py-3">
    <h6 class="fs-13 fw-700 text-reset mb-1">
        {{ translate('Background Color') }}
        <span class="text-danger">*</span>
    </h6>
    <span class="fs-11 fw-400 text-gray">{{ translate('Choose the background color of notice') }}</span>
    <div class="aiz-radio-inline mt-3">
        <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="Light Blue" title="">
            <input type="radio" class="js-notice-color" name="bg_color" value="Light Blue" checked>
            <span class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1 rounded-circle">
                <span class="size-25px d-inline-block rounded-circle" style="background: #f1fafd;"></span>
            </span>
        </label>

        <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="Light Pink" title="">
            <input type="radio" class="js-notice-color" name="bg_color" value="Light Pink">
            <span class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1 rounded-circle">
                <span class="size-25px d-inline-block rounded-circle" style="background: #fff4f8;"></span>
            </span>
        </label>

        <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="Light Green" title="">
            <input type="radio" class="js-notice-color" name="bg_color" value="Light Green">
            <span class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1 rounded-circle">
                <span class="size-25px d-inline-block rounded-circle" style="background: #e4ffea;"></span>
            </span>
        </label>

        <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="Light Yellow" title="">
            <input type="radio" class="js-notice-color" name="bg_color" value="Light Yellow">
            <span class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1 rounded-circle">
                <span class="size-25px d-inline-block rounded-circle" style="background: #fff9e3;"></span>
            </span>
        </label>

        <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="Light Gray" title="">
            <input type="radio" class="js-notice-color" name="bg_color" value="Light Gray">
            <span class="aiz-megabox-elem d-flex align-items-center justify-content-center p-1 rounded-circle">
                <span class="size-25px d-inline-block rounded-circle" style="background: #f2f2f8;"></span>
            </span>
        </label>
    </div>
</div>
<div class="mt-4 py-3">
    <div class="d-flex align-items-center mb-2">
        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
            <input value="1" name="save_as_preset" class="js-notice-save-preset" type="checkbox" checked>
            <span class="slider round"></span>
        </label>
        <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Save as Preset') }}</span>
    </div>
</div>