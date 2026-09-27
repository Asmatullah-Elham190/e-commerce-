@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All Elements') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="@if (auth()->user()->can('add_element')) col-lg-7 @else col-lg-12 @endif">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Elements') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($elements as $key => $element)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $element->getTranslation('name') }}</td>
                                    <td class="text-right w-140px">
                                        @can('view_element_types')
                                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                                href="{{ route('elements.show', $element->id) }}"
                                                title="{{ translate('Element Types') }}">
                                                <i class="las la-cog"></i>
                                            </a>
                                        @endcan
                                        @can('edit_element')
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('elements.edit', ['id' => $element->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete_element')
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('elements.destroy', $element->id) }}"
                                                title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $elements->links() }}
                    </div>
                </div>
            </div>
        </div>
        @can('add_element')
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Add New Element') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('elements.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ translate('Name') }}</label>
                                <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
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
