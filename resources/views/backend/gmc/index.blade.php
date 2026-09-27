@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{ translate('All GMC products') }}</h1>
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
                <a class="btn btn-soft-info   btn-sm"  onclick="pushToGMC(true)" href="javascript:void(0)" title="{{ translate('Push All Products to Google Merchant Center') }}">
                    <i class="las la-sync"></i>
                    
                    <span>{{ translate('Sync All Products') }}</span>
                </a>
                <a class="btn btn-soft-success btn-sm" onclick="exportToGMC('xml', true)" href="javascript:void(0)" title="{{ translate('Export All Products XML ') }}">
                    <i class="las la-download"></i>
                    <span>{{ translate('Export All Products XML') }}</span>
                </a>
                @if ($seller_type != 'seller' && auth()->user()->can('add_gmc_product'))
                    <a href="javascript:void(0);" onclick="openRightcanvas()" class="position-relative overflow-hidden add-new-btn">
                        <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Import Product') }}</span>
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
                            
                            <!-- Google Merchant Center Export Options -->
                            <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" onclick="exportToGMC('xml')" href="javascript:void(0)">
                                <i class="las la-file-code"></i> {{ translate('Export as XML') }}
                            </a>
                            <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" onclick="exportToGMC('csv')" href="javascript:void(0)">
                                <i class="las la-file-csv"></i> {{ translate('Export as CSV') }}
                            </a>
                            <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" onclick="pushToGMC()" href="javascript:void(0)">
                                <i class="las la-file-code"></i> {{ translate('Push/Sync to Google') }}
                            </a>

                             <a class="dropdown-item text-danger fs-14 fw-500 hov-bg-light hov-text-blue" onclick="bulkRemoveFromGmc()" href="javascript:void(0)" title="{{ translate('Remove selected products from Google Merchant Center') }}">
                                <i class="las la-trash"></i> {{ translate('Remove from GMC') }}
                            </a>
                        </div>
                    </div>
                    @if($seller_type == 'seller')
                    <div class="col-md-2 mr-0 px-0 inner-select ml-1">
                        <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" id="user_id" name="user_id" onchange="sort_products()">
                            <option value="" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('All Sellers') }}</option>
                            @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $key => $seller)
                                <option class="hov-bg-light text-secondary fs-14 fw-40" value="{{ $seller->id }}">
                                    {{ $seller->shop?->name }} ({{ $seller->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
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
                                    <label class="form-check-label fs-14 px-2" for="all">All</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="all-publish">
                                    <label class="form-check-label fs-14 px-2" for="all-publish">All Published</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="all-discount">
                                    <label class="form-check-label fs-14 px-2" for="all-discount">All
                                        Discounted</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="low-stock">
                                    <label class="form-check-label fs-14 px-2" for="low-stock">Low Stock</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="refundable">
                                    <label class="form-check-label fs-14 px-2" for="refundable">Refundable</label>
                                </div>
                            </div>
                        </div>
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
         @include('offcanvas.products_select_right_canvas')

    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>

    <!-- Google Merchant Center Export Modal -->
    <div class="modal fade" id="gmcExportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Google Merchant Center Export') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="gmcExportModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="sr-only">{{ translate('Loading...') }}</span>
                        </div>
                        <p>{{ translate('Preparing export...') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script type="text/javascript">
        //Dynamic Tab Content Data
        let currentTab = '{{ Str::slug($product_types[0] ?? '') }}';
        let searchTimer;
        let seller_type = '{{ $seller_type }}';
        let selected_filter = [];

        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        
        $(document).on("change", ".check-all-products", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one-product:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one-product:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });


        function push_to_gmc() {
            $('#confirmation-question').html('<div class="footable-loader h-50 pt-4"><span class="fooicon fooicon-loader"></span></div>');
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('google_merchant_center.google.sync')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.success) {
                        AIZ.plugins.notify('success', '{{ translate('Selected products have been pushed to Google Merchant Center.') }}');
                        hideBulkActionModal();
                        getProducts(currentTab);
                    }
                }
            });
        }

        

        function pushToGMC( isBulk = false) {
            if ($('.check-one:checked').length == 0 && !isBulk) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one item') }}');
                return;
            }
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Push to GMC Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to push the selected products to Google Merchant Center?') }}');
            $('#impact-message').text('{{ translate('Products already pushed to GMC will be updated.') }}');
            $('#conform-yes-btn').attr("onclick","push_to_gmc()");
            $('.confirmation-icon').addClass('d-none');
            $('#todays-confirm-icon').removeClass('d-none');
            
        }
        function getProducts(slug, page = 1) {
            var type = $('#type').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('google_merchant_center.products.filter' ) }}?page=${page}`,
                method: 'GET',
                data: { type: type, product_type: slug, search: keyword, seller_type: seller_type, selected_filter:selected_filter, user_id: user_id},
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
            if(statusSlug =='inhouse-products'){
                seller_type = 'admin';
            }
            else if (statusSlug =='seller-products'){
                seller_type = 'seller';
            }
            else{
                seller_type = '{{ $seller_type }}';
            }
            getProducts(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function() {
            getProducts(currentTab);
            filterProductByCategory();
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
            const page = $(this).attr('href').split('page=')[1];
            getProducts(currentTab, page);
        });


        // Right Offcanvas JS Start
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');

            // Open function
            function openRightcanvas() {
                rightOffcanvas.classList.add('active');
                overlay.classList.add('active');
                document.body.classList.add('body-no-scroll');
                $('#rightOffcanvas .action-btn').text("{{ translate('Add') }}").attr('onclick', 'updateProductInGMC()');
            }
            // Close function
            function closeRightcanvas() {
                rightOffcanvas.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('body-no-scroll');
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

        function enableInputField() {
            $('.stock-input').removeClass('d-none');
            $('.stock-input').each(function() {
                $(this).attr('disabled', false);
            });
            //all stock quantity span hide
            $('span#stock-quantity').addClass('d-none');
            $('#offcanvas-btn').removeClass('d-none');
        }

        function disableInputField() {
            $('.stock-input').addClass('d-none');
            $('.stock-input').each(function() {
                $(this).attr('disabled', true);
            });
            $('span#stock-quantity').removeClass('d-none');
            $('#offcanvas-btn').addClass('d-none');
        }

        function updateStocks(productId) {
            var formData = {};
            $('.stock-input').each(function() {
                var stockId = $(this).attr('name');
                var inputValue = $(this).val();
                formData[stockId] = inputValue;
            });

            $.ajax({
                url: "{{ route('bulk-product-stock-update') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    stocks: formData,
                    product_id: productId
                },
                success: function(response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Stock updated successfully') }}');
                        closeRightcanvas();
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

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

        // ========== GOOGLE MERCHANT CENTER EXPORT FUNCTIONS ==========
        
        /**
         * Export selected products to Google Merchant Center format
         * @param {string} format - 'xml', 'csv', or 'txt'
         */
        function exportToGMC(format, exportAll = false) {
            let selectedProducts = [];
            if(!exportAll) {
                $('.check-one-product:checked').each(function() {
                    selectedProducts.push($(this).val());
                });
                
                if(selectedProducts.length === 0) {
                    AIZ.plugins.notify('warning', '{{ translate("Please select at least one product to export") }}');
                    return;
                }
            }
            
            // Show loading modal
            $('#gmcExportModal').modal('show');
            $('#gmcExportModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">{{ translate('Loading...') }}</span>
                    </div>
                    <p>{{ translate('Preparing Google Merchant Center feed...') }}</p>
                    <p class="text-muted small">{{ translate('This may take a moment for large product selections') }}</p>
                </div>
            `);
            
            // Use fetch API with JSON body instead of URLSearchParams
            fetch('{{ route("google_merchant_center.export_products") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/xml, text/csv, text/plain, application/json'
                },
                body: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    product_ids: selectedProducts,
                    format: format
                })
            })
            .then(response => {
                if (!response.ok) {
                    // If response is not OK, try to get error message
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Export failed');
                    }).catch(() => {
                        throw new Error(`Export failed with status: ${response.status}`);
                    });
                }
                
                // Get filename from Content-Disposition header
                let filename = `google_feed_${format}_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.${format}`;
                const disposition = response.headers.get('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '');
                    }
                }
                
                // Get blob and download
                return response.blob().then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    
                    // Close modal and show success
                    setTimeout(() => {
                        $('#gmcExportModal').modal('hide');
                        AIZ.plugins.notify('success', `{{ translate("Export completed successfully!") }} ${selectedProducts.length} {{ translate("products exported as") }} ${format.toUpperCase()}`);
                    }, 500);
                });
            })
            .catch(error => {
                console.error('Export error:', error);
                $('#gmcExportModalBody').html(`
                    <div class="text-center py-4">
                        <i class="las la-exclamation-triangle la-3x text-danger mb-3"></i>
                        <h5>{{ translate("Export Failed") }}</h5>
                        <p class="text-muted">{{ translate("Something went wrong. Please try again.") }}</p>
                        <p class="text-danger small">${escapeHtml(error.message)}</p>
                    </div>
                `);
                
                setTimeout(() => {
                    $('#gmcExportModal').modal('hide');
                }, 3000);
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function updateProductInGMC(productId = null, type = null) {
            let selectedProductIds = [];
            $('.product-select:checked').each(function () {
                selectedProductIds.push($(this).val());
            });

            const addGmcProductUrl = "{{ route('google_merchant_center.products.update') }}";

            $.ajax({
                url: addGmcProductUrl,
                method: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    ids: selectedProductIds
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', response.message);
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                        return;
                    }
                    getProducts(currentTab);
                    closeRightcanvas();
                    filterProductByCategory();
                },
                error: function(xhr) {
                    closeRightcanvas();
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

         function filterProductByCategory() {
            var searchKey = $('input[name=search_product_keyword]').val();
            var selectedCategory = $('select[name=selected_Products_category]').val();
            $.post('{{ route('google_merchant_center.products.search') }}', { _token: AIZ.data.csrf, search_key:searchKey, category:selectedCategory, product_type:"physical_digital",single_select: 0 }, function(data){
                $('#products-list').html(data);
                AIZ.plugins.sectionFooTable('#products-list');
            });
        }

        function removeFromGmc(productId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Confirm Product Removal') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to remove this product from GMC?') }}');
            $('#impact-message').text('{{ translate('Proceeding will remove this item from active GMC listings. You cannot undo this action.') }}');
            $('#conform-yes-btn').attr("onclick", "remove_from_gmc(" + productId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }

        function bulkRemoveFromGmc() {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Confirm Bulk Product Removal') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to remove the selected products from GMC?') }}');
            $('#impact-message').text('{{ translate('Proceeding will remove these items from active GMC listings.') }}');
            $('#conform-yes-btn').attr("onclick", "remove_from_gmc(null, true)");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
        }

        function remove_from_gmc(productId = null, isBulk = false) {
            if(isBulk) {
                var ids = [];
                $('.check-one-product:checked').each(function() {
                    ids.push($(this).val());
                });
                if(ids.length === 0) {
                    AIZ.plugins.notify('danger', '{{ translate("Please select at least one product to remove") }}');
                    hideBulkActionModal();
                    return;
                }
            }
            $.ajax({
                url: "{{ route('google_merchant_center.products.update') }}",
                method: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    ids: isBulk ? ids : [productId],
                    gmc: 0
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('danger', '{{ translate("Product removed from GMC successfully") }}');
                        getProducts(currentTab);
                        hideBulkActionModal();
                        filterProductByCategory();
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate("An error occurred during removal") }}');
                }
            });
        }

        function singlePush(productId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Confirm Product Push') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to push this product to GMC?') }}');
            $('#impact-message').text('{{ translate('Proceeding will add this item to active GMC listings. You can remove it later if needed.') }}');
            $('#conform-yes-btn').attr("onclick", "singlePushConfirm(" + productId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#todays-confirm-icon').removeClass('d-none');
        }

        function singlePushConfirm(productId) {
            $('#confirmation-question').html('<div class="footable-loader h-50 pt-4"><span class="fooicon fooicon-loader"></span></div>');
             $.ajax({
                url: "{{ route('google_merchant_center.google.sync.single', '') }}/" + productId,
                method: 'POST',
                data: {
                    _token: AIZ.data.csrf
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', '{{ translate("Product pushed to GMC successfully") }}');
                        getProducts(currentTab);
                        hideBulkActionModal();
                        filterProductByCategory();
                    } else {
                        AIZ.plugins.notify('danger', response.message);
                    }
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate("An error occurred during push") }}');
                    hideBulkActionModal();
                }
            });
        }

    </script>
@endsection