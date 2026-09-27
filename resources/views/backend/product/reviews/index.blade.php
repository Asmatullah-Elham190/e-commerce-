@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-10 col-sm-10 col-lg-10 mx-auto">
            <div class="aiz-titlebar text-left pb-5px">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h1 class="h3 fw-bold">{{ translate('All Rating & Reviews') }}</h1>
                    </div>
                </div>
            </div>
            <div class="card">
                <div
                    class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px">
                    <div class="table-tabs-container">
                        <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                            @foreach ($review_tabs as $review_tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                        data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        id="{{ Str::slug($review_tab) }}-tab"
                                        onclick="changeTab(this, '{{ Str::slug($review_tab) }}')" role="tab"
                                        aria-controls="{{ Str::slug($review_tab) }}">
                                        {{ translate($review_tab) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="">
                        @if (auth()->user()->can('add_custom_review'))
                            <a href="{{route('custom-review.create')}}" class="position-relative overflow-hidden add-new-btn">
                                <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Custom Review') }}</span>
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
                    <form class="" id="sort_reviews" action="" method="GET">
                        <div class="card-header row border-0 pb-0 mt-2">
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
                                        id="search_input" name="search" placeholder="{{translate('Search Reviews ...')}}">
                                </div>
                            </div>

                            <div class="col-md-2 ml-auto mb-1 mb-md-0 px-0 px-md-1">
                                <div class="dropdown w-100">
                                    <button
                                        class="btn px-3  w-100 d-flex justify-content-between align-items-center dropdown-toggle"
                                        type="button" id="filterMenu" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="text-secondary fs-14 fw-400">{{translate('Filter By Seller')}}</span>
                                        <span class="dropdown-toggle-icon"></span>
                                    </button>

                                    <div class="dropdown-menu py-3 w-100" aria-labelledby="filterMenu">
                                        <div class="form-check hover-bg-light py-2 d-flex align-items-center px-3">
                                            <input class="input-check seller-filter" type="checkbox" id="seller_all" value="all" checked>
                                            <label class="form-check-label fs-14 px-2" for="seller_all">{{ translate('All') }}</label>
                                        </div>
                                        @foreach ($sellers as $seller)
                                            <div class="form-check hover-bg-light py-2 d-flex align-items-center px-3">
                                                <input class="input-check seller-filter" type="checkbox" 
                                                    id="seller_{{ $seller->id }}" value="{{ $seller->id }}">
                                                <label class="form-check-label fs-14 px-2" for="seller_{{ $seller->id }}">{{ $seller->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 ml-auto pr-0 pr-md-3 pl-0 inner-select ">
                                <select class="form-control  aiz-selectpicker mb-2 mb-md-0 bg-light" name="type"
                                    id="type" onchange="getReviews(currentTab)">
                                    <option value="" class="hov-bg-light text-secondary fs-14 fw-400">{{translate('Sort by Rating')}}</option>
                                    <option value="desc" class="hov-bg-light text-secondary fs-14 fw-40">
                                        {{ translate('Rating (High > Low)') }}</option>
                                    <option value="asc" class="hov-bg-light text-secondary fs-14 fw-40">
                                        {{ translate('Rating (Low > High)') }}</option>
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
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = '{{ Str::slug($review_tabs[0]) }}';
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

        function sort_reviews(el) {
            $('#sort_reviews').submit();
        }

        $(document).on('change', '.seller-filter', function () {
            if ($(this).val() === 'all') {
                if ($(this).is(':checked')) {
                    $('.seller-filter').not(this).prop('checked', false);
                }
            } else {
                $('#seller_all').prop('checked', false);
                if ($('.seller-filter:not(#seller_all):checked').length === 0) {
                    $('#seller_all').prop('checked', true);
                }
            }
            getReviews(currentTab);
        });

        function getSellerIds() {
            let ids = [];
            $('.seller-filter:not(#seller_all):checked').each(function () {
                ids.push($(this).val());
            });
            return ids;
        }

        function getReviews(slug, page = 1) {
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('reviews.filter') }}?page=${page}`,
                method: 'GET',
                data: { 
                    review_status: slug, 
                    search: keyword,
                    seller_id: getSellerIds(),  
                    rating: $('#type').val()
                },
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
            getReviews(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getReviews(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getReviews(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getReviews(currentTab, page);
        });

    </script>
@endsection
