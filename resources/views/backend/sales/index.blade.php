@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{$order_types}}</h1>
            </div>

        </div>
    </div>

    <div class="card">

        <!--Nav Tab -->
         <div
            class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px table-nav-tabs pb-3 pb-xl-0">
            <div class="table-tabs-container flex-grow-1">
                <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                    @foreach ($seller_types as $seller_type)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                id="{{ Str::slug($seller_type) }}-tab"
                                onclick="changeTab(this, '{{ Str::slug($seller_type) }}')" role="tab"
                                aria-controls="{{ Str::slug($seller_type) }}">
                                {{ translate($seller_type) }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
           
        </div>
        <div class="tab-filter-bar">
            <form class="" id="sort_orders" action="" method="GET">
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
                                id="search_input" name="search" placeholder="Search Orders…">
                        </div>
                    </div>

                    @php
                        $thermal_printer = json_decode(get_setting('thermal_printer'), true) ?? [];
                    @endphp
                    <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                        <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400" type="button"
                            data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        @canany(['delete_order', 'export_order'])
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('export_order')
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue"
                                href="javascript:void(0)" id="order-bulk-export" onclick="order_bulk_export()">{{ translate('Export') }}</a>
                            @endcan
                            @can('can_download_and_print_shipping_label')
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" title="{{ translate('Download Shipping Label') }}"
                                href="javascript:void(0)" id="bulk-download-shipping-label" onclick="bulk_download_shipping_label()">{{ translate('Download Shipping Label') }}</a>
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" title="{{ translate('Print Shipping Label') }}" target="_blank"
                                href="javascript:void(0)" id="bulk-print-shipping-label" onclick="bulk_print_shipping_label()">{{ translate('Print Shipping Label') }}</a>
                            @endcan
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" title="{{ translate('Download Invoice') }}"
                                href="javascript:void(0)" id="bulk-download-invoice" onclick="bulk_download_invoice()">{{ translate('Download Invoice') }}</a>
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" title="{{ translate('Print Invoice') }}"
                                href="javascript:void(0)" id="bulk-download-print" onclick="bulk_print_invoice()">{{ translate('Print Invoice') }}</a>
                            @if(isset($thermal_printer['generate_invoice_for_thermal_printer']) && $thermal_printer['generate_invoice_for_thermal_printer'] == 1)
                                <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" title="{{ translate('Print Thermal Invoice') }}"
                                    href="javascript:void(0)" id="bulk-thermal-invoice-print" onclick="bulk_thermal_invoice_print()">{{ translate('Print Thermal Invoice') }}</a>
                            @endif
                            @if(auth()->user()->can('unpaid_order_payment_notification_send') && $unpaid_order_payment_notification->status == 1 && Route::currentRouteName() == 'unpaid_orders.index')
                            <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" id="bulk_unpaid_order_payment_notification"
                               href="javascript:void(0)" onclick="bulk_unpaid_order_payment_notification()">{{ translate('Unpaid Order Payment Notification') }}</a>
                            @endif

                            @can('delete_order')
                            <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                href="javascript:void(0)" onclick="bulkDelete()">
                                {{ translate('Delete Selection') }}</a>
                            @endcan
                        </div>
                        @endcan
                    </div>
                    
                    @if($seller_type == 'seller')
                    <div class="col-md-2 mr-0 px-0 inner-select ml-1">
                        <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" id="user_id" name="user_id" onchange="sort_orders()">
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
                                <span class="text-secondary fs-14 fw-400">{{translate('Filter By delivery Status')}}</span>
                                <span class="dropdown-toggle-icon"></span>
                            </button>

                            <div class="dropdown-menu py-3 w-100" aria-labelledby="filterMenu">
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="all">
                                    <label class="form-check-label fs-14 px-2" for="all">{{translate('All')}}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="pending">
                                    <label class="form-check-label fs-14 px-2" for="pending">{{ translate('Pending') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="confirmed">
                                    <label class="form-check-label fs-14 px-2" for="confirmed">{{ translate('Confirmed') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="picked_up">
                                    <label class="form-check-label fs-14 px-2" for="picked_up">{{ translate('Picked Up') }}</label>
                                </div>

                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="on_the_way">
                                    <label class="form-check-label fs-14 px-2" for="on_the_way">{{ translate('On The Way') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="delivered">
                                    <label class="form-check-label fs-14 px-2" for="delivered"> {{ translate('Delivered') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check" type="checkbox" id="cancelled">
                                    <label class="form-check-label fs-14 px-2" for="cancelled"> {{ translate('Cancel') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(Route::currentRouteName() != 'unpaid_orders.index')
                    <div class="col-md-2 ml-auto pr-0 pr-md-1 pl-0 inner-select ">
                        <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" name="payment_status"
                            id="payment_status" onchange="sort_orders()">
                            <option value="" class="hov-text-light text-white fs-14 fw-400">{{ translate('Filter by Payment Status') }}</option>
                            <option value="payment_status,paid" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'payment_status' && $query == 'paid') selected @endif @endisset>
                                {{ translate('Paid') }}</option>
                            <option value="payment_status,unpaid" class="hov-bg-light text-secondary fs-14 fw-40"
                                @isset($col_name, $query) @if ($col_name == 'payment_status' && $query == 'unpaid') selected @endif @endisset>
                                {{ translate('Unpaid') }}</option>
                          
                        </select>
                    </div>
                    @endif

                    <div class="col-md-2 ml-auto pl-0 inner-select input-group mb-0 border border-light px-3 bg-light rounded-1">
                        <input type="text" class="aiz-date-range form-control form-control-sm border-0 px-2 bg-transparent" value=""
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
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

    {{-- Bulk Unpaid Order Payment Notification --}}
    <div id="complete_unpaid_order_payment" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content pb-2rem px-2rem">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <form class="form-horizontal" action="{{ route('unpaid_order_payment_notification') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <input type="hidden" name="order_ids" value="" id="order_ids">
                        <p class="mt-2 mb-2 fs-16 fw-700">{{ translate('Are you sure to send notification for the selected orders?') }}</p>
                        <button type="submit" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px">{{ translate('Send Notification') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script type="text/javascript">
        //Dynamic Tab Content Data
        let currentTab = '{{ Str::slug($seller_types[0] ?? '') }}';
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

        function single_delete(orderId) {
            $.ajax({
                url: "{{ route('orders.destroy', ':id') }}".replace(':id', orderId),
                type: 'GET',
                success: function(response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getOrders(currentTab);
                    }
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-order-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected items deleted successfully') }}');
                        hideBulkActionModal(); 
                        getOrders(currentTab);
                    }
                }
            });
        }

        
        function singleDelete(orderId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete this order?') }}');
            $('#impact-message').text('{{ translate('This action cannot be undone. Once deleted, the order will be permanently removed.') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + orderId + ")");
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
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected Orders?') }}');
            $('#impact-message').text('{{ translate('This action cannot be undone. Once deleted, the orders will be permanently removed.') }}');
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
        
        function getOrders(slug, page = 1) {
            var type = $('#type').val();
            var payment_status = $('#payment_status').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var order_from = '{{ $order_from }}'
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            let dateRange = $('.aiz-date-range').val();
            let col = '{{ $col ?? '' }}';
            let status = '{{ $status ?? '' }}';
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('orders.filter' ) }}?page=${page}`,
                method: 'GET',
                data: { type: type, seller_type: slug, search: keyword, selected_filter:selected_filter, user_id: user_id, order_from: order_from, payment_status: payment_status, date:dateRange, col: col, status: status },
                success: function(response) {
                    $('#tab-content').html(response.html);
                    initFooTable();

                },
                error: function() {
                    $('#tab-content').html(`
                        <div class="text-center py-2 w-100">
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

            getOrders(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function() {
            getOrders(currentTab);
        });

        function sort_orders(el){
            getOrders(currentTab);
        }

        $(document).ready(function() {
            $('.aiz-date-range').on('apply.daterangepicker', function(ev, picker) {
                getOrders(currentTab);
            });
            $('.aiz-date-range').on('change', function() {
                getOrders(currentTab);
            });
        });
        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getOrders(currentTab);
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
            getOrders(currentTab);
        });



        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getOrders(currentTab, page);
        });


        function order_bulk_export (){
            var url = '{{route('order-bulk-export')}}';
            $("#sort_orders").attr("action", url);
            $('#sort_orders').submit();
            $("#sort_orders").attr("action", '');
        }

        function bulk_download_shipping_label() {
            var selectedIds = [];
            
            $(".check-one:checked").each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one order') }}');
                return;
            }

            var queryString = selectedIds.map(function(id) {
                return 'order_ids[]=' + id;
            }).join('&');

            window.location.href = '{{ route("bulk_shipping_label.download") }}?' + queryString;
        }

        function bulk_print_shipping_label() {
            var selectedIds = [];
            
            $(".check-one:checked").each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one order') }}');
                return;
            }

            var queryString = selectedIds.map(function(id) {
                return 'order_ids[]=' + id;
            }).join('&');

            window.location.href = '{{ route("bulk_shipping_label.print") }}?' + queryString;
        }

        function bulk_download_invoice() {
            var selectedIds = [];
            
            $(".check-one:checked").each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one order') }}');
                return;
            }

            var queryString = selectedIds.map(function(id) {
                return 'order_ids[]=' + id;
            }).join('&');

            window.location.href = '{{ route("bulk_invoice.download") }}?' + queryString;
        }

        function bulk_print_invoice() {
            var selectedIds = [];
            
            $(".check-one:checked").each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one order') }}');
                return;
            }

            var queryString = selectedIds.map(function(id) {
                return 'order_ids[]=' + id;
            }).join('&');

            window.location.href = '{{ route("bulk_invoice.print") }}?' + queryString;
        }

        function bulk_thermal_invoice_print() {
            var selectedIds = [];
            
            $(".check-one:checked").each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one order') }}');
                return;
            }

            var queryString = selectedIds.map(function(id) {
                return 'order_ids[]=' + id;
            }).join('&');

            window.location.href = '{{ route("bulk_thermal_invoice.print") }}?' + queryString;
        }

        // Unpaid Order Payment Notification
        function unpaid_order_payment_notification(order_id){
            var orderIds = [];
            orderIds.push(order_id);
            $('#order_ids').val(orderIds);
            $('#complete_unpaid_order_payment').modal('show', {backdrop: 'static'});
        }

        // Unpaid Order Payment Notification
        function bulk_unpaid_order_payment_notification(){
            var orderIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                orderIds.push($(this).val());
            });
            if(orderIds.length > 0){
                $('#order_ids').val(orderIds);
                $('#complete_unpaid_order_payment').modal('show', {backdrop: 'static'});
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Please Select Order first.') }}');
            }
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

        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');
        let selectedOrderId = null;

        $(document).on('click', '#manage_order', function (e) {
            e.preventDefault();

            selectedOrderId = $(this).data('order-id');
            openRightcanvas(selectedOrderId);
        });

        function openRightcanvas(orderId) {

            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');

            rightOffcanvas.innerHTML =
                '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';

            $.ajax({
                type: "POST",
                url: "{{ route('all_orders_manage_modal') }}",
                data: {
                    _token: AIZ.data.csrf,
                    order_id: orderId
                },
                success: function (html) {
                    rightOffcanvas.innerHTML = html;
                    $(document).trigger('offcanvas:loaded');
                },
                error: function () {
                    rightOffcanvas.innerHTML =
                        '<p class="text-danger p-3">{{ translate("Failed to load") }}</p>';
                }
            });
        }

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

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeRightcanvas();
            }
        });

        $(document).on('change', '#oc_shipping_system', function () {
            const val  = $(this).val();
            const CSRF = $('meta[name="csrf-token"]').attr('content');
            const $wrap = $('#oc_shipping_extra_fields');
            $wrap.html(''); 

            if (val === 'shiprocket') {
                $wrap.html('<p class="text-muted">{{ translate('Loading...') }}</p>');
                $.when(
                    $.ajax({ url: "{{ route('pickup.addresses.list') }}", type: 'POST', data: { _token: CSRF, user_id: '{{ Auth::id() }}', shipping_system: 'shiprocket' } }),
                    $.ajax({ url: "{{ route('box.sizes.list') }}", type: 'POST', data: { _token: CSRF, user_id: '{{ Auth::id() }}', shipping_system: 'shiprocket' } })
                ).done(function (pickupRes, boxRes) {
                    const pickups = pickupRes[0] || [];
                    const boxes   = boxRes[0]   || [];

              
                    let pickupOptions = '<option value="">{{ translate('Select Address') }}</option>';
                    pickups.forEach(addr => {
                        pickupOptions += `<option value="${addr.id}">${addr.address_nickname || addr.id}</option>`;
                    });

               
                    let boxOptions = '<option value="">{{ translate('Select Box Size') }}</option>';
                    boxes.forEach(box => {
                        boxOptions += `<option value="${box.id}">${box.length} × ${box.breadth} × ${box.height} cm</option>`;
                    });

                    $wrap.html(`
                        <div class="form-group mb-3">
                            <label class="fw-700">{{ translate('Address Nickname') }}</label>
                            <select class="form-control aiz-selectpicker" id="oc_pickup_address_id" data-live-search="true">
                                ${pickupOptions}
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-700">{{ translate('Box Size') }}</label>
                            <select class="form-control aiz-selectpicker" id="oc_box_size_id" data-live-search="true">
                                ${boxOptions}
                            </select>
                        </div>
                    `);
                    AIZ.plugins.bootstrapSelect('refresh');
                }).fail(function () {
                    $wrap.html('<p class="text-danger">{{ translate('Failed to load Shiprocket info.') }}</p>');
                });
            }

            if (val === 'pathao') {
                $wrap.html('<p class="text-muted">{{ translate('Loading...') }}</p>');
                $.ajax({
                    url: "{{ route('pathao.all.store') }}",
                    type: 'GET',
                    success: function (res) {
                        if (!res.success) {
                            $wrap.html(`<p class="text-danger">${res.message}</p>`);
                            return;
                        }
                        let storeOptions = '<option value="">{{ translate('Select Store') }}</option>';
                        (res.stores || []).forEach(s => {
                            storeOptions += `<option value="${s.store_id}">${s.store_name}</option>`;
                        });
                        $wrap.html(`
                            <div class="form-group mb-3">
                                <label class="fw-700">{{ translate('Store Name') }}</label>
                                <select class="form-control aiz-selectpicker" id="oc_pathao_store_id" data-live-search="true">
                                    ${storeOptions}
                                </select>
                            </div>
                        `);
                    },
                    error: function () {
                        $wrap.html('<p class="text-danger">{{ translate('Failed to load Pathao stores.') }}</p>');
                    }
                });
            }
            if (val === 'redx') {
                $wrap.html('<p class="text-muted">{{ translate('Loading...') }}</p>');
                $.ajax({
                    type: 'GET',
                    url: "{{ route('redx.all_pickup_store.delivery_area') }}",
                    success: function (html) {
                        const $temp = $('<div>').html(html);
                        const $formGroups = $temp.find('.form-group');

                        if ($formGroups.length) {
                            $formGroups.find('select[name="pickup_store_id"]').attr('id', 'oc_redx_pickup_store_id');
                            $formGroups.find('select[name="delivery_area_id"]').attr('id', 'oc_redx_delivery_area_id');
                            $formGroups.find('select').removeClass('aiz-selectpicker');
                            $wrap.html($formGroups);
                        } else {
                            $wrap.html('<p class="text-danger">{{ translate('Failed to parse RedX data.') }}</p>');
                        }
                    },
                    error: function () {
                        $wrap.html('<p class="text-danger">{{ translate('Failed to load RedX data.') }}</p>');
                    }
                });
            }
        });

        $(document).on('offcanvas:loaded', function () {
            const ORDER_ID = $('#oc_order_id').val();
            const CSRF     = $('meta[name="csrf-token"]').attr('content');
            if (!$('#oc_shiprocket_courier').length) return;

            $.post("{{ route('shiprocket.couriers') }}", {
                _token: CSRF, order_id: ORDER_ID
            }).done(function (res) {
                if (!res.success) return;
                let html = '<option value="">{{ translate('Select Courier') }}</option>';
                res.couriers.forEach(c => { html += `<option value="${c.id}">${c.name}</option>`; });
                $('#oc_shiprocket_courier').html(html).selectpicker('refresh');
            });
        });

        $(document).on('click', '#oc_confirm_btn', function () {
            const btn      = $(this);
            const ORDER_ID = $('#oc_order_id').val();
            const CSRF     = $('meta[name="csrf-token"]').attr('content');

            if (!ORDER_ID) {
                AIZ.plugins.notify('danger', '{{ translate('Order ID not found') }}');
                return;
            }

            const shippingSystem = $('#oc_shipping_system').val();

            if (shippingSystem === 'shiprocket') {
                const pickupId = $('#oc_pickup_address_id').val();
                const boxId    = $('#oc_box_size_id').val();
                if (!pickupId || !boxId) {
                    AIZ.plugins.notify('warning', '{{ translate('Please select pickup address and box size.') }}');
                    return;
                }
            }

            if (shippingSystem === 'pathao') {
                const storeId = document.getElementById('oc_pathao_store_id')?.value;
                if (!storeId) {
                    AIZ.plugins.notify('warning', '{{ translate('Please select a Pathao store.') }}');
                    btn.prop('disabled', false).text('{{ translate('Confirm') }}');
                    return;
                }
            }

            if (shippingSystem === 'redx') {
                const pickupStoreId = $('#oc_redx_pickup_store_id').val();
                const areaId        = $('#oc_redx_delivery_area_id').val();
                if (!pickupStoreId || !areaId) {
                    AIZ.plugins.notify('warning', '{{ translate('Please select pickup store and delivery area.') }}');
                    return;
                }
            }

            const selectedCourier = $('#oc_shiprocket_courier').val();
            if ($('#oc_shiprocket_courier').length && !selectedCourier) {
                AIZ.plugins.notify('warning', '{{ translate('Please select a courier.') }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            const calls = [];

            const deliveryStatus = $('#oc_delivery_status').val();
            if (deliveryStatus) {
                calls.push($.ajax({
                    url: "{{ route('orders.update_delivery_status') }}",
                    type: 'POST',
                    data: { _token: CSRF, order_id: ORDER_ID, status: deliveryStatus }
                }));
            }

            const paymentStatus = $('#oc_payment_status').val();
            if (paymentStatus === 'paid') {
                calls.push($.ajax({
                    url: "{{ route('orders.update_payment_status') }}",
                    type: 'POST',
                    data: { _token: CSRF, order_id: ORDER_ID, status: 'paid' }
                }));
            }

            const orderNote = $('#notes').val();
                if (typeof orderNote !== 'undefined' && orderNote !== null) {
                    calls.push($.ajax({
                        url: "{{ route('orders.update_note') }}",
                        type: 'POST',
                        data: { _token: CSRF, order_id: ORDER_ID, order_note: orderNote }
                    }));
                }

            const deliveryBoy = $('#oc_delivery_boy').val();
            if (deliveryBoy) {
                calls.push($.ajax({
                    url: "{{ route('orders.delivery-boy-assign') }}",
                    type: 'POST',
                    data: { _token: CSRF, order_id: ORDER_ID, delivery_boy: deliveryBoy }
                }));
            }

            if (shippingSystem === 'steadfast') {
                calls.push($.ajax({
                    url: "{{ route('steadfast.create.order') }}",
                    type: 'POST',
                    data: { _token: CSRF, order_id: ORDER_ID }
                }));
            }

            if (shippingSystem === 'shiprocket') {
                calls.push($.ajax({
                    url: "{{ route('orders.confirm_shiprocket_info') }}",
                    type: 'POST',
                    data: {
                        _token: CSRF, order_id: ORDER_ID,
                        pickup_address_id: $('#oc_pickup_address_id').val(),
                        shipping_box_size_id: $('#oc_box_size_id').val()
                    }
                }));
            }

            if (selectedCourier && $('#oc_shiprocket_courier').length) {
                calls.push($.ajax({
                    url: "{{ route('shiprocket.assign.awb') }}",
                    type: 'POST',
                    data: { _token: CSRF, order_id: ORDER_ID, courier_id: selectedCourier }
                }));
            }

            if ($('#oc_request_pickup').val() == '1') {
                calls.push($.ajax({
                    url: "{{ route('shiprocket.request.pickup') }}",
                    type: 'POST',
                    data: { _token: CSRF, order_id: ORDER_ID }
                }));
            }

            if (shippingSystem === 'pathao') {
                const storeId = document.getElementById('oc_pathao_store_id')?.value;
                calls.push($.ajax({
                    url: "{{ route('pathao.create.order') }}",
                    type: 'POST',
                    data: { 
                        _token: CSRF, 
                        order_id: ORDER_ID, 
                        store_id: storeId 
                    }
                }));
            }

            if (shippingSystem === 'redx') {
                const areaId   = $('#oc_redx_delivery_area_id').val();
                const areaName = $('#oc_redx_delivery_area_id option:selected').text();
                calls.push($.ajax({
                    url: "{{ route('redx_order.create') }}",
                    type: 'POST',
                    data: {
                        _token: CSRF, order_id: ORDER_ID, shipping_system: 'redx',
                        pickup_store_id: $('#oc_redx_pickup_store_id').val(),
                        delivery_area_id: areaId,
                        delivery_area: areaName
                    }
                }));
            }

            if (calls.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate('Nothing to update') }}');
                btn.prop('disabled', false).text('{{ translate('Confirm') }}');
                return;
            }

            const shippingCalls = [];
            const regularCalls = [];

            calls.forEach(function(call, index) {
            });

            function runCallsSequentially(allCalls) {
                if (allCalls.length === 0) {
                    AIZ.plugins.notify('success', '{{ translate('Order updated successfully') }}');
                    closeOffcanvas();
                    getOrders(currentTab);
                    btn.prop('disabled', false).text('{{ translate('Confirm') }}');
                    return;
                }

                const promises = allCalls.map(function(ajaxCall) {
                    return ajaxCall.then(
                        function(res) {
                            if (res && res.success === false) {
                                return $.Deferred().reject({ responseJSON: res }).promise();
                            }
                            return res;
                        }
                    );
                });

                Promise.all(promises.map(function(p) {
                    return p.then(
                        function(res) { return { status: 'fulfilled', value: res }; },
                        function(err) { return { status: 'rejected', reason: err }; }
                    );
                })).then(function(results) {
                    const failed = results.find(function(r) { return r.status === 'rejected'; });
                    
                    if (failed) {
                        let msg = '{{ translate('Something went wrong. Please try again.') }}';
                        try {
                            const res = failed.reason.responseJSON;
                            if (res && res.message) msg = res.message;
                            if (res && res.errors) {
                                const firstError = Object.values(res.errors).flat()[0];
                                if (firstError) msg = firstError;
                            }
                        } catch(e) {}
                        AIZ.plugins.notify('danger', msg);
                    } else {
                        AIZ.plugins.notify('success', '{{ translate('Order updated successfully') }}');
                        closeOffcanvas();
                        getOrders(currentTab);
                    }
                    btn.prop('disabled', false).text('{{ translate('Confirm') }}');
                });
            }

            runCallsSequentially(calls);
        });
        
        $(document).on('click', '.copy_tracking_code_btn', function () {
            let trackingCode = $(this)
                .closest('.input-group')
                .find('.tracking_code_input')
                .val();
            navigator.clipboard.writeText(trackingCode).then(function () {
                AIZ.plugins.notify('success', 'Tracking code copied');
            });
        });
    </script>
    
@endsection
