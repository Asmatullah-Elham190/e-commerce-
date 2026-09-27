@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left pb-5px">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3 fw-bold">{{ translate('All Seller Requests') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">

        <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom border-light px-25px table-nav-tabs pb-3 pb-xl-0">
            <div class="table-tabs-container flex-grow-1">
                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 pb-15px fs-14 fw-500 active"
                            data-toggle="tab" role="tab" aria-selected="true"
                            id="all-requests-tab"
                            onclick="changeTab(this, 'all-requests')" role="tab"
                            aria-controls="all-requests">
                            {{ translate('All Requests') }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-filter-bar">
            <form class="" id="sort_products" action="" method="GET">
                <div class="card-header row border-0 pb-0 mt-2">
                    <div class="col pl-0 pl-md-3">
                        <div class="input-group mb-0 border border-light px-3 bg-light rounded-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-0 bg-transparent px-0" id="search">
                                    <svg id="Group_38844" xmlns="http://www.w3.org/2000/svg" width="16.001" height="16" viewBox="0 0 16.001 16">
                                        <path d="M8.248,14.642a6.394,6.394,0,1,1,6.394-6.394A6.4,6.4,0,0,1,8.248,14.642Zm0-11.509a5.115,5.115,0,1,0,5.115,5.115A5.121,5.121,0,0,0,8.248,3.133Z"
                                            transform="translate(-1.854 -1.854)" fill="#a5a5b8" />
                                        <path d="M23.011,23.651a.637.637,0,0,1-.452-.187l-4.92-4.92a.639.639,0,0,1,.9-.9l4.92,4.92a.639.639,0,0,1-.452,1.091Z"
                                            transform="translate(-7.651 -7.651)" fill="#a5a5b8" />
                                    </svg>
                                </span>
                            </div>
                            <input type="text" class="form-control form-control-sm border-0 px-2 bg-transparent"
                                id="search_input" name="search" placeholder="{{ translate('Search requests…') }}">
                        </div>
                    </div>

                    <div class="col-md-2 ml-auto pr-0 pr-md-3 pl-0 inner-select">
                        <select class="form-control aiz-selectpicker mb-2 mb-md-0 bg-light" id="user_id" name="user_id" onchange="sort_products()" data-live-search="true">
                            <option value="">{{ translate('All Sellers') }}</option>
                            @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $seller)
                                <option value="{{ $seller->id }}">
                                    {{ $seller->shop?->name }} ({{ $seller->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tab-content filter-tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-content">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.loading_modal')
@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = 'all-requests';
        let searchTimer;
        let page = 1;

        function getRequests(slug, page = 1) {
            currentTab = slug;
            var user_id = $('#user_id').val();
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('seller_requests.filter') }}?page=${page}`,
                method: 'GET',
                data: { search: keyword, user_id: user_id },
                success: function (response) {
                    $('#tab-content').html(response.html);
                    initFooTable();
                },
                error: function () {
                    $('#tab-content').html(`
                        <div class="text-center py-2 my-4 w-100">
                            <h5 class="fs-16 fw-bold text-gray">{{ translate('Something Went Wrong') }}</h5>
                            <i class="las la-frown fs-48 text-soft-white"></i>
                        </div>
                    `);
                }
            });
        }

        function changeTab(button, slug) {
            document.querySelectorAll('#myTab .nav-link').forEach(el => el.classList.remove('active'));
            button.classList.add('active');
            getRequests(slug);
        }

        function sort_products() {
            getRequests(currentTab);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getRequests(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getRequests(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            getRequests(currentTab, page);
        });
    </script>
@endsection