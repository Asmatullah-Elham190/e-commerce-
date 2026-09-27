<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
         <thead>
            <tr>
                @if (auth()->user()->can('product_delete'))
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
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Thumb') }}</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">{{ translate('Name / Brand') }}</th>

                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Owner / Category') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Ratings') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Price Details') }}
                </th>

                @if($ptoduct_type != 'pos_product_list')
                <th class="hide-xl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Info') }}</th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Published') }}</th>
                @if (get_setting('product_approve_by_admin') == 1 && $type == 'seller')
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Approved') }}</th>
                @endif

                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary">{{ translate('Featured') }}
                </th>

                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Todays Deal') }}</th>
                @endif
                <th class="text-right text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Options') }}</th>
            </tr>
        </thead>

        <tbody>
            <!-- ROW  -->
            @forelse ($products as $key => $product)
            <tr class="data-row">
                
                <td class="align-middle w-40px">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    @if (auth()->user()->can('product_delete'))
                    <div class="form-group d-inline-block">
                        <label class="aiz-checkbox">
                            <input type="checkbox" class="check-one" name="id[]"value="{{ $product->id }}">
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>
                    @else
                    <div class="form-group d-inline-block">{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</div>
                    @endif
                </td>
               

                
                <td data-label="Thumb" class="w-60px w-md-80px w-md-100px">
                    <div class="w-40px h-40px w-sm-60px h-sm-60px w-md-80px h-md-80px rounded-2 overflow-hidden border">
                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image" class="img-fit">
                    </div>

                </td>
                <td data-label="Name" class="w-lg-300px">
                    <div class="row gutters-5 w-sm-180px w-md-200px w-lg-100 mw-100 ml-1 ml-lg-0">
                        <div class="col">
                            <span class="text-truncate-2 fs-12 fs-md-14 fw-400 mr-2">{{ $product->getTranslation('name') }}</span>
                            @if(isset($product->brand->name))
                                <a href="{{ route('products.all', ['brand_id' => $product->brand->id, 'brand_name' => $product->brand->name]) }}" class="fs-12 fs-md-14 fw-700 d-inline-block mt-1">
                                    {{ translate($product->brand->name) }}
                                </a>
                            @else
                                <span class="fs-12 fs-md-14 fw-700 d-inline-block mt-1 text-secondary">{{ translate('No Brand') }}</span>
                            @endif

                        </div>
                    </div>
                </td>
                <td class="hide-xs" data-label="Owner Category">
                     @php $shop = optional(optional($product->user)->shop); @endphp
                    <a href="{{ $shop->id ? route('sellers.profile', encrypt($shop->id)) : '#' }}" class="fs-12 fs-md-14 fw-700 d-block">
                         {{ $shop->name ?? translate('Inhouse') }}
                    </a>
                    <span class="fs-12 fw-200 text-secondary d-block pt-1">{{ translate('Main Category') }}</span>
                    <p class="fs-12 fs-md-14 fw-700 m-0">{{translate($product->main_category->name ?? '')}}</p> 
                </td>
                <td class="hide-sm" data-label="Ratings">
                    <!--Ratting-->
                    <div class="d-flex align-items-center rattings">
                        <span class="rating rating-mr-1">
                            {{ renderStarRatingLatest($product->rating) }}
                        </span>
                    </div>
                    <p class="fs-14 m-0 py-1"><span class="fw-700">{{ $product->rating }}</span><span class="px-1">{{ translate('out of') }}</span>
                        <span>5.0</span>
                    </p>
                    @php
                        $total = 0;
                        $total += $product->reviews->where('status', 1)->count();
                    @endphp

                    <p class="fs-14 fw-400 text-secondary m-0">
                        <span class="mr-1">{{ $total }}</span>{{translate('Reviews') }}
                    </p>
                </td>

                <td class="hide-md align-middle" data-label="Price Details">
                    <div class="border-width-3  border-left border-blue px-2 py-0 mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Price') }}</span>
                        <p class="fs-16 fw-700 m-0">{{ single_price($product->unit_price) }}</p>
                    </div>
                    @if (discount_in_percentage($product) > 0)
                    <div class="border-width-3  border-left border-danger px-2 py-0">
                        <p class="fs-14 fw-400 m-0 py-5px">{{ translate('Discount') }}
                            <span class="text-danger fw-700 pl-1">{{ discount_in_percentage($product) }}%</span>
                        </p>
                    </div>
                    @endif
                </td>
                @if($ptoduct_type != 'pos_product_list')
                <td class="hide-xl" data-label="Info">
                    <span class="fs-12 fw-400 text-secondary">{{('Number of Sale')}}</span>
                    <p class="fs-16 fw-700 m-0 pb-10px">{{ $product->num_of_sale }}</p>
                    @if(!$product->draft && !$product->digital)
                    <a href="javascript:void(0)" onclick='openRightcanvas({{ $product->id }}, "{{ $product->getTranslation('name') }}" )'
                        class="fs-14 fw-300 text-blue td-see-more">{{translate('View Stock')}}</a>
                    @endif
                </td>
                        
                <td class="hide-xxl align-middle" data-label="Published">
                    @if (!$product->draft)
                    <label class="aiz-switch aiz-switch-blue mb-0">
                        <input onchange="update_published(this)" value="{{ $product->id }}"type="checkbox" <?php if ($product->published == 1) {
                                echo 'checked';
                            } ?>>
                        <span class="slider round"></span>
                    </label>
                    @endif
                </td>
                @if (get_setting('product_approve_by_admin') == 1 && $type == 'seller')
                <td class="hide-xxl align-middle" data-label="Approved">
                     @if (!$product->draft)
                    <label class="aiz-switch aiz-switch-blue mb-0">
                        <input onchange="update_approved(this)" value="{{ $product->id }}"
                            type="checkbox" <?php if ($product->approved == 1) {
                                echo 'checked';
                            } ?>>
                        <span class="slider round"></span>
                    </label>
                    @endif
                </td>
                @endif

                <td class="hide-xxl align-middle" data-label="Featured">
                    @if (!$product->draft)
                    <label class="aiz-switch aiz-switch-blue mb-0">
                        <input onchange="update_featured(this)" value="{{ $product->id }}"
                            type="checkbox" <?php if ($product->featured == 1) {
                                echo 'checked';
                            } ?>>
                        <span class="slider round"></span>
                    </label>
                    @endif
                </td>

                <td class="hide-xxl align-middle" data-label="TodaysDeal">
                    @if (!$product->draft)
                    <label class="aiz-switch aiz-switch-blue mb-0">
                        <input onchange="update_todays_deal(this)" value="{{ $product->id }}"
                            type="checkbox" <?php if ($product->todays_deal == 1) {
                                echo 'checked';
                            } ?>>
                        <span class="slider round"></span>
                    </label>
                    @endif
                </td>
                @endif

                <td class="text-right align-middle">
                    <div class="dropdown float-right">
                        <button class="btn btn-light w-30px h-30px w-sm-35px h-sm-35px d-flex align-items-center justify-content-center action-toggle p-0" type="button"
                            data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="16"
                                viewBox="0 0 3 16">
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
                                @if($ptoduct_type != 'pos_product_list')
                                <!--Edit-->
                                @can('product_edit')
                                @php
                                    $routeName = $type == 'seller' ? 'products.seller.edit' : 'products.admin.edit';

                                    $params = [
                                        'id' => $product->id,
                                        'lang' => env('DEFAULT_LANGUAGE'),
                                    ];
                                    $params['source'] = $source;
                                @endphp

                                <a href="{{ route($routeName, $params) }}"  class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11.985"
                                            height="12" viewBox="0 0 11.985 12">
                                            <path
                                                id="edit_square_24dp_9393A3_FILL0_wght400_GRAD0_opsz24"
                                                d="M121.2-909a1.154,1.154,0,0,1-.846-.352A1.154,1.154,0,0,1,120-910.2v-8.39a1.154,1.154,0,0,1,.352-.846,1.154,1.154,0,0,1,.846-.352h3.91a.541.541,0,0,1,.449.187.645.645,0,0,1,.15.412.626.626,0,0,1-.157.412.563.563,0,0,1-.457.187h-3.9v8.39h8.39v-3.91a.541.541,0,0,1,.187-.449.645.645,0,0,1,.412-.15.645.645,0,0,1,.412.15.541.541,0,0,1,.187.449v3.91a1.154,1.154,0,0,1-.352.846,1.154,1.154,0,0,1-.846.352ZM125.393-914.393Zm-1.8,1.2v-1.453a1.183,1.183,0,0,1,.09-.457,1.165,1.165,0,0,1,.255-.382l5.154-5.154a1.2,1.2,0,0,1,.4-.27,1.2,1.2,0,0,1,.449-.09,1.183,1.183,0,0,1,.457.09,1.219,1.219,0,0,1,.4.27l.839.854a1.347,1.347,0,0,1,.255.4,1.147,1.147,0,0,1,.09.442,1.237,1.237,0,0,1-.082.442,1.122,1.122,0,0,1-.262.4l-5.154,5.154a1.27,1.27,0,0,1-.382.262,1.1,1.1,0,0,1-.457.1h-1.453a.58.58,0,0,1-.427-.172A.58.58,0,0,1,123.6-913.195Zm7.206-5.753-.839-.839Zm-6.007,5.154h.839l3.476-3.476-.419-.419-.434-.419-3.461,3.461Zm3.9-3.9-.434-.419.434.419.419.419Z"
                                                transform="translate(-120 921)" fill="#414141" />
                                        </svg>
                                    </span>
                                    <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('Edit')}}</span>
                                </a>
                                @endcan
                                <!--View Product-->
                                @if(!$product->draft)
                                <a href="{{ route('product', $product->slug) }}" target="_blank"
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                            height="10" viewBox="0 0 12 8.182">
                                            <path id="Path_45218" data-name="Path 45218"
                                                d="M46-793.455a2.367,2.367,0,0,0,1.739-.716,2.367,2.367,0,0,0,.716-1.739,2.367,2.367,0,0,0-.716-1.739A2.367,2.367,0,0,0,46-798.364a2.367,2.367,0,0,0-1.739.716,2.367,2.367,0,0,0-.716,1.739,2.367,2.367,0,0,0,.716,1.739A2.367,2.367,0,0,0,46-793.455Zm0-.982a1.42,1.42,0,0,1-1.043-.43,1.42,1.42,0,0,1-.43-1.043,1.42,1.42,0,0,1,.43-1.043,1.42,1.42,0,0,1,1.043-.43,1.42,1.42,0,0,1,1.043.43,1.42,1.42,0,0,1,.43,1.043,1.42,1.42,0,0,1-.43,1.043A1.42,1.42,0,0,1,46-794.436Zm0,2.618a6.315,6.315,0,0,1-3.627-1.111A6.318,6.318,0,0,1,40-795.909a6.318,6.318,0,0,1,2.373-2.98A6.315,6.315,0,0,1,46-800a6.315,6.315,0,0,1,3.627,1.111A6.318,6.318,0,0,1,52-795.909a6.318,6.318,0,0,1-2.373,2.98A6.315,6.315,0,0,1,46-791.818ZM46-795.909Zm0,3a5.206,5.206,0,0,0,2.83-.811,5.331,5.331,0,0,0,1.97-2.189,5.331,5.331,0,0,0-1.97-2.189,5.206,5.206,0,0,0-2.83-.811,5.206,5.206,0,0,0-2.83.811,5.331,5.331,0,0,0-1.97,2.189,5.331,5.331,0,0,0,1.97,2.189A5.206,5.206,0,0,0,46-792.909Z"
                                                transform="translate(-40 800)" fill="#414141" />
                                        </svg>

                                    </span>
                                    <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('View Product')}}</span>
                                </a>
                                @endif
                                @if($product->digital)
                                @can('add_digital_product')
                                <a
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue" href="{{route('digitalproducts.download', encrypt($product->id))}}">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10.2" height="12" viewBox="0 0 10.2 12">
                                            <path d="M 4.6 7.8 L 4.6 1.8 L 5.6 1.8 L 5.6 7.8 L 8.4 7.8 L 5.1 11.1 L 1.8 7.8 L 4.6 7.8 Z M 1.8 10.8 L 8.4 10.8 L 8.4 12 L 1.8 12 L 1.8 10.8 Z"
                                                    fill="#414141"
                                                    transform="translate(0 -1.8)"
                                                />
                                        </svg>

                                    </span>
                                    <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('Download')}}</span>
                                </a>
                                @endcan
                                @else
                                @can('product_duplicate')
                                <!--Make a Clone-->
                                <a
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="duplicateProduct({{$product->id}},'{{ $type }}')" href="#">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10.2"
                                            height="12" viewBox="0 0 10.2 12">
                                            <path id="Path_45217" data-name="Path 45217"
                                                d="M123.6-870.4a1.155,1.155,0,0,1-.847-.353,1.155,1.155,0,0,1-.353-.847v-7.2a1.156,1.156,0,0,1,.353-.847A1.155,1.155,0,0,1,123.6-880H129a1.155,1.155,0,0,1,.848.352,1.156,1.156,0,0,1,.352.847v7.2a1.155,1.155,0,0,1-.352.847,1.155,1.155,0,0,1-.848.353Zm0-1.2H129v-7.2h-5.4Zm-2.4,3.6a1.155,1.155,0,0,1-.848-.353A1.155,1.155,0,0,1,120-869.2v-8.4h1.2v8.4h6.6v1.2Zm2.4-3.6v0Z"
                                                transform="translate(-120 880)" fill="#414141" />
                                        </svg>

                                    </span>
                                    <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('Make a Clone')}}</span>
                                </a>
                                @endcan
                                @endif
                                @can('product_delete')
                                <!--Delete-->
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue" onclick="singleDelete({{$product->id}})">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10.667"
                                            height="12" viewBox="0 0 10.667 12">
                                            <path id="Path_45219" data-name="Path 45219"
                                                d="M162-828a1.284,1.284,0,0,1-.942-.392,1.284,1.284,0,0,1-.392-.942V-838H160v-1.333h3.333V-840h4v.667h3.333V-838H170v8.667a1.284,1.284,0,0,1-.392.942,1.284,1.284,0,0,1-.942.392Zm6.667-10H162v8.667h6.667Zm-5.333,7.333h1.333v-6h-1.333Zm2.667,0h1.333v-6H166ZM162-838v0Z"
                                                transform="translate(-160 840)" fill="#dc3545" />
                                        </svg>
                                    </span>
                                    <span class="fs-14 text-danger fw-500 pl-10px">{{translate('Delete')}}</span>
                                </a>
                                @endcan
                                @else
                                @can('pos_product_delete')
                                <!--Delete-->
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-nowrap" onclick="singleRemove({{$product->id}})">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10.667"
                                            height="12" viewBox="0 0 10.667 12">
                                            <path id="Path_45219" data-name="Path 45219"
                                                d="M162-828a1.284,1.284,0,0,1-.942-.392,1.284,1.284,0,0,1-.392-.942V-838H160v-1.333h3.333V-840h4v.667h3.333V-838H170v8.667a1.284,1.284,0,0,1-.392.942,1.284,1.284,0,0,1-.942.392Zm6.667-10H162v8.667h6.667Zm-5.333,7.333h1.333v-6h-1.333Zm2.667,0h1.333v-6H166ZM162-838v0Z"
                                                transform="translate(-160 840)" fill="#dc3545" />
                                        </svg>
                                    </span>
                                    <span class="fs-14 text-danger fw-500 pl-5px">{{translate('Remove from POS')}}</span>
                                </a>
                                @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <div class="w-100">
                        <h5 class="fs-16 fw-bold text-gray">{{ translate('No Products found!') }}</h5>
                        <i class="las la-frown fs-48 text-soft-white"></i>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="aiz-pagination" id="pagination">
        {{ $products->links() }}
    </div>
</div>