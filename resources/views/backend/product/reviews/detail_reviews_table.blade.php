<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('can_published_reviews'))
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
                    {{ translate('Customer') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Rating') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Comment') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Date') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Published') }}
                </th>
                @can('edit_custom_review')
                    <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                        {{ translate('Options') }}
                    </th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse ($reviews as $key => $review)
                    @php
                        if ($review->type == 'real') {
                            $customerName = $review->user->name ?? translate('Customer Not Found');
                            $customerAvatar = uploaded_asset($review->user->avatar_original ?? null);
                        } else {
                            $customerName = $review->custom_reviewer_name;
                            $customerAvatar = uploaded_asset($review->custom_reviewer_image);
                        }
                    @endphp
                    <tr class="data-row">
                        <td class="align-middle h-40">
                            <div>
                                <button type="button"
                                    class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                            </div>
                            @if (auth()->user()->can('can_published_reviews'))
                                <div class="form-group d-inline-block">
                                    <label class="aiz-checkbox mb-2">
                                        <input type="checkbox" class="check-one" name="id[]" value="{{ $review->id }}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            @else
                                <div class="form-group d-inline-block">
                                    {{ $key + 1 + ($reviews->currentPage() - 1) * $reviews->perPage() }}
                                </div>
                            @endif
                        </td>
                        <td class="align-middle w-200px w-md-200px mw-200 pr-3" data-label="Product Name">
                            <div class="row gutters-5">
                                <div class="col">
                                    <span class="fs-14 fw-400 text-dark">
                                        <div class="row gutters-5 align-items-center">
                                            <div class="col-auto">
                                                <img src="{{ $customerAvatar }}" alt="Image"
                                                    class="size-50px img-fit rounded-circle">
                                            </div>
                                            <div class="col">
                                                <span class="fw-600 text-truncate-1">{{ $customerName }}</span>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle w-100px w-md-100px mw-100" data-label="Rating">
                            <div class="row gutters-5">
                                <div class="col d-flex flex-wrap align-items-center" style="gap:10px;">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ $review->rating }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-xs align-middle w-500px w-md-500px mw-500 pr-5" data-label="Comment">
                            <div class="row gutters-5">
                                <div class="col d-flex flex-wrap align-items-center" style="gap:10px;">
                                    <span class="fs-14 fw-400 text-dark">
                                        {{ $review->comment }}
                                        @if($review->photos != null)
                                            <div class="spotlight-group d-flex flex-wrap mt-2">
                                                @foreach (explode(',', $review->photos) as $photo)
                                                    <a href="{{ uploaded_asset($photo) }}"
                                                        class="mr-2 mr-md-3 mb-2 mb-md-3 border overflow-hidden has-transition hov-scale-img hov-border-primary"
                                                        target="_blank">
                                                        <img class="img-fit h-60px lazyload has-transition"
                                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                            data-src="{{ uploaded_asset($photo) }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="hide-sm align-middle w-200px w-md-200px mw-200 pr-3" data-label="Date">
                            <div class="row gutters-5">
                                <span class="fs-14 fw-400 text-dark">
                                    {{ date("j F, Y", strtotime($review->created_at)) }}
                                </span>
                            </div>
                        </td>
                        <td class="hide-sm align-middle w-100px w-md-100px mw-100" data-label="Published">
                            <div class="row gutters-5">
                                <div class="col d-flex flex-wrap align-items-center" style="gap:10px;">
                                    <span class="fs-14 fw-400 text-dark">
                                        <label class="aiz-switch aiz-switch-blue mb-0">
                                            <input type="checkbox" onchange="singlepublished(this, {{ $review->id }})"
                                                <?php if ($review->status == 1) echo "checked"; ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </td>
                        @can('edit_custom_review')
                            <td class="align-middle hide-s text-right" data-label="Options">
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
                                                <a href="{{route('custom-review.edit', $review->id)}}"
                                                    title="{{ translate('Edit') }}"
                                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                                    <span class="fs-14 fw-500 pl-10px">{{ translate('Edit') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endcan
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
        {{ $reviews->appends(request()->input())->links() }}
    </div>
</div>