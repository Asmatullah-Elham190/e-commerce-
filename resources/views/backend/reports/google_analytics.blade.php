@extends('backend.layouts.app')

@section('content')
<div class="row gutters-16">
    <!-- Header with Date Range -->
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="fs-16 fw-600 mb-0">
                 {{ translate('Google Analytics Dashboard') }}
            </h3>
            <div class="btn-group" role="group">
                <a href="{{ route('google-analytics-test.result', ['days' => 7]) }}" class="btn {{ $days == 7 ? 'btn-primary' : 'btn-soft-primary' }}">{{translate('7 Days')}}</a>
                <a href="{{ route('google-analytics-test.result', ['days' => 30]) }}" class="btn {{ $days == 30 ? 'btn-primary' : 'btn-soft-primary' }}">{{translate('30 Days')}}</a>
                <a href="{{ route('google-analytics-test.result', ['days' => 90]) }}" class="btn {{ $days == 90 ? 'btn-primary' : 'btn-soft-primary' }}">{{translate('90 Days')}}</a>
            </div>
        </div>
    </div>

    <!-- Realtime Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-box bg-soft-primary h-150px mb-2rem overflow-hidden">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-primary mb-1">{{ $realtimeUsers }}</h1>
                    <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Active Users Now') }}</h3>
                </div>
                <div class="bg-primary rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="dashboard-box bg-soft-success h-150px mb-2rem overflow-hidden">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-success mb-1">{{ $totals['activeUsers'] ?? 0 }}</h1>
                    <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Total Active Users') }}</h3>
                </div>
                <div class="bg-success rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="dashboard-box bg-soft-warning h-150px mb-2rem overflow-hidden">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-warning mb-1">{{ $totals['screenPageViews'] ?? 0 }}</h1>
                    <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Page Views') }}</h3>
                </div>
                <div class="bg-warning rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="dashboard-box bg-soft-info h-150px mb-2rem overflow-hidden">
            <div class="d-flex align-items-center justify-content-between h-100 px-3">
                <div>
                    <h1 class="fs-30 fw-600 text-info mb-1">{{ $realtimePerMinute }}/min</h1>
                    <h3 class="fs-13 fw-600 text-secondary mb-0">{{ translate('Avg. Per Minute') }}</h3>
                </div>
                <div class="bg-info rounded-circle p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                        <line x1="12" y1="20" x2="12" y2="10"></line>
                        <line x1="18" y1="20" x2="18" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="16"></line>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitors Trend Chart -->
    <div class="col-lg-8">
        <div class="dashboard-box card mb-2rem h-lg-510px">
            <div class="card-header border-0 p-0 h-auto">
                <h5 class="mb-0 fs-16 fw-600">{{ translate('Visitors & Page Views Trend') }}</h5>
            </div>
            <div class="card-body p-0">
                <canvas id="visitors-trend-chart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Browsers & Countries -->
    <div class="col-lg-4">
        <!-- Realtime Mini Stats -->
        <div class="dashboard-box card mb-2rem">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2 fs-16 fw-600">{{ translate('Realtime Activity') }}</h6>
                        <h4 class="mb-0">{{ $realtimeLast5Min }} {{ translate('users in last 5 min') }}</h4>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                        <span class="sr-only">{{ translate('Live')}}</span>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                        style="width: {{ min(($realtimeLast5Min / 10) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
        <div class="dashboard-box g-analytics-card mb-2rem">
            <div class="card-header border-0 p-0 bg-white">
                <h5 class="mb-0 fs-16 fw-600">{{ translate('Top Browsers') }}</h5>
            </div>
            <div class="card-body p-0 h-260px c-scrollbar-light">
                <table class="table table-vertical-middle mb-0">
                    <thead>
                        <tr>
                            <th class="pl-0">{{ translate('Browser') }}</th>
                            <th class="text-right">{{ translate('Views') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topBrowsers as $browser)
                        <tr>
                            <td class="pl-0">
                                <span class="badge badge-md badge-dot badge-circle badge-info mr-2"></span>
                                {{ $browser['browser'] }}
                            </td>
                            <td class="text-right">{{ $browser['screenPageViews'] }}</td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Pages -->
    <div class="col-lg-6">
        <div class="dashboard-box card mb-2rem g-analytics-card">
            <div class="card-header border-0 p-0 h-auto mb-3">
                <h5 class="mb-1 fs-16 fw-600">{{ translate('Most Visited Pages') }}</h5>
            </div>
            <div class="card-body px-0 py-0 h-400px c-scrollbar-light">
                <table class="table table-vertical-middle aiz-table mb-0">
                    <thead class="bg-soft-secondary">
                        <tr>
                            <th>{{ translate('Page Title') }}</th>
                            <th class="text-center">{{ translate('Views') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPages as $page)
                        <tr>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;" title="{{ $page['pageTitle'] }}">
                                    {{ $page['pageTitle'] }}
                                </div>
                                <small class="text-muted text-truncate d-block">
                                    {{ Str::limit($page['fullPageUrl'], 50) }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-inline text-dark badge-soft-primary">{{ $page['screenPageViews'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Countries & Referrers -->
    <div class="col-lg-6">
        <div class="row gutters-16">
            <!-- Top Countries -->
            <div class="col-md-6">
                <div class="dashboard-box card mb-2rem g-analytics-card">
                    <div class="card-header border-0 p-0 h-auto">
                        <h5 class="mb-0 fs-16 fw-600">{{ translate('Top Countries') }}</h5>
                    </div>
                    <div class="card-body p-0 h-290px h-lg-420px c-scrollbar-light">
                        <table class="table table-vertical-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-0">{{ translate('Country') }}</th>
                                    <th class="text-right">{{ translate('Views') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCountries as $country)
                                @if(!empty($country['country']))
                                <tr>
                                    <td class="pl-0">
                                        <span class="mr-2"><svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1433D3"><path d="M480.28-96Q401-96 331-126t-122.5-82.5Q156-261 126-330.96t-30-149.5Q96-560 126-629.5q30-69.5 82.5-122T330.96-834q69.96-30 149.5-30t149.04 30q69.5 30 122 82.5T834-629.28q30 69.73 30 149Q864-401 834-331t-82.5 122.5Q699-156 629.28-126q-69.73 30-149 30Zm-.28-72q122 0 210-81t100-200q-9 8-20.5 12.5T744-432H600q-29.7 0-50.85-21.15Q528-474.3 528-504v-48H360v-96q0-29.7 21.15-50.85Q402.3-720 432-720h48v-24q0-14 5-26t13-21q-3-1-10-1h-8q-130 0-221 91t-91 221h216q60 0 102 42t42 102v48H384v105q23 8 46.73 11.5Q454.45-168 480-168Z"/></svg></span>
                                        {{ $country['country'] }}
                                    </td>
                                    <td class="text-right">{{ $country['screenPageViews'] }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top Referrers -->
            <div class="col-md-6">
                <div class="dashboard-box card mb-3 g-analytics-card">
                    <div class="card-header border-0 p-0">
                        <h5 class="mb-0 fs-16 fw-600">{{ translate('Top Referrers') }}</h5>
                    </div>
                    <div class="card-body p-0 h-290px h-lg-420px c-scrollbar-light">
                        <table class="table table-vertical-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-0">{{ translate('Source') }}</th>
                                    <th class="text-right">{{ translate('Views') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReferrers as $referrer)
                                <tr>
                                    <td class="pl-0">
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $referrer['pageReferrer'] ?: 'Direct' }}">
                                            @if(empty($referrer['pageReferrer']))
                                                {{ translate('Direct') }}
                                            @else
                                                {{ Str::limit($referrer['pageReferrer'], 30) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-right">{{ $referrer['screenPageViews'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        // Visitors Trend Chart
        var ctx = document.getElementById('visitors-trend-chart').getContext('2d');
        
        // Process visitors trend data
        var trendData = @json($visitorsTrend);
        var labels = trendData.map(item => item.pageTitle.split(' ').slice(0, 3).join(' ') + '...');
        var activeUsers = trendData.map(item => item.activeUsers);
        var pageViews = trendData.map(item => item.screenPageViews);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: '{{ translate('Active Users') }}',
                        data: activeUsers,
                        borderColor: '#009ef7',
                        backgroundColor: 'rgba(0, 158, 247, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: '{{ translate('Page Views') }}',
                        data: pageViews,
                        borderColor: '#19c553',
                        backgroundColor: 'rgba(25, 197, 83, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Add tooltips for truncated text
        $('[title]').tooltip({
            placement: 'auto',
            trigger: 'hover'
        });

    })();
</script>
@endsection