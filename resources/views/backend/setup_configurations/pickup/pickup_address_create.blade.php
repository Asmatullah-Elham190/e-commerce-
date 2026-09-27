@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Pickup Address Information') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pickup_address.store') }}" method="POST" id="aizSubmitForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="col-from-label">
                                {{ translate('Courier Type') }} <span class="text-danger">*</span>
                            </label>
                            <select name="courier_type" class="form-control aiz-selectpicker" data-live-search="true">
                                <option value="">{{ translate('Set Courier Type') }}
                                </option>
                                <option value="shiprocket">{{ ucfirst(translate('Shiprocket') ) }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="col-from-label">{{translate('Address Nickname')}} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="address_nickname"
                                placeholder="{{ translate('Address Nickname') }}" id="address_nickname" maxlength="100" required>
                            <small
                                class="text-muted">{{ translate('This name must match the Shiprocket pickup address nickname.') }}</small>
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