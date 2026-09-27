<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{translate('Date')}}
                </th>
                <th class="text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{translate('Seller')}}
                </th>
                <th class="text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{translate('Amount')}}
                </th>
                <th class="text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Payment Details') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $key => $payment)
                @php $user = \App\Models\User::find($payment->seller_id); @endphp
                @if ($user && $user->shop)
                    <tr class="data-row">
                        <td class="align-middle h-40 pr-5">
                            <div>
                                <button type="button"
                                    class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                            </div>
                            <div class="form-group d-inline-block mb-0">
                                {{ $key + 1 + ($payments->currentPage() - 1) * $payments->perPage() }}
                            </div>
                        </td>
                        <td class="align-middle" data-label="Date">
                            <div class="row gutters-5 w-200px w-md-200px mw-200">
                                <div class="col">
                                    <span
                                        class="fs-14 fw-400 text-dark">
                                            {{ $payment->created_at }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle" data-label="Seller">
                            <div class="row gutters-5 w-300px w-md-300px mw-300">
                                <div class="col d-flex align-items-center">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ $user->name }} ({{ $user->shop->name }})
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle" data-label="Amount">
                            <div class="row gutters-5 w-100px w-md-100px mw-100">
                                <div class="col d-flex align-items-center">
                                    <span
                                        class="fs-14 fw-400 text-dark">
                                            {{ single_price($payment->amount) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle" data-label="Payment Details">
                            <div class="row gutters-5 w-200px w-md-200px mw-200">
                                <div class="col d-flex align-items-center">
                                    <span
                                        class="fs-14 fw-400 text-dark">
                                            {{ translate(ucfirst(str_replace('_', ' ', $payment->payment_method))) }} @if ($payment->txn_code != null) ({{ translate('TRX ID') }} : {{ $payment->txn_code }}) @endif
                                    </span>
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
        {{ $payments->appends(request()->input())->links() }}
    </div>
</div>