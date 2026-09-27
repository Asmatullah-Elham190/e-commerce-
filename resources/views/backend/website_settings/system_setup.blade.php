@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Sytem Settings -->
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{translate('System Setup')}}</h1>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- System Name -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('System Name')}}</label>
                            <input type="hidden" name="types[]" value="site_name">
                            <input type="text" name="site_name" class="form-control"
                                placeholder="{{ translate('System Name') }}" value="{{ get_setting('site_name') }}">
                        </div>
                        <!-- Frontend Website Name -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Frontend Website Name')}}</label>
                            <input type="hidden" name="types[]" value="website_name">
                            <input type="text" name="website_name" class="form-control"
                                placeholder="{{ translate('Website Name') }}" value="{{ get_setting('website_name') }}">
                        </div>
                        <!-- Site Motto -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Site Motto')}}</label>
                            <input type="hidden" name="types[]" value="site_motto">
                            <input type="text" name="site_motto" class="form-control"
                                placeholder="{{ translate('Best eCommerce Website') }}"
                                value="{{  get_setting('site_motto') }}">
                        </div>
                        <!-- Uploaded image format -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('Uploaded image format')}}</label>
                            <input type="hidden" name="types[]" value="uploaded_image_format">
                            <select name="uploaded_image_format" class="form-control aiz-selectpicker"
                                data-live-search="true" data-selected="{{ get_setting('uploaded_image_format') }}">
                                <option value="default">{{ translate('default') }}</option>
                                <option value="png">{{ translate('PNG') }}</option>
                                <option value="jpg">{{ translate('JPEG') }}</option>
                                <option value="webp">{{ translate('WebP') }}</option>
                            </select>
                            <small class="text-muted">{{ translate('"svg and gif" image will not be converted.') }}</small>
                        </div>
                        <!-- Site Icon -->
                        <div class="form-group">
                            <label class="col-from-label">{{ translate('Site Icon') }}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="site_icon">
                                        <input type="hidden" name="site_icon" class="selected-files"
                                            value="{{ get_setting('site_icon') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <small
                                class="text-muted">{{ translate('Minimum dimensions required: 32px width X 32px height.') }}</small>
                        </div>

                        <!-- Update Button -->
                        <div class="mt-4 text-right">
                            <button type="submit"
                                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $('select[name="image_watermark_type"]').on('change', function () {
            let val = $(this).val();
            if (val == 'image') {
                $('#watermark_image').removeClass('d-none');
                $('#watermark_text').addClass('d-none');
            } else {
                $('#watermark_text').removeClass('d-none');
                $('#watermark_image').addClass('d-none');
            }
        });
    </script>
@endsection