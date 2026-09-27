@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col-md-12">
    			<h1 class="h3">{{translate('All areas')}}</h1>
    		</div>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <form class="" id="sort_areas" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Area') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="sort_area" name="sort_area" @isset($sort_area) value="{{ $sort_area }}" @endisset placeholder="{{ translate('Type Area name & Enter') }}">
                        </div>
                        @if (get_setting('has_state') == 1)
                        <div class="col-md-3">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_state" name="sort_state">
                                <option value="">{{ translate('Select State') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" @if ($sort_state == $state->id) selected @endif {{$sort_state}}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="col-md-3">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_country" name="sort_country">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if ($sort_country == $country->id) selected @endif {{$sort_country}}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                         <div class="col-md-3">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_city" name="sort_city">
                                <option value="">{{ translate('Select City') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @if ($sort_city == $city->id) selected @endif {{$sort_city}}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th data-breakpoints="lg">#</th>
                                <th>{{translate('Name')}}</th>
                                <th>{{translate('City')}}</th>
                                <th>{{translate('Area Wise Shipping Cost')}}</th>
                                <th>{{translate('Show/Hide')}}</th>
                                <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($areas as $key => $area)
                                <tr>
                                    <td>{{ ($key+1) + ($areas->currentPage() - 1) * $areas->perPage() }}</td>
                                    <td>{{ $area->getTranslation('name') }}</td>
                                    <td>{{ $area->city->name }}</td>
                                    <td>{{ single_price($area->cost) }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                          <input onchange="update_status(this)" value="{{ $area->id }}" type="checkbox" <?php if($area->status == 1) echo "checked";?> >
                                          <span class="slider round"></span>
                                        </label>
                                      </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('areas.edit', ['id'=>$area->id, 'lang'=>env('DEFAULT_LANGUAGE')]) }}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('areas.destroy', $area->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $areas->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New Area') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('areas.store') }}" method="POST">
                        @csrf

                        <!-- Area Name -->
                        <div class="form-group mb-3">
                            <label for="name">{{ translate('Name') }}</label>
                            <input type="text" placeholder="{{ translate('Name') }}" name="name" class="form-control" required>
                        </div>

                        @if(get_setting('has_state') == 1)
                        <!-- State Select -->
                        <div class="form-group mb-3">
                            <label for="state_id">{{ translate('State') }}</label>
                            <select id="form_state" class="select2 form-control aiz-selectpicker" name="state_id"
                                    data-toggle="select2" data-placeholder="Choose..." data-live-search="true" required>
                                <option value="">{{ translate('Select State') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <!-- Country Select -->
                        <div class="form-group mb-3">
                            <label for="country_id">{{ translate('Country') }}</label>
                            <select id="form_country" class="select2 form-control aiz-selectpicker" name="country_id"
                                    data-toggle="select2" data-placeholder="Choose..." data-live-search="true" required>
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- City Select -->
                        <div class="form-group mb-3">
                            <label for="city_id">{{ translate('City') }}</label>
                            <select id="form_city" class="select2 form-control aiz-selectpicker" name="city_id"
                                    data-toggle="select2" data-placeholder="Choose..." data-live-search="true" required>
                                <option value="">{{ translate('Select City') }}</option>
                                <!-- Will be populated dynamically -->
                            </select>
                        </div>

                        <!-- Cost -->
                        <div class="form-group mb-3">
                            <label for="cost">{{ translate('Cost') }}</label>
                            <input type="number" min="0" step="0.01" placeholder="{{ translate('Cost') }}" name="cost" class="form-control" required>
                        </div>

                        <!-- Submit -->
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function sort_areas(el){
            $('#sort_areas').submit();
        }

        function update_status(el){

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('areas.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Area status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }


       $('#sort_state').on('change', function () {
            var stateId = $(this).val();
            $.ajax({
                url: '{{ route("get-cities-by-state") }}',
                type: 'GET',
                data: { state_id: stateId },
                success: function (response) {
                    let citySelect = $('#sort_city');
                    citySelect.empty().append('<option value="">{{ __('Select City') }}</option>');

                    response.forEach(function (city) {
                        citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });

                    citySelect.selectpicker('refresh');
                }
            });
        });

        $('#sort_country').on('change', function () {
            var countryId = $(this).val();

            if (countryId) {
                $.ajax({
                    url: '{{ route("get-cities-by-country") }}',
                    type: 'GET',
                    data: { country_id: countryId },
                    success: function (response) {
                        let citySelect = $('#sort_city');
                        citySelect.empty().append('<option value="">{{ __('Select City') }}</option>');
                        response.forEach(function (city) {
                            citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });

                        citySelect.selectpicker('refresh');
                    }
                });
            }
        });

        $(document).ready(function () {
            $('#form_state').on('change', function () {
                var stateId = $(this).val();
                var citySelect = $('#form_city');

                if (stateId) {
                    $.ajax({
                        url: '{{ route("get-cities-by-state") }}',
                        type: 'GET',
                        data: { state_id: stateId },
                        success: function (response) {
                            citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');

                            response.forEach(function (city) {
                                citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                            });

                            citySelect.selectpicker('refresh');
                        },
                        error: function () {
                            citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                            citySelect.selectpicker('refresh');
                        }
                    });
                } else {
                    citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                    citySelect.selectpicker('refresh');
                }
            });

            $('#form_country').on('change', function () {
                var countryId = $(this).val();
                var citySelect = $('#form_city');

                if (countryId) {
                    $.ajax({
                        url: '{{ route("get-cities-by-country") }}',
                        type: 'GET',
                        data: { country_id: countryId },
                        success: function (response) {
                            citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                            response.forEach(function (city) {
                                citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                            });

                            citySelect.selectpicker('refresh');
                        },
                        error: function () {
                            citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                            citySelect.selectpicker('refresh');
                        }
                    });
                } else {
                    citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                    citySelect.selectpicker('refresh');
                }
            });

        });

    </script>
@endsection
