@extends('backend.layouts.app')

@section('content')
<div class="col-lg-8 mx-auto">
    <div class="mb-3 border border-1 border-gray-300 rounded-1 p-4 d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between" style="gap: 18px">
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center" style="gap: 12px">
            <div
                class="w-60px h-60px rounded-1 d-flex align-items-center justify-content-center overflow-hidden bg-soft-danger flex-shrink-0">
                <img src="{{ static_asset('assets/img/business-settings/shipping-label.svg') }}"
                    alt="Setting Icon">
            </div>
            <div>
                <h6 class="fw-semibold text-dark">{{translate('Shipping Label')}}</h6>
            <span
                class="fs-12 fw-400 d-block text-gray">{{translate('Configure shipping label formats, layout and printing preferences for efficient order packaging and dispatch.')}}</span>
            </div>
        </div>
        <a href="{{ route('business_settings.index') }}" class="fs-14 fw-500 text-reset text-blue hov-text-white has-transition bg-soft-blue hov-bg-blue rounded-pill py-2 px-3 flex-shrink-0">
            <i class="las la-angle-left fs-14"></i>
            {{translate('Back to Business Settings')}}
        </a>
    </div>
    <div class="card">

        <div class="card-body">
            <form action="{{ route('shipping.label.update') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>{{ translate('Label Size Preset') }}</label>
                    <div class="row ml-0">
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="label_size_preset" value="4x6" {{ ($shipping_label['label_size_preset'] ?? '') == '4x6' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('4 x 6 inches') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="label_size_preset" value="4x4" {{ ($shipping_label['label_size_preset'] ?? '') == '4x4' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('4 x 4 inches') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="label_size_preset" value="3x4" {{ ($shipping_label['label_size_preset'] ?? '') == '3x4' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('3 x 4 inches') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="label_size_preset" value="2x3" {{ ($shipping_label['label_size_preset'] ?? '') == '2x3' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('2 x 3 inches') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>{{ translate('Barcode Type') }}</label>
                    <div class="row ml-0">
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="barcode_type" value="code39" {{ ($shipping_label['barcode_type'] ?? '') == 'code39' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('Code 39') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="barcode_type" value="code128" {{ ($shipping_label['barcode_type'] ?? '') == 'code128' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('Code 128') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" name="barcode_type" value="qrcode" {{ ($shipping_label['barcode_type'] ?? '') == 'qrcode' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('QR Code') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>{{ translate('Barcode Encodes') }}</label>
                    <div class="row ml-0">
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" id="barcode_encode" name="barcode_encode" value="order_number" {{ ($shipping_label['barcode_encode'] ?? '') == 'order_number' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('Order Code') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" id="barcode_encode" name="barcode_encode" value="tracking_code" {{ ($shipping_label['barcode_encode'] ?? '') == 'tracking_code' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('Tracking Code') }}</span>
                            </span>
                        </label>
                        <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                            <input type="radio" id="barcode_encode" name="barcode_encode" value="custom_value" {{ ($shipping_label['barcode_encode'] ?? '') == 'custom_value' ? 'checked' : '' }}>
                            <span class="d-flex align-items-center aiz-megabox-elem"
                                style="padding: 0.75rem 1.2rem;">
                                <span class="aiz-rounded-check flex-shrink-0"></span>
                                <span class="flex-grow-1 pl-3">{{ translate('Custom Value') }}</span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="form-group mb-4" id="custom_barcode_value_wrapper"
                    style="display: {{ ($shipping_label['barcode_encode'] ?? '') == 'custom_value' ? 'block' : 'none' }};">
                    <label>{{ translate('Custom Barcode Value') }}</label>
                    <input type="text"
                        class="form-control"
                        name="custom_barcode_value"
                        placeholder="{{ translate('Enter custom barcode value') }}"
                        value="{{ $shipping_label['custom_barcode_value'] ?? '' }}">
                </div>
                <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                            <input type="checkbox" name="show_human_readable_text_below_barcode" value="1"
                                {{ ($shipping_label['show_human_readable_text_below_barcode'] ?? 0) == 1 ? 'checked' : '' }}>
                            <span></span>
                        </label>
                        <span class="d-block" style="margin-top: -6px">{{ translate('Show Human Readable Text Below Barcode') }}</span>
                    </div>
                </div>
                <div class="form-group mb-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                            <input type="checkbox" name="show_qr_code_alongside_barcode" value="1"
                                {{ ($shipping_label['show_qr_code_alongside_barcode'] ?? 0) == 1 ? 'checked' : '' }} {{ ($shipping_label['barcode_type'] ?? '') == 'qrcode' ? 'disabled' : '' }}>
                            <span></span>
                        </label>
                        <span class="d-block" style="margin-top: -6px">{{ translate('Show QR Code Alongside Barcode') }}</span>
                    </div>
                </div>
                <h6 class="mt-4 mb-4 fs-12">{{ translate('Label Content Fields') }}</h6>
                @php
                    $fields = [
                        'sender_name_and_address' => 'Sender Name & Address',
                        'receiver_name_and_address' => 'Receiver Name & Address',
                        'order_number' => 'Order Number',
                        'cod_amount' => 'COD Amount (Cash On Delivery)'
                    ];
                @endphp
                @foreach ($fields as $key => $label)
                    <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="fields[{{ $key }}]" value="1"
                                    {{ ($shipping_label['fields'][$key] ?? 0) == 1 ? 'checked' : '' }}>
                                <span></span>
                            </label>
                            <span class="d-block" style="margin-top: -6px">{{ translate($label) }}</span>
                        </div>
                    </div>
                @endforeach
                <div class="form-group mb-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                            <input type="checkbox" id="custom_footer_toggle" name="fields[custom_footer_text]" value="1"
                                {{ ($shipping_label['fields']['custom_footer_text'] ?? 0) == 1 ? 'checked' : '' }}>
                            <span></span>
                        </label>
                        <span class="d-block" style="margin-top: -6px">{{ translate('Custom Footer Text') }}</span>
                    </div>
                </div>
                <div class="form-group mt-3"
                    id="custom_footer_input_wrapper"
                    style="display: {{ ($shipping_label['fields']['custom_footer_text'] ?? 0) == 1 ? 'block' : 'none' }};">
                    <input type="text"
                        class="form-control"
                        name="custom_footer_text"
                        placeholder="{{ translate('Enter footer text') }}"
                        value="{{ $shipping_label['custom_footer_text'] ?? '' }}">
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="types[]" value="label_logo">
                    <label class="col-form-label">{{ translate('Label Logo') }}</label>
                    <div class="add-product-page-content">
                        <div class="img-upload-container">
                            <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div
                                    class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                    <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                        class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                </div>
                                <input type="hidden" name="label_logo" class="selected-files" value="{{ $shipping_label['label_logo'] ?? '' }}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modal')
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('File Preview') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="min-height: 500px;">
                    <div id="filePreviewContainer" class="text-center"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('change', '[name=country_id]', function () {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).ready(function () {
            var country_id = $('[name=country_id]').val();
            if (country_id) {
                get_states(country_id);
            }
        });

        function get_states(country_id) {
            var savedStateName = "{{ $business_info['state'] ?? '' }}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.get-state') }}",
                type: 'POST',
                data: { country_id: country_id },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj && obj.length > 0) {
                        var select = $('[name="state_id"]');
                        select.html(obj);

                        if (savedStateName) {
                            select.find('option').each(function () {
                                if ($(this).text().trim() === savedStateName.trim()) {
                                    $(this).prop('selected', true);
                                }
                            });
                        }

                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function showFileInModal(fileUrl) {
            const ext = fileUrl.split('.').pop().toLowerCase();
            const container = document.getElementById('filePreviewContainer');
            container.innerHTML = '';

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                const img = document.createElement('img');
                img.src = fileUrl;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '600px';
                container.appendChild(img);
            } else if (ext === 'pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl;
                iframe.style.width = '100%';
                iframe.style.height = '600px';
                iframe.frameBorder = 0;
                container.appendChild(iframe);
            } else {
                container.innerHTML = '<p class="text-danger">Unsupported file format.</p>';
            }

            $('#filePreviewModal').modal('show');
        }

        $(document).on('change', '.preview-input', function () {
            let input = this;
            let previewBox = $($(this).data('preview'));
            let fileName = input.files[0]?.name || '';

            $(this).siblings('.custom-file-name').text(fileName || '{{ translate('Choose file') }}');

            previewBox.html('');

            if (input.files && input.files[0]) {
                let file = input.files[0];
                let fileType = file.type;

                if (fileType.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        previewBox.html(
                            '<img src="' + e.target.result + '" class="preview-img img-fluid" style="max-height:120px;">'
                        );
                    };
                    reader.readAsDataURL(file);
                }
                else if (fileType === 'application/pdf') {
                    previewBox.html(`
                                <div class="pdf-preview border rounded text-center position-relative" 
                                     style="width:120px; height:120px; background:#f8f9fa;">
                                    <i class="las la-file-pdf" style="font-size:60px; color:#e74c3c; opacity:0.7; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);"></i>
                                    <small class="text-muted position-absolute bottom-0 start-0 end-0 px-1 text-truncate">
                                        ${fileName}
                                    </small>
                                </div>
                            `);
                }
            }
        });
    </script>

    @if(get_active_countries()->count() == 1)
        <script>
            $(document).ready(function () {
                get_states({{ get_active_countries()->first()->id }});
            });
        </script>
    @endif
    <script>
        
        $(document).on('change', '#barcode_encode', function () {
            if ($(this).val() === 'custom_value') {
                $('#custom_barcode_value_wrapper').slideDown();
            } else {
                $('#custom_barcode_value_wrapper').slideUp();
            }
        });

        
        $(document).on('change', '#custom_footer_toggle', function () {
            if ($(this).is(':checked')) {
                $('#custom_footer_input_wrapper').slideDown();
            } else {
                $('#custom_footer_input_wrapper').slideUp();
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            function handleQrCodeSelection() {
                var isQrCode = $('input[name="barcode_type"]:checked').val() === 'qrcode';
                var $qrAlongside = $('input[name="show_qr_code_alongside_barcode"]');
                
                if (isQrCode) {
                    $qrAlongside.prop('disabled', true);
                    $qrAlongside.prop('checked', false);
                    $qrAlongside.closest('.form-group').find('.aiz-switch').addClass('opacity-50');
                } else {
                    $qrAlongside.prop('disabled', false);
                    $qrAlongside.closest('.form-group').find('.aiz-switch').removeClass('opacity-50');
                }
            }
            handleQrCodeSelection();
            $('input[name="barcode_type"]').on('change', function() {
                handleQrCodeSelection();
            });
        });
    </script>
@endsection