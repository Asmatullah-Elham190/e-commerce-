@extends('backend.layouts.app')

@section('content')

    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('Set Category Wise Product Discount')}}</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <!--Nav Tab -->
        <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px table-nav-tabs pb-3 pb-xl-0">
            <div class="table-tabs-container flex-grow-1">
                @php
                    $active_tab = $active_tab ?? 'all-categories';
                @endphp
                <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                    @foreach ($category_tabs as $category_tab)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $active_tab == Str::slug($category_tab) ? 'active' : '' }}" data-toggle="tab"  role="tab" aria-selected="{{ $active_tab == Str::slug($category_tab) ? 'true' : 'false' }}"
                            id="{{ Str::slug($category_tab) }}-tab"  onclick="changeTab(this, '{{ Str::slug($category_tab) }}')" aria-controls="{{ Str::slug($category_tab) }}">
                            {{ translate($category_tab) }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!--Card Header (Search) Start-->
        <div class="tab-filter-bar">
            <form class="" id="sort_categories" action="" method="GET">
                <div class="card-header row gutters-10 border-0 pb-0 mt-2">
                    <div class="col-12">
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
                                id="search_input" name="search"placeholder="{{translate('Search Categories ...')}}">
                        </div>
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
        <!--Card Header (Search) End-->
    </div>
@endsection

@section('modal')
    <!-- confirm Modal -->
    <div id="confirm-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700">{{translate('N.B: If you set discount here all the products of this category will be discounted. You can also set individual product discount later.
 Do you want to continue?')}}</p>
                    <div>
                        <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <a href="javascript:void(0)" id="trigger_btn" data-value="" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px" onclick="setDiscount()">{{translate('Confirm')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="confirm-modal-switch" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <path d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" fill="#ffc700" />
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700" id="confirmation-message"></p>
                    <div>
                        <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px" onclick="confirmSettingChange()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- /.modal -->
@endsection

@section('script')
    <script type="text/javascript">

        let pendingElement = null;
        let pendingType = null;
        $(document).ready(function() {
            setTimeout(() => {
                AIZ.plugins.dateRange();
            }, "2000");
        });

        function trigger_alert_switch(el, type) {
            pendingElement = el;
            pendingType = type;
            const isChecked = $(el).is(':checked');
                
            const message = isChecked
                ? `Turning on this switch will apply the same discount to all seller products in this category. Do you want to proceed?`
                : `Turning off this switch will not affect already discounted seller products of this category. Are you sure?`;
            $('#confirm-modal-switch .modal-body p').text(message);
            $('#confirm-modal-switch').modal('show');
        }

        function confirmSettingChange() {

            const categoryId = $(pendingElement).attr('id').replace('seller_product_discount_', '');

            const discount = $('input[type="number"][data-category-id="' + categoryId + '"]').val();

            const dateRange = $('input.aiz-date-range[data-category-id="' + categoryId + '"]').val();

            const sellerProductDiscount = $(pendingElement).prop('checked') ? 1 : 0;

            saveDiscount(
                categoryId,
                discount,
                dateRange,
                sellerProductDiscount,
                sellerProductDiscount
                    ? 'Seller product discount enabled successfully'
                    : 'Seller product discount disabled successfully'
            );

            $('#confirm-modal-switch').modal('hide');

            pendingElement = null;
            pendingType = null;
        }

        $('#confirm-modal-switch').on('hidden.bs.modal', function () {
            if (pendingElement) {
                $(pendingElement).prop('checked', !$(pendingElement).is(':checked'));
                pendingElement = null;
                pendingType = null;
            }
        });

        function trigger_alert(CategoryId){
            $('#trigger_btn').attr('data-value', CategoryId);
            $('#confirm-modal').modal('show');
        }

        function handleDiscountEnter(event, el) {
            if (event.key === 'Enter') {
                event.preventDefault();
                var categoryId = $(el).data('category-id');
                var discount = $(el).val();

                if ('{{ env('DEMO_MODE') }}' == 'On') {
                    AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                    return;
                }

                if (discount < 0) {
                    AIZ.plugins.notify('danger', '{{ translate('Discount can not be less than 0') }}');
                    return;
                }

                var dateRange = $('input.aiz-date-range[data-category-id="' + categoryId + '"]').val();
                var sellerProductDiscount = $('#seller_product_discount_' + categoryId).prop('checked') ? 1 : 0;

                saveDiscount(
                    categoryId,
                    discount,
                    dateRange,
                    sellerProductDiscount,
                    'Discount updated successfully'
                );            
            }
        }

        $(document).on('apply.daterangepicker', '.aiz-date-range', function(ev, picker) {
            var el = this;
            var categoryId = $(el).data('category-id');

            setTimeout(function() {
                var dateRange = $(el).val();

                if ('{{ env('DEMO_MODE') }}' == 'On') {
                    AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                    return;
                }

                if (!dateRange) {
                    AIZ.plugins.notify('danger', '{{ translate('Please select a valid date range') }}');
                    return;
                }

                var discount = $('input[type="number"][data-category-id="' + categoryId + '"]').val();
                var sellerProductDiscount = $('#seller_product_discount_' + categoryId).prop('checked') ? 1 : 0;

                saveDiscount(
                    categoryId,
                    discount,
                    dateRange,
                    sellerProductDiscount,
                    'Discount date range updated successfully'
                );
            }, 200);
        });

        function saveDiscount(categoryId, discount, dateRange, sellerProductDiscount, message = '') {

            $.post('{{ route('set_product_discount') }}', {
                _token: '{{ csrf_token() }}',
                category_id: categoryId,
                discount: discount,
                date_range: dateRange,
                seller_product_discount: sellerProductDiscount
            }, function(data) {

                if (data == 1) {

                    if (message) {
                        AIZ.plugins.notify('success', message);
                    } else {
                        AIZ.plugins.notify('success', '{{ translate('Saved Successfully') }}');
                    }
                }

            }).fail(function() {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            });
        }

        let currentTab = '{{ $active_tab }}';
        var searchTimer;

        $(document).on("change", ".check-all", function() 
        {
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

        function sort_categories(el)
        {
            $('#sort_categories').submit();
        }
        
        function getCategories(slug, page = 1) 
        {
            var status = $('#status').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();

            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');

            $.ajax({
                url: `{{ route('categories_wise_product_discount_filter') }}?page=${page}`,
                method: 'GET',
                data: {
                    status: status,
                    category_status: slug,
                    search: keyword
                },
                success: function(response) {
                    $('#tab-content').html(response.html);
                    initFooTable();
                    initDateRangeWithSave();
                }
            });
        }

        function initDateRangeWithSave() {
            $('.aiz-date-range').each(function () {
                var $this = $(this);
                
                if ($this.data('daterangepicker')) {
                    $this.data('daterangepicker').remove();
                    $this.off('apply.daterangepicker');
                    $this.off('cancel.daterangepicker');
                }

                var format    = $this.data('format') || 'DD-MM-Y HH:mm:ss';
                var separator = $this.data('separator') || ' to ';
                var timePicker = $this.data('time-picker') || false;
                
                var initialValue = $this.val();

                $this.daterangepicker({
                    singleDatePicker: false,
                    autoUpdateInput: false,
                    timePicker: timePicker,
                    timePickerIncrement: 1,
                    locale: {
                        format: format,
                        separator: separator,
                        applyLabel: 'Select',
                        cancelLabel: 'Clear',
                    },
                });

                if (initialValue) {
                    $this.val(initialValue);
                }

                $this.on('apply.daterangepicker', function (ev, picker) {
                    var dateRange = picker.startDate.format(format) + separator + picker.endDate.format(format);
                    $(this).val(dateRange);

                    var categoryId = $(this).data('category-id');

                    if ('{{ env('DEMO_MODE') }}' == 'On') {
                        AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                        return;
                    }

                    var discount = $('input[type="number"][data-category-id="' + categoryId + '"]').val();
                    var sellerProductDiscount = $('#seller_product_discount_' + categoryId).prop('checked') ? 1 : 0;

                    saveDiscount(
                        categoryId,
                        discount,
                        dateRange,
                        sellerProductDiscount,
                        'Discount date range updated successfully'
                    );
                });

                $this.on('cancel.daterangepicker', function () {
                    $(this).val('');
                });
            });
        }

        function changeTab(button, statusSlug) 
        {
            document.querySelectorAll('#myTab .nav-link').forEach(el => el.classList.remove('active'));
            button.classList.add('active');
            getCategories(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function() 
        {
            getCategories(currentTab);
        });

        $('#search_input').on('keyup', function () 
        {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getCategories(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function(e) 
        {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getCategories(currentTab, page);
        });

        function clearField(button, type) {
            const container = button.closest('.d-flex');
            let input;

            if (type === 'discount') {
                input = container.querySelector('.discount-input');
            } else if (type === 'discount-date-range') {
                input = container.querySelector('.discount-date-range-input');
            }

            if (input) {
                input.value = '';
                input.focus();
            }
        }
    </script>
@endsection