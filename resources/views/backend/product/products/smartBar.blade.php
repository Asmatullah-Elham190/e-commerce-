@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-7 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Smart Bar') }}</h5>  
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <div class="d-flex align-items-center mb-2">
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input type="checkbox" value="1" onchange="isRefundable()" id="smart_bar_status"
                                        @if(get_setting('smart_bar_status')) checked @endif>
                                    <span></span>
                                </label>
                                <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Show Smart Bar') }}</span>
                            </div>
                            <span class="fs-12 mb-0">({{ translate('This bar will show a product summary at the bottom of the product detail page while scrolling.') }})</span>
                        </div>
                        <div class="form-group">
                            <label class="col-from-label fs-13">
                                {{ translate('Select Background Design') }}
                            </label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="types[]" value="smart_bar_background_design">

                                <!-- Light Option -->
                                <label class="aiz-megabox d-block bg-white mb-0 mr-4" style="flex: 1; min-width: 120px;"> 
                                    <input type="radio" name="smart_bar_background_design" value="plain"
                                        @if(get_setting('smart_bar_background_design') == 'plain') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Plain') }}</span>
                                    </span>
                                </label>

                                <!-- Dark Option -->
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1; min-width: 120px;">
                                    <input type="radio" name="smart_bar_background_design" value="blur"
                                        @if(get_setting('smart_bar_background_design') == 'blur') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Blur') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-from-label fs-13">{{ translate('Select Background Color') }}</label>
                            <div class="input-group">
                                <input type="hidden" name="types[]" value="smart_bar_background_color">
                                <input type="text" class="form-control aiz-color-input" placeholder="Ex: #e1e1e1"
                                    name="smart_bar_background_color"
                                    value="{{ get_setting('smart_bar_background_color') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text p-0">
                                        <input data-target="smart_bar_background_color"
                                            class="aiz-color-picker border-0 size-40px" type="color"
                                            value="{{ get_setting('smart_bar_background_color') }}">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-from-label fs-13">
                                {{ translate('Select Text Color') }}
                            </label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="types[]" value="smart_bar_text_color">

                                <!-- Light Option -->
                                <label class="aiz-megabox d-block bg-white mb-0 mr-4" style="flex: 1; min-width: 120px;"> 
                                    <input type="radio" name="smart_bar_text_color" value="white"
                                        @if(get_setting('smart_bar_text_color') == 'white') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
                                    </span>
                                </label>

                                <!-- Dark Option -->
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1; min-width: 120px;">
                                    <input type="radio" name="smart_bar_text_color" value="dark"
                                        @if(get_setting('smart_bar_text_color') == 'dark') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-from-label fs-13">{{ translate('Select Button Color') }}</label>
                            <div class="input-group">
                                <input type="hidden" name="types[]" value="smart_bar_button_color">
                                <input type="text" class="form-control aiz-color-input" placeholder="Ex: #e1e1e1"
                                    name="smart_bar_button_color"
                                    value="{{ get_setting('smart_bar_button_color') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text p-0">
                                        <input data-target="smart_bar_button_color"
                                            class="aiz-color-picker border-0 size-40px" type="color"
                                            value="{{ get_setting('smart_bar_button_color') }}">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="ol-from-label fs-13">
                                {{ translate('Select Button Text Color') }}
                            </label>
                            <div class="d-flex align-items-center">
                                <input type="hidden" name="types[]" value="smart_bar_button_text_color">

                                <!-- Light Option -->
                                <label class="aiz-megabox d-block bg-white mb-0 mr-4" style="flex: 1; min-width: 120px;"> 
                                    <input type="radio" name="smart_bar_button_text_color" value="white"
                                        @if(get_setting('smart_bar_button_text_color') == 'white') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
                                    </span>
                                </label>

                                <!-- Dark Option -->
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1; min-width: 120px;">
                                    <input type="radio" name="smart_bar_button_text_color" value="dark"
                                        @if(get_setting('smart_bar_button_text_color') == 'dark') checked @endif>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
                                    </span>
                                </label>
                            </div>
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

@section('modal')
    <!-- confirm trigger Modal -->
    <div id="confirm-trigger-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-2 mb-2 fs-16 fw-700" id="confirm_text"></p>
                    <p class="fs-13" id="confirm_detail_text"></p>
                    <a href="javascript:void(0)" id="trigger_btn" data-value="" data-status="" data-clicked="" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px"></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

        $('#trigger_btn').on('click', function() {
            const actionType = $(this).attr('data-action-type');
            if (actionType === 'smart_bar_status') {
                updateSettings();
            }
            $(this).attr('data-clicked', 1);
            $('#confirm-trigger-modal').modal('hide');
        });

        function updateSettings() {
            var value = $('#trigger_btn').attr('data-value');
            var type = $('#trigger_btn').attr('data-type');

            $.post('{{ route('business_settings.smart_bar_status') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            }).fail(function() {
                AIZ.plugins.notify('danger', '{{ translate('Network error') }}');
            });
        }

        $('#smart_bar_status').on('change', function() {
            if('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                $(this).prop('checked', !$(this).is(':checked'));
                return;
            }
            const isChecked = $(this).is(':checked');
            const confirmText = isChecked 
                ? "{{ translate('Are you sure you want to set this smart bar in product detail page?') }}"
                : "{{ translate('Are you sure you want to disable this smart bar in product detail page?') }}";
            const detailText = isChecked 
                ? "{{ translate('Customers will see a smart bar in product detail page.') }}"
                : "{{ translate('Customers will no longer see smart bar in product detail page.') }}";
            const btnText = isChecked 
                ? "{{ translate('Allow') }}"
                : "{{ translate('Disable') }}";
            $('#confirm_text').text(confirmText);
            $('#confirm_detail_text').text(detailText);
            $('#trigger_btn')
                .text(btnText)
                .attr('data-action-type', 'smart_bar_status')
                .attr('data-type', 'smart_bar_status')
                .attr('data-value', isChecked ? 1 : 0);
            $('#confirm-trigger-modal')
                .data('action-type', 'smart_bar_status')
                .modal('show');
        });  
        
        
        $('#confirm-trigger-modal').on('hidden.bs.modal', function () {
            const actionType = $(this).data('action-type');
            if ($('#trigger_btn').attr('data-clicked') == 1) {
                $('#trigger_btn').attr('data-clicked', '');
                $(this).removeData('action-type');
            } else {
                if (actionType === 'smart_bar_status') {
                    const current = $('#smart_bar_status').is(':checked');
                    $('#smart_bar_status').prop('checked', !current);
                } 
                else if (actionType === 'smart_bar_status') {
                    var id = $('#trigger_btn').attr('data-value');
                    if (id) {
                        var smart_bar_status = $('#trigger_btn').attr('data-smart_bar_status') == 1 ? false : true;
                        $('#trigger_alert_' + id).prop('checked', smart_bar_status);
                    }
                }
                $(this).removeData('action-type');
                $('#trigger_btn').removeAttr('data-action-type data-type data-value data-smart_bar_status');
            }
        });

    </script>
@endsection