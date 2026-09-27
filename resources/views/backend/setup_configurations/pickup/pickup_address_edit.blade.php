@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Edit Pickup Address Information') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pickup_address.update', $pickup_address->id) }}"
                        method="POST" id="aizSubmitForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="col-from-label fs-13">{{ translate('Courier Type') }} <span class="text-danger">*</span></label>
                            <select name="courier_type" class="form-control aiz-selectpicker mb-2 mb-md-0" data-live-search="true">
                                <option value="">{{ translate('Set Courier Type') }}</option>
                                <option value="shiprocket" 
                                    {{ old('courier_type', $pickup_address->courier_type) == 'shiprocket' ? 'selected' : '' }}>
                                    {{ ucfirst(translate('Shiprocket')) }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="col-from-label fs-13">{{translate('Address Nickname')}} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" maxlength="100" name="address_nickname"
                                value="{{ old('address_nickname', $pickup_address->address_nickname) }}"
                                placeholder="{{ translate('Address Nickname') }}">   
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