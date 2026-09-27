@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-10 col-xl-8 mx-auto">
        <div class="mb-3 border border-1 border-gray-300 rounded-1 p-4 d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between"
            style="gap: 18px">
            <div class="d-flex flex-wrap flex-md-nowrap align-items-center" style="gap: 12px">
                <div
                    class="w-60px h-60px rounded-1 d-flex align-items-center justify-content-center overflow-hidden bg-soft-blue flex-shrink-0">
                    <img src="{{ static_asset('assets/img/business-settings/general-settings.svg') }}" alt="Setting Icon">
                </div>
                <div>
                    <h6 class="fw-semibold text-dark">{{translate('General Settings')}}</h6>
                    <span
                        class="fs-12 fw-400 d-block text-gray">{{translate('Manage core business information, store identity and essential configurations that define how your store operates.')}}</span>
                </div>
            </div>
            <a href="{{ route('business_settings.index') }}"
                class="fs-14 fw-500 text-reset text-blue hov-text-white has-transition bg-soft-blue hov-bg-blue rounded-pill py-2 px-3 flex-shrink-0">
                <i class="las la-angle-left fs-14"></i>
                {{translate('Back to Business Settings')}}
            </a>
        </div>
        <div class="card">
            <div class="card-body">

                <form action="{{ route('business_info.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12 d-block">
                            <label>
                                {{ translate('Company Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="company_name"
                                placeholder="{{ translate('Company Name') }}"
                                value="{{ $business_info['company_name'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 d-block">
                            <label>
                                {{ translate('Phone') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" name="phone" placeholder="{{ translate('Phone') }}"
                                value="{{ $business_info['phone'] ?? '' }}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 d-block">
                            <label>
                                {{ translate('Email') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" name="email" placeholder="{{ translate('Email') }}"
                                value="{{ $business_info['email'] ?? '' }}" required>
                        </div>
                    </div>
                    @if (get_active_countries()->count() > 1)
                        <div class="row mb-3">
                            <div class="col-12 d-block">
                                <label>{{ translate('Country') }} <span class="text-danger">*</span></label>
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                                    <option value="">{{ translate('Select your country') }}</option>
                                    @foreach (\App\Models\Country::where('status', 1)->get() as $country)
                                        <option value="{{ $country->id }}" @if(isset($business_info['country']) && $business_info['country'] == $country->name) selected @endif>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @elseif(get_active_countries()->count() == 1)
                        <input type="hidden" name="country_id" value="{{ get_active_countries()->first()->id }}">
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 d-block">
                            <label>{{ translate('State') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" data-live-search="true" name="state_id"
                                required></select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 d-block">
                            <label for="address">{{ translate('Address') }}</label>
                            <textarea placeholder="{{ translate('Type Address') }}" name="address" required
                                class="form-control" rows="3">{{ $business_info['address'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 d-block">
                            <label for="postal_code">{{ translate('Postal Code') }}</label>
                            <input type="text" placeholder="{{ translate('Postal Code') }}" name="postal_code" required
                                class="form-control" value="{{ $business_info['postal_code'] ?? '' }}">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 d-block">
                            <label>{{translate('System Timezone')}}</label>
                            <select name="timezone" class="form-control aiz-selectpicker" data-live-search="true">
                                @foreach (timezones() as $key => $value)
                                    <option value="{{ $value }}" @if (app_timezone() == $value) selected @endif>{{ $key }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 d-block">
                            <label>{{ translate('VAT / TIN / BIN Number') }}</label>

                            <input type="text" class="form-control" name="certificate_number"
                                placeholder="{{ translate('VAT / TIN / BIN Number') }}"
                                value="{{ $business_info['certificate_number'] ?? '' }}">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12 d-block">
                            <label>
                                {{ translate('Reg Certificate / Trade License / Sale Tax Permit') }}
                                @if (!isset($business_info['certificate']) || empty($business_info['certificate']))
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <div class="d-flex align-items-center">
                                <div class="custom-file mr-3">
                                    <label class="custom-file-label">
                                        <input type="file" class="custom-file-input preview-input"
                                            data-preview="#certificate_preview" name="certificate" id="certificate"
                                            accept=".jpg,.jpeg,.png,.bmp,application/pdf">
                                        <span class="custom-file-name">{{ translate('Choose file') }}</span>
                                    </label>
                                </div>
                                <div class="flex-shrink-0">
                                    <div id="certificate_preview"></div>
                                    @if (isset($business_info['certificate']) && !empty($business_info['certificate']))
                                        <div class="text-right">
                                            <a onclick="showFileInModal('{{ my_asset($business_info['certificate']) }}')"
                                                class="btn btn-sm btn-info text-white py-2">
                                                {{ translate('View Current') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (addon_is_activated('gst_system'))
                        <div class="row mb-3">
                            <div class="col-12 d-block">
                                <label>
                                    {{ translate('GSTIN Number') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="gstin_number"
                                    placeholder="{{ translate('GSTIN Number') }}" value="{{ $business_info['gstin'] ?? '' }}"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12 d-block">
                                <label>
                                    {{ translate('GSTIN Certificate') }}
                                    @if (!isset($business_info['gstin_certificate']) || empty($business_info['gstin_certificate']))
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="d-flex align-items-center">
                                    <div class="custom-file mr-3">
                                        <label class="custom-file-label">
                                            <input type="file" class="custom-file-input preview-input"
                                                data-preview="#gst_preview" name="gstin_certificate" id="gstin_certificate"
                                                accept=".jpg,.jpeg,.png,.bmp,application/pdf">
                                            <span class="custom-file-name">{{ translate('Choose file') }}</span>
                                        </label>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div id="gst_preview"></div>
                                        @if (isset($business_info['gstin_certificate']) && !empty($business_info['gstin_certificate']))
                                            <div class="text-right">
                                                <a onclick="showFileInModal('{{ my_asset($business_info['gstin_certificate']) }}')"
                                                    class="btn btn-sm btn-info text-white py-2">
                                                    {{ translate('View Current') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-12 d-block">
                            <input type="hidden" name="types[]" value="shop_logo">
                            <label class="col-form-label">{{ translate('Inhouse Shop Logo') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="shop_logo" class="selected-files"
                                            value="{{ $business_info['shop_logo'] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">
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