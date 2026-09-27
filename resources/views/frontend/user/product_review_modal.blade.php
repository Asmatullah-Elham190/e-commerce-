<!-- Offcanvas Header -->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fs-16 fw-700 text-dark mb-0">
            {{ translate('Review') }}
        </h6>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
        </button>
    </div>
</div>

@if($review == null)
    <!-- Offcanvas Body -->
    <div class="right-offcanvas-body position-absolute h-100 px-30px pt-20px mb-3">

        <input type="hidden" id="modal_product_id" name="product_id" value="{{ $product->id }}">
        <input type="hidden" id="modal_order_id" name="order_id" value="{{ $order_id }}">

        <div class="form-group mb-3">
            <label class="fw-700">{{ translate('Product') }}</label>

            <div class="d-flex align-items-center gap-3">
                <!-- Product Image -->
                <img class="w-80px h-80px border border-gray-400 mr-2" src="{{ uploaded_asset($product->thumbnail_img) }}"
                    alt="">
                <!-- Product Name -->
                <p class="mb-0">{{ $product->getTranslation('name') }}</p>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="fw-700">{{ translate('Rating') }} <span class="text-danger">*</span></label>
            <div class="rating rating-input">
                <label>
                    <input type="radio" name="rating" value="1" required>
                    <i class="las la-star"></i>
                </label>
                <label>
                    <input type="radio" name="rating" value="2">
                    <i class="las la-star"></i>
                </label>
                <label>
                    <input type="radio" name="rating" value="3">
                    <i class="las la-star"></i>
                </label>
                <label>
                    <input type="radio" name="rating" value="4">
                    <i class="las la-star"></i>
                </label>
                <label>
                    <input type="radio" name="rating" value="5">
                    <i class="las la-star"></i>
                </label>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="fw-700">{{ translate('Comment') }} <span class="text-danger">*</span></label>
            <textarea lang="en" rows="4" class="form-control mb-3 rounded-0" name="comment"
                placeholder="{{ translate('Your review') }}"></textarea>
        </div>

        <div class="form-group mb-5">
            <label class="fw-700">{{ translate('Review Images') }}</label> (<span class="fs-11">{{ translate('Max 6 files')}}</span>)
            <div class="bypass-img-upload-container">
                <div class="direct-uploader flex-shrink-0" data-direct-upload="true">
                    <img src="{{ static_asset('assets/img/plus-lg.svg') }}" class="upload-icon">
                    <input type="file" name="photos[]" accept=".jpeg,.jpg,.webp,.png" multiple>
                </div>
                <div class="file-preview box sm direct-preview"></div>
            </div>
            <div class="upload-error text-danger mt-1"></div>
            @error('photos')
                <span class="text-danger d-block mt-1">{{ $message }}</span>
            @enderror

            @error('photos.*')
                <span class="text-danger d-block mt-1">{{ $message }}</span>
            @enderror
            <small class="text-muted">
                {{translate('These images are visible in product review page gallery')}}
            </small>
        </div>
    </div>

    <!-- Offcanvas Footer -->
    <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
        <div class="d-flex justify-content-end">
            <button type="button" class="fs-14 fw-700 py-10px px-20px btn btn-primary" id="review-store">
                {{ translate('Confirm') }}
            </button>
        </div>
    </div>
@else
    <div class="right-offcanvas-body position-absolute h-100 px-10px pt-20px">

    <!-- Review -->
    <li class="media list-group-item d-flex">
        <div class="media-body text-left">
            <div class="form-group mb-3">
                <label class="fw-700">{{ translate('Product') }}</label>

                <div class="d-flex align-items-center gap-3">
                    <!-- Product Image -->
                    <img class="w-80px h-80px border border-gray-400 mr-2"
                        src="{{ uploaded_asset($product->thumbnail_img) }}" alt="">
                    <!-- Product Name -->
                    <p class="mb-0">{{ $product->getTranslation('name') }}</p>
                </div>
            </div>
            <!-- Rating -->
            <div class="form-group">
                <label class="opacity-60">{{ translate('Rating')}}</label>
                <p class="rating rating-sm">
                    @for ($i = 0; $i < $review->rating; $i++)
                        <i class="las la-star active"></i>
                    @endfor
                    @for ($i = 0; $i < 5 - $review->rating; $i++)
                        <i class="las la-star"></i>
                    @endfor
                </p>
            </div>
            <!-- Comment -->
            <div class="form-group">
                <label class="opacity-60">{{ translate('Comment')}}</label>
                <p class="comment-text">
                    {{ $review->comment }}
                </p>
            </div>
            <!-- Review Images -->
            @if($review->photos != null)
                <div class="form-group">
                    <label class="opacity-60">{{ translate('Images')}}</label>
                    <div class="d-flex flex-wrap">
                        @foreach (explode(',', $review->photos) as $photo)
                            <div class="mr-3 mb-3 size-90px">
                                <a href="javascript:void(0)" onclick="openReviewImageModal('{{ uploaded_asset($photo) }}')">
                                    <img class="img-fit h-100 lazyload border" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($photo) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </li>
    </div>
@endif