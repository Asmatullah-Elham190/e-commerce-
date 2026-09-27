@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Sytem Settings -->
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{translate('Logo & Favicon')}}</h1>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <!-- System Logo - White -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('System Logo - White')}}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="system_logo_white">
                                        <input type="hidden" name="system_logo_white" class="selected-files"
                                            value="{{ get_setting('system_logo_white') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <small
                                class="text-muted">{{ translate('Will be used in admin panel side menu. Minimum dimensions required: 189px width X 31px height.') }}</small>

                        </div>
                        <!-- System Logo - Black -->
                        <div class="form-group mb-4">
                            <label class="col-from-label">{{translate('System Logo - Black')}}</label>
                            <div class="add-product-page-content">
                                <div class="img-upload-container">
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="types[]" value="system_logo_black">
                                        <input type="hidden" name="system_logo_black" class="selected-files"
                                            value="{{ get_setting('system_logo_black') }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <small
                                class="text-muted">{{ translate('Will be used in Admin login page, Seller login page & Delivery Boy login page. Minimum dimensions required: 189px width X 31px height.') }}</small>

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