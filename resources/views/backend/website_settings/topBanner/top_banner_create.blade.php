@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Create New Top Bar') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('top_banner.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">{{ translate('Text') }}</label>
                            <div class="col-xxl-9">
                                <textarea class="form-control" rows="5" name="text">{{ old('text') }}</textarea>
                            </div>
                        </div>

                        <!-- Link -->
                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">
                                {{translate('Link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="link" placeholder="{{ translate('Type your text here') }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
