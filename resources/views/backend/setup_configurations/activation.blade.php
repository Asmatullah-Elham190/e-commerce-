@extends('backend.layouts.app')

@section('content')
    <div class="feature-activation-wrapper pt-4 pb-4">
        <div class="col-12 mx-auto">
            <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Feature Activation') }}</h1>
            <span class="fs-12 fw-400">{{ translate('Customize how the business operates') }}</span>

            <div class="row gutters-12 mt-3 pt-1">
                <!-- Infrastructure -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mb-3">{{ translate('INFRASTRUCTURE') }} </h5>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">

                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/http-activation.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'FORCE_HTTPS')" <?php if (env('FORCE_HTTPS') == 'On') {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('HTTPS Activation') }} </h6>
                        <span
                            class="fs-12 fw-400  sub-title">{{ translate('Enable this to force the website to load over a secure HTTPS connection.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/maintainance-mode.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                            <input type="checkbox" onchange="updateSettings(this, 'maintenance_mode')" <?php if (get_setting('maintenance_mode') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Maintenance Mode') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Enable this to temporarily disable the website and show a maintenance message.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/disable-img-encoding.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'disable_image_optimization')"
                                <?php if (get_setting('disable_image_optimization') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Disable Image Encoding') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Enable this to disable image encoding and skip image optimization.') }}</span>
                    </div>
                </div>

                <!-- Seller & Vendor -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mt-3 mb-3">{{ translate('SELLER & VENDOR') }} </h5>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/vendor.svg') }}" class="flex-shrink-0"
                                alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'vendor_system_activation')"
                                <?php if (get_setting('vendor_system_activation') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Vendor / Multivendor System') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Allow third-party sellers to list and sell on your platform.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/admin-approval.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'product_approve_by_admin')"
                                <?php if (\App\Models\BusinessSetting::where('type', 'product_approve_by_admin')->first() && get_setting('product_approve_by_admin') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Admin Approval for Seller Product') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Require admin review before seller products go live.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/seller-order-management.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'product_manage_by_admin')"
                                <?php if (\App\Models\BusinessSetting::where('type', 'product_manage_by_admin')->first() && get_setting('product_manage_by_admin') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Seller Order Management') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let admin control and manage seller orders.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/seller-verification.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'seller_registration_verify')"
                                <?php if (get_setting('seller_registration_verify') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Seller Email Verification') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Verify seller email while registration.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/digital-product-for-seller.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'digital_product_manage_by_seller')"
                                <?php if (get_setting('digital_product_manage_by_seller') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Digital Products for Sellers') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Allow sellers to list and sell downloadable digital goods.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/seller-external-product.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'product_external_link_for_seller')"
                                <?php if (get_setting('product_external_link_for_seller') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Seller External Product Links') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Allow sellers to add external URLs to their product listings.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                @if (addon_is_activated('wholesale'))
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/wholesale.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'seller_wholesale_product')"
                                    <?php if (get_setting('seller_wholesale_product') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Wholesale (B2B) for Sellers') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Enable to allow seller to set bulk pricing and wholesale orders for business buyers.') }}</span>
                    </div>
                </div>
                @endif

                <!-- Single -->
                @if (addon_is_activated('auction'))
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/auction-products.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'seller_auction_product')"
                                    <?php if (get_setting('seller_auction_product') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Auction Products for Sellers') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let sellers list products for time-limited bidding auctions.') }}</span>
                    </div>
                </div>
                @endif

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/classified-products.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'classified_product')" <?php if (get_setting('classified_product') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Classified Products') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Enable classified-style listings with buyer–seller direct contact.') }}</span>
                    </div>
                </div>


                <!-- Customer & checkout -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mt-3 mb-3">{{ translate('Customer & checkout') }}
                    </h5>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/customer-registration-verification.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'customer_registration_verify')"
                                    <?php if (get_setting('customer_registration_verify') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Customer Registration Verification') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Require email or OTP verification on new customer sign-up.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/guest-checkout.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'guest_checkout_activation')"
                                <?php if (get_setting('guest_checkout_activation') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Guest Checkout') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Allow customers to complete purchase without registering.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/pickup-point.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'pickup_point')" <?php if (get_setting('pickup_point') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Pickup Point') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let customers select a local pickup location at checkout.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/billing-address.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'billing_address_required')"
                                    <?php if (get_setting('billing_address_required') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Billing Address at Checkout') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Make billing address a required field during checkout.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/last-viewed-product.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'last_viewed_product_activation')"
                                <?php if (get_setting('last_viewed_product_activation') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Last Viewed Products') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Show customers their recently browsed product history.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/product-query.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'product_query_activation')"
                                <?php if (get_setting('product_query_activation') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Product Query (Q&A)') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let customers ask questions directly on product pages.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/customer-seller.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'conversation_system')" <?php if (get_setting('conversation_system') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Customer-Seller Conversation') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Enable in-platform messaging between buyers and sellers.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/floating-action-button.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'use_floating_buttons')"
                                <?php if (get_setting('use_floating_buttons') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Floating Action Buttons') }} </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Show quick-action buttons (categories, flash deals etc) floating on the storefront.') }}</span>
                    </div>
                </div>


                <!-- Promotions & loyalty -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mt-3 mb-3">{{ translate('Promotions & loyalty') }}
                    </h5>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/coupon-system.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'coupon_system')" <?php if (get_setting('coupon_system') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Coupon System') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Enable discount codes and coupon campaigns for customers.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/newsletter-subscription.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'newsletter_activation')"
                                <?php if (get_setting('newsletter_activation') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Newsletter Subscription') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Collect customer emails for marketing campaigns.') }}</span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/wallet-system.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'wallet_system')" <?php if (get_setting('wallet_system') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Wallet system') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Give customers a digital wallet for store credits and refunds.') }}</span>
                    </div>
                </div>


                <!-- Security & authentication -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mt-3 mb-3">
                        {{ translate('Security & authentication') }}
                    </h5>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/email-verification.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'email_verification')" <?php if (get_setting('email_verification') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Email Verification') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Send a verification link to confirm new account emails.') }}
                            <small class="text-blue d-flex align-items-center mt-1">
                                <i class="las la-info-circle fs-14 mr-1" style="margin-top: -2px;"></i>
                                {{ translate('Requires SMTP configuration') }}
                            </small>
                        </span>
                    </div>
                </div>


                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/facebook-login.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'facebook_login')" <?php if (get_setting('facebook_login') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Facebook Login') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let customers sign in using their Facebook account') }}
                            <small class="text-blue d-flex align-items-center mt-1">
                                <i class="las la-info-circle fs-14 mr-1" style="margin-top: -2px;"></i>
                                {{ translate('Requires Facebook app credentials') }}
                            </small>
                        </span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/google-login.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'google_login')" <?php if (get_setting('google_login') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Google Login') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let customers sign in using their Google account.') }}
                            <small class="text-blue d-flex align-items-center mt-1">
                                <i class="las la-info-circle fs-14 mr-1" style="margin-top: -2px;"></i>
                                {{ translate(' Requires Google OAuth credentials') }}
                            </small>
                        </span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/twitter-login.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'twitter_login')" <?php if (get_setting('twitter_login') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Twitter / X login') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let customers sign in using their Twitter/X account.') }}
                            <small class="text-blue d-flex align-items-center mt-1">
                                <i class="las la-info-circle fs-14 mr-1" style="margin-top: -2px;"></i>
                                {{ translate('Requires Twitter app credential') }}
                            </small>
                        </span>
                    </div>
                </div>

                <!-- Single -->
                <div class="col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                    <div class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ static_asset('assets/img/feature-activation/apple-login.svg') }}"
                                class="flex-shrink-0" alt="Icon">
                            <label class="aiz-switch aiz-switch-blue mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'apple_login')" <?php if (get_setting('apple_login') == 1) {
                                    echo 'checked';
                                } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <h6 class="fs-16 fw-semibold mt-3 mb-2">{{ translate('Apple Login') }}
                        </h6>
                        <span
                            class="fs-12 fw-400 sub-title">{{ translate('Let customers sign in using their Apple ID.') }}
                            <small class="text-blue d-flex align-items-center mt-1">
                                <i class="las la-info-circle fs-14 mr-1" style="margin-top: -2px;"></i>
                                {{ translate('Requires Apple developer credentials') }}
                            </small>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type) {

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }

            $.post('{{ route('business_settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection