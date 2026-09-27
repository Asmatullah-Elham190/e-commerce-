@extends('seller.layouts.app')

@section('panel_content')

    <div class="col-md-10 mx-auto">
        <div class="aiz-titlebar mt-2 mb-2">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3">{{ translate('Shop Design')}}
                        <span class="ml-3 fs-13">(<a href="{{ route('shop.visit', $shop->slug) }}"
                                class="btn btn-link btn-sm px-0" target="_blank">
                                {{ translate('Visit Shop')}}
                                <i class="las la-arrow-right"></i></a>)
                        </span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Banner Settings') }}</h5>
            </div>
            <div class="card-body">
                <form class="" action="{{ route('seller.shop.banner.update') }}" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @csrf

                    <div class="mb-3">
                        <label class="col-form-label">{{ translate('Top Banner') }} (1920x360)</label>
                        <div class="row p-3 p-md-4 mb-3 mb-md-2rem mx-0" style="border: 1px dashed #e4e5eb;">
                            <div class="col-md-6">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse')}}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="top_banner_image" value="{{ $shop->top_banner_image }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small
                                    class="text-muted">{{ translate('We had to limit height to maintian consistancy. In some device both side of the banner might be cropped for height limitation.') }}</small>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-3" placeholder="{{ translate('Top banner Link')}}"
                                    name="top_banner_link" value="{{ $shop->top_banner_link }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">{{ translate('Slider Banners') }} (1500x450)</label>
                        <div class="shop-slider-target">
                            @php $slider_images = $shop->slider_images; @endphp
                            @if ($slider_images != null)
                                @foreach (json_decode($slider_images, true) as $key => $value)
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse')}}
                                                            </div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="slider_images[]" class="selected-files"
                                                            value="{{ json_decode($slider_images, true)[$key] }}">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="slider_links[]"
                                                        value="{{ isset(json_decode($shop->slider_links, true)[$key]) ? json_decode($shop->slider_links, true)[$key] : '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="">
                            <button type="button"
                                class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                style="background: #fcfcfc;" data-toggle="add-more" data-content='
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="slider_images[]" class="selected-files" value="">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://" name="slider_links[]" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>' data-target=".shop-slider-target">
                                <i class="las la-2x text-success la-plus-circle"></i>
                                <span class="ml-2">{{ translate('Add New') }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">{{ translate('Banner Full width 1') }}</label>
                        <div class="shop-banner-full-width-1">
                            @php $banner_full_width_1_images = $shop->banner_full_width_1_images; @endphp
                            @if ($banner_full_width_1_images != null)
                                @foreach (json_decode($banner_full_width_1_images, true) as $key => $value)
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse')}}
                                                            </div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="banner_full_width_1_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode($banner_full_width_1_images, true)[$key] }}">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="banner_full_width_1_links[]"
                                                        value="{{ isset(json_decode($shop->banner_full_width_1_links, true)[$key]) ? json_decode($shop->banner_full_width_1_links, true)[$key] : '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="">
                            <button type="button"
                                class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                style="background: #fcfcfc;" data-toggle="add-more" data-content='
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="banner_full_width_1_images[]" class="selected-files" value="">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://" name="banner_full_width_1_links[]" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>' data-target=".shop-banner-full-width-1">
                                <i class="las la-2x text-success la-plus-circle"></i>
                                <span class="ml-2">{{ translate('Add New') }}</span>
                            </button>
                        </div>
                    </div>

                    @php $banners_half_width_images = $shop->banners_half_width_images; @endphp
                    <div class="mb-3">
                        <label class="col-form-label">{{ translate('Banners half width') }}
                            ({{ translate('2 Equal Banners') }})</label>
                        <div class="shop-banners-half-width">
                            @php $banner_full_width_1_images = $shop->banner_full_width_1_images; @endphp
                            @if ($banners_half_width_images != null)
                                @foreach (json_decode($banners_half_width_images, true) as $key => $value)
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse')}}
                                                            </div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="banners_half_width_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode($banners_half_width_images, true)[$key] }}">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="banners_half_width_links[]"
                                                        value="{{ isset(json_decode($shop->banners_half_width_links, true)[$key]) ? json_decode($shop->banners_half_width_links, true)[$key] : '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="">
                            <button type="button"
                                class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                style="background: #fcfcfc;" data-toggle="add-more" data-content='
                                        <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                            <div class="row gutters-5">

                                                <div class="col-md-5">
                                                    <div class="form-group mb-md-0">
                                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                            </div>
                                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                            <input type="hidden" name="banners_half_width_images[]" class="selected-files" value="">
                                                        </div>
                                                        <div class="file-preview box sm">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md">
                                                    <div class="form-group mb-md-0">
                                                        <input type="text" class="form-control" placeholder="http://" name="banners_half_width_links[]" value="">
                                                    </div>
                                                </div>

                                                <div class="col-md-auto">
                                                    <div class="form-group mb-md-0">
                                                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>' data-target=".shop-banners-half-width">
                                <i class="las la-2x text-success la-plus-circle"></i>
                                <span class="ml-2">{{ translate('Add New') }}</span>
                            </button>
                        </div>
                    </div>

                    @php $banner_full_width_2_images = $shop->banner_full_width_2_images; @endphp
                    <div class="mb-3">
                        <label class="col-form-label">{{ translate('Banner Full width 2') }}</label>
                        <div class="shop-banner-full-width-2">
                            @php $banner_full_width_1_images = $shop->banner_full_width_1_images; @endphp
                            @if ($banner_full_width_2_images != null)
                                @foreach (json_decode($banner_full_width_2_images, true) as $key => $value)
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                                {{ translate('Browse')}}
                                                            </div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="banner_full_width_2_images[]"
                                                            class="selected-files"
                                                            value="{{ json_decode($banner_full_width_2_images, true)[$key] }}">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://"
                                                        name="banner_full_width_2_links[]"
                                                        value="{{ isset(json_decode($shop->banner_full_width_2_links, true)[$key]) ? json_decode($shop->banner_full_width_2_links, true)[$key] : '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button"
                                                        class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                        data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="">
                            <button type="button"
                                class="btn btn-block border hov-bg-soft-secondary fs-14 rounded-0 d-flex align-items-center justify-content-center"
                                style="background: #fcfcfc;" data-toggle="add-more" data-content='
                                    <div class="p-3 p-md-4 mb-3 mb-md-2rem remove-parent" style="border: 1px dashed #e4e5eb;">
                                        <div class="row gutters-5">

                                            <div class="col-md-5">
                                                <div class="form-group mb-md-0">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="banner_full_width_2_images[]" class="selected-files" value="">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md">
                                                <div class="form-group mb-md-0">
                                                    <input type="text" class="form-control" placeholder="http://" name="banner_full_width_2_links[]" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="form-group mb-md-0">
                                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>' data-target=".shop-banner-full-width-2">
                                <i class="las la-2x text-success la-plus-circle"></i>
                                <span class="ml-2">{{ translate('Add New') }}</span>
                            </button>
                        </div>
                        <div class="form-group mb-0 text-right mt-4">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Navbar Background & Text Color') }}</h5>
            </div>
            <div class="card-body">
                <form class="" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    @csrf
                    <div class="form-box-content p-3">

                        <div class="form-group mb-4">
                            <label class="col-from-label fs-13">{{ translate('Navbar Background Color') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control aiz-color-input" name="navbar_bg_color" value="{{ $shop->navbar_bg_color ?? '#5B346C' }}" 
                                    placeholder="Ex: #e1e1e1">
                                <div class="input-group-append">
                                    <span class="input-group-text p-0">
                                        <input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $shop->navbar_bg_color ?? '#5B346C' }}">
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-from-label fs-13">{{ translate('Navbar Text Color') }}</label>

                            <div class="d-flex align-items-center">
                                
                                <label class="aiz-megabox d-block bg-white mb-0 mr-3" style="flex: 1;">
                                    <input type="radio"
                                        name="navbar_text_color"
                                        value="white"
                                        @checked(($shop->navbar_text_color ?? 'white') == 'white')>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Light') }}</span>
                                    </span>
                                </label>
                                
                                <label class="aiz-megabox d-block bg-white mb-0" style="flex: 1;">
                                    <input type="radio"
                                        name="navbar_text_color"
                                        value="black"
                                        @checked(($shop->navbar_text_color ?? 'white') == 'black')>
                                    <span class="d-flex align-items-center aiz-megabox-elem rounded-0"
                                        style="padding: 0.75rem 1.2rem;">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Dark') }}</span>
                                    </span>
                                </label>

                            </div>

                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
