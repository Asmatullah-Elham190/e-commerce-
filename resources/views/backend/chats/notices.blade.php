<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">
            @forelse ($notices as $notice)
                <div class="px-20px py-20px border-bottom-dashed">
                    <div class="p-2 p-lg-3 d-flex flex-column justify-content-between rounded-2"
                        style="background: {{ $notice->bg_color_hex }};">
                        <div>
                            <span class="fs-13 fw-400 text-dark">{{ $notice->message }}
                                @if ($notice->id === 1)
                                    <a href="{{ route('seller.shop.verify') }}">this</a>
                                @elseif($notice->id === 2)  
                                    <a href="{{ route('seller.shop.index') }}">here</a>  
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
                            @if ($notice->notice_type != 'default')
                                <div class="dropdown float-right">
                                    <button class="border-0 px-3 py-2 bg-soft-light rounded-1" type="button"
                                        id="presetDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7">
                                            <path id="Polygon_1" d="M5.241.886a1,1,0,0,1,1.519,0l3.826,4.463A1,1,0,0,1,9.826,7H2.174a1,1,0,0,1-.759-1.651Z" transform="translate(12 7) rotate(180)" fill="#1b2133" />
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs p-0 rounded-1 overflow-hidden" aria-labelledby="presetDropdownMenuButton">
                                        @if ($notice->notice_type != 'permanent')
                                            <a class="dropdown-item border-bottom py-3 fs-12 fw-400 js-preset-notice-postagain" 
                                                data-notice-id="{{ $notice->id }}" href="javascript:void(0);">{{ translate('Post Again') }}</a>
                                        @endif
                                        <a class="dropdown-item border-bottom py-3 fs-12 fw-400 js-preset-notice-edit" data-notice-id="{{ $notice->id }}" href="#">{{ translate('Edit') }}</a>
                                        <a class="dropdown-item border-bottom py-3 fs-12 fw-400 js-preset-notice-delete" data-notice-id="{{ $notice->id }}" href="#">{{ translate('Delete') }}</a>
                                    </div>
                                </div>
                            @endif
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

    <div class="bg-white px-3 py-4 border-top mt-auto">
        <a href="javascript:void(0);" class="js-goto-create-notice fs-14 fw-500 text-blue has-transition d-block text-center">
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