@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                @if(isset($back_to) && $back_to== 'brands')
                <a class="fs-14 fw-400 d-inline-block" href="{{ route('brands.index') }}"> <i class="las la-angle-left"></i> {{translate('Back to Brands')}}</a>
                @elseif(isset($back_to) && $back_to== 'categories')
                <a class="fs-14 fw-400 d-inline-block" href="{{ route('categories.index') }}"><i class="las la-angle-left"></i> {{translate('Back to Categories')}} </a>
                @else
                <h1 class="h3 fw-bold">{{ translate('All products') }}</h1>
                @endif
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
                @if ($seller_type != 'seller' && auth()->user()->can('add_new_product'))
                    <a href="{{ route('products.create') }}" class="position-relative overflow-hidden add-new-btn">
                        <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Product') }}</span>
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
                            @can('product_edit')
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue"
                                href="javascript:void(0)" id="bulk-publish-option" onclick="bulkPublish()">
                                {{ translate('Publish') }}</a>
                            <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" id="bulk-featured-option" onclick="bulkFeatured()"
                                href="javascript:void(0)">
                                {{ translate('Mark Featured') }}</a>
                            <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" id="bulk-td-option" onclick="bulkProductTodaysDeal()"
                                href="javascript:void(0)">
                                {{ translate('Mark Todays Deal') }}</a>
                            @endcan
                            @can('product_delete')
                            <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                href="javascript:void(0)" onclick="bulkDelete()">
                                {{ translate('Delete') }}</a>
                            @endcan
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


                    <div class="col-md-2 ml-auto pr-0 pr-md-3 pl-0 inner-select ">
                        <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" name="type"
                            id="type" onchange="sort_products()">
                            <option value="" class="hov-text-light text-white fs-14 fw-400">Sort</option>
                            <option value="rating,desc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif @endisset>
                                {{ translate('Rating (High > Low)') }}</option>
                            <option value="rating,asc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif @endisset>
                                {{ translate('Rating (Low > High)') }}</option>
                            <option value="num_of_sale,desc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'desc') selected @endif @endisset>
                                {{ translate('Num of Sale (High > Low)') }}</option>
                            <option value="num_of_sale,asc" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'asc') selected @endif @endisset>
                                {{ translate('Num of Sale (Low > High)') }}</option>
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
    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>

@endsection


@section('script')
    <script type="text/javascript">
        //Dynamic Tab Content Data
        let currentTab = '{{ Str::slug($product_types[0] ?? '') }}';
        let searchTimer;
        let seller_type = '{{ $seller_type }}';
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

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
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

        function update_published(el){

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
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else if(data == 3){
                    AIZ.plugins.notify('danger', '{{ translate('GST verification is pending for this account.') }}');
                }
                else if(data == 4){
                    AIZ.plugins.notify('warning', '{{ translate('Please assign GST details') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_approved(el){

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            if(el.checked){
                var approved = 1;
            }
            else{
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token      :   '{{ csrf_token() }}',
                id          :   el.value,
                approved    :   approved
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_featured(el){
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
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        // function sort_products(el){
        //     $('#sort_products').submit();
        // }

        function single_delete(productId) {
            $.ajax({
                url: "{{ route('products.destroy', ':id') }}".replace(':id', productId),
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


        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-product-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected items deleted successfully') }}');
                        hideBulkActionModal(); 
                        getProducts(currentTab);
                    }
                }
            });
        }

        function bulk_publish() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-product-publish')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected items Published successfully') }}');
                        hideBulkActionModal(); 
                        getProducts(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function bulk_feature() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-product-featured')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success','{{ translate('Selected items added in featured successfully') }}' );
                        hideBulkActionModal(); 
                        getProducts(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }


        function bulk_todays_deal() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-product-todays-deal')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected products have been marked as Today’s Deals.') }}');
                        hideBulkActionModal();
                        getProducts(currentTab);
                    }
                }
            });
        }

        var duplicateProductUrl = "{{ route('products.duplicate', ':id') }}";
        
        function singleDelete(productId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected product?') }}');
            $('#impact-message').text('{{ translate('This action cannot be undone. Once deleted, the product will be permanently removed.') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + productId + ")");
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
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected products?') }}');
            $('#impact-message').text('{{ translate('This action cannot be undone. Once deleted, the products will be permanently removed.') }}');
            $('#conform-yes-btn').attr("onclick","bulk_delete()");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }


        function bulkPublish() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one item') }}');
                return;
            }
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Publish Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to publish the selected products?') }}');
            $('#impact-message').text('{{ translate('Products already published will be skipped.') }}');
            $('#conform-yes-btn').attr("onclick","bulk_publish()");
            $('.confirmation-icon').addClass('d-none');
            $('#publish-confirm-icon').removeClass('d-none');
           
        }

        function bulkProductTodaysDeal() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one item') }}');
                return;
            }
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Today’s Deal Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to make the selected products as Today’s Deals?') }}');
            $('#impact-message').text('{{ translate('Products already marked as Today’s Deals will be skipped.') }}');
            $('#conform-yes-btn').attr("onclick","bulk_todays_deal()");
            $('.confirmation-icon').addClass('d-none');
            $('#todays-confirm-icon').removeClass('d-none');
            
        }

        
        function bulkFeatured() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one item') }}');
                return;
            }
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Feature Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to mark the selected products as featured ?') }}');
            $('#impact-message').text('{{ translate('Products already marked as featured will be skipped.') }}');
            $('#conform-yes-btn').attr("onclick","bulk_feature()");
            $('.confirmation-icon').addClass('d-none');
            $('#feature-confirm-icon').removeClass('d-none');
        }
        
        function getProducts(slug, page = 1) {
            let source = '{{ $source }}'
            var type = $('#type').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('products.filter' ) }}?page=${page}`,
                method: 'GET',
                data: { type: type, product_type: slug, search: keyword, seller_type: seller_type, selected_filter:selected_filter, user_id: user_id, brand_id: brand_id, category_id: category_id, source: source },
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
            const page = $(this).attr('href').split('page=')[1];
            getProducts(currentTab, page);
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
                    url: "{{ route('stock.show', '') }}/" + id,
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

    </script>
@endsection
