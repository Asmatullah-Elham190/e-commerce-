@extends('seller.layouts.app')

@section('panel_content')
<div class="page-content">
    <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 fw-700">{{ translate('Edit product') }}</h1>
            </div>
        </div>
    </div>

    <!-- Language Bar -->
    <div class="px-3 px-md-2rem add-product-page-content mb-4">
        <!-- Data type -->
        <input type="hidden" id="data_type" value="physical">

        <form class="" action="{{route('seller.products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="aizSubmitForm">
            @csrf
            <input name="_method" type="hidden" value="POST">
            <input type="hidden" name="id" value="{{ $product->id }}">
            <input type="hidden" name="lang" value="{{ $lang }}">
            <input type="hidden" name="added_by" value="seller">
            <!-- Language Bar -->
            <ul class="nav nav-tabs nav-fill language-bar mb-3">
                @foreach (get_all_active_language() as $key => $language)
                <li class="nav-item">
                    <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('seller.products.edit', ['id'=>$product->id, 'lang'=> $language->code] ) }}">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="row">
                <div class="col-xl-8">
                    <!-- Product Basic Information Start -->
                    <div class="border border-gray-300 rounded-2" id="basic-information">
                        <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                            <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                <h5 class="fs-16 fw-700">{{translate('Product Basic Information')}}</h5>
                                @if (get_setting('ai_activation') == 1)
                                    <a href="javascript:void(0)" class="d-flex align-items-center bg-transparent border-0" onclick="generateWithAI('basic-information')">
                                        <img src="{{ static_asset('assets/img/generate-icon.svg') }}" class="w-20px h-20px"
                                            alt="generate Icon">
                                        <h5 class="fs-16 fw-700 mb-0 ml-2" id="basic-information-generate">{{translate('Generate')}}</h5>
                                    </a>
                                @endif
                            </div>
                            <div class="row gutters-5" >
                                <!-- Product Name -->
                                <div class="col-12">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="product-name"
                                            class="col-from-label fs-14 fw-500">{{ translate('Product Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $product->getTranslation('name', $lang) }}" placeholder="{{ translate('Product Name') }}">
                                    </div>
                                </div>
                                @php
                                    function renderSingleCategoryOptions($categories, $selectedId = null, $level = 0) {
                                        foreach ($categories as $category) {
                                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                            $selected = ($selectedId == $category->id) ? 'selected' : '';
                                            echo "<option value=\"{$category->id}\" {$selected}>";
                                            echo $indent . e($category->getTranslation('name'));
                                            echo "</option>";
                                            
                                            if ($category->childrenCategories && $category->childrenCategories->count() > 0) {
                                                renderSingleCategoryOptions($category->childrenCategories, $selectedId, $level + 1);
                                            }
                                        }
                                    }
                                    function renderMultiCategoryOptions($categories, $selectedIds = [], $level = 0) {
                                        foreach ($categories as $category) {
                                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                            $selected = (is_array($selectedIds) && in_array($category->id, $selectedIds)) ? 'selected' : '';
                                            echo "<option value=\"{$category->id}\" {$selected}>";
                                            echo $indent . e($category->getTranslation('name'));
                                            echo "</option>";
                                            
                                            if ($category->childrenCategories && $category->childrenCategories->count() > 0) {
                                                renderMultiCategoryOptions($category->childrenCategories, $selectedIds, $level + 1);
                                            }
                                        }
                                    }
                                @endphp
                                <!-- Category -->
                                <!-- Single Select -->
                                <div class="col-md-6">
                                    <div class="form-group mb-2 mb-lg-3" id="category">
                                        <label for="category_id" class="col-from-label fs-14 fw-500">
                                            {{ translate('Select Main Category') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true">
                                            @php 
                                                $selectedCategoryId = isset($product) ? $product->category_id : old('category_id', null);
                                                renderSingleCategoryOptions($categories, $selectedCategoryId);
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <!-- Brand -->
                                <div class="col-md-6">
                                    <div class="form-group mb-2 mb-lg-3" id="brand">
                                        <label for="brand_id"
                                            class="col-from-label fs-14 fw-500">{{ translate('Brand') }}</label>
                                        <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                            data-live-search="true">
                                            <option value="">{{ translate('Select Brand') }}</option>
                                            @foreach (\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Product Basic Information End -->

                    <!-- Product Configuration Start -->
                    <div class="border border-gray-300 rounded-2 mt-4" id="product-configuration">
                        <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4" >
                            <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                <h5 class="fs-16 fw-700" >{{translate('Product Configuration')}}</h5>
                                @if (get_setting('ai_activation') == 1)
                                    <a href="javascript:void(0)" class="d-flex align-items-center bg-transparent border-0">
                                        <img src="{{ static_asset('assets/img/generate-icon.svg') }}" class="w-20px h-20px"
                                            alt="generate Icon">
                                        <h5 class="fs-16 fw-700 mb-0 ml-2" id="product-configuration-generate" onclick="generateWithAI('product-configuration')">{{translate('Generate')}}</h5>
                                    </a>
                                @endif
                            </div>
                            <div class="row gutters-5">
                                <!-- Multi Select -->
                                <div class="col-12">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="related-categories" class="col-from-label fs-14 fw-500">
                                            {{ translate('Related Categories') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control aiz-selectpicker" data-live-search="true" name="category_ids[]" id="category_ids" multiple>
                                            @php 
                                                $selectedCategoryIds = [];
                                                if(isset($product) && $product) {
                                                    $selectedCategoryIds = $product->categories()->pluck('category_id')->toArray();
                                                }
                                                if(old('category_ids')) {
                                                    $selectedCategoryIds = old('category_ids');
                                                }
                                                renderMultiCategoryOptions($categories, $selectedCategoryIds);
                                            @endphp
                                        </select>
                                    </div>
                                </div>
                                <!-- Unit -->
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group mb-2 mb-lg-3" id="unit">
                                        <label for="unit_id"
                                            class="col-from-label fs-14 fw-500">{{ translate('Unit') }}</label>
                                        <select class="form-control aiz-selectpicker" name="unit" id="unit_id"
                                            data-live-search="true">
                                            <option value="">{{ translate('Select Unit') }}</option>
                                            @foreach (\App\Models\Unit::all() as $unit)
                                                <option value="{{ $unit->id }}" @if($product->unit == $unit->id) selected @endif>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Weight -->
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="weight"
                                            class="col-from-label fs-14 fw-500">{{ translate('Weight (In Kg)') }}</label>
                                        <input type="number" class="form-control" name="weight" value="{{ $product->weight }}"  step="0.001" placeholder="0.00">
                                    </div>
                                </div>
                                <!--  Minimum Purchase Qty -->
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="min-qty"
                                            class="col-from-label fs-14 fw-500">{{ translate('Minimum Purchase Qty*') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" lang="en" class="form-control @error('min_qty') is-invalid @enderror" name="min_qty" value="{{ $product->min_qty }}" placeholder="1" min="1" step="1" integer-only required>
                                    </div>
                                </div>
                                <!-- Barcode -->
                                @if (addon_is_activated('pos_system'))
                                    <div class="col-12">
                                        <div class="form-group mb-2 mb-lg-3">
                                            <label for="barcode"
                                                class="col-from-label fs-14 fw-500">{{ translate('Barcode') }}</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="barcode" name="barcode"
                                                    value="{{ $product->barcode }}">
                                                <div class="input-group-prepend">
                                                    <button type="button" id="generateBarcodeBtn"
                                                        class="bg-dark text-white fs-14 fw-400 border-0 rounded-right px-3 w-100px" onclick="generateBarCode()">{{ translate('Generate') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <!-- Tags -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tags" class="col-from-label fs-14 fw-500">{{ translate('Tags') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control aiz-tag-input" id="tags"
                                            name="tags[]" value="{{ $product->tags }}" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                        <small
                                            class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Configuration End -->

                    <!-- Files & Media Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <div class="mb-3 pb-1 border-bottom-dashed">
                            <h5 class="fs-16 fw-700">{{translate('Files & Media') }}</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-5 col-xl-4">
                                <div class="form-group mb-0">
                                    <label class="col-from-label fs-14 fw-500">
                                        <span class="d-block">{{ translate('Add Thumbnail Image') }}</span>
                                        <span class="pb-2 d-block">
                                            <span
                                                class="fs-12 fw-400 text-gray pr-3">{{ translate('300px X 300px') }}</span>
                                            {{--<a href="#"
                                                class="fs-12 fw-400 text-blue text-decoration-underline">{{ translate('View Guidelines') }}</a>--}}
                                        </span>
                                    </label>
                                    <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files" value="{{ $product->thumbnail_img }}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-7 col-xl-8 mt-3 mt-md-0">
                                <!-- Gallery Images -->
                                <div class="form-group mb-2">
                                    <label class="col-from-label fs-14 fw-500">
                                        <span class="d-block">{{ translate('Add Gallery Images*') }}</span>
                                        <span class="pb-2 d-block">
                                            <span
                                                class="fs-12 fw-400 text-gray pr-3">{{ translate('800px X 800px ') }}</span>
                                            {{--<a href="#" class="fs-12 fw-400 text-blue text-decoration-underline">
                                                {{ translate('View Guidelines') }}</a>--}}
                                        </span>
                                    </label>
                                    <div class="img-upload-container">
                                        <div class="input-group file-upload-input border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                            data-toggle="aizuploader" data-type="image" data-multiple="true">
                                            <div
                                                class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                                <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                    class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                            </div>
                                            <input type="hidden" name="photos" class="selected-files" value="{{ $product->photos }}">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Youtube Video Link -->
                        <div class="form-group mb-2 mt-3">
                            <label
                                class="col-from-label fs-14 fw-500">{{ translate('Youtube video / shorts link') }}</label>
                            <small class="d-block text-muted fs-12 fw-400 mb-2">{{ translate('Paste a YouTube video or Shorts URL. The video will be displayed and playable on the product page.') }}</small>
                            <div class="video-provider-link">
                                @if (empty($product->video_link))
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="video_link[]" value=""
                                            placeholder="{{ translate('Paste url here') }}">
                                    </div>
                                </div>
                                @endif
                                @foreach ($product->video_link ?? [] as $index => $video_link)
                                <div class="row mb-2">
                                    <div class="col">
                                        @if($index == 0)
                                        @endif
                                        <input type="text" class="form-control" name="video_link[]" value="{{ $video_link }}"
                                            placeholder="{{ translate('Paste url here') }}">
                                    </div>
                                    @if($index > 0)
                                    <div class="col-auto d-flex justify-content-end">
                                        <button type="button" class="my-1 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                            <i class="las la-times"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group row d-flex justify-content-end mx-0" style="width: 100%">
                                <button type="button"
                                    class="btn btn-block border border-gray-400 border-dashed hov-bg-soft-secondary fs-14 rounded-2 d-flex align-items-center justify-content-center mt-3"
                                    data-toggle="add-more"
                                    data-content='<div class="row mb-2">
                                            <div class="col">
                                                <input type="text" class="form-control" name="video_link[]" value="" placeholder="{{ translate('Paste url here') }}">
                                            </div>
                                            <div class="col-auto d-flex justify-content-end">
                                                    <button type="button" class="my-1 pt-2 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                            </div>
                                        </div>'
                                    data-target=".video-provider-link">
                                    <i class="las la-plus mr-2"></i>
                                    {{ translate('Add Another') }}
                                </button>
                            </div>
                        </div>
                        <!-- Video/Thumbnail -->
                        <div class="row mt-4">
                            <!-- Video -->
                            <div class="col-md-6 col-lg-5 col-xl-4">
                                <div class="form-group mb-0">
                                    <label class="col-from-label fs-14 fw-500">
                                        <span class="d-block">{{ translate('Add Videos') }}</span>
                                        <span
                                            class="fs-12 fw-400 text-gray">{{ translate('Under 30s for better performance') }}</span>
                                    </label>
                                    <div class="mt-2 file-upload-input input-group  border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="video"  data-multiple="true">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="short_video" class="selected-files" value="{{ $product->short_video }}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <!-- Thumbnail -->
                            <div class="col-md-6 col-lg-5 col-xl-4">
                                <div class="form-group mb-0">
                                    <label class="col-from-label fs-14 fw-500">
                                        <span class="d-block">{{ translate('Add Video Thumbnail') }}</span>
                                        <span
                                            class="fs-12 fw-400 text-gray">{{ translate('Upload if you want to set video thumb manually') }}</span>
                                    </label>
                                    <div class="mt-2 file-upload-input input-group  border border-dashed border-gray-400 rounded-1 w-120px h-120px d-flex align-items-center justify-content-center"
                                        data-toggle="aizuploader" data-type="image">
                                        <div
                                            class="form-control p-0 border-0 d-flex align-items-center justify-content-center">
                                            <img src="{{ static_asset('assets/img/plus-lg.svg') }}"
                                                class="w-40px h-40px w-md-64px h-md-64px" alt="generate Icon">
                                        </div>
                                        <input type="hidden" name="short_video_thumbnail" class="selected-files" value="{{ $product->short_video_thumbnail }}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- PDF Specification -->
                        <div class="form-group mt-2">
                            <label class="col-form-label fs-14 fw-500">{{ translate('PDF Specification') }}</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="document">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="pdf" class="selected-files" value="{{ $product->pdf }}">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <!-- Files & Media End -->


                    <!-- Product Description Start -->
                    <div class="border border-gray-300 rounded-2 mt-4" id="product-description">
                        <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                            <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                <h5 class="fs-16 fw-700">{{translate('Product Description') }}</h5>
                                @if (get_setting('ai_activation') == 1)
                                    <a href="javascript:void(0)" onclick="generateWithAI('product-description')" class="d-flex align-items-center bg-transparent border-0">
                                        <img src="{{ static_asset('assets/img/generate-icon.svg') }}" class="w-20px h-20px"
                                            alt="generate Icon">
                                        <h5 class="fs-16 fw-700 text-blue mb-0 ml-2" id="product-description-generate">{{translate('Generate')}}</h5>
                                    </a>
                                @endif
                            </div>
                            <div class="">
                                <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Product Description End -->

                    <!-- SEO Meta Tags Start -->
                    <div class="border border-gray-300 rounded-2 mt-4" id="product-seo-meta-tag">
                        <div class="bg-white border-radius-10px px-3 px-lg-4 py-3 py-lg-4">
                            <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                                <h5 class="fs-16 fw-700">{{translate('SEO Meta Tags')}}</h5>
                                @if (get_setting('ai_activation') == 1)
                                    <a href="javascript:void(0)" onclick="generateWithAI('product-seo-meta-tag')" class="d-flex align-items-center bg-transparent border-0">
                                        <img src="{{ static_asset('assets/img/generate-icon.svg') }}" class="w-20px h-20px"
                                            alt="generate Icon">
                                        <h5 class="fs-16 fw-700 text-blue mb-0 ml-2" id="product-seo-meta-tag-generate">{{translate('Generate')}}</h5>
                                    </a>
                                @endif
                            </div>
                            <div class="row gutters-5">
                                <!-- Meta Title -->
                                <div class="col-12">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="meta_title"
                                            class="col-from-label fs-14 fw-500">{{ translate('Meta Title') }}</label>
                                        <input type="text" id="meta_title" class="form-control" name="meta_title"
                                            value="{{ $product->meta_title }}" placeholder="{{ translate('Meta Title') }}">
                                    </div>
                                </div>
                                <!-- Description -->
                                <div class="col-12">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="meta_description"
                                            class="col-from-label fs-14 fw-500">{{ translate('Description') }}</label>
                                        <textarea id="meta_description" name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
                                    </div>
                                </div>
                                <!-- Meta Image -->
                                <div class="col-12">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label class="col-from-label fs-14 fw-500">{{ translate('Meta Image') }}</label>
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                    {{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="meta_img" class="selected-files" value="{{ $product->meta_img }}">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                                <!-- Tags -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="meta_keywords" class="col-from-label fs-14 fw-500">{{ translate('Tags') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="text" id="meta_keywords" name="meta_keywords[]" class="form-control aiz-tag-input"
                                            value="{{ $product->meta_keywords }}" placeholder="{{ translate('Type and hit enter to add a meta Keyword') }}">
                                    </div>
                                </div>
                                
                                <!-- Slug -->
                                <div class="col-12">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="slug" class="col-from-label fs-14 fw-500">{{ translate('Slug') }}</label>
                                        <input type="text" id="slug" class="form-control" name="slug"
                                            value="{{ $product->slug }}" placeholder="{{ translate('Slug') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SEO Meta Tags End -->

                    <!-- Product Price & Stock Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <div class="mb-3 pb-1 d-flex align-items-center justify-content-between border-bottom-dashed">
                            <h5 class="fs-16 fw-700">{{translate('Product Price & Stock')}}</h5>
                        </div>
                        <!-- Product Variation Configuration -->
                        <h6 class="fs-14 fw-700">{{translate('Product Variation Configuration')}}</h6>
                        <!-- Colors -->
                        <div class="form-group row gutters-5">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple @if(count(json_decode($product->colors)) < 1) disabled @endif>
                                    @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                    <option value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                                        @if(in_array($color->code, json_decode($product->colors, true) ?? [])) selected @endif></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 align-content-center">
                                <label class="aiz-switch aiz-switch-blue mb-0">
                                    <input value="1" type="checkbox" name="colors_active" @if(count(json_decode($product->colors)) > 0) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <!-- Attributes -->
                        <div class="form-group row gutters-5">
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                            </div>
                            <div class="col-md-9">
                                <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" multiple data-placeholder="{{ translate('Choose Attributes') }}">
                                    @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                    <option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="chose_options_text" class="{{ count(json_decode($product->choice_options ?? '[]')) == 0 ? 'd-none' : '' }}">
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>
                        <!-- choice options -->
                        <div class="customer_choice_options mb-4" id="customer_choice_options">
                            @foreach (json_decode($product->choice_options) as $key => $choice_option)
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
                                    <input type="text" class="form-control" name="choice[]" value="{{ optional(\App\Models\Attribute::find($choice_option->attribute_id))->getTranslation('name') }}" placeholder="{{ translate('Choice Title') }}" readonly>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_{{ $choice_option->attribute_id }}[]" data-selected-text-format="count" multiple required>
                                        @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_option->attribute_id)->get() as $row)
                                        <option value="{{ $row->value }}" @if(in_array($row->value, $choice_option->values)) selected @endif>
                                            {{ $row->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row gutters-5 mt-3">
                            <div class="col-12">
                                <div class="form-group mb-2 mb-lg-3">
                                    <label for="unit-price"
                                        class="col-from-label fs-14 fw-500">{{ translate('Unit Price') }}
                                        <span>*</span></label>
                                    <input type="number" lang="en" min="0" value="{{ $product->unit_price }}" step="0.01" placeholder="{{ translate('Unit price') }}" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror">
                                </div>
                            </div>
                            <!-- Discount Date Range -->
                            <div class="col-md-6 ">
                                <div class="form-group mb-2 mb-lg-3" id="brand">
                                    <label for="discount-date-range"
                                        class="col-from-label fs-14 fw-500">{{ translate('Discount Date Range') }}</label>
                                    @php
                                        $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                                        $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                                    @endphp
                                    <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-past-disable="true"  data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
                                        @if($product->discount_start_date && $product->discount_end_date) value="{{ $start_date.' to '.$end_date }}" @endif>
                                </div>
                            </div>
                            <!-- Discount -->
                            <div class="col-md-6">
                                <div class="form-group mb-2 mb-lg-3">
                                    <label for="discount"
                                        class="col-from-label fs-14 fw-500">{{ translate('Discount') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="discount" name="discount" 
                                            value="{{ $product->discount }}" placeholder="{{ translate('0.00') }}">
                                        <select class="form-control aiz-selectpicker" name="discount_type" id="discount_type">
                                            <option value="amount" @if($product->discount_type == 'amount') selected @endif>{{ translate('Flat') }}</option>
                                            <option value="percent" @if($product->discount_type == 'percent') selected @endif>{{ translate('Percent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="show-hide-div">
                            <div class="row gutters-5 mt-3">
                                <!-- Stock -->
                                <div class="col-md-6 col-xl-4">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="stock"
                                            class="col-from-label fs-14 fw-500">{{ translate('Stock') }}</label>
                                        <input type="number" lang="en" value="{{ optional($product->stocks->first())->qty ?? 0 }}" step="1" integer-only name="current_stock" class="form-control" placeholder="10">
                                    </div>
                                </div>
                                <!-- SKU -->
                                <div class="col-md-6 col-xl-8">
                                    <div class="form-group mb-2 mb-lg-3">
                                        <label for="sku"
                                            class="col-from-label fs-14 fw-500">{{ translate('SKU') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="sku" name="sku" value="{{ optional($product->stocks->first())->sku ?? '' }}" placeholder="{{ translate('Product SKU') }}">
                                            <div class="input-group-prepend">
                                                <button type="button" id="generateSKUBtn"
                                                    class="bg-dark text-white fs-14 fw-400 border-0 rounded-right px-3 w-100px" onclick="generateSKU()" >{{ translate('Generate') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gutters-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-from-label">
                                        <span class=" fs-14 fw-500 pr-2">{{ translate('Product External Link') }}</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" placeholder="{{ translate('External link') }}" value="{{ $product->external_link }}" name="external_link" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 mt-md-0">
                                <div class="form-group mb-2 mb-lg-3">
                                    <label for="link-button-text" class="col-from-label fs-14 fw-500">{{ translate('Link Button Text') }}</label>
                                    <input type="text" placeholder="{{ translate('External link button text') }}" name="external_link_btn" value="{{ $product->external_link_btn }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Price & Stock End -->

                    <!-- Product Variants Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4 mb-4 mb-xl-0" id="variant-div-show-hide">
                        <h5 class="fs-16 fw-700">{{ translate('Product Variants') }}</h5>
                        <!-- sku combination -->
                        <div class="sku_combination" id="sku_combination">
                        </div>
                    </div>
                    <!-- Product Variants End -->
                </div>

                <div class="col-xl-4">

                    @if (addon_is_activated('refund_request'))
                        <!-- Refund Start -->
                        <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                            <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Refund') }}</h5>
                            <div>
                                <div class="d-flex align-items-center mt-3 mb-2">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="checkbox" name="refundable" value="1" onchange="isRefundable()"
                                            @if($product->refundable == 1) checked @endif>
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Refundable') }}</span>
                                </div>
                                <small id="refundable-note" class="text-muted d-none"></small>
                                <div class="mt-3">
                                    <label class="aiz-checkbox mb-0">
                                        <input type="checkbox" name="show_refund_notes" value="1" @if($product->show_refund_notes == 1) checked @endif>
                                        <span class="fs-14 fw-400">{{ translate('Show notes in refund section in product description page') }}</span>
                                        <span class="aiz-square-check" style="width: 20px; height: 20px;"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 refundable-notes">
                                <h6 class="fs-14 fw-700 mb-3">{{translate('Note (Add from preset)')}}</h6>
                                <input type="hidden" name="refund_note_id" id="refund_note_id" value="{{ $product->refund_note_id }}">
                                <div id="refund_note">
                                    @if($product->refundNote != null)
                                        <div class="border border-gray my-2 p-2">
                                            {{ $product->refundNote->getTranslation('description') ?? '' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="aiz-carousel gutters-10" data-items="2" data-xxl-items="2" data-xl-items="2"
                                    data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="2"
                                    data-arrows="false" data-dots="false" data-autoplay="false" data-infinite="true"
                                    data-center="false">
                                    @foreach(\App\Models\Note::where("note_type", 'refund')->orderBy('created_at','desc')->get() as $refund)
                                    <div class="carousel-box">
                                        <div class="refund-notes border border-2 border-gray-300 rounded-2 p-3 overflow-hidden has-transition {{ $refund->id == $product->refund_note_id ? 'border-primary' : '' }}"
                                            onclick="selectNote(this, 'refund_note_id', 'refund-notes', {{ $refund->id }})">
                                            <p class="fs-14 fw-400 m-0 text-truncate-3">
                                                {{ $refund->getTranslation('description') }}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if(get_setting('seller_can_add_note'))
                                    <div class="mt-4">
                                        <a href="#" id="add_note" data-note-type="refund"
                                            class="btn btn-block border border-gray-400 border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-2 d-flex align-items-center justify-content-center"
                                            onclick="">
                                            <i class="las la-plus"></i>
                                            <span class="ml-2">{{ translate('Add Refund Note') }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    <!-- Refund End -->
                    @endif

                    <!-- Warranty Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Warranty') }}</h5>
                        <div>
                            <div class="d-flex align-items-center mt-3 mb-2">
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input type="checkbox" name="has_warranty" onchange="warrantySelection()" @if($product->has_warranty == 1) checked @endif>
                                    <span></span>
                                </label>
                                <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{translate('Enable warranty for this product') }}</span>
                            </div>
                            <div class="form-group mb-0 warranty_selection_div @if($product->has_warranty != 1) d-none @endif">
                                <select class="form-control aiz-selectpicker mt-2" name="warranty_id" id="warranty_id" @if($product->has_warranty == 1) required @endif>
                                    <option value="">{{ translate('Select Warranty') }}</option>
                                    @foreach (\App\Models\Warranty::all() as $warranty)
                                        <option value="{{ $warranty->id }}" @if($product->warranty_id == $warranty->id) selected @endif>{{ $warranty->getTranslation('text') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label class="aiz-checkbox mb-0">
                                    <input type="checkbox" name="show_warranty_note" value="1" @if($product->show_warranty_note == 1) checked @endif>
                                    <span class="fs-14 fw-400">{{translate('Show notes in warranty section in product description page')}}</span>
                                    <span class="aiz-square-check" style="width: 20px; height: 20px;"></span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 warranty-notes">
                            <h6 class="fs-14 fw-700 mb-3">{{translate('Notes (Add from Preset)')}}</h6>
                            <input type="hidden" name="warranty_note_id" id="warranty_note_id" value="{{ $product->warranty_note_id }}">
                            <div id="warranty_note">
                                @if($product->warrantyNote != null)
                                    <div class="border border-gray my-2 p-2">
                                        {{ $product->warrantyNote->getTranslation('description') ?? '' }}
                                    </div>
                                @endif
                            </div>
                            <div class="aiz-carousel gutters-10" data-items="2" data-xxl-items="2" data-xl-items="2"
                                data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="2"
                                data-arrows="false" data-dots="false" data-autoplay="false" data-infinite="true"
                                data-center="false">
                                @foreach(\App\Models\Note::where("note_type", 'warranty')->orderBy('created_at','desc')->get() as $warranty)
                                <div class="carousel-box">
                                    <div class="single-warranty-notes border border-2 border-gray-300 rounded-2 p-3 overflow-hidden has-transition {{ $warranty->id == $product->warranty_note_id ? 'border-primary' : '' }}"
                                        onclick="selectNote(this, 'warranty_note_id', 'single-warranty-notes', {{ $warranty->id }})">
                                        <p class="fs-14 fw-400 m-0 text-truncate-3">
                                            {{ $warranty->getTranslation('description') }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                            @if(get_setting('seller_can_add_note'))
                                <div class="mt-4">
                                    <a href="#" id="add_note" data-note-type="warranty"
                                        class="btn btn-block border border-gray-400 border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-2 d-flex align-items-center justify-content-center"
                                        onclick="">
                                        <i class="las la-plus"></i>
                                        <span class="ml-2">{{ translate('Add Warranty Note') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Warranty End -->

                    <!-- Shipping Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Shipping') }}</h5>
                        <div>
                            <h6 class="fs-14 fw-700">{{ translate('Shipping Configuration') }}</h6>
                            @if (get_setting('shipping_type') == 'product_wise_shipping')
                                <div class="d-flex align-items-center mt-3 mb-2">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Free Shipping') }}</span>
                                </div>
                                <div class="d-flex align-items-center mt-3 mb-2">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Flat Rate') }}</span>
                                </div>
                                <!-- Shipping cost -->
                                <div class="flat_rate_shipping_div" @if($product->shipping_type != 'flat_rate') style="display: none" @endif>
                                    <div class="form-group mb-2">
                                        <label class="col-from-label">{{translate('Shipping cost')}}</label>
                                        <input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-3 mb-2">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="checkbox" name="is_quantity_multiplied" value="1" @if($product->is_quantity_multiplied == 1) checked @endif>
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Is Product Quantity Multiply') }}</span>
                                </div>
                            @else
                            <p>
                                {{ translate('Shipping configuration is maintained by Admin.') }}
                            </p>
                            @endif
                        </div>
                        <div class="mt-4">
                            <h6 class="fs-14 fw-700">{{ translate('Estimated Shipping Time') }}</h6>
                            <div class="form-group mb-2 mb-lg-3 mt-3">
                                <label class="col-from-label fs-14 fw-400">Shipping Days</label>
                                <div class="input-group mb-3">
                                    <input placeholder="Text area (7-15 days)"
                                        aria-label="Shipping Days" aria-describedby="shipping-days" type="text" class="form-control" name="est_shipping_days" value="{{ $product->est_shipping_days }}" >
                                    <div class="input-group-append">
                                        <span class="input-group-text fs-13">Days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="aiz-checkbox mb-0">
                                    <input type="checkbox" name="show_estimated_shipping_time" value="1" @if($product->show_estimated_shipping_time == 1) checked @endif>
                                    <span class="fs-14 fw-400">{{ translate('Show estimated shipping time in product description page') }}</span>
                                    <span class="aiz-square-check" style="width: 20px; height: 20px;"></span>
                                </label>
                            </div>
                            <div class="mt-2">
                                <label class="aiz-checkbox mb-0">
                                    <input type="checkbox" name="show_shipping_note" value="1"  @if($product->show_shipping_note == 1) checked @endif>
                                    <span class="fs-14 fw-400">{{translate('Show notes in shipping time section')}}</span>
                                    <span class="aiz-square-check" style="width: 20px; height: 20px;"></span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4 shipping-notes">
                            <h6 class="fs-14 fw-700 mb-3">{{translate('Notes (Add from Preset)')}}</h6>
                            <input type="hidden" name="shipping_note_id" id="shipping_note_id" value="{{ $product->shipping_note_id }}">
                            <div id="shipping_note">
                                @if($product->shippingNote != null)
                                    <div class="border border-gray my-2 p-2">
                                        {{ $product->shippingNote->getTranslation('description') ?? '' }}
                                    </div>
                                @endif
                            </div>
                            <div class="aiz-carousel gutters-10" data-items="2" data-xxl-items="2" data-xl-items="2"
                                data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="2"
                                data-arrows="false" data-dots="false" data-autoplay="false" data-infinite="true"
                                data-center="false">
                                @foreach(\App\Models\Note::where("note_type", 'shipping')->orderBy('created_at','desc')->get() as $shipping)
                                <div class="carousel-box">
                                    <div class="shp-notes border border-2 border-gray-300 rounded-2 p-3 overflow-hidden has-transition {{ $shipping->id == $product->shipping_note_id ? 'border-primary' : '' }}"
                                        onclick="selectNote(this, 'shipping_note_id', 'shp-notes', {{ $shipping->id }})">
                                        <p class="fs-14 fw-400 m-0 text-truncate-3">
                                            {{ $shipping->getTranslation('description') }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                            @if(get_setting('seller_can_add_note'))
                                <div class="mt-4">
                                    <a href="#" id="add_note" data-note-type="shipping"
                                        class="btn btn-block border border-gray-400 border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-2 d-flex align-items-center justify-content-center"
                                        onclick="">
                                        <i class="las la-plus"></i>
                                        <span class="ml-2">{{ translate('Add Shipping Note') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Shipping End -->


                    <!-- Cash On Delivery Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{ translate('Cash On Delivery') }}</h5>
                        @if (get_setting('cash_payment') == '1')
                            <div>
                                <div class="d-flex align-items-center mt-3 mb-2">
                                    <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                        <input type="checkbox" name="cash_on_delivery" value="1" @if($product->cash_on_delivery == 1) checked @endif>
                                        <span></span>
                                    </label>
                                    <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{ translate('Cash on delivery available') }}</span>
                                </div>
                                <div class="mt-3">
                                    <label class="aiz-checkbox mb-0">
                                        <input type="checkbox" name="show_delivery_notes" value="1" @if($product->show_delivery_notes == 1) checked @endif>
                                        <span class="fs-14 fw-400">{{ translate('Show notes in cash on delivery section') }}</span>
                                        <span class="aiz-square-check" style="width: 20px; height: 20px;"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 cash-on-delivery-notes">
                                <h6 class="fs-14 fw-700 mb-3">{{ translate('Notes (Add from Preset)') }}</h6>
                                <input type="hidden" name="delivery_note_id" id="delivery_note_id" value="{{ $product->delivery_note_id }}">
                                <div id="delivery_note">
                                    @if($product->codNote != null)
                                        <div class="border border-gray my-2 p-2">
                                            {{ $product->codNote->getTranslation('description') ?? '' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="aiz-carousel gutters-10" data-items="2" data-xxl-items="2" data-xl-items="2"
                                    data-lg-items="2" data-md-items="2" data-sm-items="2" data-xs-items="2"
                                    data-arrows="false" data-dots="false" data-autoplay="false" data-infinite="true"
                                    data-center="false">
                                    @foreach(\App\Models\Note::where("note_type", 'delivery')->orderBy('created_at','desc')->get() as $delivery)
                                    <div class="carousel-box">
                                        <div class="delivery-notes border border-2 border-gray-300 rounded-2 p-3 overflow-hidden has-transition {{ $delivery->id == $product->delivery_note_id ? 'border-primary' : '' }}"
                                            onclick="selectNote(this, 'delivery_note_id', 'delivery-notes', {{ $delivery->id }})">
                                            <p class="fs-14 fw-400 m-0 text-truncate-3">
                                                {{ $delivery->getTranslation('description') }}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                </div>
                                @if(get_setting('seller_can_add_note'))
                                    <div class="mt-4">
                                        <a href="#" id="add_note" data-note-type="delivery"
                                            class="btn btn-block border border-gray-400 border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-2 d-flex align-items-center justify-content-center"
                                            onclick="">
                                            <i class="las la-plus"></i>
                                            <span class="ml-2">{{ translate('Add Delivery Note') }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p>
                                {{ translate('Cash On Delivery activation is maintained by Admin.') }}
                            </p>
                        @endif
                    </div>
                    <!-- Cash On Delivery End -->

                    <!-- GST Rate -->
                    @if (addon_is_activated('gst_system'))
                        <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                            <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('HSN & GST')}}</h5>
                            <div class="form-group mb-0 mt-3">
                                <label class="col-from-label">{{translate('HSN Code')}}</label>
                                <input type="text" lang="en" placeholder="{{ translate('HSN Code') }}" name="hsn_code" value="{{ $product->hsn_code }}" class="form-control" required>
                            </div>
                            <div class="form-group mb-0 mt-3">
                                <label class="col-from-label">{{translate('GST Rate (%)')}}</label>
                                <input type="number" lang="en" min="0" value="{{ $product->gst_rate }}" step="0.01" placeholder="{{ translate('GST Rate') }}" name="gst_rate" class="form-control" required>
                            </div>
                        </div>
                    @else
                        <!-- VAT & Tax Start -->
                        <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                            <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Vat & TAX')}}</h5>
                            @foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                                @php
                                    $tax_amount = 0;
                                    $tax_type = '';
                                    foreach($tax->product_taxes as $row) {
                                        if($product->id == $row->product_id) {
                                            $tax_amount = $row->tax;
                                            $tax_type = $row->tax_type;
                                        }
                                    }
                                @endphp
                            <div class="form-group mb-0 mt-3">
                                <label class="col-from-label fs-14 fw-400"> {{$tax->name}}
                                        <input type="hidden" value="{{$tax->id}}" name="tax_id[]"></label>
                                <div class="input-group ">
                                    <input type="number" lang="en" min="0" value="{{ $tax_amount }}" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control">
                                    <div class="input-group-append w-140px">
                                        <select class="form-control aiz-selectpicker" name="tax_type[]">
                                            <option value="amount" @if($tax_type == 'amount') selected @endif>{{ translate('Flat') }}</option>
                                            <option value="percent" @if($tax_type == 'percent') selected @endif>{{ translate('Percentage') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                    <!-- VAT & Tax End -->


                    <!-- Stock & Order Display Settings Start -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Stock & Order Display Settings')}}
                        </h5>
                        <!-- Hide Stock -->
                        <div class="d-flex align-items-center">
                            <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                <input type="radio" name="stock_visibility_state" value="hide" @if($product->stock_visibility_state == 'hide') checked @endif>
                                <span></span>
                            </label>
                            <span class="fs-14 fw-700 d-block" style="margin-top: -6px">{{translate('Hide Stock Visibility State')}}</span>
                        </div>
                        <div class="mt-3">
                             <!-- Show Stock Quantity -->
                            <div class="d-flex align-items-center mt-3 mb-2">
                                <label class="aiz-switch-blue mb-0 pr-2">
                                    <input type="radio" name="stock_visibility_state" value="quantity" @if($product->stock_visibility_state == 'quantity') checked @endif>
                                    <span></span>
                                </label>
                                <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{translate('Show Stock Quantity')}}</span>
                            </div>
                            <!-- Show Stock With Text Only -->
                            <div class="d-flex align-items-center mt-3 mb-2">
                                <label class="aiz-switch-blue mb-0 pr-2">
                                    <input type="radio" name="stock_visibility_state" value="text" @if($product->stock_visibility_state == 'text') checked @endif>
                                    <span></span>
                                </label>
                                <span class="fs-14 fw-400 d-block" style="margin-top: -6px">{{translate('Show Stock With Text Only')}}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fs-14 fw-700 d-block" style="margin-top: -7px"> {{ translate('Low Stock Quantity Warning')}}</span>
                                <label class="aiz-switch aiz-switch-blue mb-0 pr-2">
                                    <input value="1" type="checkbox" name="low_stock_quantity_warning" @if($product->low_stock_quantity_warning == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <span class="fs-13 fw-400">{{ translate('Quantity')}}</span>
                                </div>
                                <div class="col-lg-6 mt-2 mt-lg-0">
                                    <input type="number" name="low_stock_quantity" value="{{ $product->low_stock_quantity }}" min="0" step="1" class="form-control" placeholder="10">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- Stock & Order Display Settings End -->

                    <!-- Frequently Bought Product -->
                    <div class="border border-gray-300 rounded-2 px-3 px-lg-4 py-3 py-lg-4 mt-4">
                        <h5 class="fs-16 fw-700 border-bottom-dashed mb-3 pb-2">{{translate('Frequently Bought')}}</h5>
                        <div class="w-100">
                            <div class="d-flex mb-4">
                                <div class="radio mar-btm mr-5 d-flex align-items-center">
                                    <input
                                        id="fq_bought_select_products"
                                        type="radio"
                                        name="frequently_bought_selection_type"
                                        value="product"
                                        onchange="fq_bought_product_selection_type()"
                                        @if($product->frequently_bought_selection_type == 'product') checked @endif
                                    >
                                    <label for="fq_bought_select_products" class="fs-14 fw-700 mb-0 ml-2">{{translate('Select Product')}}</label>
                                </div>
                                <div class="radio mar-btm mr-3 d-flex align-items-center">
                                    <input
                                        id="fq_bought_select_category"
                                        type="radio"
                                        name="frequently_bought_selection_type"
                                        value="category"
                                        onchange="fq_bought_product_selection_type()"
                                        @if($product->frequently_bought_selection_type == 'category') checked @endif
                                    >
                                    <label for="fq_bought_select_category" class="fs-14 fw-700 mb-0 ml-2">{{translate('Select Category')}}</label>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="fq_bought_select_product_div @if($product->frequently_bought_selection_type != 'product') d-none @endif">
                                        @php
                                            $fq_bought_products = $product->frequently_bought_products()->where('category_id', null)->get();
                                        @endphp
                                        <div id="selected-fq-bought-products">
                                            @if(count($fq_bought_products) > 0)
                                                <div class="table-responsive mb-4">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="opacity-50 pl-0">{{ translate('Product Thumb') }}</th>
                                                                <th class="opacity-50">{{ translate('Product Name') }}</th>
                                                                <th class="opacity-50">{{ translate('Category') }}</th>
                                                                <th class="opacity-50 text-right pr-0">{{ translate('Options') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($fq_bought_products as $fQBproduct)
                                                                <tr class="remove-parent">
                                                                    <input type="hidden" name="fq_bought_product_ids[]" value="{{ $fQBproduct->frequently_bought_product->id }}">
                                                                    <td class="w-150px pl-0" style="vertical-align: middle;">
                                                                        <p class="d-block size-48px">
                                                                            <img src="{{ uploaded_asset($fQBproduct->frequently_bought_product->thumbnail_img) }}" alt="{{ translate('Image')}}"
                                                                                class="h-100 img-fit lazyload" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                        </p>
                                                                    </td>
                                                                    <td style="vertical-align: middle;">
                                                                        <p class="d-block fs-13 fw-700 hov-text-primary mb-1 text-dark" title="{{ translate('Product Name') }}">
                                                                            {{ $fQBproduct->frequently_bought_product->getTranslation('name') }}
                                                                        </p>
                                                                    </td>
                                                                    <td style="vertical-align: middle;">{{ $fQBproduct->frequently_bought_product->main_category->name ?? translate('Category Not Found') }}</td>
                                                                    <td class="text-right pr-0" style="vertical-align: middle;">
                                                                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                                                                            <i class="las la-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                        <button
                                            type="button"
                                            class="btn btn-block border border-gray-400 border-dashed hov-bg-soft-secondary fs-14 rounded-2 d-flex align-items-center justify-content-center"
                                            onclick="showFqBoughtProductModal()">
                                            <i class="las la-plus"></i>
                                            <span class="ml-2">{{ translate('Add More') }}</span>
                                        </button>
                                    </div>
                                    {{-- Select Category for Frequently Bought Product --}}
                                    <div class="fq_bought_select_category_div @if($product->frequently_bought_selection_type != 'category') d-none @endif">
                                        @php
                                            $fq_bought_product_category_id = $product->frequently_bought_products()->where('category_id','!=', null)->first();
                                            $fqCategory = $fq_bought_product_category_id != null ? $fq_bought_product_category_id->category_id : null;
                                        @endphp
                                        <div class="form-group row">
                                            <label class="col-md-2 col-from-label">{{translate('Category')}} <span class="text-danger">*</span></label>
                                            <div class="col-md-10">
                                                <select
                                                    class="form-control aiz-selectpicker"
                                                    data-placeholder="{{ translate('Select a Category')}}"
                                                    name="fq_bought_product_category_id"
                                                    data-live-search="true"
                                                    data-selected="{{ $fqCategory }}"
                                                    @if($product->frequently_bought_selection_type == 'category') required @endif
                                                >
                                                    <option value="">{{ translate('Select a Category') }}</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @if($fqCategory == $category->id) selected @endif>{{ $category->getTranslation('name') }}</option>
                                                        @foreach ($category->childrenCategories as $childCategory)
                                                            @include('categories.child_category', ['child_category' => $childCategory])
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Frequently Bought Product End -->
                </div>    
            </div>
            <div class="mt-4 text-right">
                @if ($product->draft)
                    <button type="submit" name="button" value="unpublish" data-action="unpublish" class="mx-2 btn btn-light w-230px btn-md rounded-2 fs-14 fw-700 shadow-secondary border-soft-secondary action-btn">{{ translate('Save & Unpublish') }}</button>
                    <button type="submit" name="button" value="publish" data-action="publish" class="mx-2 btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success action-btn">{{ translate('Save & Publish') }}</button>
                    <button type="button" name="button" value="draft"  class="mx-2 btn btn-secondary w-230px btn-md rounded-2 fs-14 fw-700 shadow-secondary action-btn" id="saveDraftBtn">{{ translate('Save as Draft') }}</button>
                @else
                    <button type="submit" name="button"  class="mx-2 btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success action-btn">{{ translate('Update') }}</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@section('modal')
	<!-- Frequently Bought Product Select Modal -->
    @include('modals.product_select_modal')

    {{-- Note Modal --}}
    @include('modals.note_modal')

    <!-- loading Modal -->
    @include('modals.loading_modal')
@endsection

@section('script')
<!-- Treeview js -->
<script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>

<script type="text/javascript">

    // select main category in related categories
    $(document).ready(function() {
        $('#category_id').on('change', function() {
            let mainCatId = $(this).val();
            let relatedSelect = $('#category_ids');

            // enable all options first
            relatedSelect.find('option').prop('disabled', false);

            if(mainCatId) {
                relatedSelect.find('option[value="'+mainCatId+'"]').prop('selected', true).prop('disabled', true);
            }

            relatedSelect.selectpicker('refresh');
        });
        $('#category_id').trigger('change');
        
        show_hide_shipping_div();
        fq_bought_product_selection_type();

        if ($('input[name="colors_active"]').is(':checked')) {
            $('#add_color').removeClass('d-none');
        }
        
    });

    $('form').bind('submit', function (e) {
		if ( $(".action-btn").attr('attempted') == 'true' ) {
			//stop submitting the form because we have already clicked submit.
			e.preventDefault();
		}
		else {
			$(".action-btn").attr("attempted", 'true');
		}

        let mainCatId = $('#category_id').val();
        let relatedSelect = $('#category_ids');
        if (mainCatId) {
            relatedSelect.find('option[value="'+mainCatId+'"]').prop('disabled', false);
        }
    });

    $("[name=shipping_type]").on("change", function (){
        show_hide_shipping_div();
    });

    function show_hide_shipping_div() {
        $(".flat_rate_shipping_div").hide();

        if($("[name=shipping_type]:checked").val() == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }
    }


    function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('seller.products.add-more-choice-option') }}',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });


    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')){
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else{
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice",function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });
    
    $('input[name="unit_price"]').on('keyup', function() {
        update_sku();
    });

    $('input[name="name"]').on('keyup', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('seller.products.sku_combination_edit') }}',
           data:$('#aizSubmitForm').serialize(),
           success: function(data){
               $('#sku_combination').html(data);
               setTimeout(() => {
                        AIZ.uploader.previewGenerate();
                }, "2000");
               if (data.trim().length > 1) {
                   $('#show-hide-div').hide();
                   $('#variant-div-show-hide').show();
                   AIZ.plugins.sectionFooTable('#sku_combination');
               }
               else {
                    $('#show-hide-div').show();
                    $('#variant-div-show-hide').hide();
               }
           }
       });
    }

    AIZ.plugins.tagify();


    $(document).ready(function(){
        update_sku();

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

    $('#choice_attributes').on('change', function() {
        $('#customer_choice_options').html(null);
        $.each($("#choice_attributes option:selected"), function(){
            add_more_customer_choice_option($(this).val(), $(this).text());
        });

        update_sku();
    });

    function fq_bought_product_selection_type(){
        var productSelectionType = $("input[name='frequently_bought_selection_type']:checked").val();
        if(productSelectionType == 'product'){
            $('.fq_bought_select_product_div').removeClass('d-none');
            $('.fq_bought_select_category_div').addClass('d-none');
            $("select[name='fq_bought_product_category_id']").prop('required', false);
        }
        else if(productSelectionType == 'category'){
            $('.fq_bought_select_category_div').removeClass('d-none');
            $('.fq_bought_select_product_div').addClass('d-none');
            $("select[name='fq_bought_product_category_id']").prop('required', true);
        }
    }

    function showFqBoughtProductModal() {
        $('#fq-bought-product-select-modal').modal('show', {backdrop: 'static'});
    }

    function filterFqBoughtProduct() {
        var productID = $('input[name=id]').val();
        var searchKey = $('input[name=search_keyword]').val();
        var fqBroughCategory = $('select[name=fq_brough_category]').val();
        $.post('{{ route('seller.product.search') }}', { _token: AIZ.data.csrf, product_id: productID, search_key:searchKey, category:fqBroughCategory, product_type:"physical" }, function(data){
            $('#product-list').html(data);
            AIZ.plugins.sectionFooTable('#product-list');
        });
    }

    function addFqBoughtProduct() {
        var selectedProducts = [];
        $("input:checkbox[name=fq_bought_product_id]:checked").each(function() {
            selectedProducts.push($(this).val());
        });

        var fqBoughtProductIds = [];
        $("input[name='fq_bought_product_ids[]']").each(function() {
            fqBoughtProductIds.push($(this).val());
        });

        var productIds = selectedProducts.concat(fqBoughtProductIds.filter((item) => selectedProducts.indexOf(item) < 0))

        $.post('{{ route('seller.get-selected-products') }}', { _token: AIZ.data.csrf, product_ids:productIds}, function(data){
            $('#fq-bought-product-select-modal').modal('hide');
            $('#selected-fq-bought-products').html(data);
            AIZ.plugins.sectionFooTable('#selected-fq-bought-products');
        });
    }

    // Warranty
    function warrantySelection(){
        if($('input[name="has_warranty"]').is(':checked')) {
            $('.warranty_selection_div').removeClass('d-none');
            $('#warranty_id').attr('required', true);
        }
        else {
            $('.warranty_selection_div').addClass('d-none');
            $('#warranty_id').removeAttr('required');
        }
    }

    // Refundable
    function isRefundable() {
        const refundType = "{{ get_setting('refund_type') }}";
        const $refundable = $('input[name="refundable"]');
        const categoryId = $('#category_id').val();
        const $note = $('#refundable-note');

        $refundable.off('change.isRefundablLock');

        if (!refundType) {
            $refundable.prop('checked', false);
            $refundable.prop('disabled', true);
            $('.refund-block').addClass('d-none');
            $note.text('{{ translate("Refund system is not configured.") }}')
                .removeClass('d-none');
            return;
        }

        if (refundType !== 'category_based_refund') {
            $refundable.prop('disabled', false);
            $note.addClass('d-none');
            $('.refund-block').toggleClass('d-none', !$refundable.is(':checked'));
            return;
        }

        if (!$mainCategoryRadio.length) {
            $refundable.prop('checked', false);
            $refundable.prop('disabled', true);
            $('.refund-block').addClass('d-none');
            $note.text('{{ translate("Your refund type is category based. At first select the main category.") }}')
                .removeClass('d-none');
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ route("seller.products.check_refundable_category") }}',
            data: {
                _token: '{{ csrf_token() }}',
                category_id: categoryId
            },
            success: function (response) {
                if (response.status === 'success' && response.is_refundable) {
                    $refundable.prop('disabled', false);
                    $note.text('{{ translate("This product allows refunds.") }}')
                        .removeClass('d-none');
                    $refundable.on('change.isRefundableLock', function () {
                        if (!$refundable.is(':checked')) {
                            $('.refund-block').addClass('d-none');
                        } else {
                            $('.refund-block').removeClass('d-none');
                        }
                    });
                } else {
                    $refundable.prop('checked', false);
                    $refundable.prop('disabled', true);
                    $('.refund-block').addClass('d-none');
                    $note.text('{{ translate("Selected main category has no refund. Select a refundable category.") }}')
                        .removeClass('d-none');
                }
            },
            error: function () {
                $refundable.prop('checked', false);
                $refundable.prop('disabled', true);
                $('.refund-block').addClass('d-none');
                $note.text('{{ translate("Could not verify category refund status.") }}')
                    .removeClass('d-none');
            }
        });
    }
    
    function noteModal(noteType){
        $.post('{{ route('get_notes') }}',{_token:'{{ @csrf_token() }}', note_type: noteType}, function(data){
            $('#note_modal #note_modal_content').html(data);
            $('#note_modal').modal('show', {backdrop: 'static'});
        });
    }

    function addNote(noteId, noteType){
        var noteDescription = $('#note_description_'+ noteId).val();
        $('#'+noteType+'_note_id').val(noteId);
        $('#'+noteType+'_note').html(noteDescription);
        $('#'+noteType+'_note').addClass('border border-gray my-2 p-2');
        $('#note_modal').modal('hide');
    }

</script>

<script>
    $(document).ready(function () {
        var hash = document.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
            $('#tab').val(location.hash.substr(1));
        } else {
            $('.nav-tabs a[href="#general"]').tab('show');
            $('#tab').val('general');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
    });


</script>

<script type="text/javascript">
    $(document).ready(function () {
        warrantySelection();
        isRefundable();

        $(document).on('change', 'input[name="category_id"]', function () {
            isRefundable();
        });

        $('input[name="refundable"]').on('change', function () {
            if (!$('input[name="refundable"]').prop('disabled')) {
                $('.refund-block').toggleClass('d-none', !$(this).is(':checked'));
            }
        });
    });

    let draftProductId = {{ $product->id ?? 'null' }};

    $(document).ready(function() {
        function saveDraft() {
            let form = $('#aizSubmitForm')[0];
            let formData = new FormData(form);

            // Update Draft
            if (draftProductId) {
                formData.append('id', draftProductId);
            }
            let draftBtn = $('#saveDraftBtn');
            let draftBtnText = draftBtn.length ? draftBtn.text() : '';
            if (draftBtn.length) {
                draftBtn.prop('disabled', true).html('<i class="las la-spinner la-spin mr-2"></i> '+AIZ.local.saving_as_draft);
            }

            $.ajax({
                url: "{{ route('seller.products.store_as_draft') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        draftProductId = response.product_id;

                        // Update form action for future edits
                        $('#aizSubmitForm').attr('action', "{{ url('seller/products/update') }}/" + draftProductId);

                        if ($('#aizSubmitForm input[name="_method"]').length === 0) {
                            $('#aizSubmitForm').append('<input type="hidden" name="_method" value="POST">');
                        }
                        if (draftBtn.length) {
                         draftBtn.prop('disabled', false).html('<i class="las la-check-circle mr-2"></i>'+draftBtnText);
                        }
                        AIZ.plugins.notify('success',  `${response.message}`);
                    } else {
                        if (draftBtn.length) {
                            draftBtn.prop('disabled', false).html('<i class="las la-exclamation-circle text-danger mr-2"></i>'+draftBtnText);
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        Object.values(errors).forEach(function(fieldErrors) {
                            // fieldErrors.forEach(function(error) {
                            //     AIZ.plugins.notify('danger', error);
                            // });
                        if (draftBtn.length) {
                            draftBtn.prop('disabled', false).html('<i class="las la-exclamation-circle text-danger mr-2"></i>'+draftBtnText);
                        }
                        });
                    } else {
                        if (draftBtn.length) {
                            draftBtn.prop('disabled', false).html('<i class="las la-exclamation-circle text-danger mr-2"></i>'+draftBtnText);
                        }
                         //AIZ.plugins.notify('danger', AIZ.local.error_occured_while_processing);
                    }
                }
            });
        }

        // Auto-save on tab click
        $('a[data-toggle="tab"]').on('show.bs.tab', function() {
            var productName = $('input[name="name"]').val();
            if (productName && productName.trim() !== '') {
                var idDraft = {{ $product->draft ?? 0 }};
                if(idDraft == 1){
                    saveDraft();
                }
            }
        });

        $('#saveDraftBtn').on('click', function(e) {
            e.preventDefault();
            saveDraft();
        });

    });


    
    function generateBarCode() {
        const btn = document.getElementById('generateBarcodeBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i>';
        setTimeout(() => {
            const now = Date.now();
            const randomSuffix = Math.floor(Math.random() * 100);
            const barcode = Number(now.toString() + randomSuffix.toString().padStart(2, '0'));
            
            document.getElementById('barcode').value = barcode;
            btn.innerHTML = '<i class="las la-check-circle text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = "{{ translate('Regenerate') }}";
                btn.disabled = false;
            }, 1200);

        }, 300);
    }

    function generateSKU() {
        const btn = document.getElementById('generateSKUBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner la-spin"></i>';
        setTimeout(() => {
            const now = Date.now();
            const randomSuffix = Math.floor(Math.random() * 100);
            const barcode = Number(now.toString() + randomSuffix.toString().padStart(2, '0'));
            
            document.getElementById('sku').value = barcode;
            btn.innerHTML = '<i class="las la-check-circle text-success"></i>';
            setTimeout(() => {
                btn.innerHTML = "{{ translate('Regenerate') }}";
                btn.disabled = false;
            }, 1200);

        }, 300);
    }

    // AI Generation Function
    function generateWithAI(selectedSections = null) {
        let productName = ($('input[name="name"]').val() || '').trim();

        if (!productName) {
            AIZ.plugins.notify('warning', '{{ translate("Please enter a product name first") }}');
            return;
        }

        let textEl = $('#' + selectedSections + '-generate');
        let iconEl = $('#' + selectedSections).find('img').first();
        textEl.addClass('generate-gradient-text');
         $('#' + selectedSections).addClass('animated-gradient-border');
        iconEl.attr('src', '{{ static_asset("assets/img/generate-icon-2.svg") }}');
        textEl.html('{{ translate("Generating") }}...');


        let inputData = {};

        let $scope = selectedSections
            ? $('#' + selectedSections)
            : $('#aizSubmitForm');

        $scope.find('input[name]:not(:disabled), select[name]:not(:disabled), textarea[name]:not(:disabled)')
        .each(function () {
            let name = $(this).attr('name');
            if (!name) return;

            if (name.endsWith('[]')) {
                name = name.replace('[]', '');
            }

            if ($(this).is(':checkbox')) {
                inputData[name] = $(this).is(':checked') ? 1 : 0;
            } 
            else if ($(this).is(':radio')) {
                if ($(this).is(':checked')) {
                    inputData[name] = $(this).val();
                }
            } 
            else {
                inputData[name] = $(this).val();
            }
        });

        // alert(selectedSections);
        // alert(JSON.stringify(inputData));
        
        $.ajax({
            url: '{{ route("seller.products.generate-with-ai") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_name: productName,
                input_data: inputData,
                section: selectedSections
            },
            success: function(response) {
                textEl.text('{{ translate("Regenerate") }}');
                if (response.success) {
                    fillGeneratedData(response.data);
                    AIZ.plugins.notify('success', '{{ translate("Product content generated successfully!") }}');
                } else {
                    AIZ.plugins.notify('danger', response.message || '{{ translate("Failed to generate content") }}');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                var errorMessage = '{{ translate("Error connecting to AI service") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                AIZ.plugins.notify('danger', errorMessage);
            },
            complete: function() {
                textEl.removeClass('generate-gradient-text');
                $('#' + selectedSections).removeClass('animated-gradient-border');
                iconEl.attr('src', '{{ static_asset("assets/img/generate-icon.svg") }}');
                $('#generateWithAI').prop('disabled', false)
            }
        });
    }

    function fillGeneratedData(data) {
       // console.log('AI Generated Data:', data);
        
        $.each(data, function(fieldName, fieldValue) {
            if (!fieldValue && fieldValue !== 0) return; // Skip empty values if preferred

            // 1. find the element by name
            let $field = $(`[name="${fieldName}"], [name="${fieldName}[]"]`);

            // 2. Summernote (Description)
            if (fieldName === 'description') {
                let $editor = $('.aiz-text-editor');
                if ($editor.length && typeof $editor.summernote === 'function') {
                    $editor.summernote('code', fieldValue || '');
                    return; // Exit current iteration
                }
            }

            // for tagify
            if ($field.hasClass('aiz-tag-input') || fieldName.includes('tag') || fieldName === 'meta_keywords') {
                handleTagify($field, fieldValue);
                return;
            }

            // 4. Generic Dynamic filed (Barcode, HSN, Meta, Unit, Price, etc.)
            if ($field.length) {
                $field.val(fieldValue).trigger('input').trigger('change');
            }
        });
    }

    // Tagify Helper
    function handleTagify($input, fieldValue) {
        let tagifyTags = fieldValue;
        if (typeof fieldValue === 'string') {
            const trimmed = fieldValue.trim();
            if (trimmed.startsWith('[')) {
                try {
                    tagifyTags = JSON.parse(trimmed);
                } catch (e) {
                    console.error('Failed to parse Tag JSON:', trimmed, e);
                    tagifyTags = []; 
                }
            } else {
                tagifyTags = trimmed.split(',').map(t => ({ value: t.trim() })).filter(t => t.value);
            }
        } else if (Array.isArray(fieldValue) && typeof fieldValue[0] === 'string') {
            tagifyTags = fieldValue.map(tag => ({ value: tag }));
        }

        let tagifyInstance = $input[0].tagify || $input.data('tagify');
        if (tagifyInstance) {
            tagifyInstance.removeAllTags();
            tagifyInstance.addTags(tagifyTags);
        } else {
            let tagValues = tagifyTags.map(t => t.value || t).join(',');
            $input.val(tagValues).trigger('change');
        }
    }

    function selectNote(element, hiddenInputId, wrapperClass, noteId) {
        document.getElementById(hiddenInputId).value = noteId;
        document.querySelectorAll('.' + wrapperClass).forEach(function(el){
            el.classList.remove('border-primary');
        });
        element.classList.add('border-primary');
    }
</script>
@endsection
