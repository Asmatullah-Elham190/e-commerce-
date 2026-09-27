<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use CoreComponentRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Str;
use DB;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use ZipArchive;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:analytics_tools_configuration'])->only('google_analytics');
    }

    public function dashboard()
    {
        return view('backend.marketing_analytics.dashboard');
    }

    public function google_analytics_report(Request $request)
    {
        if (!get_setting('google_analytics')) {
            flash(translate("Google Analytics is not enabled."))->error();
            return back();
        }

        try {
            $days = (int) request('days', 7);
            $period = Period::days($days);
            $totalStats = Analytics::fetchTotalVisitorsAndPageViews($period);
            $totals = $totalStats->first() ?? [];

            $visitorsTrend = Analytics::fetchVisitorsAndPageViews($period);
            $topPages     = Analytics::fetchMostVisitedPages($period, 50);
            $topCountries = Analytics::fetchTopCountries($period, 12);
            $topReferrers = Analytics::fetchTopReferrers($period, 12);
            $topBrowsers  = Analytics::fetchTopBrowsers($period, 10);
            $activeUsers = $totals['activeUsers'] ?? 0;
            $realtimeUsers = $activeUsers;
            $realtimePerMinute = $activeUsers > 0 ? round($activeUsers / 30, 0) : 0;
            $realtimeLast5Min = $activeUsers > 0 ? round($activeUsers / 6, 0) : 0;

            return view('backend.reports.google_analytics', compact(
                'topPages', 'topCountries', 'topReferrers', 'topBrowsers', 'visitorsTrend', 'totals', 'days', 'activeUsers', 'realtimeUsers', 'realtimePerMinute', 'realtimeLast5Min'
            ));
            
        } catch (\Google\ApiCore\ApiException $e) {
            // Handle Google API specific exceptions
            $errorMessage = $e->getMessage();
            $errorStatus = $e->getStatus();
            
            // You can customize error messages based on status
            if ($errorStatus === 'PERMISSION_DENIED') {
                flash(translate("Permission denied: Please check your Google Analytics credentials and property ID."))->error();
            } elseif ($errorStatus === 'NOT_FOUND') {
                flash(translate("Property not found: Please verify your Google Analytics property ID."))->error();
            } else {
                flash(translate("Google Analytics Error: ") . $errorMessage)->error();
            }
            
            return back();
            
        } catch (\Exception $e) {
            flash(translate("Google Analytics configuration error. Please check your credentials and settings."))->error();
            return back();
        }
    }

    public function google_analytics_config(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        return view('backend.setup_configurations.google_configuration.google_analytics');
    }

    public function google_tag_manager(Request $request)
    {
        return view('backend.setup_configurations.google_configuration.google_tag_manager');
    }
    public function pixel_analytics(Request $request)
    {
        return view('backend.setup_configurations.facebook_configuration.pixel_analytics');
    }

    public function pixel_conversation_api(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        return view('backend.setup_configurations.facebook_configuration.pixel_capi');
    }

    public function custom_script()
    {
        return view('backend.setup_configurations.custom_script');
    }

    public function global_seo()
    {
        return view('backend.setup_configurations.global_seo');
    }
}
