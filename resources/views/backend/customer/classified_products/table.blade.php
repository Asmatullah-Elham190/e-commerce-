<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('delete_classified_product'))
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
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Image') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Uploaded By') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Customer Status') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Published') }}
                </th>
                @can('delete_classified_product')
                    <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                        {{ translate('Options') }}
                    </th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $key => $product)
            <tr class="data-row">
                <td class="align-middle h-40">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('delete_classified_product'))
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox mb-2">
                                <input type="checkbox" class="check-one" name="id[]"
                                    value="{{ $product->id }}">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                    @else
                        <div class="form-group d-inline-block">
                            {{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}
                        </div>
                    @endif
                </td>
                <td class="align-middle w-300px w-md-300px mw-300 pr-5" data-label="Name">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="fs-14 fw-400 text-dark">
                                    <a href="{{ route('customer.product', $product->slug) }}" class="text-reset text-truncate-2" target="_blank">{{$product->getTranslation('name')}}</a>
                            </span>
                        </div>
                    </div>
                </td>
                <td class="hide-sm align-middle" data-label="Name">
                    <div class="row gutters-5 w-100px w-md-100px mw-100">
                        <div class="col">
                            <span
                                class="fs-14 fw-400 text-dark">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{translate('Product Image')}}" class="h-50px">
                            </span>
                        </div>
                    </div>
                </td>
                <td class="hide-md align-middle" data-label="Name">
                    <div class="row gutters-5 w-200px w-md-200px mw-200">
                        <div class="col">
                            <span
                                class="fs-14 fw-400 text-dark">
                                {{$product->user->name}}                            
                            </span>
                        </div>
                    </div>
                </td>
                <td class="hide-xs align-middle" data-label="Name">
                    <div class="row gutters-5 w-100px w-md-100px mw-100">
                        <div class="col">
                            <span
                                class="fs-14 fw-400 text-dark">
                                @if ($product->status == 1)
                                    <span class="badge badge-inline badge-success">{{ translate('PUBLISHED') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('UNPUBLISHED') }}</span>
                                @endif                        
                            </span>
                        </div>
                    </div>
                </td>
                <td class="hide-xs align-middle" data-label="Name">
                    <div class="row gutters-5 w-100px w-md-100px mw-100">
                        <div class="col">
                            <span
                                class="fs-14 fw-400 text-dark">
                                <label class="aiz-switch aiz-switch-blue mb-0">
                                    <input
                                        @can('publish_classified_product') onchange="update_published(this)" @endcan
                                        value="{{ $product->id }}" type="checkbox" <?php if($product->published == 1) echo "checked";?>
                                        @if(!auth()->user()->can('publish_classified_product')) disabled @endif
                                    >
                                    <span class="slider round"></span>
                                </label>                       
                            </span>
                        </div>
                    </div>
                </td>
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
                                    <a href="{{route('customer.product', $product->slug)}}" title="{{ translate('View') }}"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('View') }}</span>
                                    </a>
                                    <!--Delete-->
                                    @can('delete_classified_product')
                                        <a href="javascript:void(0)"
                                            class="d-flex text-danger align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$product->id}})"
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
        {{ $products->appends(request()->input())->links() }}
    </div>
</div>