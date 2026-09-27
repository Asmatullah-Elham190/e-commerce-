<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('delete_note'))
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
                    {{ translate('Type') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Seller Can Access') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-400 text-secondary">
                    {{ translate('Description') }}
                </th>
                @canany(['delete_note', 'edit_note'])
                    <th class="hide-xs text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                        {{ translate('Options') }}
                    </th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @forelse ($notes as $key => $note)
            <tr class="data-row">
                <td class="align-middle h-40">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('delete_note'))
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox mb-2">
                                <input type="checkbox" class="check-one" name="id[]"
                                    value="{{ $note->id }}">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                    @else
                        <div class="form-group d-inline-block">
                            {{ $key + 1 + ($notes->currentPage() - 1) * $notes->perPage() }}
                        </div>
                    @endif
                </td>
                <td class="align-middle" data-label="Type">
                    <div class="row gutters-5 w-100px w-md-100px mw-100">
                        <div class="col">
                            <span
                                class="fs-14 fw-400 text-dark">
                                    {{ucfirst($note->note_type)}}
                            </span>
                        </div>
                    </div>
                </td>
                <td class="hide-sm align-middle" data-label="Seller Can Access">
                    <div class="row gutters-5 w-100px w-md-100px mw-100">
                        <div class="col d-flex align-items-center">
                            <span class="fs-14 fw-400 text-dark">
                                @if( $note->user_id == get_admin()->id)
                                    <label class="aiz-switch aiz-switch-blue mb-0">
                                        <input onchange="updateSellerAccess(this)" value="{{ $note->id }}" type="checkbox" <?php if ($note->seller_access == 1) echo "checked"; ?> >
                                        <span class="slider round"></span>
                                    </label>
                                @endif
                            </span>
                        </div>
                    </div>
                </td>
                <td class="hide-sm align-middle" data-label="decription">
                    <div class="row gutters-5 w-300px w-md-300px w-xl-500px mw-300">
                        <div class="col d-flex align-items-center">
                            <span
                                class="fs-14 fw-400 text-dark">
                                    {{ $note->getTranslation('description') }}
                            </span>
                        </div>
                    </div>
                </td>
                @canany(['edit_note', 'delete_note'])
                    <td class="align-middle hide-xs text-right" data-label="Options">
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
                                        @if(auth()->user()->can('delete_note'))
                                            <a href="javascript:void(0);" title="{{ translate('Edit') }}" onclick="edit_note({{ $note->id }})"
                                                class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark">
                                                <span
                                                    class="fs-14 fw-500 pl-10px">{{ translate('Edit') }}</span>
                                            </a>
                                        @endif
                                        @can('delete_note')
                                        <a href="javascript:void(0)"
                                            class="d-flex text-danger align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$note->id}})"
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
        {{ $notes->appends(request()->input())->links() }}
    </div>
</div>