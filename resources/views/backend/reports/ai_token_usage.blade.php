@extends('backend.layouts.app')

@section('content')
<div class="row gutters-16">
    <!-- Header with Date Range -->
    <div class="col-12">
        <div class="tab-filter-bar">
            <form class="" id="sort_orders" action="" method="GET">
                <div class="card-header row border-0 align-items-center bg-white">
                    <div class="col-md-4 px-0">
                        <h3 class="fs-16 fw-600 mb-0">
                            {{ translate('Token Usage History') }}
                        </h3>
                    </div>

                    <div class="col-md-4 offset-md-4 inner-select input-group mt-2 mt-md-0 border border-light bg-light rounded-1 px-0">
                        <input type="text" class="aiz-date-range form-control form-control-sm border-0 px-2 bg-transparent" 
                            value="{{ $date ?? '' }}" name="date" placeholder="{{ translate('Filter by date') }}" 
                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Realtime Stats Cards -->
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-box h-150px mb-2rem overflow-hidden" style="background: #207AFC">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-white mb-1">{{ $totalRequests }}</h1>
                    <h3 class="fs-13 fw-600 text-white mb-0">{{ translate('Total Requests') }}</h3>
                </div>
                <div class="border-white border-1 rounded-5 rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <path id="Path_5" data-name="Path 5" d="M78.334,36.228A1.545,1.545,0,0,1,80.309,38.2L70.062,66.808a2.007,2.007,0,0,1-3.748.081l-3.7-9.1a1.825,1.825,0,0,1,.3-1.877l5.44-6.471a.879.879,0,0,0-1.238-1.238l-6.47,5.429a1.852,1.852,0,0,1-1.877.3l-9.124-3.713a2.007,2.007,0,0,1,.081-3.748Z" transform="translate(-48.4 -36.138)" fill="#fff"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="dashboard-box h-150px mb-2rem overflow-hidden" style="background: #9D87EC">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-white mb-1">{{ $totalTokens }}</h1>
                    <h3 class="fs-13 fw-600 text-white mb-0">{{ translate('Total Tokens') }}</h3>
                </div>
                <div class="border-white border-1 rounded-5 rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 23.684 42.632">
                        <path id="Path_4" data-name="Path 4" d="M131.842-797.368a13.019,13.019,0,0,1-8.4-2.753,8.329,8.329,0,0,1-3.437-6.72v-23.684a8.332,8.332,0,0,1,3.47-6.691A12.963,12.963,0,0,1,131.842-840a12.963,12.963,0,0,1,8.372,2.783,8.332,8.332,0,0,1,3.47,6.691v23.684a8.329,8.329,0,0,1-3.437,6.72A13.019,13.019,0,0,1,131.842-797.368Zm0-28.362a12.871,12.871,0,0,0,5.888-1.51q2.961-1.51,3.322-3.227-.362-1.717-3.306-3.257a12.639,12.639,0,0,0-5.9-1.539,12.507,12.507,0,0,0-5.872,1.51q-2.878,1.51-3.339,3.286.461,1.776,3.339,3.257A12.706,12.706,0,0,0,131.842-825.73Zm0,11.783a14.646,14.646,0,0,0,2.664-.237,14.047,14.047,0,0,0,2.451-.681,12.433,12.433,0,0,0,2.2-1.1,11.5,11.5,0,0,0,1.891-1.48v-7.105a11.5,11.5,0,0,1-1.891,1.48,12.433,12.433,0,0,1-2.2,1.1,14.047,14.047,0,0,1-2.451.681,14.646,14.646,0,0,1-2.664.237,15.191,15.191,0,0,1-2.7-.237,13.887,13.887,0,0,1-2.484-.681,11.924,11.924,0,0,1-2.187-1.1,10.68,10.68,0,0,1-1.842-1.48v7.105a10.681,10.681,0,0,0,1.842,1.48,11.924,11.924,0,0,0,2.188,1.1,13.887,13.887,0,0,0,2.484.681A15.191,15.191,0,0,0,131.842-813.947Zm0,11.842a11.965,11.965,0,0,0,3.076-.414,14.118,14.118,0,0,0,2.878-1.1,9.28,9.28,0,0,0,2.2-1.539,3.331,3.331,0,0,0,1.053-1.747v-5.8a11.506,11.506,0,0,1-1.891,1.48,12.433,12.433,0,0,1-2.2,1.1,14.037,14.037,0,0,1-2.451.681,14.646,14.646,0,0,1-2.664.237,15.191,15.191,0,0,1-2.7-.237,13.877,13.877,0,0,1-2.484-.681,11.924,11.924,0,0,1-2.187-1.1,10.686,10.686,0,0,1-1.842-1.48v5.862a3.222,3.222,0,0,0,1.036,1.717,9.458,9.458,0,0,0,2.188,1.51,14.027,14.027,0,0,0,2.895,1.1A12.153,12.153,0,0,0,131.842-802.105Z" transform="translate(-120 840)" fill="#fff"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="dashboard-box h-150px mb-2rem overflow-hidden" style="background: #EE4D5D">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-white mb-1">{{ $avgPerRequest }}</h1>
                    <h3 class="fs-13 fw-600 text-white mb-0">{{ translate('Avg Tokens/Request') }}</h3>
                </div>
                <div class="border-white border-1 rounded-5 rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                    <path id="Path_3" data-name="Path 3" d="M128.421-860.43v-12.85h5.053v12.85l-2.526-2.352Zm8.421,2.52V-880h5.053v17.05ZM120-852.115v-14.446h5.053v9.407ZM120-848l10.863-10.835,5.979,5.123,9.432-9.407h-2.695v-3.36H152v8.4h-3.368v-2.688l-11.621,11.591-5.979-5.123-6.316,6.3Z" transform="translate(-120 880)" fill="#fff"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- AI Usage Logs Table -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table mb-0" id="aiz-data-table">
                    <thead>
                        <tr>
                            <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Date & Time') }}</th>
                            <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('User') }}</th>
                            <th class="text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Model') }}</th>
                            <th class="hide-xs text-uppercase fs-10 fs-md-12 fw-700 text-secondary">{{ translate('Prompt Tokens') }}</th>
                            <th class="hide-sm text-uppercase fs-12 fw-700 text-secondary">{{ translate('Completion Tokens') }}</th>
                            <th class="hide-md text-uppercase fs-12 fw-700 text-secondary">{{ translate('Total Tokens') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($logs as $key => $log)
                            <tr class="data-row">
                                <td class="align-middle" data-label="Date & Time">
                                    <span class="fw-600">
                                        {{ date('d M Y, h:i A', strtotime($log->created_at)) }}
                                    </span>
                                </td>

                                <td class="align-middle" data-label="User">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-14 fw-600">
                                            {{ optional($log->user)->name ?? 'System' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="align-middle" data-label="Model">
                                    <span class="badge badge-inline badge-soft-info px-3 py-2">
                                        {{ $log->model }}
                                    </span>
                                </td>

                                <td class="hide-xs align-middle" data-label="Prompt Tokens">
                                    <span class="fw-600 text-primary">
                                        {{ number_format($log->prompt_tokens) }}
                                    </span>
                                </td>

                                <td class="hide-sm align-middle" data-label="Completion Tokens">
                                    <span class="fw-600 text-success">
                                        {{ number_format($log->completion_tokens) }}
                                    </span>
                                </td>

                                <td class="hide-md align-middle" data-label="Total Tokens">
                                    <div class="border-width-3 border-left border-primary px-2 py-0">
                                        <p class="fs-14 fw-700 m-0">
                                            {{ number_format($log->total_tokens) }}
                                        </p>
                                    </div>
                                </td>

                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="w-100">
                                        <i class="las la-robot fs-48 text-soft-light mb-3"></i>
                                        <h5 class="fs-16 fw-bold text-gray">{{ translate('No AI Usage Logs found!') }}</h5>
                                        <p class="text-muted">{{ translate('Start using AI features to see token usage here.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($logs->hasPages())
                <div class="aiz-pagination mt-3" id="pagination">
                    {{ $logs->appends(request()->input())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('.aiz-date-range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-Y') + ' to ' + picker.endDate.format('DD-MM-Y'));
            $('#sort_orders').submit();
        });

        $('.aiz-date-range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#sort_orders').submit();
        });
        $('#sort_orders').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true);
            $('#aiz-data-table tbody').html(`
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">{{ translate('Loading...') }}</p>
                    </td>
                </tr>
            `);
        });
    });
</script>
@endsection