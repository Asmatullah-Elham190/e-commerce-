@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Category Information')}}</h5>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill language-bar">
                    @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('categories.edit', ['id'=>$category->id, 'lang'=> $language->code] ) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{$language->name}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <form class="p-4" id="aizSubmitForm" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
    	            <input type="hidden" name="lang" value="{{ $lang }}">
                	@csrf
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <input type="text" name="name" value="{{ $category->getTranslation('name', $lang) }}" class="form-control" maxlength="255" id="name" placeholder="{{translate('Name')}}" required>
                    </div>
                   <div class="form-group mb-3">
                        <label class="col-form-label">{{ translate('Type') }}</label>
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Physical Option -->
                            <div class="form-control d-flex align-items-center justify-content-start mr-3 type-option" data-value="0">
                                <input type="radio" name="digital" value="0" class="mr-2" onchange="categoriesByType(this.value)" @if ($category->digital == '0') checked @endif>
                                <label class="mb-0 fs-14">{{ translate('Physical') }}</label>
                            </div>
                            <!-- Digital Option -->
                            <div class="form-control d-flex align-items-center justify-content-start type-option" data-value="1">
                                <input type="radio" name="digital" value="1" class="mr-2" onchange="categoriesByType(this.value)" @if ($category->digital == '1') checked @endif>
                                <label class="mb-0 fs-14">{{ translate('Digital') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Parent Category')}}</label>
                        <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" data-selected="{{ $category->parent_id }}">
                            @include('backend.product.categories.categories_option_edit', ['categories' => $categories])
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Ordering Number')}}</label>
                        <input type="number" integer-only name="order_level" value="{{ $category->order_level }}" class="form-control" id="order_level" placeholder="{{translate('Order Level')}}">
                        <small>{{translate('Higher number has high priority')}}</small>
                    </div>
    	            <div class="form-group mb-3">
                        <label class="col-form-label" for="signinSrEmail">{{translate('Banner')}}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="banner" class="selected-files" value="{{ $category->banner }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        <small class="text-muted">{{ translate('Minimum dimensions required: 150px width X 150px height.') }}</small>
                    </div>
                    <div class="form-group mb-3"> 
                        <label class="col-form-label" for="signinSrEmail">{{translate('Icon')}}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="icon" class="selected-files" value="{{ $category->icon }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        <small class="text-muted">{{ translate('Minimum dimensions required: 16px width X 16px height.') }}</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label" for="signinSrEmail">{{translate('Cover Image')}}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="cover_image" class="selected-files" value="{{ $category->cover_image }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                        <small class="text-muted">{{ translate('Minimum dimensions required: 318px width X 340px height.') }}</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Meta Title')}}</label>
                        <input type="text" class="form-control" name="meta_title" value="{{ $category->meta_title }}" placeholder="{{translate('Meta Title')}}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Meta Description')}}</label>
                        <textarea name="meta_description" rows="5" class="form-control">{{ $category->meta_description }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Meta Keywords')}}</label>
                        <textarea name="meta_keywords" class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}">{{ $category->meta_keywords }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Slug')}}</label>
                        <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $category->slug }}" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="col-form-label">{{translate('Filtering Attributes')}}</label>
                        <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" data-selected="{{ $category->attributes->pluck('id') }}" multiple>
                            @foreach (\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
    function categoriesByType(val){
          $('.type-option').removeClass('border-primary');
          $('.type-option[data-value="'+val+'"]').addClass('border-primary');
        $('select[name="parent_id"]').html('');
        AIZ.plugins.bootstrapSelect('refresh');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('categories.categories-by-type') }}',
            data:{
               digital: val
            },
            success: function(data) {
                $('select[name="parent_id"]').html(data);
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }
    $(document).ready(function() {
        const checkedRadio = $('input[name="digital"]:checked').val();
        if(checkedRadio !== undefined){
            $('.type-option[data-value="'+checkedRadio+'"]').addClass('border-primary');
        }
    });
   
</script>

@endsection
