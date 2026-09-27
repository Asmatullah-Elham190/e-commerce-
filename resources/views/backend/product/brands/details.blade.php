
<div class="px-30px">
    <div class="d-flex align-items-center justify-content-between border-sm-bottom pb-15px">
        <h5 class="m-0 fs-16 fw-700 text-dark">{{ $brand->getTranslation('name') }}</h5>
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
        <h6 class="m-0 fs-14 fw-700 text-dark pb-10px">{{translate('Brand Logo')}}</h6>
        <div
            class="brand-logo-container w-120px h-80px overflow-hidden border border-light bg-light rounded-2 d-flex align-items-center justify-content-center">
            <img src="{{ uploaded_asset($brand->logo) }}" class="img-fluid img-fit" alt="Logo">
        </div>
    </div>
    <div class="border-bottom-dashed  pt-15px pb-15px">
        <p class="m-0 fs-14 fw-700 text-dark">{{translate('Products of this Brand')}} <span>{{$brand->products_count}}</span>
        @if($brand->products_count > 0)
        <span>
            <a type="button" href="{{ route('products.all', ['brand_id' => $brand->id,'brand_name' => $brand->name]) }}"
                class="fs-12 fw-700 text-blue py-1 px-10px pb-0 rounded-pill border border-gray-300 bg-gray-100 ml-2 hov-bg-blue hov-text-white text-nowrap">{{ translate('View Products')}}</a>
        </span>
        @endif
    </p>
    </div>

    <div class="pt-15px pb-20px">
        <p class="m-0 fs-14 fw-700 text-dark">{{translate("Categories with this brand's products")}} <span>({{ $brand->products->pluck('categories')->flatten()->unique('id')->count() }})</span></p>
    </div>
    <div class="border-left border-gray-300 pl-15px brand-categories">
        @foreach ($brand->products->pluck('categories')->flatten()->unique('id') as $category)
        <p class="fs-14 fw-400 text-dark">{{ $category->name }}</p>
        @endforeach
    </div>

</div>