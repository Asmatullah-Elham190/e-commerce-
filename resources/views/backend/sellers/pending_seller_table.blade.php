<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if(auth()->user()->can('delete_seller'))
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
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">{{ translate('Shop Info') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Contact Details') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary">{{ translate('Registration Info') }}</th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Access Approval') }}</th>
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
                    @if(auth()->user()->can('delete_seller'))
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

                <td class="hide-xs" data-label="Owner Info">
                    <div class="mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Shop Name') }}</span>
                        <p class="fs-14 fw-700 m-0">{{ $shop->name }}</p>
                    </div>
                    <div class="">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Owner Name') }}</span>
                        <p class="fs-12 fw-400 m-0 py-5px text-truncate">{{ $shop->user->name ?? '-' }}</p>
                    </div>

                </td>

                <td class="hide-sm" data-label="Contact Details">
                    <div class="mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Phone') }}</span>
                        <p class="fs-14 fw-700 m-0">{{ $shop->user->phone ?? '-' }}</p>
                    </div>
                    <div class="">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Email') }}</span>
                        <p class="fs-12 fw-400 m-0 py-5px text-truncate">{{ $shop->user->email ?? '-' }}</p>
                    </div>
                </td>

                <td class="hide-md align-middle" data-label="Registration Info">
                    <div class="border-width-3 border-left border-info px-2 py-0 mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Registration Date') }}</span>
                        <p class="fs-13 fw-600 m-0">{{ $shop->created_at ? $shop->created_at->format('Y-m-d H:i:s') : '-' }}</p>
                    </div>
                    <div class="border-width-3 border-left border-warning px-2 py-0">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Status') }}</span> <br>
                        @if(addon_is_activated('portfolio_system') != 1)
                            <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                        @else
                            @if ($shop->verification_status != 1 && $shop->business_info != null)
                                @php 
                                    $verification_docs = json_decode($shop->business_info);
                                @endphp
                                <span class="badge badge-inline badge-success">{{ translate('Submitted') }}</span>
                                <br>
                                <a href="javascript:void(0)" class="badge badge-inline badge-info border border-info mt-1" 
                                onclick="showDocsInModal('{{ json_encode($verification_docs) }}', '{{ $shop->id }}')">
                                    {{ translate('View Info') }}
                                </a>
                            @elseif ($shop->verification_status == 1)
                                <span class="badge badge-inline badge-success">{{ translate('Verified') }}</span>
                            @else
                                <span class="badge badge-inline badge-secondary">{{ translate('Not Submitted') }}</span>
                            @endif
                        @endif
                    </div>
                    
                </td>

                <td class="hide-xxl align-middle" data-label="Access Approval">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input
                            @can('approve_seller') onchange="update_approved(this)" @endcan
                            value="{{ $shop->id }}" 
                            type="checkbox"
                            <?php if($shop->registration_approval == 1) echo "checked";?>
                            @cannot('approve_seller') disabled @endcan
                        >
                        <span class="slider round"></span>
                    </label>
                </td>

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
                                    <a href="{{ route('sellers.profile', encrypt($shop->id)) }}" 
                                       class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 12 8.182">
                                                <path d="M46-793.455a2.367,2.367,0,0,0,1.739-.716,2.367,2.367,0,0,0,.716-1.739,2.367,2.367,0,0,0-.716-1.739A2.367,2.367,0,0,0,46-798.364a2.367,2.367,0,0,0-1.739.716,2.367,2.367,0,0,0-.716,1.739,2.367,2.367,0,0,0,.716,1.739A2.367,2.367,0,0,0,46-793.455Zm0-.982a1.42,1.42,0,0,1-1.043-.43,1.42,1.42,0,0,1-.43-1.043,1.42,1.42,0,0,1,.43-1.043,1.42,1.42,0,0,1,1.043-.43,1.42,1.42,0,0,1,1.043.43,1.42,1.42,0,0,1,.43,1.043,1.42,1.42,0,0,1-.43,1.043A1.42,1.42,0,0,1,46-794.436Zm0,2.618a6.315,6.315,0,0,1-3.627-1.111A6.318,6.318,0,0,1,40-795.909a6.318,6.318,0,0,1,2.373-2.98A6.315,6.315,0,0,1,46-800a6.315,6.315,0,0,1,3.627,1.111A6.318,6.318,0,0,1,52-795.909a6.318,6.318,0,0,1-2.373,2.98A6.315,6.315,0,0,1,46-791.818ZM46-795.909Zm0,3a5.206,5.206,0,0,0,2.83-.811,5.331,5.331,0,0,0,1.97-2.189,5.331,5.331,0,0,0-1.97-2.189,5.206,5.206,0,0,0-2.83-.811,5.206,5.206,0,0,0-2.83.811,5.331,5.331,0,0,0-1.97,2.189,5.331,5.331,0,0,0,1.97,2.189A5.206,5.206,0,0,0,46-792.909Z" transform="translate(-40 800)" fill="#414141"/>
                                            </svg>
                                        </span>
                                        <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('View Profile') }}</span>
                                    </a>
                                @endcan

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
            {{ $shops->links() }}
        </div>
    @endif
</div>