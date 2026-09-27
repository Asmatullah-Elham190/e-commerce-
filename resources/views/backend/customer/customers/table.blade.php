<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('customer_delete'))
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
                <th class="">#</th>
                @endif
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Name') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Email Address') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Phone') }}
                </th>
                <th class="hide-7xl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Package') }}
                </th>
                <th class="hide-xxl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Wallet Balance') }}
                </th>
                <th class="hide-xl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Verification Status') }}
                </th>
                @canany(['ban_customer','delete_customer'])
                <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Options') }}
                </th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $key => $customer)
            <tr class="data-row">
                <td class="align-middle h-40">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('customer_delete'))
                    <div class="form-group d-inline-block">
                        <label class="aiz-checkbox mb-2">
                            <input type="checkbox" class="check-one" name="id[]"
                                value="{{ $customer->id }}">
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>
                    @else
                    <div class="form-group d-inline-block">
                        {{ $key + 1 + ($customers->currentPage() - 1) * $customers->perPage() }}
                    </div>
                    @endif
                </td>
                <td class="align-middle" data-label="Name">
                    <div class="row gutters-5 w-300px w-md-300px mw-300">
                        <div class="col">
                            <span
                                class="fs-14 fw-700 @if($customer->banned == 1) text-danger @elseif($customer->is_suspicious == 1) text-info @else text-dark @endif">
                                
                                    @if($customer->banned == 1)
                                    <i class="las la-ban las" aria-hidden="true"></i>
                                    @elseif($customer->is_suspicious == 1)
                                    <i class="las la-exclamation-circle" aria-hidden="true"></i>
                                    @endif
                                    {{$customer->name}}
                                
                            </span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xs" data-label="Email Address">
                    <div class="row gutters-5 w-200px w-md-200px mw-200">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">{{ $customer->email}}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-md w-200px w-md-200px mw-200" data-label="Phone">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">{{ $customer->phone}}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-7xl w-200px w-md-200px mw-200" data-label="Package">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-blue fs-14 fw-700">
                                @if ($customer->customer_package != null)
                                {{$customer->customer_package->getTranslation('name')}}
                                @endif
                            </span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xxl w-200px w-md-200px mw-200" data-label="Wallet Balance">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">{{single_price($customer->balance)}}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xl w-200px w-md-200px mw-200" data-label="Verification Status">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-12 fw-700">
                                @if($customer->email_verified_at != null)

                                <span class="text-success"><i class="las la-check-circle mr-1 fs-14"></i>{{translate('Verified')}}</span>
                                @else

                                <span class="text-warning"><i class="las la-exclamation-circle mr-1 fs-14"></i>{{translate('Unverified')}}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </td>
                @canany(['delete_customer','ban_customer'])
                <td class="align-middle hide-s text-right" data-label="Options">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="dropdown float-right">
                            <button
                                class="btn btn-light w-35px h-35px  action-toggle d-flex align-items-center justify-content-center p-0"
                                type="button" data-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="3"
                                    height="16" viewBox="0 0 3 16">
                                    <g id="Group_38888" data-name="Group 38888"
                                        transform="translate(-1653 -342)">
                                        <circle id="Ellipse_1018" data-name="Ellipse 1018"
                                            cx="1.5" cy="1.5" r="1.5"
                                            transform="translate(1653 348.5)" />
                                        <circle id="Ellipse_1019" data-name="Ellipse 1019"
                                            cx="1.5" cy="1.5" r="1.5"
                                            transform="translate(1653 342)" />
                                        <circle id="Ellipse_1020" data-name="Ellipse 1020"
                                            cx="1.5" cy="1.5" r="1.5"
                                            transform="translate(1653 355)" />
                                    </g>
                                </svg>

                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm">
                                <div class="table-options">
                                    @if($customer->email_verified_at != null && auth()->user()->can('login_as_customer'))
                                    <a href="{{route('customers.login', encrypt($customer->id))}}" title="{{ translate('Log in as this Customer') }}"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12.985"
                                                height="13" viewBox="0 0 11.985 12">
                                                <path
                                                    id="edit_square_24dp_9393A3_FILL0_wght400_GRAD0_opsz24"
                                                    d="M121.2-909a1.154,1.154,0,0,1-.846-.352A1.154,1.154,0,0,1,120-910.2v-8.39a1.154,1.154,0,0,1,.352-.846,1.154,1.154,0,0,1,.846-.352h3.91a.541.541,0,0,1,.449.187.645.645,0,0,1,.15.412.626.626,0,0,1-.157.412.563.563,0,0,1-.457.187h-3.9v8.39h8.39v-3.91a.541.541,0,0,1,.187-.449.645.645,0,0,1,.412-.15.645.645,0,0,1,.412.15.541.541,0,0,1,.187.449v3.91a1.154,1.154,0,0,1-.352.846,1.154,1.154,0,0,1-.846.352ZM125.393-914.393Zm-1.8,1.2v-1.453a1.183,1.183,0,0,1,.09-.457,1.165,1.165,0,0,1,.255-.382l5.154-5.154a1.2,1.2,0,0,1,.4-.27,1.2,1.2,0,0,1,.449-.09,1.183,1.183,0,0,1,.457.09,1.219,1.219,0,0,1,.4.27l.839.854a1.347,1.347,0,0,1,.255.4,1.147,1.147,0,0,1,.09.442,1.237,1.237,0,0,1-.082.442,1.122,1.122,0,0,1-.262.4l-5.154,5.154a1.27,1.27,0,0,1-.382.262,1.1,1.1,0,0,1-.457.1h-1.453a.58.58,0,0,1-.427-.172A.58.58,0,0,1,123.6-913.195Zm7.206-5.753-.839-.839Zm-6.007,5.154h.839l3.476-3.476-.419-.419-.434-.419-3.461,3.461Zm3.9-3.9-.434-.419.434.419.419.419Z"
                                                    transform="translate(-120 921)"
                                                    fill="#1f1f1f" />
                                            </svg>
                                        </span>
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('Log in as this Customer') }}</span>
                                    </a>
                                    @endif
                                    <!--Edit-->
                                    @can('ban_customer')
                                    @if($customer->banned != 1)
                                    <a href="{{route('customers.ban', encrypt( $customer->id))}}" title="{{ translate('Ban this Customer') }}"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark ">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="13" viewBox="0 -960 960 960" width="12.985" fill="#1f1f1f">
                                                <path d="M768-90 666-192H192v-92q0-25.78 12.5-47.39T239-366q43-26 91-42t99-21L90-768l51-51 678 678-51 51ZM264-264h330l-95-96h-19q-54 0-106 14t-99 42q-4.95 2.94-7.98 8.24Q264-290.47 264-284v20Zm458-102q20 11 32 30t14 42l-99-99q13.63 6.3 26.88 12.6 13.25 6.3 26.12 14.4ZM559-504l-54-53q21.2-8.08 34.1-26.5 12.9-18.41 12.9-40.86Q552-654 530.96-675t-50.58-21q-22.38 0-40.83 12.9T413-649l-53-54q20-31 52-48t68-17q60 0 102 42t42 102q0 36-17 68t-48 52Zm35 240H264h330ZM462-600Z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('Ban this Customer') }}</span>
                                    </a>
                                    @else
                                    <a href="{{route('customers.ban', encrypt( $customer->id))}}" title="{{ translate('Unban this Customer') }}"
                                        class="d-flex text-dark align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="13" viewBox="0 -960 960 960" width="12.985" fill="#1f1f1f">
                                                <path d="M695-456 576-575l51-51 68 68 153-152 51 50-204 204Zm-311-24q-60 0-102-42t-42-102q0-60 42-102t102-42q60 0 102 42t42 102q0 60-42 102t-102 42ZM96-192v-92q0-25.78 12.5-47.39T143-366q55-32 116-49t125-17q64 0 125 17t116 49q22 13 34.5 34.61T672-284v92H96Zm72-72h432v-20q0-6.47-3.03-11.76-3.02-5.3-7.97-8.24-47-27-99-41.5T384-360q-54 0-106 14.5T179-304q-4.95 2.94-7.98 8.24Q168-290.47 168-284v20Zm216.21-288Q414-552 435-573.21t21-51Q456-654 434.79-675t-51-21Q354-696 333-674.79t-21 51Q312-594 333.21-573t51 21ZM384-312Zm0-312Z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('unban this Customer') }}</span>
                                    </a>
                                    @endif
                                    @endcan
                                    @can('mark_customer_suspected')
                                    @if($customer->is_suspicious != 1)
                                    <a href="{{route('customers.suspicious', $customer->id)}}" title="{{ translate('Mark as Suspecious') }}"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark ">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="13" viewBox="0 -960 960 960" width="12.985" fill="#1f1f1f">
                                                <path d="M768-90 666-192H192v-92q0-25.78 12.5-47.39T239-366q43-26 91-42t99-21L90-768l51-51 678 678-51 51ZM264-264h330l-95-96h-19q-54 0-106 14t-99 42q-4.95 2.94-7.98 8.24Q264-290.47 264-284v20Zm458-102q20 11 32 30t14 42l-99-99q13.63 6.3 26.88 12.6 13.25 6.3 26.12 14.4ZM559-504l-54-53q21.2-8.08 34.1-26.5 12.9-18.41 12.9-40.86Q552-654 530.96-675t-50.58-21q-22.38 0-40.83 12.9T413-649l-53-54q20-31 52-48t68-17q60 0 102 42t42 102q0 36-17 68t-48 52Zm35 240H264h330ZM462-600Z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('Mark as Suspecious') }}</span>
                                    </a>
                                    @else
                                    <a href="{{route('customers.suspicious', $customer->id)}}" title="{{ translate('Unmark as Suspecious') }}"
                                        class="d-flex text-dark align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="13" viewBox="0 -960 960 960" width="12.985" fill="#1f1f1f">
                                                <path d="M695-456 576-575l51-51 68 68 153-152 51 50-204 204Zm-311-24q-60 0-102-42t-42-102q0-60 42-102t102-42q60 0 102 42t42 102q0 60-42 102t-102 42ZM96-192v-92q0-25.78 12.5-47.39T143-366q55-32 116-49t125-17q64 0 125 17t116 49q22 13 34.5 34.61T672-284v92H96Zm72-72h432v-20q0-6.47-3.03-11.76-3.02-5.3-7.97-8.24-47-27-99-41.5T384-360q-54 0-106 14.5T179-304q-4.95 2.94-7.98 8.24Q168-290.47 168-284v20Zm216.21-288Q414-552 435-573.21t21-51Q456-654 434.79-675t-51-21Q354-696 333-674.79t-21 51Q312-594 333.21-573t51 21ZM384-312Zm0-312Z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('Unmark as Suspecious') }}</span>
                                    </a>
                                    @endif
                                    @endcan
                                    @if(addon_is_activated('offline_payment'))
                                        @if ($customer->banned != 1)
                                            <a href="#" id="recharge_wallet" data-user-id="{{ $customer->id }}" title="{{ translate('Wallet Recharge') }}"
                                                class="d-flex text-dark align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                                <span class="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="13" viewBox="0 -960 960 960" width="12.985" fill="#1f1f1f">
                                                        <path d="M240-192q-60 0-102-42T96-336v-288q0-60 42-102t102-42h480q60 0 102 42t42 102v288q0 60-42 102t-102 42H240Zm0-432h480q19 0 37 5.5t35 14.5v-20q0-30-21-51t-51-21H240q-30 0-51 21t-21 51v20q17-10 35-15t37-5Zm-63 110 430 105q8 2 16 0t15-7l135-112q-11-11-24.5-17.5T720-552H240q-20 0-38 11t-25 27Z" />
                                                    </svg>
                                                </span>
                                                <span
                                                    class="fs-14 fw-500 pl-10px">{{ translate('Wallet Recharge') }}</span>
                                            </a>
                                        @endif
                                    @endif
                                    <!--Delete-->
                                    @can('delete_customer')
                                    <a href="javascript:void(0)"
                                        class="d-flex text-danger align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$customer->id}})"
                                        title="{{ translate('Delete') }}">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12.985"
                                                height="13" viewBox="0 0 10.667 12">
                                                <path id="Path_45219" data-name="Path 45219"
                                                    d="M162-828a1.284,1.284,0,0,1-.942-.392,1.284,1.284,0,0,1-.392-.942V-838H160v-1.333h3.333V-840h4v.667h3.333V-838H170v8.667a1.284,1.284,0,0,1-.392.942,1.284,1.284,0,0,1-.942.392Zm6.667-10H162v8.667h6.667Zm-5.333,7.333h1.333v-6h-1.333Zm2.667,0h1.333v-6H166ZM162-838v0Z"
                                                    transform="translate(-160 840)"
                                                    fill="#dc3545" />
                                            </svg>
                                        </span>
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('Delete') }}</span>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                @endcanany
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <div class="w-100">
                        <h5 class="fs-16 fw-bold text-gray">{{ translate('No Data found!') }}</h5>
                        <i class="las la-frown fs-48 text-soft-white"></i>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="aiz-pagination">
        {{ $customers->appends(request()->input())->links() }}
    </div>
</div>