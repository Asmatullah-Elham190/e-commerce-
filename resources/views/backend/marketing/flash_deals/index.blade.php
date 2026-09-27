@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp


    <div class="col-12 col-sm-12 col-lg-12 mx-auto">
        <div class="aiz-titlebar text-left pb-5px">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h3 fw-bold">{{ translate('All Flash Deals') }}</h1>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px">
                <div class="table-tabs-container">
                    <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                        @foreach ($flash_deal_tabs as $flash_deal_tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                    data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    id="{{ Str::slug($flash_deal_tab) }}-tab"
                                    onclick="changeTab(this, '{{ Str::slug($flash_deal_tab) }}')" role="tab"
                                    aria-controls="{{ Str::slug($flash_deal_tab) }}">
                                    {{ translate($flash_deal_tab) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mb-3 mb-md-0">
                    @can('add_flash_deal')
                        <a href="{{ route('flash_deals.create') }}" class="position-relative overflow-hidden add-new-btn">
                            <span
                                class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Flash Deal') }}</span>
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
                    @endcan
                </div>
            </div>

            <div class="tab-filter-bar">
                <form class="" id="sort_flash_deals" action="" method="GET">
                    <div class="card-header border-0 pb-0 mt-2">

                        <div class="col pl-0 ">
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
                                    id="search_input" name="search" placeholder="{{translate('Search Flash Deals ...')}}">
                            </div>
                        </div>

                        <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                            <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400"
                                type="button" data-toggle="dropdown">
                                {{ translate('Bulk Action') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('delete_flash_deal')
                                    <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                        href="javascript:void(0)" onclick="bulkDeleted()">
                                        {{ translate('Delete') }}
                                    </a>
                                @endcan
                            </div>
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
@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = '{{ Str::slug($flash_deal_tabs[0]) }}';
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
        function sort_flash_deals(el) {
            $('#sort_flash_deals').submit();
        }

        function single_delete(flashDealId) {
            $.ajax({
                url: "{{ route('flash_deals.destroy', ':id') }}".replace(':id', flashDealId),
                type: 'GET',
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getFlashDeals(currentTab);
                    }
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_flash_deals')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-flash-deals-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', 'Selected flash deals Deleted successfully');
                        hideBulkActionModal();
                        getFlashDeals(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function bulkDeleted() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one flash deal') }}');
                return;
            }

            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected flash deals?') }}');
            $('#conform-yes-btn').attr("onclick", "bulk_delete()");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');

        }

        function singleDelete(flashDealId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected flash deals?') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + flashDealId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
        }


        function getFlashDeals(slug, page = 1) {
            var status = $('#status').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('flash_deals.filter') }}?page=${page}`,
                method: 'GET',
                data: { status: status, flash_deal_status: slug, search: keyword },
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
            getFlashDeals(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getFlashDeals(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getFlashDeals(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getFlashDeals(currentTab, page);
        });

        function update_flash_deal_status(el) {

            if ('{{env('DEMO_MODE')}}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            if (el.checked) {
                var status = 1;
            }
            else {
                var status = 0;
            }
            $.post('{{ route('flash_deals.update_status') }}', { _token: '{{ csrf_token() }}', id: el.value, status: status }, function (data) {
                if (data == 1) {
                    location.reload();
                }
                else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function update_flash_deal_feature(el) {
            if ('{{env('DEMO_MODE')}}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            if (el.checked) {
                var featured = 1;
            }
            else {
                var featured = 0;
            }
            $.post('{{ route('flash_deals.update_featured') }}', { _token: '{{ csrf_token() }}', id: el.value, featured: featured }, function (data) {
                if (data == 1) {
                    location.reload();
                }
                else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection