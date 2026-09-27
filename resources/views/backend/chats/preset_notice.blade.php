{{-- preset_notice blade --}}
<div class="d-flex flex-column justify-content-between h-100">
    <div class="flex-grow-1 c-scrollbar-light" style="overflow-y: auto;">
        <div class="d-flex flex-column">
            @foreach ($notices as $notice)
                <div class="px-20px py-20px border-bottom-dashed">
                    <div class="p-2 p-lg-3 d-flex flex-column justify-content-between rounded-2"
                        style="background: {{ $notice->bg_color_hex }};">
                        <div>
                            <span class="fs-13 fw-400 text-dark">
                                {{ $notice->message }}
                                @if ($notice->id === 1)
                                    <a href="{{ route('seller.shop.verify') }}">this</a>
                                @elseif($notice->id === 2)  
                                    <a href="{{ route('seller.shop.index') }}">here</a>  
                                @endif
                            </span>
                        </div>
                        <div class="mt-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                @if ($notice->notice_type === 'default')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                                        <path id="Union_67" d="M1.75,14A1.75,1.75,0,0,1,0,12.25V7.875A1.749,1.749,0,0,1,.875,6.359V4.375a4.375,4.375,0,0,1,8.75,0V6.359A1.75,1.75,0,0,1,10.5,7.875V12.25A1.75,1.75,0,0,1,8.75,14ZM7.875,6.125V4.375a2.625,2.625,0,0,0-5.25,0v1.75Z" fill="#989898" />
                                    </svg>
                                @endif
                                <span class="fs-11 fw-400 text-gray mt-1 @if ($notice->notice_type === 'default') ml-3 @endif">{{ $notice->notice_type_label }}</span>
                                @if ($notice->notice_type === 'temporary' && $notice->notice_datetime)
                                    <span class="fs-11 fw-400 text-gray mt-1 ml-2">
                                        {{ translate('Expired at') }}: {{ $notice->notice_datetime_formatted }}
                                    </span>
                                @endif
                            </div>
                            @if ($notice->notice_type === 'default')
                                <label class="aiz-switch aiz-switch-blue mb-0">
                                    <input value="1" name="status" type="checkbox"
                                        class="js-preset-notice-status" data-notice-id="{{ $notice->id }}"
                                        {{ $notice->status ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            @else
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
            @endforeach
        </div>
    </div>
</div>