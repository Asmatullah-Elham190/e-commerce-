@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Area Information') }}</h5>
</div>

<div class="row">
  <div class="col-lg-8 mx-auto">
      <div class="card">
          <div class="card-body p-0">
              <ul class="nav nav-tabs nav-fill language-bar">
                @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('areas.edit', ['id'=>$area->id, 'lang'=> $language->code]) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{ $language->name }}</span>
                        </a>
                    </li>
                @endforeach
              </ul>
              <form class="p-4" action="{{ route('areas.update', $area->id) }}" method="POST" enctype="multipart/form-data">
                  @method('PATCH')
                  @csrf
                  <input type="hidden" name="lang" value="{{ $lang }}">

                  <div class="form-group mb-3">
                      <label for="name">{{ translate('Name') }}</label>
                      <input type="text" placeholder="{{ translate('Name') }}" value="{{ $area->getTranslation('name', $lang) }}" name="name" class="form-control" required>
                  </div>

                  @if(get_setting('has_state') == 1)
                  <div class="form-group">
                      <label for="state_id">{{ translate('State') }}</label>
                      <select class="select2 form-control aiz-selectpicker" id="state_id" name="state_id" data-toggle="select2" data-placeholder="{{ translate('Choose ...') }}" data-live-search="true" required>
                          @foreach ($states as $state)
                            <option value="{{ $state->id }}" @if(optional($area->city)->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  @else
                  <div class="form-group">
                      <label for="country_id">{{ translate('Country') }}</label>
                      <select class="select2 form-control aiz-selectpicker" id="country_id" name="country_id" data-toggle="select2" data-placeholder="{{ translate('Choose ...') }}" data-live-search="true" required>
                          @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if(optional($area->city)->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  @endif

                  <div class="form-group">
                      <label for="city_id">{{ translate('City') }}</label>
                      <select class="select2 form-control aiz-selectpicker" id="city_id" name="city_id" data-toggle="select2" data-placeholder="{{ translate('Choose ...') }}" data-live-search="true" required>
                          @foreach ($cities as $city)
                            <option value="{{ $city->id }}" @if($area->city_id == $city->id) selected @endif>{{ $city->name }}</option>
                          @endforeach
                      </select>
                  </div>

                  <div class="form-group mb-3">
                      <label for="cost">{{ translate('Cost') }}</label>
                      <input type="number" min="0" step="0.01" placeholder="{{ translate('Cost') }}" name="cost" class="form-control" value="{{ $area->cost }}" required>
                  </div>

                  <div class="form-group mb-3 text-right">
                      <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

@endsection

@section('script')
<script>
    $('#state_id').on('change', function() {
        var stateId = $(this).val();
        var citySelect = $('#city_id');
        if(stateId) {
            $.ajax({
                url: '{{ route("get-cities-by-state") }}',
                type: 'GET',
                data: { state_id: stateId },
                success: function(response) {
                    citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                    response.forEach(function(city) {
                        citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                    citySelect.selectpicker('refresh');
                },
                error: function() {
                    citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                    citySelect.selectpicker('refresh');
                }
            });
        } else {
            citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
            citySelect.selectpicker('refresh');
        }
    });

    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        var citySelect = $('#city_id');
        if(countryId) {
            $.ajax({
                url: '{{ route("get-cities-by-country") }}',
                type: 'GET',
                data: { country_id: countryId },
                success: function(response) {
                    citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                    response.forEach(function(city) {
                        citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                    citySelect.selectpicker('refresh');
                },
                error: function() {
                    citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
                    citySelect.selectpicker('refresh');
                }
            });
        } else {
            citySelect.empty().append('<option value="">{{ __("Select City") }}</option>');
            citySelect.selectpicker('refresh');
        }
    });

</script>
@endsection
