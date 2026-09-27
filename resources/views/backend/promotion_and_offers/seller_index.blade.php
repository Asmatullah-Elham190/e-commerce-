{{-- seller_index  --}}
@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp

    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{ translate('Seller Promotional Products') }}</h1>
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
                
                    <div class="col-md-2 mr-0 px-0 inner-select ml-1 mr-1">
                        <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" id="user_id" name="user_id" onchange="sort_products()">
                            <option value="" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('All Sellers') }}</option>
                            @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $key => $seller)
                                <option class="hov-bg-light text-secondary fs-14 fw-40" value="{{ $seller->id }}">
                                    {{ $seller->shop?->name }} ({{ $seller->name }})
                                </option>
                            @endforeach
                        </select>
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
        
        function getProducts(slug, page = 1) {
            var type = $('#type').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('seller_promotional_products.filter' ) }}?page=${page}`,
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
            if(statusSlug === 'drafts'){
                $('#bulk-publish-option, #bulk-featured-option, #bulk-td-option').hide();
            } else {
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

    </script>
@endsection
