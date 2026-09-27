<div class="row">
    <div class="col-lg-5 d-flex flex-column gap-3">

        <!-- 1st Card -->
        <div class="border-2 border-primary rounded-2 p-4">
            <div class="text-muted mb-2">{{translate('Total Products')}}</div>
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ App\Models\Product::where('user_id', $shop->user->id)->get()->count() }}</h4>
                <a href="{{ route('shop.visit', $shop->slug) }}" target="_blank" class="text-primary underline fw-600">Browse Products on Client Side</a>
            </div>
        </div>



        <!-- 3rd Card -->
        <div class="border-2 border-color rounded-2 p-4 mt-4">
            <div class="text-muted mb-2">{{translate('Total Orders Received')}}</div>
            <h4>{{ App\Models\OrderDetail::where('seller_id', $shop->user->id)->get()->count() }}</h4>

            <!-- Row 1 -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center fs-12 font-weight-bold">
                    <!-- Example SVG icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="2" viewBox="0 0 12 2" class="mr-2">
                        <rect id="Rectangle_23660" data-name="Rectangle 23660" width="12" height="2" fill="#e50263" />
                    </svg>

                    {{translate('Total Delivered Orders')}}
                </div>
                <div class="text-primary fw-semibold">{{ App\Models\Order::where('seller_id', $shop->user->id)->where('delivery_status', 'delivered')->get()->count() }}</div>
            </div>

            <!-- Row 2 -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center fs-12 font-weight-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="2" viewBox="0 0 12 2" class="mr-2">
                        <rect id="Rectangle_23661" data-name="Rectangle 23661" width="12" height="2" fill="#00b24a" />
                    </svg>
                    {{translate('Total Pending Orders')}}
                </div>
                <div class="text-primary fw-semibold">{{ App\Models\Order::where('seller_id', $shop->user->id)->where('delivery_status', 'pending')->get()->count() }}</div>
            </div>
        </div>


        <!-- 2nd Card -->
        <div class="border-none bg-color rounded-2 p-4 mt-4">
            <div class="text-muted mb-2">{{translate('Total Sold Amount')}}</div>
            @php
            $orderDetails = \App\Models\OrderDetail::where('seller_id', $shop->user->id)->get();
            $total = 0;
            foreach ($orderDetails as $key => $orderDetail) {
            if($orderDetail->order != null && $orderDetail->order->payment_status == 'paid'){
            $total += $orderDetail->price;
            }
            }
            @endphp
            <h4 class="mb-0">{{ single_price($total) }}</h4>
        </div>


        <!-- 4th Card -->
        <div class="border-1 border-color rounded-2 p-4 mt-4">
            <div class="text-muted mb-2">{{translate('Wallet Balance')}}</div>
            <h4>{{ single_price($shop->user->balance) }}</h4>
            <div class="text-muted mb-2 mt-3">{{translate('Commission Paid to Platform')}}</div>
            <h4>
                @php
                $admin_commission = App\Models\CommissionHistory::where('seller_id', $shop->user->id)->sum('admin_commission');
                @endphp
                {{ single_price($admin_commission) }}
            </h4>

            <div class="text-muted mb-2 mt-3">{{translate('Total Withdrawn Amount')}}</div>
            <h4>
                @php
                $withdrawnAmount = App\Models\SellerWithdrawRequest::where('user_id', $shop->user->id)->where('status', 1)->sum('amount');
                @endphp
                {{ single_price($withdrawnAmount) }}
            </h4>

            <div class="d-flex justify-content-between align-items-center mt-5 font-weight-bold">
                {{translate('Last Payout Date')}}
                <div class="font-weight-bold">{{ optional(App\Models\SellerWithdrawRequest::where('user_id', $shop->user->id)->where('status', 1)->latest()->first())->created_at?->format('d M, Y') ?? '' }}</div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <a href="#" class="text-primary underline fw-600">{{translate('Pending Withdrawal Request')}}</a>
                <div class="font-weight-bold">{{ App\Models\SellerWithdrawRequest::where('user_id', $shop->user->id)->where('status', 0)->get()->count() }}</div>
            </div>
        </div>



    </div>

    <div class="col-lg-7 d-flex flex-column gap-3">
        @if($shop->verification_status == 1 && $shop->verification_info != null)
        <div class="rounded-2 p-4 mt-4 mt-md-0 bg-color mb-3" style="border: none;">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <div>{{translate('Verification Status')}}</div>
                    <p class="font-weight-bold ml-3 mb-0">{{translate('Verified')}}</p>
                </div>
                <a href="javascript:void();" onclick="show_seller_verification_info('{{$shop->id}}');"
                    class="ml-auto text-muted fs-12"
                    style="text-decoration: underline;">
                    {{translate('View Submitted Form')}}
                </a>
            </div>
        </div>

        @elseif($shop->verification_status == 1 && $shop->verification_info == null)
        <div class="rounded-2 p-4 mt-4 mt-md-0 bg-color mb-3" style="border: none;">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <div>{{translate('Verification Status')}}</div>
                    <p class="font-weight-bold ml-3 mb-0">{{translate('Verified')}}</p>
                </div>
                <span class="ml-auto text-muted fs-12">{{translate('By Admin')}}</span>
            </div>
        </div>

        @elseif($shop->verification_status != 1 && $shop->verification_info != null)
        <div class="rounded-2 p-4 mt-4 mt-md-0 bg-color2 mb-3" style="border: none;">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <div>{{translate('Verification Status')}}</div>
                    <p class="font-weight-bold ml-3 mb-0">{{translate('Pending Approval')}}</p>
                </div>
                <a href="javascript:void();" onclick="show_seller_verification_info('{{$shop->id}}');"
                    class="ml-auto text-muted fs-12"
                    style="text-decoration: underline;">
                    {{translate('View Submitted Form')}}
                </a>
            </div>
        </div>
        @else

        <div class="rounded-2 p-4 mt-4 mt-md-0 bg-color3 mb-3" style="border: none;">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <div>{{translate('Verification Status')}}</div>
                    <p class="font-weight-bold ml-3 mb-0">{{translate('Not Applied')}}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- First Card - Supplier Info -->
        <div class="card rounded-2 border-color card-no-shadow mt-2 mt-md-2">
            <div class="card-body p-0 ">
                <div class="d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="card-title p-3 font-weight-bold mb-0">{{translate('Seller Info')}}</h6>
                    <!-- <span class="text-muted mr-3"> <i class="las la-pen border rounded p-2"></i> </span> -->
                </div>

                <div class="p-3">
                    <!-- Supplier Info Table -->
                    <div class="d-flex flex-column fs-13">
                        <!-- Row 1 -->
                        <div class="d-flex py-2 border-bottom-dashed2">
                            <div class="w-210px fw-medium product-category-color">{{translate('Name')}}</div>
                            <div class="w-50">{{ $shop->user->name }}</div>
                        </div>
                        <!-- Row 2 -->
                        <div class="d-flex py-2 border-bottom-dashed2">
                            <div class="w-210px fw-medium product-category-color">{{translate('Email')}}</div>
                            <div class="w-50">{{ $shop->user->email }}</div>
                        </div>
                        <!-- Row 3 -->
                        <div class="d-flex py-2 border-bottom-dashed2">
                            <div class="w-210px fw-medium product-category-color">{{translate('Phone Number')}}</div>
                            <div class="w-50">{{ $shop->user->phone }}</div>
                        </div>
                        <!-- Row 4 -->
                        <div class="d-flex py-2 border-bottom-dashed2">
                            <div class="w-210px fw-medium product-category-color">{{translate('Account Creation')}}</div>
                            <div class="w-50">{{ $shop->created_at->format('d F, Y') }}</div>
                        </div>
                        <!-- Row 5 -->
                        <div class="d-flex py-2 border-bottom-dashed2">
                            <div class="w-210px fw-medium product-category-color">{{translate('Last Login Date')}}</div>
                            <div class="w-50">{{ $shop->last_login ? $shop->last_login->format('d F, Y') : 'N/A' }}</div>
                        </div>
                        <!-- Row 6 -->
                        <div class="d-flex py-2 align-items-start">
                            <div class="w-210px fw-medium product-category-color">{{translate('Status')}}</div>
                            <div class="d-flex gap-2 flex-wrap">
                                @if($shop->user->banned != 1)
                                <!-- Active -->
                                <div class="status-badge badge-active">{{translate('Active')}}</div>
                                <!-- Blocked -->
                                @elseif($shop->user->banned == 1)
                                <div class="status-badge badge-blocked">{{translate('Blocked')}}</div>
                                @endif
                                <!-- Suspicious -->
                                @if($shop->user->is_suspicious == 1)
                                <div class="status-badge badge-suspicious ml-2">{{translate('Suspicious')}}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Address Card - Single Column Layout -->
        <div class="card rounded-2 border-color card-no-shadow mt-1">
            <div class="card-body p-0">
                <h6 class="card-title p-3 border-bottom font-weight-bold mb-0">{{translate('Address')}}</h6>

                <div class="p-3">
                    <!-- Address List -->
                    <div class="d-flex flex-column fs-13 gap-2">
                        <!-- Address Block 1 -->
                        @if($default_shipping_address)
                        <div class="border-bottom-dashed2 pb-2">
                            <div class="text-color font-weight-bold mb-1">{{translate('Default Shipping Address')}}</div>
                            <div>
                                {{ $default_shipping_address->address }},
                                {{ $default_shipping_address->area ? $default_shipping_address->area->name . ',' : '' }}
                                {{ $default_shipping_address->city ? $default_shipping_address->city->name . ',' : '' }}
                                {{ $default_shipping_address->state ? $default_shipping_address->state->name : '' }}
                                {{ '-'. $default_shipping_address->postal_code }},
                                {{ $default_shipping_address->country ? $default_shipping_address->country->name : '' }}
                            </div>
                        </div>
                        @endif

                        <!-- Address Block 2 -->
                        @if($addresses && count($addresses))
                        <div class="border-bottom-dashed2 pb-2 mt-0">
                            <div class="text-muted font-weight-bold">{{translate('Other Address')}}</div>
                            @foreach($addresses as $address)
                            <div class="mb-2 ">
                                {{ $address->address }},
                                {{ $address->area ? $address->area->name . ',' : '' }}
                                {{ $address->city ? $address->city->name . ',' : '' }}
                                {{ $address->state ? $address->state->name : '' }}
                                {{ '-'. $address->postal_code }},
                                {{ $address->country ? $address->country->name : '' }}
                            </div>
                            @endforeach
                        </div>
                        @endif


                        <!-- Address Block 1 -->
                        {{--<div class="border-bottom-dashed2 pb-2">
                                                <div class="text-color font-weight-bold mb-1">Default Billing Address</div>
                                                <div>1713 Greenfelder Plaza, North Winonaport,<br>Florida - 44649, Benin</div>
                                            </div>--}}


                        {{--
                                            <!-- Address Block 3 -->
                                            <div>
                                                <div class="text-muted font-weight-bold mb-1">Warehouse Address</div>
                                                <div>88 Holly Ridge Drive, Port Elizabeth,<br>New Jersey - 8854, South Africa</div>
                                            </div>
                                            --}}
                    </div>

                </div>
            </div>
        </div>

        @if(addon_is_activated('gst_system'))
        <!-- GST Card - Single Column Layout -->
        @php
        $business_info = json_decode($shop->business_info, true);
        @endphp
        <div class="card rounded-2 border-color card-no-shadow mt-1">
            <div class="card-body p-0">
                <h6 class="card-title p-3 border-bottom font-weight-bold mb-0">{{translate('GST Information')}}</h6>

                <div class="p-3">
                    <!-- Address List -->
                    <div class="d-flex flex-column fs-13 gap-2">
                        <!-- Address Gstin -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div><span class="font-weight-bold mb-1">{{translate('GSTIN')}}</span>: <span>{{ $business_info['gstin'] ?? '' }}</span></div>
                            
                            <button class="w-30px h-30px bg-white border border-soft-light rounded-1 d-flex align-items-center justify-content-center" onclick="show_seller_gstin_edit_modal()">
                                <i class="las la-edit text-gray"></i>
                            </button>
                        </div>

                        
                    </div>

                </div>
            </div>
        </div>
        @endif
    </div>
</div>