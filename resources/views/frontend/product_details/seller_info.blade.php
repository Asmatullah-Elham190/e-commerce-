@if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->shop != null)
    <div class="border mb-4" style="background: #fcfcfd;">
        <div class="position-relative p-3 p-sm-4 text-left">
            <div class="opacity-60 fs-14 mb-3">{{ translate('Seller') }}</div>
            <div class="mt-1">
                <!-- Shop Logo -->
                @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="avatar avatar-md mr-2 overflow-hidden border float-left float-lg-none float-xl-left">
                    <img class="lazyload"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($detailedProduct->user->shop->logo) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>
                @endif
                <!-- Shop Name & Verification status -->
                <div>
                    <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                        class="text-reset hov-text-primary d-block fs-14 fw-700">
                        {{ $detailedProduct->user->shop->name }}
                        @if ($detailedProduct->user->shop->verification_status == 1)
                            <span class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                    <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                        <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#3490f3"/>
                                    </g>
                                </svg>
                            </span>
                        @else
                            <span class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                    <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                        <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="red"/>
                                    </g>
                                </svg>
                            </span>
                        @endif
                    </a>
                    <div class="location opacity-70">{{ $detailedProduct->user->shop->address }}</div>
                </div>
            </div>
            <!-- Ratting -->
            <div class="mt-3">
                <div class="rating rating-mr-2">
                    {{ renderStarRating($detailedProduct->user->shop->rating) }}
                </div>
                <div class="opacity-60 fs-12">
                    ({{ $detailedProduct->user->shop->num_of_reviews }}
                    {{ translate('customer reviews') }})
                </div>
            </div>
            <!-- Social Links -->
            @if ($detailedProduct->user->shop->facebook || $detailedProduct->user->shop->google || $detailedProduct->user->shop->twitter || $detailedProduct->user->shop->youtube)
                <div class="mt-3">
                    <ul class="social list-inline mb-0">
                        @if ($detailedProduct->user->shop->facebook)
                        <li class="list-inline-item mr-2 mb-2">
                            <a href="{{ $detailedProduct->user->shop->facebook }}" class="facebook"
                                target="_blank">
                                <i class="lab la-facebook-f opacity-60"></i>
                            </a>
                        </li>
                        @endif
                        @if ($detailedProduct->user->shop->instagram)
                        <li class="list-inline-item mr-2 mb-2">
                            <a href="{{ $detailedProduct->user->shop->instagram }}" class="instagram"
                                target="_blank">
                                <i class="lab la-instagram opacity-60"></i>
                            </a>
                        </li>
                        @endif
                        @if ($detailedProduct->user->shop->google)
                        <li class="list-inline-item mr-2 mb-2">
                            <a href="{{ $detailedProduct->user->shop->google }}" class="google"
                                target="_blank">
                                <i class="lab la-google opacity-60"></i>
                            </a>
                        </li>
                        @endif
                        @if ($detailedProduct->user->shop->twitter)
                        <li class="list-inline-item mr-2 mb-2">
                            <a href="{{ $detailedProduct->user->shop->twitter }}" class="x-twitter"
                                target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#5d6161" viewBox="0 0 16 16" class="mb-2 pb-1">
                                    <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 
                                    .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                                </svg>
                            </a>
                        </li>
                        @endif
                        @if ($detailedProduct->user->shop->youtube)
                        <li class="list-inline-item">
                            <a href="{{ $detailedProduct->user->shop->youtube }}" class="youtube"
                                target="_blank">
                                <i class="lab la-youtube opacity-60"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            @endif
            <!-- shop link button -->
            <div class="mt-3">
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="btn btn-block btn-slide-secondary-base fs-14 fw-700 rounded-0">{{ translate('Visit Store') }}</a>
            </div>
        </div>
    </div>
@endif
