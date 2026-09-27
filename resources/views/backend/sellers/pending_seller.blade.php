@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{ translate('Pending Sellers') }}</h1>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
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
                    <input type="text" class="form-control form-control-sm border-0 px-2 bg-transparent" id="search_input" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name or email or mobile number & Enter') }}">
                </div>
            </div>

            <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400" type="button"
                    data-toggle="dropdown">
                    {{ translate('Bulk Action') }}
                </button>
                @canany(['delete_seller'])
                <div class="dropdown-menu dropdown-menu-right">
                    @can('delete_seller')
                    <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-bluehref="javascript:void(0)"  data-target="#bulk-delete-modal">
                        {{ translate('Delete Selection') }}</a>
                    @endcan
                </div>
                @endcan
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

@endsection

@section('modal')

    @include('modals.delete_modal')
    @include('modals.bulk_delete_modal')
    <div class="modal fade" id="docsPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Customer Documents')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="min-height: 500px;">
                    
                    <div id="filePreviewContainer" class="text-center"></div>
                </div>

                <div class="d-flex align-items-center justify-content-between w-100 px-3 px-lg-5 pb-5 mb-3">
                    <button type="button" id="back-btn"
                        class="bg-transparent border-2 border-gray-400 fs-14 fw-700 rounded-2 py-15px text-success d-block mr-2 w-100"
                        data-dismiss="modal">{{translate('No')}}</button>
                    <a href="javascript:void(0)" id="conform-yes-btn"
                        class="bg-transparent text-center border border-2 border-gray-400 rounded-2 fs-14 fw-700 py-15px text-danger d-block w-100">{{translate('Approved')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    let searchTimer;

    function update_approved(el){
        if ('{{ env('DEMO_MODE') }}' === 'On') {
            AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
            return;
        }
        let registration_approval = el.checked ? 1 : 0;
        let shop_id = el.value;
        let $row = $(el).closest('tr');

        $.post('{{ route('sellers.registration.approved') }}', {
            _token: '{{ csrf_token() }}',
            id: shop_id,
            registration_approval: registration_approval
        }, function (data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate('Pending sellers Approved successfully') }}');
                if (registration_approval === 1) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
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
                    location.reload();
                }
            }
        });
    }


    function update_registration_verification_approval(shop_id){
        if ('{{ env('DEMO_MODE') }}' === 'On') {
            AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
            return;
        }
        $.post('{{ route('sellers.registration.approved') }}', {
            _token: '{{ csrf_token() }}',
            id: shop_id,
            registration_approval: 1,
            verification_status : 1
        }, function (data) {
            if (data == 1) {
                AIZ.plugins.notify('success', '{{ translate('Unverified sellers Verified successfully') }}');
                $('#docsPreviewModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 800);
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }

    function showDocsInModal(customer_docs_json, shop_id) {

        const docs = JSON.parse(customer_docs_json);
        const container = $('#filePreviewContainer').empty();

        const baseUrl = "{{ my_asset('') }}/";
        const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        const docList = [
            { key: 'certificate', label: '{{ translate("Tax Identification Document") }}' },
            { key: 'id_card', label: '{{ translate("ID Card") }}' },
            { key: 'seller_photo', label: '{{ translate("Seller Photo") }}' },
            { key: 'seller_selfie', label: '{{ translate("Seller Selfie") }}' }
            
        ];

        if(docs['certificate_number']) {
            container.append(`
                <div class="mb-4">
                    <h5 class="mb-2">{{ translate('Tax Identification Number') }}:</h5>
                    <p>${docs['certificate_number']}</p>
                </div>
            `);
        }

        docList.forEach(({ key, label }) => {

            if (!docs[key]) return;

            const fileUrl = baseUrl + docs[key];
            const ext = docs[key].split('.').pop().toLowerCase();

            let previewHtml = `<p class="text-danger">Unsupported file format.</p>`;

            if (imageExts.includes(ext)) {
                previewHtml = `<img src="${fileUrl}" style="max-width:100%; max-height:600px;">`;
            } else if (ext === 'pdf') {
                previewHtml = `<iframe src="${fileUrl}" style="width:100%; height:600px;" frameborder="0"></iframe>`;
            }

            container.append(`
                <div class="mb-4">
                    <h5 class="mb-2">${label}:</h5>
                    ${previewHtml}
                </div>
            `);
        });
        $('#docsPreviewModal').data('shop-id', shop_id).modal('show');
    }

    $(document).on('click', '#conform-yes-btn', function () {
        const shop_id = $('#docsPreviewModal').data('shop-id');
        update_registration_verification_approval(shop_id);
    });

    document.addEventListener('DOMContentLoaded', function() {
        getPendingSellers();
    });

    $('#search_input').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            getPendingSellers();
        }, 500);
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        getPendingSellers(page);
    });

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

    function getPendingSellers(page = 1) {
        let keyword = $('#search_input').val();
        let status = '{{ $status ?? '' }}';
        $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
        $.ajax({
            url: `{{ route('sellers.registration_pending' ) }}?page=${page}`,
            method: 'GET',
            data: { search: keyword, status: status },
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
                        getPendingSellers();
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    AIZ.plugins.notify('danger', '{{ translate('Error deleting seller') }}');
                }
            });
        }

</script>
@endsection
