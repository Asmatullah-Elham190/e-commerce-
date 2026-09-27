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
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Todays Deal') }}</th>
         
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
                                @canany(['remove_from_promotional', 'remove_from_todays_deal'])
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
                                    <span class="fs-14 text-danger fw-500 pl-10px">{{translate('Remove')}}</span>
                                </a>
                                @endcanany
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