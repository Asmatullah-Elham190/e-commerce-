<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('custom_label_delete'))
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
                    {{ translate('Label') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Added By') }}
                </th>
                <th class="hide-md text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Seller Can Access') }}
                </th>
                <th class="hide-sm text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">
                    {{ translate('Status') }}
                </th>
                @canany(['custom_label_edit','custom_label_delete'])
                    <th class="hide-s text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                        {{ translate('Options') }}
                    </th>
                @endcanany
            </tr>
        </thead>
        <tbody>
            @forelse ($custom_labels as $key => $custom_label)
            <tr class="data-row">
                <td class="align-middle h-40">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if($custom_label->id == 200 || $custom_label->id == 201 || $custom_label->id == 202 || $custom_label->id == 203) 
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 16 20">
                            <path id="df12b5039313fc3798dfa93cfb504acd" d="M17,9V7A5,5,0,0,0,7,7V9a2.946,2.946,0,0,0-3,3v7a2.946,2.946,0,0,0,3,3H17a2.946,2.946,0,0,0,3-3V12A2.946,2.946,0,0,0,17,9ZM9,7a3,3,0,0,1,6,0V9H9Zm4.1,8.5-.1.1V17a1,1,0,0,1-2,0V15.6a1.487,1.487,0,1,1,2.1-.1Z" transform="translate(-4 -2)" fill="#d1d1e2"/>
                        </svg> 
                    @else    
                        @if (auth()->user()->can('custom_label_delete'))
                            <div class="form-group d-inline-block">
                                <label class="aiz-checkbox mb-2">
                                    <input type="checkbox" class="check-one" name="id[]"
                                        value="{{ $custom_label->id }}">
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        @else
                            <div class="form-group d-inline-block">
                                {{ $key + 1 + ($custom_labels->currentPage() - 1) * $custom_labels->perPage() }}
                            </div>
                        @endif
                    @endif
                </td>
                <td class="align-middle" data-label="Label">
                    <div class="row gutters-5 w-200px w-md-200px mw-200">
                        <div class="col">
                            <span class="px-2 py-1 rounded rounded-4" style="background-color: {{ $custom_label->background_color }}; color: {{$custom_label->text_color}}">{{ $custom_label->getTranslation('text') }}</span>
                        </div>
                    </div>
                </td>
                @php
                    $admin_name = \App\Models\User::where('user_type', 'admin')->first();
                @endphp
                <td class="hide-sm align-middle" data-label="Added By">
                    <div class="row gutters-5 w-200px w-md-200px mw-200">
                        <div class="col">
                            <span
                                class="text-dark fs-14 fw-400">{{ $custom_label->user->name ?? $admin_name->name}}</span>
                        </div>
                    </div>
                </td>
                <td class="hide-md align-middle w-200px w-md-200px mw-200" data-label="Seller Can Access">
                    <div class="row gutters-5">
                        <div class="col">
                            @if($custom_label->user_id == 0 || optional($custom_label->user_id == get_admin()->id))
                                <span class="text-dark fs-14 fw-400">
                                    <label class="aiz-switch aiz-switch-primary mb-0">
                                        <input value="{{ $custom_label->id }}" id="trigger_alert_{{ $custom_label->id }}" 
                                            type="checkbox" @if($custom_label->seller_access == 1) checked @endif
                                            onchange="trigger_alert(this)">
                                        <span class="slider round"></span>
                                    </label>
                                </span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="hide-sm align-middle w-200px w-md-200px mw-200" data-label="Status">
                    <div class="row gutters-5">
                        <div class="col">
                            <span class="text-dark fs-14 fw-400">
                                <label class="aiz-switch aiz-switch-primary mb-0">
                                    <input value="{{ $custom_label->id }}" id="status_alert_{{ $custom_label->id }}" 
                                        type="checkbox" @if($custom_label->status == 1) checked @endif
                                        onchange="trigger_status_alert(this)">
                                    <span class="slider round"></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </td>
                @canany(['custom_label_edit','custom_label_delete'])
                    <td class="hide-s align-middle text-right" data-label="Options">
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
                                        @can('custom_label_edit')
                                            <a href="{{route('custom_label.edit', $custom_label->id)}}" title="{{ translate('Edit') }}"
                                                class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-dark ">
                                                <span
                                                    class="fs-14 fw-500 pl-10px">{{ translate('Edit') }}</span>
                                            </a>
                                        @endcan
                                        <!--Delete-->
                                        @if($custom_label->id != 200 && $custom_label->id != 201 && $custom_label->id != 202 && $custom_label->id != 203) 
                                            @can('custom_label_delete')
                                                <a href="javascript:void(0)"
                                                    class="d-flex text-danger align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$custom_label->id}})"
                                                    title="{{ translate('Delete') }}">
                                                    <span
                                                        class="fs-14 fw-500 pl-10px">{{ translate('Delete') }}</span>
                                                </a>
                                            @endcan
                                        @endif
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
        {{ $custom_labels->appends(request()->input())->links() }}
    </div>
</div>