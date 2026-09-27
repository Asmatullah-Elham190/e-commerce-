@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">{{translate('Google reCAPTCHA Setting')}}</h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('google_recaptcha.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="control-label">{{translate('Google reCAPTCHA')}}</label>
                        </div>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="google_recaptcha" type="checkbox" @if (get_setting('google_recaptcha')==1)
                                    checked
                                    @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="CAPTCHA_KEY">
                        <div class="col-md-4">
                            <label class="control-label">{{translate('Site KEY')}}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="CAPTCHA_KEY" value="{{  env('CAPTCHA_KEY') }}" placeholder="{{ translate('Site KEY') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="RECAPTCHA_SECRET_KEY">
                        <div class="col-md-4">
                            <label class="control-label">{{translate('SECRET KEY')}}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="RECAPTCHA_SECRET_KEY" value="{{  env('RECAPTCHA_SECRET_KEY') }}" placeholder="{{ translate('SECRET KEY') }}" required>
                        </div>
                    </div>


                    <div class="form-group row">
                         <input type="hidden" name="types[]" value="RECAPTCHA_SCORE_THRESHOLD">
                        <label class="col-md-4 col-from-label">{{ translate('Accept V3 Score') }}</label>
                        <div class="col-md-8">
                           <select class="form-control aiz-selectpicker" name="RECAPTCHA_SCORE_THRESHOLD" id="accept-v3-score" data-live-search="true">
                                <option value="">{{ translate('Select Score') }}</option>
                                <option value="0.3" {{ env('RECAPTCHA_SCORE_THRESHOLD') == '0.3' ? 'selected' : '' }}>More than or equal to 0.3</option>
                                <option value="0.5" {{ env('RECAPTCHA_SCORE_THRESHOLD') == '0.5' ? 'selected' : '' }}>More than or equal to 0.5</option>
                                <option value="0.7" {{ env('RECAPTCHA_SCORE_THRESHOLD') == '0.7' ? 'selected' : '' }}>More than or equal to 0.7</option>
                                <option value="0.9" {{ env('RECAPTCHA_SCORE_THRESHOLD') == '0.9' ? 'selected' : '' }}>More than or equal to 0.9</option>
                            </select>
                            <small class="text-muted">{{translate("Google reCAPTCHA v3 returns a score ranging from 0.0 to 1.0 that indicates the likelihood a request is made by a human or a bot.")}}</small>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">How to Interpret the reCAPTCHA V3 Scores</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        1. Score: 0.0 - 0.3 : <strong>Very likely a bot</strong> — Recommended Action: Block the request or require additional verification.
                    </li>
                    <li class="list-group-item">
                        2. Score: 0.3 - 0.5 : <strong>Suspicious activity</strong> — Recommended Action: Might want to require additional verification.
                    </li>
                    <li class="list-group-item">
                        3. Score: 0.5 - 0.7 : <strong>Possibly human</strong> — Recommended Action: Could be legitimate traffic.
                    </li>
                    <li class="list-group-item">
                        4. Score: 0.7 - 0.9 : <strong>Likely human</strong> — Recommended Action: Probably safe to allow.
                    </li>
                    <li class="list-group-item">
                        5. Score: 0.9 - 1.0 : <strong>Very likely human</strong> — Recommended Action: Definitely safe to allow.
                    </li>
                    <li class="list-group-item">
                        6. If Google reCAPTCHA v3 credentials have not yet been created, please register your site by visiting
                        <a href="https://www.google.com/recaptcha/admin/create" target="_blank">this link</a> and complete the setup process.
                    </li>
                </ul>

            </div>
        </div>
    </div>



    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="list-group mb-4">
                    <li class="list-group-item bg-light" aria-current="true">Recaptcha Applicable Pages</li>
                    <li class="list-group-item">
                       <div class="row">
                            @php
                                $settings = [
                                    'recaptcha_admin_login' => 'Admin Login',
                                    'recaptcha_customer_login' => 'Customer Login',
                                    'recaptcha_customer_register' => 'Customer Registration',
                                    'recaptcha_seller_login' => 'Seller Login',
                                    'recaptcha_seller_register' => 'Seller Registration',
                                    'recaptcha_seller_mail_verification' => 'Seller Mail Verification',
                                    'recaptcha_forgot_password' => 'Forgot Password',
                                    'recaptcha_delivery_boy_login' => 'Delivery Boy Login',
                                    'recaptcha_contact_form' => 'Contact Us Form',
                                ]; 
                                if (addon_is_activated('affiliate_system')) {
                                    $settings['recaptcha_affiliate_apply'] = 'Affiliate Application Form';
                                }
                            @endphp

                            @foreach($settings as $key => $label)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                    <div class="p-2 border mt-1 mb-2">
                                        <label class="control-label d-flex">{{ $label }}</label>
                                        <label class="aiz-switch aiz-switch-success">
                                            <input type="checkbox"
                                               onchange="triggerConfirmation(this, '{{ $key }}', '{{ $label }}')"
                                                {{ get_setting($key) == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </li>
                </ul>

            </div>

        </div>
    </div>
</div>
@endsection
@section('modal')
    <!-- confirm Modal -->
    <div id="confirm-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <path d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" fill="#ffc700" />
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700" id="confirmation-message"></p>
                    <div>
                        <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px" onclick="confirmSettingChange()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /.modal -->
@endsection

@section('script')
<script type="text/javascript">

    let pendingElement = null;
    let pendingType = null;

    function triggerConfirmation(el, type, label) {
        pendingElement = el;
        pendingType = type;
        $('#confirm-modal .modal-body p').text(`Are you sure you want to change the Recaptcha setting for "${label}"?`);
        $('#confirm-modal').modal('show');
    }

    function confirmSettingChange() {
        if (pendingElement && pendingType) {
            updateSettings(pendingElement, pendingType);
        }
        $('#confirm-modal').modal('hide');
        // Reset state
        pendingElement = null;
        pendingType = null;
    }


    // Revert on cancel
    $('#confirm-modal').on('hidden.bs.modal', function () {
        if (pendingElement) {
            $(pendingElement).prop('checked', !$(pendingElement).is(':checked'));
            pendingElement = null;
            pendingType = null;
        }
    });

   function updateSettings(el, type) {

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            
            var value = ($(el).is(':checked')) ? 1 : 0;
             
            $.post('{{ route('business_settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        $(document).ready(function () {
            // This is hardcoded from server-side value of google_recaptcha setting
           var isRecaptchaEnabled = @json(get_setting('google_recaptcha') == 1);

            toggleRecaptchaChildren(isRecaptchaEnabled);

            function toggleRecaptchaChildren(isEnabled) {
                $('input[type="checkbox"]').each(function () {
                    if ($(this).attr('onchange')?.includes('triggerConfirmation')) {
                        $(this).prop('disabled', !isEnabled);
                        $(this).closest('.border').css('opacity', isEnabled ? 1 : 0.5); // Optional: Visual cue
                    }
                });
            }
        });

</script>
@endsection