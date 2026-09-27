@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Element Type Details') }}</h1>
        </div>
    </div>

    <div class="row">
        <!-- Small table -->
        <div class="@if (auth()->user()->can('add_element_styles')) col-lg-7 @else col-lg-12 @endif">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">
                        {{ $element_type->name }}
                    </strong>
                </div>

                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th width="5%;">#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{translate('Value')}}</th>
                                <th>{{ translate('Action') }}</th=>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_element_styles as $key => $element_style)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $element_style->name }}
                                    </td>
                                    <td>
                                        {{ $element_style->value }}
                                    </td>
                                    <td class="text-right">
                                        @can('delete_element_style')
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('destroy-element-style', $element_style->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @can('add_element_styles')
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Add New Element Style') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Error Meassages -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('store-element-style') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Element Type Name') }}</label>
                                <input type="hidden" name="element_type_id" value="{{ $element_type->id }}">
                                <input type="text" placeholder="{{ translate('Name') }}" name=""
                                    value="{{ $element_type->name }}" class="form-control" readonly>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">{{ translate('Top Header bg color') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="hidden" name="names[]" value="top_header_bg_color">
                                        <input type="text" class="form-control aiz-color-input" placeholder="#000000"
                                            name="top_header_bg_color" value="{{ $style_values['top_header_bg_color'] ?? '#000000' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text p-0">
                                                <input data-target="top_header_bg_color" value="{{ $style_values['top_header_bg_color'] ?? '#000000' }}"
                                                    class="aiz-color-picker border-0 size-40px" type="color">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Middle header background color -->
                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">{{ translate('Middle Header bg color') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="hidden" name="names[]" value="middle_header_bg_color">
                                        <input type="text" class="form-control aiz-color-input" placeholder="#000000"
                                            name="middle_header_bg_color" value="{{ $style_values['middle_header_bg_color'] ?? '#000000' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text p-0">
                                                <input data-target="middle_header_bg_color" value="{{ $style_values['middle_header_bg_color'] ?? '#000000' }}"
                                                    class="aiz-color-picker border-0 size-40px" type="color">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Bottom header background color -->
                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">{{ translate('Bottom Header bg color') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="hidden" name="names[]" value="bottom_header_bg_color">
                                        <input type="text" class="form-control aiz-color-input" placeholder="#000000"
                                            name="bottom_header_bg_color" value="{{ $style_values['bottom_header_bg_color'] ?? '#000000' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text p-0">
                                                <input data-target="bottom_header_bg_color" value="{{ $style_values['bottom_header_bg_color'] ?? '#000000' }}"
                                                    class="aiz-color-picker border-0 size-40px" type="color">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--top header text color -->
                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">{{ translate('Top Header Text color') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="hidden" name="names[]" value="top_header_text_color">
                                        <input type="text" class="form-control aiz-color-input" placeholder="#000000"
                                            name="top_header_text_color" value="{{ $style_values['top_header_text_color'] ?? '#000000' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text p-0">
                                                <input data-target="top_header_text_color" value="{{ $style_values['top_header_text_color'] ?? '#000000' }}"
                                                    class="aiz-color-picker border-0 size-40px" type="color">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--middle header text color -->
                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">{{ translate('Middle Header Text color') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="hidden" name="names[]" value="middle_header_text_color">
                                        <input type="text" class="form-control aiz-color-input" placeholder="#000000"
                                            name="middle_header_text_color" value="{{ $style_values['middle_header_text_color'] ?? '#000000' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text p-0">
                                                <input data-target="middle_header_text_color" value="{{ $style_values['middle_header_text_color'] ?? '#000000' }}"
                                                    class="aiz-color-picker border-0 size-40px" type="color">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--bottom header text color -->
                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">{{ translate('Bottom Header Text color') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="hidden" name="names[]" value="bottom_header_text_color">
                                        <input type="text" class="form-control aiz-color-input" placeholder="#000000"
                                            name="bottom_header_text_color" value="{{ $style_values['bottom_header_text_color'] ?? '#000000' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text p-0">
                                                <input data-target="bottom_header_text_color" value="{{ $style_values['bottom_header_text_color'] ?? '#000000' }}"
                                                    class="aiz-color-picker border-0 size-40px" type="color">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3 text-right">
                                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection