<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Product') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Owner') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Rating') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Reviews') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Custom Reviews') }}
                </th>
                <th class="hide-xs text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Options') }}
                </th>
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
                        <div class="form-group d-inline-block mb-0 pr-3">
                            {{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}
                        </div>
                    </td>
                    <td class="align-middle w-500px w-md-500px mw-500 pr-5" data-label="Product Name">
                        <div class="row gutters-5">
                            <div class="col">
                                <span class="fs-14 fw-400 text-dark">
                                    <div class="row gutters-5 align-items-center">
                                        <div class="col-auto">
                                            <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="Image"
                                                class="size-50px img-fit">
                                        </div>
                                        <div class="col">
                                            <span
                                                class="text-muted text-truncate-1">{{ $product->getTranslation('name') }}</span>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle w-200px w-md-200px mw-200 hide-xs" data-label="Owner">
                        <div class="row gutters-5">
                            <div class="col">
                                <span class="fs-14 fw-400 text-dark text-truncate-1">
                                    {{ $product->user->name}}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle hide-xs w-150px w-md-150px mw-150" data-label="Rating">
                        <div class="row gutters-5">
                            <div class="col">
                                <span class="fs-14 fw-400 text-dark">
                                    {{ $product->rating}}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle hide-xs w-150px w-md-150px mw-150" data-label="Reviews">
                        <div class="row gutters-5">
                            <div class="col">
                                <span class="fs-14 fw-400 text-dark">
                                    {{ $product->reviews->count()}}
                                    @if($product->reviews()->where('viewed', 0)->count() > 0)
                                        <span class="badge badge-inline badge-danger">{{ translate('new') }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="hide-sm align-middle w-150px w-md-150px mw-150" data-label="Custom Reviews">
                        <div class="row gutters-5">
                            <div class="col">
                                <span class="fs-14 fw-400 text-dark">
                                    {{ $product->reviews->where('type', 'custom')->count()}}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle hide-xs text-right" data-label="Options">
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
                                        <a href="{{ route('detail-reviews', $product->id) }}"
                                            title="{{ translate('View Reviews') }}"
                                            class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                            <span class="fs-14 fw-500 pl-10px">{{ translate('View Reviews') }}</span>
                                        </a>
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