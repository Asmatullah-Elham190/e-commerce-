@if (get_setting('seller_can_add_custom_label') != 0)

    @extends('seller.layouts.app')

    @section('panel_content')
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Custom Label Information') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('seller.custom_label.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-from-label fs-13">{{translate('Text')}} <span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="text"
                                value="{{ old('text') }}" placeholder="{{ translate('Text') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="col-from-label">{{ translate('Background Color') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control aiz-color-input" placeholder="Ex: #e1e1e1"
                                    name="background_color" required>
                                <div class="input-group-append">
                                    <span class="input-group-text p-0">
                                        <input class="aiz-color-picker border-0 size-40px" type="color">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Select Text Color -->
                        <div class="form-group">
                            <label class="col-from-label fs-13">{{ translate('Select Text Color') }}</label>
                            <div class="d-flex align-items-center">
                                <!-- Light Option -->
                                <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                                    <input type="radio" name="text_color" value="white" checked>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
                                    </span>
                                </label>
                                <!-- Dark Option -->
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
                                    <input type="radio" name="text_color" value="dark">
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <label class="col-12 control-label" for="products">{{translate('Products')}}</label>
                            <div class="col-12">
                                <button type="button"
                                    class="bg-transparent d-block w-100 py-2 px-3 border border-dashed border-gray-400 rounded-1 d-flex align-items-center justify-content-center file-upload-input text-reset hov-text-blue"
                                    onclick='openRightcanvas("")'>
                                    <i class="las la-plus"></i>
                                    Add Product
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="form-group" id="custom_label_product_table">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('modal')

        <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white py-20px z-1045">

            <div class="border-bottom pb-15px px-30px">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="fs-16 fw-700 m-0">{{ translate('Select Products') }}</h5>
                    <button onclick="closeOffcanvas()" class="border-0 bg-transparent pr-0">
                        <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
                    </button>
                </div>
            </div>

            <div class="right-offcanvas-body position-absolute h-100 px-30px inventory-offcanvas-body">
                <div class="pb-5px">
                    <div class="row gutters-5 mt-3">
                        <div class="col-md-6">
                            <select class="form-control aiz-selectpicker" name="custom_label_category"
                                onchange="customLabelFilterProducts()" data-placeholder="{{ translate('Choose Category') }}"
                                data-live-search="true">
                                <option value="">{{ translate('Choose Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                    @foreach($category->childrenCategories as $childCategory)
                                        @include('categories.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-2 mt-md-0">
                            <input type="text" class="form-control" name="custom_label_search_keyword"
                                onkeyup="customLabelFilterProducts()" placeholder="{{ translate('Search by Product Name') }}">
                        </div>
                    </div>

                    <div class="mt-3" id="custom-label-products-list"></div>
                </div>
            </div>

            <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px">
                <div class="d-flex justify-content-end footer-btn">
                    <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel" onclick="closeRightcanvas()">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="button" class="d-block fs-14 fw-700 py-10px save"
                        onclick="addSelectedToCustomLabelProductTable()">
                        {{ translate('Add Selected') }}
                    </button>
                </div>
            </div>
        </div>
        <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
    @endsection

    @section('script')
        <script type="text/javascript">

            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');

            function openRightcanvas() {
                rightOffcanvas.classList.add('active');
                overlay.classList.add('active');
                document.body.classList.add('body-no-scroll');
                $('#custom-label-products-list').html('');
            }
            function closeRightcanvas() {
                rightOffcanvas.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('body-no-scroll');
                $('#custom-label-products-list').html('');
                $('select[name=custom_label_category]').val('').trigger('change');
                $('input[name=custom_label_search_keyword]').val('');
            }
            function closeOffcanvas() { closeRightcanvas(); }

            if (overlay) overlay.addEventListener('click', closeRightcanvas);
            document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRightcanvas(); });

            let customLabelSearchTimer;

            function customLabelFilterProducts() {
                clearTimeout(customLabelSearchTimer);
                customLabelSearchTimer = setTimeout(function () {
                    const category = $('select[name=custom_label_category]').val();
                    const searchKey = $('input[name=custom_label_search_keyword]').val();

                    $('#custom-label-products-list').html(
                        '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>'
                    );

                    $.post(
                        '{{ route('seller.custom_labels.product_search') }}',
                        {
                            _token: '{{ csrf_token() }}',
                            category: category,
                            search_key: searchKey
                        },
                        function (data) {
                            $('#custom-label-products-list').html(data);
                            addedProductIds.forEach(function (id) {
                                $('.custom-label-product-check[data-product-id="' + id + '"]').prop('checked', true);
                            });
                        }
                    );
                }, 400);
            }

            let addedProductIds = new Set();

            function addSelectedToCustomLabelProductTable() {
                const newIds = [];

                $('.custom-label-product-check:checked').each(function () {
                    const id = $(this).attr('data-product-id');
                    if (!addedProductIds.has(id)) {
                        addedProductIds.add(id);
                        newIds.push(id);
                    }
                });

                if (newIds.length > 0) {
                    $.post(
                        '{{ route('seller.custom_labels.product_add') }}',
                        { _token: '{{ csrf_token() }}', product_ids: newIds },
                        function (data) {
                            const existing = $('#custom_label_product_table table');
                            if (existing.length === 0) {
                                $('#custom_label_product_table').html(data);
                            } else {
                                const newRows = $(data).find('tbody tr');
                                existing.find('tbody').append(newRows);
                            }
                            if (typeof AIZ !== 'undefined' && AIZ.plugins.bootstrapSelect) {
                                AIZ.plugins.bootstrapSelect();
                            }
                        }
                    );
                }

                closeRightcanvas();
            }

            initFooTable();

            function removeCustomLabelProductRow(productId) {
                $('#custom-label-product-row-' + productId).remove();
                addedProductIds.delete(String(productId));
                if ($('#custom_label_product_table tbody tr').length === 0) {
                    $('#custom_label_product_table').html('');
                }
            }

        </script>
    @endsection
@endif