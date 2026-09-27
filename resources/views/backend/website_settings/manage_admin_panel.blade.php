@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('website.dashboard') }}" class="fs-14 fw-500 text-reset hov-text-blue has-transition">
                    <i class="las la-arrow-left fs-14"></i>
                    {{translate('Back to Design Studio Home')}}
                </a>
            </div>
            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Admin Navbar') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST">
                        @csrf

                        <!-- Background Color -->
                        <div class="form-group mb-4">
                            <label class="col-from-label fs-13">{{ translate('Navbar Background Color') }}</label>
                            <input type="hidden" name="types[]" value="navbar_bg_color">
                            <div class="input-group">
                                <input type="text" class="form-control aiz-color-input" name="navbar_bg_color" value="{{ get_setting('navbar_bg_color') }}" 
                                    placeholder="Ex: #e1e1e1">
                                <div class="input-group-append">
                                    <span class="input-group-text p-0">
                                        <input class="aiz-color-picker border-0 size-40px" type="color" value="{{get_setting('navbar_bg_color') }}">
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Text Color -->
                        <div class="form-group mb-4">
                            <label class="col-from-label fs-13">{{ translate('Navbar Text Color') }}</label>
                            <input type="hidden" name="types[]" value="navbar_text_color">
                            <div class="d-flex align-items-center">
                                <!-- Light Option -->
                                <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                                    <input type="radio" name="navbar_text_color" value="white" @checked(get_setting('navbar_text_color') == 'white')>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
                                    </span>
                                </label>
                                <!-- Dark Option -->
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
                                    <input type="radio" name="navbar_text_color" value="black" @checked(get_setting('navbar_text_color') == 'black')>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
                                    </span>
                                </label>
                            </div>
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