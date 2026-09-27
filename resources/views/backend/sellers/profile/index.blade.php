@extends('backend.layouts.app')

@section('content')
<div class="row seller-page">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <!-- Seller Banner -->
        <div class="position-relative text-center supplier-banner mx-auto" style="background-image: url('{{ static_asset('assets/img/seller-bg.png') }}');">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                <div class="mb-2" style="width: 80px; height: 80px;">
                    <img src="{{ uploaded_asset($shop->logo) }}"
                        alt="{{ $shop->name }}"
                        class="img-fluid rounded-circle h-100 w-100 object-fit-cover">
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-center mb-1">
                    <span class="font-weight-bold fs-18">{{ $shop->name }}</span>
                    @if($shop->verification_status)
                    <div class="icon-container ml-2" data-tooltip="Verified">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="_6819ed6f635ef55dbc567842d82cbaf7" data-name="6819ed6f635ef55dbc567842d82cbaf7" transform="translate(-1 -1)">
                                <path id="Path_42581" data-name="Path 42581" d="M9,1a8,8,0,1,0,8,8A8,8,0,0,0,9,1Z" fill="#00c96c" />
                                <path id="Path_42582" data-name="Path 42582" d="M14.822,8.821,9.731,13.912a.727.727,0,0,1-1.029,0L5.793,11,6.821,9.975,9.216,12.37l4.577-4.577Z" transform="translate(-1.307 -1.853)" fill="#fff" fill-rule="evenodd" />
                            </g>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="d-flex align-items-center mr-2 fs-13">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="12.8" viewBox="0 0 16 12.8">
                        <path id="Path_42644" data-name="Path 42644" d="M18,5.6A1.6,1.6,0,0,0,16.4,4H3.6A1.6,1.6,0,0,0,2,5.6v9.6a1.6,1.6,0,0,0,1.6,1.6H16.4A1.6,1.6,0,0,0,18,15.2Zm-1.6,0L10,9.592,3.6,5.6Zm0,9.6H3.6v-8l6.4,4,6.4-4Z" transform="translate(-2 -4)" fill="#9393a3" />
                    </svg>

                    <div class="mr-3 ml-2">{{ $shop->user->email }}</div>

                    @if($shop->user->phone)
                    <svg xmlns="http://www.w3.org/2000/svg" width="12.097" height="12" viewBox="0 0 12.097 12">
                        <path id="Path_42645" data-name="Path 42645" d="M9.621,13.151a5.627,5.627,0,0,1-2.492-.675A13.134,13.134,0,0,1,3.754,9.984,13.472,13.472,0,0,1,1.262,6.609C.38,4.792.328,3.286,1.21,2.456a.5.5,0,0,1,.727,0,.5.5,0,0,1,0,.727c-.519.519-.415,1.661.26,3.011A12.257,12.257,0,0,0,4.481,9.309,12.257,12.257,0,0,0,7.6,11.593c1.35.675,2.492.779,3.011.26a.514.514,0,0,1,.727.727,2.523,2.523,0,0,1-1.713.571Z" transform="translate(0 -1.065)" fill="#9393a3" />
                        <path id="Path_42646" data-name="Path 42646" d="M4.771,5.6A.5.5,0,0,1,4.3,5.285a.458.458,0,0,1,.208-.675l1.194-.571a.7.7,0,0,0,.415-.675A.525.525,0,0,0,5.913,3L4.2,1.235a.749.749,0,0,0-.675-.156l-.311.156-.831.883a.514.514,0,0,1-.727-.727L2.486.56A1.48,1.48,0,0,1,3.213.145,1.862,1.862,0,0,1,4.926.56L6.64,2.274a1.754,1.754,0,0,1,.467.986,1.792,1.792,0,0,1-.986,1.765L4.926,5.6c0-.052-.1,0-.156,0Zm6.593,6.022A.471.471,0,0,1,11,11.463a.5.5,0,0,1,0-.727l.831-.831a.37.37,0,0,0,.156-.311.749.749,0,0,0-.156-.675L10.118,7.206A1.3,1.3,0,0,0,9.755,7a.63.63,0,0,0-.675.415L8.509,8.607a.5.5,0,1,1-.883-.467L8.2,6.946A1.75,1.75,0,0,1,9.962,5.96a1.611,1.611,0,0,1,.934.467L12.61,8.14a1.625,1.625,0,0,1,.415,1.713,2.037,2.037,0,0,1-.415.727l-.883.883A.471.471,0,0,1,11.364,11.619Z" transform="translate(-0.445 0)" fill="#9393a3" />
                        <path id="Path_42647" data-name="Path 42647" d="M10.557,12.973a.738.738,0,0,1-.311-.1q-.934-.7-1.869-1.557A12.376,12.376,0,0,1,6.819,9.443a.527.527,0,0,1,.156-.727.549.549,0,0,1,.727.1,11.069,11.069,0,0,0,1.454,1.713,21.258,21.258,0,0,0,1.713,1.454.466.466,0,0,1,.1.727c-.052.208-.208.26-.415.26Z" transform="translate(-2.961 -4.106)" fill="#9393a3" />
                    </svg>

                    <div class="ml-2">{{ $shop->user->phone }}</div>
                    @endif
                </div>
                

            </div>
        </div>

        <!-- Tabs Section -->
        <section class="py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap border-bottom">
                <!-- Tabs -->
                 <div class="supplier-tab-wrapper">
                <ul class="nav nav-tabs supplier-tab" id="customTabs">
                    
                    <li class="nav-item">
                        <button class="nav-link" onclick="changeSellerTab(this, 'overview')">{{translate('Overview')}}</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" onclick="changeSellerTab(this, 'products')">{{translate('Items')}}</button>
                    </li>
                    <li class="nav-item">
                        <button id="order_tab" class="nav-link" onclick="changeSellerTab(this, 'orders')">{{translate('Orders')}}</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" onclick="changeSellerTab(this, 'payments')">{{translate('Payment History')}}</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" onclick="changeSellerTab(this, 'documents')">{{translate('Documents')}}</button>
                    </li>
                </ul>
                 </div>
                <!-- Right: Button and Icons -->
                <div class="d-flex align-items-center ml-auto mt-3 mt-md-0 mr-n5">

                    @can('product_delete' || 'order_delete')
                    <div class="dropdown mb-2 mb-md-0 mr-3 bulk-action-visibility d-none">
                        <button class="btn border dropdown-toggle py-1" type="button" data-toggle="dropdown">
                            {{translate('Bulk Action')}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                            <a class="dropdown-item fs-13 confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal"> {{translate('Delete selection')}}</a>
                        </div>
                    </div>
                    @endcan

                    <!-- Show on md and above -->
                    <div class="d-none d-lg-flex align-items-center mr-3">
                        <!-- Orders Button -->
                        <button onclick="changeSellerTab(this, 'orders' , 'order_tab')"
                            class="btn d-inline-flex align-items-center btn-demand font-weight-semibold">
                            <!-- SVG icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="12.699" height="16"
                                viewBox="0 0 12.699 16">
                                <g id="_901df7a6091730d2a30cc44cf8aa9e09" data-name="901df7a6091730d2a30cc44cf8aa9e09"
                                    transform="translate(-3.316)">
                                    <path id="Path_42632" data-name="Path 42632"
                                        d="M4.9,0a.669.669,0,0,0-.667.663V3.334a2.01,2.01,0,0,0,2,2H8.913A.668.668,0,0,0,8.913,4H6.236a.656.656,0,0,1-.664-.665V.663A.669.669,0,0,0,4.9,0Z"
                                        transform="translate(6.435 0)" fill="#fff" />
                                    <path id="Path_42633" data-name="Path 42633"
                                        d="M3.33,0A2.013,2.013,0,0,0,1.322,2V7.332a.669.669,0,1,0,1.339,0V2a.656.656,0,0,1,.669-.665H9.071l3.612,3.608V14a.656.656,0,0,1-.664.663H3.33A.656.656,0,0,1,2.661,14V5.666a.669.669,0,0,0-1.339,0V14A2.015,2.015,0,0,0,3.33,16h8.689a2.008,2.008,0,0,0,2-2V4.666a.667.667,0,0,0-.194-.474L9.819.2A.67.67,0,0,0,9.344,0Z"
                                        transform="translate(1.994 0)" fill="#fff" />
                                    <path id="Path_42634" data-name="Path 42634"
                                        d="M.458,5.038C.209,5.016.015,4.724.015,4.371s.194-.645.443-.666H6.11c.261,0,.472.3.472.668s-.211.668-.472.668Z"
                                        transform="translate(6.367 2.63)" fill="#fff" />
                                    <path id="Path_42637" data-name="Path 42637"
                                        d="M.458,5.038C.209,5.016.015,4.724.015,4.371s.194-.645.443-.666H6.11c.261,0,.472.3.472.668s-.211.668-.472.668Z"
                                        transform="translate(6.367 4.97)" fill="#fff" />
                                    <path id="Path_42638" data-name="Path 42638"
                                        d="M.458,5.038C.209,5.016.015,4.724.015,4.371s.194-.645.443-.666H6.11c.261,0,.472.3.472.668s-.211.668-.472.668Z"
                                        transform="translate(6.367 7.309)" fill="#fff" />
                                </g>
                            </svg>
                            <span class="ml-1">Orders</span>
                        </button>

                        <!-- Mail Icon -->
                        <div class="ml-2 icon-container" data-toggle="tooltip" title="Mail Seller">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                viewBox="0 0 32 32">
                                <g id="Rectangle_23615" data-name="Rectangle 23615" fill="#fff" stroke="#dce0e6"
                                    stroke-width="1">
                                    <rect width="32" height="32" rx="6" stroke="none" />
                                    <rect x="0.5" y="0.5" width="31" height="31" rx="5.5"
                                        fill="none" />
                                </g>
                                <path id="Path_42640" data-name="Path 42640"
                                    d="M18,5.6A1.6,1.6,0,0,0,16.4,4H3.6A1.6,1.6,0,0,0,2,5.6v9.6a1.6,1.6,0,0,0,1.6,1.6H16.4A1.6,1.6,0,0,0,18,15.2Zm-1.6,0L10,9.592,3.6,5.6Zm0,9.6H3.6v-8l6.4,4,6.4-4Z"
                                    transform="translate(6 6)" fill="#9393a3" />
                            </svg>
                        </div>

                        <!-- Menu Dropdown Icon -->
                        <div class="dropdown ml-2">
                            <a href="#"
                                class="text-muted p-0 border-0 bg-transparent shadow-none text-secondary"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                    viewBox="0 0 32 32">
                                    <g id="Rectangle_23616" data-name="Rectangle 23616" fill="#fff"
                                        stroke="#dce0e6" stroke-width="1">
                                        <rect width="32" height="32" rx="6" stroke="none" />
                                        <rect x="0.5" y="0.5" width="31" height="31" rx="5.5"
                                            fill="none" />
                                    </g>
                                    <g id="Group_30571" data-name="Group 30571" transform="translate(-1733 -445)">
                                        <circle id="Ellipse_1018" data-name="Ellipse 1018" cx="1.5"
                                            cy="1.5" r="1.5" transform="translate(1748 459.5)"
                                            fill="#9393a3" />
                                        <circle id="Ellipse_1019" data-name="Ellipse 1019" cx="1.5"
                                            cy="1.5" r="1.5" transform="translate(1748 453)" fill="#9393a3" />
                                        <circle id="Ellipse_1020" data-name="Ellipse 1020" cx="1.5"
                                            cy="1.5" r="1.5" transform="translate(1748 466)" fill="#9393a3" />
                                    </g>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                @can('login_as_seller')
                                <a href="{{route('sellers.login', encrypt($shop->id))}}" class="dropdown-item fs-13">
                                    {{translate('Log in as this Seller')}}
                                </a>
                                @endcan

                                @can('pay_to_seller')
                                <a href="javascript:void();" onclick="show_seller_payment_modal('{{$shop->id}}');" class="dropdown-item fs-13">
                                    {{translate('Go to Payment')}}
                                </a>
                                @endcan
                                @can('seller_payment_history')
                                <a href="{{route('sellers.payment_history', encrypt($shop->user_id))}}" class="dropdown-item fs-13">
                                    {{translate('Payment History')}}
                                </a>
                                @endcan
                                @can('edit_seller')
                                <a href="{{route('sellers.edit', encrypt($shop->id))}}" class="dropdown-item fs-13">
                                    {{translate('Edit')}}
                                </a>
                                @endcan
                                @can('ban_seller')
                                @if($shop->user->banned != 1)
                                <a href="javascript:void();" onclick="confirm_ban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item fs-13">
                                    {{translate('Ban this seller')}}
                                    <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                </a>
                                @else
                                <a href="javascript:void();" onclick="confirm_unban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item fs-13">
                                    {{translate('Unban this seller')}}
                                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                                </a>
                                @endif
                                @endcan
                                @can('mark_seller_suspected')
                                @if($shop->user->is_suspicious == 1)
                                <a href="javascript:void();" onclick="confirm_suspicious('{{route('seller.suspicious', encrypt($shop->user->id))}}', true);" class="dropdown-item">
                                    {{ translate(" Mark as " . ($shop->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                </a>
                                @else
                                <a href="javascript:void();" onclick="confirm_suspicious('{{route('seller.suspicious', encrypt($shop->user->id))}}', false);" class="dropdown-item">
                                    {{ translate(" Mark as " . ($shop->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                </a>
                                @endif
                                @endcan


                                @can('delete_seller')
                                <a href="javascript:void();" class="dropdown-item confirm-delete" data-href="{{route('sellers.destroy', $shop->id)}}">
                                    {{translate('Delete')}}
                                </a>
                                @endcan

                            </div>
                        </div>
                    </div>


                    <!-- Show on sm and below -->
                    <div class="dropdown d-lg-none mb-2">
                        <button class="btn btn-light border dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item d-flex align-items-center"  onclick="changeSellerTab(this, 'orders' , 'order_tab')">
                                <!-- SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="12.699" height="16"
                                    viewBox="0 0 12.699 16">
                                    <g id="_901df7a6091730d2a30cc44cf8aa9e09"
                                        data-name="901df7a6091730d2a30cc44cf8aa9e09" transform="translate(-3.316)">
                                        <path id="Path_42632" data-name="Path 42632"
                                            d="M4.9,0a.669.669,0,0,0-.667.663V3.334a2.01,2.01,0,0,0,2,2H8.913A.668.668,0,0,0,8.913,4H6.236a.656.656,0,0,1-.664-.665V.663A.669.669,0,0,0,4.9,0Z"
                                            transform="translate(6.435 0)" fill="#fff" />
                                        <path id="Path_42633" data-name="Path 42633"
                                            d="M3.33,0A2.013,2.013,0,0,0,1.322,2V7.332a.669.669,0,1,0,1.339,0V2a.656.656,0,0,1,.669-.665H9.071l3.612,3.608V14a.656.656,0,0,1-.664.663H3.33A.656.656,0,0,1,2.661,14V5.666a.669.669,0,0,0-1.339,0V14A2.015,2.015,0,0,0,3.33,16h8.689a2.008,2.008,0,0,0,2-2V4.666a.667.667,0,0,0-.194-.474L9.819.2A.67.67,0,0,0,9.344,0Z"
                                            transform="translate(1.994 0)" fill="#fff" />
                                        <path id="Path_42634" data-name="Path 42634"
                                            d="M.458,5.038C.209,5.016.015,4.724.015,4.371s.194-.645.443-.666H6.11c.261,0,.472.3.472.668s-.211.668-.472.668Z"
                                            transform="translate(6.367 2.63)" fill="#fff" />
                                        <path id="Path_42637" data-name="Path 42637"
                                            d="M.458,5.038C.209,5.016.015,4.724.015,4.371s.194-.645.443-.666H6.11c.261,0,.472.3.472.668s-.211.668-.472.668Z"
                                            transform="translate(6.367 4.97)" fill="#fff" />
                                        <path id="Path_42638" data-name="Path 42638"
                                            d="M.458,5.038C.209,5.016.015,4.724.015,4.371s.194-.645.443-.666H6.11c.261,0,.472.3.472.668s-.211.668-.472.668Z"
                                            transform="translate(6.367 7.309)" fill="#fff" />
                                    </g>
                                </svg>
                                <span class="ml-2">Orders</span>
                            </button>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                    viewBox="0 0 32 32">
                                    <g id="Rectangle_23615" data-name="Rectangle 23615" fill="#fff"
                                        stroke="#dce0e6" stroke-width="1">
                                        <rect width="32" height="32" rx="6" stroke="none" />
                                        <rect x="0.5" y="0.5" width="31" height="31" rx="5.5"
                                            fill="none" />
                                    </g>
                                    <path id="Path_42640" data-name="Path 42640"
                                        d="M18,5.6A1.6,1.6,0,0,0,16.4,4H3.6A1.6,1.6,0,0,0,2,5.6v9.6a1.6,1.6,0,0,0,1.6,1.6H16.4A1.6,1.6,0,0,0,18,15.2Zm-1.6,0L10,9.592,3.6,5.6Zm0,9.6H3.6v-8l6.4,4,6.4-4Z"
                                        transform="translate(6 6)" fill="#9393a3" />
                                </svg>
                                <span class="ml-2">Mail Seller</span>
                            </a>
                            <div class="dropdown-divider"></div>
                             @can('login_as_seller')
                                <a href="{{route('sellers.login', encrypt($shop->id))}}" class="dropdown-item fs-13">
                                    {{translate('Log in as this Seller')}}
                                </a>
                                @endcan

                                @can('pay_to_seller')
                                <a href="javascript:void();" onclick="show_seller_payment_modal('{{$shop->id}}');" class="dropdown-item fs-13">
                                    {{translate('Go to Payment')}}
                                </a>
                                @endcan
                                @can('seller_payment_history')
                                <a href="{{route('sellers.payment_history', encrypt($shop->user_id))}}" class="dropdown-item fs-13">
                                    {{translate('Payment History')}}
                                </a>
                                @endcan
                                @can('edit_seller')
                                <a href="{{route('sellers.edit', encrypt($shop->id))}}" class="dropdown-item fs-13">
                                    {{translate('Edit')}}
                                </a>
                                @endcan
                                @can('ban_seller')
                                @if($shop->user->banned != 1)
                                <a href="javascript:void();" onclick="confirm_ban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item fs-13">
                                    {{translate('Ban this seller')}}
                                    <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                </a>
                                @else
                                <a href="javascript:void();" onclick="confirm_unban('{{route('sellers.ban', $shop->id)}}');" class="dropdown-item fs-13">
                                    {{translate('Unban this seller')}}
                                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                                </a>
                                @endif
                                @endcan
                                @can('mark_seller_suspected')
                                @if($shop->user->is_suspicious == 1)
                                <a href="javascript:void();" onclick="confirm_suspicious('{{route('seller.suspicious', encrypt($shop->user->id))}}', true);" class="dropdown-item">
                                    {{ translate(" Mark as " . ($shop->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                </a>
                                @else
                                <a href="javascript:void();" onclick="confirm_suspicious('{{route('seller.suspicious', encrypt($shop->user->id))}}', false);" class="dropdown-item">
                                    {{ translate(" Mark as " . ($shop->user->is_suspicious == 1 ? 'unsuspect' : 'suspicious') . " ") }}
                                </a>
                                @endif
                                @endcan


                                @can('delete_seller')
                                <a href="javascript:void();" class="dropdown-item text-danger confirm-delete" data-href="{{route('sellers.destroy', $shop->id)}}">
                                    {{translate('Delete')}}
                                </a>
                                @endcan
                        </div>
                    </div>
                              
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content pt-4" id="sellerTabContent">
                <div class="tab-pane fade show active" id="tab-content">
                    <!-- AJAX content will load here -->
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-1"></div>
</div>
@endsection

@section('modal')
@include('modals.delete_modal')
@include('modals.bulk_delete_modal')
{{-- Unpaid Order Payment Notification --}}
<div id="complete_unpaid_order_payment" class="modal fade">
    <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content pb-2rem px-2rem">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <form class="form-horizontal" action="{{ route('unpaid_order_payment_notification') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center">
                    <input type="hidden" name="order_ids" value="" id="order_ids">
                    <p class="mt-2 mb-2 fs-16 fw-700">{{ translate('Are you sure to send notification for the selected orders?') }}</p>
                    <button type="submit" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px">{{ translate('Send Notification') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Seller Common Modal -->
<div class="modal fade" id="seller_common_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="seller-common-content">

        </div>
    </div>
</div>


<!-- Reusable Confirmation Modal -->
<div class="modal fade" id="universal-confirm-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6" id="universal-modal-title">{{ translate('Confirmation') }}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="universal-modal-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <a class="btn btn-primary" id="universal-confirm-button">{{ translate('Proceed!') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- docs view modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{translate('File Preview')}}</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="min-height: 500px;">
        <div id="filePreviewContainer" class="text-center"></div>
      </div>
    </div>
  </div>
</div>

{{-- edit gstin --}}
<div class="modal fade" id="seller_gstin_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="seller-gstin-content">
            <form class="form-horizontal" action="{{ route('sellers.seller_gstin_update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Edit Seller GSTIN Number')}}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                    </button>
                </div>
                <input type="hidden" name="id" value="{{encrypt($shop->id)}}">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-from-label" for="gstin">{{translate('GSTIN')}}</label>
                        <input type="text" lang="en" name="gstin_number" value="{{ (json_decode($shop->business_info, true)['gstin'] ?? '') }}" class="form-control" required="">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{translate('Save Change')}}</button>
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
   let currentSellerTab = 'overview';

 function getSellerTabData(tab, page = 1) {
    $.ajax({
        url: "{{ route('sellers.profile.tab', $shop->id) }}" + "?page=" + page,
        method: 'GET',
        data: { tab: tab },
        success: function(response) {
            $('#tab-content').html(response.html);
            
            // Initialize FooTable after content loads
            setTimeout(function() {
                if (typeof AIZ !== 'undefined' && AIZ.plugins.fooTable) {
                    AIZ.plugins.sectionFooTable('#tab-content');
                    $('.aiz-table').trigger('footable_redraw');

                    if (AIZ.plugins.bootstrapSelect) {
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            }, 100);
        },
        error: function() {
            $('#tab-content').html('<div class="text-danger p-4">Failed to load data.</div>');
        }
    });
}


    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'overview'; // fallback to overview

        const button = Array.from(document.querySelectorAll('#customTabs .nav-link')).find(btn =>
            btn.getAttribute('onclick').includes(`'${tab}'`)
        );

        if (button) {
            changeSellerTab(button, tab);
        } else {
            getSellerTabData('overview');
        }
    });


    function changeSellerTab(button, tab, active_id = null) {
        document.querySelectorAll('#customTabs .nav-link').forEach(el => el.classList.remove('active'));
        button.classList.add('active');
        if (active_id) {
            document.getElementById(active_id).classList.add('active');
        }
        getSellerTabData(tab);
        if(tab== 'orders' || tab == 'products') {
           document.querySelector('.bulk-action-visibility').classList.remove('d-none');
        } else {
           document.querySelector('.bulk-action-visibility').classList.add('d-none');
        }

        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }



    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        const activeTabId = $(this).attr('href').split('tab=')[1].split('&')[0];
        getSellerTabData(activeTabId, page);
    });
    $(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault();
        let url = $(this).data('href');
        $('#delete-link').attr('href', url);
        $('#delete-modal').modal('show');
    });

    $(document).on('click', '.check-all', function() {
        $('.check-one').prop('checked', $(this).is(':checked'));
    });
    $(document).on('click', '.check-one', function() {
        let allChecked = $('.check-one').length === $('.check-one:checked').length;
        $('.check-all').prop('checked', allChecked);
    });

    function bulk_delete() {
        var data = new FormData($('#sort_' + currentSellerTab)[0]);
        let url = '';

        if (currentSellerTab == 'products') {
            url = "{{ route('bulk-product-delete') }}";
        } else if (currentSellerTab == 'orders') {
            url = "{{ route('bulk-order-delete') }}";
        }
        let selectedIds = [];
        $('.check-one:checked').each(function() {
            selectedIds.push($(this).val());
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 1) {
                    selectedIds.forEach(id => {
                        $(`.row-item[data-id="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    });

                    $('.check-all').prop('checked', false);
                    AIZ.plugins.notify('success', 'Selected items deleted successfully');
                    $('#bulk-delete-modal').modal('hide');
                } else {
                    AIZ.plugins.notify('danger', 'Delete failed. Try again.');
                }
            },
            error: function() {
                AIZ.plugins.notify('danger', 'Something went wrong.');
            }
        });
    }

    // Unpaid Order Payment Notification
    function unpaid_order_payment_notification(order_id) {
        var orderIds = [];
        orderIds.push(order_id);
        $('#order_ids').val(orderIds);
        $('#complete_unpaid_order_payment').modal('show', {
            backdrop: 'static'
        });
    }

    function printInvoice(orderId) {
        url = "{{ route('invoice.print', ':id') }}";
        url = url.replace(':id', orderId);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(html) {
                // Open in new window
                var printWindow = window.open('', '_blank', 'width=800,height=600');
                printWindow.document.open();
                printWindow.document.write(html);
                printWindow.document.close();

                // Wait for content to load before printing
                printWindow.onload = function() {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close(); // Optional: close after printing
                };
            },
            error: function() {
                alert('Failed to load invoice for printing.');
            }
        });
    }

          function sort_sellers(el){
            $('#sort_sellers').submit();
        }

        // Ban
        function confirm_ban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to ban this seller?") }}'
            });
        }
        // Unban
        function confirm_unban(url) {
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to unban this seller?") }}'
            });
        }
        // Suspicious / Unsuspicious
        function confirm_suspicious(url, isSuspicious) {
            const action = isSuspicious ? 'unsuspect' : 'suspect';
            showConfirmationModal({
                url: url,
                message: '{{ translate("Do you really want to") }} ' + action + ' {{ translate("this seller?") }}'
            });
        }

        function showConfirmationModal({ url, message }) {
            if ('{{ env('DEMO_MODE') }}' === 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            document.getElementById('universal-modal-message').innerText = message;
            document.getElementById('universal-confirm-button').setAttribute('href', url);

            $('#universal-confirm-modal').modal('show', { backdrop: 'static' });
        }

        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#seller_common_modal #seller-common-content').html(data);
                $('#seller_common_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_seller_verification_info(id){
            $.post('{{ route('sellers.verification_info_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#seller_common_modal #seller-common-content').html(data);
                $('#seller_common_modal').modal('show', {backdrop: 'static'});
            });
        }


        function show_seller_gstin_edit_modal(id){
                $('#seller_gstin_modal').modal('show', {backdrop: 'static'});
        }


        //print Verification file like ctrl +P
        function printFile(fileUrl) {
            let oldIframe = document.getElementById('print-iframe');
            if (oldIframe) oldIframe.remove();
            let iframe = document.createElement('iframe');
            iframe.id = 'print-iframe';
            iframe.style.display = 'none';
            iframe.src = fileUrl;

            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 300);
            };

            document.body.appendChild(iframe);
        }

        // View file in modal
        function showFileInModal(fileUrl) {
            const ext = fileUrl.split('.').pop().toLowerCase();
            const container = document.getElementById('filePreviewContainer');
            container.innerHTML = '';

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                const img = document.createElement('img');
                img.src = fileUrl;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '600px';
                container.appendChild(img);
            } else if (ext === 'pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = fileUrl;
                iframe.style.width = '100%';
                iframe.style.height = '600px';
                iframe.frameBorder = 0;
                container.appendChild(iframe);
            } else {
                container.innerHTML = '<p class="text-danger">Unsupported file format.</p>';
            }

            $('#filePreviewModal').modal('show');
        }

        

</script>

@endsection