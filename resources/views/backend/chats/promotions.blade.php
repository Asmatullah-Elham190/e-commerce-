<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">
            @php
                $admin = \App\Models\User::where('user_type', 'admin')->first();
            @endphp
            @forelse ($promotions as $promotion)
                <div class="px-20px py-20px border-bottom-dashed">
                    <div class="d-flex align-items-start flex-shrink-0" style="gap: 12px;">
                        <div
                            class="w-40px h-40px d-flex flex-shrink-0 align-items-center justify-content-center overflow-hidden rounded-circle border-1 border-gray-300">
                            <img src="{{ uploaded_asset($admin->avatar_original) }}" class="h-100 w-100 img-fit lazyload"
                                alt="">
                        </div>
                        <div class="flex-grow-1 d-flex flex-column align-items-start">
                            <p class="mb-2">
                                <span
                                    class="fs-13 fw-400 text-dark">{{ translate('You created a promotion offer for') }}</span>

                                @if ($promotion->flash_sale_id && $promotion->flashSale)
                                    <a href="#"
                                        class="fs-13 fw-700 text-reset hov-text-blue has-transition">{{ $promotion->flashSale->title }}</a>
                                    <span class="fs-13 fw-400 text-dark">{{ translate('for') }}</span>
                                    <a href="#"
                                        class="fs-13 fw-700 text-reset hov-text-blue has-transition">{{ translate('FLASH DEALS') }}</a>
                                @else
                                    <a href="#"
                                        class="fs-13 fw-700 text-reset hov-text-blue has-transition">{{ $promotion->promo_type_label }}</a>
                                @endif
                            </p>

                            @if (!empty($promotion->message))
                                <div class="bg-soft-light p-2 p-lg-3 rounded-2 mb-2 w-100">
                                    <p class="fs-13 fw-400 text-dark font-italic mb-0">
                                        {{ $promotion->message }}
                                    </p>
                                </div>
                            @endif

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
            @empty
                <div class="px-20px py-40px text-center mt-4">
                    <span class="fs-13 fw-400 text-gray">{{ translate('No promotions found') }}</span>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white px-3 py-4 border-top mt-auto">
        <a href="javascript:void(0);"
            class="js-goto-create-promotion fs-14 fw-500 text-blue has-transition d-block text-center">
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