<h5 class=" mb-0 fw-semibold mt-2">{{ translate('Payment History') }}</h5>
<div class=" mt-2">
    <div>
        <table class="table aiz-table inv-table-2 mb-0">
            <thead>
               <tr>
                    <th class="pl-3" style="width: 15%;">#</th>
                    <th class="pl-3" style="width: 23%;">{{ translate('Date') }}</th>
                    <th class="pl-3" data-breakpoints="md" style="width: 22%;">{{ translate('Amount') }}</th>
                    <th class="pl-3" data-breakpoints="md" style="width: 34%;">{{ translate('Payment Method') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $key => $payment)

                <tr class="payment-history-tr">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        <b>{{$payment->created_at->format('d F, Y')}}</b>
                    </td>
                    <td>
                        <b> {{ single_price($payment->amount) }}</b>
                    </td>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        @if ($payment->txn_code != null)
                        ({{ translate('TRX ID') }} : {{ $payment->txn_code }})
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination inv-pagination mt-4">
            {{ $payments->appends(request()->input())->links() }}
        </div>
    </div>
</div>