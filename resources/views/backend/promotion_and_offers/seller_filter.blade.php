{{-- seller_filter --}}
<div class="card-body">
    <table class="table mb-0" id="aiz-data-table">
         <thead>
            <tr>
                <th class="hide-lg">#</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Thumb') }}</th>
                <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary ml-1 ml-lg-0">{{ translate('Name / Brand') }}</th>

                <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Owner / Category') }}</th>
                <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Ratings') }}</th>
                <th class="hide-md text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Price Details') }}
                </th>
                <th class="hide-xxl text-uppercase fs-12 fw-700 text-secondary"> {{ translate('Marketing') }}</th>
         
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $key => $product)
            <tr class="data-row">
                
                <td class="align-middle w-40px">
                    <div>
                        <button type="button"
                            class="toggle-plus-minus-btn border-0 bg-blue fs-14 fw-500 text-white p-0 align-items-center justify-content-center">+</button>
                    </div>
                    <div class="form-group d-inline-block">{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</div>
                </td>
               
                <td data-label="Thumb" class="w-60px w-md-80px w-md-100px">
                    <div class="w-40px h-40px w-sm-60px h-sm-60px w-md-80px h-md-80px rounded-2 overflow-hidden border">
                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image" class="img-fit">
                    </div>
                </td>

                <td data-label="Name" class="w-lg-300px w-300px w-md-300px w-md-300px">
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
                <td class="hide-xs w-300px w-md-300px w-md-300px" data-label="Owner Category">
                     @php $shop = optional(optional($product->user)->shop); @endphp
                    <a href="{{ $shop->id ? route('sellers.profile', encrypt($shop->id)) : '#' }}" class="fs-12 fs-md-14 fw-700 d-block">
                         {{ $shop->name ?? translate('Inhouse') }}
                    </a>
                    <span class="fs-12 fw-200 text-secondary d-block pt-1">{{ translate('Main Category') }}</span>
                    <p class="fs-12 fs-md-14 fw-700 m-0">{{translate($product->main_category->name ?? '')}}</p> 
                </td>
                <td class="hide-sm w-300px w-md-300px w-md-300px" data-label="Ratings">
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

                <td class="hide-md align-middle w-200px w-md-200px w-md-200px" data-label="Price Details">
                    <div class="border-width-3 border-left border-blue px-2 py-0 mb-1">
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
                
            
                <td class="hide-xxl align-middle" data-label="Marketing">
                    @php $participateEntry = $participateProducts->get($product->id); @endphp
                    <div class="row px-2 py-0 mb-1">
                        @if ($participateEntry && $participateEntry->todays_deal)
                            <span class="badge badge-inline bg-danger text-white mr-1">{{ translate("Today's Deal") }}</span>
                        @endif
                        @if ($participateEntry && $participateEntry->featured)
                            <span class="badge badge-inline bg-danger text-white mr-1">{{ translate("Featured") }}</span>
                        @endif
                        @if ($participateEntry && $participateEntry->flash_sale)
                            <span class="badge badge-inline bg-danger text-white">{{ translate('Flash Sale') }} ({{ $participateEntry->discount }}%)</span>
                        @endif
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