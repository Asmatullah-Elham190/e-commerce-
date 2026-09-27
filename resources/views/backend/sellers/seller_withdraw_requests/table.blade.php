<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Date') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Seller') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Total Amount to Pay') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Requested Amount') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Message') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Status') }}
                </th>
                <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Options') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($seller_withdraw_requests as $key => $seller_withdraw_request)
                @php $user = \App\Models\User::find($seller_withdraw_request->user_id); @endphp
                @if ($user && $user->shop)
                    <tr class="data-row">
                        <td class="align-middle h-40 pr-3">
                            <div>
                                <button type="button"
                                    class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                            </div>
                            <div class="form-group d-inline-block mb-0">
                                {{ $key + 1 + ($seller_withdraw_requests->currentPage() - 1) * $seller_withdraw_requests->perPage() }}
                            </div>
                        </td>
                        <td class="hide-xs align-middle w-150px w-md-150px mw-150 pr-2" data-label="Date">
                            <div class="row gutters-5">
                                <div class="col">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ $seller_withdraw_request->created_at }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle w-400px w-md-400px mw-400 pr-3" data-label="Seller">
                            <div class="row gutters-5">
                                <div class="col d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ $user->name }} ({{ $user->shop->name }})
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle w-150px w-md-150px mw-150 pr-1" data-label="Total Amount to Pay">
                            <div class="row gutters-5">
                                <div class="col d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ single_price($user->shop->admin_to_pay) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-xs align-middle w-150px w-md-150px mw-150 pr-1" data-label="Requested Amount">
                            <div class="row gutters-5">
                                <div class="col d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ single_price($seller_withdraw_request->amount) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle w-200px w-md-200px mw-200 pr-1" data-label="Message">
                            <div class="row gutters-5">
                                <div class="col d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ $seller_withdraw_request->message }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle w-150px w-md-150px mw-150 pr-1" data-label="Status">
                            <div class="row gutters-5">
                                <div class="col d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark">
                                        @if ($seller_withdraw_request->status == 1)
                                            <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-info">{{translate('Pending')}}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-s align-middle text-right" data-label="Options">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="dropdown float-right">
                                    <button
                                        class="btn btn-light w-35px h-35px  action-toggle d-flex align-items-center justify-content-center p-0"
                                        type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="3" height="16" viewBox="0 0 3 16">
                                            <g id="Group_38888" data-name="Group 38888" transform="translate(-1653 -342)">
                                                <circle id="Ellipse_1018" data-name="Ellipse 1018" cx="1.5" cy="1.5" r="1.5"
                                                    transform="translate(1653 348.5)" />
                                                <circle id="Ellipse_1019" data-name="Ellipse 1019" cx="1.5" cy="1.5" r="1.5"
                                                    transform="translate(1653 342)" />
                                                <circle id="Ellipse_1020" data-name="Ellipse 1020" cx="1.5" cy="1.5" r="1.5"
                                                    transform="translate(1653 355)" />
                                            </g>
                                        </svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm">
                                        <div class="table-options">
                                            <a href="javascript:void(0);" title="{{ translate('Pay Now') }}"
                                                onclick="show_seller_payment_modal('{{$seller_withdraw_request->user_id}}','{{ $seller_withdraw_request->id }}');"
                                                class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                                <span class="fs-14 fw-500 pl-10px">{{ translate('Pay Now') }}</span>
                                            </a>
                                            @if(auth()->user()->can('pay_to_seller'))
                                                <a href="javascript:void(0);" title="{{ translate('Message View') }}"
                                                    onclick="show_message_modal('{{ $seller_withdraw_request->id }}');"
                                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                                    <span class="fs-14 fw-500 pl-10px">{{ translate('Message View') }}</span>
                                                </a>
                                            @endif
                                            @if(auth()->user()->can('pay_to_seller'))
                                                <a href="{{route('sellers.payment_history', encrypt($seller_withdraw_request->user_id))}}"
                                                    title="{{ translate('Payment History') }}"
                                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                                    <span class="fs-14 fw-500 pl-10px">{{ translate('Payment History') }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
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
        {{ $seller_withdraw_requests->appends(request()->input())->links() }}
    </div>
</div>