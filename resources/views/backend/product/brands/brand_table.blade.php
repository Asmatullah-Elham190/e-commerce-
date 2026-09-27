<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
        <thead>
            <tr>
                @if (auth()->user()->can('delete_brand'))
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
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Logo') }}
                </th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Name') }}
                </th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary text-nowrap">
                    {{ translate('Qty Products') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Created') }}
                </th>
                <th class="hide-lg text-uppercase fs-12 fw-700 text-secondary">
                    {{ translate('Categories') }}</th>
                <th class="text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">
                    {{ translate('Options') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $key => $brand)
                <tr class="data-row">
                    <td class="align-middle w-40px">
                        <div>
                            <button type="button"
                                class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                        </div>
                        @if (auth()->user()->can('delete_brand'))
                        <div class="form-group d-inline-block">
                            <label class="aiz-checkbox">
                                <input type="checkbox" class="check-one" name="id[]" value="{{ $brand->id }}">
                                <span class="aiz-square-check"></span>
                            </label>
                        </div>
                        @else
                        <div class="form-group d-inline-block">
                        {{ $key + 1 + ($brands->currentPage() - 1) * $brands->perPage() }}
                        </div>
                        @endif
                    </td>

                    <td class="px-0 align-middle" data-label="Logo">
                        <div class="w-40px h-60px h-md-48px w-md-70px rounded-2 overflow-hidden d-flex align-items-center justify-content-center">
                            <img src="{{ uploaded_asset($brand->logo) }}"
                                alt="Image" class="img-fit">
                        </div>
                    </td>

                    <td class="align-middle" data-label="Name">
                        <div class="row gutters-5 w-100px w-md-100px mw-100">
                            <div class="col">
                                <span
                                    class="text-dark text-truncate-2 fs-14 fw-600">{{ $brand->getTranslation('name') }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle hide-sm text-center" data-label="Qty Products">
                        <div class="row gutters-5 w-100px w-md-100px mw-100">
                            <div class="col">
                                <span class="text-dark fs-14 fw-700">
                                    {{$brand->products_count}}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle hide-sm" data-label="Created">
                        <div class="row gutters-5 w-100px w-md-100px mw-100">
                            <div class="col">
                                <span class="text-dark fs-14 fw-400">
                                    {{ $brand->created_at->format('d M, Y') }}
                                </span>
                            </div>
                        </div>
                    </td>
                    @php
                        $categories = $brand->products->pluck('categories')->flatten()->unique('id');
                        $categoryNames = $categories->pluck('name');
                        $displayText = $categoryNames->implode(', ');
                        $charLimit = 60;
                    @endphp
                    <td class="align-middle hide-lg" data-label="Categories">
                        <div class="row gutters-5 w-200px w-md-200px mw-200">
                            <div class="col">
                                @if (strlen($displayText) > $charLimit)
                                    <span
                                        class="text-dark text-truncate-1 fs-14 fw-400">{{ Str::limit($displayText, $charLimit) }}</span>
                                    <a href="javascript:void(0)"
                                        onclick='openRightcanvas({{ $brand->id }})'
                                        class="fs-14 fw-400 text-blue td-see-more">{{ translate('See More') }}</a>
                                @else
                                    <span
                                        class="text-dark text-truncate-1 fs-14 fw-400">{{ $displayText }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-right align-middle" data-label="Options">
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
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                <div class="table-options">
                                    <!--Edit-->
                                    @can('edit_brand')
                                    <a href="{{route('brands.edit', ['id'=>$brand->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11.985"
                                                height="12" viewBox="0 0 11.985 12">
                                                <path
                                                    id="edit_square_24dp_9393A3_FILL0_wght400_GRAD0_opsz24"
                                                    d="M121.2-909a1.154,1.154,0,0,1-.846-.352A1.154,1.154,0,0,1,120-910.2v-8.39a1.154,1.154,0,0,1,.352-.846,1.154,1.154,0,0,1,.846-.352h3.91a.541.541,0,0,1,.449.187.645.645,0,0,1,.15.412.626.626,0,0,1-.157.412.563.563,0,0,1-.457.187h-3.9v8.39h8.39v-3.91a.541.541,0,0,1,.187-.449.645.645,0,0,1,.412-.15.645.645,0,0,1,.412.15.541.541,0,0,1,.187.449v3.91a1.154,1.154,0,0,1-.352.846,1.154,1.154,0,0,1-.846.352ZM125.393-914.393Zm-1.8,1.2v-1.453a1.183,1.183,0,0,1,.09-.457,1.165,1.165,0,0,1,.255-.382l5.154-5.154a1.2,1.2,0,0,1,.4-.27,1.2,1.2,0,0,1,.449-.09,1.183,1.183,0,0,1,.457.09,1.219,1.219,0,0,1,.4.27l.839.854a1.347,1.347,0,0,1,.255.4,1.147,1.147,0,0,1,.09.442,1.237,1.237,0,0,1-.082.442,1.122,1.122,0,0,1-.262.4l-5.154,5.154a1.27,1.27,0,0,1-.382.262,1.1,1.1,0,0,1-.457.1h-1.453a.58.58,0,0,1-.427-.172A.58.58,0,0,1,123.6-913.195Zm7.206-5.753-.839-.839Zm-6.007,5.154h.839l3.476-3.476-.419-.419-.434-.419-3.461,3.461Zm3.9-3.9-.434-.419.434.419.419.419Z"
                                                    transform="translate(-120 921)"
                                                    fill="#414141" />
                                            </svg>
                                        </span>
                                        <span  class="fs-14 text-secondary fw-500 pl-10px">{{translate('Edit')}}</span>
                                    </a>
                                    @endcan
                                    <!--Delete-->
                                    @can('delete_brand')
                                    <a  href="javascript:void(0)" class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$brand->id}})" title="{{ translate('Delete') }}">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10.667"
                                                height="12" viewBox="0 0 10.667 12">
                                                <path id="Path_45219" data-name="Path 45219"
                                                    d="M162-828a1.284,1.284,0,0,1-.942-.392,1.284,1.284,0,0,1-.392-.942V-838H160v-1.333h3.333V-840h4v.667h3.333V-838H170v8.667a1.284,1.284,0,0,1-.392.942,1.284,1.284,0,0,1-.942.392Zm6.667-10H162v8.667h6.667Zm-5.333,7.333h1.333v-6h-1.333Zm2.667,0h1.333v-6H166ZM162-838v0Z"
                                                    transform="translate(-160 840)"
                                                    fill="#dc3545" />
                                            </svg>
                                        </span>
                                        <span class="fs-14 text-danger fw-500 pl-10px">{{translate('Delete')}}</span>
                                    </a>
                                    @endcan
                                    <!--Change Logo-->
                                    @can('edit_brand')
                                    {{--<a href="#"
                                        class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="9.967"
                                                height="12" viewBox="0 0 9.967 12">
                                                <path
                                                    id="replace_image_16dp_9393A3_FILL0_wght400_GRAD0_opsz20"
                                                    d="M182.548-854.281l-.359-.469a.251.251,0,0,0-.219-.125.251.251,0,0,0-.219.125l-.391.516a.255.255,0,0,0-.027.3.263.263,0,0,0,.246.156h2.438a.26.26,0,0,0,.253-.156.264.264,0,0,0-.034-.3l-.609-.8a.273.273,0,0,0-.219-.117.236.236,0,0,0-.219.1ZM180.36-852a1.083,1.083,0,0,1-.795-.33,1.083,1.083,0,0,1-.33-.795V-858a1.091,1.091,0,0,1,.326-.795,1.061,1.061,0,0,1,.783-.33h4.891a1.083,1.083,0,0,1,.795.33,1.083,1.083,0,0,1,.33.795v4.875a1.083,1.083,0,0,1-.33.795,1.083,1.083,0,0,1-.795.33Zm0-1.125h4.875V-858H180.36Zm0,0v0Zm7.125-7.125H184.86a.546.546,0,0,1-.4-.161.539.539,0,0,1-.162-.4.55.55,0,0,1,.162-.4.54.54,0,0,1,.4-.164h1.125a4.087,4.087,0,0,0-1.422-1.109,4.087,4.087,0,0,0-1.766-.391,4.044,4.044,0,0,0-2.094.563,4.039,4.039,0,0,0-1.5,1.547.624.624,0,0,1-.383.32.614.614,0,0,1-.422-.039.558.558,0,0,1-.281-.3.43.43,0,0,1,.023-.4,5.2,5.2,0,0,1,1.914-2.047A5.124,5.124,0,0,1,182.8-864a5.205,5.205,0,0,1,2.3.522A5.144,5.144,0,0,1,186.923-862v-1.437a.546.546,0,0,1,.161-.4.539.539,0,0,1,.4-.162.55.55,0,0,1,.4.162.54.54,0,0,1,.164.4v2.625a.544.544,0,0,1-.162.4A.544.544,0,0,1,187.485-860.25Z"
                                                    transform="translate(-178.08 864)"
                                                    fill="#1983ff" />
                                            </svg>
                                        </span>
                                        <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('Change Logo')}}</span>
                                    </a>--}}
                                    @endcan
                                    <!--View Products-->
                                    <a href="{{ route('products.all', ['brand_id' => $brand->id, 'brand_name' => $brand->name]) }}" 
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                height="8.571" viewBox="0 0 12 8.571">
                                                <path
                                                    id="view_array_16dp_4B77D1_FILL0_wght400_GRAD0_opsz20"
                                                    d="M144-712.286v-6.857a.826.826,0,0,1,.25-.607.827.827,0,0,1,.607-.25.827.827,0,0,1,.607.25.827.827,0,0,1,.25.607v6.857a.827.827,0,0,1-.25.607.827.827,0,0,1-.607.25.827.827,0,0,1-.607-.25A.827.827,0,0,1,144-712.286Zm3.429.857a.827.827,0,0,1-.607-.25.827.827,0,0,1-.25-.607v-6.857a.827.827,0,0,1,.25-.607.827.827,0,0,1,.607-.25h5.143a.827.827,0,0,1,.607.25.826.826,0,0,1,.25.607v6.857a.827.827,0,0,1-.25.607.827.827,0,0,1-.607.25Zm6.857-.857v-6.857a.826.826,0,0,1,.25-.607.826.826,0,0,1,.607-.25.827.827,0,0,1,.607.25.826.826,0,0,1,.25.607v6.857a.827.827,0,0,1-.25.607.827.827,0,0,1-.607.25.826.826,0,0,1-.607-.25A.827.827,0,0,1,154.286-712.286Zm-6.429-.429h4.286v-6h-4.286ZM150-715.714Z"
                                                    transform="translate(-144 720)"
                                                    fill="#1983ff" />
                                            </svg>
                                        </span>
                                        <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('View Products')}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="aiz-pagination" id="pagination">
        {{ $brands->links() }}
    </div>
</div>