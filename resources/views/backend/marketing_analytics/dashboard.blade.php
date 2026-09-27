@extends('backend.layouts.app')

@section('content')
    <div class="marketing-analytics-dashboard-content-wrapper pt-4 pb-4">
        <div class="col-12 mx-auto">
            <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Marketing Analytics') }}</h1>
            <span
                class="fs-12 fw-400">{{ translate('Connect, track and optimize your store marketing from one place') }}</span>

            <div class="row gutters-12 mt-3 pt-1">
                @can('manage_google_analytics')
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('google-analytics-config') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/google-analytics.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Google Analytics (GA4)') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Track visitor behavior, sales performance and conversion insights.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('google-tag-manager-config') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/gtm.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Google Tag Manager (GTM)') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Manage and deploy marketing tags without editing code.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @canany(['delete_gmc_product', 'edit_gmc_product', 'add_gmc_product'])
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('google_merchant_center.configuration') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/gmc.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Google Marchant Center') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Sync products with Google Shopping and product ads. Meta Pixel') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('facebook_catalogue.configuration') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/catalog.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                            {{ translate('Meta Shop Sync (Catalog)') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Sync your store products with Facebook and Instagram Shops') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcanany
                @can('manage_pixel_analytics')
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('pixel_analytics.index') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/meta-pixel.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Meta Pixel') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Track customer actions and measure Meta ad performance.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('pixel_conversation_api.index') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/capi.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                            {{ translate('Meta Conversion API (CAPI)') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Send server-side conversion events directly to Meta securely.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('manage_sitemap_generator')
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('sitemap_generator') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/sitemap-generator.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                            {{ translate('Sitemap Generator') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Generate SEO-friendly sitemaps for better search indexing.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @can('manage_custom_script')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('custom_script') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex flex-column">
                                <img src="{{ static_asset('assets/img/marketing-analytics/custom-script.svg') }}"
                                    class="w-50px h-50px" alt="Icon">
                                <div class="mt-2 pt-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                        {{ translate('Custom Scripts') }}
                                    </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Add custom tracking codes, widgets or third-party scripts.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan
                @can('manage_global_seo')
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <a href="{{ route('global_seo') }}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex flex-column">
                                    <img src="{{ static_asset('assets/img/marketing-analytics/global-seo.svg') }}"
                                        class="w-50px h-50px" alt="Icon">
                                    <div class="mt-2 pt-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                            {{ translate('Global SEO') }}
                                        </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray text-truncate-2">{{ translate('Configure default SEO settings for your entire website.') }}</span>
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
