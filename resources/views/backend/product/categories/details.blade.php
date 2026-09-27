<div class="px-30px ">
    <div class="d-flex align-items-center justify-content-between border-sm-bottom pb-15px">
        <h5 class="m-0 fs-16 fw-700 text-dark">{{ $category->getTranslation('name') }}</h5>
        <button onclick="closeOffcanvas()" class="border-0 bg-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                <path id="Path_45226" data-name="Path 45226"
                    d="M228.588-716.31l-.9-.9,7.1-7.1-7.1-7.1.9-.9,7.1,7.1,7.1-7.1.9.9-7.1,7.1,7.1,7.1-.9.9-7.1-7.1Z"
                    transform="translate(-227.69 732.31)" fill="#a5a5b8" />
            </svg>
        </button>
    </div>
</div>


<!--Offcanvas Body-->
<div class="right-offcanvas-body position-absolute h-100 px-30px">
    {{-- <p id="offcanvasContent" class="text-muted"></p> --}}

    <div class="pt-15px pb-15px border-bottom-dashed">
        <!--Logo-->
         @if ($category->icon != null)
        <div>
            <h6 class="m-0 fs-14 fw-700 text-dark pb-10px">{{translate('Logo')}}</h6>
            <div
                class="brand-logo-container w-40px h-40px overflow-hidden border border-gray-300 rounded-2 d-flex align-items-center justify-content-center">
                <img src="{{ uploaded_asset($category->icon) }}" class="img-fluid w-20px h-20px"
                    alt="Logo">
            </div>
        </div>
        @endif
        <!--Banner - Cover page -->
        <div class="pt-20px">
            <h6 class="m-0 fs-14 fw-700 text-dark pb-10px">{{translate('Banner - Cover Image')}}</h6>
            <div class="d-flex">
                <div
                    class="w-120px h-120px overflow-hidden border border-gray-300 rounded-2 d-flex align-items-center justify-content-center">
                    <img src="{{ isset($category->banner) ? uploaded_asset($category->banner) : static_asset('assets/img/placeholder.jpg') }}"
                        class="img-fluid w-130px h-130px object-fit-cover object-position-center" alt="Banner">
                </div>
                <div
                    class="w-120px h-120px overflow-hidden border border-gray-300  rounded-2 d-flex align-items-center justify-content-center ml-2rem">
                    <img src="{{ isset($category->cover_image) ? uploaded_asset($category->cover_image) : static_asset('assets/img/placeholder.jpg') }}"
                        class="img-fluid w-130px h-130px object-fit-cover object-position-center" alt="Cover">
                </div>

            </div>
        </div>
    </div>
    <div class="border-bottom-dashed  pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark pb-5px">{{translate('Products in the Category')}}</p>
        <span class="fs-14 fw-400 text-dark">{{ $category->product_categories_count }} </span>
        @if($category->product_categories_count>0)
        <span>
            <a type="button" href="{{ route('products.all', ['category_id' => $category->id,'category_name' => $category->name]) }}"
                class="fs-12 fw-700 text-blue py-1 px-10px pb-0 rounded-pill border border-gray-300 bg-gray-100 ml-2 hov-bg-blue hov-text-white text-nowrap">{{ translate('View Products')}}</a>
        </span>
        @endif
    </div>
    <div class="border-bottom-dashed  pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark pb-5px">{{translate('Parent Category')}}</p>
        @php
            $parent = \App\Models\Category::where(
                'id',
                $category->parent_id,
            )->first();
        @endphp
        <span class="fs-14 fw-400 text-dark">{{ $parent != null ? $parent->getTranslation('name') : '—' }}</span>
    </div>
    <div class="border-bottom-dashed  pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark pb-5px">{{translate('Order Level')}}</p>
        <span class="fs-14 fw-400 text-dark">{{ $category->order_level }}</span>
    </div>
    <div class="border-bottom-dashed  pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark pb-5px">{{translate('Level')}}</p>
        <span class="fs-14 fw-400 text-dark">{{ $category->level }}</span>
    </div>
    @if (get_setting('seller_commission_type') == 'category_based')
    <div class="border-bottom-dashed  pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark pb-5px">Commission of Seller's</p>
        <span class="fs-14 fw-400 text-dark">{{ $category->commision_rate }}%</span>
    </div>
    @endif

    <div class="pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark pb-10px">{{translate('Category Based Discount')}}</p>
        @php
        $end_date   = $category->discount_end_date ? date('d-m-Y H:i:s', $category->discount_end_date) : null;
        @endphp
        @if($category->discount > 0 && ($end_date == null || strtotime($end_date) > strtotime(date('d-m-Y H:i:s'))))
        
        <span class="fs-14 fw-400 text-dark">{{ $category->discount }}%</span>
        @else
        <button type="button"
            class="border border-gray-400 rounded-4 fs-14 fw-500 text-blue bg-gray-100 py-10px px-30px hov-bg-blue hov-text-white">{{translate('Not Applied')}}</button>
        @endif
    </div>
</div>