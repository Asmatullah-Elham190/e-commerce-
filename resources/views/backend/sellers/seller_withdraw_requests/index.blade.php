@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-10 col-sm-10 col-lg-10 mx-auto">
            <div class="aiz-titlebar text-left pb-5px">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h1 class="h3 fw-bold">{{ translate('All Seller Withdraw Request') }}</h1>
                    </div>
                </div>
            </div>

            <div class="card">
                <div
                    class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px">
                    <div class="table-tabs-container">
                        <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                            @foreach ($seller_withdraw_request_tabs as $seller_withdraw_request_tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                        data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        id="{{ Str::slug($seller_withdraw_request_tab) }}-tab"
                                        onclick="changeTab(this, '{{ Str::slug($seller_withdraw_request_tab) }}')" role="tab"
                                        aria-controls="{{ Str::slug($seller_withdraw_request_tab) }}">
                                        {{ translate($seller_withdraw_request_tab) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="tab-filter-bar">
                    <form class="" id="sort_seller_withdraw_requests" action="" method="GET">
                        <div class="card-header border-0 pb-0 mt-2">
                            <div class="flex-grow-1">
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
                                        id="search_input" name="search" placeholder="{{translate('Search...')}}">
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
    </div>

@endsection

@section('modal')
    <div class="modal fade" id="payment_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="payment-modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="message_modal">
        <div class="modal-dialog">
            <div class="modal-content" id="message-modal-content">

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = '{{ Str::slug($seller_withdraw_request_tabs[0]) }}';
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
        function sort_seller_withdraw_requests(el) {
            $('#sort_seller_withdraw_requests').submit();
        }

        function getSellerWithdrawRequests(slug, page = 1) {
            var status = $('#status').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('withdraw_requests_all_filter') }}?page=${page}`,
                method: 'GET',
                data: { status: status, eller_withdraw_request_status: slug, search: keyword },
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
            getSellerWithdrawRequests(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getSellerWithdrawRequests(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getSellerWithdrawRequests(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getSellerWithdrawRequests(currentTab, page);
        });

        function show_seller_payment_modal(id, seller_withdraw_request_id) {
            $.post('{{ route('withdraw_request.payment_modal') }}', { _token: '{{ @csrf_token() }}', id: id, seller_withdraw_request_id: seller_withdraw_request_id }, function (data) {
                $('#payment-modal-content').html(data);
                $('#payment_modal').modal('show', { backdrop: 'static' });
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_message_modal(id) {
            $.post('{{ route('withdraw_request.message_modal') }}', { _token: '{{ @csrf_token() }}', id: id }, function (data) {
                $('#message-modal-content').html(data);
                $('#message_modal').modal('show', { backdrop: 'static' });
            });
        }

    </script>
@endsection