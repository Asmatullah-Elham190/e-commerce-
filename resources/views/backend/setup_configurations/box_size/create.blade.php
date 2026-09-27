@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Box Size Information') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('shipping_box_size.store') }}" method="POST" id="aizSubmitForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">
                                {{translate('Courier Type')}}<span class="text-danger"> *</span>
                            </label>
                            <select name="courier_type" class="form-control aiz-selectpicker"
                            data-live-search="true">
                                <option value="">{{ translate('Set Courier Type') }}
                                </option>
                                <option value="shiprocket">{{ ucfirst(translate('Shiprocket'))  }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">
                                {{translate('Box Length (cm)')}}<span class="text-danger"> *</span> 
                            </label>
                            <input type="number" placeholder="{{translate('Length')}}" name="length" 
                                class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">
                                {{translate('Box Breadth (cm)')}}<span class="text-danger"> *</span>
                            </label>
                            <input type="number" placeholder="{{translate('Breadth')}}" name="breadth" 
                                class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="col-from-label fs-13">
                                {{translate('Box Height (cm)')}}<span class="text-danger"> *</span>
                            </label>
                            <input type="number" placeholder="{{translate('Height')}}" name="height" 
                                class="form-control">   
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection