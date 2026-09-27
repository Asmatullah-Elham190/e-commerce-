@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col-md-12">
    			<h1 class="h3">{{translate('All States')}}</h1>
    		</div>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <form class="" id="sort_cities" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('States') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="sort_state" name="sort_state" @isset($sort_state) value="{{ $sort_state }}" @endisset placeholder="{{ translate('Type state name') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_country" name="sort_country">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach (\App\Models\Country::where('status', 1)->get() as $country)
                                    <option value="{{ $country->id }}" @if ($sort_country == $country->id) selected @endif {{$sort_country}}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th>{{translate('Name')}}</th>
                                <th>{{translate('Country')}}</th>
                                <th>{{translate('Show/Hide')}}</th>
                                <th class="text-right">{{translate('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($states as $key => $state)
                                <tr>
                                    <td>{{ ($key+1) + ($states->currentPage() - 1)*$states->perPage() }}</td>
                                    <td>{{ $state->name }}</td>
                                    <td>{{ $state->country->name }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="triggerConfirmation(this)" value="{{ $state->id }}" type="checkbox" <?php if($state->status == 1) echo "checked";?> >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('states.edit', $state->id) }}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $states->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
    		<div class="card">
    			<div class="card-header">
    				<h5 class="mb-0 h6">{{ translate('Add New State') }}</h5>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('states.store') }}" method="POST">
    					@csrf
    					<div class="form-group mb-3">
    						<label for="name">{{translate('Name')}}</label>
    						<input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>
    					</div>

                        <div class="form-group">
                            <label for="country">{{translate('Country')}}</label>
                            <select class="select2 form-control aiz-selectpicker" name="country_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                @foreach (\App\Models\Country::where('status', 1)->get() as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    					<div class="form-group mb-3 text-right">
    						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
    </div>

@endsection

@section('modal')
    <!-- confirm Modal -->
    <div id="confirm-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <path d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" fill="#ffc700" />
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700" id="confirmation-message"></p>
                    <div>
                        <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px" onclick="confirmSettingChange()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /.modal -->
@endsection

@section('script')
    <script type="text/javascript">

        let pendingElement = null;

        function triggerConfirmation(el) {
        const isTurningOn = $(el).is(':checked'); // checkbox checked

        if (isTurningOn) {
            update_status(el);
        } else {
            pendingElement = el;

            $('#confirm-modal .modal-body p').text(`If you disable a State, all associated Cities and Areas under that state will also be automatically disabled`);
            $('#confirm-modal').modal('show');
        }
        }
        function confirmSettingChange() {
            update_status(pendingElement);
            $('#confirm-modal').modal('hide');
            pendingElement = null;
        }
        // modal close if cancel
        $('#confirm-modal').on('hidden.bs.modal', function () {
            if (pendingElement) {
                $(pendingElement).prop('checked', !$(pendingElement).is(':checked'));
                pendingElement = null;
            }
        });

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
            $.post('{{ route('states.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('State status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
