@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Facebook Catalogue Setting')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('facebook_catalogue.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="FACEBOOK_CATALOGUE_ID">
                            <label class="col-from-label">{{translate('Catalog ID')}}</label>
                            <input type="text" class="form-control" name="FACEBOOK_CATALOGUE_ID" value="{{  env('FACEBOOK_CATALOGUE_ID') }}" placeholder="123456789" required>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="types[]" value="FACEBOOK_ACCESS_TOKEN">
                            <label class="col-from-label">{{translate('Access Token')}}</label>
                            <input type="text" class="form-control" name="FACEBOOK_ACCESS_TOKEN" value="{{  env('FACEBOOK_ACCESS_TOKEN') }}" placeholder="{{ translate('Facebook Access Token') }}" required>
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
                    <h5 class="mb-0 h6">{{ translate('Meta (Facebook) Catalog Setup Instructions') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            1. {{ translate('Go to') }} 
                            <a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers Portal</a> 
                            {{ translate('and create a new App.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            2. {{ translate('Select') }} <strong>Business</strong> {{ translate('as the app type.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            3. {{ translate('Give your app a name (e.g.,') }} <strong>My Store Catalog</strong> {{ translate('and click Create App.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            4. {{ translate('Go to') }} <strong>Add Products to Catalog</strong> → {{ translate('Set Up Catalog') }}
                        </li>

                        <li class="list-group-item text-dark">
                            5. {{ translate('Select') }} <strong>E-commerce</strong> {{ translate('as catalog type and create your catalog.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            6. {{ translate('Now go to') }} 
                            <a href="https://business.facebook.com/commerce" target="_blank">Commerce Manager</a> 
                            {{ translate('and copy your') }} <strong>Catalog ID</strong> 
                            {{ translate('from Settings page.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            7. {{ translate('Go back to') }} <a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers Portal</a>
                        </li>

                        <li class="list-group-item text-dark">
                            8. {{ translate('Go to') }} <strong>Graph API Explorer</strong> → {{ translate('Select your App') }}
                        </li>

                        <li class="list-group-item text-dark">
                            9. {{ translate('From User or Page dropdown, select') }} <strong>Page Access Token</strong>
                        </li>

                        <li class="list-group-item text-dark">
                            10. {{ translate('Add permissions:') }} 
                            <strong>business_management</strong> {{ translate('and') }} <strong>catalog_management</strong>
                        </li>

                        <li class="list-group-item text-dark">
                            11. {{ translate('Click') }} <strong>Generate Access Token</strong> {{ translate('and copy the token.') }}
                        </li>

                        <li class="list-group-item text-dark">
                            12. {{ translate('Paste the Catalog ID and Access Token in the fields above and Save.') }}
                        </li>
                    </ul>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fa fa-info-circle"></i>
                        <strong>{{ translate('Note:') }}</strong>
                        {{ translate('The Access Token must be a') }}
                        <strong>Page Access Token</strong>
                        {{ translate('with catalog_management permission. User Access Token will not work.') }}
                    </div>
                    
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>{{ translate('Important:') }}</strong>
                        {{ translate('Your website URL must be live (HTTPS) and verified in Business Manager before products will appear in Facebook Shop.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection