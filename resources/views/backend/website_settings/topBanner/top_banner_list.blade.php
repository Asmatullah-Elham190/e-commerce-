@extends('backend.layouts.app')

@section('content')

<style>
    .custom-break {
        word-break: break-word;  
        white-space: normal;      
        overflow-wrap: anywhere;  
        max-width: 700px; 
    }
</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Top Bars') }}</h1>
            </div>
            @can('top_banner_create')
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('top_banner.create') }}" class="btn btn-circle btn-info">
                        <span>{{ translate('Add New Top Bar') }}</span>
                    </a>
                </div>
            @endcan
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_dynamic_popup" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('All Top Bars')}}</h5>
                </div>
    
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type text & Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th data-breakpoints="sm">{{ translate('Text') }}</th>
                            <th data-breakpoints="lg" width="30%">{{ translate('Link') }}</th>
                            <th data-breakpoints="lg">{{ translate('Status') }}</th>
                            @canany(['top_banner_edit', 'top_banner_delete'])
                                <th class="text-right">{{ translate('Actions') }}</th>
                            @endcanany    
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topBanners as $key => $topBanner)
                            <tr>
                                <td>{{ $key + 1 + ($topBanners->currentPage() - 1) * $topBanners->perPage() }}</td>
                                <td class="text-break custom-break">
                                    {{ $topBanner->getTranslation('text') }}
                                </td>
                                <td>{{ $topBanner->link}}</td>
                                <td>
                                    <label class="aiz-switch aiz-switch-primary mb-0">
		    						    <input
                                            onchange="trigger_alert(this)"
                                            value="{{ $topBanner->id }}" id="trigger_alert_{{ $topBanner->id }}" type="checkbox" @if($topBanner->status == 1) checked @endif
                                        >
		    					    	<span class="slider round"></span>
		    					    </label>
                                </td>
                                @canany(['top_banner_edit', 'top_banner_delete'])
                                    <td>
                                        <div class="dropdown float-right">
                                            <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                                <i class="las la-ellipsis-v list-icon"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                @can('top_banner_edit')
                                                    <a class="dropdown-item" href="{{route('top_banner.edit', ['id'=>$topBanner->id, 'lang'=>env('DEFAULT_LANGUAGE')])}}">
                                                        {{translate('Edit')}}
                                                    </a>
                                                @endcan
                                                @can('top_banner_delete')
                                                    <a class="dropdown-item confirm-delete" href="javascript:void(0)" data-href="{{route('top_banner.delete', $topBanner->id)}}">
                                                        {{translate('Delete')}}
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                @endcanany    
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $topBanners->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')

    <!-- confirm trigger Modal -->
    <div id="confirm-trigger-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-2 mb-2 fs-16 fw-700" id="confirm_text"></p>
                    <p class="fs-13" id="confirm_detail_text"></p>
                    <a href="javascript:void(0)" id="trigger_btn" data-value="" data-status="" data-clicked="" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px" onclick="update_top_banner_status()"></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function trigger_alert(el){

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            var id = el.value;
            var status = el.checked ? 1 : 0;
            var confirm_text = status == 1 ? "{{translate('Are you sure you want to trigger this Top Bar?')}}" : "{{translate('Are you sure you want to close this Top Bar?')}}";
            var confirm_detail_text = status == 1 ? "{{translate('Triggering this will show this Top Bar to all visiting customer immediately.')}}" : "{{translate('closing this will hide this Top Bar from all visiting customer immediately.')}}";
            var confirm_btn_text = status == 1 ? "{{translate('Trigger This Top Bar')}}" : "{{translate('Hide This Top Bar')}}";
            $('#trigger_btn').attr('data-value', id);
            $('#trigger_btn').attr('data-status', status);
            $('#trigger_btn').text(confirm_btn_text);
            $('#confirm_text').text(confirm_text);
            $('#confirm_detail_text').text(confirm_detail_text);
            $('#confirm-trigger-modal').modal('show');
        }

        function update_top_banner_status(el){

            $('#trigger_btn').attr('data-clicked', 1);
            $('#confirm-trigger-modal').modal('hide');
            var id = $('#trigger_btn').attr('data-value');
            var status = $('#trigger_btn').attr('data-status');
            $.post('{{ route('top-banner.update-status') }}', {_token:'{{ csrf_token() }}', id:id, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Top bar status updated successfully') }}');
                }
            });
        }

        $('#confirm-trigger-modal').on('hidden.bs.modal', function () {
            if ($('#trigger_btn').attr('data-clicked') == 1) {
                $('#trigger_btn').attr('data-clicked', '');
            }else{
                var id = $('#trigger_btn').attr('data-value');
                var status = $('#trigger_btn').attr('data-status') == 1 ? false : true;
                $('#trigger_alert_'+id).prop('checked', status);
            }
        })

        $(document).on("change", ".check-all", function() {
            $('.check-one:checkbox').prop('checked', this.checked);
        });

    </script>
@endsection