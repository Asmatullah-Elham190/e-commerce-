@if ($activity['type'] === 'promotion')
    @php $promotion = $activity['data']; @endphp
    <div class="px-20px py-20px border-bottom-dashed">
        <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
            <div
                class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                <img src="{{ uploaded_asset($admin->avatar_original) }}" class="h-100 w-100 img-fit lazyload" alt="">
            </div>
            <div class="flex-grow-1 d-flex flex-column align-items-start">
                <p class="mb-2">
                    <span class="fs-13 fw-400 text-dark">{{ translate('Admin created a promotion offer for') }}</span>

                    @if ($promotion->flash_sale_id && $promotion->flashSale)
                        <a class="fs-13 fw-700 text-reset has-transition">{{ $promotion->flashSale->title }}</a>
                        <span class="fs-13 fw-400 text-dark">{{ translate('for') }}</span>
                        <a class="fs-13 fw-700 text-reset has-transition">{{ translate('FLASH DEALS') }}</a>
                    @else
                        <a class="fs-13 fw-700 text-reset has-transition">{{ $promotion->promo_type_label }}</a>
                    @endif
                </p>
                <div class="d-flex align-items-center">
                    <span class="fs-11 fw-400 text-gray mr-3">{{ $promotion->created_at->format('M d Y, h:i a') }}</span>
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-between w-100 mt-3" style="gap: 12px;">
                    <div class="d-flex align-items-center flex-shrink-0" style="gap: 8px;">
                    </div>
                    <a href="{{ route('seller.promotional_products.index') }}"
                        class="fs-13 fw-400 text-reset hov-text-blue has-transition border border-1 border-gray-300 rounded-2 px-3 py-2 mt-1">
                        {{ translate('Join Campaign') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@elseif ($activity['type'] === 'message')
    @php $conversation = $activity['data']; @endphp
    <div class="px-20px py-20px border-bottom-dashed js-open-chat cursor-pointer"
        data-seller-id="{{ $conversation->seller_id }}">
        <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
            <div
                class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                <img src="{{ uploaded_asset($admin->avatar_original) }}" class="h-100 w-100 img-fit lazyload" alt="">
            </div>
            <div class="flex-grow-1 d-flex flex-column align-items-start">
                <p class="mb-2">
                    <a class="fs-13 fw-700 text-reset has-transition">{{ $admin->name }}</a>
                    <span class="fs-13 fw-400 text-dark">{{ translate('sent a message') }}</span>
                </p>
                <div class="bg-soft-light p-2 p-lg-3 rounded-2 mb-2 w-100">
                    <p class="fs-13 fw-400 text-dark font-italic mb-1">
                        {{ $conversation->lastSellerMessage->message }}
                    </p>
                    <div class="d-flex align-items-center">
                        <span
                            class="fs-11 fw-400 text-gray mr-3">{{ $conversation->lastSellerMessage->created_at->format('M d Y, h:i a') }}</span>
                    </div>
                </div>
                <a href="javascript:void(0);"
                    class="js-goto-messages fs-13 fw-400 text-reset hov-text-blue has-transition border border-1 border-gray-300 rounded-2 px-3 py-2 mt-1">
                    {{ translate('Open Messages') }}
                </a>
            </div>
        </div>
    </div>
@elseif ($activity['type'] === 'notice')
    @php $notice = $activity['data']; @endphp
    <div class="px-20px py-20px border-bottom-dashed">
        <div class="p-2 p-lg-3 d-flex flex-column justify-content-between rounded-2"
            style="background: {{ $notice->bg_color_hex }};">
            <div>
                <span class="fs-13 fw-400 text-dark">
                    {{ $notice->message }}
                    @if ($notice->id === 1)
                        <a class="text-reset hov-text-blue fw-600"
                            href="{{ route('seller.shop.verify') }}">{{ translate('here') }}</a>
                    @elseif ($notice->id === 2)
                        <a class="text-reset hov-text-blue fw-600"
                            href="{{ route('seller.shop.index') }}">{{ translate('this') }}</a>
                    @elseif ($notice->id === 3 && !empty($notice->expiry_date))
                        <span class="text-danger fw-600">{{ $notice->expiry_date }}</span>
                    @endif
                </span>
            </div>
            <div class="mt-2 d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
                <div class="d-flex align-items-center">
                    <span class="fs-11 fw-400 text-gray mr-3">{{ $notice->notice_type_label }}</span>
                    @if ($notice->notice_type === 'temporary' && $notice->notice_datetime)
                        <span class="fs-11 fw-400 text-gray">
                            {{ translate('Expired at') }}: {{ $notice->notice_datetime_formatted }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif