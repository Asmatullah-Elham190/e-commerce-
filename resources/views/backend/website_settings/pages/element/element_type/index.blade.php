@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('Element Details') }}</h1>
        </div>
    </div>

    <div class="row">
        <!-- Small table -->
        <div class="@if (auth()->user()->can('add_element_types')) col-lg-7 @else col-lg-12 @endif">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">
                        {{ $element->getTranslation('name') }}
                    </strong>
                </div>

                <div class="card-body">
                    <table class="table aiz-table mb-0"> 
                        <thead>
                            <tr>
                                <th width="5%;">#</th>
                                <th width="15%;">{{ translate('Type') }}</th>
                                <th width="60%;">{{translate('Image')}}</th>
                                <th width="20%;"class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_element_types as $key => $element_type)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $element_type->name }}
                                    </td>
                                    <td>
                                        @if($element_type->image_id != null)
                                         <img src="{{ uploaded_asset($element_type->image_id) }}" alt="{{translate('Image')}}" class="h-50px w-500px">
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @can('view_element_styles')
                                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                                href="{{ route('show-element-style', $element_type->id) }}"
                                                title="{{ translate('Element Styles') }}">
                                                <i class="las la-cog"></i>
                                            </a>
                                        @endcan
                                        @can('edit_element_type')
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('edit-element-type', ['id' => $element_type->id]) }}"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_element_type')
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('destroy-element-type', $element_type->id) }}"
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
        @can('add_element_types')
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Add New Element Type') }}</h5>
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
                        <form action="{{ route('store-element-type') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Element Name') }}</label>
                                <input type="hidden" name="element_id" value="{{ $element->id }}">
                                <input type="text" placeholder="{{ translate('Name') }}" name=""
                                    value="{{ $element->name }}"class="form-control" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Element Type') }}</label>
                                <input type="text" placeholder="{{ translate('Type') }}" id="type" name="type"
                                    class="form-control" required>
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
