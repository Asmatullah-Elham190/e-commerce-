<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandTranslation;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_brands'])->only('index');
        $this->middleware(['permission:add_brand'])->only('create');
        $this->middleware(['permission:edit_brand'])->only('edit');
        $this->middleware(['permission:delete_brand'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brand_tabs =['All Brands', 'Unused Brands'];
        return view('backend.product.brands.index', compact('brand_tabs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view('backend.product.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        $brand = new Brand;
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_keywords = $request->meta_keywords;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        return response()->json([
                'success' => true,
                'message' => translate('Brand has been inserted successfully'),
                'redirect' => route('brands.index')
            ]);

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
        $lang   = $request->lang;
        $brand  = Brand::findOrFail($id);
        return view('backend.product.brands.edit', compact('brand','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $brand->name = $request->name;
        }
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_keywords = $request->meta_keywords;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = BrandTranslation::firstOrNew(['lang' => $request->lang, 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();
        return response()->json([
                'success' => true,
                'message' => translate('Brand has been updated successfully'),
                'redirect' => route('brands.index')
            ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->brand_translations()->delete();
        Product::where('brand_id', $brand->id)->update(['brand_id' => null]);
        Brand::destroy($id);
        return 1;
    }

    public function get_brands_by_filter(Request $request)
    {
        //Log::info('Filter Brands Request: ', $request->all());
        $sort_search =null;
        $brands = Brand::withCount('products')->with(['products.categories' => function($q) {
                $q->select('categories.id', 'categories.name');
            }])->orderBy('created_at', 'desc');
        if ($request->has('brand_type')){
            if($request->brand_type == 'unused_brands'){
                $brands = $brands->having('products_count', '=', 0);
            }
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        $view = view('backend.product.brands.brand_table',
            compact('brands', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function bulk_brands_delete(Request $request)
    {
        $brand_ids = $request->id;
        foreach ($brand_ids as $id) {
            $brand = Brand::findOrFail($id);
            $brand->brand_translations()->delete();
            Product::where('brand_id', $brand->id)->update(['brand_id' => null]);
            Brand::destroy($id);
        }
        return 1;
    }

    public function showCategories($id)
    {
        $brand = Brand::with(['products.categories' => function($q) {
            $q->select('categories.id', 'categories.name');}])->withCount('products') ->findOrFail($id);
        return view('backend.product.brands.details', compact('brand'));
    }

    public function admin_ajax_add_brand_modal(Request $request)
    {
        return view('backend.product.brands.ajax_add_brand_modal');
    }

    public function admin_ajax_add_brand_store(Request $request)
    {
        $brand = new Brand;
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_keywords = $request->meta_keywords;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $brand->logo = $request->logo;
        $brand->save();

        $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);
        $brand_translation->name = $request->name;
        $brand_translation->save();

        return response()->json([
            'success'        => true,
            'message'        => translate('Brand has been inserted successfully'),
            'brand_id'    => $brand->id,
            'brand_name'  => $brand->name,
        ]);
    }
}
