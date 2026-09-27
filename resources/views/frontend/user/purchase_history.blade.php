@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="card shadow-none rounded-0 border pl-4 pr-4 pt-4">
    <h5 class="mb-2 fs-20 fw-700 text-dark">{{ translate('Purchase History') }}</h5>

    <!-- Tabs & Filters -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
        <ul class="nav nav-tabs purchase-history-tab border-0 fs-12 ml-n3" id="orderTabs">
            @foreach ($all_status as $status)
            <li class="nav-item">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    onclick="changeTab(this, '{{ Str::slug($status) }}')">
                    {{ translate($status) }}
                </button>
            </li>
            @endforeach
        </ul>

        <div class="form-group mb-0 w-25">
            <select class="form-control aiz-selectpicker purchase-history" name="delivery_status" id="delivery_status"
                data-style="btn-light" data-width="100%">
                <option value="">{{ translate('All') }}</option>
                <option value="pending" {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>{{ translate('Pending') }}</option>
                <option value="on_the_way" {{ request('delivery_status') == 'on_the_way' ? 'selected' : '' }}>{{ translate('On The Way') }}</option>
                <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>{{ translate('Delivered') }}</option>
                <option value="cancelled" {{ request('delivery_status') == 'cancelled' ? 'selected' : '' }}>{{ translate('Cancelled') }}</option>
            </select>
        </div>
    </div>

    <!-- Dynamic Tab Content -->
    <div class="tab-content mt-4" id="orderTabContent">
        <div class="tab-pane fade show active" id="tab-content">
            <!-- AJAX content will load here -->
        </div>
    </div>
</div>
@endsection

@section('modal')

<!-- Delete modal -->
<div id="delete-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{translate('Cancel Confirmation')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1 fs-14">{{translate('Are you sure to Cancel this Order?')}}</p>
                <button type="button" class="btn btn-secondary rounded-5 mt-2 btn-sm" data-dismiss="modal">{{translate('No')}}</button>
                <a href="" id="delete-link" class="btn btn-primary rounded-5 mt-2 btn-sm">{{translate('Yes')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade uploadModal" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <!-- Header -->
            <div class="modal-header bg-dark text-white py-2 rounded-0">
                <h6 class="modal-title mb-0">Image Preview</h6>
                <button type="button" class="close text-white fs-3 border-0 bg-transparent" data-dismiss="modal">
                    &times;
                </button>
            </div>
            <!-- Body -->
            <div class="modal-body text-center bg-light p-3">
                <img id="modalImage"
                    src=""
                    class="img-fluid rounded shadow-sm"
                    style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
<script>
    let currentTab = 'all';

    function getOrderData(slug, page = 1) {
        currentTab = slug;
        $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
        $.ajax({
            url: `{{ route('purchase_history.filter') }}?page=${page}`,
            method: 'GET',
            data: {
                tab: slug.replace(/-/g, '_'),
            },
            success: function(response) {
                $('#tab-content').html(response.html);
            },
            error: function() {
                $('#tab-content').html('<div class="text-danger p-4">{{ translate("Failed to load data.") }}</div>');
            }
        });
    }

    function changeTab(button, statusSlug) {
        document.querySelectorAll('#orderTabs .nav-link').forEach(el => el.classList.remove('active'));
        button.classList.add('active');
        getOrderData(statusSlug);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const deliverySelect = document.getElementById('delivery_status');

        function loadOrdersByStatus(status) {
            getOrderData(status);
        }

        deliverySelect.addEventListener('change', function() {
            loadOrdersByStatus(this.value || 'all');
            document.querySelectorAll('#orderTabs .nav-link').forEach(el => el.classList.remove('active'));
        });
        const urlParams = new URLSearchParams(window.location.search);
        const toReviewParam = urlParams.get('to_review');
        if (toReviewParam && (toReviewParam === '1')) {
            const toReviewBtn = document.querySelector(`#orderTabs button[onclick*="to-review"]`);
            if (toReviewBtn) {
                document.querySelectorAll('#orderTabs .nav-link').forEach(el => el.classList.remove('active'));
                toReviewBtn.classList.add('active');
                getOrderData('to-review');
            }

        } else {
            loadOrdersByStatus('all');
        }
    });


    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        const page = $(this).attr('href').split('page=')[1];

        window.scrollTo({ top: 0, behavior: 'smooth' });

        getOrderData(currentTab, page);
    });

    $(document).on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        let url = $(this).data('href');
        $('#delete-link').attr('href', url);
        $('#delete-modal').modal('show');
    });

    function openReviewOffcanvas(product_id, order_id) {
        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');
        
        if (rightOffcanvas) rightOffcanvas.classList.add('active');
        if (overlay) overlay.classList.add('active');
        document.body.classList.add('body-no-scroll');
        
        if (rightOffcanvas) {
            rightOffcanvas.innerHTML = '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';
        }
        
        $.ajax({
            type: "POST",
            url: "{{ route('product_review_modal') }}",
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product_id,
                order_id: order_id
            },
            success: function(html) {
                if (rightOffcanvas) {
                    rightOffcanvas.innerHTML = html;

                    if (typeof AIZ !== 'undefined' && AIZ.extra && AIZ.extra.inputRating) {
                        AIZ.extra.inputRating();
                    }

                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.aizUploader) {
                        AIZ.plugins.aizUploader();
                    }
                }
            },
            error: function() {
                if (rightOffcanvas) {
                    rightOffcanvas.innerHTML = '<div class="p-4 text-center text-danger">{{ translate("Failed to load review form") }}</div>';
                }
                AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
            }
        });
    }

    $(document).on('click', '#review-store', function(e) {
        e.preventDefault();
        
        const btn = $(this);
        const formData = new FormData();
        
        const product_id = $('#modal_product_id').val(); 
        const order_id = $('#modal_order_id').val();
        const rating = $('input[name="rating"]:checked').val();
        const comment = $('textarea[name="comment"]').val();

        if (!product_id) {
            AIZ.plugins.notify('danger', 'Product ID is missing!');
            return;
        }

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('product_id', product_id);
        formData.append('order_id', order_id);
        formData.append('rating', rating);
        formData.append('comment', comment);
        
        const input = document.querySelector('input[name="photos[]"]');
        if (input && input.files.length > 0) {
            for (let i = 0; i < input.files.length; i++) {
                formData.append('photos[]', input.files[i]);
            }
        }
        
        if (!$('input[name="rating"]:checked').val()) {
            AIZ.plugins.notify('warning', '{{ translate("Please select a rating") }}');
            return;
        }
        if (!$('textarea[name="comment"]').val()) {
            AIZ.plugins.notify('warning', '{{ translate("Please write a comment") }}');
            return;
        }
        
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span> {{ translate("Submitting...") }}');
        
        // AJAX Submit
        $.ajax({
            url: "{{ route('reviews.store') }}", 
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                AIZ.plugins.notify('success', '{{ translate("Review submitted successfully") }}');
                
                if (typeof closeOffcanvas === 'function') {
                    closeOffcanvas();
                } else {
                    const rightOffcanvas = document.getElementById('rightOffcanvas');
                    const overlay = document.getElementById('rightOffcanvasOverlay');
                    if (rightOffcanvas) rightOffcanvas.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    document.body.classList.remove('body-no-scroll');
                }
                
                setTimeout(() => {
                    if (typeof getOrderData === 'function') {
                        getOrderData(currentTab);
                    } else {
                        location.reload();
                    }
                }, 1000);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('{{ translate("Confirm") }}');
                const errorMsg = xhr.responseJSON?.message || '{{ translate("Something went wrong") }}';
                AIZ.plugins.notify('danger', errorMsg);
            }
        });
    });
    function openReviewImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
</script>

@endsection