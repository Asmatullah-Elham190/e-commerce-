@extends('backend.layouts.app')

@section('content')
    <div class="design-studio-wrapper pt-4 pb-4">
        <div class="col-lg-12 col-xl-10 mx-auto">
            <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Design Studio') }}</h1>
            <span class="fs-12 fw-400 text-muted">{{ translate("Manage your site's look, layout and content") }}</span>

            <!--===== Layout & Navigation Section =====-->
            <div class="mt-4 design-studio-section">
                <div class="row gutters-12">
                    <div class="col-12">
                        <h5 class="fs-14 fw-500 text-gray text-uppercase mb-3">{{ translate('Layout & Navigation') }}
                        </h5>
                    </div>

                    @canany(['select_homepage', 'edit_website_page'])
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <label class="w-100 mb-0 cursor-pointer">
                            <input type="radio" name="layout_nav_type" class="design-studio-input"
                                data-target="#home-page-content" value="homepage">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex align-items-start justify-content-between">
                                    <img src="{{ static_asset('assets/img/design-studio/home-page.svg') }}"
                                        class="flex-shrink-0" alt="">
                                    <i class="las la-angle-right flex-shrink-0 fs-18 text-gray"></i>
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Homepage') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Select layout & manage sections') }}</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @endcanany

                    @canany(['select_header', 'header_setup'])
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <label class="w-100 mb-0 cursor-pointer">
                            <input type="radio" name="layout_nav_type" class="design-studio-input"
                                data-target="#header-content" value="header">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex align-items-start justify-content-between">
                                    <img src="{{ static_asset('assets/img/design-studio/header.svg') }}"
                                        class="flex-shrink-0" alt="">
                                    <i class="las la-angle-right flex-shrink-0 fs-18 text-gray"></i>
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Header') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Style, logo, nav links') }}</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @endcanany

                    @canany(['view_top_banner', 'top_banner_setting'])
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <label class="w-100 mb-0 cursor-pointer">
                            <input type="radio" name="layout_nav_type" class="design-studio-input"
                                data-target="#top-bar-content" value="topbar">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex align-items-start justify-content-between">
                                    <img src="{{ static_asset('assets/img/design-studio/top-bar.svg') }}"
                                        class="flex-shrink-0" alt="">
                                    <i class="las la-angle-right flex-shrink-0 fs-18 text-gray"></i>
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Topbar') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Style, logo, nav links') }}</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @endcanany

                    @can('select_megamenu')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.select-megamenu') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex align-items-start justify-content-between">
                                    <img src="{{ static_asset('assets/img/design-studio/megamenu.svg') }}"
                                        class="flex-shrink-0" alt="">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Mega Menu') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Choose your style') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @canany(['footer_setup', 'select_footer'])
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <label class="w-100 mb-0 cursor-pointer">
                            <input type="radio" name="layout_nav_type" class="design-studio-input"
                                data-target="#footer-content" value="footer">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div class="d-flex align-items-start justify-content-between">
                                    <img src="{{ static_asset('assets/img/design-studio/footer.svg') }}"
                                        class="flex-shrink-0" alt="">
                                    <i class="las la-angle-right flex-shrink-0 fs-18 text-gray"></i>
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Footer') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Style, logo, nav links') }}</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @endcanany
                    @can('select_all_category_layout')
                        <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                            <a href="{{ route('select_all_category_layout') }}">
                                <div
                                    class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <img src="{{ static_asset('assets/img/design-studio/megamenu.svg') }}"
                                            class="flex-shrink-0" alt="">
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('All Category Layout') }}</h6>
                                        <span
                                            class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Choose your style') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('product_details_section')
                        <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                            <a href="{{ route('product_details_section') }}">
                                <div
                                    class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <img src="{{ static_asset('assets/img/design-studio/megamenu.svg') }}"
                                            class="flex-shrink-0" alt="">
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Product Details') }}</h6>
                                        <span
                                            class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Choose your style') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                </div>

                <!-- Tab Pane Content Areas -->
                <div class="mt-1 design-studio-pane-group">

                    @canany(['select_homepage', 'edit_website_page'])
                    <div id="home-page-content" class="design-studio-tab-pane">
                        <div
                            class="tab-content-inner position-relative bg-white border border-1 border-gray-300 rounded-2 p-3 p-lg-4">
                            <button type="button"
                                class="tab-content-close-btn border-0 bg-transparent text-gray hov-text-danger has-transition p-0">
                                <i class="las la-times fs-24"></i>
                            </button>
                            <h6 class="fs-16 fw-semibold m-0">{{ translate('Homepage Settings') }}
                            </h6>
                            <div class="mt-3 d-flex flex-column setting-link-wrapper">
                                @can('select_homepage')
                                <a href="{{ route('website.select-homepage') }}"
                                    class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span>{{ translate('Select Homepage') }} </span>
                                    <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                </a>
                                @endcan
                                @can('edit_website_page')
                                <a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}"
                                    class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span>{{ translate('Edit Homepage') }} </span>
                                    <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @endcanany

                    @canany(['select_header', 'header_setup'])
                    <div id="header-content" class="design-studio-tab-pane">
                        <div
                            class="tab-content-inner position-relative bg-white border border-2 border-gray-300 rounded-2 p-3 p-lg-4">
                            <button type="button"
                                class="tab-content-close-btn border-0 bg-transparent text-gray hov-text-danger has-transition p-0">
                                <i class="las la-times fs-20"></i>
                            </button>
                            <h6 class="fs-16 fw-semibold m-0">{{ translate('Header') }}
                            </h6>
                            <div class="mt-3 d-flex flex-column setting-link-wrapper">
                                @can('select_header')
                                <a href="{{ route('website.select-header') }}"
                                    class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span>{{ translate('Select Header') }} </span>
                                    <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                </a>
                                @endcan
                                @can('header_setup')
                                <a href="{{ route('website.header') }}"
                                    class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span>{{ translate('Header Settings') }} </span>
                                    <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @endcanany

                    @canany(['view_top_banner', 'top_banner_setting'])
                    <div id="top-bar-content" class="design-studio-tab-pane">
                        <div
                            class="tab-content-inner position-relative bg-white border border-2 border-gray-300 rounded-2 p-3 p-lg-4">
                            <button type="button"
                                class="tab-content-close-btn border-0 bg-transparent text-gray hov-text-danger has-transition p-0">
                                <i class="las la-times fs-20"></i>
                            </button>
                            <h6 class="fs-16 fw-semibold m-0">{{ translate('Topbar') }}
                            </h6>
                            <div class="mt-3 d-flex flex-column setting-link-wrapper">
                                @can('view_top_banner')
                                <a href="{{route('top_banner.index')}}"
                                    class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span>{{ translate('Topbar List') }} </span>
                                    <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                </a>
                                @endcan
                                @can('top_banner_setting')
                                <a href="{{route('top_banner.setting')}}"
                                    class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                    <span>{{ translate('Topbar Settings') }} </span>
                                    <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    @endcanany

                    @canany(['footer_setup', 'select_footer'])
                        <div id="footer-content" class="design-studio-tab-pane">
                            <div
                                class="tab-content-inner position-relative bg-white border border-2 border-gray-300 rounded-2 p-3 p-lg-4">
                                <button type="button"
                                    class="tab-content-close-btn border-0 bg-transparent text-gray hov-text-danger has-transition p-0">
                                    <i class="las la-times fs-20"></i>
                                </button>
                                <h6 class="fs-16 fw-semibold m-0">{{ translate('Footer') }}
                                </h6>
                                <div class="mt-3 d-flex flex-column setting-link-wrapper">
                                    @can('select_footer')
                                        <a href="{{ route('website.select-footer') }}"
                                            class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                            <span>{{ translate('Select Footer') }} </span>
                                            <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                        </a>
                                    @endcan    
                                    @can('footer_setup')
                                        <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}"
                                            class="fs-14 fw-400 text-reset bg-light hov-bg-soft-blue rounded-1 py-2 px-3 d-flex align-items-center justify-content-between">
                                            <span>{{ translate('Footer Settings') }} </span>
                                            <i class="las la-angle-right flex-shrink-0 fs-16 text-gray"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endcanany

                </div>
                <!-- Tab Content Areas End -->

            </div>
            <!--===== Layout & Navigation Section End =====-->


            <!--===== Brand & Style Section =====-->
            <div class="mt-4 design-studio-section">
                <div class="row gutters-12">
                    <div class="col-12">
                        <h5 class="fs-14 fw-500 text-gray text-uppercase mb-3">{{ translate('Brand & Style') }}</h5>
                    </div>

                    @can('system_setup')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.system_setup') }}" class="d-block">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/system-setup.svg') }}"
                                        class="flex-shrink-0" alt="System Setup Icon">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('System Setup') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Select layout & manage sections') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('website_appearance')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.appearance') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/appearance.svg') }}"
                                        class="flex-shrink-0" alt="Appearance Icon">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Appearance') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Colors') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('select_font_family')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.select-font-family') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/typography.svg') }}"
                                        class="flex-shrink-0" alt="Typography Icon">

                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Typography') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Choose your Fonts') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('logo_and_favicon')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.logo_and_favicon') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/logo-favicon.svg') }}"
                                        class="flex-shrink-0" alt="Logo & Favicon Icon">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Logo & Favicon') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Upload brand assets') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
            <!--===== Brand & Style Section End =====-->


            <!--===== Content & Pages Section =====-->
            <div class="mt-4 design-studio-section">
                <div class="row gutters-12">
                    <div class="col-12">
                        <h5 class="fs-14 fw-500 text-gray text-uppercase mb-3">{{ translate('Content & Pages') }}
                        </h5>
                    </div>

                    @can('banners_and_sliders')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.banners_and_sliders') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/banner-slider.svg') }}"
                                        class="flex-shrink-0" alt="Banner Slider Icon">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Banners & sliders') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Common Banners & Sliders') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('view_all_website_pages')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.pages') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/pages.svg') }}"
                                        class="flex-shrink-0" alt="Pages Icon">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Pages') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('About, contact, custom pages') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can('authentication_layout_settings')
                    <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                        <a href="{{ route('website.authentication-layout-settings') }}">
                            <div
                                class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                <div>
                                    <img src="{{ static_asset('assets/img/design-studio/auth-pages.svg') }}"
                                        class="flex-shrink-0" alt="Auth Pages Icon">
                                </div>
                                <div class="mt-3">
                                    <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Auth Pages') }}</h6>
                                    <span
                                        class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Login & register layout') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan
                </div>

            </div>
            <!--===== Content & Pages Section End =====-->

            <!--===== Admin Panel Section =====-->
            <div class="mt-4 design-studio-section">
                <div class="row gutters-12">
                    <div class="col-12">
                        <h5 class="fs-14 fw-500 text-gray text-uppercase mb-3">{{ translate('Admin Panel') }}
                        </h5>
                    </div>

                    @can('can_manage_admin_panel')
                        <div class="col-6 col-md-6 col-lg-3 col-xl-3 mb-3">
                            <a href="{{ route('website.manage_admin_panel') }}">
                                <div
                                    class="design-studio-card border border-2 border-gray-300 bg-white has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                    <div>
                                        <img src="{{ static_asset('assets/img/design-studio/admin-navbar.svg') }}"
                                            class="flex-shrink-0" alt="Admin Navbar Icon">
                                    </div>
                                    <div class="mt-3">
                                        <h6 class="fs-15 fw-bold text-dark mb-1">{{ translate('Admin Navbar') }}</h6>
                                        <span
                                            class="fs-12 fw-400 text-truncate d-block w-100 text-muted">{{ translate('Navbar Background & Text Color') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                </div>

            </div>
            <!--===== Admin Panel Section End =====-->

        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {

            // radio uncheck if page load
            $('.design-studio-input').prop('checked', false);
            // hide if open in a page
            $('.design-studio-tab-pane').removeClass('animate-in d-show');

            // Show Pane
            function showPane($pane) {
                $pane.addClass('d-show');
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        $pane.addClass('animate-in');
                    });
                });
            }

            // Hide Pane
            function hidePane($pane) {
                $pane.removeClass('animate-in d-show');
            }

            $('.design-studio-input').on('change', function() {
                var $target = $($(this).data('target'));
                if ($(this).is(':checked') && $target.length) {
                    var $currentSection = $target.closest('.design-studio-section');

                    $('.design-studio-section').not($currentSection).each(function() {
                        $(this).find('.design-studio-input').prop('checked', false);
                        $(this).find('.design-studio-tab-pane').removeClass('animate-in d-show');
                    });

                    $currentSection.find('.design-studio-tab-pane').not($target).each(function() {
                        hidePane($(this));
                    });

                    showPane($target);
                }
            });

            // Close Tab Pane
            $(document).on('click', '.tab-content-close-btn', function() {
                var $pane = $(this).closest('.design-studio-tab-pane');
                var $section = $pane.closest('.design-studio-section');
                $section.find('.design-studio-input').prop('checked', false);
                hidePane($pane);
            });
        });
    </script>
@endsection
