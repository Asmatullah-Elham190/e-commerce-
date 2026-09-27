@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Custom Script -->
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Google Tag Manager Script') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Header custom script -->
                        <div class="form-group">
                            <label class="col-from-label">{{ translate('Header script - before </head>') }}</label>
                            <input type="hidden" name="types[]" value="header_gtm_script">
                            <textarea name="header_gtm_script" rows="4" class="form-control" placeholder="<script>&#10;...&#10;</script>">{{ get_setting('header_gtm_script') }}</textarea>
                            <small>{{ translate('Write script with <script> tag') }}</small>
                            
                        </div>
                        <!-- Footer custom script -->
                        <div class="form-group">
                            <label class="col-from-label">{{ translate('Footer GTM script - before </body>') }}</label>
                            <input type="hidden" name="types[]" value="footer_gtm_script">
                            <textarea name="footer_gtm_script" rows="4" class="form-control" placeholder="<script>&#10;...&#10;</script>">{{ get_setting('footer_gtm_script') }}</textarea>
                            <small>{{ translate('Write script with <noscript> tag') }}</small>
                        </div>
                        <!-- Update Button -->
                        <div class="mt-4 text-right">
                            <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Google Tag Manager Setup Instructions') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            1. {{ translate('Go to') }} <a href="https://tagmanager.google.com" target="_blank">Google Tag Manager</a> {{ translate('and sign in with your Google account.') }}
                        </li>
                        <li class="list-group-item text-dark">
                            2. {{ translate('Click "Create Account" and fill in:') }}
                            <ul class="mt-1">
                                <li>- {{ translate('Account Name: Your company name') }}</li>
                                <li>- {{ translate('Country: Your country') }}</li>
                                <li>- {{ translate('Container Name: Your website name') }}</li>
                                <li>- {{ translate('Target Platform: Web') }}</li>
                            </ul>
                        </li>
                        <li class="list-group-item text-dark">
                            3. {{ translate('After creating container, you will see the installation code snippet.') }}
                        </li>
                        <li class="list-group-item text-dark">
                            4. {{ translate('Copy the first') }} <strong>&lt;script&gt;</strong> {{ translate('code and paste it in the') }} <strong>"Header script"</strong> {{ translate('field above.') }}
                        </li>
                        <li class="list-group-item text-dark">
                            5. {{ translate('Copy the second') }} <strong>&lt;noscript&gt;</strong> {{ translate('code and paste it in the') }} <strong>"Footer GTM script"</strong> {{ translate('field above.') }}
                        </li>
                    </ul>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>{{ translate('Important:') }}</strong> {{ translate('Make sure to include both scripts. The header script must be placed as high in the <head> as possible, and the footer script immediately after the opening <body> tag.') }}
                    </div>

                    <div class="alert alert-info mt-2 mb-0">
                        <i class="fa fa-lightbulb-o"></i>
                        <strong>{{ translate('Example GTM ID:') }}</strong> {{ translate('GTM-XXXXXXX (appears in your script)') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection