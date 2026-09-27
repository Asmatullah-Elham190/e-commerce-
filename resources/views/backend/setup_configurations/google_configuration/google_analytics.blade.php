@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Google Analytics Setting')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('google_analytics.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex align-items-center mb-1">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="google_analytics" type="checkbox" @if (get_setting('google_analytics') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                            <label class="ml-2 mb-0">{{translate('Google Analytics')}}</label>
                        </div>
                        
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="TRACKING_ID">
                            <label class="col-from-label">{{translate('Tracking ID')}}</label>
                            <input type="text" class="form-control" name="TRACKING_ID" value="{{  env('TRACKING_ID') }}" placeholder="G-XXXXXXXX" required>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="types[]" value="ANALYTICS_PROPERTY_ID">
                            <label class="col-from-label">{{translate('Analytics Property ID')}}</label>
                            <input type="text" class="form-control" name="ANALYTICS_PROPERTY_ID" value="{{  env('ANALYTICS_PROPERTY_ID') }}" placeholder="123456789" required>
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
                    <h5 class="mb-0 h6">{{ translate('Google Analytics Setup Instructions') }}</h5>
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
                            <strong>Google Analytics Data API</strong>.
                        </li>

                        <li class="list-group-item text-dark">
                            3. {{ translate('Go to APIs & Services → Credentials → Create Credentials → Service Account.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            4. {{ translate('Create a key for the service account and download the JSON credentials file.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            5. {{ translate('Rename the downloaded JSON file to') }} 
                            <strong>service-account-credentials.json</strong>.
                        </li>

                        <li class="list-group-item text-dark">
                            6. {{ translate('Upload this file manually to the following path in your server or hosting panel:') }}  
                            <br>
                            <code>storage/app/analytics/service-account-credentials.json</code>
                        </li>

                        <li class="list-group-item text-dark">
                            7. {{ translate('Add the following configuration line to your .env file. If it does not already exist, please add it manually:') }}
                            <br>
                            <code>ANALYTICS_SERVICE_ACCOUNT_CREDENTIALS_JSON=storage/app/analytics/service-account-credentials.json</code>
                        </li>

                        <li class="list-group-item text-dark">
                            8. {{ translate('Go to') }} 
                            <a href="https://analytics.google.com" target="_blank">Google Analytics</a> → 
                            {{ translate('Admin → Property Settings and copy the Property ID.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            9. {{ translate('In Google Analytics Admin, go to Property Access Management and add the Service Account Email with Viewer permission.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            10. {{ translate('Finally enter the Tracking ID and Analytics Property ID in the fields and save the settings.') }}
                        </li>
                    </ul>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fa fa-info-circle"></i>
                        <strong>{{ translate('Note:') }}</strong>
                        {{ translate('The file name must be exactly') }}
                        <strong>service-account-credentials.json</strong>
                        {{ translate('and must be placed inside') }}
                        <code>storage/app/analytics/</code>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection