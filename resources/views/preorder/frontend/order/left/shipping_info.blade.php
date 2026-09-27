@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if(Auth::check())
 @foreach (Auth::user()->addresses as $key => $address)
   @php
        $city = optional($address->city);
        $area_id = $address->area_id;

        $has_area_id = !is_null($area_id);
        $city_status = $city->status;
        $active_area_exists = $city->areas()->where('status', 1)->exists(); // new line
        $area_status = $has_area_id ? optional($address->area)->status : 1;
        $is_disabled = ($city_status === 0) || ($has_area_id && $area_status === 0) || ($active_area_exists && !$has_area_id) || ($address->state_id == null && get_setting('has_state') == 1);
    @endphp
<div class="border mb-4 {{ $is_disabled ? ' border-danger' : '' }}">
    <div class="row">
        <div class="col-md-8">
            <label class="aiz-megabox d-block bg-white mb-0">
                <input type="radio" name="address_id" value="{{ $address->id }}"
                           {{ $address->id == $address_id && !$is_disabled ? 'checked' : '' }}
                           {{ $is_disabled ? 'disabled' : '' }} required>
                <span class="d-flex p-3 aiz-megabox-elem border-0">
                    <!-- Checkbox -->
                    <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                    <!-- Address -->
                    <span class="flex-grow-1 pl-3 text-left">
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('Address') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ $address->address }}</span>
                        </div>
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('Postal Code') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ $address->postal_code }}</span>
                        </div>
                        @if ($address->area)
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('Area') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ optional($address->area)->name }}</span>
                        </div>
                        @endif
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('City') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ optional($address->city)->name }}</span>
                        </div>
                        @if (get_setting('has_state') == 1)
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('State') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ optional($address->state)->name }}</span>
                        </div>
                        @endif
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('Country') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ optional($address->country)->name }}</span>
                        </div>
                        <div class="row">
                            <span class="fs-14 text-secondary col-md-3 col-5">{{ translate('Phone') }}</span>
                            <span class="fs-14 text-dark fw-500 ml-2 col">{{ $address->phone }}</span>
                        </div>
                    </span>
                </span>
            </label>
        </div>
        <!-- Edit Address Button -->
        <div class="col-md-4 p-3 text-right">
            <a class="btn btn-sm btn-secondary-base text-white mr-4 rounded-0 px-4"
                onclick="edit_address('{{$address->id}}')">{{ translate('Change') }}</a>
        </div>
        @if($is_disabled)
        <div class="col-md-12">
            <div class="text-center text-danger">
                <span>{{ translate('We no longer deliver in this area.') }}</span>
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach

<input type="hidden" name="checkout_type" value="logged">
<!-- Add New Address -->
<div class="border p-3 c-pointer text-center bg-light has-transition hov-bg-soft-light h-100 d-flex flex-column justify-content-center"
    onclick="add_new_address()">
    <i class="las la-plus mb-1 fs-20 text-gray"></i>
    <div class="alpha-7 fw-700">{{ translate('Add New Address') }}</div>
</div>
@else
<!-- Guest Shipping a address -->
@include('frontend.partials.cart.guest_shipping_info')
@endif