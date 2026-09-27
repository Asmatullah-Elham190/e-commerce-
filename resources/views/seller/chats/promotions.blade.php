{{-- promotion --}}
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
                                    class="fs-13 fw-400 text-dark">{{ translate('Admin created a promotion offer for') }}</span>

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
                                </div>
                                <a href="{{ route('seller.promotional_products.index') }}"
                                    class="fs-13 fw-400 text-reset hov-text-blue has-transition border border-1 border-gray-300 rounded-2 px-3 py-2 mt-1">
                                    {{ translate('Join Campaign') }}
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
</div>