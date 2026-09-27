@extends('seller.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Products') }}</h1>
        </div>
      </div>
    </div>

    <div class="row gutters-10 justify-content-center">
        @if (addon_is_activated('seller_subscription'))
            <div class="col-md-4 mx-auto mb-3" >
                <div class="bg-grad-1 text-white rounded-lg overflow-hidden">
                  <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                      <i class="las la-upload la-2x text-white"></i>
                  </span>
                  <div class="px-3 pt-3 pb-3">
                      <div class="h4 fw-700 text-center">{{ max(0, auth()->user()->shop->product_upload_limit - auth()->user()->products()->count()) }}</div>
                      <div class="opacity-50 text-center">{{  translate('Remaining Uploads') }}</div>
                  </div>
                </div>
            </div>
        @endif

        <div class="col-md-4 mx-auto mb-3" >
            <a href="{{ route('seller.products.create')}}">
              <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                  <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                      <i class="las la-plus la-3x text-white"></i>
                  </span>
                  <div class="fs-18 text-primary">{{ translate('Add New Product') }}</div>
              </div>
            </a>
        </div>

        @if (addon_is_activated('seller_subscription'))
        @php
            $seller_package = \App\Models\SellerPackage::find(Auth::user()->shop->seller_package_id);
        @endphp
        <div class="col-md-4">
            <a href="{{ route('seller.seller_packages_list') }}" class="text-center bg-white shadow-sm hov-shadow-lg text-center d-block p-3 rounded">
                @if($seller_package != null)
                    <img src="{{ uploaded_asset($seller_package->logo) }}" height="44" class="mw-100 mx-auto">
                    <span class="d-block sub-title mb-2">{{ translate('Current Package')}}: {{ $seller_package->getTranslation('name') }}</span>
                @else
                    <i class="la la-frown-o mb-2 la-3x"></i>
                    <div class="d-block sub-title mb-2">{{ translate('No Package Found')}}</div>
                @endif
                <div class="btn btn-outline-primary py-1">{{ translate('Upgrade Package')}}</div>
            </a>
        </div>
        @endif

    </div>

     <!--Nav Tab -->
         <div
            class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px table-nav-tabs pb-3 pb-xl-0">
            <div class="table-tabs-container flex-grow-1">
                <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                   @foreach ($product_types as $product_type)
                        @php
                            $slug = Str::slug($product_type);
                            $isActive = $selected_type
                                ? Str::slug($selected_type) === $slug
                                : $loop->first;
                        @endphp

                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link px-0 pb-15px fs-14 fw-500 {{ $isActive ? 'active' : '' }}"
                                data-toggle="tab"
                                role="tab"
                                aria-selected="{{ $isActive ? 'true' : 'false' }}"
                                id="{{ $slug }}-tab"
                                onclick="changeTab(this, '{{ $slug }}')"
                                aria-controls="{{ $slug }}"
                            >
                                {{ translate($product_type) }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            
        </div>
        <div class="tab-filter-bar">
            <form class="" id="sort_products" action="" method="GET">
                <div class="row  border-0 pb-0 my-3">
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
                            <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue" href="javascript:void(0)"  data-target="#bulk-delete-modal"> {{translate('Delete selection')}}</a>
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
                                    <input class="input-check border"  type="checkbox" id="all">
                                    <label class="form-check-label fs-14 px-2" for="all">{{ translate('All') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="all-discount">
                                    <label class="form-check-label fs-14 px-2" for="all-discount">{{ translate('All Discounted') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="all-publish">
                                    <label class="form-check-label fs-14 px-2" for="all-publish">{{ translate('All Published') }}</label>
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

@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')

    <!-- Offcanvas -->
    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        
        <!-- content will here -->
         @include('offcanvas.products_select_right_canvas')

    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection

@section('script')
    <script type="text/javascript">


        let currentTab = '{{ Str::slug($selected_type ?? ($product_types[0] ?? '')) }}';
        let searchTimer;
        let page = 1 ;
        let selected_filter = [];
        let brand_id = '{{ $brand_id ?? '' }}';
        let category_id = '{{ $category_id ?? '' }}';


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

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else if(data == 2){
                    AIZ.plugins.notify('danger', '{{ translate('Please upgrade your package.') }}');
                    location.reload();
                }
                else if(data == 3){
                    AIZ.plugins.notify('danger', '{{ translate('GST verification is pending for your account.') }}');
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.products.bulk-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

        function getProducts(slug, page = 1) {
            var type = $('#type').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('seller.products.filter' ) }}?page=${page}`,
                method: 'GET',
                data: { type: type, product_type: slug, search: keyword, selected_filter:selected_filter, brand_id: brand_id, category_id: category_id },
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

         // Right Offcanvas JS Start
            const rightOffcanvas = document.getElementById('rightOffcanvas');
            const overlay = document.getElementById('rightOffcanvasOverlay');

            // Open function
            function openRightcanvas(id, name) {
                // content.textContent = data;
                rightOffcanvas.classList.add('active');
                overlay.classList.add('active');
                document.body.classList.add('body-no-scroll');
                rightOffcanvas.innerHTML='';

                $.ajax({
                    type: "GET",
                    url: "{{ route('seller.stock.show', '') }}/" + id,
                    success: function (data) {
                        rightOffcanvas.innerHTML = data;
                    },
                    error: function () {
                        rightOffcanvas.innerHTML = '<p class="text-danger">{{ translate("Failed to load stock data") }}</p>';
                    }
                });
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
                url: "{{ route('seller.bulk-product-stock-update') }}",
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

        var duplicateProductUrl = "{{ route('seller.products.duplicate', ':id') }}";

        function single_delete(productId) {
            $.ajax({
                url: "{{ route('seller.products.single_destroy', ':id') }}".replace(':id', productId),
                type: 'GET',
                success: function(response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getProducts(currentTab);
                    }
                }
            });
        }

        function singleDelete(productId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected product?') }}');
            $('#impact-message').text('{{ translate('This action cannot be undone. Once deleted, the product will be permanently removed.') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + productId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }

    </script>
@endsection