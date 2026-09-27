@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Edit Box Size Information') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('shipping_box_size.update', $box_size->id) }}" method="POST" id="aizSubmitForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">{{translate('Courier Type')}}<span class="text-danger"> *</span>
                            </label>
                            <select name="courier_type" class="form-control aiz-selectpicker mb-2 mb-md-0" data-live-search="true">
                                <option value="">{{ translate('Set Courier Type') }}</option>
                                <option value="shiprocket" 
                                {{ old('courier_type', $box_size->courier_type) == 'shiprocket' ? 'selected' : '' }}>{{ ucfirst(translate('Shiprocket')) }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">{{translate('Box Length (cm)')}}<span class="text-danger"> *</span> </label>
                            <input type="number" value="{{ old('length', $box_size->length) }}" placeholder="{{translate('Length')}}" name="length" 
                            class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">{{translate('Box Breadth (cm)')}}<span class="text-danger"> *</span></label>
                            <input type="number" value="{{ old('breadth', $box_size->breadth) }}" placeholder="{{translate('Breadth')}}" name="breadth" 
                            class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">{{translate('Box Height (cm)')}}<span class="text-danger"> *</span></label>
                            <input type="number" value="{{ old('height', $box_size->height) }}" placeholder="{{translate('Height')}}" name="height" 
                            class="form-control">
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
