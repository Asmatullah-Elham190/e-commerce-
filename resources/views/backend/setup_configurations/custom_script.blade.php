@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Custom Script -->
        <div class="card">
            <div class="card-header">
                <h6 class="fw-600 mb-0">{{ translate('Custom Script') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Header custom script -->
                    <div class="form-group mb-4">
                        <label class="col-from-label">{{ translate('Header custom script - before </head>') }}</label>
                        <input type="hidden" name="types[]" value="header_script">
                        <textarea name="header_script" rows="4" class="form-control" placeholder="<script>&#10;...&#10;</script>">{{ get_setting('header_script') }}</textarea>
                        <small>{{ translate('Write script with <script> tag') }}</small>
                    </div>
                    <!-- Footer custom script -->
                    <div class="form-group">
                        <label class="col-from-label">{{ translate('Footer custom script - before </body>') }}</label>
                        <input type="hidden" name="types[]" value="footer_script">
                        <textarea name="footer_script" rows="4" class="form-control" placeholder="<script>&#10;...&#10;</script>">{{ get_setting('footer_script') }}</textarea>
                        <small>{{ translate('Write script with <script> tag') }}</small>
                    </div>
                    <!-- Update Button -->
                    <div class="mt-4 text-right">
                        <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $('select[name="image_watermark_type"]').on('change', function() {
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