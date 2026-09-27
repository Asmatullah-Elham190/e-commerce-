@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Edit Top Bar Information') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-fill language-bar">
                        @foreach (get_all_active_language() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
                                    href="{{ route('top_banner.edit', ['id' => $topBanner->id, 'lang' => $language->code]) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}" height="11"
                                        class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <form class="form-horizontal" action="{{ route('top_banner.update', $topBanner->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">{{ translate('Text') }}</label>
                            <div class="col-xxl-9">
                                <textarea class="form-control" rows="5" name="text">{{ $topBanner->text }}</textarea>
                            </div>
                        </div>

                        <!-- Link -->
                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">
                                {{translate('Link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="{{ $topBanner->link }}" name="link" placeholder="{{ translate('Type your text here') }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
