@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Custom Product Visitiors')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('custom_product_visitors.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">{{translate('Show Custom Product Visitors')}}</label>
                        </div>
                        <div class="col-md-7">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="show_custom_product_visitors" type="checkbox" @if (get_setting('show_custom_product_visitors')==1)
                                    checked
                                    @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">{{translate('Visitors Range')}}</label>
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex">
                                <input type="number" class="form-control"
                                    name="min_custom_product_visitors"
                                    value="{{ get_setting('min_custom_product_visitors') ?? 5 }}"
                                    min="1" step="1"
                                    placeholder="{{ translate('Min Visitors') }}">

                                <span class="mx-2 align-self-center">{{ translate('to') }}</span>
                                <input type="number" class="form-control"
                                    name="max_custom_product_visitors"
                                    value="{{ get_setting('max_custom_product_visitors') ?? 20 }}"
                                    min="1" step="1"
                                    placeholder="{{ translate('Max Visitors') }}">

                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener("submit", function(e) {
        const minInput = document.querySelector('[name="min_custom_product_visitors"]');
        const maxInput = document.querySelector('[name="max_custom_product_visitors"]');
        const min = parseInt(minInput.value, 10);
        const max = parseInt(maxInput.value, 10);

        if (min > max) {
            e.preventDefault();
            AIZ.plugins.notify('danger', '{{ translate('Minimum visitors cannot be greater than maximum visitors.') }}');
            minInput.focus();
        }
    });
</script>

@endsection