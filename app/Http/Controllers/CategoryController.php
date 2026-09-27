<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Models\User;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;
use Cache;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_product_categories'])->only('index');
        $this->middleware(['permission:add_product_category'])->only('create');
        $this->middleware(['permission:edit_product_category'])->only('edit');
        $this->middleware(['permission:delete_product_category'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $category_tabs = ['All Categories', 'Physical Categories', 'Digital Categories'];
        $categories = Category::orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.product.categories.index', compact('categories', 'sort_search', 'category_tabs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->order_level = 0;
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }

        if ($request->slug != null) {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        $category->attributes()->sync($request->filtering_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        return response()->json([
                'success' => true,
                'message' => translate('Category has been inserted successfully'),
                'redirect' => route('categories.index')
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
        $lang = $request->lang;
        $category = Category::findOrFail($id);
        $categories = Category::where('parent_id', 0)
            ->where('digital', $category->digital)
            // ->with('childrenCategories')
            // ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)
            ->with(['childrenCategories' => function ($query) use ($category) {
                $query->whereNotIn('id', CategoryUtility::children_ids($category->id, true))
                      ->where('id', '!=' , $category->id);
            }])
            ->orderBy('name','asc')
            ->get();

        return view('backend.product.categories.edit', compact('category', 'categories', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $category->name = $request->name;
        }
        if($request->order_level != null) {
            $category->order_level = $request->order_level;
        }
        $category->digital = $request->digital;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;

        $previous_level = $category->level;

        if ($request->parent_id != "0") {
            $category->parent_id = $request->parent_id;

            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1 ;
        }
        else{
            $category->parent_id = 0;
            $category->level = 0;
        }

        // if($category->level > $previous_level){
        //     CategoryUtility::move_level_down($category->id);
        // }
        // elseif ($category->level < $previous_level) {
        //     CategoryUtility::move_level_up($category->id);
        // }

        if ($request->slug != null) {
            $category->slug = strtolower($request->slug);
        }
        else {
            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }


        if ($request->commision_rate != null) {
            $category->commision_rate = $request->commision_rate;
        }

        $category->save();

        //Updating childer categories level
        CategoryUtility::update_child_level($category->id);

        $category->attributes()->sync($request->filtering_attributes);

        $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
        $category_translation->name = $request->name;
        $category_translation->save();

        Cache::forget('featured_categories');
        //flash(translate('Category has been updated successfully'))->success();
        return response()->json([
                'success' => true,
                'message' => translate('Category has been updated successfully'),
                'redirect' => route('categories.index')
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
        $category = Category::findOrFail($id);
        $category->attributes()->detach();

        // Category Translations Delete
        foreach ($category->category_translations as $key => $category_translation) {
            $category_translation->delete();
        }

        foreach (Product::where('category_id', $category->id)->get() as $product) {
            $product->category_id = null;
            $product->save();
        }

        CategoryUtility::delete_category($id);
        Cache::forget('featured_categories');

        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        return 1;
    }

    public function updateHot(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->hot_category = $request->status;
        $category->save();
        Cache::forget('hot_categories');
        return 1;
    }

    public function categoriesByType(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', $request->digital)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.categories.categories_option', compact('categories'));
    }

    public function categoriesWiseProductDiscount(Request $request){
        $sort_search =null;
        $category_tabs = ['All Categories'];
        $categories = Category::with('sellerDiscounts')->orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.product.category_wise_discount.set_discount', compact('categories', 'sort_search', 'category_tabs'));
    }

    public function categoriesWiseProductDiscountFilter(Request $request)
    {
        $categories = Category::with('sellerDiscounts')->orderBy('order_level', 'desc');
        $sort_search = null;

        if ($request->filled('search')) {
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%' . $sort_search . '%');
        }

        $categories = $categories->paginate(15);
        $view = view(
            'backend.product.category_wise_discount.table',
            compact('categories', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }
    
    public function categoriesWiseCommission(Request $request){
        $sort_search =null;
        $categories = Category::orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.sellers.category_wise_commission.set_commission', compact('categories', 'sort_search'));
    }

    public function categoriesWiseCommissionUpdate(Request $request)
    {
     
        $categoryId = $request->input('category_id');
        $commissionRate = $request->input('commission');
    
        $category = Category::findOrFail($categoryId);
    
        // Update the main category
        $category->commision_rate = $commissionRate;
        $category->save();
    
        // Recursively update all children
        $this->updateChildrenCommissionRate($category, $commissionRate);
    
       
        return 1;
    }
    
    private function updateChildrenCommissionRate(Category $category, $commissionRate)
    {
        foreach ($category->categories as $child) {
            $child->commision_rate = $commissionRate;
            $child->save();
    
            if ($child->categories->isNotEmpty()) {
                $this->updateChildrenCommissionRate($child, $commissionRate);
            }
        }
    }

    public function get_categories_by_filter(Request $request)
    {
        Log::info('Filter Category Request: ', $request->all());
         $categories = Category::orderBy('order_level', 'desc');
         $sort_search =null;
         if($request->category_type=='physical_categories'){
            $categories= $categories->where('digital',0);
         }
         else if($request->category_type=='digital_categories'){
            $categories= $categories->where('digital',1);
         }

        if ($request->search != null){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }

        $categories=$categories->paginate(15);
        $view = view('backend.product.categories.categories_table',
            compact('categories', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function category_details($id)
    {
        $category = Category::withCount('productCategories')->findOrFail($id);
        return view('backend.product.categories.details', compact('category'));
    }

    public function bulk_categories_featured(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $category_id) {
                $category = Category::findOrFail($category_id);
                if (!$category) {
                    continue;
                }
                $category->featured = 1;
                $category->save();
            }
            Cache::forget('featured_categories');
            return 1;
        }
    }

    public function bulk_categories_hot(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $category_id) {
                $category = Category::findOrFail($category_id);
                if (!$category) {
                    continue;
                }
                $category->hot_category = '1';
                $category->save();
            }
            Cache::forget('hot_categories');
            return 1;
        }
    }

    public function bulk_categories_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $category_id) {
                $category = Category::findOrFail($category_id);
                if (!$category) {
                    continue;
                }
                $category->attributes()->detach();
                foreach ($category->category_translations as $key => $category_translation) {
                    $category_translation->delete();
                }
                foreach (Product::where('category_id', $category->id)->get() as $product) {
                    $product->category_id = null;
                    $product->save();
                }

                CategoryUtility::delete_category($category_id);
            }
            Cache::forget('featured_categories');
            return 1;
        }
    }

    public function admin_ajax_add_category_modal(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.product.categories.ajax_add_category_modal', ['categories' => $categories]);
    }

    public function admin_ajax_add_category_store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->order_level = $request->order_level ?? 0;
        $category->digital = $request->digital ?? 0;
        $category->banner = $request->banner;
        $category->icon = $request->icon;
        $category->cover_image = $request->cover_image;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;

        if ($request->parent_id && $request->parent_id != "0") {
            $category->parent_id = $request->parent_id;
            $parent = Category::find($request->parent_id);
            $category->level = $parent->level + 1;
        }

        $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);

        $category->save();

        $category->attributes()->sync($request->filtering_attributes ?? []);

        $translation = CategoryTranslation::firstOrNew([
            'lang' => env('DEFAULT_LANGUAGE'),
            'category_id' => $category->id
        ]);
        $translation->name = $request->name;
        $translation->save();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return response()->json([
            'success'        => true,
            'message'        => translate('Category has been inserted successfully'),
            'category_id'    => $category->id,
            'category_name'  => $category->name,
            'single_options' => $this->buildCategoryOptions($categories, $category->id),
            'multi_options'  => $this->buildCategoryOptions($categories, [$category->id]),
        ]);
    }

    private function buildCategoryOptions($categories, $selectedIds = [], $level = 0): string
    {
        $html = '';
        $selectedIds = (array) $selectedIds;
        
        foreach ($categories as $category) {
            $indent   = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            $selected = in_array($category->id, $selectedIds) ? 'selected' : '';
            $html    .= "<option value=\"{$category->id}\" {$selected}>{$indent}" . e($category->getTranslation('name')) . "</option>";

            if ($category->childrenCategories?->count() > 0) {
                $html .= $this->buildCategoryOptions($category->childrenCategories, $selectedIds, $level + 1);
            }
        }
        return $html;
    }
}
