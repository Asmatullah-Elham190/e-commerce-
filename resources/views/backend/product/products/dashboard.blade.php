@extends('backend.layouts.app')

@section('content')
    <div class="product-managment-wrapper pt-4 pb-4">
        <div class="col-12 mx-auto">
            <h1 class="fs-18 fw-bold mb-0 mt-2 pt-1 pb-1">{{ translate('Product Management') }}</h1>
            <span class="fs-12 fw-400">{{ translate('Create, setup and manage all your products') }}</span>

            <div class="row gutters-12 mt-3 pt-2">
                <!-- PRODUCTS -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mb-3">{{ translate('PRODUCTS') }} </h5>
                </div>

                @can('show_all_products')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('products.all')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/all-product.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('All Products') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('View, search and manage your entire product line.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                <!-- Single -->
                @can('show_in_house_products')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('products.admin')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/inhouse-product.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('In-house Products') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Manage products of your own.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @if(get_setting('vendor_system_activation') == 1)
                    @can('show_seller_products')
                        <div class="col-md-6 col-lg-6 col-xl-4">
                            <a href="{{ route('products.seller','all') }}" class="d-block">
                                <div
                                    class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <img src="{{ static_asset('assets/img/product-management/seller-product.svg') }}"
                                            class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                        <div class="ml-3 flex-grow-1">
                                            <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Seller Products') }} </h6>
                                            <span
                                                class="fs-12 fw-400 text-gray">{{ translate('Oversee items listed by your sellers.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                @endif

                @can('add_new_product')
                    <div class="col-md-6 col-lg-6 col-xl-8">
                        <a href="{{route('products.create')}}" class="d-block">
                            <div
                                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 bg-soft-blue overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start">
                                    <img src="{{ static_asset('assets/img/product-management/add-new.svg') }}"
                                        class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                    <div class="ml-3 flex-grow-1">
                                        <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                            {{ translate('Add New Physical Products') }} </h6>
                                        <span
                                            class="fs-12 fw-400 text-gray">{{ translate('Create listings for physical products that require shipping.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan

                @can('add_digital_product')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('digitalproducts.create') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/add-new.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                        {{ translate('Add New Digital Products') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Create downloadable products for instant delivery.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                <!-- PRODUCT Setup -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mt-3 mb-3">{{ translate('PRODUCT Setup') }} </h5>
                </div>

                @can('view_product_categories')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('categories.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/category.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Category') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Organize your products into hierarchical groups.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_all_brands')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('brands.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/brand.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Brand') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Define and manage manufacturer or brand names.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_measurement_points')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('unit.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/units.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Units') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Specify unit standards.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_colors')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('colors')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/colors.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Colors') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Create or enable color variations') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_product_attributes')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('attributes.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/attributes.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Attributes') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Define custom product properties.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_size_charts')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{ route('size-charts.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/size-guide.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Size Guide') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Build measurement charts to help customers.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_product_warranties')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('warranties.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/warranty.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Warranty') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Set up protection plans and guarantee terms.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_notes')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('note.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/notes.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Notes') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Add preset instructions for customers to show in website..') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan


                <!-- PRODUCT operation -->
                <div class="col-12">
                    <h5 class="fs-14 fw-bold text-dark text-uppercase mt-3 mb-3">{{ translate('PRODUCT operation') }}
                    </h5>
                </div>

                @can('view_product_reviews')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('reviews.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/product-reviews.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">{{ translate('Product Reviews') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Manage customer ratings & feedbacks.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('smart-bar')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('smart.bar')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/smart-bar.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                        {{ translate('Smart Bar') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Configure product details bar across description page.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('view_custom_label')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('custom_label.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/custom-level.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                        {{ translate('Custom Label') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Design and assign special badges to product box.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('product_bulk_import')
                <div class="col-md-6 col-lg-6 col-xl-8">
                    <a href="{{ route('product_bulk_upload.index') }}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/bulk-import.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                        {{ translate('Bulk Import') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Upload a bulk of products simultaneously using spreadsheet files.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endcan

                @can('product_bulk_export')
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <a href="{{route('product_bulk_export.index')}}" class="d-block">
                        <div
                            class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start">
                                <img src="{{ static_asset('assets/img/product-management/bulk-export.svg') }}"
                                    class="flex-shrink-0 w-50px h-50px" alt="Icon">
                                <div class="ml-3 flex-grow-1">
                                    <h6 class="fs-16 fw-semibold mb-2 text-dark">
                                        {{ translate('Bulk Export') }} </h6>
                                    <span
                                        class="fs-12 fw-400 text-gray">{{ translate('Download your product database in .csv .') }}</span>
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
