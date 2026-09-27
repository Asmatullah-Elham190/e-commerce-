    @extends('backend.layouts.app')

    @section('content')
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <img class="mr-3" src="{{ static_asset('assets/img/cards/'.$shipping_system->name.'.png') }}" height="30">
                            <h5 class="mb-0 h6">{{ ucfirst(translate($shipping_system->name)) }}</h5>
                        </div>
                        <label class="aiz-switch aiz-switch-success mb-0 float-right">
                            <input type="checkbox" onchange="updateShippingSettings(this, {{ $shipping_system->id }})" @if ($shipping_system->active == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="card-body">
                        @include('backend.shipping_system.partials.' . $shipping_system->name . '.' . $shipping_system->name)
                    </div>
                </div>
            </div>
        </div>
        @php
            // $demo_mode = env('DEMO_MODE') == 'On' ? true : false;
        @endphp
    @endsection

    @section('script')
        <script type="text/javascript">

            function updateShippingSettings(el, id) {

                if('{{env('DEMO_MODE')}}' == 'On'){
                    AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                    return;
                }

                if ($(el).is(':checked')) {
                    var value = 1;
                } else {
                    var value = 0;
                }

                $.post('{{ route('shipping.activation') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    value: value
                }, function(data) {
                    if (data == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Shipping Settings updated successfully') }}');
                    } else {
                        AIZ.plugins.notify('danger', 'Something went wrong');
                    }
                });
            }

        </script>
    @endsection
