<?php

namespace App\Http\Controllers;

use App\Models\TopBanner;
use App\Models\TopBannerTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class TopBannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_top_banner'])->only('index');
        $this->middleware(['permission:top_banner_create'])->only('create');
        $this->middleware(['permission:top_banner_create'])->only('store');
        $this->middleware(['permission:top_banner_edit'])->only('edit');
        $this->middleware(['permission:top_banner_edit'])->only('update');
        $this->middleware(['permission:top_banner_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $topBanners = TopBanner::query();

        if ($request->has('search')) {
            $sort_search = $request->search;
            $topBanners->where('text', 'like', '%' . $sort_search . '%');
        }
        $topBanners = $topBanners->orderBy('created_at', 'desc')->paginate(15);

        return view('backend.website_settings.topBanner.top_banner_list', compact('topBanners', 'sort_search'));
    }

    public function create()
    {
        return view('backend.website_settings.topBanner.top_banner_create');
    }

    public function setting()
    {
        return view('backend.website_settings.topBanner.top_banner_setting');
    }

    public function store(Request $request)
    {
        $topBanner = new TopBanner();
        $topBanner->text      = $request->text;
        $topBanner->link   = $request->link;
        $topBanner->save();

        if ($request->text != null) {
            $top_banner_translation = TopBannerTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'top_banner_id' => $topBanner->id]);
            $top_banner_translation->text = $request->text;
            $top_banner_translation->save();
        }

        flash(translate('Top Bar has been created successfully!'))->success();
        return redirect()->route('top_banner.index');
    }

    public function edit(Request $request, $id)
    {
        $lang  = $request->lang;
        $topBanner  = TopBanner::findOrFail($id);
        return view('backend.website_settings.topBanner.top_banner_edit', compact('topBanner', 'lang'));
    }

    public function update(Request $request, $id)
    {
        $topBanner = TopBanner::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $topBanner->text     = $request->text;
        }
        $topBanner->link   = $request->link;
        $topBanner->save();

        if ($request->text != null) {
            $top_label_translation = TopBannerTranslation::firstOrNew(['lang' => $request->lang, 'top_banner_id' => $topBanner->id]);
            $top_label_translation->text = $request->text;
            $top_label_translation->save();
        }

        flash(translate('Top Bar has been updated successfully!'))->success();
        return redirect()->route('top_banner.index');
    }

    public function destroy($id)
    {
        $topBanner = TopBanner::findOrFail($id);
        $topBanner->delete();
        flash(translate('Top Bar has been deleted successfully!'))->success();
        return back();
    }

    public function update_status(Request $request)
    {
        $top_banner = TopBanner::findOrFail($request->id);
        $top_banner->status = $request->status;
        if ($top_banner->save()) {
            return 1;
        }
        return 0;
    }
}
