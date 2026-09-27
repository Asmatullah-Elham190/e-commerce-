<?php

namespace App\Http\Controllers\Seller;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductDraftRequest;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Wishlist;
use App\Models\User;
use App\Notifications\ShopProductNotification;
use App\Services\AiService;
use Artisan;
use Auth;


use App\Services\ProductService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\FrequentlyBoughtProductService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $frequentlyBoughtProductService;
    protected $aiService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        FrequentlyBoughtProductService $frequentlyBoughtProductService,
        AiService $aiService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->frequentlyBoughtProductService = $frequentlyBoughtProductService;
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $product_types = [];
        $brand_id = null;
        $category_id = null;
        $back_to=null;
        $selected_type= null;
        if ($request->has('brand_id') && $request->brand_id != null) {
            $brand_id = $request->brand_id;
            $back_to='brands';
            $product_types = ["Products of '{$request->brand_name}' Brand"];
        }
        else if($request->has('category_id') && $request->category_id != null) {
            $product_types = ["Products of '{$request->category_name}' Category"];
            $back_to='categories';
            $category_id = $request->category_id;
        }else{
            $product_types = ['All Products', 'Physical Products', 'Digital Products', 'Not Approved', 'Drafts'];
        }
        if ($request->has('selected_type')) {
            $selected_type = $request->selected_type;
        }
        $type = 'all';

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        return view('seller.product.products.index', compact('product_types', 'brand_id', 'category_id','back_to','categories','selected_type'));
    }

    public function create(Request $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }
        if (addon_is_activated('gst_system')) {
            $shop = Auth::user()->shop;
            if ($shop && !$shop->gst_verification) {
                flash(translate('GST verification is pending for your account.'))->warning();
                return redirect()->route('seller.products');
            }
        }
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('seller.product.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return redirect()->route('seller.products');
            }
        }
        if (addon_is_activated('gst_system')) {
            $shop = Auth::user()->shop;
            if ($shop && !$shop->gst_verification) {
                flash(translate('GST verification is pending for your account.'))->warning();
                return redirect()->route('seller.products');
            }
        }

        $product = $this->productService->store($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);

        ///Product categories
        $product->categories()->attach($request->category_ids);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Delete other Taxes if GST Rate is updated
        if ($request->filled('gst_rate') && addon_is_activated('gst_system')) {
            $product->taxes()->delete();
        }

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang', 'name', 'unit', 'description', 'product_id'
        ]));

        if (get_setting('product_approve_by_admin') == 1) {
            $users = User::findMany(User::where('user_type', 'admin')->first()->id);
            
            $data = array();
            $data['product_type']   = 'physical';
            $data['status']         = 'pending';
            $data['product']        = $product;
            $data['notification_type_id'] = get_notification_type('seller_product_upload', 'type')->id;

            Notification::send($users, new ShopProductNotification($data));
        }


        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $redirrect_url = route('seller.products');
        return response()->json([
            'success' => true,
            'message' => translate('Product has been inserted successfully'),
            'redirect' => $redirrect_url
        ]);

    }

    public function store_as_draft(ProductDraftRequest $request)
    {
        if(isset($request->id)) {
            $product = Product::find($request->id);
            if ($product && $product->draft != 1) {
                return response()->json([
                'success' => false,
                'message' => translate('Only draft products can be automatically saved as draft.'),
                'redirect' => ''
                ]);
            }
        }

        try {
            // Prepare product data
            $productData = $request->except([
                '_token',
                'sku',
                'choice',
                'tax_id',
                'tax',
                'tax_type'
            ]);

            // Add draft-specific fields
            $productData['published'] = 0;
            $productData['draft'] = 1;
            $productData['name'] = $productData['name'] ? $productData['name']:'Draft  Product';
            $productData['unit_price'] = $productData['unit_price'] ?? 0.0;
            $productData['current_stock'] = $productData['current_stock'] ?? 0;
            $productData['qty'] = $productData['qty'] ?? 0;

            // Create or update draft product
            $product = $this->productService->storeOrUpdateDraft($productData);
            $request->merge(['product_id' => $product->id]);

            // Sync categories if present
            if ($request->filled('category_ids')) {
                $product->categories()->sync($request->category_ids);
            }

            // Save tax if exist
            if ($request->filled('tax_id')) {
                $this->productTaxService->store([
                    'tax_id' => $request->tax_id,
                    'tax' => $request->tax,
                    'tax_type' => $request->tax_type,
                    'product_id' => $product->id
                ]);
            }

            // Product stock if present
            if ($product->stocks()->exists()) {
                $product->stocks()->delete();
            }
            $this->productStockService->store($request->only([
                'colors_active',
                'colors',
                'choice_no',
                'unit_price',
                'sku',
                'current_stock',
                'product_id'
            ]), $product);


            // Frequently bought products if present
            if ($request->filled('frequently_bought_selection_type')) {
                $this->frequentlyBoughtProductService->store([
                    'product_id' => $product->id,
                    'frequently_bought_selection_type' => $request->frequently_bought_selection_type,
                    'fq_bought_product_ids' => $request->fq_bought_product_ids,
                    'fq_bought_product_category_id' => $request->fq_bought_product_category_id
                ]);
            }

            // Product translations
            ProductTranslation::updateOrCreate(
                [
                    'product_id' => $product->id, 
                    'lang' => env('DEFAULT_LANGUAGE', 'en')
                ],
                [
                    'name' => $request->name,
                    'unit' => $request->unit,
                    'description' => $request->description
                ]
            );

            // Clear caches
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return response()->json([
                'success' => true,
                'product_id' => $product->id,
                'message' => translate('Draft saved successfully'),
            ]);

        } catch (\Exception $e) {
            \Log::error('Draft save failed: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => translate('Failed to save draft: ') . $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->digital == 1) {
             return redirect()->route('seller.digitalproducts.edit', [
                'id'   => $id,
                'lang' => request('lang', env('DEFAULT_LANGUAGE'))
            ]);
        }

        if (addon_is_activated('gst_system')) {
            $shop = $product->user->shop;
            if ($shop && !$shop->gst_verification) {
                flash(translate('GST verification is pending.'))->warning();
                return back();
            }
        }

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('seller.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        //Product
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type'
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);

        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $request->merge(['product_id' => $product->id]);
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Delete other Taxes if GST Rate is updated
        if ($request->filled('gst_rate') && addon_is_activated('gst_system')) {
            $product->taxes()->delete();
        }

        // Frequently Bought Products
        $product->frequently_bought_products()->delete();
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));
        
        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $redirrect_url = route('seller.products');

        return response()->json([
            'success' => true,
            'message' => translate('Product has been updated successfully'),
            'redirect' => $redirrect_url
        ]);
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if (addon_is_activated('seller_subscription') && $request->status == 1) {
            if (!seller_package_validity_check()) {
                return 2;
            }
        }
        if (addon_is_activated('gst_system') && $request->status == 1) {
            $shop = Auth::user()->shop;
            if ($shop && !$shop->gst_verification) {
                return 3;
            }
            if($product->gst_rate==''|| $product->gst_rate==null || $product->hsn_code=='' || $product->hsn_code==null){
                return 4;
            }
        }

        
        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->seller_featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                flash(translate('Please upgrade your package.'))->warning();
                return back();
            }
        }

        if (addon_is_activated('gst_system')) {
            $shop = Auth::user()->shop;
            if ($shop && !$shop->gst_verification) {
                flash(translate('GST verification is pending for your account.'))->warning();
                return redirect()->route('seller.products');
            }
        }

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        // Product Categories
        foreach($product->product_categories as $product_category){
            ProductCategory::insert([
                'product_id' => $product_new->id,
                'category_id' => $product_category->category_id,
            ]);
        }

        $this->frequentlyBoughtProductService->product_duplicate_store($product->frequently_bought_products, $product_new);
        

        $redirrect_url = route('seller.products.edit', ['id' => $product_new->id, 'lang' => env('DEFAULT_LANGUAGE')]);

        return response()->json([
                'success' => true,
                'message' => translate('Product Copied Successfully. You can now edit and save your new product'),
                'redirect' => $redirrect_url
            ]);

    }


    public function single_destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return 0;
        }

        $product->product_translations()->delete();
        $product->categories()->detach();
        $product->stocks()->delete();
        $product->taxes()->delete();
        $product->frequently_bought_products()->delete();
        $product->last_viewed_products()->delete();
        $product->flash_deal_products()->delete();
        deleteProductReview($product);
        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Wishlist::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return 1;
        } else {
            flash(translate('Something went wrong'))->error();
            return 0;
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (Auth::user()->id != $product->user_id) {
            flash(translate('This product is not yours.'))->warning();
            return back();
        }

        $product->product_translations()->delete();
        $product->categories()->detach();
        $product->stocks()->delete();
        $product->taxes()->delete();
        $product->frequently_bought_products()->delete();
        $product->last_viewed_products()->delete();
        $product->flash_deal_products()->delete();
        deleteProductReview($product);
        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();
            Wishlist::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }
        return 1;
    }

    public function product_search(Request $request)
    {
        $products = $this->productService->product_search($request->except(['_token']));
        return view('partials.product.product_search', compact('products'));
    }

    public function products_search(Request $request)
    {
        $products = $this->productService->products_search($request->except(['_token']));
        $single_select = $request->single_select ?? 0;
        return view('partials.product.products_search', compact('products', 'single_select'));
    }

    public function get_selected_products(Request $request){
        $products = product::whereIn('id', $request->product_ids)->get();
        return  view('partials.product.frequently_bought_selected_product', compact('products'));
    }

    public function categoriesWiseProductDiscount(Request $request){
        $sort_search =null;
         $categories = Category::with('sellerDiscount')
        ->orderBy('order_level', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('seller.product.category_wise_discount.set_discount', compact('categories', 'sort_search'));
    }
    
    public function setProductDiscount(Request $request)
    {   
        $response = $this->productService->setCategoryWiseDiscount($request->except(['_token']));
        return $response;
    }

    public function get_filter_products(Request $request)
    {
        //Log::info('Filter Products Request: ', $request->all());
        $col_name = null;
        $query = null;
        $sort_search = null;
        $products = Product::where('auction_product', 0)->where('wholesale_product', 0);  
        $products = $products->where('user_id', auth()->user()->id);
        if ($request->product_type == 'drafts') {
            $products = $products->where('draft', 1);
        } else {
            $products = $products->where('draft', 0);
            if ($request->product_type != 'drafts') {
                if ($request->product_type == 'digital_products') {
                    $products = $products->where('digital', 1);
                } else if ($request->product_type == 'physical_products') {
                    $products = $products->where('digital', 0);
                } else if ($request->product_type == 'not_approved') {
                    $products = $products->where('approved', 0);
                }
                else if ($request->product_type == 'pos_product_list') {
                    $products = $products->where('pos', 1);
                }
            }
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $filters = $request->selected_filter ?? [];
        if (!empty($filters)) {
            if (in_array('all-discount', $filters)) {
                $products->where('discount', '>', 0);
            }
            if (in_array('all-publish', $filters)) {
                $products->where('published', 1);
            }
        }
        if ( $request->filled('brand_id')) {
            $products = $products->where('brand_id', $request->brand_id);
        } 
        if ($request->filled('category_id')) {
            $products = $products->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->category_id);
            });
        }

        if($request->product_type == 'pos_product_list'){
            $products = $products->orderBy('updated_at', 'desc')->paginate(15);
        }else{
            $products = $products->orderBy('created_at', 'desc')->paginate(15);
        }
        $ptoduct_type = $request->product_type;

        $view = view('seller.product.products.products_table',
            compact('products', 'col_name', 'query', 'sort_search','ptoduct_type')
        )->render();

        return response()->json(['html' => $view]);
    }

    public function stockShow($id)
    {
        $product = Product::findOrFail($id);
        return view('backend.product.products.show_stock', compact('product'));
    }

    public function bulk_product_stock_update(Request $request)
    {
        if ($request->stocks) {
            $product = Product::findOrFail($request->product_id);
            foreach ($request->stocks as $stock_id => $qty) {
                if (is_numeric($stock_id) && $stock_id > 0) {
                    $product_stock = ProductStock::find($stock_id);
                } else {
                    $product_stock = null;
                }
                if (!$product_stock) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $request->product_id;
                    $product_stock->variant = '';
                     $product_stock->price = $product->unit_price;
                    $product_stock->sku = NULL;
                }
                $product_stock->qty = $qty;
                $product_stock->save();
            }
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
    }

    public function generateWithAI(Request $request)
    {
       return $products = $this->aiService->productGenerateWithAI($request->all());
    }
}