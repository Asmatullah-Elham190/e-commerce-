
@php
    $route = Route::currentRouteName() == 'sellers.index' ? 'all_seller_route' : 'seller_rating_followers';
@endphp
<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if(auth()->user()->can('delete_seller') && ($route == 'all_seller_route'))
                    <th>
                        <div class="form-group">
                            <div class="aiz-checkbox-inline">
                                <label class="aiz-checkbox pt-5px d-block">
                                    <input type="checkbox" class="check-all">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </div>
                    </th>
                @else
                    <th class="hide-lg">#</th>
                @endif
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Logo') }}</th>
                <th class=" text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">{{ translate('Shop Info') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Contact Details') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary">{{ translate('Business Info') }}</th>
                @if($route == 'all_seller_route')
                    <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Verification') }}</th>
                    <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Verification Approval') }}</th>
                @else
                    <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Rating') }}</th>
                    <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Followers') }}</th>
                    <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Custom Followers') }}</th>
                @endif
                <th class="text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Options') }}</th>
            </tr>
        </thead>

        <tbody>
            @forelse($shops as $key => $shop)
            <tr class="data-row">
                <td class="align-middle w-40px">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if(auth()->user()->can('delete_seller') && ($route == 'all_seller_route'))
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox">
                                <input type="checkbox" class="check-one" name="id[]" value="{{ $shop->id }}">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                    @else
                        <div class="form-group d-inline-block">{{ ($key+1) + ($shops->currentPage() - 1)*$shops->perPage() }}</div>
                    @endif
                </td>

                <td data-label="Logo" class="w-60px w-md-80px w-md-100px">
                    <div class="w-40px h-40px w-sm-60px h-sm-60px w-md-80px h-md-80px rounded-2 overflow-hidden border">
                        <img src="{{ uploaded_asset($shop->logo) }}" alt="Logo" class="img-fit" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </td>

                <td data-label="Shop Info">
                    <div class=" mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Shop Name') }}</span>
                        <p class="fs-14 fw-700 m-0">
                            @if ($route == 'all_seller_route')
                                @if($shop->user->banned == 1) 
                                    <i class="las la-ban text-danger" aria-hidden="true"></i> 
                                @elseif($shop->user->is_suspicious == 1) 
                                    <i class="las la-exclamation-circle text-info" aria-hidden="true"></i> 
                                @else
                                    <i class="las la-check-circle  text-success" aria-hidden="true"></i>
                                @endif
                            @endif
                            {{ $shop->name }}
                        </p>
                    </div>
                    <div class="">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Owner Name') }}</span>
                        <p class="fs-12 fw-400 m-0 text-truncate">{{ $shop->user->name ?? '-' }}</p>
                    </div>
                </td>

                <td class="hide-sm" data-label="Contact Details">
                    <div class=" mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Phone') }}</span>
                        <p class="fs-14 fw-700 m-0">{{ $shop->user->phone ?? '-' }}</p>
                    </div>
                    <div class="">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Email') }}</span>
                        <p class="fs-12 fw-400 m-0 text-truncate">{{ $shop->user->email ?? '-' }}</p>
                    </div>
                </td>

                <td class="hide-md align-middle" data-label="Business Info">
                    @if($route == 'all_seller_route')
                        <div class="border-width-3 border-left border-info px-2 py-0 mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Products') }}</span>
                            <p class="fs-13 fw-600 m-0">{{ $shop->user->products->count() }}</p>
                        </div>
                        <div class="border-width-3 border-left border-warning px-2 py-0 mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Due to seller') }}</span>
                            <p class="fs-13 fw-600 m-0">
                                @if ($shop->admin_to_pay >= 0)
                                    {{ single_price($shop->admin_to_pay) }}
                                @else
                                    {{ single_price(abs($shop->admin_to_pay)) }} ({{ translate('Due to Admin') }})
                                @endif
                            </p>
                        </div>
                        @if(get_setting('seller_commission_type') == 'seller_based')
                            <div class="border-width-3 border-left border-danger px-2 py-0">
                                <span class="text-secondary fs-12 fw-400">{{ translate('Commission') }}</span>
                                <p class="fs-13 fw-600 m-0">{{ $shop->commission_percentage }}%</p>
                            </div>
                        @endif
                    @else
                        <div class="border-width-3 border-left border-info px-2 py-0 mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Registration Date') }}</span>
                            <p class="fs-13 fw-600 m-0">{{ $shop->created_at ? $shop->created_at->format('Y-m-d') : '-' }}</p>
                        </div>
                        <div class="border-width-3 border-left border-secondary px-2 py-0">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Seller Type') }}</span>
                            <p class="fs-13 fw-600 m-0">
                                @if($shop->user->banned)
                                    <span class="badge badge-inline badge-danger">{{ translate('Banned') }}</span>
                                @elseif($shop->user->is_suspicious)
                                    <span class="badge badge-inline badge-info">{{ translate('Suspicious') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('Regular') }}</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </td>

                @if($route == 'all_seller_route')
                    <td class="hide-xxl align-middle" data-label="Verification">
                        
                        <div class="mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Email') }}</span>
                             <br>
                            @if($shop->user->email_verified_at != null)
                                <span class="badge badge-inline badge-success">{{ translate('Verified') }}</span>
                            @else
                                <span class="badge badge-inline badge-warning">{{ translate('Unverified') }}</span>
                            @endif
                        </div>
                        <div class="mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Seller') }}</span>
                                <br>
                            @if ($shop->verification_status != 1 && $shop->verification_info != null)
                                    <span class="badge badge-inline badge-warning"> {{ translate('Applied') }}</span>
                                    <a href="javascript:void();" onclick="show_seller_verification_info('{{$shop->id}}');" class="badge badge-inline badge-info ml-1">
                                        {{ translate('View Details') }}
                                    </a>
                                @elseif($shop->verification_status == 1 && $shop->verification_info != null)
                                    <span class="badge badge-inline badge-success"> {{ translate('Verified') }}</span>
                                    <a href="javascript:void();" onclick="show_seller_verification_info('{{$shop->id}}');" class="badge badge-inline badge-info ml-1">
                                        {{ translate('View Details') }}
                                    </a>
                                @elseif($shop->verification_status == 1 && $shop->verification_info == null)
                                    <span class="badge badge-inline badge-success"> {{ translate('Verified (Admin)') }}</span>
                                @else
                                    <span class="badge badge-inline badge-secondary"> {{ translate('Not Applied') }}</span>
                                @endif
                        </div>
                    </td>

                    <td class="hide-xxl align-middle" data-label="Access Approval">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input
                                @can('approve_seller') onchange="update_approved(this)" @endcan
                                value="{{ $shop->id }}" 
                                type="checkbox"
                                <?php if($shop->verification_status == 1) echo "checked";?>
                                @cannot('approve_seller') disabled @endcan
                            >
                            <span class="slider round"></span>
                        </label>
                    </td>
                @else
                    <td class="hide-xxl" data-label="Rating">
                        <div class="d-flex align-items-center rattings">
                            <span class="rating rating-mr-1">
                                @for ($i=0; $i < $shop->rating; $i++)
                                    <i class="las la-star active"></i>
                                @endfor
                                @for ($i=0; $i < 5-$shop->rating; $i++)
                                    <i class="las la-star"></i>
                                @endfor
                            </span>
                        </div>
                        <p class="fs-14 m-0 py-1"><span class="fw-700">{{ $shop->rating }}</span><span class="px-1">{{ translate('out of') }}</span>
                            <span>5.0</span>
                        </p>
                    </td>
                    <td class="hide-xxl align-middle" data-label="Followers">
                        <div class="mb-1">
                            <p class="fs-16 fw-700 m-0">{{ $shop->followers()->count() }}</p>
                            <span class="text-secondary fs-12 fw-400">{{ translate('Total Followers') }}</span>
                        </div>
                    </td>
                    <td class="hide-xxl align-middle" data-label="Custom Followers">
                        <div class="">
                            <p class="fs-16 fw-700 m-0">{{ $shop->custom_followers }}</p>
                            @if(auth()->user()->can('edit_seller_custom_followers'))
                                <a href="javascript:void();" onclick="editCustomFollowers({{ $shop->id }}, {{ $shop->custom_followers }});" class="fs-12 fw-500 text-primary">
                                    {{ translate('Edit') }}
                                </a>
                            @endif
                        </div>
                    </td>
                @endif

                <td class="text-right align-middle">
                    <div class="dropdown float-right">
                        <button class="btn btn-light w-30px h-30px w-sm-35px h-sm-35px d-flex align-items-center justify-content-center action-toggle p-0" 
                                type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="16" viewBox="0 0 3 16">
                                <g id="Group_38888" data-name="Group 38888" transform="translate(-1653 -342)">
                                    <circle id="Ellipse_1018" data-name="Ellipse 1018" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 348.5)"/>
                                    <circle id="Ellipse_1019" data-name="Ellipse 1019" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 342)"/>
                                    <circle id="Ellipse_1020" data-name="Ellipse 1020" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 355)"/>
                                </g>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                            <div class="table-options">
                                @can('view_seller_profile')
                                    <a href="{{ route('sellers.profile', encrypt($shop->id)) }}" target="_blank"
                                       class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 12 8.182">
                                                <path d="M46-793.455a2.367,2.367,0,0,0,1.739-.716,2.367,2.367,0,0,0,.716-1.739,2.367,2.367,0,0,0-.716-1.739A2.367,2.367,0,0,0,46-798.364a2.367,2.367,0,0,0-1.739.716,2.367,2.367,0,0,0-.716,1.739,2.367,2.367,0,0,0,.716,1.739A2.367,2.367,0,0,0,46-793.455Zm0-.982a1.42,1.42,0,0,1-1.043-.43,1.42,1.42,0,0,1-.43-1.043,1.42,1.42,0,0,1,.43-1.043,1.42,1.42,0,0,1,1.043-.43,1.42,1.42,0,0,1,1.043.43,1.42,1.42,0,0,1,.43,1.043,1.42,1.42,0,0,1-.43,1.043A1.42,1.42,0,0,1,46-794.436Zm0,2.618a6.315,6.315,0,0,1-3.627-1.111A6.318,6.318,0,0,1,40-795.909a6.318,6.318,0,0,1,2.373-2.98A6.315,6.315,0,0,1,46-800a6.315,6.315,0,0,1,3.627,1.111A6.318,6.318,0,0,1,52-795.909a6.318,6.318,0,0,1-2.373,2.98A6.315,6.315,0,0,1,46-791.818ZM46-795.909Zm0,3a5.206,5.206,0,0,0,2.83-.811,5.331,5.331,0,0,0,1.97-2.189,5.331,5.331,0,0,0-1.97-2.189,5.206,5.206,0,0,0-2.83-.811,5.206,5.206,0,0,0-2.83.811,5.331,5.331,0,0,0-1.97,2.189,5.331,5.331,0,0,0,1.97,2.189A5.206,5.206,0,0,0,46-792.909Z" transform="translate(-40 800)" fill="#414141"/>
                                            </svg>
                                        </span>
                                        <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('View Profile') }}</span>
                                    </a>
                                @endcan

                                @if($route == 'all_seller_route')
                                    @can('login_as_seller')
                                        <a href="{{ route('sellers.login', encrypt($shop->id)) }}" 
                                           class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                    <path d="M6,6A3,3,0,1,1,9,3,3,3,0,0,1,6,6ZM6,8c-2.67,0-8,1.34-8,4v2H14V12C14,9.34,8.67,8,6,8Z" transform="translate(-0.001 -1)" fill="#414141"/>
                                                </svg>
                                            </span>
                                            <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('Login as Seller') }}</span>
                                        </a>
                                    @endcan

                                    @can('pay_to_seller')
                                        <a href="javascript:void();" onclick="show_seller_payment_modal('{{$shop->id}}');" 
                                           class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                                                    <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" fill="#414141"/>
                                                    <path d="M12,6a6,6,0,1,0,6,6A6,6,0,0,0,12,6Zm0,10a4,4,0,1,1,4-4A4,4,0,0,1,12,16Z" fill="#414141"/>
                                                </svg>
                                            </span>
                                            <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('Go to Payment') }}</span>
                                        </a>
                                    @endcan

                                    @can('seller_payment_history')
                                        <a href="{{ route('sellers.payment_history', encrypt($shop->user_id)) }}" 
                                           class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                                                    <path d="M20,4H4A2,2,0,0,0,2,6V18a2,2,0,0,0,2,2H20a2,2,0,0,0,2-2V6A2,2,0,0,0,20,4Zm0,14H4V6H20Z" fill="#414141"/>
                                                    <path d="M18,10H6V8H18Z" fill="#414141"/>
                                                    <path d="M18,14H6V12H18Z" fill="#414141"/>
                                                </svg>
                                            </span>
                                            <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('Payment History') }}</span>
                                        </a>
                                    @endcan

                                    @can('edit_seller')
                                        <a href="{{ route('sellers.edit', encrypt($shop->id)) }}" 
                                           class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11.985" height="12" viewBox="0 0 11.985 12">
                                                    <path d="M121.2-909a1.154,1.154,0,0,1-.846-.352A1.154,1.154,0,0,1,120-910.2v-8.39a1.154,1.154,0,0,1,.352-.846,1.154,1.154,0,0,1,.846-.352h3.91a.541.541,0,0,1,.449.187.645.645,0,0,1,.15.412.626.626,0,0,1-.157.412.563.563,0,0,1-.457.187h-3.9v8.39h8.39v-3.91a.541.541,0,0,1,.187-.449.645.645,0,0,1,.412-.15.645.645,0,0,1,.412.15.541.541,0,0,1,.187.449v3.91a1.154,1.154,0,0,1-.352.846,1.154,1.154,0,0,1-.846.352ZM125.393-914.393Zm-1.8,1.2v-1.453a1.183,1.183,0,0,1,.09-.457,1.165,1.165,0,0,1,.255-.382l5.154-5.154a1.2,1.2,0,0,1,.4-.27,1.2,1.2,0,0,1,.449-.09,1.183,1.183,0,0,1,.457.09,1.219,1.219,0,0,1,.4.27l.839.854a1.347,1.347,0,0,1,.255.4,1.147,1.147,0,0,1,.09.442,1.237,1.237,0,0,1-.082.442,1.122,1.122,0,0,1-.262.4l-5.154,5.154a1.27,1.27,0,0,1-.382.262,1.1,1.1,0,0,1-.457.1h-1.453a.58.58,0,0,1-.427-.172A.58.58,0,0,1,123.6-913.195Zm7.206-5.753-.839-.839Zm-6.007,5.154h.839l3.476-3.476-.419-.419-.434-.419-3.461,3.461Zm3.9-3.9-.434-.419.434.419.419.419Z" transform="translate(-120 921)" fill="#414141"/>
                                                </svg>
                                            </span>
                                            <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('Edit') }}</span>
                                        </a>
                                    @endcan

                                    @can('ban_seller')
                                        @if($shop->user->banned != 1)
                                            <a href="javascript:void();" onclick="confirm_ban('{{ route('sellers.ban', $shop->id) }}');" 
                                               class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                                                        <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" fill="#dc3545"/>
                                                        <path d="M12,6a6,6,0,1,0,6,6A6,6,0,0,0,12,6Z" fill="#dc3545"/>
                                                    </svg>
                                                </span>
                                                <span class="fs-14 text-danger fw-500 pl-10px">{{ translate('Ban this seller') }}</span>
                                            </a>
                                        @else
                                            <a href="javascript:void();" onclick="confirm_unban('{{ route('sellers.ban', $shop->id) }}');" 
                                               class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                                                        <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Z" fill="#28a745"/>
                                                        <path d="M16.5,8.5,9,16,7.5,14.5" stroke="#fff" stroke-width="2"/>
                                                    </svg>
                                                </span>
                                                <span class="fs-14 text-success fw-500 pl-10px">{{ translate('Unban this seller') }}</span>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('mark_seller_suspected')
                                        @if($shop->user->is_suspicious == 1)
                                            <a href="javascript:void();" onclick="confirm_suspicious('{{ route('seller.suspicious', encrypt($shop->user->id)) }}', true);" 
                                               class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                                                        <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2ZM12,6a4,4,0,1,1-4,4A4,4,0,0,1,12,6Z" fill="#ffc107"/>
                                                        <path d="M12,18a6,6,0,0,1-6-6H2a10,10,0,0,0,20,0H18A6,6,0,0,1,12,18Z" fill="#ffc107"/>
                                                    </svg>
                                                </span>
                                                <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('Mark as Unsuspect') }}</span>
                                            </a>
                                        @else
                                            <a href="javascript:void();" onclick="confirm_suspicious('{{ route('seller.suspicious', encrypt($shop->user->id)) }}', false);" 
                                               class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24">
                                                        <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,16a2,2,0,1,1,2-2A2,2,0,0,1,12,18Z" fill="#ffc107"/>
                                                        <path d="M12,6a4,4,0,0,1,4,4H8A4,4,0,0,1,12,6Z" fill="#ffc107"/>
                                                    </svg>
                                                </span>
                                                <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('Mark as Suspicious') }}</span>
                                            </a>
                                        @endif
                                    @endcan
                                @endif

                                @can('delete_seller')
                                    <a href="javascript:void(0)"
                                       class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue" 
                                       onclick="singleDelete({{ $shop->id }})">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10.667" height="12" viewBox="0 0 10.667 12">
                                                <path d="M162-828a1.284,1.284,0,0,1-.942-.392,1.284,1.284,0,0,1-.392-.942V-838H160v-1.333h3.333V-840h4v.667h3.333V-838H170v8.667a1.284,1.284,0,0,1-.392.942,1.284,1.284,0,0,1-.942.392Zm6.667-10H162v8.667h6.667Zm-5.333,7.333h1.333v-6h-1.333Zm2.667,0h1.333v-6H166ZM162-838v0Z" transform="translate(-160 840)" fill="#dc3545"/>
                                            </svg>
                                        </span>
                                        <span class="fs-14 text-danger fw-500 pl-10px">{{ translate('Delete') }}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div class="w-100">
                        <h5 class="fs-16 fw-bold text-gray">{{ translate('No Shops found!') }}</h5>
                        <i class="las la-frown fs-48 text-soft-white"></i>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($shops->hasPages())
        <div class="aiz-pagination" id="pagination">
            {{ $shops->appends(request()->input())->links() }}
        </div>
    @endif
</div>