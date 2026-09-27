@extends('backend.layouts.app')

@section('content')
    @php
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
    @endphp



    <div class="col-12 col-sm-12 col-lg-10 mx-auto">
        <div class="aiz-titlebar text-left pb-5px">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h3 fw-bold">{{ translate('All Brands') }}</h1>
                </div>
            </div>
        </div>
        <div class="card">
            <!--Nav Tab -->
            <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px table-nav-tabs pb-3 pb-xl-0">
                <div class="table-tabs-container flex-grow-1">
                    <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                        @foreach ($brand_tabs as $brand)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}" data-toggle="tab"  role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                id="{{ Str::slug($brand) }}-tab"  onclick="changeTab(this, '{{ Str::slug($brand) }}')" role="tab" aria-controls="{{ Str::slug($brand) }}">
                                {{ translate($brand) }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!--Right Side- Add New Button -->
                <div class="mb-3 mb-md-0">
                    @can('add_brand')
                     <a href="{{ route('brands.create') }}" class="position-relative overflow-hidden add-new-btn">
                        <span class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Brand') }}</span>
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

            <!--Card Header (Search) Start-->
            <div class="tab-filter-bar">
                <form class="" id="sort_brands" action="" method="GET">
                    <div class="card-header row mx-0 mx-md-2 border-0 pb-0 mt-2">
                        
                        <div class="col px-0">
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
                                    id="search_input" name="search"placeholder="{{translate('Search Brands ...')}}">
                            </div>
                        </div>

                        @can('delete_brand')
                            <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 ml-1 ml-md-3 rounded-1">
                                <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400" type="button"
                                    data-toggle="dropdown">
                                    {{ translate('Bulk Action') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                        href="javascript:void(0)" onclick="bulkDeleted()">
                                        {{ translate('Delete') }}</a>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <!-- Dynamic Tab Content -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-content">
                            <!-- AJAX content will load here -->
                        </div>
                    </div>
                </form>
            </div>
            <!--Card Header (Search) End-->
        </div>
    </div>
@endsection


@section('modal')
    <!-- Offcanvas -->
    <div id="rightOffcanvas" class="right-offcanvas-md position-fixed top-0 fullscreen bg-white  py-20px z-1045">  
    </div>
    <!-- Overlay -->
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection

@section('script')
<script type="text/javascript">
    let currentTab = '{{ Str::slug($brand_tabs[0]) }}';
    var searchTimer;

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
    function sort_brands(el){
        $('#sort_brands').submit();
    }
    function single_delete(brandId) {
        if('{{env('DEMO_MODE')}}' == 'On'){
            AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
            hideBulkActionModal();
            return;
        }
        $.ajax({
            url: "{{ route('brands.destroy', ':id') }}".replace(':id', brandId),
            type: 'GET',
            success: function(response) {
                if (response == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                    hideBulkActionModal();
                    getBrands(currentTab);
                }
            }
        });
    }

    function bulk_delete() {
        if('{{env('DEMO_MODE')}}' == 'On'){
            AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
            hideBulkActionModal();
            return;
        }
        var data = new FormData($('#sort_brands')[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('bulk-brands-delete')}}",
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response == 1) {
                    AIZ.plugins.notify('success', 'Selected Brands Deleted successfully');
                    hideBulkActionModal(); 
                    getBrands(currentTab);
                }
            },
            error: function () {
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }

    function bulkDeleted() {
        if ($('.check-one:checked').length == 0) {
            AIZ.plugins.notify('danger', '{{ translate('Please select at least one brand') }}');
            return;
        }
        showBulkActionModal();
        $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
        $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected brands?') }}');
        $('#impact-message').text('{{ translate('Associated products will be affected. Once deleted, the Brands and products will be permanently removed.') }}');
        $('#conform-yes-btn').attr("onclick","bulk_delete()");
        $('.confirmation-icon').addClass('d-none');
        $('#delete-confirm-icon').removeClass('d-none');
        
    }

    function singleDelete(brandId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected Brand?') }}');
            $('#impact-message').text('{{ translate('Associated products will be affected. Once deleted, the Brand and product will be permanently removed.') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + brandId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
           
        }
    
    function getBrands(slug, page = 1) {
        var type = $('#type').val();
        var user_id = $('#user_id').val();
        currentTab = slug;
        var slug = slug.replace(/-/g, '_');
        let keyword = $('#search_input').val();
        $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
        $.ajax({
            url: `{{ route('brands.filter' ) }}?page=${page}`,
            method: 'GET',
            data: { type: type, brand_type: slug, search: keyword },
            success: function(response) {
                $('#tab-content').html(response.html);
                initFooTable();

            },
            error: function() {
                $('#tab-content').html('<div class="text-danger p-4">{{ translate("Failed to load data.") }}</div>');
            }
        });
    }

    function changeTab(button, statusSlug) {
        document.querySelectorAll('#myTab .nav-link').forEach(el => el.classList.remove('active'));
        button.classList.add('active');
        getBrands(statusSlug);
    }

    document.addEventListener('DOMContentLoaded', function() {
        getBrands(currentTab);
    });

     $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getBrands(currentTab);
            }, 500);
        });
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getBrands(currentTab, page);
        });
     // Right Offcanvas JS Start
        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        // Open function
        function openRightcanvas(id) {
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');

            $.ajax({
                type: "GET",
                url: "{{ route('brand_category.show', '') }}/" + id,
                success: function (data) {
                    rightOffcanvas.innerHTML = data;
                },
                error: function () {
                    rightOffcanvas.innerHTML = '<p class="text-danger">{{ translate("Failed to load Brand Details data") }}</p>';
                }
            });
        }
        // Close function
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
        // Optional: close with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeRightcanvas();
        });
    // Right Offcanvas JS End
</script>
@endsection