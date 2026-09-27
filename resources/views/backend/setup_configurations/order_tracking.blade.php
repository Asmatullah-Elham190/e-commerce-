@extends('backend.layouts.app')

@section('content')
<div class="col-lg-8 mx-auto">
    <div class="mb-3 border border-1 border-gray-300 rounded-1 p-4 d-flex flex-wrap flex-md-nowrap align-items-center justify-content-between" style="gap: 18px">
        <div class="d-flex flex-wrap flex-md-nowrap align-items-center" style="gap: 12px">
            <div
                class="w-60px h-60px rounded-1 d-flex align-items-center justify-content-center overflow-hidden bg-soft-lime flex-shrink-0">
                <img src="{{ static_asset('assets/img/business-settings/order-tracking.svg') }}"
                    alt="Setting Icon">
            </div>
            <div>
                <h6 class="fw-semibold text-dark">{{translate('Order Tracking')}}</h6>
            <span
                class="fs-12 fw-400 d-block text-gray">{{translate('Set up tracking codes and tracking options to keep customers informed about their order status and delivery progress.')}}</span>
            </div>
        </div>
        <a href="{{ route('business_settings.index') }}" class="fs-14 fw-500 text-reset text-blue hov-text-white has-transition bg-soft-blue hov-bg-blue rounded-pill py-2 px-3 flex-shrink-0">
            <i class="las la-angle-left fs-14"></i>
            {{translate('Back to Business Settings')}}
        </a>
    </div>
    <div class="card">

        <div class="card-body">
            <form action="{{ route('order_tracking.config.update') }}" method="POST">
                @csrf
                <div class="form-group md-3">
                    <label class="col-from-label">{{translate('Name')}}</label><span class="text-danger"> *</span>
                    <input type="text" name="name" class="form-control" value="{{ $order_tracking['name'] ?? '' }}" required maxlength="120">
                </div>
                <div class="form-group md-3">
                    <label class="col-from-label">{{translate('Moto')}}</label><span class="text-danger"> *</span>
                    <input type="text" name="moto" class="form-control" value="{{ $order_tracking['moto'] ?? '' }}" required maxlength="120">
                </div>
                <div class="form-group mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                            <input type="checkbox" name="enable_order_tracking" value="1"
                                {{ ($order_tracking['enable_order_tracking'] ?? 0) == 1 ? 'checked' : '' }}>
                            <span></span>
                        </label>
                        <span class="d-block" style="margin-top: -6px">{{ translate('Enable Order Tracking') }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 d-block">
                        <label class="col-form-label">{{ translate('Logo') }}</label>
                        <div class="add-product-page-content">
                            <div class="img-upload-container">
                                <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                    data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div
                                        class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                        <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                            class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                    </div>
                                    <input type="hidden" name="logo" class="selected-files"
                                        value="{{ $order_tracking['logo'] ?? '' }}">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
