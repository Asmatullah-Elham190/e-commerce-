@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Default Language') }}</h5>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label class="col-from-label">{{ translate('Default Language') }}</label>
                                </div>
                                <input type="hidden" name="types[]" value="DEFAULT_LANGUAGE">
                                <div class="col-lg-6">
                                    <select class="form-control aiz-selectpicker" name="DEFAULT_LANGUAGE" data-selected="{{ env('DEFAULT_LANGUAGE') }}">
                                        @foreach (\App\Models\Language::where('status', 1)->get() as $key => $language)
                                            <option value="{{ $language->code }}" @if(env('DEFAULT_LANGUAGE') == $language->code) selected @endif>
                                                {{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-info">{{translate('Save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
               <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Import App Translations') }}</h5>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('app-translations.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label class="col-from-label">{{ translate('English Trasnlation File') }}</label>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-file">
                                        <label class="custom-file-label">
                                            <input type="file" id="lang_file" name="lang_file"  class="custom-file-input" required>
                                            <span class="custom-file-name">{{ translate('Choose app_en.arb file') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-info">{{translate('Import')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="alert  alert-info mb-1 pb-0">
            <h6 class="text-info">Language Settings Instructions</h6>
            <ul>
                <li class="py-0">To create a new language, click <strong>Add New Language</strong>.</li>
                <li class="py-0">Enter the <strong>Language Name</strong>, <strong>Language Code</strong> (short form), and <strong>Flutter App Lang Code</strong>.</li>
                <li class="py-0">Click <strong>Save</strong>. The page will redirect to the language listing page.</li>
                <li class="py-0">You can select any language from the list as the <strong>System Default Language</strong> and click Save.</li>
                <li class="py-0">To import app translation files, select the file and click <strong>Import</strong>.</li>
            </ul>

            <h6 class="text-info">Translation Settings Instructions</h6>
            <ul class="pb-1 mb-3">
                <li class="py-0">Click the <strong>“Translation”</strong> option next to the language you want to edit.</li>
                <li class="py-0">On the Translation page, <strong>“Translate By Google”</strong> button to automatically translate your website content.</li>
                <li class="py-0">To sync translations for the mobile app, click <strong>“Sync Translation For App”</strong>.</li>
                <li class="py-0">To export the translation file, click <strong>“Export arb File”</strong>.</li>
                <li class="py-0">To manually update any translation value, edit the value fields and click <strong>Save</strong>.</li>
                <li class="py-0">To copy all keys into the value fields, click <strong>“Copy Translations”</strong>, then click Save.</li>
                <li class="py-0">Google Translate won't override your custom translations</li>
            </ul>
        </div>
    </div>
</div>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<div class="text-md-right">
			<a href="{{ route('languages.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Language')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Language')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Code')}}</th>
                    <th data-breakpoints="lg">{{translate('Flutter App Lang Code')}}</th>
                    <th data-breakpoints="lg">{{translate('RTL')}}</th>
                    <th>{{translate('Status')}}</th>
                    <th class="text-right" width="17%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($languages as $key => $language)
                    <tr>
                        <td>{{ ($key+1) + ($languages->currentPage() - 1)*$languages->perPage() }}</td>
                        <td>{{ $language->name }}</td>
                        <td>{{ $language->code }}</td>
                        <td>{{ $language->app_lang_code }}</td>
                        <td><label class="aiz-switch aiz-switch-success mb-0">
                            <input onchange="update_rtl_status(this)" value="{{ $language->id }}" type="checkbox" @if($language->rtl == 1) checked @endif>
                            <span class="slider round"></span></label>
                        </td>
                        <td><label class="aiz-switch aiz-switch-success mb-0">
                            <input onchange="update_status(this)" value="{{ $language->id }}" type="checkbox" @if($language->status == 1) checked @endif>
                            <span class="slider round"></span></label>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('languages.show', $language->id)}}" title="{{ translate('Translation') }}">
                                <i class="las la-language"></i>
                            </a>
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('languages.edit', $language->id)}}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            @if($language->code != 'en')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('languages.destroy', $language->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $languages->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function update_rtl_status(el){

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('languages.update_rtl_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function update_status(el){

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('languages.update-status') }}', {
                    _token : '{{ csrf_token() }}',
                    id : el.value,
                    status : status
                }, function(data) {
                if(data == 1) {
                    location.reload();
                }
                else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
