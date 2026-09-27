<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
         <thead>
            <tr>
                @if (auth()->user()->can('facebook_catalog_delete'))
                    <th>
                        <div class="form-group">
                            <div class="aiz-checkbox-inline">
                                <label class="aiz-checkbox pt-5px d-block">
                                    <input type="checkbox" class="check-all-products">
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

                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Owner ') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Category') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Price Details') }}
                </th>
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
                    @if (auth()->user()->can('facebook_catalog_delete'))
                    <div class="form-group d-inline-block">
                        <label class="aiz-checkbox">
                            <input type="checkbox" class="check-one-product" name="id[]" value="{{ $product->id }}">
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
                </td>
                
                <td class="hide-sm" data-label="Category">
                    <p class="fs-12 fs-md-14 fw-700 m-0">{{ translate($product->main_category->name ?? '') }}</p> 
                </td>

                <td class="hide-md align-middle" data-label="Price Details">
                    <div class="border-width-3 border-left border-blue px-2 py-0 mb-1">
                        <span class="text-secondary fs-12 fw-400">{{ translate('Price') }}</span>
                        <p class="fs-16 fw-700 m-0">{{ single_price($product->unit_price) }}</p>
                    </div>
                    @if (discount_in_percentage($product) > 0)
                    <div class="border-width-3 border-left border-danger px-2 py-0">
                        <p class="fs-14 fw-400 m-0 py-5px">{{ translate('Discount') }}
                            <span class="text-danger fw-700 pl-1">{{ discount_in_percentage($product) }}%</span>
                        </p>
                    </div>
                    @endif
                </td>
                
                <td class="text-right align-middle">
                    <div class="dropdown float-right">
                        <button class="btn btn-light w-30px h-30px w-sm-35px h-sm-35px d-flex align-items-center justify-content-center action-toggle p-0" type="button"
                            data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="16" viewBox="0 0 3 16">
                                <g id="Group_38888" data-name="Group 38888" transform="translate(-1653 -342)">
                                    <circle id="Ellipse_1018" data-name="Ellipse 1018" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 348.5)" />
                                    <circle id="Ellipse_1019" data-name="Ellipse 1019" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 342)" />
                                    <circle id="Ellipse_1020" data-name="Ellipse 1020" cx="1.5" cy="1.5" r="1.5" transform="translate(1653 355)" />
                                </g>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                            <div class="table-options">
                                @if(!$product->draft)
                                <a href="{{ route('product', $product->slug) }}" target="_blank"
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 12 8.182">
                                            <path id="Path_45218" data-name="Path 45218" d="M46-793.455a2.367,2.367,0,0,0,1.739-.716,2.367,2.367,0,0,0,.716-1.739,2.367,2.367,0,0,0-.716-1.739A2.367,2.367,0,0,0,46-798.364a2.367,2.367,0,0,0-1.739.716,2.367,2.367,0,0,0-.716,1.739,2.367,2.367,0,0,0,.716,1.739A2.367,2.367,0,0,0,46-793.455Zm0-.982a1.42,1.42,0,0,1-1.043-.43,1.42,1.42,0,0,1-.43-1.043,1.42,1.42,0,0,1,.43-1.043,1.42,1.42,0,0,1,1.043-.43,1.42,1.42,0,0,1,1.043.43,1.42,1.42,0,0,1,.43,1.043,1.42,1.42,0,0,1-.43,1.043A1.42,1.42,0,0,1,46-794.436Zm0,2.618a6.315,6.315,0,0,1-3.627-1.111A6.318,6.318,0,0,1,40-795.909a6.318,6.318,0,0,1,2.373-2.98A6.315,6.315,0,0,1,46-800a6.315,6.315,0,0,1,3.627,1.111A6.318,6.318,0,0,1,52-795.909a6.318,6.318,0,0,1-2.373,2.98A6.315,6.315,0,0,1,46-791.818ZM46-795.909Zm0,3a5.206,5.206,0,0,0,2.83-.811,5.331,5.331,0,0,0,1.97-2.189,5.331,5.331,0,0,0-1.97-2.189,5.206,5.206,0,0,0-2.83-.811,5.206,5.206,0,0,0-2.83.811,5.331,5.331,0,0,0-1.97,2.189,5.331,5.331,0,0,0,1.97,2.189A5.206,5.206,0,0,0,46-792.909Z" transform="translate(-40 800)" fill="#414141" />
                                        </svg>
                                    </span>
                                    <span class="fs-14 text-secondary fw-500 pl-10px">{{ translate('View Product') }}</span>
                                </a>
                                @endif

                                @can('facebook_catalog_edit')
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue" 
                                    onclick="singlePushToCatalog({{ $product->id }})" title="{{ translate('Push to Catalog') }}">
                                    <span>
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 12 8.182">
                                            <path id="Path_45218" data-name="Path 45218" d="M46-793.455a2.367,2.367,0,0,0,1.739-.716,2.367,2.367,0,0,0,.716-1.739,2.367,2.367,0,0,0-.716-1.739A2.367,2.367,0,0,0,46-798.364a2.367,2.367,0,0,0-1.739.716,2.367,2.367,0,0,0-.716,1.739,2.367,2.367,0,0,0,.716,1.739A2.367,2.367,0,0,0,46-793.455Zm0-.982a1.42,1.42,0,0,1-1.043-.43,1.42,1.42,0,0,1-.43-1.043,1.42,1.42,0,0,1,.43-1.043A1.42,1.42,0,0,1 46-796a1.42 1.42 0 011.043-.43 1.42 1.42 0 011.043 .43 1.42 1.42 0 01 .43 1.043 1.42 1.42 0 01-.43 1.043A1.42 1.42 0 0146-794.436Zm0 2.618a6.315 6.315 0 01-3.627-1.111A6.318 6.318 0 0144-795909a6..318 6..318 00-2..373-2..98A6..315 6..315 00-3..627-111A6..315 6..315 00-3..627111A6..318 6..318 A6..318 A6..318 A6..315 6..315 00-2..373 2..98A6..315 6..315 00-3..627 1..111A6..315 6..315 00-3..627-1..111A6..318 6..318 00-2..373-2..98A6..315 6..315 00-3..627-1..111A6..315 6..315 00-3..627111A6..318 6..318 A6..318 A6..318 A5.206 5.206 00-2.83-.811A5.331 5.331 00-1.97-2.189A5.331 5.331 001.97-2.189A5.206 5.206 00-2.83-.811A5.206 5.206 00-2.83 .811A5.331 5.331 00-1.97 2.189A5.331 5.331 A5.206 A5.206Z" transform="translate(-40 800)" fill="#414141" />
                                        </svg>

                                    </span>
                                    <span class="fs-14 text-secondary fw-500 pl-10px">{{translate('Push')}}</span>
                                </a>
                                @endcan
                               
                                @can('facebook_catalog_delete')
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center px-20px py-10px hov-bg-light hov-text-blue text-nowrap" 
                                    onclick="removeFromCatalog({{ $product->id }})" title="{{ translate('Remove from Catalog') }}">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10.667" height="12" viewBox="0 0 10.667 12">
                                            <path id="Path_45219" data-name="Path 45219" d="M162-828a1.284,1.284,0,0,1-.942-.392,1.284,1.284,0,0,1-.392-.942V-838H160v-1.333h3.333V-840h4v.667h3.333V-838H170v8.667a1.284,1.284,0,0,1-.392.942,1.284,1.284,0,0,1-.942.392Zm6.667-10H162v8.667h6.667Zm-5.333,7.333h1.333v-6h-1.333Zm2.667,0h1.333v-6H166ZM162-838v0Z" transform="translate(-160 840)" fill="#dc3545" />
                                        </svg>
                                    </span>
                                    <span class="fs-14 text-danger fw-500 pl-5px">{{ translate('Remove') }}</span>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center py-5">
                    <div class="w-100">
                        <h5 class="fs-16 fw-bold text-gray">{{ translate('No Products found in Facebook Catalog!') }}</h5>
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