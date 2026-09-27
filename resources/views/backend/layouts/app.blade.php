<!doctype html>
@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <link rel="apple-touch-icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <title>{{ get_setting('website_name') . ' | ' . get_setting('site_motto') }}</title>

    <!-- google font -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">

    <!-- aiz core css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css?v=') }}{{ get_setting('current_version') }}">
    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000,9999) }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=') }}{{ rand(1000,9999) }}">

    <style>
        :root {
            --blue: #3390f3;
            --hov-blue: #1f6dc2;
            --soft-blue: #f1fafd;

            --primary: #009ef7;
            --hov-primary: #008cdd;
            --soft-primary: #f1fafd;
            --secondary: #a1a5b3;
            --soft-secondary: rgba(143, 151, 171, 0.15);
            --success: #19c553;
            --hov-success: #16a846;
            --soft-success:  #e6fff3;
            --info: #8f60ee;
            --hov-info: #714cbd;
            --soft-info: #f4effe;
            --warning: #ffc700;
            --soft-warning: #fff9e3;
            --danger: #F0416C;
            --soft-danger: #fff4f8;
            --dark: #232734;
            --soft-dark: #1b2133;

            --secondary-base: #f1416c;
            --hov-secondary-base: #c73459;
            --soft-secondary-base: rgb(241, 65, 108, 0.15);
        }
        body {
            font-size: 12px;
            font-family: {!! !empty(get_setting('system_font_family')) ? get_setting('system_font_family') : "'Public Sans', sans-serif" !!}, sans-serif;
        }
        /* .bootstrap-select .btn,
        .btn:not(.btn-circle),
        .form-control,
        .input-group-text,
        .custom-file-label, .custom-file-label::after {
            border-radius: 0;
        } */
        .border-gray {
            border-color: #e4e5eb !important;
        }
        .card {
            border-radius: 8px;
            background: #fff;
            border: 1px solid #f1f1f4;
            box-shadow: 0px 6px 14px rgba(35, 39, 52, 0.04);
        }
        .form-control {
            border: 1px solid #e4e5eb;
        }
        .aiz-color-input{
            border-top-left-radius: 4px !important;
            border-bottom-left-radius: 4px !important;
        }

        .menu-search-input::placeholder{
            color: {{ get_setting('navbar_text_color') }} !important;
            opacity: 0.5;
        }

        :root{
            --navbar-text-color: {{ get_setting('navbar_text_color') == 'white' ? '#fff' : '#000' }};
            --navbar-hover-bg: {{ get_setting('navbar_text_color') == 'white' ? 'rgba(255,255,255,0.13)' : 'rgba(0,0,0,0.08)' }};
            --navbar-active-bg: {{ get_setting('navbar_text_color') == 'white' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.1)' }};
            --navbar-after-bg: {{ get_setting('navbar_text_color') == 'white' ? '#cfd4e6' : '#4b5563' }};
            --navbar-search-focus-border: {{ get_setting('navbar_text_color') == 'black' ? '#7c8191' : '#dfe3f0' }};
        }

        .footer-email-input::placeholder{
            color: {{ get_setting('footer_text_color') }} !important;
            opacity: 0.5;
        }

        .header-content-wrapper .nav-menu-list .nav-item .nav-link:hover,
        .header-content-wrapper .add-to-cart:hover{
            color: {{ get_setting('top_header_text_color') }} !important;
            opacity: 0.5;
        }

        .login-nav-item:hover .user-icon-circle{
            border-color: {{ get_setting('top_header_text_color') }} !important;
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.16);
        }

        .login-nav-item:hover .user-icon-circle i{
            color:  {{ get_setting('top_header_text_color') }} !important;
        }

        .aiz-side-nav-wrap #menu-search:focus {
            border: 1px solid var(--navbar-search-focus-border) !important;
        }

        .form-control.file-amount{
            border-top-right-radius: 4px !important;
            border-bottom-right-radius: 4px !important;
        }
    </style>
    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
            saving: '{{ translate('Saving') }}',
            something_went_wrong: '{{translate('Something went wrong!')}}',
            error_occured_while_processing: '{{translate('An error occurred while processing')}}',
            saving_as_draft: '{{translate('Saving As Draft')}}',
        }
    </script>

</head>

<body class="">

    <div class="aiz-main-wrapper">
        @include('backend.inc.admin_sidenav')
        <div class="aiz-content-wrapper bg-white">
            @include('backend.inc.admin_nav')
            <div class="aiz-main-content">
                <div class="px-15px px-lg-25px">
                    @yield('content')
                </div>
                <div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto border-top">
                    <p class="mb-0">&copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}</p>
                </div>
            </div><!-- .aiz-main-content -->
        </div><!-- .aiz-content-wrapper -->
    </div><!-- .aiz-main-wrapper -->

    
    <!-- Bulk Action modal -->
    @include('modals.bulk_action_modal')

    <!-- Offcanvas -->
    <div id="globalRightOffcanvas" class="global-right-offcanvas-lg position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        <!-- content will here -->
    </div>
    <!-- Overlay -->
    <div id="globalRightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>

    @yield('modal')


    <script src="{{ static_asset('assets/js/vendors.js?v=') }}{{ get_setting('current_version') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000,9999) }}"></script>
    <script src="{{ static_asset('assets/js/aiz-form-submission.js?v=') }}{{ rand(1000,9999) }}"></script>

    @yield('script')

    <script type="text/javascript">
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
            @if ($message['message'] == translate('Product has been inserted successfully'))
                var data_type = ['digital', 'physical', 'auction', 'wholesale'];
                data_type.forEach(element => {
                    localStorage.setItem('tempdataproduct_'+element, '{}');
                    localStorage.setItem('tempload_'+element, 'no');
                });
            @endif
        @endforeach

        $('.dropdown-menu a[data-toggle="tab"]').click(function(e) {
            e.stopPropagation()
            $(this).tab('show')
        })

        if ($('#lang-change').length > 0) {
            $('#lang-change a').on('click', function(e) {
                e.preventDefault();

                var locale = $(this).data('flag');

                $.post('{{ route('language.change') }}', {
                    _token: '{{ csrf_token() }}',
                    locale: locale
                }, function(data) {
                    location.reload();
                });
            });
        }

        function menuSearch() {
            var filter, item;
            filter = $("#menu-search").val().toUpperCase();
            items = $("#main-menu").find("a");
            items = items.filter(function(i, item) {
                if ($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item)
                    .attr('href') !== '#') {
                    return item;
                }
            });

            if (filter !== '') {
                $("#main-menu").addClass('d-none');
                $("#search-menu").html('')
                if (items.length > 0) {
                    for (i = 0; i < items.length; i++) {
                        const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
                        const link = $(items[i]).attr('href');
                        $("#search-menu").append(
                            `<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span style="color: {{ get_setting('navbar_text_color') }}">${text}</span></a></li`
                            );
                    }
                } else {
                    $("#search-menu").html(
                        `<li class="aiz-side-nav-item"><span class="text-center d-block" style="color: {{ get_setting('navbar_text_color') }}">{{ translate('Nothing Found') }}</span></li>`
                        );
                }
            } else {
                $("#main-menu").removeClass('d-none');
                $("#search-menu").html('')
            }
        }

        const globalRightOffcanvas = document.getElementById('globalRightOffcanvas');
        const globalOverlay = document.getElementById('globalRightOffcanvasOverlay');

        $(document).on('click', '#add_category', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('category');
        });

        $(document).on('click', '#add_brand', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('brand');
        });

        $(document).on('click', '#add_unit', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('unit');
        });

        $(document).on('click', '#add_warranty', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('warranty');
        });

        $(document).on('click', '#add_flash_sale', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('flash_sale');
        });

        $(document).on('click', '#add_color', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('color');
        });

        $(document).on('click', '#add_attribute', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('attribute');
        });

        $(document).on('click', '#add_note', function (e) {
            e.preventDefault();
            const noteType = $(this).data('note-type') || 'refund';
            openGlobalRightOffcanvas('note', noteType);
        });

        $(document).on('click', '#add_measurement_point', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('measurement_point');
        });

        $(document).on('click', '#view_all_notification', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('all_notification');
        });

        $(document).on('click', '#view_all_chat', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('all_chat');
        });

        function openGlobalRightOffcanvas(type = 'category', extraParam = null) {
            globalRightOffcanvas.classList.add('active');
            globalOverlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            globalRightOffcanvas.innerHTML =
                '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';

            const urls = {
                category: "{{ route('admin_ajax_add_category_modal') }}",
                brand: "{{ route('admin_ajax_add_brand_modal') }}",
                flash_sale: "{{ route('admin_ajax_add_flash_sale_modal') }}",
                color: "{{ route('admin_ajax_add_color_modal') }}",
                attribute: "{{ route('admin_ajax_add_attribute_modal') }}",
                note: "{{ route('admin_ajax_add_note_modal') }}",
                measurement_point: "{{ route('admin_ajax_add_measurement_point_modal') }}",
                unit: "{{ route('admin_ajax_add_unit_modal') }}",
                warranty: "{{ route('admin_ajax_add_warranty_modal') }}",
                all_notification: "{{ route('admin_view_all_notication_modal') }}",
                all_chat: "{{ route('admin_view_all_chat_modal') }}"
            };

            const postData = { _token: AIZ.data.csrf };
            if (type === 'note' && extraParam) {
                postData.type = extraParam;
            }

            $.ajax({
                type: "POST",
                url: urls[type],
                data: postData,
                success: function (html) {
                    globalRightOffcanvas.innerHTML = html;
                    AIZ.plugins.bootstrapSelect('refresh');

                    $('#globalRightOffcanvas .aiz-date-range').each(function() {
                        var $input = $(this);
                        var separator = $input.data('separator') || ' to ';
                        var format = $input.data('format') || 'DD-MM-Y';
                        var timePicker = $input.data('time-picker') || false;
                        var pastDisable = $input.data('past-disable') || false;

                        if ($input.data('daterangepicker')) {
                            $input.data('daterangepicker').remove();
                        }

                        $input.daterangepicker({
                            timePicker: timePicker,
                            autoUpdateInput: false,
                            minDate: pastDisable ? moment() : false,
                            locale: {
                                format: format,
                                separator: separator,
                            }
                        });

                        $input.on('apply.daterangepicker', function(ev, picker) {
                            $(this).val(picker.startDate.format(format) + separator + picker.endDate.format(format));
                        });

                        $input.on('cancel.daterangepicker', function() {
                            $(this).val('');
                        });
                    });
                },
                error: function () {
                    globalRightOffcanvas.innerHTML =
                        '<p class="text-danger p-3">{{ translate("Failed to load") }}</p>';
                }
            });
        }

        function closeglobalRightOffcanvas() {
            globalRightOffcanvas.classList.remove('active');
            globalOverlay.classList.remove('active');
            document.body.classList.remove('body-no-scroll');

            $('#view_all_chat .badge-danger').remove();
        }

        function closeGlobalRightOffcanvas() {
            closeglobalRightOffcanvas();
        }

        if (globalOverlay) {
            globalOverlay.addEventListener('click', closeglobalRightOffcanvas);
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeglobalRightOffcanvas();
            }
        });

        $(document).on('click', '#add-category', function () {
            const btn = $(this);
            const name = $('#name').val();

            if (!name || !name.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill name") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_category_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    name: name,
                    digital: $('input[name="digital"]:checked').val() || 0,
                    parent_id: $('select[name="parent_id"]').val() || '',
                    order_level: $('#order_level').val(),
                    banner: $('input[name="banner"]').val() || '',
                    icon: $('input[name="icon"]').val() || '',
                    cover_image: $('input[name="cover_image"]').val() || '',
                    meta_title: $('input[name="cat_meta_title"]').val() || '',
                    meta_description: $('textarea[name="cat_meta_description"]').val() || '',
                    meta_keywords: $('textarea[name="meta_keywords"]').val() || '',
                    filtering_attributes: $('select[name="filtering_attributes[]"]').val() || [],
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);

                        $('#category_id')
                            .html('<option value="" disabled>{{ translate("Select Main Category") }}</option>' + res.single_options)
                            .val(res.category_id)
                            .selectpicker('refresh')
                            .trigger('change');

                        $('#category_ids')
                            .html(res.multi_options)
                            .selectpicker('refresh');

                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#add-brand', function () {
            const btn = $(this);
            const name = $('input[name="brand_name"]').val();
            
            if (!name || !name.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill name") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_brand_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    name: name,
                    logo: $('input[name="logo"]').val() || '',
                    meta_title: $('input[name="brand_meta_title"]').val() || '',
                    meta_description: $('textarea[name="brand_meta_description"]').val() || '',
                    meta_keywords: $('textarea[name="brand_meta_keywords"]').val() || ''
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        var newOption = new Option(res.brand_name, res.brand_id, true, true);
                        $('#brand_id').append(newOption).selectpicker('refresh');
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#add-unit', function () {
            const btn = $(this);
            const unit_name = $('input[name="unit_name"]').val();
            
            if (!unit_name || !unit_name.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill name") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_unit_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    unit_name: unit_name
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        var newOption = new Option(res.unit_name, res.unit_id, true, true);
                        $('#unit_id').append(newOption).selectpicker('refresh');
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#add-warranty', function () {
            const btn = $(this);

            const warranty_text = $('input[name="warranty_text"]').val();
            
            if (!warranty_text || !warranty_text.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill warranty text") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_warranty_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    warranty_text: warranty_text,
                    warranty_logo: $('input[name="warranty_logo"]').val() || '',
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        var newOption = new Option(res.warranty_text, res.warranty_id, true, true);
                        $('#warranty_id').append(newOption).selectpicker('refresh');
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#add-color', function () {
            const btn = $(this);

            const name = $('input[name="color_name"]').val();
            const code = $('input[name="code"]').val();
            
            if (!name || !name.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill name") }}');
                return;
            }
            
            if (!code || !code.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill code") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_color_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    name: name,
                    code: code
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        
                        var $newOption = $('<option>', {
                            value: res.color_code,
                            selected: true, 
                            'data-content': `<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:${res.color_code}'></span><span>${res.color_name}</span></span>`
                        });
                        $('#colors').append($newOption).selectpicker('refresh');
                        
                        $('#colors').selectpicker('val', 
                            $('#colors').val() 
                                ? [...$('#colors').val(), res.color_code] 
                                : [res.color_code]
                        );
                        
                        update_sku();
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#attribute-add-row-btn', function() {
            const wrapper = document.getElementById('attribute-wrapper');
            const addBtn = document.getElementById('attribute-add-row-btn');
            
            const newRow = document.createElement('div');
            newRow.className = 'row gutters-5 mb-2 attribute-row';
            newRow.innerHTML = `
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter Attribute Value" name="attribute_values[]" maxlength="60">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" id="attribute-remove-row-btn" class="my-1 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger">
                        <i class="las la-times"></i>
                    </button>
                </div>
            `;
            wrapper.insertBefore(newRow, addBtn);
        });

        $(document).on('click', '#attribute-remove-row-btn', function() {
            $(this).closest('.attribute-row').remove();
        });

        $(document).on('click', '#add-attribute', function () {
            const btn = $(this);
            const name = $('#name').val();

            if (!name || !name.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill name") }}');
                return;
            }

            var attributeValues = [];
            $('input[name="attribute_values[]"]').each(function() {
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
                url: "{{ route('admin_ajax_add_attribute_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    name: name,
                    attribute_values: attributeValues
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);

                        if ($('#choice_attributes').length) {
                            var newOption = new Option(res.attribute_name, res.attribute_id, true, true);
                            $('#choice_attributes').append(newOption).selectpicker('refresh');
                            $('#choice_attributes').trigger('change');
                        }

                        if ($('#size_options').length && res.attribute_values && res.attribute_values.length) {
                            res.attribute_values.forEach(function(av) {
                                var opt = new Option(av.value, av.id, true, true);
                                $('#size_options').append(opt);
                            });
                            $('#size_options').selectpicker('refresh');
                            if (typeof size_combination === 'function') {
                                size_combination();
                            }
                        }

                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#add-note', function () {
            const btn = $(this);
            const noteType = $('select[name="note_type"]').val();
            const description = $('textarea[name="note_description"]').val();
            
            if (!description || !description.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill description") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_note_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    note_type: noteType,
                    description: description
                },
                success: function (res) {
                    if (!res.success) return;

                    AIZ.plugins.notify('success', res.message);

                    const wrapperMap = {
                        refund:   '.refundable-notes',
                        warranty: '.warranty-notes',
                        shipping: '.shipping-notes',
                        delivery: '.cash-on-delivery-notes',
                    };
                    const hiddenInputMap = {
                        refund:   'refund_note_id',
                        warranty: 'warranty_note_id',
                        shipping: 'shipping_note_id',
                        delivery: 'delivery_note_id',
                    };

                    const wrapperSelector = wrapperMap[noteType];
                    const hiddenInputId   = hiddenInputMap[noteType];
                    const $wrapper        = $(wrapperSelector);
                    const $carousel       = $wrapper.find('.aiz-carousel');

                    if ($carousel.hasClass('slick-initialized')) {
                        $carousel.slick('unslick');
                    }

                    $carousel.html(res.html);

                    AIZ.plugins.slickCarousel();

                    setTimeout(function () {
                        $wrapper.find('#' + hiddenInputId).val(res.note_id);

                        $carousel.find('[data-note-id]')
                            .removeClass('border-primary')
                            .addClass('border-gray-300');

                        $carousel.find('[data-note-id="' + res.note_id + '"]')
                            .removeClass('border-gray-300')
                            .addClass('border-primary');
                    }, 300);

                    closeglobalRightOffcanvas();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors ?? {};
                        const first = Object.values(errors)[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        function selectNote(el, inputId, className, id) {
            $('#' + inputId).val(id);

            const $carousel = $(el).closest('.aiz-carousel');

            $carousel.find('.' + className)
                .removeClass('border-primary')
                .addClass('border-gray-300');

            $(el)
                .removeClass('border-gray-300')
                .addClass('border-primary');
        }

        $(document).on('click', '#add-flash-sale-confirm', function () {
            const btn = $(this);
            const $offcanvas = $('#globalRightOffcanvas');
            const title = $offcanvas.find('input[name="title"]').val();
            const dateRange = $offcanvas.find('input[name="date_range"]').val();

            if (!title || !title.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill title") }}');
                return;
            }

            if (!dateRange || !dateRange.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please select date range") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ml-2"></span>');
            }

            $.ajax({
                url: '{{ route('admin_ajax_add_flash_sale_store') }}',
                type: 'POST',
                data: {
                    _token: AIZ.data.csrf,
                    title: title,
                    date_range: dateRange,
                    fs_thumbnail_img: $offcanvas.find('input[name="fs_thumbnail_img"]').val() || '',
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        var newOption = new Option(res.flash_sale_title, res.flash_sale_id, true, true);
                        $('#flash_deal').append(newOption).selectpicker('refresh');
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        $(document).on('click', '#add-measurement-point', function () {
            const btn = $(this);
            
            const measurement_point_name = $('input[name="measurement_point_name"]').val();

            if (!measurement_point_name || !measurement_point_name.trim()) {
                AIZ.plugins.notify('warning', '{{ translate("Please fill Name") }}');
                return;
            }

            btn.prop('disabled', true);
            if (!btn.find('.spinner-border').length) {
                btn.append('<span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            }

            $.ajax({
                url: "{{ route('admin_ajax_add_measurement_point_store') }}",
                type: "POST",
                data: {
                    _token: AIZ.data.csrf,
                    measurement_point_name: measurement_point_name,
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        var newOption = new Option(res.measurement_point_name, res.measurement_point_id, true, true);
                        $('#measurement_points').append(newOption).selectpicker('refresh');
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').remove();
                }
            });
        });

        function loadTabContent(url, $container, onSuccess) {
            $container.html('<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>');

            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (html) {
                    $container.html(html);
                    if (typeof onSuccess === 'function') {
                        onSuccess(html);
                    }
                },
                error: function (xhr) {
                    console.error('Tab load failed:', xhr.status, xhr.responseText);
                    $container.html('<p class="text-danger p-3 text-center">{{ translate("Failed to load") }}</p>');
                }
            });
        }

        $(document).on('click', '.js-open-chat', function (e) {
            e.preventDefault();
            var sellerId = $(this).data('seller-id');
            var url = "{{ route('admin_seller_chat.show', ['seller_id' => '__SELLER_ID__']) }}"
                        .replace('__SELLER_ID__', sellerId);

            loadTabContent(url, $('#chat-single-view'));
            $('#chat-single-view').removeClass('d-none').css('display', '');
            $('#chat-list-view').addClass('d-none').css('display', '');

            $('#nav-messages-tab .bg-danger').remove();
            $('#view_all_chat .badge-danger').remove();
        });

        $(document).on('click', '.js-back-to-chat-list', function (e) {
            e.preventDefault();

            loadTabContent('{{ route("admin_seller_chat.list") }}', $('#chat-list-view'));
            $('#chat-list-view').removeClass('d-none').css('display', '');
            $('#chat-single-view').addClass('d-none').css('display', '').empty();
        });

        $(document).on('click', '.js-send-chat-message', function (e) {
            e.preventDefault();
            var btn = $(this);
            var sellerId = btn.data('seller-id');
            var input = $('.js-chat-message-input');
            var msg = input.val().trim();
            if (!msg) return;

            $.ajax({
                url: '{{ route("admin_seller_chat.send_message") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', seller_id: sellerId, message: msg },
                success: function (res) {
                    if (res.success) {
                        $('.js-no-message-placeholder').remove();

                        var messageHtml = `
                            <div class="d-flex flex-column align-items-end justify-content-end">
                                <p class="mb-1 bg-white border border-gray-300 rounded-2 px-10px py-10px d-inline-block">
                                    <span class="fs-13 fw-400 text-reset d-block">${res.message}</span>
                                    <span class="fs-11 fw-400 text-gray d-block mt-1 text-right w-100">${res.time}</span>
                                </p>
                            </div>`;
                        $('.js-chat-messages-body').append(messageHtml);
                        input.val('');

                        var container = $('.js-chat-messages-body').closest('.c-scrollbar-light');
                        container.scrollTop(container[0].scrollHeight);
                    }
                },
                error: function (xhr) {
                    console.error('Message send failed:', xhr.status, xhr.responseText);
                }
            });
        });   
        
        var noticesLoaded = false;
        var requestsLoaded = false;
        var promotionsLoaded = false;
        var presetNoticeLoaded = false;
        var plusLoaded = false;
        var plusNoticeLoaded = false;
        var plusMessageLoaded = false;
        var plusPromotionLoaded = false;
        var pendingSubTab = null;
        var pendingNoticeEditData = null;

        $(document).on('shown.bs.tab', '#nav-notices-tab', function (e) {
            loadTabContent('{{ route("admin_seller_hub.notices") }}', $('#nav-notices'));
        });

        $(document).on('shown.bs.tab', '#nav-requests-tab', function (e) {
            $('#nav-requests-tab .bg-danger').remove();
            loadTabContent('{{ route("admin_seller_hub.requests") }}', $('#nav-requests'));
        });

        $(document).on('shown.bs.tab', '#nav-promotions-tab', function (e) {
            $('#nav-promotions-tab .bg-danger').remove();
            loadTabContent('{{ route("admin_seller_hub.promotions") }}', $('#nav-promotions'));
        });

        $(document).on('shown.bs.tab', '#nav-preset-notice-tab', function (e) {
            loadTabContent('{{ route("admin_seller_hub.preset_notice") }}', $('#nav-preset-notice'));
        });

        $(document).on('shown.bs.tab', '#nav-plus-tab', function (e) {
            loadTabContent('{{ route("admin_seller_hub.plus") }}', $('#nav-plus'), function () {
                if (pendingSubTab) {
                    var sub = pendingSubTab;
                    pendingSubTab = null;
                    activateTab(sub);
                } else {
                    loadPlusMessageContent();
                }
            });
        });

        function loadPlusMessageContent() {
            loadTabContent('{{ route("admin_seller_hub.plus_message") }}', $('#plus-message'), function () {
                if (AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        }

        $(document).on('shown.bs.tab', '#plus-message-tab', function (e) {
            loadPlusMessageContent();
        });

        $(document).on('shown.bs.tab', '#plus-promotion-tab', function (e) {
            loadTabContent('{{ route("admin_seller_hub.plus_promotion") }}', $('#plus-promotion'), function () {
                if (AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        });

        $(document).on('change', '.js-plus-message-audience', function () {
            var isSpecific = $(this).val() === 'specific';
            var $select = $('.js-plus-message-sellers');
            
            $select.prop('disabled', !isSpecific);
            
            if ($select.hasClass('selectpicker') || $select.data('selectpicker')) {
                $select.selectpicker('refresh');
            } else if (AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });

        $(document).on('click', '#seller-hub-post-btn', function (e) {
            e.preventDefault();

            if ($('#plus-message').hasClass('active')) {
                submitPlusMessage();
            } else if ($('#plus-promotion').hasClass('active')) {
                submitPlusPromotion();
            } else if ($('#plus-notice').hasClass('active')) {
                submitPlusNotice();
            }
        });

        function submitPlusMessage() {
            var audience = $('input[name="message_audience"]:checked').val();
            var sellerIds = audience === 'specific' ? $('.js-plus-message-sellers').selectpicker('val') : [];
            var content = $('.js-plus-message-content').val().trim();

            if (!content) {
                AIZ.plugins.notify('warning', '{{ translate("Please write message content") }}');
                return;
            }
            if (audience === 'specific' && (!sellerIds || sellerIds.length === 0)) {
                AIZ.plugins.notify('warning', '{{ translate("Please select at least one seller") }}');
                return;
            }

            var btn = $('#seller-hub-post-btn');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ route("admin_seller_hub.plus_message_store") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    audience: audience,
                    seller_ids: sellerIds,
                    content: content
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    console.error('Post message failed:', xhr.status, xhr.responseText);
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
            });
        }

        $(document).on('change', '.js-promo-audience', function () {
            var isSpecific = $(this).val() === 'specific';
            var $select = $('.js-promo-sellers');
            $select.prop('disabled', !isSpecific);
            if (AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });

        $(document).on('change', '.js-promo-type', function () {
            var isFlashDeal = $(this).val() === 'flash_deals';
            var $select = $('.js-promo-flash-deals');
            $select.prop('disabled', !isFlashDeal);
            if (AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });

        function submitPlusPromotion() {
            var audience = $('input[name="promo_audience"]:checked').val();
            var sellerIds = audience === 'specific' ? $('.js-promo-sellers').selectpicker('val') : [];

            var promoType = $('input[name="promo_type"]:checked').val();
            var flashDealId = promoType === 'flash_deals' ? $('.js-promo-flash-deals').selectpicker('val') : null;
            var message = $('.js-promo-content').val().trim();

            if (!promoType) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a promotion type") }}');
                return;
            }
            if (audience === 'specific' && (!sellerIds || sellerIds.length === 0)) {
                AIZ.plugins.notify('warning', '{{ translate("Please select at least one seller") }}');
                return;
            }
            if (promoType === 'flash_deals' && !flashDealId) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a flash sale") }}');
                return;
            }

            var btn = $('#seller-hub-post-btn');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ route("admin_seller_hub.plus_promotion_store") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    audience: audience,
                    seller_ids: sellerIds,
                    promo_type: promoType,
                    flash_sale_id: flashDealId,
                    message: message,
                },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
            });
        }    

        $(document).on('click', '.js-goto-create-promotion', function (e) {
            e.preventDefault();
            pendingSubTab = 'plus-promotion-tab';
            activateTab('nav-plus-tab');
        }); 
        
        $(document).on('change', '.js-notice-audience', function () {
            var isSpecific = $(this).val() === 'specific';
            var $select = $('.js-notice-sellers');
            $select.prop('disabled', !isSpecific);
            if (AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });

        $(document).on('change', '.js-notice-type', function () {
            var isTemporary = $(this).val() === 'temporary';
            $('.js-notice-datetime').prop('disabled', !isTemporary);
        });

        function submitPlusNotice() {
            var audience = $('input[name="notice_audience"]:checked').val();
            var sellerIds = audience === 'specific' ? $('.js-notice-sellers').selectpicker('val') : [];

            var noticeType = $('input[name="notice_type"]:checked').val();
            var noticeDatetime = noticeType === 'temporary' ? $('.js-notice-datetime').val() : null;
            var message = $('.js-notice-content').val().trim();
            var bgColor = $('input[name="bg_color"]:checked').val();
            var savePreset = $('.js-notice-save-preset').is(':checked') ? 1 : 0;
            var noticeId = $('.js-notice-id').val();
            var isEdit = !!noticeId;

            if (audience === 'specific' && (!sellerIds || sellerIds.length === 0)) {
                AIZ.plugins.notify('warning', '{{ translate("Please select at least one seller") }}');
                return;
            }
            if (noticeType === 'temporary' && !noticeDatetime) {
                AIZ.plugins.notify('warning', '{{ translate("Please select date for temporary notice") }}');
                return;
            }
            if (!message) {
                AIZ.plugins.notify('warning', '{{ translate("Please write content") }}');
                return;
            }
            if (!bgColor) {
                AIZ.plugins.notify('warning', '{{ translate("Please select a background color") }}');
                return;
            }

            var btn = $('#seller-hub-post-btn');
            btn.prop('disabled', true);

            var payload = {
                _token: '{{ csrf_token() }}',
                audience: audience,
                seller_ids: sellerIds,
                notice_type: noticeType,
                notice_datetime: noticeDatetime,
                message: message,
                bg_color: bgColor,
                save_as_preset: savePreset,
            };

            var url = '{{ route("admin_seller_hub.plus_notice_store") }}';
            if (isEdit) {
                payload.notice_id = noticeId;
                url = '{{ route("admin_seller_hub.plus_notice_update") }}';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: payload,
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        resetNoticeForm();
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const first = Object.values(xhr.responseJSON?.errors ?? {})[0]?.[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
            });
        }

        $(document).on('click', '.js-goto-create-notice', function (e) {
            e.preventDefault();
            pendingNoticeEditData = null;
            pendingSubTab = 'plus-notice-tab';
            activateTab('nav-plus-tab');
        });

        $(document).on('click', '.js-goto-preset-notice', function (e) {
            e.preventDefault();
            activateTab('nav-preset-notice-tab');
        });

        function applyNoticeEditData() {
            if (!pendingNoticeEditData) return;
            var data = pendingNoticeEditData;
            pendingNoticeEditData = null;

            $('.js-notice-id').val(data.id || '');

            if (data.audience === 'all') {
                $('#notices-all-seller').prop('checked', true).trigger('change');
            } else {
                $('#notices-seller-form').prop('checked', true).trigger('change');
                $('.js-notice-sellers').selectpicker('val', data.seller_ids);
            }

            var $typeRadio = $('input[name="notice_type"][value="' + data.notice_type + '"]');
            if ($typeRadio.length) {
                $typeRadio.prop('checked', true).trigger('change');
            }
            if (data.notice_type === 'temporary') {
                $('.js-notice-datetime').val(data.notice_datetime);
            }

            $('.js-notice-content').val(data.message);

            $('input[name="bg_color"]').each(function () {
                $(this).prop('checked', $(this).val() === data.bg_color);
            });

            $('.js-notice-save-preset').prop('checked', data.save_as_preset);

            $('#seller-hub-post-btn').text(data.id ? '{{ translate("Update") }}' : '{{ translate("Post") }}');
        }

        function resetNoticeForm() {
            $('.js-notice-id').val('');
            $('.js-notice-content').val('');
            $('#seller-hub-post-btn').text('{{ translate("Post") }}');
        }

        $(document).on('change', '.js-preset-notice-status', function () {
            var $checkbox = $(this);
            var noticeId = $checkbox.data('notice-id');
            var status = $checkbox.is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("admin_seller_hub.preset_notice_toggle_status") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', notice_id: noticeId, status: status },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                    }
                },
                error: function () {
                    $checkbox.prop('checked', !status);
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        });

        $(document).on('click', '.js-preset-notice-delete', function (e) {
            e.preventDefault();
            var noticeId = $(this).data('notice-id');
            var $item = $(this).closest('.border-bottom-dashed');

            $.ajax({
                url: '{{ route("admin_seller_hub.preset_notice_delete") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', notice_id: noticeId },
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        $item.remove();
                    }
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        });

        $(document).on('click', '.js-preset-notice-edit', function (e) {
            e.preventDefault();
            var noticeId = $(this).data('notice-id');

            $.ajax({
                url: '{{ route("admin_seller_hub.preset_notice_edit") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', notice_id: noticeId },
                success: function (res) {
                    if (!res.success) return;
                    pendingNoticeEditData = res.notice;
                    pendingSubTab = 'plus-notice-tab';
                    activateTab('nav-plus-tab');
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        });  

        function activateTab(tabId) {
            var $tab = $('#' + tabId);
            if ($tab.hasClass('active')) {
                $tab.trigger('shown.bs.tab');
            } else {
                $tab.tab('show');
            }
        }

        $(document).on('shown.bs.tab', '#plus-notice-tab', function (e) {
            loadTabContent('{{ route("admin_seller_hub.plus_notice") }}', $('#plus-notice'), function () {
                if (AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
                applyNoticeEditData();
            });
        });

        $(document).on('click', '.js-open-message-from-activity', function (e) {
            e.preventDefault();
            var sellerId = $(this).data('seller-id');

            activateTab('nav-messages-tab');

            var url = "{{ route('admin_seller_chat.show', ['seller_id' => '__SELLER_ID__']) }}"
                        .replace('__SELLER_ID__', sellerId);

            loadTabContent(url, $('#chat-single-view'));
            $('#chat-single-view').removeClass('d-none').css('display', '');
            $('#chat-list-view').addClass('d-none').css('display', '');

            $('#nav-messages-tab .bg-danger').remove();
            $('#view_all_chat .badge-danger').remove();
        });

        $(document).on('click', '.js-preset-notice-postagain', function (e) {
            e.preventDefault();
            var noticeId = $(this).data('notice-id');

            $.ajax({
                url: '{{ route("admin_seller_hub.preset_notice_edit") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', notice_id: noticeId },
                success: function (res) {
                    if (!res.success) return;
                    pendingNoticeEditData = res.notice;
                    pendingNoticeEditData.id = null;
                    pendingSubTab = 'plus-notice-tab';
                    activateTab('nav-plus-tab');
                },
                error: function () {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            });
        });

    </script>
</body>

</html>
