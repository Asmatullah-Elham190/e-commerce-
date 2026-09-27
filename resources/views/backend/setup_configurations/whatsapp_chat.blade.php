@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('WhatsApp Chat Setting')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('whatsapp_chat.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Chat')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="whatsapp_chat" type="checkbox" @if (get_setting('whatsapp_chat') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Order')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0 ">
                                    <input value="1" id="whatsapp_order_switch" name="whatsapp_order" type="checkbox" @if (get_setting('whatsapp_order') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row message-area">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('For Seller Products')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0 ">
                                    <input value="1" id="whatsapp_order_seller_prods" name="whatsapp_order_seller_prods" type="checkbox" @if (get_setting('whatsapp_order_seller_prods') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                                <br>
                                <small class="text-info">{{translate('Get WhatsApp Order for Seller products')}}</small>
                            </div>
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('Order Message')}}</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="form-control" rows="5" placeholder="{{translate('Order Message')}}" name="order_messege_template">{{get_setting('order_messege_template')}}</textarea>
                            </div>

                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="WHATSAPP_NUMBER">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Number')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="WHATSAPP_NUMBER" value="{{  env('WHATSAPP_NUMBER') }}" placeholder="{{ translate('WhatsApp Number') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        function toggleMessageArea() {
            if ($('#whatsapp_order_switch').is(':checked')) {
                $('.message-area').removeClass('d-none');
            } else {
                $('.message-area').addClass('d-none');
            }
        }

        toggleMessageArea();

        $('#whatsapp_order_switch').on('change', function () {
            toggleMessageArea();
        });
    });
</script>

@endsection
