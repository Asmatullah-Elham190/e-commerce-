@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{ translate('Promotional Products') }}</h1>
            </div>

        </div>
    </div>

    <div class="card">

        <!--Nav Tab -->
         <div
            class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px table-nav-tabs pb-3 pb-xl-0">
            <div class="table-tabs-container flex-grow-1">
                <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                    @foreach ($product_types as $product_type)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                id="{{ Str::slug($product_type) }}-tab"
                                onclick="changeTab(this, '{{ Str::slug($product_type) }}')" role="tab"
                                aria-controls="{{ Str::slug($product_type) }}">
                                {{ translate($product_type) }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!--Right Side- Add New Button -->
            <div class="">
                @if (auth()->user()->can('add_promotional_products'))
                    <a href="javascript:void(0);" onclick="openRightcanvas()" class="position-relative overflow-hidden add-new-btn">
                        <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Promotional Products') }}</span>
                        <span class="position-absolute top-0 right-0 h-100 w-40px bg-blue d-flex align-items-center justify-content-end z-1 plus-icon-container m-0 p-0 rounded-pill">
                            <svg id="plus-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
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
            <form class="" id="sort_products" action="" method="GET">
                <div class="card-header row  border-0 pb-0 mt-2">
                    <div class="col pl-0 pl-md-3">
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
                                id="search_input" name="search" placeholder="Search products…">
                        </div>
                    </div>
                    
                    <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                        <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400" type="button"
                            data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('remove_from_promotional')
                            <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                href="javascript:void(0)" onclick="bulkDelete()">
                                {{ translate('Remove From Promotional') }}</a>
                            @endcan
                        </div>
                    </div>
                    <!--Filter-->
                    <div class="col-md-2 ml-auto mb-1 mb-md-0 px-0 px-md-1">
                        <div class="dropdown w-100">
                            <button
                                class="btn px-3  w-100 d-flex justify-content-between align-items-center dropdown-toggle"
                                type="button" id="filterMenu" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="text-secondary fs-14 fw-400">Filter</span>
                                <span class="dropdown-toggle-icon"></span>
                            </button>

                            <div class="dropdown-menu py-3 w-100" aria-labelledby="filterMenu">
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="all">
                                    <label class="form-check-label fs-14 px-2" for="all">{{ translate('All') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="all-discount">
                                    <label class="form-check-label fs-14 px-2" for="all-discount">{{ translate('All Discounted') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="low-stock">
                                    <label class="form-check-label fs-14 px-2" for="low-stock">{{ translate('Low Stock') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 ml-auto pr-0 pr-md-3 pl-0 inner-select ">
                        <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" name="type"
                            id="type" onchange="sort_products()">
                            <option value="" class="hov-text-light text-white fs-14 fw-400">{{ translate('Sort') }}</option>
                            <option value="rating,desc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif @endisset>
                                {{ translate('Rating (High > Low)') }}</option>
                            <option value="rating,asc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif @endisset>
                                {{ translate('Rating (Low > High)') }}</option>
                            <option value="unit_price,desc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'desc') selected @endif @endisset>
                                {{ translate('Base Price (High > Low)') }}</option>
                            <option value="unit_price,asc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'asc') selected @endif @endisset>
                                {{ translate('Base Price (Low > High)') }}</option>
                        </select>
                    </div>

                </div>
            

                <!-- Dynamic Tab Content -->
                <div class="tab-content filter-tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-content">
                        <!-- AJAX content will load here -->
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('modal')
     <!-- loading Modal -->
    @include('modals.loading_modal')

    <!-- Offcanvas -->
    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        
        <!-- content will here -->
         @include('backend.promotion_and_offers.product_select_right_canvas')

    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>

@endsection


@section('script')
    <script type="text/javascript">
        //Dynamic Tab Content Data
        let currentTab = '{{ Str::slug($product_types[0] ?? '') }}';
        let searchTimer;
        let page = 1 ;
        let selected_filter = [];
        let brand_id = '{{ $brand_id ?? '' }}';
        let category_id = '{{ $category_id ?? '' }}';

        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $(document).ready(function(){
        });

        function filterProductByCategory() {
            var searchKey = $('input[name=search_product_keyword]').val();
            var selectedCategory = $('select[name=selected_Products_category]').val();
            $.post('{{ route('promotional_products.search') }}', { _token: AIZ.data.csrf, product_id: null, search_key:searchKey, category:selectedCategory, product_type:"physical",single_select: 0 }, function(data){
                $('#products-list').html(data);
                AIZ.plugins.sectionFooTable('#products-list');
            });
        }

        function updateProductInPromotional() {
            let allProductIds = [];
            $('.product-select').each(function () {
                allProductIds.push($(this).val());
            });

            let checkedProductIds = [];
            $('.product-select:checked').each(function () {
                checkedProductIds.push($(this).val());
            });

            if (allProductIds.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate("No products found") }}');
                return;
            }

            $.ajax({
                url: "{{ route('promotional_products.update') }}",
                method: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    all_ids: allProductIds,
                    checked_ids: checkedProductIds
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', '{{ translate("Promotional products added successfully") }}');
                        getProducts(currentTab, page);
                        closeRightcanvas();
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Operation failed") }}');
                    }
                },
                error: function() {
                    closeRightcanvas();
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        }

        function single_remove(productId) {
            $.ajax({
                url: "{{ route('promotional_products.update') }}",
                method: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    all_ids: [productId],
                    checked_ids: []
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('danger', '{{ translate("Product removed from Promotional successfully") }}');
                        getProducts(currentTab);
                        hideBulkActionModal();
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Operation failed") }}');
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate("An error occurred during removal") }}');
                }
            });
        }

        function bulk_remove() {
            let selectedIds = [];
            $('.check-one:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate("Please select at least one item") }}');
                return;
            }

            $.ajax({
                url: "{{ route('promotional_products.update') }}",
                type: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    all_ids: selectedIds,
                    checked_ids: []
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('danger', '{{ translate("Selected products removed from Promotional") }}');
                        hideBulkActionModal();
                        getProducts(currentTab);
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Operation failed") }}');
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        }
        
        function singleDelete(productId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Confirm Product Removal') }}');
            $('#confirmation-question').text('{{ translate('Please note that once removed, this products will also be automatically removed from all associated promotions and offer sections. This action is non reversible.') }}');
            $('#impact-message').text('{{ translate('Are you sure you want to remove the selected product from the Promotional section !') }}');
            $('#conform-yes-btn').attr("onclick", "single_remove(" + productId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }
        
        function bulkDelete() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one item') }}');
                return;
            }
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Please note that once removed, these products will also be automatically removed from all associated promotions and offer sections. This action is non reversible.') }}');
            $('#impact-message').text('{{ translate('Are you sure you want to remove the selected products from the Promotional section !') }}');
            $('#conform-yes-btn').attr("onclick","bulk_remove()");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }
        
        function getProducts(slug, page = 1) {
            var type = $('#type').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('promotional_products.filter' ) }}?page=${page}`,
                method: 'GET',
                data: { type: type, product_type: slug, search: keyword, selected_filter:selected_filter, user_id: user_id, brand_id: brand_id, category_id: category_id },
                success: function(response) {
                    $('#tab-content').html(response.html);
                    initFooTable();

                },
                error: function() {
                    $('#tab-content').html(`
                        <div class="text-center py-2 my-4 w-100">
                            <h5 class="fs-16 fw-bold text-gray">{{ translate('Something Went Wrong') }}</h5>
                            <i class="las la-frown fs-48 text-soft-white"></i>
                        </div>
                    `);
                }
            });
        }

        function changeTab(button, statusSlug) {
            document.querySelectorAll('#myTab .nav-link').forEach(el => el.classList.remove('active'));
            button.classList.add('active');
            // Show or hide dropdown options for draft products
            if(statusSlug === 'drafts'){
                // Hide Publish, Featured, Todays Deal
                $('#bulk-publish-option, #bulk-featured-option, #bulk-td-option').hide();
            } else {
                // Show them for other tabs
                $('#bulk-publish-option, #bulk-featured-option, #bulk-td-option').show();
            }

            getProducts(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function() {
            getProducts(currentTab);
        });

        function sort_products(el){
            getProducts(currentTab, );
        }

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getProducts(currentTab);
            }, 500);
        });

        //Filter By stock,published,discount
        $('.input-check').on('change', function () {
            if (this.id === 'all') {
                if ($(this).is(':checked')) {
                    $('.input-check').prop('checked', true);
                } else {
                    $('.input-check').prop('checked', false);
                }
            } else {
                if (!$(this).is(':checked')) {
                    $('#all').prop('checked', false);
                }
            }
            selected_filter = [];

            $('.input-check:checked').each(function () {
                if (this.id !== 'all') { 
                    selected_filter.push(this.id);
                }
            });
            getProducts(currentTab);
        });



        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            getProducts(currentTab, page);
        });


        // Right Offcanvas JS Start
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');
            // Open function
            function openRightcanvas() {
                // content.textContent = data;
                rightOffcanvas.classList.add('active');
                overlay.classList.add('active');
                document.body.classList.add('body-no-scroll');
                 $('#rightOffcanvas .action-btn').text("{{ translate('Add') }}").attr('onclick', 'updateProductInPromotional()');
            }
            // Close function
            function closeRightcanvas() {
                rightOffcanvas.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('body-no-scroll');
                $('#products-list').html('');
                $('select[name=selected_Products_category]').val('');
                $('.right-offcanvas-body .filter-option-inner-inner').text('{{ translate('Choose Category') }}');
                 
            }
            function closeOffcanvas() {
                closeRightcanvas();
            }

            if (overlay) {
                overlay.addEventListener('click', closeRightcanvas);
            }
            // Optional: close with ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeRightcanvas();
            });
        // Right Offcanvas JS End

       
        //Table Nav Tabs Scroll Behavior
        document.addEventListener('DOMContentLoaded', () => {
            const tableTabsContainer = document.querySelector('.table-tabs-container');
            const tableTabs = tableTabsContainer.querySelectorAll('.nav-link');

            tableTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const offset = tab.offsetLeft - tableTabsContainer.clientWidth / 2 + tab
                        .clientWidth / 2;
                    tableTabsContainer.scrollTo({
                        left: offset,
                        behavior: "smooth"
                    });
                });
            });
        });

        function update_todays_deal(el){

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
