@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Category Bulk Upload')}}</h5>
        </div>
        <div class="card-body">
            <div class="alert mb-2"
                style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                <p>1. {{translate('Download the skeleton file and fill it with proper data')}}.</p>
                <p>2. {{translate('You can download the example file to understand how the data must be filled')}}.</p>
                <p>3. {{translate('Parent Category and Atrribute should be in numerical id. The file includes separate sheets for Category, Attribute.')}}.</p>
                <p>4. {{translate('You can find all ids in the separate sheets inside the downloaded file')}}.</p>
                <p class="mb-0">5.
                    {{translate('Once you have downloaded and filled the skeleton file, upload it in the form below and submit')}}.
                </p>
            </div>
            <div class="">
                <a href="{{ route('bulk_category_demo_download') }}">
                    <button class="btn btn-info">
                        {{ translate('Download CSV with Reference Data')}}
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6"><strong>{{translate('Upload Category File')}}</strong></h5>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('category_bulk_upload') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <label class="custom-file-label">
                                <input type="file" name="bulk_file" class="custom-file-input" required>
                                <span class="custom-file-name">{{ translate('Choose File')}}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-info">{{translate('Upload CSV')}}</button>
                </div>
            </form>
        </div>
    </div>

    @can('category_bulk_export')
        <a href="{{route('category_bulk_export')}}" class="d-block">
            <div
                class="card border border-2 border-gray-200 card-no-shadow has-transition rounded-2 p-3 p-lg-4 overflow-hidden">
                <div class="d-flex justify-content-between align-items-start">
                    <img src="{{ static_asset('assets/img/product-management/bulk-export.svg') }}"
                        class="flex-shrink-0 w-50px h-50px" alt="Icon">
                    <div class="ml-3 flex-grow-1">
                        <h6 class="fs-16 fw-semibold mb-2 text-dark">
                            {{ translate('Bulk Export') }} </h6>
                        <span
                            class="fs-12 fw-400 text-gray">{{ translate('Download your category database in .csv .') }}</span>
                    </div>
                </div>
            </div>
        </a>
    @endcan

@endsection