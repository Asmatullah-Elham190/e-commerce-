@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Brand Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill language-bar">
  				@foreach (get_all_active_language() as $key => $language)
  					<li class="nav-item">
  						<a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('brands.edit', ['id'=>$brand->id, 'lang'=> $language->code] ) }}">
  							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
  							<span>{{ $language->name }}</span>
  						</a>
  					</li>
	            @endforeach
  			</ul>
            <form class="p-4" action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" id="aizSubmitForm">
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" maxlength="100" value="{{ $brand->getTranslation('name', $lang) }}" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="signinSrEmail">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="logo" value="{{$brand->logo}}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                    <small class="text-muted">{{ translate('Minimum dimensions required: 120px width X 80px height.') }}</small>
                </div>
                <div class="form-group mb-3">
                    <label>{{translate('Meta Title')}}</label>
                    <input type="text" class="form-control" name="meta_title" value="{{ $brand->meta_title }}" placeholder="{{translate('Meta Title')}}">
                </div>
                <div class="form-group mb-3">
                    <label>{{translate('Meta Description')}}</label>
                    <textarea name="meta_description" rows="8" class="form-control">{{ $brand->meta_description }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label>{{translate('Meta Keywords')}}</label>
                    <textarea name="meta_keywords" class="resize-off form-control">{{ $brand->meta_keywords }}</textarea>
                    <small class="text-muted">{{ translate('Separate with coma') }}</small>
                </div>
                <div class="form-group mb-3">
                    <label for="name">{{translate('Slug')}}</label>
                    <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $brand->slug }}" class="form-control">
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
