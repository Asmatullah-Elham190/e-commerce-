@extends('backend.layouts.app')
@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Create New Package')}}</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('customer_packages.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="col-from-label" for="name">
                                {{translate('Package Name')}}
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name"
                                class="form-control" required>

                        </div>
                        <div class="form-group">
                            <label class="col-from-label" for="name">
                                {{translate('Amount')}}
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Amount')}}"
                                id="amount" name="amount" class="form-control" required>

                        </div>
                        <div class="form-group">
                            <label class="col-from-label" for="name">
                                {{translate('Product Upload')}}
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number" lang="en" min="0" step="1" placeholder="{{translate('Product Upload')}}"
                                id="product_upload" name="product_upload" class="form-control" required>

                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="signinSrEmail">
                                {{translate('Package logo')}}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="logo" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>

                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection