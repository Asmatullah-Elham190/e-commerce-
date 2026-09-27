@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-10 col-sm-10 col-lg-10 mx-auto">
            <div class="aiz-titlebar text-left pb-5px">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h1 class="h3 fw-bold">{{ translate('All Size Charts') }}</h1>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-0 row">
                        <div class="d-flex align-items-center mt-3 mb-2 ml-3">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" onchange="updateSettings(this, 'seller_can_add_size_chart')" @if(get_setting('seller_can_add_size_chart')) checked @endif>
                                <span></span>
                            </label>
                            <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Seller Can Add Size Chart') }}?</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div
                    class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px">
                    <div class="table-tabs-container">
                        <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                            @foreach ($sizeChart_tabs as $sizeChart_tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                        data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        id="{{ Str::slug($sizeChart_tab) }}-tab"
                                        onclick="changeTab(this, '{{ Str::slug($sizeChart_tab) }}')" role="tab"
                                        aria-controls="{{ Str::slug($sizeChart_tab) }}">
                                        {{ translate($sizeChart_tab) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="">
                        @if (auth()->user()->can('add_size_charts'))
                            <a href="{{ route('size-charts.create') }}" id=""
                                class="position-relative overflow-hidden add-new-btn">
                                <span
                                    class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Size Chart') }}</span>
                                <span
                                    class="position-absolute top-0 right-0 h-100 w-40px bg-blue d-flex align-items-center justify-content-end z-1 plus-icon-container m-0 p-0 rounded-pill">
                                    <svg id="plus-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <path id="Path_45216" data-name="Path 45216"
                                            d="M141.874-812.13a.706.706,0,0,1-.515-.21.7.7,0,0,1-.212-.514V-817.4h-4.553a.7.7,0,0,1-.514-.209.694.694,0,0,1-.21-.511.706.706,0,0,1,.21-.515.7.7,0,0,1,.514-.212h4.549v-4.557a.7.7,0,0,1,.209-.514.694.694,0,0,1,.511-.21.706.706,0,0,1,.515.21.7.7,0,0,1,.212.514v4.553h4.557a.7.7,0,0,1,.514.208.694.694,0,0,1,.21.511.706.706,0,0,1-.21.515.7.7,0,0,1-.514.212h-4.553v4.553a.7.7,0,0,1-.209.514A.694.694,0,0,1,141.874-812.13Z"
                                            transform="translate(-135.87 824.13)" fill="#fff" />
                                    </svg>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="tab-filter-bar">
                    <form class="" id="sort_sizeCharts" action="" method="GET">
                        <div class="card-header border-0 pb-0 mt-2">
                            <div class="flex-grow-1 mr-2">
                                <div class="input-group mb-0 border border-light px-3 bg-light rounded-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0 bg-transparent px-0" id="search">
                                            <svg id="Group_38844" data-name="Group 38844" xmlns="http://www.w3.org/2000/svg"
                                                width="16.001" height="16" viewBox="0 0 16.001 16">
                                                <path id="Path_3090" data-name="Path 3090"
                                                    d="M8.248,14.642a6.394,6.394,0,1,1,6.394-6.394A6.4,6.4,0,0,1,8.248,14.642Zm0-11.509a5.115,5.115,0,1,0,5.115,5.115A5.121,5.121,0,0,0,8.248,3.133Z"
                                                    transform="translate(-1.854 -1.854)" fill="#a5a5b8" />
                                                <path id="Path_3091" data-name="Path 3091"
                                                    d="M23.011,23.651a.637.637,0,0,1-.452-.187l-4.92-4.92a.639.639,0,0,1,.9-.9l4.92,4.92a.639.639,0,0,1-.452,1.091Z"
                                                    transform="translate(-7.651 -7.651)" fill="#a5a5b8" />
                                            </svg>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm border-0 px-2 bg-transparent"
                                        id="search_input" name="search"
                                        placeholder="{{translate('Search Size Charts ...')}}">
                                </div>
                            </div>

                            <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 rounded-1">
                                <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400"
                                    type="button" data-toggle="dropdown">
                                    {{ translate('Bulk Action') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @can('delete_size_charts')
                                        <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                            href="javascript:void(0)" onclick="bulkDeleted()">
                                            {{ translate('Delete') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="tab-content filter-tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab-content">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.size_chart_show_modal')

    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        @include('backend.product.sizeCharts.product_select_right_canvas')
    </div>
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = '{{ Str::slug($sizeChart_tabs[0]) }}';
        var searchTimer;

        $(document).on("change", ".check-all", function () {
            if (this.checked) {
                $('.check-one:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function () {
                    this.checked = false;
                });
            }

        });
        function sort_sizeCharts(el) {
            $('#sort_sizeCharts').submit();
        }

        function single_delete(sizeChartId) {
            $.ajax({
                url: "{{ route('size-charts.destroy', ':id') }}".replace(':id', sizeChartId),
                type: 'GET',
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getSizeCharts(currentTab);
                    }
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_sizeCharts')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-size-chart-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', 'Selected size charts Deleted successfully');
                        hideBulkActionModal();
                        getSizeCharts(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function bulkDeleted() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one size chart') }}');
                return;
            }

            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected size charts?') }}');
            $('#conform-yes-btn').attr("onclick", "bulk_delete()");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');

        }

        function singleDelete(sizeChartId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected size chart?') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + sizeChartId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
        }

        function getSizeCharts(slug, page = 1) {
            var status = $('#status').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('size_charts.filter') }}?page=${page}`,
                method: 'GET',
                data: { status: status, sizeChart_status: slug, search: keyword },
                success: function (response) {
                    $('#tab-content').html(response.html);
                    initFooTable();
                },
                error: function () {
                    $('#tab-content').html('<div class="text-danger p-4">{{ translate("Failed to load data.") }}</div>');
                }
            });
        }

        function changeTab(button, statusSlug) {
            document.querySelectorAll('#myTab .nav-link').forEach(el => el.classList.remove('active'));
            button.classList.add('active');
            getSizeCharts(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getSizeCharts(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getSizeCharts(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getSizeCharts(currentTab, page);
        });

        function showSizeChartDetail(id, name) {
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            $.ajax({
                type: "GET",
                url: "{{ route('size-charts.show', '') }}/" + id,
                data: {},
                success: function (data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }

        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');
        let savedCategoryId = null;
        let selectedProductIds = new Set();
        let currentWholeCategoryMode = false;
        let isViewMode = false;
        let productSearchTimer;

        function setOffcanvasMode(viewMode) {
            isViewMode = viewMode;

            $('select[name=selected_products_category]').prop('disabled', viewMode);
            $('input[name=search_product_keyword]').prop('disabled', viewMode);
            $('#assign_whole_category').prop('disabled', viewMode);
            $('select[name=selected_products_category]').selectpicker('refresh');

            if (viewMode) {
                $('#offcanvas-title').text("{{ translate('Assigned Category/Products') }}");
                $('#offcanvas-btn').addClass('d-none');
            } else {
                $('#offcanvas-title').text("{{ translate('Assign Catgeory/Product') }}");
                $('#offcanvas-btn').removeClass('d-none');

                $('input[name=search_product_keyword]').prop('disabled', false);
            }
        }

        function openRightcanvas(sizeChartId, categoryId, productIds) {
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            $('#assign_size_chart_id').val(sizeChartId);
            $('#rightOffcanvas .action-btn').text("{{ translate('Assign') }}").attr('onclick', 'assignCategoryOrProducts()');

            savedCategoryId = categoryId ? String(categoryId) : null;
            selectedProductIds = new Set((productIds || []).map(String));
            currentWholeCategoryMode = false;

            $('select[name=selected_products_category]').selectpicker('val', '');
            $('#assign_whole_category').prop('checked', false);
            $('input[name=search_product_keyword]').val('');
            $('#products-list').html('<div class="text-secondary fs-13 py-2">{{ translate("Please choose a category or search a product to see the list") }}</div>');

            setOffcanvasMode(false);
        }

        function openViewAssignedCanvas(sizeChartId, categoryId, productIds) {
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            $('#assign_size_chart_id').val(sizeChartId);

            savedCategoryId = categoryId ? String(categoryId) : null;
            selectedProductIds = new Set((productIds || []).map(String));
            $('input[name=search_product_keyword]').val('');
            $('#products-list').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');

            if (savedCategoryId) {
                $('select[name=selected_products_category]').selectpicker('val', savedCategoryId);
                $('#assign_whole_category').prop('checked', true);
                currentWholeCategoryMode = true;
                loadProducts(savedCategoryId, '', true);
            } else {
                $('select[name=selected_products_category]').selectpicker('val', '');
                $('#assign_whole_category').prop('checked', false);
                currentWholeCategoryMode = false;
                loadSpecificProductsReadonly(Array.from(selectedProductIds));
            }

            setOffcanvasMode(true);
        }

        function loadProducts(categoryId, searchKey, wholeCategoryMode) {
            currentWholeCategoryMode = wholeCategoryMode;
            $.post('{{ route('sizeChart_products.search') }}', {
                _token: AIZ.data.csrf,
                product_id: null,
                search_key: searchKey || '',
                category: categoryId || '',
                product_type: "physical",
                single_select: 0,
                readonly: wholeCategoryMode ? 1 : 0
            }, function (data) {
                $('#products-list').html(data);
                AIZ.plugins.sectionFooTable('#products-list');
                applyCheckedStateSync();
            });
        }

        function loadSpecificProductsReadonly(productIds) {
            if (!productIds || productIds.length === 0) {
                $('#products-list').html('<div class="text-secondary fs-13 py-2">{{ translate("No products assigned") }}</div>');
                return;
            }

            $.post('{{ route('sizeChart_products.search') }}', {
                _token: AIZ.data.csrf,
                product_id: null,
                search_key: '',
                category: '',
                selected_product_ids: productIds,
                product_type: "physical",
                single_select: 0,
                readonly: 1
            }, function (data) {
                $('#products-list').html(data);
                AIZ.plugins.sectionFooTable('#products-list');
            });
        }

        const productsListEl = document.getElementById('products-list');
        const productsListObserver = new MutationObserver(function () {
            applyCheckedStateSync();
        });
        if (productsListEl) {
            productsListObserver.observe(productsListEl, { childList: true, subtree: true });
        }

        function applyCheckedStateSync() {
            $('.product-select').each(function () {
                var id = String($(this).val());
                if (currentWholeCategoryMode || isViewMode) {
                    $(this).prop('checked', currentWholeCategoryMode ? true : selectedProductIds.has(id)).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false).prop('checked', selectedProductIds.has(id));
                }
            });
        }

        $(document).on('change', '.product-select', function () {
            if (isViewMode) return;
            var id = String($(this).val());
            if (this.checked) {
                selectedProductIds.add(id);
            } else {
                selectedProductIds.delete(id);
            }
        });

        function closeRightcanvas() {
            rightOffcanvas.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
            $('#products-list').html('');
            $('select[name=selected_products_category]').selectpicker('val', '');
            $('.right-offcanvas-body .filter-option-inner-inner').text('{{ translate('Choose Category') }}');
            $('#assign_whole_category').prop('checked', false);
            $('input[name=search_product_keyword]').val('');
            savedCategoryId = null;
            selectedProductIds = new Set();
            currentWholeCategoryMode = false;

            setOffcanvasMode(false);
        }

        function closeOffcanvas() {
            closeRightcanvas();
        }

        if (overlay) {
            overlay.addEventListener('click', closeRightcanvas);
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeRightcanvas();
        });

        function toggleCategoryWideAssign(checkbox) {
            if (isViewMode) return;
            var selectedCategory = $('select[name=selected_products_category]').val();
            if (checkbox.checked) {
                $('input[name=search_product_keyword]').prop('disabled', true).val('');
                if (selectedCategory) {
                    loadProducts(selectedCategory, '', true);
                } else {
                    $('#products-list').html('<div class="text-secondary fs-13 py-2">{{ translate("Please choose a category first") }}</div>');
                }
            } else {
                $('input[name=search_product_keyword]').prop('disabled', false);
                if (selectedCategory) {
                    loadProducts(selectedCategory, '', false);
                } else {
                    $('#products-list').html('');
                }
            }
        }

        function resetAssignStateAndFilter() {
            if (isViewMode) return;
            var selectedCategory = $('select[name=selected_products_category]').val();

            if (selectedCategory && savedCategoryId && String(selectedCategory) === savedCategoryId) {
                $('#assign_whole_category').prop('checked', true);
                $('input[name=search_product_keyword]').prop('disabled', true).val('');
                loadProducts(selectedCategory, '', true);
                return;
            }

            $('#assign_whole_category').prop('checked', false);
            $('input[name=search_product_keyword]').prop('disabled', false);
            filterProductByCategory();
        }

        function filterProductByCategory() {
            if (isViewMode) return;
            clearTimeout(productSearchTimer);
            productSearchTimer = setTimeout(function () {
                var searchKey = $('input[name=search_product_keyword]').val();
                var selectedCategory = $('select[name=selected_products_category]').val();

                if (!searchKey && !selectedCategory) {
                    $('#products-list').html('<div class="text-secondary fs-13 py-2">{{ translate("Please choose a category or search a product to see the list") }}</div>');
                    return;
                }

                var wholeCategoryMode = $('#assign_whole_category').is(':checked');
                loadProducts(selectedCategory, searchKey, wholeCategoryMode);
            }, 400);
        }

        function assignCategoryOrProducts() {
            var sizeChartId = $('#assign_size_chart_id').val();
            var wholeCategory = $('#assign_whole_category').is(':checked');
            var selectedCategory = $('select[name=selected_products_category]').val();

            $.ajax({
                url: "{{ route('assignCategoryOrProducts.update') }}",
                method: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    size_chart_id: sizeChartId,
                    whole_category: wholeCategory ? 1 : 0,
                    category_id: wholeCategory ? (selectedCategory || null) : null,
                    checked_ids: wholeCategory ? [] : Array.from(selectedProductIds)
                },
                success: function (response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', '{{ translate("Assigned successfully") }}');
                        closeRightcanvas();
                        getSizeCharts(currentTab);
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Operation failed") }}');
                    }
                },
                error: function () {
                    closeRightcanvas();
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        }

        function updateSettings(el, type) {
            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            var value = ($(el).is(':checked')) ? 1 : 0;
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

        function updateSellerAccess (el){
            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            var isCanAccess = el.checked ? 1 : 0;
            $.post('{{ route('sizechart.update-seller-access') }}', {
                _token      :   '{{ csrf_token() }}',
                id          :   el.value,
                status      :   isCanAccess
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Admin size chart seller access status update successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection