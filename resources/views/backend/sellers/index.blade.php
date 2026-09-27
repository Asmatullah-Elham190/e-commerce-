@extends('backend.layouts.app')

@section('content')

@php
    $route = Route::currentRouteName() == 'sellers.index' ? 'all_seller_route' : 'seller_rating_followers';
@endphp

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
        <!--Right Side- Add New Button -->
        <div class="">
            @if(auth()->user()->can('add_seller') && ($route == 'all_seller_route'))
                <a href="{{ route('sellers.create') }}" class="position-relative overflow-hidden add-new-btn">
                    <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Seller') }}</span>
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
        <form class="" id="sort_sellers" action="" method="GET">
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
                            id="search_input" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset 
                            placeholder="{{ translate('Type name or email or mobile number & Enter') }}">
                    </div>
                </div>

                @if($route == 'all_seller_route')
                    <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                        <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400" type="button"
                            data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('delete_seller')
                                <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                    href="javascript:void(0)" data-target="#bulk-delete-modal">
                                    {{ translate('Delete selection') }}
                                </a>
                            @endcan
                            @can('seller_commission_configuration')
                                <a class="dropdown-item text-secondary fs-14 fw-500 hov-bg-light hov-text-blue" 
                                    onclick="set_bulk_commission()" href="javascript:void(0)">
                                    {{ translate('Set Bulk Commission') }}
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="col-md-2 mr-0 px-0 inner-select ml-1">
                        <select class="form-control aiz-selectpicker mb-2 mb-md-0 bg-light" 
                            name="verification_status" onchange="sort_sellers()" data-selected="{{ $verification_status }}">
                            <option value="" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Email Verification Status') }}</option>
                            <option value="verified" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Verified') }}</option>
                            <option value="un_verified" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Unverified') }}</option>
                        </select>
                    </div>

                    <div class="col-md-2 mr-0 px-0 inner-select ml-1">
                        <select class="form-control aiz-selectpicker mb-2 mb-md-0 bg-light" 
                            name="approved_status" id="approved_status" onchange="sort_sellers()">
                            <option value="" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Seller Verification Status') }}</option>
                            <option value="1" @isset($approved) @if($approved == '1') selected @endif @endisset 
                                class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Approved') }}</option>
                            <option value="0" @isset($approved) @if($approved == '0') selected @endif @endisset 
                                class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Non-Approved') }}</option>
                        </select>
                    </div>
                @endif

                <!-- Filter Dropdown -->
                {{--<div class="col-md-2 ml-auto mb-1 mb-md-0 px-0 px-md-1">
                    <div class="dropdown w-100">
                        <button class="btn px-3 w-100 d-flex justify-content-between align-items-center dropdown-toggle bg-light border-light"
                            type="button" id="filterMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-secondary fs-14 fw-400">{{ translate('Filter') }}</span>
                            <span class="dropdown-toggle-icon"></span>
                        </button>

                        <div class="dropdown-menu py-3 w-100" aria-labelledby="filterMenu">
                            <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                <input class="input-check" type="checkbox" id="all" name="filter_all">
                                <label class="form-check-label fs-14 px-2" for="all">{{ translate('All') }}</label>
                            </div>
                            <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                <input class="input-check" type="checkbox" id="verified_only" name="verified_only">
                                <label class="form-check-label fs-14 px-2" for="verified_only">{{ translate('Verified Only') }}</label>
                            </div>
                            <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                <input class="input-check" type="checkbox" id="approved_only" name="approved_only">
                                <label class="form-check-label fs-14 px-2" for="approved_only">{{ translate('Approved Only') }}</label>
                            </div>
                            <div class="form-check hover-bg-light py-2 d-flex align-items-center">
                                <input class="input-check" type="checkbox" id="pending_verification" name="pending_verification">
                                <label class="form-check-label fs-14 px-2" for="pending_verification">{{ translate('Pending Verification') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="col-md-2 ml-auto pr-0 pr-md-3 pl-0 inner-select">
                    <select class="form-control aiz-selectpicker mb-2 mb-md-0 bg-light" name="sort_by" id="sort_by" onchange="sort_sellers()">
                        <option value="" class="hov-bg-light text-secondary fs-14 fw-40">{{ translate('Sort By') }}</option>
                        <option value="newest" class="hov-bg-light text-secondary fs-14 fw-40"
                            @isset($sort_by) @if($sort_by == 'newest') selected @endif @endisset>
                            {{ translate('Newest First') }}
                        </option>
                        <option value="oldest" class="hov-bg-light text-secondary fs-14 fw-40"
                            @isset($sort_by) @if($sort_by == 'oldest') selected @endif @endisset>
                            {{ translate('Oldest First') }}
                        </option>
                        <option value="name_asc" class="hov-bg-light text-secondary fs-14 fw-40"
                            @isset($sort_by) @if($sort_by == 'name_asc') selected @endif @endisset>
                            {{ translate('Shop Name (A-Z)') }}
                        </option>
                        <option value="name_desc" class="hov-bg-light text-secondary fs-14 fw-40"
                            @isset($sort_by) @if($sort_by == 'name_desc') selected @endif @endisset>
                            {{ translate('Shop Name (Z-A)') }}
                        </option>
                    </select>
                </div>--}}
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
	<!-- Delete Modal -->
	@include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')

	<!-- Seller verification info Modal -->
	<div class="modal fade" id="verification_info_modal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content" id="verification-info-modal-content">

			</div>
		</div>
	</div>

	<!-- Seller Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog modal-dialog-centered">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>

	
<!-- Reusable Confirmation Modal -->
<div class="modal fade" id="universal-confirm-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6" id="universal-modal-title">{{ translate('Confirmation') }}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="universal-modal-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <a class="btn btn-primary" id="universal-confirm-button">{{ translate('Proceed!') }}</a>
            </div>
        </div>
    </div>
</div>
   

    {{-- Edit Seller Custom Followers --}}
    <div class="modal fade" id="edit_seller_custom_followers">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Edit Seller Custom Followers')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                    </button>
                </div>
                <form class="form-horizontal" action="{{ route('edit_Seller_custom_followers') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="shop_id" value="" id="shop_id">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Custom Followers')}}</label>
                            <div class="col-md-9">
                                <input type="number" lang="en" min="0" step="1" placeholder="{{translate('Custom Followers')}}" value="" name="custom_followers" id="custom_followers" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm text-white">{{translate('save!')}}</button>
                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="set_seller_commission">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Set Seller Commission')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="shop_id" value="" id="shop_id">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Commission Percentage')}}</label>
                        <div class="col-md-9">
                            <input type="number" lang="en" min="0" step="1" placeholder="{{translate('Commission Percentage')}}" value="" name="commission_percentage" id="commission_percentage" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm text-white" onclick="setSellerBasedCommission()">{{translate('save!')}}</button>
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        let searchTimer;
        let currentTab = '{{ Str::slug($seller_types[0] ?? '') }}';
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

        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_seller_verification_info(id){
            $.post('{{ route('sellers.verification_info_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#verification_info_modal #verification-info-modal-content').html(data);
                $('#verification_info_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
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
            $.post('{{ route('sellers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Approved sellers updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        // Ban
        function confirm_ban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to ban this seller?") }}'
            });
        }

        // Unban
        function confirm_unban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to unban this seller?") }}'
            });
        }

        function showConfirmationModal({ url, message }) {
            if ('{{ env('DEMO_MODE') }}' === 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            // Set dynamic content
            document.getElementById('universal-modal-message').innerText = message;
            document.getElementById('universal-confirm-button').setAttribute('href', url);

            // Show the modal
            $('#universal-confirm-modal').modal('show', { backdrop: 'static' });
        }


        function bulk_delete() {
            var data = new FormData($('#sort_sellers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-seller-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected Sellers Deleted Successfully') }}');
                        $('#bulk-delete-modal').modal('hide');
                        getSellers(currentTab);
                    }
                }
            });
        }
        // Set seller bulk commission
        function set_bulk_commission(){
            var sellerIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                sellerIds.push($(this).val());
            });
            if(sellerIds.length > 0){
                $('#seller_ids').val(sellerIds);
                $('#set_seller_commission').modal('show', {backdrop: 'static'});
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Please Select Seller first.') }}');
            }
        }

        
        // Edit seller custom followers
        function editCustomFollowers(shop_id, custom_followers){
            $('#shop_id').val(shop_id);
            $('#custom_followers').val(custom_followers);
            $('#edit_seller_custom_followers').modal('show', {backdrop: 'static'});
        }

        // Suspicious / Unsuspicious
        function confirm_suspicious(url, isSuspicious) {
            const action = isSuspicious ? 'unsuspect' : 'suspect';
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to") }} ' + action + ' {{ translate("this seller?") }}'
            });
        }

        function getSellers(slug, page = 1) {
            currentTab = slug;
            let keyword = $('#search_input').val();
            let status = '{{ $status ?? '' }}';
            let verification_status = $('#sort_sellers select[name="verification_status"]').val();
            let approved_status = $('#sort_sellers select[name="approved_status"]').val();
            let route = '{{ $route }}';
            let url = '';

            // Build URL based on route type
            if (route === 'all_seller_route') {
                url = '{{ route("sellers.index") }}';
            } else if (route === 'seller_rating_followers') {
                url = '{{ route("sellers.rating_followers") }}';
            } else {
                url = '{{ route("sellers.index") }}'; // default fallback
            }

            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `${url}?page=${page}`,
                method: 'GET',
                data: { search: keyword, status: status, verification_status: verification_status, approved_status: approved_status, seller_type: slug, },
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
            getSellers(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getSellers(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getSellers(currentTab, page);
        });

         function sort_sellers(el){
            getSellers(currentTab);
        }

        function singleDelete(productId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected seller?') }}');
            $('#impact-message').text('{{ translate('This action cannot be undone. Once deleted, the seller will be permanently removed.') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + productId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }

        function single_delete(sellerId) {
             $.ajax({
                url: '{{ route("sellers.ajax.destroy", "") }}/' + sellerId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getSellers(currentTab);
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    AIZ.plugins.notify('danger', '{{ translate('Error deleting seller') }}');
                }
            });
        }


        function setSellerBasedCommission() {
            var sellerIds = [];
            $(".check-one[name='id[]']:checked").each(function() {
                sellerIds.push($(this).val());
            });
            
            var commission_percentage = $("#commission_percentage").val();
            if(!commission_percentage) {
                AIZ.plugins.notify('warning', 'Please enter commission percentage');
                return false;
            }
            
            var data = new FormData();
            data.append('seller_ids', sellerIds.join(','));
            data.append('commission_percentage', commission_percentage);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('set_seller_based_commission')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected sellers Commission Updated successfully') }}');
                        $('#set_seller_commission').modal('hide');
                        getSellers(currentTab);
                    } else {
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
        function changeTab(button, statusSlug) {
            document.querySelectorAll('#myTab .nav-link').forEach(el => el.classList.remove('active'));
            button.classList.add('active');
            getSellers(statusSlug);
        }

    </script>
@endsection
