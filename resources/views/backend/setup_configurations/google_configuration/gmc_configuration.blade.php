@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Google Merchant Center Setting')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('google_merchant_center.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="GOOGLE_MERCHANT_ID">
                            <label class="col-from-label">{{translate('Merchant ID')}}</label>
                            <input type="text" class="form-control" name="GOOGLE_MERCHANT_ID" value="{{  env('GOOGLE_MERCHANT_ID') }}" placeholder="123456789" required>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Google Merchant Center (GMC) Setup Instructions') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            1. {{ translate('Go to') }} 
                            <a href="https://console.cloud.google.com" target="_blank">Google Cloud Console</a> 
                            {{ translate('and create or select a project.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            2. {{ translate('Navigate to APIs & Services → Library and enable') }} 
                            <strong>Content API for Shopping</strong>.
                        </li>

                        <li class="list-group-item text-dark">
                            3. {{ translate('Go to APIs & Services → Credentials → Create Credentials → Service Account.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            4. {{ translate('Give your service account a name (e.g.,') }} 
                            <strong>my-ecommerce-connection</strong>).
                        </li>

                        <li class="list-group-item text-dark">
                            5. {{ translate('Click CREATE AND CONTINUE, then click CONTINUE (skip role selection), then click DONE.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            6. {{ translate('From the service account list, click on your newly created account.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            7. {{ translate('Go to KEYS tab → ADD KEY → Create New Key → Select JSON → CREATE.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            8. {{ translate('A JSON file will download automatically.') }}
                            <strong class="text-danger">{{ translate('Keep this file secure and never share it.') }}</strong>
                        </li>

                        <li class="list-group-item text-dark">
                            9. {{ translate('Rename the downloaded JSON file to') }} 
                            <strong>google-merchant-credentials.json</strong>.
                        </li>

                        <li class="list-group-item text-dark">
                            10. {{ translate('Upload this file manually to the following path in your server or hosting panel:') }}  
                            <br>
                            <code>storage/app/google-merchant/google-merchant-credentials.json</code>
                        </li>

                        <li class="list-group-item text-dark">
                            11. {{ translate('Now go to') }} 
                            <a href="https://merchants.google.com" target="_blank">Google Merchant Center</a> 
                            {{ translate('and log in to your account.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            12. {{ translate('Find out Settings, then go to') }} 
                            <strong>Access and services</strong>.
                        </li>

                        <li class="list-group-item text-dark">
                            13. {{ translate('Click') }} 
                            <strong>+ Add user/people</strong>.
                        </li>

                        <li class="list-group-item text-dark">
                            14. {{ translate('Open your downloaded JSON file with Notepad, find the') }} 
                            <strong>"client_email"</strong> 
                            {{ translate('field. Copy that email address (looks like:') }} 
                            <code>something@project-name.iam.gserviceaccount.com</code>).
                        </li>

                        <li class="list-group-item text-dark">
                            15. {{ translate('Paste this email in the Add user popup, select') }} 
                            <strong>Admin</strong> {{ translate('access, and click') }} <strong>Add user</strong>.
                        </li>

                    </ul>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fa fa-info-circle"></i>
                        <strong>{{ translate('Note:') }}</strong>
                        {{ translate('The file name must be exactly') }}
                        <strong>google-merchant-credentials.json</strong>
                        {{ translate('and must be placed inside') }}
                        <code>storage/app/google-merchant/</code>.
                        {{ translate('After setup, you can push products from the product list page using the "Push to GMC" button.') }}
                    </div>
                    
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>{{ translate('Important:') }}</strong>
                        {{ translate('Make sure your website is live (not localhost) before pushing products to GMC. Google cannot access localhost URLs.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection