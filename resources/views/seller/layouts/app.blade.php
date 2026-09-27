{{-- app blade --}}
<!doctype html>
@if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
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
	<title>{{ get_setting('website_name').' | '.get_setting('site_motto') }}</title>

	<!-- google font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

	<!-- aiz core css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css?v=') }}{{ get_setting('current_version') }}">
    @if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
	<link rel="stylesheet" href="{{ static_asset('assets/css/aiz-seller.css?v=') }}{{ get_setting('current_version') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=') }}{{ get_setting('current_version') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/seller-custom-style.css?v=') }}{{ get_setting('current_version') }}">

    <style>
        body {
            font-size: 12px;
            font-family: {!! !empty(get_setting('system_font_family')) ? get_setting('system_font_family') : "'Public Sans', sans-serif" !!}, sans-serif;
        }
        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }
        .pac-container{
            z-index: 100000;
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

        :root{
            --navbar-text-color: {{ Auth::user()->shop->navbar_text_color == 'black' ? '#000' : '#fff' }};
            --navbar-hover-bg: {{ Auth::user()->shop->navbar_text_color == 'black' ? 'rgba(0,0,0,0.08)' : 'rgba(255,255,255,0.13)' }};
            --navbar-active-bg: {{ Auth::user()->shop->navbar_text_color == 'black' ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.3)' }};
            --navbar-after-bg: {{ Auth::user()->shop->navbar_text_color == 'black' ? '#4b5563' : '#cfd4e6' }};
            --navbar-search-focus-border: {{ Auth::user()->shop->navbar_text_color == 'black' ? '#7c8191' : '#dfe3f0' }};
        }


        .seller-search-menu-placeholder::placeholder{
            color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }} !important;
            opacity: 0.5;
        }

        .login-nav-item:hover .user-icon-circle i{
            color:  {{ get_setting('top_header_text_color') }} !important;
        }

        .seller-sidenav-search:focus-within{
            border: 1px solid var(--navbar-search-focus-border) !important;
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
        @include('seller.inc.seller_sidenav')
		<div class="aiz-content-wrapper">
            @include('seller.inc.seller_nav')
			<div class="aiz-main-content">
				<div class="px-15px px-lg-25px">
                    @yield('panel_content')
				</div>
				<div class="text-center py-3 px-15px px-lg-25px mt-auto border-sm-top " style="background-color: rgba(91, 52, 108, 0.1);">

                    <div class="d-flex justify-content-center flex-wrap">
                        <a href="{{ route('home') }}/seller-legal-notice" target="_blank" class="btn btn-link p-0 text-decoration-none mr-3 fw-700 fs-12 hov-text-info">
                            {{ translate('Legal Notice') }}
                        </a>
                        <a href="{{ route('home') }}/seller-withdrawal-policy" target="_blank" class="btn btn-link p-0 text-decoration-none mr-3 fw-700 fs-12 hov-text-info">
                            {{ translate('Right of Withdrawal') }}
                        </a>
                        <a href="{{ route('home') }}/seller-terms-and-conditions" target="_blank" class="btn btn-link p-0 text-decoration-none mr-3 fw-700 fs-12 hov-text-info">
                            {{ translate('Terms & Conditions') }}
                        </a>
                        <a href="{{ route('home') }}/seller-policy" target="_blank" class="btn btn-link p-0 text-decoration-none mr-3 fw-700 fs-12 hov-text-info">
                            {{ translate('Seller Privacy Policy') }}
                        </a>
                    </div>

                    <p class="mb-0 mt-2 fs-11">
                        &copy; {{ get_setting('site_name') }} v{{ get_setting('current_version') }}
                    </p>

                </div>
			</div><!-- .aiz-main-content -->
		</div><!-- .aiz-content-wrapper -->
	</div><!-- .aiz-main-wrapper -->

    @include('modals.bulk_action_modal')

    <!-- Offcanvas -->
    <div id="globalRightOffcanvas" class="global-right-offcanvas-lg position-fixed top-0 fullscreen bg-white  py-20px z-1045">
        <!-- content will here -->
    </div>
    <!-- Overlay -->
    <div id="globalRightOffcanvasOverlay" class="position-fixed top-0 left-0 h-100 w-100"></div>
    
    @yield('modal')


	<script src="{{ static_asset('assets/js/vendors.js') }}" ></script>
	<script src="{{ static_asset('assets/js/aiz-core.js') }}" ></script>
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
                $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                    location.reload();
                });

            });
        }
        function menuSearch(){
			var filter, item;
			filter = $("#menu-search").val().toUpperCase();
			items = $("#main-menu").find("a");
			items = items.filter(function(i,item){
				if($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item).attr('href') !== '#'){
					return item;
				}
			});

			if(filter !== ''){
				$("#main-menu").addClass('d-none');
				$("#search-menu").html('')
				if(items.length > 0){
					for (i = 0; i < items.length; i++) {
						const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
						const link = $(items[i]).attr('href');
						 $("#search-menu").append(`<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">${text}</span></a></li`);
					}
				}else{
					$("#search-menu").html(`<li class="aiz-side-nav-item"><span	class="text-center d-block" style="color: {{ Auth::user()->shop->navbar_text_color ?? 'white' }}">{{ translate('Nothing Found') }}</span></li>`);
				}
			}else{
				$("#main-menu").removeClass('d-none');
				$("#search-menu").html('')
			}
        }

        const globalRightOffcanvas = document.getElementById('globalRightOffcanvas');
        const globalOverlay = document.getElementById('globalRightOffcanvasOverlay');

        $(document).on('click', '#add_note', function (e) {
            e.preventDefault();
            const noteType = $(this).data('note-type') || 'refund';
            openGlobalRightOffcanvas('note', noteType);
        });

        $(document).on('click', '#view_all_chat', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('all_chat');
        });

        function openGlobalRightOffcanvas(type = 'note', extraParam = null, onLoaded = null) {
            globalRightOffcanvas.classList.add('active');
            globalOverlay.classList.add('active');
            document.body.classList.add('body-no-scroll');
            globalRightOffcanvas.innerHTML =
                '<div class="footable-loader mt-5"><span class="fooicon fooicon-loader"></span></div>';

            const urls = {
                note: "{{ route('seller.ajax_add_note_modal') }}",
                all_chat: "{{ route('seller.admin_view_all_chat_modal') }}"
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

                    if (typeof onLoaded === 'function') {
                        onLoaded();
                    }
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
                url: "{{ route('seller.ajax_add_note_store') }}",
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

        $(document).on('shown.bs.tab', '#nav-messages-tab', function (e) {
            loadTabContent('{{ route("seller.admin_seller_chat.show") }}', $('#chat-single-view'));
        });

        $(document).on('click', '.js-send-chat-message', function (e) {
            e.preventDefault();
            var btn = $(this);
            var input = $('.js-chat-message-input');
            var msg = input.val().trim();
            if (!msg) return;

            $.ajax({
                url: '{{ route("seller.admin_seller_chat.send_message") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', message: msg },
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
        var plusLoaded = false;
        var pendingSubTab = null;

        $(document).on('shown.bs.tab', '#nav-notices-tab', function (e) {
            loadTabContent('{{ route("seller.admin_seller_hub.notices") }}', $('#nav-notices'));
        });

        $(document).on('shown.bs.tab', '#nav-requests-tab', function (e) {
            loadTabContent('{{ route("seller.admin_seller_hub.requests") }}', $('#nav-requests'));
        });

        $(document).on('shown.bs.tab', '#nav-promotions-tab', function (e) {
            loadTabContent('{{ route("seller.admin_seller_hub.promotions") }}', $('#nav-promotions'));
        });

        $(document).on('shown.bs.tab', '#nav-plus-tab', function (e) {
            loadTabContent('{{ route("seller.admin_seller_hub.plus") }}', $('#nav-plus'), function () {
                if (AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
                if (pendingSubTab) {
                    var sub = pendingSubTab;
                    pendingSubTab = null;
                    activateTab(sub);
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

        $(document).on('click', '.js-request-submit-btn', function (e) {
            e.preventDefault();
            var btn = $(this);

            var selectedItem = $('.js-request-items option:selected').val();

            if (!selectedItem) {
                AIZ.plugins.notify('warning', '{{ translate("Please select an item") }}');
                return;
            }

            var itemName = ($('.js-request-item-name').val() || '').trim();
            if (itemName === '') {
                AIZ.plugins.notify('warning', '{{ translate("Please fill item name") }}');
                return;
            }

            var fieldMap = {
                category:          'category_name',
                brand:              'brand_name',
                color:              'color_name',
                attribute:          'attribute_name',
                unit:               'unit_name',
                measurement_point:  'measurement_point_name',
                warranty:           'warranty_name',
            };

            var field = fieldMap[selectedItem];

            var payload = {
                _token: AIZ.data.csrf,
                items: [selectedItem],
                message: $('.js-request-message').val()
            };

            if (field) {
                payload[field] = itemName;
            }

            btn.prop('disabled', true);

            $.ajax({
                url: '{{ route("seller.admin_seller_hub.request_store") }}',
                type: 'POST',
                data: payload,
                success: function (res) {
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        closeglobalRightOffcanvas();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON?.errors ?? {};
                        var first = Object.values(errors)[0];
                        AIZ.plugins.notify('danger', first || '{{ translate("Validation failed") }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.js-goto-messages', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('all_chat', null, function () {
                activateTab('nav-messages-tab');
            });
        });

        $(document).on('shown.bs.tab', '#nav-messages-tab', function (e) {
            $('#nav-messages-tab .bg-danger').remove();
            loadTabContent('{{ route("seller.admin_seller_chat.show") }}', $('#chat-single-view'));
        });

        $(document).on('click', '.js-goto-create-request', function (e) {
            e.preventDefault();
            activateTab('nav-plus-tab');
        });

        $(document).on('click', '.js-notice-board-view-all', function (e) {
            e.preventDefault();
            openGlobalRightOffcanvas('all_chat');
        });
    </script>

</body>
</html>
