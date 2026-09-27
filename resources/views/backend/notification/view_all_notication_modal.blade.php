<!-- Offcanvas Header -->
<div class="border-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class="fs-16 fw-700 m-0 text-truncate">Notification</h5>
        <button onclick="closeglobalRightOffcanvas()" class="border-0 bg-transparent">
            <i class="las la-times fs-24 text-gray hov-text-blue has-transition"></i>
        </button>
    </div>
</div>
<!-- Offcanvas Body -->
<div class="global-right-offcanvas-body position-absolute w-100 h-100 py-2 px-0" style="padding-bottom: 200px!important;">
    <div class="notifications">
        <ul class="nav nav-tabs nav-justified px-30px border-blue" role="tablist">
            <li class="nav-item flex-grow-0">
                <a class="nav-link text-reset active px-0 mr-3" data-toggle="tab" data-type="order"
                    href="javascript:void(0);" data-target="#orders-notifications" role="tab"
                    id="orders-tab">{{ translate('Orders') }}</a>
            </li>
            @if (addon_is_activated('preorder'))
                <li class="nav-item flex-grow-0">
                    <a class="nav-link text-reset px-0 mx-3 position-relative" data-toggle="tab" data-type="preorder" href="javascript:void(0);"
                        data-target="#preorders-notifications" role="tab"
                        id="preorders-tab"><span>{{ translate('Preorders') }}</span>
                        <span class="badge badge-sm badge-dot badge-circle badge-danger position-absolute absolute-top-right"
                            style="top: 1px!important; right: -14px!important;"></span>
                    </a>
                </li>
            @endif
            <li class="nav-item flex-grow-0">
                <a class="nav-link text-reset px-0 mx-3" data-toggle="tab" data-type="seller" href="javascript:void(0);"
                    data-target="#sellers-notifications" role="tab" id="sellers-tab">{{ translate('Sellers') }}</a>
            </li>
            <li class="nav-item flex-grow-0">
                <a class="nav-link text-reset px-0 ml-3" data-toggle="tab" data-type="seller" href="javascript:void(0);"
                    data-target="#payouts-notifications" role="tab" id="sellers-tab">{{ translate('Payouts') }}</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="orders-notifications" role="tabpanel">
                <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->take(20)->get()" />
            </div>
            <div class="tab-pane" id="preorders-notifications" role="tabpanel">
                <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PreorderNotification')->take(20)->get()" />
            </div>
            <div class="tab-pane" id="sellers-notifications" role="tabpanel">
                <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'like', '%shop%')->take(20)->get()" />
            </div>
            <div class="tab-pane" id="payouts-notifications" role="tabpanel">
                <x-unread_notification :notifications="auth()->user()->unreadNotifications()->where('type', 'App\Notifications\PayoutNotification')->take(20)->get()" />
            </div>
        </div>
    </div>
</div>
<!-- Offcanvas Footer -->
<div class="w-100 px-30px position-absolute bottom-0 bg-white global-right-offcavas-footer pt-20px pb-20px">
    <a href="{{ route('admin.all-notifications') }}"
        class="fs-14 fw-500 text-reset hov-text-blue text-center d-block py-2 has-transition">
        {{ translate('View All Notifications') }}
    </a>
</div>