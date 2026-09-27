<!--Top Section-->
<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h6 class="d-flex align-items-center fs-16 fw-700 text-dark mr-2 mt-0 mb-0 p-0">
                {{translate('Add Product In Promotional')}}
            </h6>
            
        </div>
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
    <div class="pb-5px">
        <!--Table-->
        <div class="row gutters-5 mt-3">
            <div class="col-md-6">
                <select class="form-control aiz-selectpicker" name="selected_Products_category" onchange="filterProductByCategory()" data-placeholder="{{ translate('Select a Category')}}" data-live-search="true">
                    <option value="">{{ translate('Choose Category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->getTranslation('name') }} </option>
                        @foreach ($category->childrenCategories as $childCategory)
                            @include('categories.child_category', ['child_category' => $childCategory])
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="search_product_keyword" onkeyup="filterProductByCategory()" placeholder="{{ translate('Search by Product Name') }}">
            </div>
        </div>
        <div class="mt-3" id="products-list"></div>
    </div>
</div>

<!--Offcanvas Footer-->
<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px " id="offcanvas-btn">
    <div class="d-flex justify-content-end footer-btn">
        <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel" onclick="closeRightcanvas()" >{{ translate('Cancel') }}</button>
        <button type="button" class="d-block fs-14 fw-700 py-10px save action-btn" id="multiselect-submit"> {{ translate('Add') }}</button>
    </div>
</div>