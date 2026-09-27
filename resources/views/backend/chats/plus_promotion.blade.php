{{-- plus_promotion blade --}}
<h6 class="fs-13 fw-700 text-reset mb-1">
    {{ translate('Select Audience') }}
    <span class="text-danger">*</span>
</h6>
<span class="fs-11 fw-400 text-gray">{{ translate('Select audience from all seller or select sellers') }}</span>
<div class="mt-3 d-flex flex-column">
    <div class="form-check form-check-inline">
        <input class="form-check-input js-promo-audience" type="radio" name="promo_audience" id="all-seller" value="all"
            checked>
        <label class="form-check-label fs-13 fw-400 text-dark cursor-pointer"
            for="all-seller">{{ translate('All Seller') }}</label>
    </div>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input flex-shrink-0 js-promo-audience" type="radio" name="promo_audience"
            id="seller-form" value="specific">
        <select class="form-control aiz-selectpicker fs-13 fw-400 text-gray js-promo-sellers" multiple
            data-live-search="true" data-selected-text-format="count > 1"
            data-count-selected-text="{0} {{ translate('sellers selected') }}" name="seller_ids[]" disabled>
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

<div class="mt-4 py-3 d-flex flex-column">
    <h6 class="fs-13 fw-700 text-reset mb-1">
        {{ translate('Select Promotion & Campaign') }}
        <span class="text-danger">*</span>
    </h6>
    <span class="fs-11 fw-400 text-gray">{{ translate('Select Promotion or Campaign type') }}</span>

    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input js-promo-type" type="radio" name="promo_type" id="todays-deal"
            value="todays_deal">
        <label class="form-check-label fs-13 fw-400 text-dark cursor-pointer"
            for="todays-deal">{{ translate("Today's Deal") }}</label>
    </div>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input js-promo-type" type="radio" name="promo_type" id="featured-product"
            value="featured_products">
        <label class="form-check-label fs-13 fw-400 text-dark cursor-pointer"
            for="featured-product">{{ translate("Featured Products") }}</label>
    </div>
    <div class="form-check form-check-inline mt-3">
        <input class="form-check-input flex-shrink-0 js-promo-type" type="radio" name="promo_type" id="flash-deals"
            value="flash_deals">
        <select class="form-control aiz-selectpicker fs-13 fw-400 text-gray js-promo-flash-deals mt-2"
            data-live-search="true" name="flash_sale_id" disabled>
            <option value="">{{ translate('Select Flash Sale') }}</option>
            @foreach ($flash_deals as $flash_deal)
                <option value="{{ $flash_deal->id }}" data-content="<span class='d-flex align-items-center'>
                            <img src='{{ uploaded_asset($flash_deal->banner) }}' class='img-fit mr-2' style='width:20px;height:20px;object-fit:cover;'>
                            <span>{{ $flash_deal->title }}</span>
                        </span>">
                    {{ $flash_deal->title }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4 py-3">
    <h6 class="fs-13 fw-700 text-reset mb-1">
        {{ translate('Content') }}
    </h6>
    <span class="fs-11 fw-400 text-gray">{{ translate('Write content here') }}</span>
    <textarea rows="4" class="js-promo-content mt-3 form-control fs-11 fw-400 border border-1 border-gray-300 rounded-1"
        placeholder="Write message" name="message"></textarea>
</div>