@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Element Type Information') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">

                <form class="p-4" action="{{ route('update-element-type', $element_type->id) }}" method="POST">
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" name="element_id" value="{{ $element_type->element_id }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Element type">
                            {{ translate('Element Type') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Element Type') }}" id="type" name="type"
                                class="form-control" required value="{{ $element_type->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Image')}}</label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="image_id" class="selected-files" value="{{ $element_type->image_id }}">
                            </div>
                            <div class="file-preview box lg">
                            </div>
                            <small
                                class="text-muted">{{ translate('Minimum dimensions required: 150px width X 150px height.') }}</small>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="code">
                            {{ translate('Color Code')}}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Color Code')}}" id="code" name="code"
                                class="form-control" required value="{{ $attribute_value->code }}">
                        </div>
                    </div> --}}
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection