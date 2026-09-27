@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8 mx-auto">
                <div class="card-body p-2rem">
                    <h6 class="text-center">{{ translate('Select Header Layouts') }}</h6>
                    <form action="{{ route('settings.select-header') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mx-1 header-card">
                            @foreach ($element_types as $key => $element_type)
                                
                                    <div class="card text-center px-2 py-3 w-100" data-header="{{$element_type->name}}">
                                        <input type="radio" hidden 
                                               id="element_type_{{ $key }}" 
                                               name="header_element"
                                               value="{{$element_type->id}}" 
                                               @if(get_setting('header_element') == $element_type->id) checked @endif>

                                        <img src="{{ static_asset('assets/img/headers/header' . ($key+1) . '.webp') }}"
                                             class="card-img-top mx-auto" alt="header layout">

                                        <p class="mt-2 mb-0 font-weight-bold">
                                            {{ $element_type->name }}
                                        </p>
                                    </div>
                                
                            @endforeach
                        </div>

                        <div class="row p-1">
                            <div class="col-md-8 d-none d-md-block">
                                <button type="button" class="btn bg-blue-color2 text-primary w-100 ">
                                    <small class="font-weight-bold">
                                        {{ translate('You have selected') }} <span id="dynamic-text">...</span>
                                    </small>
                                </button>
                            </div>
                            <div class="col-md-4 d-flex align-items-center justify-content-end">
                                <button type="submit"
                                    class="btn btn-success  w-100 btn-md rounded-2 fs-14 fw-700 shadow-success">
                                    {{ translate('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // make whole card clickable
    $('.header-card .card').click(function(e) {
        if (!$(e.target).is('input[type=radio]')) {
            $(this).find('input[type=radio]').prop('checked', true).trigger('change');
        }
    });

    // when radio changes → update border + dynamic text
    $('input[name="header_element"]').change(function() {
        $('.header-card .card').removeClass('border border-primary border-2');
        $(this).closest('.card').addClass('border border-primary border-2');
        $('#dynamic-text').text($(this).closest('.card').data('header'));
    });

    // initialize selected card on page load
    var selected = $('input[name="header_element"]:checked');
    if (selected.length) {
        selected.closest('.card').addClass('border border-primary border-2');
        $('#dynamic-text').text(selected.closest('.card').data('header'));
    }

    // pointer cursor
    $(document).ready(function() {
        $('.header-card .card').css('cursor', 'pointer');
    });
</script>

@endsection
