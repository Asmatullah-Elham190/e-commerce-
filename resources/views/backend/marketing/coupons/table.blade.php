<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('delete_coupon'))
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
                    {{ translate('Code') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Type') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Start Date') }}
                </th>
                <th class="hide-7xl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('End Date') }}
                </th>
                <th class="hide-xl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Validation Days') }}
                </th>
                <th class="hide-xxl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Status') }}
                </th>
                @canany(['edit_coupon','delete_coupon'])
                    <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                        {{ translate('Options') }}
                    </th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $key => $coupon)
            @php
                $isProtected = ($coupon->type == 'welcome_base' && $coupon->status == 1);
            @endphp
            <tr class="data-row" data-protected="{{ $isProtected ? '1' : '0' }}" data-coupon-code="{{ $coupon->code }}">
                <td class="align-middle w-40px">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('delete_coupon'))
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox mb-2">
                                <input type="checkbox" class="check-one" name="id[]"
                                    value="{{ $coupon->id }}" data-protected="{{ $isProtected ? '1' : '0' }}">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                    @else
                        <div class="form-group d-inline-block">
                            {{ $key + 1 + ($coupons->currentPage() - 1) * $coupons->perPage() }}
                        </div>
                    @endif
                </td>
                <!-- Rest of your table cells remain same -->
                <td class="align-middle hide-xs" data-label="code">
                    <div class="row gutters-5 w-200px w-md-200px w-lg-300px">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400 d-block">{{$coupon->code}}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xs" data-label="Type">
                    <div class="row gutters-5 w-200px w-md-200px w-lg-300px">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400 d-block text-truncate">{{ translate(Str::headline($coupon->type)) }}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-md w-200px w-md-200px mw-200" data-label="Start Date">
                    <div class="row gutters-5">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400">{{ $coupon->type != 'welcome_base' ? date('d-m-Y', $coupon->start_date) : '' }}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-7xl w-200px w-md-200px mw-200" data-label="End Date">
                    <div class="row gutters-5">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400">
                                {{ $coupon->type != 'welcome_base' ? date('d-m-Y', $coupon->end_date) : '' }}
                            </span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xxl w-200px w-md-200px mw-200" data-label="Validation Days">
                    <div class="row gutters-5">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400">
                                @if($coupon->type == 'welcome_base')
                                    {{ json_decode($coupon->details)->validation_days }}
                                @endif
                            </span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xxl w-200px w-md-200px mw-200" data-label="Status">
                    <div class="row gutters-5">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400">
                                @if($coupon->type == 'welcome_base')
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="updateCouponStatus(this)" value="{{ $coupon->id }}" type="checkbox" <?php if($coupon->status == 1) echo "checked";?> >
                                        <span class="slider round"></span>
                                    </label>
                                @endif    
                            </span>
                        </div>
                    </div>
                </td>
                @canany(['edit_coupon','delete_coupon'])
                <td class="align-middle hide-s text-right" data-label="Options">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="dropdown float-right">
                            <button class="btn btn-light w-35px h-35px action-toggle d-flex align-items-center justify-content-center p-0"
                                type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="3" height="16" viewBox="0 0 3 16">
                                    <g id="Group_38888" data-name="Group 38888" transform="translate(-1653 -342)">
                                        <circle id="Ellipse_1018" data-name="Ellipse 1018" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 348.5)"/>
                                        <circle id="Ellipse_1019" data-name="Ellipse 1019" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 342)"/>
                                        <circle id="Ellipse_1020" data-name="Ellipse 1020" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 355)"/>
                                    </g>
                                </svg>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm">
                                <div class="table-options">
                                    <!--Edit-->
                                    @can('edit_coupon')
                                        <a href="{{route('coupon.edit', encrypt($coupon->id) )}}" title="{{ translate('Edit') }}"
                                            class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                            <span class="fs-14 fw-500 pl-10px">{{ translate('Edit') }}</span>
                                        </a>
                                    @endcan
                                    <!--Delete-->
                                    @can('delete_coupon')
                                        <a href="javascript:void(0)"
                                            class="d-flex text-danger align-items-center px-20px py-10px hov-bg-light hov-text-blue" 
                                            onclick="singleDelete({{$coupon->id}}, {{ $isProtected ? 1 : 0 }}, '{{ $coupon->code }}')"
                                            title="{{ translate('Delete') }}">
                                            <span class="fs-14 fw-500 pl-10px">{{ translate('Delete') }}</span>
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
        {{ $coupons->appends(request()->input())->links() }}
    </div>
</div>