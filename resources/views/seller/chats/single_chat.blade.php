{{-- seller/chats/single_chat.blade.php --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">
            <div class="px-20px py-20px border-bottom">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <div class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                        <img src="{{ uploaded_asset($admin->avatar_original) }}" class="h-100 w-100 img-fit lazyload" alt="">
                    </div>
                    <div class="flex-grow-1 d-flex flex-column align-items-start overflow-hidden w-100" style="gap: 3px;">
                        <span class="fs-13 fw-700 text-reset">{{ $admin->name }}</span>
                        <span class="fs-11 fw-400 text-gray">({{ translate('Admin Support') }})</span>
                    </div>
                </div>
            </div>

            <div class="px-20px py-20px">
                <div class="js-chat-messages-body d-flex flex-column" style="gap: 16px;">
                    @forelse($messages as $message)
                        @if($message->user_id == auth()->id())
                            <div class="d-flex flex-column align-items-end justify-content-end">
                                <p class="mb-1 bg-white border border-gray-300 rounded-2 px-10px py-10px d-inline-block">
                                    <span class="fs-13 fw-400 text-reset d-block">{{ $message->message }}</span>
                                    <span class="fs-11 fw-400 text-gray d-block mt-1 text-right w-100">{{ $message->created_at->format('M d Y, h:i a') }}</span>
                                </p>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-start">
                                <p class="mb-1 bg-light border border-light rounded-2 px-10px py-10px d-inline-block">
                                    <span class="fs-13 fw-400 text-reset d-block">{{ $message->message }}</span>
                                    <span class="fs-11 fw-400 text-gray d-block mt-1">{{ $message->created_at->format('M d Y, h:i a') }}</span>
                                </p>
                            </div>
                        @endif
                    @empty
                        <span class="js-no-message-placeholder fs-13 fw-400 text-gray text-center d-block py-4">{{ translate('No messages yet. Start the conversation!') }}</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white px-3 py-4 border-top mt-auto">
        <div class="d-flex align-items-center justify-content-between p-2 border border-gray-300 rounded-2" style="gap: 12px;">
            <div class="flex-grow-1 d-flex align-items-center" style="gap: 8px;">
                <input type="text" class="js-chat-message-input border-0 p-0 w-100 fs-14 fw-400 text-reset form-control"
                    placeholder="{{ translate('Write a message...') }}">
            </div>
            <button type="button" class="js-send-chat-message border-0 bg-dark text-white fs-12 fw-400 hov-opacity-80 has-transition px-3 py-2 rounded-2">{{ translate('Send') }}</button>
        </div>
    </div>
</div>