@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-10 col-sm-10 col-lg-10 mx-auto">
            <div class="aiz-titlebar text-left pb-5px">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <h1 class="h3 fw-bold">{{ translate('All Attributes') }}</h1>
                    </div>
                </div>
            </div>
            <div class="card">
                <div
                    class="d-flex align-items-center justify-content-between flex-wrap border-bottom  border-light px-25px">
                    <div class="table-tabs-container">
                        <ul class="nav nav-tabs border-0 " id="myTab" role="tablist">
                            @foreach ($attribute_tabs as $attribute_tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-0 pb-15px fs-14 fw-500 {{ $loop->first ? 'active' : '' }}"
                                        data-toggle="tab" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        id="{{ Str::slug($attribute_tab) }}-tab"
                                        onclick="changeTab(this, '{{ Str::slug($attribute_tab) }}')" role="tab"
                                        aria-controls="{{ Str::slug($attribute_tab) }}">
                                        {{ translate($attribute_tab) }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="">
                        @if (auth()->user()->can('add_product_attribute'))
                            <a href="javascript:void(0);" id="add_new_attribute"
                                class="position-relative overflow-hidden add-new-btn">
                                <span
                                    class="position-relative z-2 pr-15px fs-14 fw-500 text-blue label-text">{{ translate('Add New Attribute') }}</span>
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
                    <form class="" id="sort_attributes" action="" method="GET">
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
                                        id="search_input" name="search"
                                        placeholder="{{translate('Search Attributes ...')}}">
                                </div>
                            </div>

                            <div class="dropdown mb-2 mb-md-0 bg-light mt-2 mt-md-0 rounded-1">
                                <button class="btn border dropdown-toggle border-light text-secondary fs-14 fw-400"
                                    type="button" data-toggle="dropdown">
                                    {{ translate('Bulk Action') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @can('delete_product_attribute')
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
    </div>

@endsection

@section('modal')
    <div id="rightOffcanvas" class="right-offcanvas-lg position-fixed top-0 fullscreen bg-white py-20px z-1045"></div>
    <div id="rightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
@endsection

@section('script')
    <script type="text/javascript">
        let currentTab = '{{ Str::slug($attribute_tabs[0]) }}';
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
        function sort_attributes(el) {
            $('#sort_attributes').submit();
        }

        function single_delete(attributeId) {
            $.ajax({
                url: "{{ route('attributes.destroy', ':id') }}".replace(':id', attributeId),
                type: 'GET',
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Selected item deleted successfully') }}');
                        hideBulkActionModal();
                        getAttributes(currentTab);
                    }
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_attributes')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-attribute-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        AIZ.plugins.notify('success', 'Selected attributes Deleted successfully');
                        hideBulkActionModal();
                        getAttributes(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function bulkDeleted() {
            if ($('.check-one:checked').length == 0) {
                AIZ.plugins.notify('danger', '{{ translate('Please select at least one attribute') }}');
                return;
            }

            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected attributes?') }}');
            $('#conform-yes-btn').attr("onclick", "bulk_delete()");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');

        }

        function singleDelete(attributeId) {
            showBulkActionModal();
            $('#confirmation-title').text('{{ translate('Delete Confirmation') }}');
            $('#confirmation-question').text('{{ translate('Are you sure you want to delete the selected attribute?') }}');
            $('#conform-yes-btn').attr("onclick", "single_delete(" + attributeId + ")");
            $('.confirmation-icon').addClass('d-none');
            $('#delete-confirm-icon').removeClass('d-none');
        }


        function getAttributes(slug, page = 1) {
            var status = $('#status').val();
            currentTab = slug;
            var slug = slug.replace(/-/g, '_');
            let keyword = $('#search_input').val();
            $('#tab-content').html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');
            $.ajax({
                url: `{{ route('attributes.filter') }}?page=${page}`,
                method: 'GET',
                data: { status: status, attribute_status: slug, search: keyword },
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
            getAttributes(statusSlug);
        }

        document.addEventListener('DOMContentLoaded', function () {
            getAttributes(currentTab);
        });

        $('#search_input').on('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                getAttributes(currentTab);
            }, 500);
        });
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            getAttributes(currentTab, page);
        });

        const rightOffcanvas = document.getElementById('rightOffcanvas');
        const overlay = document.getElementById('rightOffcanvasOverlay');

        $(document).on('click', '#add_new_attribute', function (e) {
            e.preventDefault();
            openRightcanvas();
        });

        function openRightcanvasShell() {
            rightOffcanvas.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            rightOffcanvas.innerHTML = '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';
        }

        function openRightcanvas() {
            openRightcanvasShell();

            $.ajax({
                type: "POST",
                url: "{{ route('attributes.create') }}",
                data: { _token: AIZ.data.csrf },
                success: function (html) {
                    rightOffcanvas.innerHTML = html;
                },
                error: function () {
                    rightOffcanvas.innerHTML = '<p class="text-danger p-3">{{ translate("Failed to load") }}</p>';
                }
            });
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

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeRightcanvas();
            }
        });

        $(document).on('click', '#store-new-attribute', function () {
            const btn = $(this);
            const name = $('#rightOffcanvas input[name="name"]').val().trim();

            if (!name) {
                AIZ.plugins.notify('warning', 'Please fill name');
                return;
            }

            var attributeValues = [];
            $('#rightOffcanvas input[name="attribute_values[]"]').each(function () {
                if ($(this).val().trim() !== '') {
                    attributeValues.push($(this).val().trim());
                }
            });

            if (attributeValues.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill attribute values") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('attributes.store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    name: name,
                    attribute_values: attributeValues
                },
                success: function (res) {
                    AIZ.plugins.notify('success', 'Attribute has been inserted successfully');
                    closeRightcanvas();
                    getAttributes(currentTab);
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        function edit_attribute(attributeId) {
            openRightcanvasShell();

            $.ajax({
                type: "POST",
                url: "{{ route('attributes.edit') }}",
                data: {
                    _token: AIZ.data.csrf,
                    id: attributeId
                },
                success: function (html) {
                    rightOffcanvas.innerHTML = html;
                },
                error: function () {
                    rightOffcanvas.innerHTML = '<p class="text-danger p-3">{{ translate("Failed to load") }}</p>';
                }
            });
        }

        $(document).on('click', '#update-attribute', function () {
            const btn = $(this);
            const id = $('#edit_attribute_id').val();
            const lang = $('#lang').val();
            const name = $('#rightOffcanvas input[name="name"]').val().trim();

            if (!name) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill name") }}');
                return;
            }

            var attributeValues = [];
            $('#rightOffcanvas input[name="attribute_values[]"]').each(function () {
                if ($(this).val().trim() !== '') {
                    attributeValues.push($(this).val().trim());
                }
            });

            if (attributeValues.length === 0) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill attribute values") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('attributes.update') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    id: id,
                    name: name,
                    lang: lang,
                    attribute_values: attributeValues
                },
                success: function (res) {
                    if (res == 1) {
                        AIZ.plugins.notify('success', '{{ translate("Attribute updated successfully") }}');
                        closeRightcanvas();
                        getAttributes(currentTab);
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.addEventListener("click", function (e) {
                const removeBtn = e.target.closest(".remove-row");
                if (removeBtn) {
                    const rowToRemove = removeBtn.closest(".attribute-row") || removeBtn.closest(".row");
                    if (rowToRemove) {
                        const hiddenIdInput = rowToRemove.querySelector('input[name="attribute_value_ids[]"]');

                        if (hiddenIdInput && hiddenIdInput.value) {
                            let deletedWrapper = document.querySelector("#deleted-attribute-ids");
                            if (!deletedWrapper) {
                                deletedWrapper = document.createElement("div");
                                deletedWrapper.id = "deleted-attribute-ids";
                                document.querySelector("#attribute-wrapper").appendChild(deletedWrapper);
                            }
                            deletedWrapper.insertAdjacentHTML(
                                "beforeend",
                                `<input type="hidden" name="deleted_attribute_value_ids[]" value="${hiddenIdInput.value}">`
                            );
                        }

                        rowToRemove.remove();
                    }
                }
            });
        });

        $(document).on('click', '.attribute-lang-switch', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            let lang = $(this).data('lang');

            $.ajax({
                type: "POST",
                url: "{{ route('attributes.edit') }}",
                data: {
                    _token: AIZ.data.csrf,
                    id: id,
                    lang: lang
                },
                success: function (html) {
                    $('#rightOffcanvas').html(html);
                },
                error: function () {
                    AIZ.plugins.notify('danger', 'Failed to load language');
                }
            });
        });

    </script>
@endsection