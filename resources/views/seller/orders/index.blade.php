@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{$order_types}}</h1>
            </div>

        </div>
    </div>

    <div class="card">

        <!--Nav Tab -->
        
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

                    
                    <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                        <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400" type="button"
                            data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item confirm-alert text-secondary fs-14 fw-500 hov-bg-light hov-text-blue"
                                href="javascript:void(0)" href="javascript:void(0)" onclick="order_bulk_export()">{{ translate('Export') }}</a>
                        </div>
                    </div>
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
                                    <input class="input-check border"  type="checkbox" id="all">
                                    <label class="form-check-label fs-14 px-2" for="all">{{translate('All')}}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="pending">
                                    <label class="form-check-label fs-14 px-2" for="pending">{{ translate('Pending') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="confirmed">
                                    <label class="form-check-label fs-14 px-2" for="confirmed">{{ translate('Confirmed') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="picked_up">
                                    <label class="form-check-label fs-14 px-2" for="picked_up">{{ translate('Picked Up') }}</label>
                                </div>

                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="on_the_way">
                                    <label class="form-check-label fs-14 px-2" for="on_the_way">{{ translate('On The Way') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="delivered">
                                    <label class="form-check-label fs-14 px-2" for="delivered"> {{ translate('Delivered') }}</label>
                                </div>
                                <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                    <input class="input-check border"  type="checkbox" id="cancelled">
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

                    <div class="col-md-2 ml-auto inner-select input-group mb-0 border border-light px-3 bg-light rounded-1 ">
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

@endsection


@section('script')
    <script type="text/javascript">
        //Dynamic Tab Content Data
        let searchTimer;
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


        
        function getOrders( page = 1) {
            var type = $('#type').val();
            var payment_status = $('#payment_status').val();
            var order_from = '{{ $order_from }}'
            let keyword = $('#search_input').val();
            let dateRange = $('.aiz-date-range').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('seller.orders.filter' ) }}?page=${page}`,
                method: 'GET',
                data: { type: type,  search: keyword, selected_filter:selected_filter, order_from: order_from, payment_status: payment_status, date:dateRange },
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

        document.addEventListener('DOMContentLoaded', function() {
            getOrders();
        });

        function sort_orders(el){
            getOrders();
        }

        $(document).ready(function() {
            $('.aiz-date-range').on('apply.daterangepicker', function(ev, picker) {
                getOrders();
            });
            $('.aiz-date-range').on('change', function() {
                getOrders();
            });
        });
        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getOrders();
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
            getOrders();
        });



        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getOrders( page);
        });

       function order_bulk_export (){
            var url = '{{route('seller.order-bulk-export')}}';
            $("#sort_orders").attr("action", url);
            $('#sort_orders').submit();
            $("#sort_orders").attr("action", '');
        }

    </script>
@endsection
