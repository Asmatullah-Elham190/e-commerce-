@extends('backend.layouts.app')

@section('content')
    <div class="business-setting-wrapper pt-4">
        <div class="col-12 mx-auto">
            <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Business Settings') }}</h1>
            <span
                class="fs-12 fw-400">{{ translate('Manage core business operations including orders, invoicing and delivery') }}</span>

            <div class="row mt-3 gutters-12">
                <!-- Single -->
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('general.info') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/business-settings/general-setting.svg') }}"
                                    class="w-50px h-50px" alt="Setting Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fw-semibold text-dark">{{translate('General Settings')}}</h6>
                                    <span
                                        class="fs-12 fw-400 d-block text-gray">{{translate('Manage core business information, store identity and essential configurations that define how your store operates.')}}</span>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Single -->
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('order.config') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/business-settings/order-configuration.svg') }}"
                                    class="w-50px h-50px" alt="Setting Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fw-semibold text-dark">{{translate('Order Configuration')}}</h6>
                                    <span
                                        class="fs-12 fw-400 d-block text-gray">{{translate('Set up order processing rules to control how orders are placed, managed and fulfilled.')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @can('vat_&_tax_setup')
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('tax.index') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/business-settings/vat-tax.svg') }}"
                                        class="w-50px h-50px" alt="Setting Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fw-semibold text-dark">{{translate('Vat, TAX & Other Charges')}}</h6>
                                        <span
                                            class="fs-12 fw-400 d-block text-gray">{{translate('Configure Vat & Tax rates & additional charges')}}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('pickup_point_setup')
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('pick_up_points.index') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/business-settings/pickup-points.svg') }}"
                                        class="w-50px h-50px" alt="Setting Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fw-semibold text-dark">{{translate('Pickup Points')}}</h6>
                                        <span
                                            class="fs-12 fw-400 d-block text-gray">{{translate('Manage pickup locations where customers can collect their orders')}}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                <!-- Single -->
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('invoice.config') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/business-settings/invoice-settings.svg') }}"
                                    class="w-50px h-50px" alt="Setting Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fw-semibold text-dark">{{translate('Invoice Settings')}}</h6>
                                    <span
                                        class="fs-12 fw-400 d-block text-gray">{{translate('Configure invoice to ensure accurate and professional order documentation.')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Single -->
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('order_tracking.config') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/business-settings/order-tracking.svg') }}"
                                    class="w-50px h-50px" alt="Setting Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fw-semibold text-dark">{{translate('Order Tracking')}}</h6>
                                    <span
                                        class="fs-12 fw-400 d-block text-gray">{{translate('Set up tracking codes and tracking options to keep customers informed about their order status and delivery progress.')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Single -->
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('shipping_label.config') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/business-settings/shipping-label.svg') }}"
                                    class="w-50px h-50px" alt="Setting Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fw-semibold text-dark">{{translate('Shipping Label')}}</h6>
                                    <span
                                        class="fs-12 fw-400 d-block text-gray">{{translate('Configure shipping label formats, layout and printing preferences for efficient order packaging and dispatch.')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Single -->
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('thermal_printer.config') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/business-settings/thermal-printer-settings.svg') }}"
                                    class="w-50px h-50px" alt="Setting Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fw-semibold text-dark">{{translate('Thermal Printer Settings')}}</h6>
                                    <span
                                        class="fs-12 fw-400 d-block text-gray">{{translate('Adjust printer settings and formats to ensure smooth and accurate printing of receipts, invoices and labels using thermal printers.')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection