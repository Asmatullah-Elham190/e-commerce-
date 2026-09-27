@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp
    @php
        $isCategoryBasedRefund = get_setting('refund_type') == 'category_based_refund';
    @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('Set Category Wise Product Refund')}}</h1>
            </div>
        </div>
        @if(get_setting('refund_type') != 'category_based_refund')
            <div class="alert alert-info mt-2 text-center">
                <p class="pt-3 font-weight-bold text-danger">{{ translate(' Category Based Refund is not Activated, Active ') }}
                    <a href="{{ route('refund_time_config') }}">{{ translate('Here') }}</a>
                </p>
            </div>
        @endif
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

                <div class="">
                    <a onclick="unassigned_categories()" class="position-relative overflow-hidden add-new-btn">
                        <span
                            class="position-relative z-2 pr-15px fs-14 fw-500 text-danger label-text">{{ translate('Filter Unassigned') }}</span>
                        <span
                            class="position-absolute top-0 right-0 h-100 w-40px bg-danger d-flex align-items-center justify-content-end z-1 plus-icon-container m-0 p-0 rounded-pill">
                            <svg id="filter-icon" xmlns="http://www.w3.org/2000/svg" width="18.667" height="12"
                                viewBox="0 0 18.667 12">
                                <path id="Path_45233" data-name="Path 45233"
                                    d="M151.667-684a.971.971,0,0,1-.713-.286.959.959,0,0,1-.287-.708.978.978,0,0,1,.287-.714.961.961,0,0,1,.713-.292H155a.971.971,0,0,1,.712.286.959.959,0,0,1,.288.708.979.979,0,0,1-.288.714A.96.96,0,0,1,155-684Zm-4-5a.971.971,0,0,1-.713-.286.959.959,0,0,1-.287-.708.978.978,0,0,1,.287-.714.961.961,0,0,1,.713-.292H159a.971.971,0,0,1,.712.286.959.959,0,0,1,.288.708.978.978,0,0,1-.288.714A.96.96,0,0,1,159-689ZM145-694a.971.971,0,0,1-.712-.286.959.959,0,0,1-.288-.708.979.979,0,0,1,.288-.714A.961.961,0,0,1,145-696h16.667a.971.971,0,0,1,.712.286.959.959,0,0,1,.288.708.979.979,0,0,1-.288.714.96.96,0,0,1-.712.292Z"
                                    transform="translate(-144 696)" fill="#fff" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>


            <!--Card Header (Search) Start-->
            <div class="tab-filter-bar">
                <form class="" id="sort_categories" action="" method="GET">
                    <div class="card-header row gutters-10 border-0 pb-0 mt-2">
                        <div class="col-12 col-lg-10">
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

                        <div class="col-12 col-lg-2 mt-2 mt-lg-0">
                            <div class="d-flex flex-wrap flex-lg-nowrap" style="gap: 5px">
                                <div class="flex-grow-1">
                                    <button type="button" onclick='openRightcanvas()'
                                        class="form-control bg-blue border border-blue fs-14 fw-bold text-white hov-opacity-80 has-transition" {{ $isCategoryBasedRefund ? '' : 'disabled' }}>Bulk
                                        Assign</button>
                                </div>
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

    @include('modals.loading_modal')

    <!-- ================ Right Offcanvas Start ================ -->
    <div id="rightOffcanvas" class="right-offcanvas-md position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        <!--Top Section-->
        <div class="border-bottom pb-15px px-30px">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="d-flex align-items-center fs-16 fw-700 text-dark mr-2 mt-0 mb-0 p-0">{{translate('Bulk Assign Refund Request Time(Days)')}}
                </h6>
                <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="Path_45226" data-name="Path 45226"
                            d="M228.588-716.31l-.9-.9,7.1-7.1-7.1-7.1.9-.9,7.1,7.1,7.1-7.1.9.9-7.1,7.1,7.1,7.1-.9.9-7.1-7.1Z"
                            transform="translate(-227.69 732.31)" fill="#a5a5b8" />
                    </svg>
                </button>
            </div>
        </div>
        <!--Offcanvas Body-->
        <div class="right-offcanvas-body position-absolute h-100 px-30px">
            <div class="pt-4">
                <div class="w-48px h-48px d-flex align-items-center justify-content-center overflow-hidden">
                    <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg" width="48" height="48.001" viewBox="0 0 48 48.001">
                        <g id="cbc3a3b70311e72d155b5260a83c5fe7" transform="translate(-2 -1.805)">
                            <path id="Path_45247" data-name="Path 45247"
                                d="M48.8,16.719,44.086,14,27.2,23.75a2.4,2.4,0,0,1-2.4,0L7.914,14,3.2,16.719a2.4,2.4,0,0,0,0,4.158L24.8,33.35a2.4,2.4,0,0,0,2.4,0L48.8,20.876a2.4,2.4,0,0,0,0-4.158Z"
                                transform="translate(0 16.134)" fill="#0b80fd" />
                            <path id="Path_45246" data-name="Path 45246"
                                d="M48.8,12.719,44.086,10,27.2,19.75a2.4,2.4,0,0,1-2.4,0L7.914,10,3.2,12.719a2.4,2.4,0,0,0,0,4.158L24.8,29.35a2.4,2.4,0,0,0,2.4,0L48.8,16.876a2.4,2.4,0,0,0,0-4.158Z"
                                transform="translate(0 10.841)" fill="#85bffe" />
                            <path id="Path_45245" data-name="Path 45245"
                                d="M26,31.542a2.4,2.4,0,0,1-1.2-.321L3.2,18.747a2.4,2.4,0,0,1,0-4.158L24.8,2.125a2.41,2.41,0,0,1,2.4,0L48.8,14.589a2.4,2.4,0,0,1,0,4.158L27.2,31.22A2.4,2.4,0,0,1,26,31.542Z"
                                transform="translate(0 0)" fill="#c4e1ff" />
                        </g>
                    </svg>
                </div>

                <div class="mt-3">
                    <p class="fs-14 fw-bold text-dark mb-2">{{translate('Selected')}} <span id="selected_categories"></span> {{translate('Category')}}</p>
                    <span class="fs-12 fw-400 text-gray">{{translate('This operation will apply the selected Refund Request Time(Days) to all chosen categories. You can modify these values individually at any time.')}}</span>
                </div>

                <div class="mt-5">
                    <div>
                        <p class="fs-14 fw-bold text-dark mb-2">{{translate('Refund Request Time(Days)')}}</p>
                      <div class="refund-days-code pl-3 pr-2 border border-2 bg-light border-light rounded-2 has-transition w-100">
                            <div class="d-flex align-items-center justify-between">
                                <div class="flex-grow-1">
                                    <input type="text" value="" name="bulk_refund_days" 
                                    class="form-control px-0 text-blue fs-12 fw-bold bg-transparent border-0" placeholder="" required>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="border-0 bg-transparent" onclick="clearField(this, 'refund_days')">
                                        <svg id="pen-icon" xmlns="http://www.w3.org/2000/svg" width="12"
                                            height="12" viewBox="0 0 12 12">
                                            <path id="_50637d5a60d7f283537860671a4b78a6"
                                                data-name="50637d5a60d7f283537860671a4b78a6"
                                                d="M12.2,6.567l-5.949,5.95a2.49,2.49,0,0,1-1.157.655l-2.282.571a.5.5,0,0,1-.6-.6l.571-2.282A2.49,2.49,0,0,1,3.437,9.7l5.949-5.95Zm1.409-4.226a1.992,1.992,0,0,1,0,2.818l-.705.7L10.09,3.045l.705-.7A1.992,1.992,0,0,1,13.613,2.341Z"
                                                transform="translate(-2.196 -1.758)" fill="#a5a5b8" />
                                        </svg>
                                        <span class="fs-10 fw-400 text-blue">{{translate('Clear')}}</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Tooltips Message -->
                            <div class="refund-days-message bg-dark mt-1 position-absolute py-1 px-2 rounded-1">
                                <span class="fs-12 text-white fw-300">{{translate('Type and press enter to save')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Offcanvas Footer-->
        <div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px"
            id="offcanvas-btn">
            <div class="d-flex justify-content-end footer-btn">
                <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel" onclick="">{{translate('Cancel')}}</button>
                <button type="button" class="d-block fs-14 fw-700 py-10px save" onclick="assignBulkRefundDays()">{{translate('Save')}}</button>
            </div>
        </div>
    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
    <!-- ================ Right Offcanvas End ================ -->
@endsection

@section('script')
    <script type="text/javascript">

        let currentTab = '{{ $active_tab }}';
        var searchTimer;
        let unassigned = 0;

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

        function assignBulkRefundDays() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one item') }}');
                return;
            }
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Refund Days Assign Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to Assign Refund Days the selected categories?') }}');
            $('#impact-message').text('{{ translate('If any Refund Days is already assigned to the selected categories, it will be replaced.') }}');
            $('#conform-yes-btn').attr("onclick", "refund_days_assign()");
            $('.confirmation-icon').addClass('d-none');
            $('#feature-confirm-icon').removeClass('d-none');

        }

        function refund_days_assign(){
            let bulk_refund_days = $('input[name="bulk_refund_days"]').val();
            let data = new FormData($('#sort_categories')[0]);
            data.append('bulk_refund_days', bulk_refund_days);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('categories.bulk-refund-days-assign')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected items Updated successfully') }}');
                        hideBulkActionModal(); 
                        getCategories(currentTab);
                        closeRightcanvas();
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function sort_categories(el)
        {
            $('#sort_categories').submit();
        }
        
        function getCategories(slug, page = 1, unassigned = 0) 
        {
            var status = $('#status').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();

            $('#tab-content').html('<div class="footable-loader mt-5"></div>');

            $.ajax({
                url: `{{ route('refund_categories.filter') }}?page=${page}`,
                method: 'GET',
                data: {
                    status: status,
                    category_status: slug,
                    search: keyword,
                    unassigned: unassigned
                },
                success: function(response) {
                    $('#tab-content').html(response.html);
                    initFooTable();
                }
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


        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        function openRightcanvas() {
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            var data = new FormData($('#sort_categories')[0]);
            var selectedCount = $('#sort_categories input[name="id[]"]:checked').length;
            $('#selected_categories').text(selectedCount);
           
        }
        
        function closeRightcanvas() {
            rightOffcanvas.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');
        }

        function closeOffcanvas() {
            closeRightcanvas();
        }

        if (overlay) {
            overlay.addEventListener('click', closeRightcanvas);
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeRightcanvas();
        });

        $(document).on('keypress', '.refund-days-input', function(e){
            if(e.which === 13){
                e.preventDefault();
                let input = $(this);
                let categoryId = input.data('category-id');
                let fieldName = 'refund_days_code';
                let fieldValue = input.val();
                let fieldLabel = fieldName.replace(/_/g, ' ').toUpperCase();
                if (fieldValue === "" || fieldValue === null) {
                    AIZ.plugins.notify('danger', fieldLabel + ' cannot be empty!');
                    input.focus();
                    return;
                }

                $.ajax({
                    url: "{{route('categories.update-refund-settings')}}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: categoryId,
                        refund_request_time: fieldValue,
                    },
                    success: function(res){
                        AIZ.plugins.notify('success', 'Updated successfully');
                    },
                    error: function(){
                        AIZ.plugins.notify('danger', 'Update failed');
                    }
                });
            }
        });

        function clearField(button, type) {
            const container = button.closest('.d-flex');
            const input = container.querySelector('.refund-days-input');
            const categoryId = input.getAttribute('data-category-id');
            input.value = '';
            input.focus();
        }
        function unassigned_categories() {
            getCategories(currentTab, 1, 1);
        }
    </script>
@endsection