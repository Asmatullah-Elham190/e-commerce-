@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-lg-8">
        <div class="card">
                  <div class="card-body bg-blue-color2">
                    @if(get_setting('shipping_type') == 'product_wise_shipping')
                     <button class="btn bg-blue-color2 text-primary w-100 "><h6 class="font-weight-bold mb-0">{{ translate('You have selected Product Wise Shipping Cost') }}</h6></button>
                    @elseif(get_setting('shipping_type') == 'flat_rate')
                    <button class="btn bg-blue-color2 text-primary w-100 "><h6 class="font-weight-bold mb-0">{{ translate('You have selected Flat Rate Shipping Cost') }}</h6></button>
                    @elseif(get_setting('shipping_type') == 'seller_wise_shipping')
                    <button class="btn bg-blue-color2 text-primary w-100 "><h6 class="font-weight-bold mb-0">{{ translate('You have selected Seller Wise Flat Shipping Cost') }}</h6></button>
                    @elseif(get_setting('shipping_type') == 'area_wise_shipping')
                    <button class="btn bg-blue-color2 text-primary w-100 "><h6 class="font-weight-bold mb-0">{{ translate('You have selected Area Wise Flat Shipping Cost') }}</h6></button>
                    @elseif(get_setting('shipping_type') == 'carrier_wise_shipping')
                    <button class="btn bg-blue-color2 text-primary w-100 "><h6 class="font-weight-bold mb-0">{{ translate('You have selected Carrier Wise Shipping Cost') }}</h6></button>
                    @endif
            </div>
        </div>

        <div class="card">
            <div class="{{ get_setting('shipping_type') == 'area_wise_shipping' ? 'border border-primary border-2 rounded-2' : '' }}">
                <div class="card-header">
                    <h6 class="mb-0">{{translate('Shipping Area Coverage & Area Wise Shipping Cost')}} <br> <p class="text-danger fs-12 fw-400 mb-0 pt-2 lh-1-5">Area-wise shipping cost is calculated based on the rates set in this configuration. The system’s shipping area coverage and the fields shown in the customer address will depend on this configuration.</p></h6>

                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            1. {{ translate('For Area-Wise Shipping Cost, you need to configure the complete location hierarchy in the following order: Country → State → City → Area.') }}.
                        </li>
                        <li class="list-group-item">
                            2. {{ translate('Choose the countries you deliver to. If only one country is enabled, the country field will be hidden at checkout. Enabling multiple countries displays the field, while disabling a country also disables its states, cities, and areas. Configure your ') }} <a href="{{ route('countries.index') }}">{{ translate('Shipping Countries') }}</a>.
                        </li>
                        <li class="list-group-item">
                            3. {{ translate('States are optional and can be enabled or disabled in Shipping Configuration. If enabled, the state field appears in address forms; if disabled, it’s skipped and cities load directly from the country. Disabling a state also disables its cities and areas. Configure your ') }} <a href="{{ route('states.index') }}">{{ translate('Shipping States') }}</a>.
                        <hr class="border-dashed">
                            <div class=" py-2 d-flex align-items-center justify-content-between flex-row">
                                <p class="mb-0 fs-13 fw-600">{{ translate('Do You want to enable states?')}}</p>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" value="{{get_setting('has_state')}}" type="checkbox"<?php if(get_setting('has_state') == 1) echo "checked"; if(addon_is_activated('gst_system')) echo " disabled";?>  >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </li>
                        <li class="list-group-item">
                            4. {{ translate('Add and enable cities to use them for shipping. If states are on, cities go under states; if off, they go under countries. Turning off a city also turns off its areas. If a city is missing, users will see ‘We no longer deliver to this address. Configure Your ') }} <a href="{{ route('cities.index') }}">{{ translate('Shipping Cities') }}</a>.
                        </li>

                        <li class="list-group-item">
                            5. {{ translate('Add and enable areas under cities with shipping costs. If none are available, city costs apply. Customers with unmatched areas will see ‘We no longer deliver in this area’ and can update their address. Configure Your ') }} <a href="{{ route('areas.index') }}">{{ translate('Shipping Areas') }}</a>.
                        </li>
                    
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="{{ get_setting('shipping_type') == 'product_wise_shipping' ? 'border border-primary border-2 rounded-2' : '' }}">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Product Wise Shipping Cost')}}</h5>
                </div>
                <div class="card-body">
                    <span>To set a product-wise shipping cost, please edit the desired product from the product edit page. You can access your product list <a href="{{route('products.all')}}">here</a></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="{{ get_setting('shipping_type') == 'flat_rate' ? 'border border-primary border-2 rounded-2' : '' }}">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Flat Rate Shipping Cost')}}</h5>
                </div>
                <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <span>{{ translate('Flat Rate Shipping Cost calculation: How many products a customer purchase, doesn\'t matter. Shipping cost is fixed') }}.</span>
                    @csrf
                    <input type="hidden" name="type" value="flat_rate_shipping_cost">
                    <div class="form-group d-flex justify-content-between pt-3">
                        <div class="w-100">
                            <input class="form-control" type="text" name="flat_rate_shipping_cost" value="{{ get_setting('flat_rate_shipping_cost') }}">
                        </div>
                        <div class="w-80 ml-2">
                            <button type="submit" class="btn btn-primary w-100">{{translate('Save')}}</button>
                        </div>
                    </div>
                    
                </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="{{ get_setting('shipping_type') == 'seller_wise_shipping' ? 'border border-primary border-2 rounded-2' : '' }}">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Seller Wise Shipping Cost')}}</h5>
                </div>
                <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <span>{{ translate('Each seller has a fixed shipping rate. Admin can set their rate here, and sellers set theirs from their panel. If a customer buys from multiple sellers, shipping costs are added together') }}.</span>
                    @csrf
                    <input type="hidden" name="type" value="shipping_cost_admin">
                    <div class="form-group d-flex justify-content-between pt-3">
                        <div class="w-100">
                            <input class="form-control" type="text" name="shipping_cost_admin" value="{{ get_setting('shipping_cost_admin') }}">
                        </div>
                        <div class="w-80 ml-2">
                            <button type="submit" class="btn btn-primary w-100">{{translate('Save')}}</button>
                        </div>
                    </div>
                    
                </div>
                </form>
            </div>
        </div>

        

        <div class="card ">
            <div class="{{ get_setting('shipping_type') == 'carrier_wise_shipping' ? 'border border-primary border-2 rounded-2' : '' }}">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Carrier Wise Shipping Cost')}}</h5>
                </div>
                <div class="card-body">
                    <span>{{ translate('Carrier Wise Shipping Cost calculation: Shipping cost calculate in addition with carrier. In each carrier you can set free shipping cost or can set weight range or price range shipping cost. To configure carrier wise shipping cost go to ') }} <a href="{{ route('carriers.index') }}">{{ translate('Shipping Carriers') }}</a>.</span>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-2"></div>
</div>




@endsection

@section('script')

<script type="text/javascript">
    function update_status(el) {
        var status = el.checked ? 1 : 0;
        $.post('{{ route('shipping_configuration.state') }}', {
            _token: '{{ csrf_token() }}',
            type: 'has_state',
            has_state: status
        }, function(response){
            if (response == 1) {
                AIZ.plugins.notify('success', '{{ translate('State Enabled Successfully') }}');
            } else if (response == 0) {
                AIZ.plugins.notify('warning', '{{ translate('State Disabled Successfully') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>

@endsection
