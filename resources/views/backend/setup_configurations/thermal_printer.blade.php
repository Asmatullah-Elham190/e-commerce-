@extends('backend.layouts.app')

@section('content')
<div class="col-lg-8 mx-auto">
    <div class="mb-3 border border-1 border-gray-300 rounded-1 p-4 d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between" style="gap: 18px">
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center" style="gap: 12px">
            <div
                class="w-60px h-60px rounded-1 d-flex align-items-center justify-content-center overflow-hidden bg-soft-danger flex-shrink-0">
                <img src="{{ static_asset('assets/img/business-settings/thermal-printer.svg') }}"
                    alt="Setting Icon">
            </div>
            <div>
                <h6 class="fw-semibold text-dark">{{translate('Thermal Printer')}}</h6>
            <span
                class="fs-12 fw-400 d-block text-gray">{{translate('Configure thermal invoice to ensure accurate and professional order documentation.')}}</span>
            </div>
        </div>
        <a href="{{ route('business_settings.index') }}" class="fs-14 fw-500 text-reset text-blue hov-text-white has-transition bg-soft-blue hov-bg-blue rounded-pill py-2 px-3 flex-shrink-0">
            <i class="las la-angle-left fs-14"></i>
            {{translate('Back to Business Settings')}}
        </a>
    </div>
    <div class="card">

        <div class="card-body">
            <form action="{{ route('thermal.printer.update') }}" method="POST">
                @csrf
                <h6 class="mt-2 mb-4 fs-12">{{ translate('Thermal Printer') }}</h6>
                <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                            <input type="checkbox" name="generate_invoice_for_thermal_printer" value="1"
                                {{ ($thermal_printer['generate_invoice_for_thermal_printer'] ?? 0) == 1 ? 'checked' : '' }}>
                            <span></span>
                        </label>
                        <span class="d-block" style="margin-top: -6px">{{ translate('Generate Invoice for Thermal Printer') }}</span>
                    </div>
                </div>
                <h6 class="mt-4 mb-4 fs-12">{{ translate('Invoice Content Fields for Thermal Printer') }}</h6>
                @php
                    $fields = [
                        'show_logo' => 'Show Logo',
                        'show_tracking_code' => 'Show Tracking Code',
                        'show_platform_contact' => 'Show Platform Contact',
                        'show_seller_contact' => ' Show Seller Contact',
                        'show_sku' => 'Show SKU',
                        'show_product_variation' => 'Show Product Variation',
                        'show_barcode' => 'Show Barcode',
                        'show_qr_code' => 'Show QR Code'
                    ];
                @endphp
                @foreach ($fields as $key => $label)
                    <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="checkbox" name="fields[{{ $key }}]" value="1"
                                    {{ ($thermal_printer['fields'][$key] ?? 0) == 1 ? 'checked' : '' }}>
                                <span></span>
                            </label>
                            <span class="d-block" style="margin-top: -6px">{{ translate($label) }}</span>
                        </div>
                    </div>
                @endforeach
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
@endsection