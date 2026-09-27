@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <h5 class="mb-3 h6">{{translate('Custom Sale Alert Products')}}</h5>
        <div class="card">
            <div class="card-body p-0">
                    <form class="p-4" action="{{ route('custom-sale-alerts.product_update') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <label class="col-from-label">{{translate('Show Custom Sale Alert')}}</label>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="1" name="show_custom_product_sale_alert" type="checkbox" @if (get_setting('show_custom_product_sale_alert')==1)
                                            checked
                                            @endif>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="col-from-label">{{ translate('Popup Show Interval (Random)') }} <i class="las la-info-circle text-primary ml-2" data-toggle="tooltip" data-placement="right" title="{{ translate('The popup will appear randomly between the minimum and maximum interval you set.') }}"></i></label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control"
                                            name="sale_alert_min_time"
                                            value="{{ get_setting('sale_alert_min_time') ?? '' }}"
                                            min="1" step="1"
                                            placeholder="{{ translate('Min Interval Second') }}">
                                        <span class="mx-2">{{ translate('To') }}</span>
                                        <input type="number" class="form-control"
                                            name="sale_alert_max_time"
                                            value="{{ get_setting('sale_alert_max_time') ?? '' }}"
                                            min="1" step="1"
                                            placeholder="{{ translate('Max Interval Second') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-from-label" for="products">{{translate('Products')}}</label>
                                    <select name="products[]" id="products" class="form-control aiz-selectpicker" multiple required
                                        data-placeholder="{{ translate('Choose Products') }}" data-live-search="true" data-selected-text-format="count">
                                        @foreach($products as $product)
                                        @php
                                        $sale_alert_product = \App\Models\CustomSaleAlert::where('product_id', $product->id)->first();
                                        @endphp
                                        <option value="{{ $product->id }}"
                                            <?php if ($sale_alert_product != null) echo "selected"; ?>
                                            data-content='<img src="{{ uploaded_asset($product->thumbnail_img) }}" class="size-30px img-fit mr-2"> {{ $product->getTranslation("name") }}'>
                                            >
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" id="discount_table">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {

    get_flash_deal_discount();

    $('#products').on('change', function() {
        get_flash_deal_discount();
    });

    function get_flash_deal_discount() {
        var product_ids = $('#products').val();
        if (product_ids.length > 0) {
            $.post('{{ route('custom_sale_alerts.products')}}', {_token: '{{ csrf_token() }}', product_ids: product_ids},
                function(data) {
                    $('#discount_table').html(data);
                    AIZ.plugins.fooTable();
                });
        } else {
            $('#discount_table').html(null);
        }
    }

    // Toggle min/max requirements based on checkbox
    $('[name="show_custom_product_sale_alert"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('[name="sale_alert_min_time"], [name="sale_alert_max_time"]').prop('required', true).attr('min', null);
        } else {
            $('[name="sale_alert_min_time"], [name="sale_alert_max_time"]').prop('required', false);
        }
    });

    document.addEventListener("submit", function(e) {
        const checkbox = document.querySelector('[name="show_custom_product_sale_alert"]');
        if (checkbox.checked) {
            const minInput = document.querySelector('[name="sale_alert_min_time"]');
            const maxInput = document.querySelector('[name="sale_alert_max_time"]');
            const min = parseFloat(minInput.value);
            const max = parseFloat(maxInput.value);

            // Check for empty values
            if (!minInput.value || !maxInput.value) {
                e.preventDefault();
                AIZ.plugins.notify('danger', '{{ translate('Both Minimum and Maximum Interval are required.') }}');
                return;
            }

            // Integer validation
            if (!Number.isInteger(min) || !Number.isInteger(max)) {
                e.preventDefault();
                AIZ.plugins.notify('danger', '{{ translate('Only integer values are allowed for intervals.') }}');
                return;
            }

            // Check min ≤ max
            if (min > max) {
                e.preventDefault();
                AIZ.plugins.notify('danger', '{{ translate('Minimum Interval cannot be greater than Maximum.') }}');
                minInput.focus();
                return;
            }
        }
    });

    // Trigger change on page load to set correct required state
    $('[name="show_custom_product_sale_alert"]').trigger('change');
});
</script>

@endsection