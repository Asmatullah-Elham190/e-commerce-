{{-- requests --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">

            @forelse ($requests as $req)
                @php $item = $req->item_label; @endphp
                <div class="px-20px py-20px border-bottom-dashed">
                    <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
                        <div class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                            <img src="{{ uploaded_asset($shop->logo) }}" class="h-100 w-100 img-fit lazyload" alt="">
                        </div>
                        <div class="flex-grow-1 d-flex flex-column align-items-start">
                            <p class="mb-2">
                                <span class="fs-13 fw-400 text-dark">
                                    @if ($item)
                                        {{ translate('You requested to add a') }}
                                        <span class="fw-700 text-reset">{{ translate($item['type']) }}</span>
                                        {{ translate('named') }}
                                        <span class="fw-700 text-reset">{{ $item['value'] }}</span>.
                                    @else
                                        {{ translate('You created a request.') }}
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
            @empty
                <div class="px-20px py-40px text-center mt-4">
                    <span class="fs-13 fw-400 text-gray">{{ translate('No requests found') }}</span>
                </div>
            @endforelse

        </div>
    </div>
    <div class="bg-white px-3 py-4 border-top mt-auto">
        <a href="javascript:void(0);" class="js-goto-create-request fs-14 fw-500 text-blue has-transition d-block text-center">
            <svg id="Group_40034" data-name="Group 40034" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                viewBox="0 0 16 16">
                <rect id="Rectangle_24889" data-name="Rectangle 24889" width="16" height="1.5"
                    transform="translate(8.75) rotate(90)" fill="#087ffa" />
                <rect id="Rectangle_24891" data-name="Rectangle 24891" width="16" height="1.5"
                    transform="translate(0 7.25)" fill="#087ffa" />
            </svg>
            {{ translate('Create New') }}</a>
    </div>
</div>