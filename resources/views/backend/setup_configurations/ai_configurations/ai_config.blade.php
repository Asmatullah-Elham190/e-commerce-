@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Google AI (Gemini) Settings')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('ai_config.update') }}" method="POST">
                        @csrf
                        <div class="d-flex align-items-center mb-3">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="ai_activation" type="checkbox" @if (get_setting('ai_activation') == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                            <label class="ml-2 mb-0">{{translate('Enable Gemini AI')}}</label>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-from-label">{{translate('Select AI Model')}}</label>
                            <select class="form-control aiz-selectpicker" name="gemini_model">
                                <optgroup label="Gemini 3.1 (Cutting Edge)">
                                    <option value="gemini-3.1-pro-preview" @if(get_setting('gemini_model') == 'gemini-3.1-pro-preview') selected @endif>Gemini 3.1 Pro (Advanced)</option>
                                    <option value="gemini-3.1-flash-lite-preview" @if(get_setting('gemini_model') == 'gemini-3.1-flash-lite-preview') selected @endif>Gemini 3.1 Flash-Lite (Super Fast)</option>
                                </optgroup>
                                <optgroup label="Gemini 3.0">
                                    <option value="gemini-3-flash-preview" @if(get_setting('gemini_model') == 'gemini-3-flash-preview') selected @endif>Gemini 3 Flash</option>
                                    <option value="gemini-3-deep-think" @if(get_setting('gemini_model') == 'gemini-3-deep-think') selected @endif>Gemini 3 Deep Think</option>
                                </optgroup>
                                <optgroup label="Gemini 2.5 (Most Stable)">
                                    <option value="gemini-2.5-pro" @if(get_setting('gemini_model') == 'gemini-2.5-pro') selected @endif>Gemini 2.5 Pro</option>
                                    <option value="gemini-2.5-flash" @if(get_setting('gemini_model') == 'gemini-2.5-flash') selected @endif>Gemini 2.5 Flash</option>
                                    <option value="gemini-2.5-flash-lite" @if(get_setting('gemini_model') == 'gemini-2.5-flash-lite') selected @endif>Gemini 2.5 Flash-Lite (Legacy Lite)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="types[]" value="GEMINI_API_KEY">
                            <label class="col-from-label">{{translate('Gemini API Key')}}</label>
                            <input type="text" class="form-control" name="GEMINI_API_KEY" value="{{ env('GEMINI_API_KEY') }}" placeholder="AIzaSy..." required>
                        </div>

                        <div class="form-group">
                            <label class="col-from-label">{{translate('Seller Monthly Token Limit')}}</label>
                            <input type="number" class="form-control" name="seller_monthly_token_limit" value="{{ get_setting('seller_monthly_token_limit') }}" placeholder="eg. 10 , 20" required>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save Settings')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('How to get your Gemini API Key') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            1. {{ translate('Visit the') }} 
                            <a href="https://aistudio.google.com/app/apikey" target="_blank">Google AI Studio</a>.
                        </li>

                        <li class="list-group-item text-dark">
                            2. {{ translate('Log in with your Google Account.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            3. {{ translate('Click on the "Create API key" button.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            4. {{ translate('Copy the generated API Key and paste it into the "Gemini API Key" field on the left.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            5. {{ translate('Select your preferred model. "Flash" is recommended for speed and lower costs.') }}
                        </li>
                    </ul>

                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>{{ translate('Important:') }}</strong>
                        {{ translate('Keep your API key private. Do not share it or commit it to public repositories.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection