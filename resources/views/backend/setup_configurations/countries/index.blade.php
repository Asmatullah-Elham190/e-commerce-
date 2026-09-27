@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <form class="" id="" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('Countries') }}</h5>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort_country" name="sort_country" @isset($sort_country) value="{{ $sort_country }}" @endisset placeholder="{{ translate('Type country name') }}">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Code')}}</th>
                        <th>{{translate('Show/Hide')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $key => $country)
                        <tr>
                            <td>{{ ($key+1) + ($countries->currentPage() - 1)*$countries->perPage() }}</td>
                            <td>{{ $country->name }}</td>
                            <td>{{ $country->code }}</td>
                            <td>
                              <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="triggerConfirmation(this)" value="{{ $country->id }}" type="checkbox" <?php if($country->status == 1) echo "checked";?> >
                                <span class="slider round"></span>
                              </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $countries->appends(request()->input())->links() }}
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
    const isTurningOn = $(el).is(':checked'); // checkbox is now checked

    if (isTurningOn) {
        updateStatus(el);
    } else {
        pendingElement = el;

        $('#confirm-modal .modal-body p').text(`If you disable a Country, all associated States, Cities, and Areas under that country will also be automatically disabled`);
        $('#confirm-modal').modal('show');
    }
    }
    function confirmSettingChange() {
        updateStatus(pendingElement);
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

    function updateStatus(el) {

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
            $.post('{{ route('countries.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Country status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
