@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-10 col-sm-10 col-lg-10 mx-auto">
            <div class="aiz-titlebar text-left pb-5px">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h1 class="h3 fw-bold">{{ translate('Reviews Details') }}</h1>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="d-flex justify-content-between">
                    <div class="row gutters-5 w-400px w-md-500px align-items-center ml-1">
                        <div class="col-auto">
                            <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="Image" class="size-80px img-fit">
                        </div>
                        <div class="col">
                            <span class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                        </div>
                    </div>
                    <div class="text-right m-3">
                        <p class="fs-11 fw-300 m-0">{{ strtoupper(translate('Rating')) }}</p>
                        <p class="fs-16 fw-900 m-0">{{ $product->rating }}</p>
                        <p class="rating rating-sm m-0">
                            @for ($i=0; $i < $product->rating; $i++)
                                <i class="las la-star active"></i>
                            @endfor
                            @for ($i=0; $i < 5-$product->rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </p>
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
                            <a href="{{ route('custom-review.create', $product->id) }}" class="position-relative overflow-hidden add-new-btn">
                                <span
                                    class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Custom Review') }}</span>
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
                    <form class="" id="sort_reviews" action="" method="GET">
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
                                        id="search_input" name="search" placeholder="{{translate('Search Reviews ...')}}">
                                </div>
                            </div>

                            @can('can_published_reviews')
                                <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 rounded-1">
                                    <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400"
                                        type="button" data-toggle="dropdown">
                                        {{ translate('Bulk Action') }}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item confirm-alert text-dark fs-14 fw-500 hov-bg-light hov-text-blue"
                                            href="javascript:void(0)" onclick="bulkPublished()">
                                            {{ translate('Published/Unpublished') }}
                                        </a>
                                    </div>
                                </div>
                            @endcan

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
        let productId = {{ $product->id }};
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

        function getReviews(slug, page = 1) {
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('reviews-details.filter') }}?page=${page}`,
                method: 'GET',
                data: {
                    product_id: productId,
                    review_status: slug,
                    search: keyword,
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

        function bulkPublished() {
            let ids = [];
            $('.check-one:checked').each(function () {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one review') }}');
                return;
            }

            window.bulkStatusIds = ids;

            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Update Status Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to update status of the selected reviews?') }}');
            $('#conform-yes-btn').attr("onclick", "bulk_status_update()");
            $('.confirmation-icon').addClass('d-none');
            $('#publish-confirm-icon').removeClass('d-none');
        }

        function bulk_status_update() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-review-published') }}",
                type: 'POST',
                data: { ids: window.bulkStatusIds },
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                        hideBulkActionModal();
                        getReviews(currentTab);
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function singlepublished(el, reviewId) {
            let status = el.checked ? 1 : 0;
            el.checked = !el.checked;

            showBulkActionModal();

            $('.confirmation-icon').addClass('d-none');   

            if (status == 1) {
                $('#confirmation-title').text('{{ translate('Publish Confirmation') }}');
                $('#confirmation-question').text('{{ translate('Are you sure you want to publish this review?') }}');
                $('#publish-confirm-icon').removeClass('d-none'); 
            } else {
                $('#confirmation-title').text('{{ translate('Unpublish Confirmation') }}');
                $('#confirmation-question').text('{{ translate('Are you sure you want to unpublish this review?') }}');
                $('#reject-confirm-icon').removeClass('d-none');
            }

            $('#conform-yes-btn').attr("onclick", `single_published(${reviewId}, ${status})`);
        }

        function single_published(reviewId, status) {
            $.ajax({
                url: "{{ route('reviews.published') }}",
                type: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    id: reviewId,
                    status: status
                },
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', status == 1
                            ? '{{ translate('Review published successfully') }}'
                            : '{{ translate('Review unpublished successfully') }}');
                        hideBulkActionModal();
                        getReviews(currentTab);
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection