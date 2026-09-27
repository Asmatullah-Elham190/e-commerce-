@extends('backend.layouts.app')

@section('content')
    <div class="">
        <div class="row align-items-end">
            <div class="col-md-6 order-2 order-md-1">
                <div class="nav border-bottom aiz-nav-tabs">
                    <a class="p-3 fs-16 text-reset show active" data-toggle="tab"
                        href="#installed">{{ translate('Installed Addon') }}</a>
                    <a class="p-3 fs-16 text-reset" data-toggle="tab"
                        href="#available">{{ translate('Available Addon') }}</a>
                </div>
            </div>
            <div class="col text-center text-md-right order-1 order-md-2">
                <div class="d-flex justify-content-start justify-content-md-end">
                    <div class="mr-3">
                        <a href="https://activeitzone.com/activation/addon" class="btn btn-primary" target="_blank">
                            {{ translate('Activate Addon Link') }}
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('addons.create') }}"
                            class="btn btn-primary">{{ translate('Install/Update Addon') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="tab-content filter-tab-content pb-5">
        <div class="tab-pane fade in active show" id="installed">
            <div class="addon-wrapper-grid">
                @forelse($addons as $key => $addon)
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="fs-16 fw-bold my-0 flex-grow-1 text-truncate mr-3">
                                    {{ ucfirst($addon->name) }} {{ translate('Addon') }}
                                </h5>
                                <label class="aiz-switch aiz-switch-blue mb-0">
                                    <input type="checkbox" data-identifier="{{ $addon->unique_identifier }}"
                                        onchange="updateStatus(this, {{ $addon->id }})" <?php    if ($addon->activated)
                    echo "checked";?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="rounded-2 overflow-hidden my-2 img-container">
                                <img src="{{ static_asset($addon->image) }}" class="img-fit w-100 h-100">
                            </div>
                            <div class="py-2 border-bottom-dashed">
                                <p class="fs-12 fw-semibold text-dark mb-2">{{ translate('Version No.') }} </p>
                                <span class="fs-12 fw-400 text-muted">{{ $addon->version }} </span>
                            </div>
                            @if (env('DEMO_MODE') != 'On')
                                <div class="mt-2">
                                    <p class="fs-12 fw-semibold text-dark mb-2">{{ translate('Purchase Code') }} </p>
                                    <span class="fs-12 fw-400 text-muted">{{ $addon->purchase_code }}
                                    </span>
                                </div>
                            @endif
                        </div>
                @empty
                    <li class="list-group-item">
                        <div class="text-center">
                            <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                            <h5 class="mb-0 h5 mt-3">{{ translate('No Addon Installed')}}</h5>
                        </div>
                    </li>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="available">
            <!-- MOBILE APPS -->
            <div class="mt-3">
                <h5 class="fs-14 fw-bold text-dark text-uppercase mb-3">{{ translate('MOBILE APPS') }} </h5>
                <div class="addon-wrapper-grid">
                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Customer Flutter App') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_flutter.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_flutter">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedCustomerFlutterApp)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $49') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Seller App') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_sellerapp.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_sellerapp">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedSellerFlutterApp)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $39') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>


                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Delivery Boy App') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_deliveryapp.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_deliveryapp">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Enable secure OTP-based login and verification for faster customer authentication.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedDeliveryBoyFlutterApp)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $29') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- ESSENTIAL ADD-ONS -->
            <div class="mt-5">
                <h5 class="fs-14 fw-bold text-dark text-uppercase mb-3">{{ translate('ESSENTIAL ADD-ONS') }} </h5>
                <div class="addon-wrapper-grid">
                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Affiliate Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_affiliate.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_affiliate">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedAffiliate)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Refund Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_refund.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_refund">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedRefund)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('OTP Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_OTP.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_OTP">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Enable secure OTP-based login and verification for faster customer authentication.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedOtp)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('GST Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_gst.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_gst">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Enable secure OTP-based login and verification for faster customer authentication.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedGst)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $49') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Seller Subscription Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_sellersub.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_sellersub">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedSellerSubscription)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Clubpoint Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_clubpoint.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_clubpoint">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedClubPoint)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('POS Manager Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_POS.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_POS">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedPos)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Preorder Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_preorder.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_preorder">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedPreorder)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $19') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Auction Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_auction.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_auction">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedAuction)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Wholesale Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_wholesale.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_wholesale">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedWholesale)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>




            </div>

            <!-- SHIPPING RELATED ADD-ONS -->
            <div class="mt-5">
                <h5 class="fs-14 fw-bold text-dark text-uppercase mb-3">{{ translate('SHIPPING RELATED ADD-ONS') }} </h5>
                <div class="addon-wrapper-grid">
                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Shiprocket Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_shiprocket.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_shiprocket">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedShiprocket)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $49') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Pathao Courier Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_pathao.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_pathao">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedPathao)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $19') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('SteadFast Courier Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_steadfast.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_steadfast">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedSteadfast)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $19') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('RedX Courier Addon Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_redx.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_redx">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedRedx)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $19') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>




            </div>

            <!-- PAYMENT RELATED ADD-ONS -->
            <div class="mt-5">
                <h5 class="fs-14 fw-bold text-dark text-uppercase mb-3">{{ translate('PAYMENT RELATED ADD-ONS') }} </h5>
                <div class="addon-wrapper-grid">
                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Cybersource Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_cs.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_cyber_source">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedCybersource)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $39') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Asian Payment Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_asian_banner.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_asian_banner">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedPaytm)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('African Payment Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_africanpay.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_africanpay">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedAfricanpg)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('UddoktaPay Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_upay.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_upay">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage refund requests, approval workflows, and customer refund operations efficiently.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedUddoktaPay)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $19') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Single -->
                    <div
                        class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden m-0">
                        <h5 class="fs-16 fw-bold text-truncate mb-1">
                            {{ translate('Offline Payment Addon') }}
                        </h5>
                        <div class="rounded-2 overflow-hidden my-2 img-container">
                            <img src="{{ static_asset('assets/img/addon-manager/aec_offline.webp') }}"
                                class="img-fit w-100 h-100" alt="aec_offline">
                        </div>
                        <div class="py-2">
                            <p class="fs-14 fw-400 text-dark mb-2">
                                {{ translate('Manage affiliates, referral commissions, and performance tracking from one place.') }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if (!$isOwnedOfflinePayment)
                                <a href="" type="button"
                                    class="fs-14 fw-bold border-0 bg-blue text-white rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('BUY NOW $25') }}
                                </a>

                            @else
                                <button type="button"
                                    class="fs-14 fw-bold border-0 soft-mint-green text-success rounded-2 py-2 px-3 text-center w-100 hov-opacity-80 has-transition">{{ translate('Owned') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var gstConfirmed = false;
        var lastEl, lastId;

        function updateStatus(el, id) {
            if ('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                $(el).prop('checked', !$(el).is(':checked'));
                return;
            }

            if ($(el).is(':checked')) {
                var status = 1;
                if ($(el).data('identifier') == 'gst_system') {
                    if (!gstConfirmed) {
                        showAlert(el, id);
                        return;
                    }
                }
            } else {
                var status = 0;
                gstConfirmed = false;
            }

            $.post('{{ route('addons.activation') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                status: status
            }, function (data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    $(el).prop('checked', !$(el).is(':checked'));
                }
                gstConfirmed = false;
            });
        }

        function showAlert(el, id) {
            lastEl = el;
            lastId = id;

            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('GST Activation Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to enable the GST system?') }}');
            $('#impact-message').html(
                '{{ translate('This action cannot be undone. All existing VAT taxes linked to products will be permanently removed. In addition, any products without an assigned HSN/GST code will be automatically unpublished.') }}'
            );
            $('.confirmation-icon').addClass('d-none');
            $('#exclamation-icon').removeClass('d-none');

            $('#conform-yes-btn').attr("onclick", "activeGST()");
        }

        function activeGST() {
            gstConfirmed = true;
            hideBulkActionModal();
            updateStatus(lastEl, lastId);
        }

        $(document).on('click', '#back-btn, [data-dismiss="modal"]', function () {
            if (!gstConfirmed && lastEl) {
                $(lastEl).prop('checked', false);
            }
        });

    </script>
@endsection