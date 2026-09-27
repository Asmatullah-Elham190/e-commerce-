@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Facebook Pixel Conversation API Setting') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('facebook_pixel.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pixel" value="facebook_pixel_capi">
                        <div class="d-flex mb-1 align-items-center">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="facebook_pixel_capi" type="checkbox" @if (get_setting('facebook_pixel_capi') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                            <label class="ml-2 mb-0">{{ translate('Pixel Conversation API') }}</label>
                        </div>
                        <div class="form-group ">
                            <input type="hidden" name="types[]" value="FACEBOOK_PIXEL_ID">
                            <label class="col-from-label">{{ translate('Facebook Pixel ID') }}</label>
                            <input type="text" class="form-control" name="FACEBOOK_PIXEL_ID" value="{{  env('FACEBOOK_PIXEL_ID') }}" placeholder="{{ translate('Facebook Pixel ID') }}" required>
                            
                        </div>
                        <div class="form-group ">
                            <input type="hidden" name="types[]" value="FACEBOOK_PIXEL_API">
                            <label class="col-from-label">{{ translate('Facebook Pixel Access Token for CAPI') }}</label>
                            <input type="text" class="form-control" name="FACEBOOK_PIXEL_API" value="{{  env('FACEBOOK_PIXEL_API') }}" placeholder="{{ translate('Facebook Pixel Access Token') }}" required>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <!-- Facebook Pixel & CAPI Setup Instructions -->
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Facebook Pixel & CAPI Setup Instructions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>{{ translate('Follow these steps to set up Conversion API (CAPI):') }}</strong>
                    </div>
                    
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            <strong>1. {{ translate('Create/Get Pixel ID') }}</strong>
                            <ul class="mt-1">
                                <li>- {{ translate('Go to') }} <a href="https://business.facebook.com/events_manager" target="_blank">Facebook Events Manager</a></li>
                                <li>- {{ translate('Select your pixel or create a new one') }}</li>
                                <li>- {{ translate('Copy the Pixel ID from the top of the page') }}</li>
                            </ul>
                        </li>
                        
                        <li class="list-group-item text-dark">
                            <strong>2. {{ translate('Generate Access Token for CAPI') }}</strong>
                            <ul class="mt-1">
                                <li>- {{ translate('In Events Manager, go to') }} <strong>Settings → Scroll to Conversion API</strong></li>
                                <li>- {{ translate('Click on') }} <strong>"Generate Access Token"</strong> {{ translate('button') }}</li>
                                <li>- {{ translate('Copy the generated token') }} ({{ translate('starts with EAA...') }})</li>
                                <li class="text-danger"> {{ translate('Save this token immediately - you won\'t see it again!') }}</li>
                            </ul>
                        </li>
                        
                    </ul>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>{{ translate('Important Notes:') }}</strong>
                        <ul class="mt-1 mb-0">
                            <li>{{ translate('Access tokens expire - you may need to regenerate them periodically') }}</li>
                            <li>{{ translate('CAPI works alongside browser pixel for better data reliability') }}</li>
                            <li>{{ translate('Make sure your domain is verified in Facebook Business Manager') }}</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success mt-2 mb-0">
                        <i class="fa fa-check-circle"></i>
                        <strong>{{ translate('Token Format Example:') }}</strong> {{ translate('EAAJjmXXfkp8BAMZCDLWDZB6ZCk4yUmMbITZBk...') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection