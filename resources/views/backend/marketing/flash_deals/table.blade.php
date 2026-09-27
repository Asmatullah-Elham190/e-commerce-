<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('delete_flash_deal'))
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
                    {{ translate('Banner') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Title') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Start Date') }}
                </th>
                <th class="hide-7xl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('End Date') }}
                </th>
                <th class="hide-xxl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Status') }}
                </th>
                <th class="hide-xl text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Featured') }}
                </th>
                @canany(['edit_flash_deal','delete_flash_deal'])
                    <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                        {{ translate('Options') }}
                    </th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @forelse ($flash_deals as $key => $flash_deal)
            <tr class="data-row">
                <td class="align-middle w-40px">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('delete_flash_deal'))
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox mb-2">
                                <input type="checkbox" class="check-one" name="id[]"
                                    value="{{ $flash_deal->id }}">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                    @else
                        <div class="form-group d-inline-block">
                            {{ $key + 1 + ($flash_deals->currentPage() - 1) * $flash_deals->perPage() }}
                        </div>
                    @endif
                </td>
                <td class="align-middle w-70px w-lg-100px" data-label="Banner">
                
                        <div class="w-50px w-lg-80px h-50px h-lg-80px border border-gray-300 overflow-hidden rounded-1 d-flex justify-content-center align-items-center">
                            <img src="{{ uploaded_asset($flash_deal->banner) }}" alt="banner" class="img-fit">
                        </div>
                   
                </td>
                <td class="align-middle hide-xs" data-label="Title">
                    <div class="row gutters-5 w-200px w-md-200px w-lg-300px">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400 d-block text-truncate">{{ $flash_deal->getTranslation('title') }}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-md w-200px w-md-200px mw-200" data-label="Start Date">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">{{ date('d-m-Y H:i:s', $flash_deal->start_date) }}</span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-7xl w-200px w-md-200px mw-200" data-label="End Date">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">
                                {{ date('d-m-Y H:i:s', $flash_deal->end_date) }}
                            </span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xxl w-200px w-md-200px mw-200" data-label="Status">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_flash_deal_status(this)" value="{{ $flash_deal->id }}" type="checkbox" <?php if($flash_deal->status == 1) echo "checked";?> >
                                        <span class="slider round"></span>
                                    </label>
                            </span>
                        </div>
                    </div>
                </td>
                <td class="align-middle hide-xl w-200px w-md-200px mw-200" data-label="Featured">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-12 fw-700">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input
                                        @can('publish_flash_deal') onchange="update_flash_deal_feature(this)" @endcan
                                        value="{{ $flash_deal->id }}" type="checkbox"
                                            <?php if($flash_deal->featured == 1) echo "checked";?>
                                            @cannot('publish_flash_deal') disabled @endcan
                                        >
                                        <span class="slider round"></span>
         						</label>
                            </span>
                        </div>
                    </div>
                </td>
                @canany(['edit_flash_deal','delete_flash_deal'])
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
                                    <!--Edit-->
                                    @can('edit_flash_deal')
                                        <a href="{{route('flash_deals.edit', ['id'=>$flash_deal->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}"
                                            class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark ">
                                            <span
                                                class="fs-14 fw-500 pl-10px">{{ translate('Edit') }}</span>
                                        </a>
                                    @endcan
                                    <!--Delete-->
                                    @can('delete_flash_deal')
                                        <a href="javascript:void(0)"
                                            class="d-flex text-danger align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$flash_deal->id}})"
                                            title="{{ translate('Delete') }}">
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
        {{ $flash_deals->appends(request()->input())->links() }}
    </div>
</div>