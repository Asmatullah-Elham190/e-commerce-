<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\SellerPackage;

class PageController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:add_website_page'])->only('create');
        $this->middleware(['permission:edit_website_page'])->only('edit');
        $this->middleware(['permission:delete_website_page'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website_settings.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = new Page;
        $page->title = $request->title;
        if (Page::where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            $page->slug             = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
            $page->type             = "custom_page";
            $page->content          = $request->content;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $request->content;
            $page_translation->save();

            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $page_name = $request->page;
        $page = Page::where('slug', $id)->first();
        if($page != null){
            if ($page_name == 'home') {
                return view('backend.website_settings.pages.'.get_setting('homepage_select').'.home_page_edit', compact('page','lang'));
            }
            elseif ($page_name == 'portfolio') {
                return view('backend.website_settings.pages.portfolio.home_page_edit', compact('page','lang'));
            }
            elseif ($page_name == 'resource') {
                return view('backend.website_settings.pages.portfolio.resource_page_edit', compact('page','lang'));
            }
            elseif ($page_name == 'about_us_page') {
                return view('backend.website_settings.pages.portfolio.about_us_page_edit', compact('page','lang'));
            }
            elseif ($page_name == 'join_us_page') {
                return view('backend.website_settings.pages.portfolio.join_us_page_edit', compact('page','lang'));
            }
            elseif ($page_name == 'news') {
                return view('backend.website_settings.pages.portfolio.news_page_edit', compact('page','lang'));
            }
            elseif ($page_name == 'plans_package_page') {
                return view('backend.website_settings.pages.portfolio.plans_package_page_edit', compact('page','lang'));
            }
            elseif ($id == 'contact-us') {
                return view('backend.website_settings.pages.contact_us_page_edit', compact('page','lang'));
            }
            else{
                return view('backend.website_settings.pages.edit', compact('page','lang'));
            }
            
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $content = $request->content;
        if($page->type == 'contact_us_page'){
            $data['description'] = $request->description;
            $data['address'] = $request->address;
            $data['phone'] = $request->phone;
            $data['email'] = $request->email;
            $content = json_encode($data);
        }
        if (Page::where('id','!=', $id)->where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            if($page->type == 'custom_page'){
              $page->slug           = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
            }
            if($request->lang == env("DEFAULT_LANGUAGE")){
              $page->title          = $request->title;
              $page->content        = $content;
            }
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $content;
            $page_translation->save();

            flash(translate('Page has been updated successfully'))->success();
            return redirect()->route('website.pages');
        }

      flash(translate('Slug has been used already'))->warning();
      return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->page_translations()->delete();

        if(Page::destroy($id)){
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug){
        $page = Page::where('slug', $slug)->first();
        $lang = get_system_language() ? get_system_language()->code : null;
        if($page != null){
            if($page->type == 'contact_us_page'){
                return view('frontend.contact_us_page', compact('page','lang'));
            }elseif($page->type == 'about_us_page'){
                return view('frontend.portfolio.about_us_page',  compact('page','lang'));
            }elseif($page->type == 'plans_page'){
                return view('frontend.portfolio.plans_page',  compact('page','lang'));
            }elseif($page->type == 'news_page'){
                $news = Blog::where('status', 1)->where('news', 1)->latest()->get();
                return view('frontend.portfolio.news_page',  compact('page','lang','news'));
            }elseif($page->type == 'join_us_page'){
            return view('frontend.portfolio.join_us_page',  compact('page','lang'));
            }elseif($page->type == 'resources_page'){
                $events = Blog::where('status', 1)->where('event', 1)->latest()->get();
                $goingons = Blog::where('status', 1)->where('going_on', 1)->latest()->get();

                return view('frontend.portfolio.resources_page',  compact('page','lang','goingons','events'));
            }
            elseif($page->type == 'plans_package_page'){
                $seller_packages = SellerPackage::all();
                return view('frontend.portfolio.plans_package_page',  compact('page','lang','seller_packages'));
            }
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }
    public function mobile_custom_page($slug){
        $page = Page::where('slug', $slug)->first();
        if($page != null){
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }

    public function getChildCategories(Request $request)
    {
        $children = \App\Models\Category::where('parent_id', $request->parent_id)->get()->map(function($cat) {
            return ['id' => $cat->id, 'name' => $cat->getTranslation('name')];
        });
        return response()->json($children);
    }
}
