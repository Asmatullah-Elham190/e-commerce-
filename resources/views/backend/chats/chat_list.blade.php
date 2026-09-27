{{-- backend/chats/chat_list.blade.php --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">
            <!--Single-->
            <div class="d-flex align-items-center flex-shrink-0 message-list-top c-scrollbar-light px-20px py-20px border-bottom" style="gap: 19px;">
                @foreach ($shops as $shop)
                    <a href="javascript:void(0);" title="{{ $shop->name }}"
                        class="js-open-chat fs-11 fw-400 text-reset text-center hov-text-blue has-transition d-block"
                        data-seller-id="{{ $shop->user_id }}">
                        <div class="w-40px h-40px mx-auto mb-1 d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                            <img src="{{ uploaded_asset($shop->logo) }}" class="h-100 w-100 img-fit lazyload" alt="">
                        </div>
                        <span class="text-truncate d-block w-70px">{{ $shop->name }}</span>
                    </a>
                @endforeach
            </div>
            <!--Single-->

            @foreach ($conversations as $key => $conversation)
                @php $unseenCount = $conversation->unseenCount ?? 0; @endphp
                <div class="js-open-chat px-20px py-20px border-bottom-dashed bg-white hov-bg-light has-transition cursor-pointer"
                    data-seller-id="{{ $conversation->seller_id }}">
                    <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
                        <div class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                            <img src="{{ $conversation->shop && $conversation->shop->logo ? uploaded_asset($conversation->shop->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                class="h-100 w-100 img-fit lazyload" alt="">
                        </div>

                        <div class="flex-grow-1 d-flex flex-column align-items-start overflow-hidden w-100" style="gap: 4px;">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <span class="fs-13 fw-700 text-reset">
                                    {{ $conversation->shop->name ?? translate('Unknown Shop') }}
                                </span>

                                @if ($unseenCount > 0)
                                    <span class="badge badge-circle bg-danger text-white fs-10 fw-600 flex-shrink-0"
                                        style="min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; padding: 0 5px;">
                                        {{ $unseenCount }}
                                    </span>
                                @endif
                            </div>

                            <span class="fs-13 {{ $unseenCount > 0 ? 'fw-700 text-dark' : 'fw-400 text-dark' }} d-block text-truncate font-italic w-75">
                                {{ $conversation->lastMessage->message ?? translate('No messages yet') }}
                            </span>

                            <span class="fs-11 fw-400 text-gray mr-3">
                                {{ $conversation->lastMessage ? $conversation->lastMessage->created_at->format('M d - Y, h:i a') : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>