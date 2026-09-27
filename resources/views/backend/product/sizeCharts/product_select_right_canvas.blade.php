<div class="border-sm-bottom pb-15px px-30px">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h6 class="d-flex align-items-center fs-16 fw-700 text-dark mr-2 mt-0 mb-0 p-0" id="offcanvas-title">
                {{translate('Assign Catgeory/Product')}}
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

<div class="right-offcanvas-body position-absolute h-100 px-30px">
    <input type="hidden" id="assign_size_chart_id" value="">
    <div class="pb-5px">

        <div class="row gutters-5 mt-3" id="assign-filter-controls">
            <div class="col-md-6">
                <select class="form-control aiz-selectpicker" name="selected_products_category"
                    onchange="resetAssignStateAndFilter()" data-placeholder="{{ translate('Select a Category')}}"
                    data-live-search="true">
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
                <input type="text" class="form-control" name="search_product_keyword"
                    onkeyup="filterProductByCategory()" placeholder="{{ translate('Search by Product Name') }}">
            </div>
        </div>

        <div class="mt-2" id="assign-whole-category-row">
            <label class="aiz-checkbox mb-0 mr-4">
                <input type="checkbox" name="category_id" id="assign_whole_category"
                    onchange="toggleCategoryWideAssign(this)">
                <span
                    class="fs-14 fw-400">{{ translate('Selecting this category will assign this size chart to all products where it is the main category.') }}</span>
                <span class="aiz-square-check" style="width: 20px; height: 20px;"></span>
            </label>
        </div>

        <div class="mt-2 mb-2 d-none" id="view-category-label">
            <span class="fs-14 fw-500 text-dark">{{ translate('Assigned Category') }}: </span>
            <span class="fs-14 fw-600 text-blue" id="view-category-name"></span>
        </div>

        <div class="mt-3" id="products-list"></div>
    </div>
</div>

<div class="w-100 px-30px position-absolute bottom-0 bg-white right-offcavas-footer pt-20px pb-20px" id="offcanvas-btn">
    <div class="d-flex justify-content-end footer-btn">
        <button type="button" class="d-block fs-14 fw-700 py-10px mr-2 cancel"
            onclick="closeRightcanvas()">{{ translate('Cancel') }}</button>
        <button type="button" class="d-block fs-14 fw-700 py-10px save action-btn"
            id="multiselect-submit">{{ translate('Add') }}</button>
    </div>
</div>