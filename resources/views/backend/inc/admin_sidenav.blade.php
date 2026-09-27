<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left" style="background-color: {{ get_setting('navbar_bg_color') }}">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left"
                style="background-color: {{ get_setting('navbar_bg_color') }}">
                @if(get_setting('system_logo_black') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-3 mb-3 position-relative" style="color: {{ get_setting('navbar_text_color') }}">
                <input 
                    class="form-control bg-transparent rounded-2 form-control-sm fs-14 menu-search-input" 
                    type="text" 
                    @if (get_setting('navbar_text_color') == 'black') 
                        style="border: 1px solid rgba(0, 0, 0, 0.3); color: {{ get_setting('navbar_text_color') }};" 
                    @elseif(get_setting('navbar_text_color') == 'white') 
                        style="border: 1px solid rgba(255, 255, 255, 0.3);  color: {{ get_setting('navbar_text_color') }};"
                    @endif
                    name="" 
                    placeholder="{{ translate('Search in menu') }}" 
                    id="menu-search" 
                    onkeyup="menuSearch()" 
                >
                <span class="absolute-top-right pr-3 mr-3" style="margin-top: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="search_FILL0_wght200_GRAD0_opsz20"
                            d="M176.921-769.231l6.255-6.255a5.99,5.99,0,0,0,1.733.949,5.687,5.687,0,0,0,1.885.329,5.317,5.317,0,0,0,3.9-1.608,5.31,5.31,0,0,0,1.609-3.9,5.322,5.322,0,0,0-1.608-3.9,5.306,5.306,0,0,0-3.9-1.611,5.321,5.321,0,0,0-3.9,1.609,5.312,5.312,0,0,0-1.611,3.9,5.554,5.554,0,0,0,.35,1.946,6.043,6.043,0,0,0,.929,1.672l-6.255,6.255Zm9.874-5.82a4.51,4.51,0,0,1-3.317-1.352,4.51,4.51,0,0,1-1.352-3.317,4.51,4.51,0,0,1,1.352-3.317,4.51,4.51,0,0,1,3.317-1.352,4.51,4.51,0,0,1,3.317,1.352,4.51,4.51,0,0,1,1.352,3.317,4.51,4.51,0,0,1-1.352,3.317A4.51,4.51,0,0,1,186.8-775.051Z"
                            transform="translate(-176.307 785.231)" fill="{{ get_setting('navbar_text_color') }}" />
                    </svg>
                </span>
            </div>
            <ul class="aiz-side-nav-list" id="search-menu"></ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">

                @can('admin_dashboard')
                    <li class="aiz-side-nav-item">
                        <a href="{{route('admin.dashboard')}}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="_3d6902ec768df53cd9e274ca8a57e401"
                                        data-name="3d6902ec768df53cd9e274ca8a57e401"
                                        d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z"
                                        transform="translate(-2 -2)" fill-rule="evenodd"
                                        fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Dashboard')}}</span>
                        </a>
                    </li>
                @endcan

                <li class="aiz-side-nav-item" style="color: {{ get_setting('navbar_text_color') }}">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400 d-block my-1 opacity-50">
                        {{translate('Products')}}
                    </span>
                </li>

                @canany(['view_product_management_dashboard', 'add_new_product', 'show_all_products', 'show_in_house_products', 'show_seller_products', 'add_digital_product', 'edit_digital_product', 'product_bulk_import', 'product_bulk_export', 'view_product_categories', 'view_all_brands', 'brand_bulk_upload', 'view_product_attributes', 'view_colors', 'view_product_warranties', 'smart-bar', 'view_custom_label', 'view_product_reviews', 'view_notes', 'add_note'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="13.714" viewBox="0 0 16 13.714">
                                    <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -4)">
                                        <path id="Path_40719" data-name="Path 40719"
                                            d="M17.429,4H2.571A.571.571,0,0,0,2,4.571V8a.571.571,0,0,0,.571.571h.571v8.571a.571.571,0,0,0,.571.571H16.286a.571.571,0,0,0,.571-.571V8.571h.571A.571.571,0,0,0,18,8V4.571A.571.571,0,0,0,17.429,4ZM15.714,16.571H4.286v-8H15.714Zm1.143-9.143H3.143V5.143H16.857Z"
                                            fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40720" data-name="Path 40720"
                                            d="M12.571,15.143H16A.571.571,0,0,0,16,14H12.571a.571.571,0,0,0,0,1.143Z"
                                            transform="translate(-4.286 -4.286)"
                                            fill="{{ get_setting('navbar_text_color') }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Management')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_product_management_dashboard')
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link" href="{{route('products.dashboard')}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Home')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['show_all_products', 'show_in_house_products', 'show_seller_products', 'add_new_product', 'add_digital_product'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Products')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('show_all_products')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('products.all')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['products.all']) || request('source') == 'all' ? 'active' : ''}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{ translate('All Products') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('show_in_house_products')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('products.admin')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['products.admin']) || request('source') == 'in-house' ? 'active' : '' }}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{ translate('In House Products') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @if(get_setting('vendor_system_activation') == 1)
                                            @can('show_seller_products')
                                                <li class="aiz-side-nav-item">
                                                    <a class="aiz-side-nav-link {{ request('source') == 'seller-all' ? 'active' : '' }}" href="{{ route('products.seller', 'all') }}">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Seller Product')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        @endif
                                        @can('add_new_product')
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link" href="{{route('products.create')}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Add New product')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('add_digital_product')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('digitalproducts.create') }}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['digitalproducts.create']) }}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Add New Digital Product') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['add_note', 'view_notes', 'view_product_warranties', 'view_product_categories', 'view_all_brands', 'brand_bulk_upload', 'view_colors', 'view_product_attributes', 'view_measurement_points', 'view_size_charts'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Setup')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @canany(['view_product_categories', 'category_bulk_upload'])
                                            <li class="aiz-side-nav-item">
                                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Category')}}</span>
                                                    <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                                </a>
                                                <ul class="aiz-side-nav-list level-4">
                                                    @can('view_product_categories')
                                                        <li class="aiz-side-nav-item">
                                                            <a href="{{route('categories.index')}}"
                                                                class="aiz-side-nav-link {{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Category')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('category_bulk_upload')
                                                        <li class="aiz-side-nav-item">
                                                            <a href="{{ route('category_bulk_upload.index') }}" class="aiz-side-nav-link">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Bulk Import/Export')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcanany
                                        @canany(['view_all_brands', 'brand_bulk_upload'])
                                            <li class="aiz-side-nav-item">
                                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Brand')}}</span>
                                                    <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                                </a>
                                                <ul class="aiz-side-nav-list level-4">
                                                    @can('view_all_brands')
                                                        <li class="aiz-side-nav-item">
                                                            <a href="{{ route('brands.index') }}"
                                                                class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('All Brands')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('brand_bulk_upload')
                                                        <li class="aiz-side-nav-item">
                                                            <a href="{{ route('brand_bulk_upload.index') }}" class="aiz-side-nav-link">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Bulk Import/Export')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcanany
                                        @can('view_colors')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('colors')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['colors', 'colors.edit', 'colors.create'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Colors')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view_product_attributes')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('attributes.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index', 'attributes.create', 'attributes.edit', 'attributes.show'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Attribute')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view_unit')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('unit.index')}}"
                                                    class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Unit')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @canany(['view_size_charts', 'view_measurement_points'])
                                            <li class="aiz-side-nav-item">
                                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Size Guide')}}</span>
                                                    <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                                </a>
                                                <ul class="aiz-side-nav-list level-4">
                                                    @can('view_size_charts')
                                                        <li class="aiz-side-nav-item">
                                                            <a href="{{ route('size-charts.index') }}"
                                                                class="aiz-side-nav-link {{ areActiveRoutes(['size-charts.index', 'size-charts.create', 'size-charts.edit'])}}">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Size Chart')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('view_measurement_points')
                                                        <li class="aiz-side-nav-item">
                                                            <a href="{{ route('measurement-points.index') }}" class="aiz-side-nav-link">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Measurement Points')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcanany
                                        @can('view_product_warranties')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('warranties.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['warranties.edit'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Warranty')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @canany(['view_notes', 'add_note'])
                                            <li class="aiz-side-nav-item">
                                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Notes')}}</span>
                                                    <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                                </a>
                                                <ul class="aiz-side-nav-list level-4">
                                                    @can('view_notes')
                                                        <li class="aiz-side-nav-item">
                                                            <a class="aiz-side-nav-link" href="{{route('note.index')}}">
                                                                <span class="aiz-side-nav-text"
                                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Note List')}}</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['view_product_reviews', 'smart-bar', 'view_custom_label', 'product_bulk_export', 'product_bulk_import'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Operation')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('view_product_reviews')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('reviews.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['detail-reviews', 'custom-review.edit']) }}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Reviews')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('smart-bar')
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link {{ areActiveRoutes(['smart.bar'])}}"
                                                    href="{{route('smart.bar')}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Smart Bar')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view_custom_label')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('custom_label.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['custom_label.edit', 'custom_label.create'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Custom Label')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('product_bulk_import')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('product_bulk_upload.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Bulk Import') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('product_bulk_export')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('product_bulk_export.index')}}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Bulk Export')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @if(addon_is_activated('auction'))
                    @canany(['add_auction_product', 'view_all_auction_products', 'view_inhouse_auction_products', 'view_seller_auction_products', 'view_auction_product_orders'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15.964" height="16" viewBox="0 0 15.964 16">
                                        <path id="_608087065a4e8d47000761280c315020"
                                            data-name="608087065a4e8d47000761280c315020"
                                            d="M4.993,20.456a.456.456,0,0,0,.456.456h8.389a.456.456,0,0,0,.456-.456V19.009a1.256,1.256,0,0,0-1.254-1.254h-.2V16.32a.456.456,0,0,0-.456-.456H6.9a.456.456,0,0,0-.456.456v1.435h-.2a1.255,1.255,0,0,0-1.254,1.254Zm2.363-3.68H11.93v.979H7.356ZM5.905,19.009a.342.342,0,0,1,.342-.342H13.04a.342.342,0,0,1,.342.342V20H5.905Zm13.717-1.79a1.405,1.405,0,0,0,1.334-1.4,1.411,1.411,0,0,0-.461-1.042l-4.466-4.009L17.6,9.031a.831.831,0,0,0-.06-1.172L14.513,5.127a.816.816,0,0,0-.6-.213.824.824,0,0,0-.574.272L8.27,10.8a.83.83,0,0,0,.059,1.173L11.354,14.7a.83.83,0,0,0,1.172-.06l1.622-1.795,4.464,4.008a1.392,1.392,0,0,0,1.011.361ZM13.779,11.9l0,0,0,0L11.9,13.972,9,11.35l4.961-5.492,2.9,2.622L13.779,11.9Zm.981.275.658-.728,4.466,4.008a.492.492,0,1,1-.661.728Z"
                                            transform="translate(-4.993 -4.912)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Auction Products')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red" />
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('add_auction_product')
                                    <li class="aiz-side-nav-item">
                                        <a class="aiz-side-nav-link" href="{{route('auction_product_create.admin')}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Add New auction product')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_auction_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('auction.all_products')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['auction_product_edit.admin', 'product_bids.admin']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('All Auction Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_inhouse_auction_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('auction.inhouse_products')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Inhouse Auction Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @if (get_setting('vendor_system_activation') == 1)
                                    @can('view_seller_auction_products')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{route('auction.seller_products')}}" class="aiz-side-nav-link">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Auction Products') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                                @can('view_auction_product_orders')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('auction_products_orders')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['auction_products_orders.index']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Auction Products Orders') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                @if(addon_is_activated('wholesale'))
                    @canany(['add_wholesale_product', 'view_all_wholesale_products', 'view_inhouse_wholesale_products', 'view_sellers_wholesale_products'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path id="Union_48" data-name="Union 48"
                                            d="M1.2,14.236a1.762,1.762,0,0,1,1.2-1.657V2c0-.325-.268-.823-.6-.823H.6C.268,1.176,0,1.031,0,.7V.647A.645.645,0,0,1,.6,0H2.4A1.407,1.407,0,0,1,3.6,1.41v9.65h10a1.4,1.4,0,0,1,1.2,1.518,1.757,1.757,0,0,1,1.165,2.01,1.8,1.8,0,0,1-3.566-.353,1.761,1.761,0,0,1,1.2-1.656v-.342H3.6v.342a1.754,1.754,0,0,1,1.165,2.01A1.784,1.784,0,0,1,3.338,15.97,1.927,1.927,0,0,1,3,16,1.782,1.782,0,0,1,1.2,14.236Zm12.4,0a.594.594,0,0,0,.6.588h0a.6.6,0,0,0,.6-.589c0-.389-.272-.5-.6-.617C13.872,13.732,13.6,13.846,13.6,14.235Zm-11.2,0a.6.6,0,0,0,.6.588H3a.6.6,0,0,0,.6-.589c0-.389-.272-.5-.6-.617C2.671,13.732,2.4,13.846,2.4,14.235Zm4.216-4.158A1.615,1.615,0,0,1,5,8.462V6.692A1.615,1.615,0,0,1,6.615,5.077h5.77A1.616,1.616,0,0,1,14,6.692V8.462a1.616,1.616,0,0,1-1.616,1.615ZM6.234,6.311a.542.542,0,0,0-.157.382V8.462A.538.538,0,0,0,6.615,9h5.77a.538.538,0,0,0,.538-.538V6.692a.536.536,0,0,0-.538-.538H6.612A.535.535,0,0,0,6.234,6.311ZM5.473,3.527A1.617,1.617,0,0,1,5,2.385V1.616A1.615,1.615,0,0,1,6.615,0H9.384A1.616,1.616,0,0,1,11,1.616v.769A1.615,1.615,0,0,1,9.384,4H6.612A1.614,1.614,0,0,1,5.473,3.527Zm.761-2.293a.542.542,0,0,0-.157.382v.769a.538.538,0,0,0,.538.538H9.384a.538.538,0,0,0,.539-.538V1.616a.542.542,0,0,0-.157-.382.536.536,0,0,0-.382-.157H6.612A.535.535,0,0,0,6.234,1.234Z"
                                            fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Wholesale Products')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red"/>
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('add_wholesale_product')
                                    <li class="aiz-side-nav-item">
                                        <a class="aiz-side-nav-link" href="{{route('wholesale_product_create.admin')}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Add New Wholesale Product')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_wholesale_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('wholesale_products.all')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_edit.admin']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('All Wholesale Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_inhouse_wholesale_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('wholesale_products.in_house')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_edit.admin']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('In House Wholesale Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @if (get_setting('vendor_system_activation') == 1)
                                    @can('view_sellers_wholesale_products')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{route('wholesale_products.seller')}}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_edit.admin']) }}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Wholesale Products') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                            </ul>
                        </li>
                    @endcanany
                @endif

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Orders & Sales')}}
                    </span>
                </li>

                @canany(['view_all_orders', 'view_inhouse_orders', 'view_seller_orders', 'view_pickup_point_orders'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15.997" height="16" viewBox="0 0 15.997 16">
                                    <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -1.994)">
                                        <path id="Path_40726" data-name="Path 40726"
                                            d="M4.857,12.571H3.714A1.714,1.714,0,0,0,2,14.285V20.57a1.714,1.714,0,0,0,1.714,1.714H4.857A1.714,1.714,0,0,0,6.571,20.57V14.285a1.714,1.714,0,0,0-1.714-1.714Zm.571,8a.571.571,0,0,1-.571.571H3.714a.571.571,0,0,1-.571-.571V14.285a.571.571,0,0,1,.571-.571H4.857a.571.571,0,0,1,.571.571Zm5.142-6.284H9.427A1.714,1.714,0,0,0,7.713,16V20.57a1.714,1.714,0,0,0,1.714,1.714H10.57a1.714,1.714,0,0,0,1.714-1.714V16A1.714,1.714,0,0,0,10.57,14.285Zm.571,6.284a.571.571,0,0,1-.571.571H9.427a.571.571,0,0,1-.571-.571V16a.571.571,0,0,1,.571-.571H10.57a.571.571,0,0,1,.571.571ZM16.283,12H15.14a1.714,1.714,0,0,0-1.714,1.714V20.57a1.714,1.714,0,0,0,1.714,1.714h1.143A1.714,1.714,0,0,0,18,20.57V13.714A1.714,1.714,0,0,0,16.283,12Zm.571,8.57a.571.571,0,0,1-.571.571H15.14a.571.571,0,0,1-.571-.571V13.714a.571.571,0,0,1,.571-.571h1.143a.571.571,0,0,1,.571.571Z"
                                            transform="translate(0 -4.289)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40727" data-name="Path 40727"
                                            d="M17.947,2.548a.571.571,0,0,0-.366-.24l-1.588-.3a.571.571,0,1,0-.213,1.122l.093.018L11.233,5.932l-5.45-2.18a.572.572,0,1,0-.424,1.062L11.072,7.1a.571.571,0,0,0,.506-.041L16.68,4l-.067.354a.571.571,0,0,0,.457.668.579.579,0,0,0,.107.01.571.571,0,0,0,.56-.465l.3-1.588A.568.568,0,0,0,17.947,2.548Z"
                                            transform="translate(-1.286)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Sales')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('all_orders.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('All Orders')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_inhouse_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('inhouse_orders.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['inhouse_orders.index', 'inhouse_orders.show'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Inhouse orders')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (get_setting('vendor_system_activation') == 1)
                                @can('view_seller_orders')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller_orders.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['seller_orders.index', 'seller_orders.show'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Seller Orders')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @can('view_pickup_point_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('pick_up_point.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_point.index', 'pick_up_point.order_show'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Pick-up Point Orders')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_unpaid_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('unpaid_orders.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['unpaid_orders.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Unpaid Orders')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @if (addon_is_activated('pos_system') && (auth()->user()->can('pos_manager') || auth()->user()->can('pos_configuration')))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13.79" height="16" viewBox="0 0 13.79 16">
                                    <g id="_371925cdd3f531725a9fa8f3ebf8fe9e" data-name="371925cdd3f531725a9fa8f3ebf8fe9e"
                                        transform="translate(-2.26 0)">
                                        <path id="Path_40673" data-name="Path 40673"
                                            d="M10.69,7H3.26a1.025,1.025,0,0,0-1,1V18.45a1.03,1.03,0,0,0,1,1.05h7.43a1.03,1.03,0,0,0,1.03-1.03V8A1.025,1.025,0,0,0,10.69,7ZM4.94,17.86H3.995v-.95H4.94Zm0-2.355H3.995v-.95H4.94Zm0-2.355H3.995V12.2H4.94Zm2.5,4.71H6.5v-.95h.955Zm0-2.355H6.5v-.95h.955Zm0-2.355H6.5V12.2h.955Zm2.5,4.71H8.99v-.95h.95Zm0-2.355H8.99v-.95h.95Zm0-2.355H8.99V12.2h.95Zm.325-3a.17.17,0,0,1-.165.17H3.835a.17.17,0,0,1-.165-.17V8.795a.165.165,0,0,1,.165-.165H10.13a.165.165,0,0,1,.165.165Zm5.09-1.45H15.13v9.09h.25a.67.67,0,0,0,.67-.67V9.375a.67.67,0,0,0-.695-.675Z"
                                            transform="translate(0 -3.5)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <rect id="Rectangle_20842" data-name="Rectangle 20842" width="1.465" height="9.095"
                                            transform="translate(12.185 5.2)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <rect id="Rectangle_20843" data-name="Rectangle 20843" width="0.63" height="9.095"
                                            transform="translate(14.06 5.2)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40674" data-name="Path 40674"
                                            d="M13.895.895a.89.89,0,0,0-.26-.635A.91.91,0,0,0,13,0a.895.895,0,0,0-.91.895v.53h1.79Zm-2.2,0a.76.76,0,0,1,0-.145.68.68,0,0,1,0-.1h.01A.5.5,0,0,1,11.755.5.43.43,0,0,1,11.79.4a1.2,1.2,0,0,1,.145-.26.5.5,0,0,1,.04-.055L12.045,0H7.995A.815.815,0,0,0,7.18.81V3.03h4.5Z"
                                            transform="translate(-2.46)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('POS System')}}</span>
                            @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                    class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="red" />
                                </svg>
                            @endif
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('pos_manager')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('poin-of-sales.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.index', 'poin-of-sales.create'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('POS Manager')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('pos_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('poin-of-sales.activation')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('POS Configuration')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('pos_Order')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('pos.orders')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('POS Orders')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('add_pos_products')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('admin.pos.products')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('POS Products')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if (addon_is_activated('refund_request'))
                    @canany(['view_refund_requests', 'view_approved_refund_requests', 'view_rejected_refund_requests', 'refund_request_configuration', 'set_category_wise_refund'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path id="_4436b8ef9250481406399210799cb7f1"
                                            data-name="4436b8ef9250481406399210799cb7f1"
                                            d="M19.25,11.25a8.031,8.031,0,0,1-15.995,1,.688.688,0,0,1,1.365-.169A6.643,6.643,0,1,0,7.112,6.039h.866a.686.686,0,1,1,0,1.371H5.384A.687.687,0,0,1,4.7,6.724V4.138a.688.688,0,0,1,1.376,0v.987A8.024,8.024,0,0,1,19.25,11.25ZM11.278,6.907a.687.687,0,0,0-.688.686v.253a2.053,2.053,0,0,0-1.824,2.247,2.146,2.146,0,0,0,2.175,1.842h.8a.686.686,0,1,1,0,1.371h-1.6a.686.686,0,1,0,0,1.371h.458v.229a.688.688,0,0,0,1.376,0v-.26a2.113,2.113,0,0,0,1.824-1.811,2.062,2.062,0,0,0-2.053-2.272h-.917a.686.686,0,1,1,0-1.371h1.609a.686.686,0,1,0,0-1.371h-.462V7.593A.687.687,0,0,0,11.278,6.907Z"
                                            transform="translate(-3.25 -3.25)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Refunds') }}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red" />
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_refund_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('refund_requests_all')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['refund_requests_all'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Refund Requests')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_dispute_refund_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('dispute_refund_requests_all')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['dispute_refund_requests_all'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Dispute Refund Requests')}}</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('refund_request_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('refund_time_config')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Refund Configuration')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_refund_reason')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('refund_reason_index')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['refund_reason_index', 'refund_reason_create', 'refund_reason_edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Refund Reasons')}}</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('set_category_wise_refund')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('categories_wise_product_refund')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Category Based Refund')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                @if (addon_is_activated('preorder'))
                    @canany(['preorder_dashboard', 'add_preorder_product', 'view_all_preorder_products', 'view_all_preorders', 'view_all_inhouse_preorders', 'view_all_seller_preorders', 'view_all_delayed_prepayment_preorders', 'view_all_final_preorders', 'view_preorder_seller_commission_history', 'preorder_settings', 'view_all_preorder_product_conversations', 'view_all_preorder_product_queries', 'view_all_preorder_product_reviews', 'view_all_faqs', 'view_all_preorder_notification_types'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.002" viewBox="0 0 16 16.002">
                                        <path id="Union_63" data-name="Union 63"
                                            d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z"
                                            transform="translate(-14072 -885.998)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Preorder')}}</span>
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <!--Submenu-->
                            <ul class="aiz-side-nav-list level-2">
                                @can('preorder_dashboard')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('preorder.dashboard')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Preorder Home')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('add_preorder_product')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('preorder-product.create')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Add New Preorder products')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_preorder_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('preorder-product.index')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['preorder-product.edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Preorder Products')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @canany(['view_all_preorders', 'view_all_inhouse_preorders', 'view_all_seller_preorders', 'view_all_delayed_prepayment_preorders', 'view_all_final_preorders'])
                                    <li class="aiz-side-nav-item">
                                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Orders (Preorder)')}}</span>
                                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                        </a>
                                        <ul class="aiz-side-nav-list level-3">
                                            @php
                                                $adminId = get_admin()->id;
                                                $all_preorders = \App\Models\Preorder::where('is_viewed', 0);
                                                $all_preorders_count = $all_preorders->count();
                                                $all_preorders_for_inhouse = clone $all_preorders;
                                                $inhouse_preorders_count = $all_preorders_for_inhouse->where('product_owner_id', $adminId)->count();
                                                $all_preorders_for_sellers = clone $all_preorders;
                                                $sellers_preorders_count = $all_preorders_for_sellers->where('product_owner_id', '!=', $adminId)->count();
                                                $all_delayed_prepayment_preorders = clone $all_preorders;
                                                $delayed_prepayment_preorders_count = $all_preorders->where('prepayment_confirm_status', 0)->where('request_preorder_status', 2)->whereHas('preorder_product', function ($query) {
                                                    $query->where('is_available', 1)->where('is_prepayment', 1);
                                                })->where('request_preorder_time', '<', \Carbon\Carbon::now()->subDay())->count();
                                                $all_delayed_final_orders = clone $all_preorders;
                                                $delayed_final_orders_count = $all_preorders->whereHas('preorder_product', function ($query) {
                                                    $query->where('is_available', 1);
                                                })
                                                    ->where(function ($query) {
                                                        $query->whereHas('preorder_product', function ($subQuery) {
                                                            $subQuery->where('is_prepayment', 1);
                                                        })
                                                            ->where('prepayment_confirm_status', 2)
                                                            ->where('final_order_status', 0)
                                                            ->where('prepayment_confirmation_time', '<', \Carbon\Carbon::now()->subDay())
                                                            ->orWhere(function ($subQuery) {
                                                                $subQuery->whereHas('preorder_product', function ($innerQuery) {
                                                                    $innerQuery->where('is_prepayment', 0);
                                                                })
                                                                    ->where('request_preorder_status', 2)
                                                                    ->where('final_order_status', 0)
                                                                    ->where('request_preorder_time', '<', \Carbon\Carbon::now()->subDay());
                                                            });
                                                    })->count();
                                            @endphp
                                            @can('view_all_preorders')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('all_preorder.list') }}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['preorder-order.show'])}} }}">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('All Orders')}}
                                                            <span class="badge badge-info ml-1">{{ $all_preorders_count ?: '' }}</span>
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view_all_inhouse_preorders')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('inhouse_preorder.list') }}" class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Inhouse Orders')}}
                                                            <span
                                                                class="badge badge-info ml-1">{{ $inhouse_preorders_count ?: '' }}</span></span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view_all_seller_preorders')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('seller_preorder.list') }}" class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Seller Orders')}}
                                                            <span
                                                                class="badge badge-info ml-1">{{ $sellers_preorders_count ?: '' }}</span></span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view_all_delayed_prepayment_preorders')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('delayed_prepayment_preorders.list') }}"
                                                        class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Delayed Prepayment')}}
                                                            <span
                                                                class="badge badge-info ml-1">{{ $delayed_prepayment_preorders_count ?: '' }}</span></span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view_all_final_preorders')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('delayed_final_orders.list') }}" class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Delayed Final Orders')}}
                                                            <span
                                                                class="badge badge-info ml-1">{{ $delayed_final_orders_count ?: '' }}</span></span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcanany
                                @can('view_preorder_seller_commission_history')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('preorder-commission-history') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate("Preorder Commission History")}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('preorder_settings')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('preorder-settings') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['faqs.create', 'faq.edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate("Preorder Settings")}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_preorder_product_conversations')
                                    @if (get_setting('conversation_system') == 1)
                                        @php
                                            $preorderConversation = get_non_viewed_preorder_conversations();
                                        @endphp
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('preorder-conversations.admin_index') }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['preorder-conversations.admin_index', 'preorder-conversations.admin_show']) }}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Preorder Product Conversation') }}</span>
                                                @if ($preorderConversation > 0)
                                                    <span class="badge badge-danger">({{ $preorderConversation }})</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endcan
                                @can('view_all_preorder_product_queries')
                                    @if (get_setting('product_query_activation') == 1)
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('preorder.product_query.index') }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['preorder.product_query.index', 'preorder.product_query.show']) }}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Preorder Product Queries') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endcan
                                @can('view_all_preorder_product_reviews')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('preorder.product_reviews.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['preorder.product_detail_reviews']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Preorder Product Reviews') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_faqs')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('faqs.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['faqs.create', 'faq.edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate("FAQs")}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_preorder_notification_types')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('preorder-notification-types.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['preorder.notification-type.edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Preorder Notification Types')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Users')}}
                    </span>
                </li>

                @canany(['view_all_customers', 'view_classified_products', 'view_classified_packages'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_40769" data-name="Path 40769"
                                        d="M8,10.667A2.667,2.667,0,1,1,10.667,8,2.667,2.667,0,0,1,8,10.667Zm0-4A1.333,1.333,0,1,0,9.333,8,1.333,1.333,0,0,0,8,6.667Zm4,8.667a4,4,0,1,0-8,0,.667.667,0,0,0,1.333,0,2.667,2.667,0,1,1,5.333,0,.667.667,0,0,0,1.333,0Zm0-10a2.667,2.667,0,1,1,2.667-2.667A2.667,2.667,0,0,1,12,5.333Zm0-4a1.333,1.333,0,1,0,1.333,1.333A1.333,1.333,0,0,0,12,1.333ZM16,10a4,4,0,0,0-4-4,.667.667,0,0,0,0,1.333A2.667,2.667,0,0,1,14.667,10,.667.667,0,1,0,16,10ZM4,5.333A2.667,2.667,0,1,1,6.667,2.667,2.667,2.667,0,0,1,4,5.333Zm0-4A1.333,1.333,0,1,0,5.333,2.667,1.333,1.333,0,0,0,4,1.333ZM1.333,10A2.667,2.667,0,0,1,4,7.333.667.667,0,0,0,4,6a4,4,0,0,0-4,4,.667.667,0,0,0,1.333,0Z"
                                        fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Customers') }}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_customers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('customers.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['customers.create'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Customer list') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if(addon_is_activated('portfolio_system') && get_setting('customer_verification'))
                                @can('view_all_customers')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('customers.unverified.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['customers.create'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Unverified Customers') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @if(get_setting('classified_product') == 1)
                                @can('view_classified_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('classified_products')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Classified Products')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_classified_packages')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('customer_packages.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['customer_packages.index', 'customer_packages.create', 'customer_packages.edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Classified Packages') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                        </ul>
                    </li>
                @endcanany

                @if (get_setting('vendor_system_activation') == 1)
                    @canany(['view_all_seller', 'seller_payment_history', 'view_seller_payout_requests', 'seller_commission_configuration', 'view_all_seller_packages', 'seller_verification_form_configuration', 'set_category_wise_commission', 'set_seller_based_commission'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path id="ef567a7fa3ca8f4541f8ab7b62352aa6"
                                            d="M19,9.625a.638.638,0,0,0-.079-.307l-2.779-5A.614.614,0,0,0,15.606,4H6.394a.614.614,0,0,0-.536.318l-2.779,5A.638.638,0,0,0,3,9.625a2.5,2.5,0,0,0,1.231,2.153V18.75A1.24,1.24,0,0,0,5.462,20H9.08a1.24,1.24,0,0,0,1.231-1.25V16.058a.759.759,0,0,1,.615-.773.684.684,0,0,1,.534.176.706.706,0,0,1,.229.521V18.75A1.24,1.24,0,0,0,12.92,20h3.618a1.24,1.24,0,0,0,1.231-1.25V11.777A2.5,2.5,0,0,0,19,9.625Zm-1.239.149a1.23,1.23,0,0,1-2.453-.149.578.578,0,0,0-.017-.086.548.548,0,0,0-.006-.084L14.114,5.25h1.132ZM9.164,5.25h1.22V9.625a1.23,1.23,0,0,1-2.455.063Zm2.451,0h1.22l1.235,4.437a1.23,1.23,0,0,1-2.455-.062Zm-4.862,0H7.886l-1.169,4.2a.548.548,0,0,0-.006.084.578.578,0,0,0-.018.086,1.23,1.23,0,0,1-2.453.149Zm9.785,13.5H12.92V15.981a1.964,1.964,0,0,0-.635-1.446,1.9,1.9,0,0,0-1.482-.491A2,2,0,0,0,9.08,16.061V18.75H5.462V12.125a2.439,2.439,0,0,0,1.846-.848A2.419,2.419,0,0,0,11,11.261a2.419,2.419,0,0,0,3.692.016,2.439,2.439,0,0,0,1.846.848Z"
                                            transform="translate(-3 -4)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Sellers') }}</span>
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_all_seller')
                                    <li class="aiz-side-nav-item">
                                        @php
                                            $sellers = \App\Models\Shop::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                                        @endphp
                                        <a href="{{ route('sellers.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['sellers.index', 'sellers.profile']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('All Seller') }}</span>
                                            @if($sellers > 0)<span class="badge badge-info">{{ $sellers }}</span> @endif
                                        </a>
                                    </li>
                                @endcan
                                @can('view_pending_seller')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('sellers.registration_pending') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Applied Seller') }}</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('view_all_seller_rating_and_followers')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('sellers.rating_followers') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Rating & Followers') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('seller_payment_history')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('sellers.payment_histories') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Payouts') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_seller_payout_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('withdraw_requests_all') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Payout Requests') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('seller_commission_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('business_settings.vendor_commission') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Commission') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('set_seller_based_commission')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller_based_commission') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Based Commission') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('set_category_wise_commission')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('categories_wise_commission')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Category Based Commission')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @if (addon_is_activated('seller_subscription'))
                                    @can('view_all_seller_packages')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('seller_packages.index') }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['seller_packages.index', 'seller_packages.create', 'seller_packages.edit'])}}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Packages') }}</span>
                                                @if (env("DEMO_MODE") == "On")
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                                        viewBox="0 0 16 14.001" class="mx-2">
                                                        <path id="Union_49" data-name="Union 49"
                                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                                            transform="translate(19337 -3329)" fill="red" />
                                                    </svg>
                                                @endif
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                                @can('seller_verification_form_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller_verification_form.index') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Verification Form') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                @if (addon_is_activated('delivery_boy'))
                    @canany(['view_all_delivery_boy', 'add_delivery_boy', 'delivery_boy_payment_history', 'collected_histories_from_delivery_boy', 'order_cancle_request_by_delivery_boy', 'delivery_boy_configuration'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg id="Group_28285" data-name="Group 28285" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" viewBox="0 0 16 16">
                                        <path id="Path_40728" data-name="Path 40728"
                                            d="M12.406,9.375h-.625v-.84a3.28,3.28,0,0,0,1.406-2.691V4.375h2.344a.469.469,0,0,0,0-.937H13.5a3.594,3.594,0,0,0-7.184.156v.313a.469.469,0,0,0,.313.442v1.5A3.28,3.28,0,0,0,8.031,8.535v.84H7.406a3.605,3.605,0,0,0-2.113.688H1.406a.469.469,0,0,0-.419.259L.049,12.2h0a.466.466,0,0,0-.05.209v3.125A.469.469,0,0,0,.469,16H15.531A.469.469,0,0,0,16,15.531V12.969A3.6,3.6,0,0,0,12.406,9.375ZM9.906.938a2.66,2.66,0,0,1,2.652,2.5h-5.3A2.66,2.66,0,0,1,9.906.938ZM7.562,5.844V4.375H12.25V5.844a2.344,2.344,0,0,1-4.688,0ZM9.906,9.125a3.271,3.271,0,0,0,.938-.137V10a.938.938,0,0,1-1.875,0V8.988A3.27,3.27,0,0,0,9.906,9.125ZM1.7,11H5.554l.469.938h-4.8ZM.937,12.875H6.312v2.188H.937Zm14.125,2.188H7.25V12.406A.466.466,0,0,0,7.2,12.2h0l-.836-1.672a2.638,2.638,0,0,1,1.042-.212h.652a1.875,1.875,0,0,0,3.7,0h.652a2.659,2.659,0,0,1,2.656,2.656Z"
                                            fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40729" data-name="Path 40729"
                                            d="M376.719,405h-1.25a.469.469,0,0,0,0,.938h1.25a.469.469,0,0,0,0-.937Z"
                                            transform="translate(-363.281 -392.344)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Delivery Boy')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red" />
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_all_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('delivery-boys.index')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('All Delivery Boy')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('add_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('delivery-boys.create')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Add Delivery Boy')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('delivery_boy_payment_history')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('delivery-boys-payment-histories')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Payment Histories')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('collected_histories_from_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('delivery-boys-collection-histories')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Collected Histories')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('order_cancle_request_by_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('delivery-boy.cancel-request')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Cancel Request')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('delivery_boy_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('delivery-boy-configuration')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Configuration')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Marketing & Promotions')}}
                    </span>
                </li>

                @canany(['view_promotion_and_offers_dashboard', 'view_all_flash_deals', 'todays_deal_products.index', 'view_all_coupons', 'set_category_wise_discount', 'view_promotional_product'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px"
                                    fill="{{ get_setting('navbar_text_color') }}">
                                    <path
                                        d="M160-80v-440H80v-240h208q-5-9-6.5-19t-1.5-21q0-50 35-85t85-35q23 0 43 8.5t37 23.5q17-16 37-24t43-8q50 0 85 35t35 85q0 11-2 20.5t-6 19.5h208v240h-80v440H160Zm371.5-748.5Q520-817 520-800t11.5 28.5Q543-760 560-760t28.5-11.5Q600-783 600-800t-11.5-28.5Q577-840 560-840t-28.5 11.5ZM360-800q0 17 11.5 28.5T400-760q17 0 28.5-11.5T440-800q0-17-11.5-28.5T400-840q-17 0-28.5 11.5T360-800ZM160-680v80h280v-80H160Zm280 520v-360H240v360h200Zm80 0h200v-360H520v360Zm280-440v-80H520v80h280Z" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Promotion & Offers') }}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_promotion_and_offers_dashboard')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('promotion_and_offers_dashboard') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Promotion & Offers Home') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_promotional_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('promotional_products.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Promotional Product') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_seller_promotional_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller_promotional_products.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Promotional Product') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_flash_deals')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('flash_deals.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Flash Sales') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_todays_deals')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('todays_deal_products.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate("Today's Deals") }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('set_category_wise_discount')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('categories_wise_product_discount')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Category Based Discount')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (get_setting('coupon_system') == 1 && auth()->user()->can('view_all_coupons'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('coupon.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index', 'coupon.create', 'coupon.edit'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Coupon') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endcanany

                @canany(['view_all_dynamic_popups', 'view_all_custom_alerts', 'manage_email_templates', 'send_newsletter', 'notification_settings', 'view_all_notification_types', 'send_custom_notification', 'view_custom_notification_history', 'send_bulk_sms', 'view_all_subscribers', 'view_blogs', 'view_blog_categories'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="_8dbc7a38f7bdee3f0be2c44d010760a2" data-name="8dbc7a38f7bdee3f0be2c44d010760a2"
                                        transform="translate(0 -4.027)">
                                        <path id="Path_40740" data-name="Path 40740"
                                            d="M38.286,16.393a.555.555,0,0,1-.344-.119L34.032,13.2a.557.557,0,0,1-.213-.438v-5.1a.556.556,0,0,1,.212-.438l3.91-3.074a.557.557,0,0,1,.9.438V15.836a.556.556,0,0,1-.556.557Zm-3.354-3.9,2.8,2.2V5.73l-2.8,2.2Z"
                                            transform="translate(-25.364 0)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40741" data-name="Path 40741"
                                            d="M9.011,22.556H3.093a3.1,3.1,0,0,1,0-6.192H9.011a.557.557,0,0,1,.557.557V22A.557.557,0,0,1,9.011,22.556ZM3.093,17.478a1.982,1.982,0,0,0,0,3.964H8.455V17.478Z"
                                            transform="translate(0 -9.25)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40742" data-name="Path 40742"
                                            d="M10.2,31.9a1.895,1.895,0,0,1-1.847-1.5l-.974-5.455a.557.557,0,1,1,1.089-.229l.975,5.455a.777.777,0,1,0,1.521-.32l-.824-4.74a.557.557,0,1,1,1.089-.229l.824,4.74A1.894,1.894,0,0,1,10.2,31.9Zm8.487-7.6h-.862a.557.557,0,0,1,0-1.114h.862a1.105,1.105,0,0,0,1.1-1.105,1.106,1.106,0,0,0-1.1-1.105h-.862a.557.557,0,0,1,0-1.114h.862a2.22,2.22,0,0,1,1.566,3.79A2.2,2.2,0,0,1,18.683,24.3Z"
                                            transform="translate(-4.9 -11.875)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Marketing') }}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('manage_marketing_dashboard')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('marketing_dashboard')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Marketing Home') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_dynamic_popups')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('dynamic-popups.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['dynamic-popups.index', 'dynamic-popups.create', 'dynamic-popups.edit'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Dynamic Pop-up') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_custom_alerts')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('custom-alerts.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['custom-alerts.index', 'custom-alerts.create', 'custom-alerts.edit', 'custom-sale-alert.edit'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Custom Alert') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_custom_sale_alert')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('custom-sale-alerts.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['custom-sale-alerts.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Custom Sell Alert') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_email_templates')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('all_email_templates.index')}}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Email Templates') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['view_blogs', 'view_blog_categories'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Blog System')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('view_blogs')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('blog.index') }}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['blog.create', 'blog.edit'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{ translate('All Blog Posts') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view_blog_categories')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('blog-category.index') }}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['blog-category.create', 'blog-category.edit'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Categories') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @can('send_newsletter')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('newsletters.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Newsletters') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['notification_settings', 'view_all_notification_types', 'send_custom_notification', 'view_custom_notification_history'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Notification')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('view_all_notification_types')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('notification-type.index') }}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['notification-type.edit'])}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Notification Types')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('send_custom_notification')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('custom_notification') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Custom Notification')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view_custom_notification_history')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('custom_notification.history') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Custom Notification History')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('notification_settings')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('notification.settings') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Settings')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @if (addon_is_activated('otp_system') && auth()->user()->can('send_bulk_sms'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('sms.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Bulk SMS') }}</span>
                                        @if (env("DEMO_MODE") == "On")
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                                viewBox="0 0 16 14.001" class="mx-2">
                                                <path id="Union_49" data-name="Union 49"
                                                    d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                                    transform="translate(19337 -3329)" fill="red" />
                                            </svg>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @can('view_all_subscribers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('subscribers.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Subscribers') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('custom_visitors_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('custom_product_visitors')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['custom_product_visitors'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Custom Visitors') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['manage_google_analytics', 'manage_pixel_analytics', 'manage_sitemap_generator', 'manage_custom_script'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Union_77" data-name="Union 77"
                                        d="M5.5,16a.625.625,0,0,1,0-1.25H7.375V13.5H2.5A2.5,2.5,0,0,1,0,11V2.5A2.5,2.5,0,0,1,2.5,0h11A2.5,2.5,0,0,1,16,2.5V11a2.5,2.5,0,0,1-2.5,2.5h-3a.625.625,0,0,1,0-1.25h3A1.251,1.251,0,0,0,14.75,11V2.5A1.251,1.251,0,0,0,13.5,1.25H2.5A1.251,1.251,0,0,0,1.25,2.5V11A1.251,1.251,0,0,0,2.5,12.25H8a.625.625,0,0,1,.625.626V14.75H10.5a.625.625,0,1,1,0,1.25ZM2.377,9.539a1.105,1.105,0,0,1-.03-1.2,1.126,1.126,0,0,1,1.206-.495.988.988,0,0,1,.136.04L4.866,6.826l.5-.447a1.113,1.113,0,0,1,.322-1.087,1.13,1.13,0,0,1,1.245-.176,1.1,1.1,0,0,1,.607.9.434.434,0,0,1,.007.088.68.68,0,0,1-.006.09L9,7.1a1.1,1.1,0,0,1,.864-.13l.051-.057L11.169,5.5l.516-.578a1.136,1.136,0,0,1,.209-1.131,1.1,1.1,0,0,1,1.162-.319,1.13,1.13,0,0,1,.765.97.439.439,0,0,1,.006.084,1.124,1.124,0,0,1-.816,1.067,1.1,1.1,0,0,1-.578,0l-.052.059L11.122,7.059l-.52.584a1.156,1.156,0,0,1,.074.332.432.432,0,0,1,0,.059h0a.467.467,0,0,1,0,.078,1.122,1.122,0,0,1-.812.989A1.106,1.106,0,0,1,8.739,8.76a1.175,1.175,0,0,1-.27-.657.955.955,0,0,1,0-.154l-1.455-.9a1.193,1.193,0,0,1-.511.159,1,1,0,0,1-.474-.074L4.856,8.187l-.5.448a1.155,1.155,0,0,1,.042.281.469.469,0,0,1-.008.1,1.1,1.1,0,0,1-1.1,1.009,1.136,1.136,0,0,1-.913-.486ZM3.3,9.039ZM3.332,9.03Zm-.074,0-.005-.006h0ZM3.219,9.03Zm.087-.006.019-.005L3.354,9,3.36,9l.009-.009.015-.026L3.39,8.93V8.909l-.007-.025-.02-.036-.005-.006-.007-.007-.023-.013L3.3,8.815H3.276l-.02.005-.029.016h0l-.014.013L3.2,8.875,3.19,8.9v.048l.017.03.006.006.008.007.026.016.022.006Zm-.056,0ZM3.2,9Zm.182-.037Zm.008-.008Zm0-.005Zm-.206-.07v0Zm.174-.04Zm-.011-.009-.006-.006h0ZM3.335,8.82ZM9.586,8.155ZM9.615,8.146Zm-.074,0V8.14h0ZM9.5,8.146Zm.087-.006h.018l.029-.017.006-.006L9.651,8.1l.016-.027.006-.022V8.038A.217.217,0,0,0,9.663,8l-.01-.018L9.644,7.97l-.011-.013-.028-.016-.023-.006H9.537l-.03.017h0L9.5,7.966l-.006.011L9.477,8v.049l.005.02L9.5,8.1l0,0,.008.008.026.016.021.006Zm-.057,0ZM9.478,8.132ZM9.66,8.1Zm.008-.009Zm.005-.006V8.069h0Zm-.005-.093Zm-.055-.045Zm0,0A.032.032,0,0,0,9.6,7.928l.011.006ZM6.462,6.228ZM6.493,6.218ZM6.412,6.218ZM6.454,6.2h.017L6.5,6.181h0l.006-.006.019-.033.006-.023V6.076h0l-.017-.029L6.508,6.04,6.5,6.027l-.026-.016L6.452,6H6.424L6.4,6.01l-.027.015-.008.008-.008.008-.016.027-.006.021V6.12l.006.023.015.026.014.014h0L6.4,6.2l.024.007Zm-.121-.13.006-.006h0l-.006.009Zm.008-.009ZM6.541,6.043ZM6.534,6.008Zm-.047,0ZM6.48,6H6.472l.005.006ZM12.739,4.643ZM12.77,4.634Zm-.082-.006-.01-.006h0Zm.047-.007.02-.005.027-.016.007-.006.008-.009.015-.026.007-.026V4.488l-.012-.02L12.8,4.454l-.01-.009-.028-.015h-.077l-.03.017-.006.006-.008.008-.016.027-.005.019V4.54l.005.019.012.02.008.008.011.013.028.016.022.006Zm.07-.044Zm.01-.011Zm.005-.006Zm-.209-.06.006-.005h0Zm.009-.01Zm.2-.011Zm-.006,0Zm.007-.013h-.008Zm-.008,0ZM12.8,4.432Z"
                                        fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Marketing Analytics') }}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('manage_marketing_analytics_dashboard')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('marketing_analytics_dashboard') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Marketing Analytics Home')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_google_analytics')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('google-analytics-config') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google Analytics (GA4)')}}</span>
                                    </a>
                                </li>
                                @if (get_setting('google_analytics') == 1)
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('google-analytics-test.result') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google Analytics Reports')}}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('google-tag-manager-config') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google TAG Manager')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_pixel_analytics')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('pixel_analytics.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Meta Pixel')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('pixel_conversation_api.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Meta Conversion API')}}</span>
                                    </a>
                                </li>
                            @endcan
                            
                            @canany(['delete_gmc_product', 'edit_gmc_product', 'add_gmc_product'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google Marchant Center')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('google_merchant_center.configuration') }}" class="aiz-side-nav-link">
                                                <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Configuration')}}</span>
                                            </a>
                                        </li>
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('google_merchant_center.feed') }}" class="aiz-side-nav-link">
                                                <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Generate Feed')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan

                            @canany(['delete_gmc_product', 'edit_gmc_product', 'add_gmc_product'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Facebook Catalogue')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('facebook_catalogue.configuration') }}" class="aiz-side-nav-link ">
                                                <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Configuration')}}</span>
                                            </a>
                                        </li>
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('facebook_catalogue.feed') }}" class="aiz-side-nav-link">
                                                <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Generate Feed')}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan

                            @can('manage_custom_script')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom_script') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Custom Script')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_sitemap_generator')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('sitemap_generator') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Sitemap Generator')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_global_seo')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('global_seo') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Global SEO')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @if (addon_is_activated('affiliate_system'))
                    @canany(['affiliate_registration_form_config', 'affiliate_configurations', 'view_affiliate_users', 'view_all_referral_users', 'view_affiliate_withdraw_requests', 'view_affiliate_logs'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg id="Group_28297" data-name="Group 28297" xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" viewBox="0 0 16 16">
                                        <path id="Path_40762" data-name="Path 40762"
                                            d="M43.75,273.875a1.875,1.875,0,1,0-1.875,1.875A1.877,1.877,0,0,0,43.75,273.875Zm-1.875.625a.625.625,0,1,1,.625-.625A.626.626,0,0,1,41.875,274.5Z"
                                            transform="translate(-38.75 -263.5)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40763" data-name="Path 40763"
                                            d="M3.125,392A3.129,3.129,0,0,0,0,395.125a.625.625,0,0,0,.625.625h5a.625.625,0,0,0,.625-.625A3.129,3.129,0,0,0,3.125,392Zm-1.768,2.5a1.875,1.875,0,0,1,3.536,0Z"
                                            transform="translate(0 -379.75)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40764" data-name="Path 40764"
                                            d="M355.75,273.875a1.875,1.875,0,1,0-1.875,1.875A1.877,1.877,0,0,0,355.75,273.875Zm-1.875.625a.625.625,0,1,1,.625-.625A.626.626,0,0,1,353.875,274.5Z"
                                            transform="translate(-341 -263.5)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40765" data-name="Path 40765"
                                            d="M315.125,392A3.129,3.129,0,0,0,312,395.125a.625.625,0,0,0,.625.625h5a.625.625,0,0,0,.625-.625A3.129,3.129,0,0,0,315.125,392Zm-1.768,2.5a1.875,1.875,0,0,1,3.536,0Z"
                                            transform="translate(-302.25 -379.75)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40766" data-name="Path 40766"
                                            d="M199.75,1.875a1.875,1.875,0,1,0-1.875,1.875A1.877,1.877,0,0,0,199.75,1.875Zm-1.875.625a.625.625,0,1,1,.625-.625A.626.626,0,0,1,197.875,2.5Z"
                                            transform="translate(-189.875)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40767" data-name="Path 40767"
                                            d="M156.625,123.75h5a.625.625,0,0,0,.625-.625,3.125,3.125,0,0,0-6.25,0A.625.625,0,0,0,156.625,123.75Zm2.5-2.5a1.878,1.878,0,0,1,1.768,1.25h-3.536A1.878,1.878,0,0,1,159.125,121.25Z"
                                            transform="translate(-151.125 -116.25)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40768" data-name="Path 40768"
                                            d="M180.893,279.472a.625.625,0,0,0-.173-.867l-1.6-1.064v-.915a.625.625,0,0,0-1.25,0v.915l-1.6,1.064a.625.625,0,1,0,.693,1.04l1.528-1.019,1.528,1.019A.625.625,0,0,0,180.893,279.472Z"
                                            transform="translate(-170.498 -267.375)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Affiliate System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red"/>
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('affiliate_registration_form_config')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('affiliate.configs')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Affiliate Registration Form')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('affiliate_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('affiliate.index')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Affiliate Configurations')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_affiliate_users')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('affiliate.users')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.users', 'affiliate_users.show_verification_request', 'affiliate_user.payment_history'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Affiliate Users')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_referral_users')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('refferals.users')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Referral Users')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_affiliate_withdraw_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('affiliate.withdraw_requests')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Affiliate Withdraw Requests')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_affiliate_logs')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('affiliate.logs.admin')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Affiliate Logs')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                @if (addon_is_activated('club_point'))
                    @canany(['club_point_configurations', 'set_club_points', 'view_users_club_points'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <g id="Group_28289" data-name="Group 28289" transform="translate(-24 -896)">
                                            <g id="Group_28287" data-name="Group 28287" transform="translate(28 900)">
                                                <path id="Path_40745" data-name="Path 40745"
                                                    d="M6,0a6,6,0,1,0,6,6A6.007,6.007,0,0,0,6,0ZM6,10.909A4.909,4.909,0,1,1,10.909,6,4.915,4.915,0,0,1,6,10.909Z"
                                                    fill="{{ get_setting('navbar_text_color') }}" />
                                                <path id="Path_40746" data-name="Path 40746"
                                                    d="M76.442,72.034l-1.726-.251-.772-1.564a.545.545,0,0,0-.978,0l-.772,1.564-1.726.251a.545.545,0,0,0-.3.93l1.249,1.218L71.119,75.9a.545.545,0,0,0,.791.575l1.544-.812L75,76.477a.545.545,0,0,0,.254.063h0a.546.546,0,0,0,.531-.667l-.29-1.69,1.249-1.218a.545.545,0,0,0-.3-.93ZM74.528,73.6a.545.545,0,0,0-.157.483l.157.913-.82-.431a.545.545,0,0,0-.508,0l-.82.431.157-.913a.545.545,0,0,0-.157-.483l-.663-.646.916-.133a.545.545,0,0,0,.411-.3l.41-.83.41.83a.545.545,0,0,0,.411.3l.916.133Z"
                                                    transform="translate(-67.454 -67.373)" fill="{{ get_setting('navbar_text_color') }}" />
                                            </g>
                                            <path id="Subtraction_228" data-name="Subtraction 228"
                                                d="M-19334.447,3339.91h0A6.017,6.017,0,0,1-19337,3335a6.005,6.005,0,0,1,6-6,6.018,6.018,0,0,1,4.91,2.554,7.579,7.579,0,0,0-.906-.054c-.182,0-.365.007-.545.02a4.882,4.882,0,0,0-3.459-1.427,4.912,4.912,0,0,0-4.906,4.906,4.872,4.872,0,0,0,1.428,3.458c-.014.183-.02.361-.02.545a8.1,8.1,0,0,0,.053.905Zm.908-4.586h0l-.754-.732a.547.547,0,0,1-.135-.558.534.534,0,0,1,.441-.369l1.723-.252.771-1.568a.551.551,0,0,1,.49-.3.546.546,0,0,1,.49.3l.207.421a7.491,7.491,0,0,0-3.234,3.057Z"
                                                transform="translate(19361 -2433)" fill="{{ get_setting('navbar_text_color') }}" />
                                        </g>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Club Point System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red" />
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('club_point_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('club_points.configs') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Club Point Configurations')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('set_club_points')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('set_product_points')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['set_product_points', 'product_club_point.edit'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Set Product Point')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_users_club_points')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('club_points.index')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['club_points.index', 'club_point.details'])}}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('User Points')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Report')}}
                    </span>
                </li>

                @canany(['earning_report', 'in_house_product_sale_report', 'seller_products_sale_report', 'products_stock_report', 'product_wishlist_report', 'user_search_report', 'commission_history_report', 'wallet_transaction_report'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg id="stats_3916778" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16">
                                    <path id="Path_40739" data-name="Path 40739"
                                        d="M16,16H2a2,2,0,0,1-2-2V0H1.333V14A.667.667,0,0,0,2,14.667H16Z" fill="{{ get_setting('navbar_text_color') }}" />
                                    <rect id="Rectangle_21340" data-name="Rectangle 21340" width="1.333" height="6"
                                        transform="translate(9.333 7.333)" fill="{{ get_setting('navbar_text_color') }}" />
                                    <rect id="Rectangle_21341" data-name="Rectangle 21341" width="1.333" height="6"
                                        transform="translate(4 7.333)" fill="{{ get_setting('navbar_text_color') }}" />
                                    <rect id="Rectangle_21342" data-name="Rectangle 21342" width="1.333" height="9.333"
                                        transform="translate(12 4)" fill="{{ get_setting('navbar_text_color') }}" />
                                    <rect id="Rectangle_21343" data-name="Rectangle 21343" width="1.333" height="9.333"
                                        transform="translate(6.667 4)" fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Report Center') }}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('earning_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('earning_payout_report.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Earning Report') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('in_house_product_sale_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('in_house_sale_report.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['in_house_sale_report.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('In House Product Sale') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('seller_products_sale_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller_sale_report.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['seller_sale_report.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Products Sale') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('products_stock_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('stock_report.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['stock_report.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Products Stock') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('product_wishlist_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('wish_report.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Products wishlist') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('user_search_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('user_search_report.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['user_search_report.index'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('User Searches') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('commission_history_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('commission-log.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Commission History') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('wallet_transaction_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('wallet-history.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Wallet Recharge History') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Content & Design')}}
                    </span>
                </li>

                @canany(['view_website_dashboard', 'header_setup', 'footer_setup', 'view_all_website_pages', 'website_appearance', 'authentication_layout_settings', 'top_banner_setting', 'view_top_banner'])
                    <li class="aiz-side-nav-item">
                        <a href="#"
                            class="aiz-side-nav-link {{ (areActiveRoutes(['website.footer', 'website.header', 'website.pages', 'website.appearance']) && request('id') != 'portfolio' && request('page') != 'portfolio') ? 'active' : '' }}">
                            <div class="aiz-side-nav-icon">
                                <svg id="Group_28315" data-name="Group 28315" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" viewBox="0 0 16 16">
                                    <circle id="Ellipse_893" data-name="Ellipse 893" cx="0.625" cy="0.625" r="0.625"
                                        transform="translate(7.375 6.125)" fill="{{ get_setting('navbar_text_color') }}" />
                                    <path id="Path_40777" data-name="Path 40777"
                                        d="M13.5,0H2.5A2.5,2.5,0,0,0,0,2.5V11a2.5,2.5,0,0,0,2.5,2.5H7.375v1.25H5.5A.625.625,0,0,0,5.5,16h5a.625.625,0,0,0,0-1.25H8.625V12.875A.625.625,0,0,0,8,12.25H2.5A1.251,1.251,0,0,1,1.25,11V2.5A1.251,1.251,0,0,1,2.5,1.25h11A1.251,1.251,0,0,1,14.75,2.5V11a1.251,1.251,0,0,1-1.25,1.25h-3a.625.625,0,0,0,0,1.25h3A2.5,2.5,0,0,0,16,11V2.5A2.5,2.5,0,0,0,13.5,0Z"
                                        fill="{{ get_setting('navbar_text_color') }}" />
                                    <path id="Path_40778" data-name="Path 40778"
                                        d="M120.375,84.75a.625.625,0,0,0,.625-.625v-.688a3.107,3.107,0,0,0,1.1-.456l.487.487a.625.625,0,0,0,.884-.884l-.487-.487a3.108,3.108,0,0,0,.456-1.1h.688a.625.625,0,1,0,0-1.25h-.688a3.108,3.108,0,0,0-.456-1.1l.487-.487a.625.625,0,0,0-.884-.884l-.487.487a3.107,3.107,0,0,0-1.1-.456v-.688a.625.625,0,0,0-1.25,0v.688a3.108,3.108,0,0,0-1.1.456l-.487-.487a.625.625,0,0,0-.884.884l.487.487a3.108,3.108,0,0,0-.456,1.1h-.688a.625.625,0,0,0,0,1.25h.688a3.108,3.108,0,0,0,.456,1.1l-.487.487a.625.625,0,0,0,.884.884l.487-.487a3.107,3.107,0,0,0,1.1.456v.688A.625.625,0,0,0,120.375,84.75ZM118.5,80.375a1.875,1.875,0,1,1,1.875,1.875A1.877,1.877,0,0,1,118.5,80.375Z"
                                        transform="translate(-112.375 -73.625)" fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Design Studio')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_website_dashboard')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.dashboard') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Design Studio Home')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('select_homepage')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-homepage') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.select-homepage'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Select Homepage')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('edit_website_page')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}"
                                        class="aiz-side-nav-link {{ (request('id') == 'home' || request('page') == 'home') ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Homepage Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('select_font_family')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-font-family') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.select-font-family'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Font Family')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('authentication_layout_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.authentication-layout-settings') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.authentication-layout-settings'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Authentication Layout & Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('select_header')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-header') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.select-header'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Select Header')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('header_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.header') }}"
                                        class="aiz-side-nav-link {{ (Route::is('website.header') && request('id') != 'portfolio') ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Header Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['view_top_banner', 'top_banner_setting'])
                                <li class="aiz-side-nav-item">
                                    <a href="#"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['top_banner.index', 'top_banner.setting', 'top_banner.create', 'top_banner.edit'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Top Bar')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('view_top_banner')
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link {{ areActiveRoutes(['top_banner.index', 'top_banner.create', 'top_banner.edit'])}}"
                                                    href="{{route('top_banner.index')}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Top Bar List')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('top_banner_setting')
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link {{ areActiveRoutes(['top_banner.setting'])}}"
                                                    href="{{route('top_banner.setting')}}">
                                                    <span class="aiz-side-nav-text"
                                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Top Bar Settings')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @can('select_all_category_layout')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('select_all_category_layout') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('All Category Layout')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('product_details_section')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('product_details_section') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Details')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('select_footer')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-footer') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.select-footer'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Select Footer')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('footer_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}"
                                        class="aiz-side-nav-link {{ (Route::is('website.footer') && request('id') != 'portfolio') ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Footer Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_website_pages')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.pages') }}"
                                        class="aiz-side-nav-link {{ (areActiveRoutes(['website.pages', 'custom-pages.create', 'custom-pages.edit']) && request('id') != 'portfolio' && request('page') != 'portfolio') ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Pages')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('system_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.system_setup') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('System Setup')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('website_appearance')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.appearance') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.appearance'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Appearance')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('logo_and_favicon')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.logo_and_favicon') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Logo & Favicon')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('banners_and_sliders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.banners_and_sliders') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Banners & Sliders')}}</span>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('show_element')
                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Element')}}</span>
                                    <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('elements.index') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text">{{translate('All Elements')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan --}}
                        </ul>
                    </li>
                @endcanany

                @canany(['manage_ai_configuration'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="16" height="16" viewBox="0 0 16 16">
                                    <defs>
                                        <clipPath id="clip-path">
                                            <path id="Path_3" data-name="Path 3" d="M0,0H16V16H0Z" fill="{{ get_setting('navbar_text_color') }}" />
                                        </clipPath>
                                    </defs>
                                    <g id="Group_1" data-name="Group 1" clip-path="url(#clip-path)">
                                        <path id="Path_1" data-name="Path 1"
                                            d="M47.948,55.953h-2.4a1.8,1.8,0,0,0-1.8,1.8V65.33a1.8,1.8,0,0,0,1.8,1.8h7.575a1.8,1.8,0,0,0,1.8-1.8V62.159l1.082-.742v3.913a2.886,2.886,0,0,1-2.885,2.885H45.552a2.886,2.886,0,0,1-2.885-2.885V57.756a2.885,2.885,0,0,1,2.885-2.886h3.03Z"
                                            transform="translate(-41.334 -53.155)" fill="{{ get_setting('navbar_text_color') }}" fill-rule="evenodd" />
                                        <path id="Path_2" data-name="Path 2"
                                            d="M256.718,0a.206.206,0,0,1,.2.164,4.667,4.667,0,0,0,1.267,2.323,4.667,4.667,0,0,0,2.323,1.267.2.2,0,0,1,0,.4,4.691,4.691,0,0,0-3.6,3.624.2.2,0,0,1-.384,0,4.691,4.691,0,0,0-3.631-3.631.2.2,0,0,1,0-.384,4.667,4.667,0,0,0,2.357-1.275A4.667,4.667,0,0,0,256.519.164a.205.205,0,0,1,.2-.164Z"
                                            transform="translate(-244.838 0)" fill="{{ get_setting('navbar_text_color') }}" fill-rule="evenodd" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('AI Studio') }}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('manage_ai_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('ai-add_edit_products') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Add/Edit Product With AI')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('ai-prompt-templates-config') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Prompt Templates')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('ai-token-usege') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Token Usage')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('ai-config') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('AI Configuration')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('view_uploaded_files')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('uploaded-files.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create'])}}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="layer1" transform="translate(-0.53 -0.53)">
                                        <path id="path3159"
                                            d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673ZM8.12,3.557,5.34,6.37a.572.572,0,0,0,0,.809.564.564,0,0,0,.81,0l1.8-1.824V10.8a.571.571,0,0,0,1.143,0V5.347l1.8,1.829a.571.571,0,0,0,.81-.806L8.935,3.557a.511.511,0,0,0-.815,0Zm-4.156,8.97a.571.571,0,0,0,0,1.143h9.128a.571.571,0,0,0,0-1.143Z"
                                            fill="{{ get_setting('navbar_text_color') }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Uploaded Files') }}</span>
                        </a>
                    </li>
                @endcan

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Communication')}}
                    </span>
                </li>

                @canany(['view_all_support_tickets', 'view_all_product_conversations', 'view_all_product_queries'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28286" data-name="Group 28286" transform="translate(0)">
                                        <path id="Path_40743" data-name="Path 40743"
                                            d="M16,9.125a3.122,3.122,0,0,0-1.255-2.5,6.9,6.9,0,0,0-1.94-4.6,6.725,6.725,0,0,0-9.61,0,6.9,6.9,0,0,0-1.94,4.6,3.124,3.124,0,0,0,1.87,5.627h1.25A.625.625,0,0,0,5,11.625v-5A.625.625,0,0,0,4.375,6H3.125a3.129,3.129,0,0,0-.569.052,5.487,5.487,0,0,1,10.887,0A3.129,3.129,0,0,0,12.875,6h-1.25A.625.625,0,0,0,11,6.625v5a.625.625,0,0,0,.625.625h.625v.625a1.877,1.877,0,0,1-1.875,1.875H8A.625.625,0,0,0,8,16h2.375A3.129,3.129,0,0,0,13.5,12.875v-.688A3.13,3.13,0,0,0,16,9.125ZM3.75,7.25V11H3.125a1.875,1.875,0,0,1,0-3.75ZM12.875,11H12.25V7.25h.625a1.875,1.875,0,1,1,0,3.75Z"
                                            fill="{{ get_setting('navbar_text_color') }}" />
                                        <path id="Path_40744" data-name="Path 40744"
                                            d="M197.875,113.25a.626.626,0,0,1,.625.625.618.618,0,0,1-.137.391,4.365,4.365,0,0,0-1.113,2.746v.613a.625.625,0,0,0,1.25,0v-.613a3.186,3.186,0,0,1,.838-1.964A1.875,1.875,0,1,0,196,113.875a.625.625,0,0,0,1.25,0A.626.626,0,0,1,197.875,113.25Z"
                                            transform="translate(-189.875 -108.5)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <circle id="Ellipse_891" data-name="Ellipse 891" cx="0.625" cy="0.625" r="0.625"
                                            transform="translate(7.375 11)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Support & Communication')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_support_tickets')
                                @php
                                    $support_ticket = DB::table('tickets')
                                        ->where('viewed', 0)
                                        ->select('id')
                                        ->count();
                                @endphp
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('support_ticket.admin_index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Ticket')}}</span>
                                        @if($support_ticket > 0)<span class="badge badge-info">{{ $support_ticket
                                        }}</span>@endif
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_product_conversations')
                                @php
                                    $conversation = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '0')->get();
                                @endphp
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('conversations.admin_index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['conversations.admin_index', 'conversations.admin_show'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Product Conversations')}}</span>
                                        @if (count($conversation) > 0)
                                            <span class="badge badge-info">{{ count($conversation) }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                            @if (get_setting('product_query_activation') == 1)
                                @can('view_all_product_queries')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('product_query.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['product_query.index', 'product_query.show']) }}">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Product Queries') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @can('view_seller_requests')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller_requests.index') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Seller Requests') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_contacts')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('contacts') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['contacts']) }}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Contacts') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Integrations')}}
                    </span>
                </li>

                @canany(['payment_methods_configurations', 'african_pg_configuration', 'african_pg_credentials_configuration', 'view_all_manual_payment_methods', 'view_all_offline_payment_orders', 'view_all_offline_wallet_recharges', 'view_all_offline_customer_package_payments', 'view_all_offline_seller_package_payments', 'asian_payment_gateway_configuration'])
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Payment_1_" transform="translate(0 -16)">
                                        <path id="Path_40758" data-name="Path 40758"
                                            d="M316.375,220h-2.5a.668.668,0,0,1,0-1.333h3.75a.668.668,0,0,0,0-1.333H315.75v-.667a.626.626,0,1,0-1.25,0v.667h-.625a2,2,0,0,0,0,4h2.5a.668.668,0,0,1,0,1.333h-3.75a.668.668,0,0,0,0,1.333H314.5v.667a.626.626,0,1,0,1.25,0V224h.625a2,2,0,0,0,0-4Z"
                                            transform="translate(-302.25 -193.333)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <g id="Group_28296" data-name="Group 28296" transform="translate(0 16)">
                                            <path id="Path_40759" data-name="Path 40759"
                                                d="M13.5,16H2.5A2.59,2.59,0,0,0,0,18.667v6.667A2.59,2.59,0,0,0,2.5,28H7.875a.668.668,0,0,0,0-1.333H2.5a1.3,1.3,0,0,1-1.25-1.333v-4h13.5V22A.626.626,0,1,0,16,22V18.667A2.59,2.59,0,0,0,13.5,16ZM1.25,20V18.667A1.3,1.3,0,0,1,2.5,17.333h11a1.3,1.3,0,0,1,1.25,1.333V20Z"
                                                transform="translate(0 -16)" fill="{{ get_setting('navbar_text_color') }}" />
                                            <path id="Path_40760" data-name="Path 40760"
                                                d="M80.625,256a.668.668,0,0,0,0,1.333H81.75a.668.668,0,0,0,0-1.333Z"
                                                transform="translate(-77.5 -248)" fill="{{ get_setting('navbar_text_color') }}" />
                                            <path id="Path_40761" data-name="Path 40761"
                                                d="M196.625,256a.668.668,0,0,0,0,1.333h1.125a.668.668,0,0,0,0-1.333Z"
                                                transform="translate(-189.875 -248)" fill="{{ get_setting('navbar_text_color') }}" />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Payment Gateways')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('payment_methods_configurations')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('payment_method.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Payment Methods')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (addon_is_activated('cybersource') && auth()->user()->can('cybersource_pg_configuration'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('cybersource_configuration')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Cybersource Payment Gateway')}}</span>
                                        @if (env("DEMO_MODE") == "On")
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                                viewBox="0 0 16 14.001" class="mx-2">
                                                <path id="Union_49" data-name="Union 49"
                                                    d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                                    transform="translate(19337 -3329)" fill="red" />
                                            </svg>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @if (addon_is_activated('paytm') && auth()->user()->can('asian_payment_gateway_configuration'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('paytm.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Asian Payment Gateway')}}</span>
                                        @if (env("DEMO_MODE") == "On")
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                                viewBox="0 0 16 14.001" class="mx-2">
                                                <path id="Union_49" data-name="Union 49"
                                                    d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                                    transform="translate(19337 -3329)" fill="red" />
                                            </svg>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @if(addon_is_activated('african_pg'))
                                @canany(['african_pg_configuration', 'african_pg_credentials_configuration'])
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('african_credentials.index')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('African Payment Gateway')}}</span>
                                            @if (env("DEMO_MODE") == "On")
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                                    viewBox="0 0 16 14.001" class="mx-2">
                                                    <path id="Union_49" data-name="Union 49"
                                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                                        transform="translate(19337 -3329)" fill="red" />
                                                </svg>
                                            @endif
                                        </a>
                                    </li>
                                @endcanany
                            @endif
                            @if (addon_is_activated('offline_payment'))
                                @canany(['view_all_manual_payment_methods', 'view_all_offline_payment_orders', 'view_all_offline_wallet_recharges', 'view_all_offline_customer_package_payments', 'view_all_offline_seller_package_payments', 'view_all_payout_method_for_customer'])
                                    <li class="aiz-side-nav-item">
                                        <a href="#" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Offline Payment System')}}</span>
                                            @if (env("DEMO_MODE") == "On")
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                                    viewBox="0 0 16 14.001" class="mx-2">
                                                    <path id="Union_49" data-name="Union 49"
                                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                                        transform="translate(19337 -3329)" fill="red" />
                                                </svg>
                                            @endif
                                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                        </a>
                                        <ul class="aiz-side-nav-list level-3">
                                            @can('view_all_manual_payment_methods')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('manual_payment_methods.index') }}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['manual_payment_methods.index', 'manual_payment_methods.create', 'manual_payment_methods.edit'])}}">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Manual Payment Methods')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view_all_offline_payment_orders')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('offline_payment_orders.index') }}" class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Offline Payment Orders')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view_all_offline_wallet_recharges')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('offline_wallet_recharge_request.index') }}"
                                                        class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Offline Wallet Recharge')}}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @if(get_setting('classified_product') == 1 && auth()->user()->can('view_all_offline_customer_package_payments'))
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('offline_customer_package_payment_request.index') }}"
                                                        class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Offline Customer Package
                                                                                                                                                                                                                                                                                                                                                                                                                            Payments')}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (addon_is_activated('seller_subscription') && auth()->user()->can('view_all_offline_seller_package_payments'))
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('offline_seller_package_payment_request.index') }}"
                                                        class="aiz-side-nav-link">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Offline Seller Package Payments')}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                            @if (addon_is_activated('refund_request') && auth()->user()->can('view_all_payout_method_for_customer'))
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('payout_method_for_customer_index') }}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['payout_method_for_customer_index', 'payout_method_for_customer_create', 'payout_method_for_customer_edit'])}}">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Offline Payout Methods')}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                @endcanany
                            @endif
                            @if (addon_is_activated('knet'))
                                @can('knet_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('knet.configuration')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Knet Payment Gateway')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @if (addon_is_activated('uddoktapay'))
                                @can('uddoktapay_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('uddoktapay.configuration')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Uddoktapay')}}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                        </ul>
                    </li>
                @endcanany

                @if (addon_is_activated('otp_system'))
                    @canany(['otp_configurations', 'sms_templates', 'sms_providers_configurations', 'send_bulk_sms'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path id="pin-code"
                                            d="M4.25,12.25a.625.625,0,0,1,.625.625h0a.625.625,0,1,1-1.25,0h0A.625.625,0,0,1,4.25,12.25Zm1.875.625h0a.625.625,0,1,0,1.25,0h0a.625.625,0,1,0-1.25,0Zm2.5,0h0a.625.625,0,1,0,1.25,0h0a.625.625,0,1,0-1.25,0Zm2.5,0h0a.625.625,0,1,0,1.25,0h0a.625.625,0,0,0-1.25,0Zm3-3.046a.625.625,0,0,0-.312,1.211,1.249,1.249,0,0,1,.937,1.211V13.5a1.251,1.251,0,0,1-1.25,1.25H2.5A1.251,1.251,0,0,1,1.25,13.5V12.25a1.257,1.257,0,0,1,.9-1.2.625.625,0,1,0-.354-1.2,2.518,2.518,0,0,0-1.284.888A2.478,2.478,0,0,0,0,12.25V13.5A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5V12.25A2.5,2.5,0,0,0,14.125,9.829Zm-10.562-.7V5.749A1.877,1.877,0,0,1,5.437,3.874h.124V2.387a2.438,2.438,0,0,1,4.875,0V3.874h.126a1.877,1.877,0,0,1,1.875,1.875V9.124A1.877,1.877,0,0,1,10.562,11H5.437A1.877,1.877,0,0,1,3.562,9.124Zm3.249-5.25H9.187V2.387a1.189,1.189,0,0,0-2.375,0V3.874Zm-2,5.25a.626.626,0,0,0,.625.625h5.125a.626.626,0,0,0,.625-.625V5.749a.626.626,0,0,0-.625-.625H5.437a.626.626,0,0,0-.625.625ZM8,8.125A.625.625,0,0,0,8.625,7.5h0a.625.625,0,0,0-1.25,0h0A.625.625,0,0,0,8,8.125Z"
                                            transform="translate(0)" fill="{{ get_setting('navbar_text_color') }}" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('OTP System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red" />
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('otp_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('otp.login_configuration') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('OTP System Settings')}}</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('otp.configconfiguration') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('OTP Configurations')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('sms_templates')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('sms-templates.index')}}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('SMS Templates')}}</span>
                                        </a>
                                    </li>
                                @endcan

                            </ul>
                        </li>
                    @endcanany
                @endif

                @if (addon_is_activated('gst_system'))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_1" data-name="Path 1"
                                        d="M104-848a2.507,2.507,0,0,1-.958-.187,2.451,2.451,0,0,1-.812-.542,3.083,3.083,0,0,0-.948-.687,3.092,3.092,0,0,0-1.177-.187,2.411,2.411,0,0,1-1.771-.729A2.411,2.411,0,0,1,97.6-852.1a3.108,3.108,0,0,0-.187-1.167,3.033,3.033,0,0,0-.687-.958,2.451,2.451,0,0,1-.542-.812A2.507,2.507,0,0,1,96-856a2.507,2.507,0,0,1,.188-.958,2.451,2.451,0,0,1,.542-.812,3.083,3.083,0,0,0,.688-.948A3.092,3.092,0,0,0,97.6-859.9a2.411,2.411,0,0,1,.729-1.771,2.411,2.411,0,0,1,1.771-.729,3.108,3.108,0,0,0,1.167-.187,3.033,3.033,0,0,0,.958-.687,2.451,2.451,0,0,1,.813-.542A2.507,2.507,0,0,1,104-864a2.507,2.507,0,0,1,.958.188,2.451,2.451,0,0,1,.813.542,3.083,3.083,0,0,0,.948.688,3.092,3.092,0,0,0,1.177.188,2.411,2.411,0,0,1,1.771.729,2.411,2.411,0,0,1,.729,1.771,3.108,3.108,0,0,0,.188,1.167,3.033,3.033,0,0,0,.688.958,2.451,2.451,0,0,1,.542.813A2.507,2.507,0,0,1,112-856a2.507,2.507,0,0,1-.187.958,2.451,2.451,0,0,1-.542.813,3.083,3.083,0,0,0-.687.948,3.092,3.092,0,0,0-.187,1.177,2.411,2.411,0,0,1-.729,1.771,2.411,2.411,0,0,1-1.771.729,3.108,3.108,0,0,0-1.167.188,3.033,3.033,0,0,0-.958.688,2.451,2.451,0,0,1-.812.542A2.507,2.507,0,0,1,104-848Zm0-1.5a1,1,0,0,0,.385-.073.985.985,0,0,0,.323-.219,4.243,4.243,0,0,1,1.438-1.021,4.709,4.709,0,0,1,1.75-.292.968.968,0,0,0,.713-.287.968.968,0,0,0,.287-.713,4.627,4.627,0,0,1,.292-1.74,4.484,4.484,0,0,1,1.021-1.448A.964.964,0,0,0,110.5-856a.964.964,0,0,0-.292-.708,4.484,4.484,0,0,1-1.021-1.448,4.627,4.627,0,0,1-.292-1.74.968.968,0,0,0-.287-.713.968.968,0,0,0-.713-.287,4.627,4.627,0,0,1-1.74-.292,4.485,4.485,0,0,1-1.448-1.021.985.985,0,0,0-.323-.219A1,1,0,0,0,104-862.5a1,1,0,0,0-.385.073.985.985,0,0,0-.323.219,4.243,4.243,0,0,1-1.437,1.021,4.709,4.709,0,0,1-1.75.292.968.968,0,0,0-.713.287.968.968,0,0,0-.287.713,4.627,4.627,0,0,1-.292,1.74,4.484,4.484,0,0,1-1.021,1.448A.964.964,0,0,0,97.5-856a.964.964,0,0,0,.292.708,4.484,4.484,0,0,1,1.021,1.448,4.627,4.627,0,0,1,.292,1.74.968.968,0,0,0,.287.713.968.968,0,0,0,.713.287,4.627,4.627,0,0,1,1.74.292,4.485,4.485,0,0,1,1.448,1.021.985.985,0,0,0,.323.219A1,1,0,0,0,104-849.5Zm2.25-3a1.205,1.205,0,0,0,.885-.365,1.206,1.206,0,0,0,.365-.885,1.206,1.206,0,0,0-.365-.885,1.205,1.205,0,0,0-.885-.365,1.205,1.205,0,0,0-.885.365,1.206,1.206,0,0,0-.365.885,1.206,1.206,0,0,0,.365.885A1.205,1.205,0,0,0,106.25-852.5Zm-4.25-.437,5-5L105.938-859l-5,5ZM101.75-857a1.205,1.205,0,0,0,.885-.365,1.206,1.206,0,0,0,.365-.885,1.206,1.206,0,0,0-.365-.885,1.205,1.205,0,0,0-.885-.365,1.205,1.205,0,0,0-.885.365,1.206,1.206,0,0,0-.365.885,1.206,1.206,0,0,0,.365.885A1.205,1.205,0,0,0,101.75-857ZM104-856Z"
                                        transform="translate(-96 864)" fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>

                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('GST System')}}</span>
                            @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                    class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="red" />
                                </svg>
                            @endif
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('gst.unverified.sellers') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text"
                                        style="color: {{ get_setting('navbar_text_color') }}">{{translate('Unverified Sellers')}}</span>
                                </a>
                            </li>
                            @can('gst_assign')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('products.hsn-gst.assigns') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('HSN Assign')}}</span>
                                    </a>
                                </li>
                                @if (addon_is_activated('wholesale'))
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.wholesale-hsn-gst.assigns') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Wholesale Products')}}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (addon_is_activated('preorder'))
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.preorder-hsn-gst.assigns') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Preorder Products')}}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (addon_is_activated('auction'))
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.auction-hsn-gst.assigns') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Auction Products')}}</span>
                                        </a>
                                    </li>
                                @endif
                            @endcan

                        </ul>
                    </li>
                @endif

                @if (addon_is_activated('shiprocket') || addon_is_activated('steadfast') || addon_is_activated('pathao') || addon_is_activated('redx'))
                    @canany(['manage_shipping_system'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="{{ get_setting('navbar_text_color') }}">
                                        <path
                                            d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm0-12H4v-.5L12 16l8-5V6z" />
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text"
                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                        class="mx-2">
                                        <path id="Union_49" data-name="Union 49"
                                            d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                            transform="translate(19337 -3329)" fill="red" />
                                    </svg>
                                @endif
                                <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                            </a>
                            @if (addon_is_activated('shiprocket'))
                                <ul class="aiz-side-nav-list level-2">
                                    @can('manage_shipping_system')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('shiprocket_configuration') }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['shiprocket_configuration'])}}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shiprocket')}}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            @endif
                            @if (addon_is_activated('steadfast'))
                                <ul class="aiz-side-nav-list level-2">
                                    @can('manage_shipping_system')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('steadfast_configuration') }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['steadfast_configuration'])}}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Steadfast')}}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            @endif
                            @if (addon_is_activated('pathao'))
                                <ul class="aiz-side-nav-list level-2">
                                    @can('manage_shipping_system')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('pathao_configuration') }}"
                                                class="aiz-side-nav-link {{ areActiveRoutes(['pathao_configuration'])}}">
                                                <span class="aiz-side-nav-text"
                                                    style="color: {{ get_setting('navbar_text_color') }}">{{translate('Pathao')}}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            @endif
                            @if (addon_is_activated('redx'))
                                <ul class="aiz-side-nav-list level-2">
                                    <li class="aiz-side-nav-item">
                                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text"
                                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Redx')}}</span>
                                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                        </a>
                                        <ul class="aiz-side-nav-list level-3">
                                            @can('manage_shipping_system')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('redx_configuration') }}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['redx_configuration'])}}">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Redx Configuration')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                        <ul class="aiz-side-nav-list level-3">
                                            @can('manage_redx_pickup_store')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('redx_pickup.index') }}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['redx_pickup.index'])}}">
                                                        <span class="aiz-side-nav-text"
                                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Redx Pickup Store')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endcanany
                @endif

                <li class="aiz-side-nav-item">
                    <span class="px-25px pt-10px text-uppercase fs-12 fw-400  d-block my-1 opacity-50"
                        style="color: {{ get_setting('navbar_text_color') }}">
                        {{translate('Settings')}}
                    </span>
                </li>

                @canany(['general_settings', 'features_activation', 'language_setup', 'currency_setup', 'vat_&_tax_setup', 'pickup_point_setup', 'smtp_settings', 'payment_methods_configurations', 'order_configuration', 'file_system_&_cache_configuration', 'social_media_logins', 'facebook_chat', 'facebook_comment', 'analytics_tools_configuration', 'google_recaptcha_configuration', 'google_map_setting', 'google_firebase_setting', 'shipping_configuration', 'shipping_country_setting', 'manage_shipping_states', 'manage_shipping_cities', 'manage_zones', 'manage_carriers', 'select_shipping_methods'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_40779" data-name="Path 40779"
                                        d="M7.688,16h.625a1.877,1.877,0,0,0,1.875-1.875V13.81a.209.209,0,0,1,.133-.191l.011,0a.209.209,0,0,1,.23.041l.223.223a1.875,1.875,0,0,0,2.652,0l.442-.442a1.875,1.875,0,0,0,0-2.652l-.223-.223a.209.209,0,0,1-.041-.23l0-.012a.209.209,0,0,1,.191-.133h.315A1.877,1.877,0,0,0,16,8.313V7.688a1.877,1.877,0,0,0-1.875-1.875H13.81a.209.209,0,0,1-.191-.133l0-.011a.209.209,0,0,1,.041-.23l.223-.223a1.875,1.875,0,0,0,0-2.652l-.442-.442a1.875,1.875,0,0,0-2.652,0l-.223.223a.21.21,0,0,1-.23.041l-.012,0a.209.209,0,0,1-.133-.191V1.875A1.877,1.877,0,0,0,8.312,0H7.687A1.877,1.877,0,0,0,5.812,1.875V2.19a.209.209,0,0,1-.133.191l-.012,0a.209.209,0,0,1-.23-.041l-.223-.223a1.875,1.875,0,0,0-2.652,0l-.442.442a1.875,1.875,0,0,0,0,2.652l.223.223a.209.209,0,0,1,.041.23l0,.011a.209.209,0,0,1-.191.133H1.875A1.877,1.877,0,0,0,0,7.687v.625a1.874,1.874,0,0,0,1.407,1.816.625.625,0,1,0,.312-1.211.624.624,0,0,1-.468-.605V7.688a.626.626,0,0,1,.625-.625H2.19a1.455,1.455,0,0,0,1.347-.906l0-.011a1.455,1.455,0,0,0-.312-1.591l-.223-.223a.625.625,0,0,1,0-.884l.442-.442a.625.625,0,0,1,.884,0l.223.223a1.456,1.456,0,0,0,1.593.311l.009,0A1.455,1.455,0,0,0,7.063,2.19V1.875a.626.626,0,0,1,.625-.625h.625a.626.626,0,0,1,.625.625V2.19a1.455,1.455,0,0,0,.906,1.347l.009,0a1.455,1.455,0,0,0,1.593-.311l.223-.223a.625.625,0,0,1,.884,0l.442.442a.625.625,0,0,1,0,.884l-.223.223a1.455,1.455,0,0,0-.311,1.593l0,.009a1.455,1.455,0,0,0,1.347.906h.315a.626.626,0,0,1,.625.625v.625a.626.626,0,0,1-.625.625H13.81a1.455,1.455,0,0,0-1.347.906l0,.009a1.455,1.455,0,0,0,.311,1.593l.223.223a.625.625,0,0,1,0,.884l-.442.442a.625.625,0,0,1-.884,0l-.223-.223a1.456,1.456,0,0,0-1.593-.311l-.009,0a1.455,1.455,0,0,0-.906,1.347v.315a.626.626,0,0,1-.625.625H7.688a.622.622,0,0,1-.6-.437.625.625,0,1,0-1.193.375A1.867,1.867,0,0,0,7.688,16ZM.536,15.433a1.829,1.829,0,0,1,0-2.586h0L4.589,8.811a3.234,3.234,0,0,1-.308-1.259,2.97,2.97,0,0,1,.9-2.141A4.228,4.228,0,0,1,8.13,4.255h.007a3.322,3.322,0,0,1,1.086.188A.625.625,0,0,1,9.47,5.473L7.964,7.01l.188.811L8.95,8,10.479,6.47a.625.625,0,0,1,1.034.24,3.472,3.472,0,0,1,.2,1.121,4.373,4.373,0,0,1-.8,2.556,3.047,3.047,0,0,1-2.49,1.3H8.417A3.414,3.414,0,0,1,7.159,11.4L3.122,15.433a1.829,1.829,0,0,1-2.586,0Zm6.876-5.311a2.1,2.1,0,0,0,1.007.316,1.818,1.818,0,0,0,1.487-.792,2.988,2.988,0,0,0,.528-1.361l-.843.845A.625.625,0,0,1,9.01,9.3L7.494,8.953a.625.625,0,0,1-.471-.468L6.669,6.959a.625.625,0,0,1,.162-.579l.823-.84A2.844,2.844,0,0,0,6.067,6.3,1.723,1.723,0,0,0,5.531,7.55a2.123,2.123,0,0,0,.342,1,.625.625,0,0,1-.065.809L1.419,13.731a.579.579,0,1,0,.819.818l4.368-4.361a.625.625,0,0,1,.806-.066Z"
                                        fill="{{ get_setting('navbar_text_color') }}" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text"
                                style="color: {{ get_setting('navbar_text_color') }}">{{translate('Setup & Configurations')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('business_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('business_settings.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['general.info', 'order.config', 'invoice.config', 'order_tracking.config', 'shipping_label.config'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Business Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('features_activation')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('activation.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Features activation')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('language_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('languages.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Languages')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('currency_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('currency.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('Currency')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('smtp_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('SMTP Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('file_system_&_cache_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('file_system.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text"
                                            style="color: {{ get_setting('navbar_text_color') }}">{{translate('File System & Cache Configuration')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('social_media_logins')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('social_login.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Social media Logins')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['facebook_chat', 'facebook_comment'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Facebook')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('whatsapp_chat')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('whatsapp_chat.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('WhatsApp Chat')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('facebook_comment')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('facebook-comment') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Facebook Comment')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['analytics_tools_configuration', 'google_recaptcha_configuration', 'google_map_setting', 'google_firebase_setting'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">

                                        @can('google_recaptcha_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google reCAPTCHA')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_map_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google-map.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google Map')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_firebase_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google-firebase.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Google Firebase')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['shipping_configuration', 'select_shipping_methods', 'shipping_country_setting', 'manage_shipping_states', 'manage_shipping_cities', 'manage_zones', 'manage_carriers', 'shipping_box_size_index', 'pickup_address_index'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping')}}</span>
                                        <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('select_shipping_methods')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('shipping_configuration.shipping_method')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.shipping_method'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Select Shipping Method')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('shipping_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('shipping_configuration.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Configuration')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('shipping_country_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('countries.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['countries.index', 'countries.edit', 'countries.update'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Countries')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_shipping_states')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('states.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['states.index', 'states.edit', 'states.update'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping States')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_shipping_cities')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('cities.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['cities.index', 'cities.edit', 'cities.update'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Cities')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_shipping_areas')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('areas.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['areas.index', 'areas.edit', 'areas.update'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Areas')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_zones')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('zones.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['zones.index', 'zones.create', 'zones.edit'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Zones')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_carriers')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('carriers.index')}}"
                                                    class="aiz-side-nav-link {{ areActiveRoutes(['carriers.index', 'carriers.create', 'carriers.edit'])}}">
                                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Carrier')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @if (addon_is_activated('shiprocket'))
                                            @can('pickup_address_index')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{ route('pickup_address.index') }}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['pickup_address.index', 'pickup_address.create', 'pickup_address.edit'])}}">
                                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Pickup Addresses')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('shipping_box_size_index')
                                                <li class="aiz-side-nav-item">
                                                    <a href="{{route('shipping_box_size.index')}}"
                                                        class="aiz-side-nav-link {{ areActiveRoutes(['shipping_box_size.index', 'shipping_box_size.create', 'shipping_box_size.edit'])}}">
                                                        <span
                                                            class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Shipping Box Size Configuration')}}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        @endif
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                @canany(['view_all_staffs', 'view_staff_roles'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28314" data-name="Group 28314" transform="translate(-19299 2175)">
                                        <path id="Path_40774" data-name="Path 40774"
                                            d="M87.867,3.07H84.133V1.72A.716.716,0,0,0,83.422,1H80.578a.716.716,0,0,0-.711.72V3.07H76.133A2.149,2.149,0,0,0,74,5.229V14.84A2.149,2.149,0,0,0,76.133,17H87.867A2.149,2.149,0,0,0,90,14.84V5.229A2.149,2.149,0,0,0,87.867,3.07Zm-6.578-.63h1.422V3.79a.711.711,0,1,1-1.422,0Zm7.289,12.4a.716.716,0,0,1-.711.72H76.133a.716.716,0,0,1-.711-.72V5.229a.716.716,0,0,1,.711-.72h3.856a2.124,2.124,0,0,0,4.022,0h3.856a.716.716,0,0,1,.711.72Z"
                                            transform="translate(19225 -2176)" fill="{{ get_setting('navbar_text_color') }}" />
                                        <g id="Group_28312" data-name="Group 28312"
                                            transform="translate(19305.07 -2169.197)">
                                            <path id="Path_40775" data-name="Path 40775"
                                                d="M199.864,197.932a1.932,1.932,0,1,0-1.932,1.932A1.934,1.934,0,0,0,199.864,197.932Zm-1.932.644a.644.644,0,1,1,.644-.644A.645.645,0,0,1,197.932,198.576Z"
                                                transform="translate(-196 -196)" fill="{{ get_setting('navbar_text_color') }}" />
                                        </g>
                                        <g id="Group_28313" data-name="Group 28313" transform="translate(19303.779 -2165)">
                                            <path id="Path_40776" data-name="Path 40776"
                                                d="M160.508,316h-2.576A1.934,1.934,0,0,0,156,317.932v1.288a.644.644,0,1,0,1.288,0v-1.288a.645.645,0,0,1,.644-.644h2.576a.645.645,0,0,1,.644.644v1.288a.644.644,0,1,0,1.288,0v-1.288A1.934,1.934,0,0,0,160.508,316Z"
                                                transform="translate(-156 -316)" fill="{{ get_setting('navbar_text_color') }}" />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Staffs')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_staffs')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('staffs.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('All staffs')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_staff_roles')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('roles.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Staff permissions')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['system_update', 'server_status'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28317" data-name="Group 28317" transform="translate(-19315.001 1976)">
                                        <g id="layer1" transform="translate(19314.471 -1976.53)">
                                            <path id="path3159"
                                                d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673Z"
                                                fill="{{ get_setting('navbar_text_color') }}" />
                                        </g>
                                        <g id="Group_28316" data-name="Group 28316"
                                            transform="translate(19317.551 -1973.449)">
                                            <g id="LWPOLYLINE" transform="translate(0 3.708)">
                                                <path id="Path_25666" data-name="Path 25666"
                                                    d="M194.061,143.129a.436.436,0,0,0,0,.873h1.527a.436.436,0,0,0,0-.873Z"
                                                    transform="translate(-193.625 -143.129)" fill="{{ get_setting('navbar_text_color') }}" />
                                            </g>
                                            <g id="LWPOLYLINE-2" data-name="LWPOLYLINE" transform="translate(3.663)">
                                                <path id="Path_25667" data-name="Path 25667"
                                                    d="M199.926,137.186a.436.436,0,0,1,.872,0v1.527a.436.436,0,0,1-.872,0Z"
                                                    transform="translate(-199.926 -136.75)" fill="{{ get_setting('navbar_text_color') }}" />
                                            </g>
                                            <g id="LWPOLYLINE-3" data-name="LWPOLYLINE" transform="translate(5.239 1.075)">
                                                <path id="Path_25668" data-name="Path 25668"
                                                    d="M204.463,139.345a.436.436,0,1,0-.617-.617l-1.079,1.079a.436.436,0,1,0,.617.617Z"
                                                    transform="translate(-202.638 -138.6)" fill="{{ get_setting('navbar_text_color') }}" />
                                            </g>
                                            <g id="LWPOLYLINE-4" data-name="LWPOLYLINE" transform="translate(1.097 1.075)">
                                                <path id="Path_25669" data-name="Path 25669"
                                                    d="M195.64,139.345a.436.436,0,1,1,.617-.617l1.079,1.079a.436.436,0,1,1-.617.617Z"
                                                    transform="translate(-195.512 -138.6)" fill="{{ get_setting('navbar_text_color') }}" />
                                            </g>
                                            <g id="LWPOLYLINE-5" data-name="LWPOLYLINE" transform="translate(1.097 5.261)">
                                                <path id="Path_25670" data-name="Path 25670"
                                                    d="M195.64,147.008a.436.436,0,0,0,.617.617l1.079-1.079a.436.436,0,1,0-.617-.617Z"
                                                    transform="translate(-195.512 -145.8)" fill="{{ get_setting('navbar_text_color') }}" />
                                            </g>
                                            <path id="Path_25671" data-name="Path 25671"
                                                d="M206.87,148.144,205,146.269l.864-.471a.436.436,0,0,0-.044-.786l-5.682-2.322a.436.436,0,0,0-.569.568l2.322,5.682a.436.436,0,0,0,.786.044l.471-.864,1.875,1.875a.437.437,0,0,0,.617,0l1.233-1.233A.437.437,0,0,0,206.87,148.144Zm-1.544.913-1.977-1.977a.436.436,0,0,0-.691.1l-.311.57-1.58-3.868,3.868,1.58-.57.311a.436.436,0,0,0-.174.591.467.467,0,0,0,.074.1l1.977,1.977Z"
                                                transform="translate(-196.099 -139.223)" fill="{{ get_setting('navbar_text_color') }}" />
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('System')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('system_update')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('system_update') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Update')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('server_status')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('system_server')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Server status')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('manage_addons')
                    <li class="aiz-side-nav-item">
                        <a href="{{route('addons.index')}}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['addons.index', 'addons.create'])}}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="_0339d7f72e6ebf7daea77d00de6c6177"
                                        data-name="0339d7f72e6ebf7daea77d00de6c6177"
                                        d="M12.2,17H9a.533.533,0,0,1-.533-.533v-1.6a1.067,1.067,0,1,0-2.133,0v1.6A.533.533,0,0,1,5.8,17H2.6A1.6,1.6,0,0,1,1,15.4V12.2a.533.533,0,0,1,.533-.533h1.6a1.067,1.067,0,1,0,0-2.133h-1.6A.533.533,0,0,1,1,9V5.8A1.6,1.6,0,0,1,2.6,4.2H5.267V3.133a2.133,2.133,0,1,1,4.267,0V4.2H12.2a1.6,1.6,0,0,1,1.6,1.6V8.467h1.067a2.133,2.133,0,1,1,0,4.267H13.8V15.4A1.6,1.6,0,0,1,12.2,17ZM9.533,15.933H12.2a.533.533,0,0,0,.533-.533V12.2a.533.533,0,0,1,.533-.533h1.6a1.067,1.067,0,1,0,0-2.133h-1.6A.533.533,0,0,1,12.733,9V5.8a.533.533,0,0,0-.533-.533H9a.533.533,0,0,1-.533-.533v-1.6a1.067,1.067,0,1,0-2.133,0v1.6a.533.533,0,0,1-.533.533H2.6a.533.533,0,0,0-.533.533V8.467H3.133a2.133,2.133,0,0,1,0,4.267H2.067V15.4a.533.533,0,0,0,.533.533H5.267V14.867a2.133,2.133,0,0,1,4.267,0Z"
                                        transform="translate(-1 -1)" fill="{{ get_setting('navbar_text_color') }}"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Addon Manager')}}</span>
                        </a>
                    </li>
                @endcan

                @if(addon_is_activated('portfolio_system'))
                    <li class="aiz-side-nav-item">
                        <a href="#"
                            class="aiz-side-nav-link {{ (request('id') == 'portfolio' || request('page') == 'portfolio' || Route::is('website.portfolioheader')) ? 'active' : '' }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="landing_portfolio" data-name="Path 1"
                                        d="M81.6-784a1.406,1.406,0,0,1-1.13-.588A2.191,2.191,0,0,1,80-786v-12a2.191,2.191,0,0,1,.47-1.412A1.406,1.406,0,0,1,81.6-800H94.4a1.406,1.406,0,0,1,1.13.588A2.191,2.191,0,0,1,96-798v12a2.191,2.191,0,0,1-.47,1.412A1.406,1.406,0,0,1,94.4-784Zm0-2H90v-3.5H81.6Zm10,0h2.8v-9H91.6Zm-10-5.5H90V-795H81.6Z"
                                        transform="translate(-80 800)" fill="{{ get_setting('navbar_text_color') }}"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Landing Page Setup')}}</span>
                            <span class="aiz-side-nav-arrow" style="color: {{ get_setting('navbar_text_color') }}"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.portfolioheader') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Header Nav')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('custom-pages.edit', ['id' => 'portfolio', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'portfolio']) }}"
                                    class="aiz-side-nav-link {{ (request('id') == 'portfolio' || request('page') == 'portfolio') ? 'active' : '' }}">
                                    <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Landing Page')}}</span>
                                </a>
                            </li>
                            @can('footer_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.footer', ['lang' => App::getLocale(), 'id' => 'portfolio']) }}"
                                        class="aiz-side-nav-link {{ (Route::is('website.footer') && request('id') == 'portfolio') ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text" style="color: {{ get_setting('navbar_text_color') }}">{{translate('Footer Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>