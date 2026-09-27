<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Email Type') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Subject') }}
                </th>
                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Status') }}
                </th>
                <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Options') }}
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($emails as $key => $email)
            <tr class="data-row">
                <td class="align-middle h-40 w-50px w-md-50px mw-50">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    <div class="form-group d-inline-block mb-0">
                        {{ $key + 1 + ($emails->currentPage() - 1) * $emails->perPage() }}
                    </div>
                </td>
                <td class="align-middle w-400px w-md-400px mw-400 pr-3" data-label="Email Type">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-600">{{ translate($email->email_type)}}</span>
                        </div>
                    </div>
                </td>
                <td class="hide-md align-middle w-600px w-md-600px mw-600 pr-3" data-label="Subject">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">{{ $email->subject}}</span>
                        </div>
                    </div>
                </td>
                <td class="hide-xs align-middle w-100px w-md-100px mw-100 pr-3" data-label="Status">
                    <div class="row gutters-5">
                        <div class="col">
                            <span
                                class="text-blue fs-14 fw-700">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" 
                                        value="{{ $email->id }}"
                                        type="checkbox" 
                                        @if($email->status == 1) checked @endif
                                        @if($email->is_status_changeable == 0) disabled @endif>
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
                                    <a href="{{ route('email-templates.edit', $email->id) }}" title="{{ translate('Edit') }}"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark ">
                                        <span
                                            class="fs-14 fw-500 pl-10px">{{ translate('Edit') }}</span>
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
        {{ $emails->appends(request()->input())->links() }}
    </div>
</div>