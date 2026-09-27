{{-- notices --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">
            @forelse ($notices as $notice)
                <div class="px-20px py-20px border-bottom-dashed">
                    <div class="p-2 p-lg-3 d-flex flex-column justify-content-between rounded-2"
                        style="background: {{ $notice->bg_color_hex }};">
                        <div>
                            <span class="fs-13 fw-400 text-dark">
                                {{ $notice->message }}
                                @if ($notice->id === 1)
                                    <a class="text-reset hov-text-blue fw-600" href="{{ route('seller.shop.verify') }}">{{ translate('here') }}</a>
                                @elseif ($notice->id === 2)
                                    <a class="text-reset hov-text-blue fw-600" href="{{ route('seller.shop.index') }}">{{ translate('this') }}</a>
                                @elseif ($notice->id === 3 && !empty($notice->expiry_date))
                                    <span class="text-danger">{{ $notice->expiry_date }}</span>
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
            @empty
                <div class="px-20px py-40px text-center mt-4">
                    <span class="fs-13 fw-400 text-gray">{{ translate('No notices found') }}</span>
                </div>
            @endforelse
        </div>
    </div>
</div>