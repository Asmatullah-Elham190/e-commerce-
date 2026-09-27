@extends('backend.layouts.app')

@section('content')
    <div class="marketing-dashboard-content-wrapper pt-4 pb-4">
        <div class="col-12 mx-auto">
            <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Marketing') }}</h1>
            <span class="fs-12 fw-400">{{ translate('Manage marketing needs for your site') }}</span>

            <div class="row gutters-12 mt-3 pt-1">
                @can('view_all_dynamic_popups')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('dynamic-popups.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/dynmc-popup.svg') }}" class="w-50px h-50px"
                                    alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Dynamic Popup') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Build eye-catching popup campaigns with customizable images and links for different scenarios.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_all_custom_alerts')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('custom-alerts.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/custom-alert.svg') }}" class="w-50px h-50px"
                                    alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Custom Alert') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Design alert notifications with visual elements and configure their size and display position.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @if (addon_is_activated('otp_system') && auth()->user()->can('send_bulk_sms'))
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('sms.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/bulk-sms.svg') }}" class="w-50px h-50px"
                                    alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Bulk SMS') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Deliver text messages to your entire/ selected customers in a single campaign.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @can('notification_settings')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('notification-type.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/notification.svg') }}"
                                    class="w-50px h-50px" alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Notifications') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Configure notification triggers and recipients with personalized delivery preferences.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('manage_email_templates')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('all_email_templates.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/email-templates.svg') }}"
                                    class="w-50px h-50px" alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Email Templates') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Organize emails with automated sending rules.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('send_newsletter')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('newsletters.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/newsletter.svg') }}" class="w-50px h-50px"
                                    alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Newsletters') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Broadcast email campaigns to your subscriber list simultaneously.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_blogs')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('blog.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/blogs.svg') }}" class="w-50px h-50px"
                                    alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Blogs') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Organize blog content by topic and publish articles that match your audience interests.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_custom_sale_alert')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('custom-sale-alerts.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/custom-sell-alerts.svg') }}"
                                    class="w-50px h-50px" alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Custom Sell Alert') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Trigger targeted product sell notifications when visitors browse your online store.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('custom_visitors_setup')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('custom_product_visitors')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/custom-visitors.svg') }}"
                                    class="w-50px h-50px" alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Custom Visitors') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Display personalized visitor counts on product pages to boost engagement.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_all_subscribers')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('subscribers.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing/subscribers.svg') }}"
                                    class="w-50px h-50px" alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Subscribers') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('View and manage your newsletter subscription database.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan







            </div>
        </div>
    </div>
@endsection
