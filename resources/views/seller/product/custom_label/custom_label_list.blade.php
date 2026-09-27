@extends('seller.layouts.app')

@section('panel_content')

    <div class="col-12 col-sm-12 col-lg-12 mx-auto">
        <div class="aiz-titlebar text-left pb-5px">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h3 fw-bold">{{ translate('All Custom Labels') }}</h1>
                </div>
            </div>
        </div>

        <div class="card">
            <!--Nav Tab -->
            <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px">
                <div class="table-tabs-container">
                    <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                        @foreach ($custom_label_tabs as $custom_label_tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                    data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    id="{{ Str::slug($custom_label_tab) }}-tab"
                                    onclick="changeTab(this, '{{ Str::slug($custom_label_tab) }}')" role="tab"
                                    aria-controls="{{ Str::slug($custom_label_tab) }}">
                                    {{ translate($custom_label_tab) }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!--Right Side- Add New Button -->
                <div class="mb-3 mb-md-0">
                    @if (get_setting('seller_can_add_custom_label') != 0)
                        <a href="{{ route('seller.custom_label.create') }}" class="position-relative overflow-hidden add-new-btn">
                            <span
                                class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Custom Label') }}</span>
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

            <!--Card Header (Search) Start-->
            <div class="tab-filter-bar">
                <form class="" id="sort_custom_labels" action="" method="GET">
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
                                <input type="text" class="form-control form-control-sm border-0 px-2 bg-transparent"
                                    id="search_input" name="search" placeholder="{{translate('Search Custom Label ...')}}">
                            </div>
                        </div>


                        <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 px-md-1 rounded-1">
                            <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400"
                                type="button" data-toggle="dropdown">
                                {{ translate('Bulk Action') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item confirm-alert text-danger fs-14 fw-500 hov-bg-light hov-text-blue"
                                    href="javascript:void(0)" onclick="bulkDeleted()">
                                    {{ translate('Delete') }}
                                </a>
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
    </div>

@endsection

@section('modal')
    <!-- confirm trigger Modal -->
    <div id="confirm-trigger-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                            <g id="alert" transform="translate(0.14 1.02)">
                                <path id="Shape"
                                    d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z"
                                    transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd" />

                            </g>
                        </g>
                    </svg>
                    <p class="mt-2 mb-2 fs-16 fw-700" id="confirm_text"></p>
                    <p class="fs-13" id="confirm_detail_text"></p>
                    <a href="javascript:void(0)" id="trigger_btn" data-value="" data-status="" data-clicked=""
                        class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px">
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = '{{ Str::slug($custom_label_tabs[0]) }}';
        var searchTimer;

        $(document).on("change", ".check-all", function () {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function () {
                    this.checked = false;
                });
            }

        });
        function sort_custom_labels(el) {
            $('#sort_custom_labels').submit();
        }

        function single_delete(customLabelId) {
            $.ajax({
                url: "{{ route('seller.custom_label.delete', ':id') }}".replace(':id', customLabelId),
                type: 'GET',
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getCustomLabels(currentTab);
                    }
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_custom_labels')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.bulk-custom-label-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', 'Selected custom labels Deleted successfully');
                        hideBulkActionModal();
                        getCustomLabels(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function bulkDeleted() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one custom label') }}');
                return;
            }

            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected custom labels?') }}');
            $('#conform-yes-btn').attr("onclick", "bulk_delete()");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');

        }

        function singleDelete(customLabelId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected custom label?') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + customLabelId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
        }


        function getCustomLabels(slug, page = 1) {
            var status = $('#status').val();
            var user_id = $('#user_id').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('seller.custom_labels.filter') }}?page=${page}`,
                method: 'GET',
                data: { status: status, custom_label_status: slug, search: keyword },
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
            getCustomLabels(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getCustomLabels(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getCustomLabels(currentTab);
            }, 500);
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getCustomLabels(currentTab, page);
        });

        $('#trigger_btn').on('click', function () {
            const actionType = $(this).attr('data-action-type');

            if (actionType === 'label_status') {  
                update_custom_label_status_toggle();
            }

            $(this).attr('data-clicked', 1);
            $('#confirm-trigger-modal').modal('hide');
        });

    
        $('#confirm-trigger-modal').on('hidden.bs.modal', function () {
            const actionType = $(this).data('action-type');

            if ($('#trigger_btn').attr('data-clicked') == 1) {
                $('#trigger_btn').attr('data-clicked', '');
                $(this).removeData('action-type');
            } else {
                if (actionType === 'label_status') {
                    var id = $('#trigger_btn').attr('data-value');
                    if (id) {
                        var status = $('#trigger_btn').attr('data-status-value') == 1 ? false : true;
                        $('#status_alert_' + id).prop('checked', status);
                    }
                }

                $(this).removeData('action-type');
                $('#trigger_btn').removeAttr('data-action-type data-type data-value data-seller_access');
            }
        });

        $(document).on("change", ".check-all", function () {
            $('.check-one:checkbox').prop('checked', this.checked);
        });

        window.trigger_status_alert = function (el) {
            if ('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                el.checked = !el.checked;
                return;
            }

            var id = el.value;
            var status = el.checked ? 1 : 0;

            var confirm_text = status == 1
                ? "{{ translate('Are you sure you want to activate this Custom Label?') }}"
                : "{{ translate('Are you sure you want to deactivate this Custom Label?') }}";

            var confirm_detail_text = status == 1
                ? "{{ translate('This custom label will be visible and usable.') }}"
                : "{{ translate('This custom label will be hidden and unusable.') }}";

            var confirm_btn_text = status == 1
                ? "{{ translate('Activate') }}"
                : "{{ translate('Deactivate') }}";

            $('#confirm_text').text(confirm_text);
            $('#confirm_detail_text').text(confirm_detail_text);
            $('#trigger_btn')
                .text(confirm_btn_text)
                .attr('data-action-type', 'label_status')
                .attr('data-value', id)
                .attr('data-status-value', status);

            $('#confirm-trigger-modal')
                .data('action-type', 'label_status')
                .modal('show');
        };

        function update_custom_label_status_toggle() {
            var id = $('#trigger_btn').attr('data-value');
            var status = $('#trigger_btn').attr('data-status-value');

            $.post('{{ route('seller.custom_label.status_update') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                status: status
            }, function (data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Custom Label status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            }).fail(function () {
                AIZ.plugins.notify('danger', '{{ translate('Network error') }}');
            });
        }
    </script>
@endsection