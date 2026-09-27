<div class="tab-content" id="shippingTabContent">
    <!--Shipping Address-->
    <div class="tab-pane fade show active" id="shipping-address" role="tabpanel"
        aria-labelledby="shipping-address-tab">
        <div class="p-3">
            <!-- Name -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Name')}}" rows="2" name="name" required></input>
                </div>
            </div>

            <!-- Email -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Email')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="email" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Email')}}" name="email" value="" required>
                </div>
            </div>

            <!-- Address -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Address')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required></textarea>
                </div>
            </div>

            <!-- Country -->
            @if (get_active_countries()->count() > 1)
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Country')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <div class="mb-3">
                        <select class="form-control aiz-selectpicker rounded-0" @if (get_setting('shipping_type') == 'carrier_wise_shipping') onchange="updateDeliveryAddress(this.value)" @endif
                            data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                            <option value="">{{ translate('Select your country') }}</option>
                            @foreach (get_active_countries() as $key => $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @elseif(get_active_countries()->count() == 1)
            <input type="hidden" name="country_id" value="{{get_active_countries()[0]->id }}">
            @endif

            @if(get_setting('has_state') == 1)
            <!-- State -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('State')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>

                    </select>
                </div>
            </div>
            @endif

            <!-- City -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('City')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" required>

                    </select>
                </div>
            </div>

            <!--Area-->
            <div class="row area-field d-none">
                <div class="col-md-2">
                    <label>{{ translate('Area')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <select class="form-control mb-3 aiz-selectpicker rounded-0 guest-checkout" data-live-search="true" name="area_id">

                    </select>
                </div>
            </div>

            @if (get_setting('google_map') == 1)
                <!-- Google Map -->
                <div class="row mt-3 mb-3">
                    <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
                    <div id="map"></div>
                    <ul id="geoData">
                        <li style="display: none;">Full Address: <span id="location"></span></li>
                        <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                        <li style="display: none;">Country: <span id="country"></span></li>
                        <li style="display: none;">Latitude: <span id="lat"></span></li>
                        <li style="display: none;">Longitude: <span id="lon"></span></li>
                    </ul>
                </div>
                <!-- Longitude -->
                <div class="row">
                    <div class="col-md-2" id="">
                        <label for="exampleInputuname">{{ translate('Longitude')}}</label>
                    </div>
                    <div class="col-md-10" id="">
                        <input type="text" class="form-control mb-3 rounded-0" id="longitude" name="longitude" readonly="">
                    </div>
                </div>
                <!-- Latitude -->
                <div class="row">
                    <div class="col-md-2" id="">
                        <label for="exampleInputuname">{{ translate('Latitude')}}</label>
                    </div>
                    <div class="col-md-10" id="">
                        <input type="text" class="form-control mb-3 rounded-0" id="latitude" name="latitude" readonly="">
                    </div>
                </div>
            @endif

            <!-- Postal code -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Postal code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="" required>
                </div>
            </div>

            <!-- Phone -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Phone')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <div class="d-flex">
                        <input type="tel" id="phone-code" class="form-control rounded-0" placeholder="" name="phone" autocomplete="off" required>
                        <input type="hidden" name="country_code" value="">

                        @if(addon_is_activated('otp_system') && get_setting('guest_checkout_with_otp') == 1)
                            <button type="button" id="sendGuestOtpBtn" class="btn btn-secondary-base text-white rounded-0 ml-2" onclick="sendGuestOtp(this)" style="white-space: nowrap;">
                                {{ translate('Verify') }}
                            </button>
                        @endif
                    </div>

                    @if(addon_is_activated('otp_system') && get_setting('guest_checkout_with_otp') == 1)
                        <input type="hidden" name="guest_phone_verified" id="guest_phone_verified" value="0">
                        <div class="mt-2" id="guest_phone_verified_badge" style="display:none;">
                            <span class="text-success fs-13"><i class="las la-check-circle"></i> {{ translate('Phone verified') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2 mt-md-2">
                    
                </div>
                <div class="col-md-10">
                    <div class="form-group form-check px-0 py-1 m-0">
                        <label class="aiz-checkbox m-0">
                            <input type="checkbox" id="sameAsShipping" name="same_as_shipping" value="1">
                            <span class="fs-14 fw-300 text-reset">{{ translate('Use this as billing address') }}</span>
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (get_setting('billing_address_required'))
    <div class="tab-pane fade" id="billing-address" role="tabpanel" aria-labelledby="billing-address-tab">
        <div class="p-3">
            <!-- Name -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Name')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Name')}}" rows="2" name="billing_name" required></input>
                </div>
            </div>

            <!-- Address -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Address')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="billing_address" required></textarea>
                </div>
            </div>

            <!-- Country -->
            @if (get_active_countries()->count() > 1)
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Country')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <div class="mb-3">
                        <select class="form-control aiz-selectpicker rounded-0" 
                            data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="billing_country_id" required>
                            <option value="">{{ translate('Select your country') }}</option>
                            @foreach (get_active_countries() as $key => $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @elseif(get_active_countries()->count() == 1)
            <input type="hidden" name="billing_country_id" value="{{get_active_countries()[0]->id }}">
            @endif

            @if(get_setting('has_state') == 1)
            <!-- State -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('State')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="billing_state_id" required>

                    </select>
                </div>
            </div>
            @endif

            <!-- City -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('City')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="billing_city_id" required>

                    </select>
                </div>
            </div>

            <!--Area-->
            <div class="row billing-area-field d-none">
                <div class="col-md-2">
                    <label>{{ translate('Area')}}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <select class="form-control mb-3 aiz-selectpicker rounded-0 guest-checkout" data-live-search="true" name="billing_area_id">

                    </select>
                </div>
            </div>

            <!-- Postal code -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Postal code')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" name="billing_postal_code" value="" required>
                </div>
            </div>

            <!-- Phone -->
            <div class="row">
                <div class="col-md-2 mt-md-2">
                    <label>{{ translate('Phone')}} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="tel" class="form-control rounded-0" placeholder="" name="billing_phone" autocomplete="off" required>
                </div>
            </div>
        </div>     
    </div>
    @endif
</div>



<div class="px-3 pt-3 pb-4 row">
    <div class="col-md-2 mt-md-2"></div>
    <div class="col-md-10">
        <div class="bg-soft-info p-2">
            {{ translate('If you have already used the same mail address or phone number before, please ') }}
            <a href="javascript:void(0);" data-toggle="modal" data-target="#login_modal" class="fw-700 animate-underline-primary">{{ translate('Login') }}</a>
            {{ translate(' first to continue') }}
        </div>
    </div>
</div>
