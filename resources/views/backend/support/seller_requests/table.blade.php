<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                <th class="hide-lg">#</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Logo') }}</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Shop Info') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Contact Details') }}</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Requested Item') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary">{{ translate('Message') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Date') }}</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($requests as $key => $req)
                @php $item = $req->item_label; @endphp
                <tr class="data-row">

                    <td class="align-middle w-40px">
                        {{ $key + 1 + ($requests->currentPage() - 1) * $requests->perPage() }}
                    </td>

                    <td data-label="Logo" class="w-60px w-md-80px w-md-100px">
                        <div class="w-40px h-40px w-sm-60px h-sm-60px w-md-80px h-md-80px rounded-2 overflow-hidden border">
                            <img src="{{ uploaded_asset($req->seller?->shop?->logo) }}" alt="Logo" class="img-fit" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                    </td>

                    <td data-label="Shop Info">
                        <div class=" mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Shop Name') }}</span>
                            <p class="fs-14 fw-700 m-0">
                                @if($req->seller?->shop?->user->banned == 1) 
                                    <i class="las la-ban text-danger" aria-hidden="true"></i> 
                                @elseif($req->seller?->shop?->user->is_suspicious == 1) 
                                    <i class="las la-exclamation-circle text-info" aria-hidden="true"></i> 
                                @else
                                    <i class="las la-check-circle  text-success" aria-hidden="true"></i>
                                @endif
                                {{ $req->seller?->shop?->name ?? translate('Unknown Shop') }}
                            </p>
                        </div>
                        <div class="">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Owner Name') }}</span>
                            <p class="fs-12 fw-400 m-0 text-truncate">{{ $req->seller?->name ?? '' }}</p>
                        </div>
                    </td>

                    <td class="hide-sm" data-label="Contact Details">
                        <div class=" mb-1">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Phone') }}</span>
                            <p class="fs-14 fw-700 m-0">{{ $req->seller?->shop?->user->phone ?? '-' }}</p>
                        </div>
                        <div class="">
                            <span class="text-secondary fs-12 fw-400">{{ translate('Email') }}</span>
                            <p class="fs-12 fw-400 m-0 text-truncate">{{ $req->seller?->shop?->user->email ?? '-' }}</p>
                        </div>
                    </td>

                    <td data-label="Requested Item" class="align-middle">
                        @if ($item)
                            <span class="badge badge-inline bg-danger text-white">{{ translate($item['type']) }}</span>
                            <p class="fs-13 fw-400 m-0 mt-1">{{ translate('Item Name') }}: {{ $item['value'] }}</p>
                        @else
                            <span class="fs-12 fw-400 text-secondary">{{ translate('N/A') }}</span>
                        @endif
                    </td>

                    <td class="hide-md align-middle" data-label="Message">
                        <p class="fs-13 fw-400 text-truncate-2 m-0" style="max-width: 250px;">
                            {{ $req->message ?? '--' }}
                        </p>
                    </td>

                    <td class="hide-sm align-middle" data-label="Date">
                        <span class="fs-12 fw-400 text-secondary">{{ $req->created_at->format('M d, Y h:i a') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="w-100">
                            <h5 class="fs-16 fw-bold text-gray">{{ translate('No Requests found!') }}</h5>
                            <i class="las la-frown fs-48 text-soft-white"></i>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="aiz-pagination" id="pagination">
        {{ $requests->links() }}
    </div>
</div>