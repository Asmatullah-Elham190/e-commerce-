{{-- all_chat_modal --}}
<div class="border-bottom pb-15px px-20px d-flex align-items-center justify-content-between">
    <h5 class="fs-16 fw-700 text-truncate m-0">{{ translate('Seller Hub') }}</h5>
    <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent p-0">
        <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
    </button>
</div>

<div class="global-right-offcanvas-body w-100 px-0 py-0 d-flex flex-column"
    style="overflow: hidden !important; height: calc(100vh - 60px) !important;">

    <div class="common-sm-nav-tabs-container w-100">
        <ul class="nav nav-tabs flex-nowrap common-sm-nav-tabs px-20px pt-5px" id="common-sm-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mr-3 active" id="nav-all-tab" data-toggle="tab"
                    href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">{{ translate('All') }}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3" id="nav-notices-tab" data-toggle="tab"
                    href="#nav-notices" role="tab" aria-controls="nav-notices"
                    aria-selected="false">{{ translate('Notices') }}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3 position-relative" id="nav-requests-tab"
                    data-toggle="tab" href="#nav-requests" role="tab" aria-controls="nav-requests"
                    aria-selected="false">
                    {{ translate('Requests') }}
                    @if ($hasUnseenRequests ?? false)
                        <span class="bg-danger rounded-circle position-absolute"
                            style="width: 5px; height: 5px; top: 6px; right: -12px;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3 position-relative" id="nav-promotions-tab"
                    data-toggle="tab" href="#nav-promotions" role="tab" aria-controls="nav-promotions"
                    aria-selected="false">
                    {{ translate('Promotions') }}
                    @if ($hasUnseenPromotionParticipates ?? false)
                        <span class="bg-danger rounded-circle position-absolute"
                            style="width: 5px; height: 5px; top: 6px; right: -12px;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3 position-relative" id="nav-messages-tab" data-toggle="tab"
                    href="#nav-messages" role="tab" aria-controls="nav-messages"
                    aria-selected="false">
                    {{ translate('Messages') }}
                    @if ($hasUnseenMessages ?? false)
                        <span class="bg-danger rounded-circle position-absolute"
                            style="width: 5px; height: 5px; top: 6px; right: -12px;"></span>
                    @endif
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link fs-13 fw-400 text-reset px-0 mx-3" id="nav-preset-notice-tab" data-toggle="tab"
                    href="#nav-preset-notice" role="tab" aria-controls="nav-preset-notice"
                    aria-selected="false">{{ translate('Preset Notices') }}</a>
            </li>

            <li class="nav-item mt-1 ml-3 ml-md-auto" role="presentation">
                <a class="nav-link plus-nav-link px-0 w-25px h-25px rounded-circle border-0 bg-soft-blue hov-bg-soft-light has-transition d-flex align-items-center justify-content-center"
                    id="nav-plus-tab" data-toggle="tab" href="#nav-plus" role="tab" aria-controls="nav-plus"
                    aria-selected="false">
                    <svg id="Group_40038" data-name="Group 40038" xmlns="http://www.w3.org/2000/svg" width="12"
                        height="12" viewBox="0 0 12 12">
                        <rect id="Rectangle_24889" data-name="Rectangle 24889" width="12" height="1.5"
                            transform="translate(6.75) rotate(90)" fill="#0b80fd" />
                        <rect id="Rectangle_24891" data-name="Rectangle 24891" width="12" height="1.5"
                            transform="translate(0 5.25)" fill="#0b80fd" />
                    </svg>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content flex-grow-1 d-flex flex-column overflow-hidden" id="common-sm-tabsContent">

        <div class="tab-pane fade show active flex-grow-1 h-100" id="nav-all" role="tabpanel"
            aria-labelledby="nav-all-tab">
            <div class="d-flex flex-column justify-content-between h-100">
                <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
                    <div class="d-flex flex-column">

                        @forelse ($activities as $activity)
                            @if ($activity['type'] === 'promotion')
                                @php 
                                    $promotion = $activity['data']; 
                                    $admin = \App\Models\User::where('user_type', 'admin')->first();
                                @endphp
                                <div class="px-20px py-20px border-bottom-dashed">
                                    <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
                                        <div
                                            class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                                            <img src="{{ uploaded_asset($admin->avatar_original) }}"
                                                class="h-100 w-100 img-fit lazyload" alt="">
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column align-items-start">
                                            <p class="mb-2">
                                                <span
                                                    class="fs-13 fw-400 text-dark">{{ translate('You created a promotion offer for') }}</span>

                                                @if ($promotion->flash_sale_id && $promotion->flashSale)
                                                    <a 
                                                        class="fs-13 fw-700 text-reset has-transition">{{ $promotion->flashSale->title }}</a>
                                                    <span class="fs-13 fw-400 text-dark">{{ translate('for') }}</span>
                                                    <a 
                                                        class="fs-13 fw-700 text-reset has-transition">{{ translate('FLASH DEALS') }}</a>
                                                @else
                                                    <a 
                                                        class="fs-13 fw-700 text-reset has-transition">{{ $promotion->promo_type_label }}</a>
                                                @endif
                                            </p>
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="fs-11 fw-400 text-gray mr-3">{{ $promotion->created_at->format('M d Y, h:i a') }}</span>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center justify-content-between w-100 mt-3"
                                                style="gap: 12px;">
                                                <div class="d-flex align-items-center flex-shrink-0" style="gap: 8px;">
                                                    @if ($promotion->respondedSellers->count() > 0)
                                                        <div class="symbol-group">
                                                            @foreach ($promotion->respondedSellers->take(3) as $respondedSeller)
                                                                <div class="symbol size-40px rounded-content overflow-hidden"
                                                                    title="{{ $respondedSeller->name ?? '' }}">
                                                                    <img src="{{ $respondedSeller->shop && $respondedSeller->shop->logo ? uploaded_asset($respondedSeller->shop->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                                                        class="h-100 w-100 img-fit lazyload" alt="">
                                                                </div>
                                                            @endforeach
                                                            @if ($promotion->respondedSellers->count() > 3)
                                                                <div class="symbol size-40px rounded-content overflow-hidden bg-success d-flex align-items-center justify-content-center"
                                                                    title="">
                                                                    <span
                                                                        class="fs-10 fw-600 text-white">{{ '+' . ($promotion->respondedSellers->count() - 3) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <span
                                                        class="fs-11 fw-400 text-gray">{{ $promotion->respondedSellers->count() . ' ' . translate('Responded') }}</span>
                                                </div>
                                                <a href="{{route('seller_promotional_products.index')}}"
                                                    class="fs-13 fw-400 text-reset hov-text-blue has-transition border border-1 border-gray-300 rounded-2 px-3 py-2 mt-1">
                                                    {{ translate('Open Campaign Requests') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @elseif ($activity['type'] === 'message')
                                @php $conversation = $activity['data']; @endphp
                                <div class="px-20px py-20px border-bottom-dashed js-open-chat"
                                    data-seller-id="{{ $conversation->seller_id }}">
                                    <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
                                        <div class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                                            <img src="{{ $conversation->shop && $conversation->shop->logo ? uploaded_asset($conversation->shop->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                                class="h-100 w-100 img-fit lazyload" alt="">
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column align-items-start">
                                            <p class="mb-2">
                                                <a class="fs-13 fw-700 text-reset has-transition">{{ $conversation->shop->name ?? translate('Unknown Shop') }}</a>
                                                <span class="fs-13 fw-400 text-dark">{{ translate('sent a message') }}</span>
                                            </p>
                                            <div class="bg-soft-light p-2 p-lg-3 rounded-2 mb-2 w-100">
                                                <p class="fs-13 fw-400 text-dark font-italic mb-1">
                                                    {{ $conversation->lastSellerMessage->message }}
                                                </p>
                                                <div class="d-flex align-items-center">
                                                    <span class="fs-11 fw-400 text-gray mr-3">{{ $conversation->lastSellerMessage->created_at->format('M d Y, h:i a') }}</span>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" 
                                                class="js-open-message-from-activity fs-13 fw-400 text-reset hov-text-blue has-transition border border-1 border-gray-300 rounded-2 px-3 py-2 mt-1"
                                                data-seller-id="{{ $conversation->seller_id }}">
                                                {{ translate('Open Messages') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($activity['type'] === 'request')
                                @php $req = $activity['data']; $item = $req->item_label; @endphp
                                <div class="px-20px py-20px border-bottom-dashed">
                                    <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
                                        <div class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                                            <img src="{{ $req->seller?->shop?->logo ? uploaded_asset($req->seller->shop->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                                class="h-100 w-100 img-fit lazyload" alt="">
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column align-items-start">
                                            <p class="mb-2">
                                                <a href="#" class="fs-13 fw-700 text-reset hov-text-blue has-transition">{{ $req->seller?->shop?->name ?? $req->seller?->name ?? translate('Unknown Seller') }}</a>
                                                <span class="fs-13 fw-400 text-dark">
                                                    @if ($item)
                                                        {{ translate('requested to add a') }} {{ strtolower(translate($item['type'])) }} {{ translate('named') }} {{ $item['value'] }}.
                                                    @else
                                                        {{ translate('created a request.') }}
                                                    @endif
                                                </span>
                                            </p>

                                            @if (!empty($req->message))
                                                <div class="bg-soft-light p-2 p-lg-3 rounded-2 mb-2 w-100">
                                                    <p class="fs-13 fw-400 text-dark font-italic mb-0">
                                                        {{ $req->message }}
                                                    </p>
                                                </div>
                                            @endif

                                            <div class="d-flex align-items-center">
                                                <span class="fs-11 fw-400 text-gray mr-3">{{ $req->created_at->format('M d Y, h:i a') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="px-20px py-40px text-center mt-4">
                                <span class="fs-13 fw-400 text-gray">{{ translate('No activity found') }}</span>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-notices" role="tabpanel" aria-labelledby="nav-notices-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-requests" role="tabpanel"
            aria-labelledby="nav-requests-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-promotions" role="tabpanel"
            aria-labelledby="nav-promotions-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-messages" role="tabpanel"
            aria-labelledby="nav-messages-tab">
            <div id="chat-list-view" class="h-100">
                @include('backend.chats.chat_list')
            </div>

            <div id="chat-single-view" class="d-none h-100">
            </div>

        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-preset-notice" role="tabpanel"
            aria-labelledby="nav-preset-notice-tab">
        </div>

        <div class="tab-pane fade flex-grow-1 h-100" id="nav-plus" role="tabpanel" aria-labelledby="nav-plus-tab">

        </div>
    </div>
</div>