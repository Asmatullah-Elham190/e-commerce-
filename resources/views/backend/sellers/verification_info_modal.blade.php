<div class="modal-body">
    <h6 class="mb-4 font-weight-bold">{{ translate('Verification Info') }}</h6>
    @if ($shop->verification_info != null)
    <table class="table inv-table-2" cellspacing="0" width="100%">
        <tbody>
            @foreach (json_decode($shop->verification_info) as $key => $info)
            <tr>
                <th class="text-muted">{{ $info->label }}</th>
                @if ($info->type == 'text' || $info->type == 'select' || $info->type == 'radio')
                <td>{{ $info->value }}</td>
                @elseif ($info->type == 'multi_select')
                <td>
                    {{ implode(', ', json_decode($info->value)) }}
                </td>
                @elseif ($info->type == 'file')
                <td>
                    <a href="{{ my_asset($info->value) }}" target="_blank" >{{translate('Click here')}}</a>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @if ($shop->verification_status != 1 && $shop->verification_info != null)
    <div class="text-center">
        <a href="{{ route('sellers.reject', $shop->id) }}" class="btn btn-sm btn-danger d-innline-block">{{translate('Reject')}}</a></li>
        <a href="{{ route('sellers.approve', $shop->id) }}" class="btn btn-sm btn-success d-innline-block">{{translate('Accept')}}</a>
    </div>
    @endif
</div>