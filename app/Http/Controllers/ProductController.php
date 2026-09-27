<?php

namespace App\Http\Controllers;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Http\Requests\ProductDraftRequest;
use App\Http\Requests\ProductRequest;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Category;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\User;
use App\Notifications\ShopProductNotification;
use Carbon\Carbon;
use CoreComponentRepository;
use Artisan;
use Cache;
use App\Services\ProductService;
use App\Services\AiService;
use App\Services\ProductTaxService;
use App\Services\ProductFlashDealService;
use App\Services\ProductStockService;
use App\Services\FrequentlyBoughtProductService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Str;

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

        // Staff Permission Check
        $this->middleware(['permission:add_new_product'])->only('create');
        $this->middleware(['permission:show_all_products'])->only('all_products');
        $this->middleware(['permission:show_in_house_products'])->only('admin_products');
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:product_edit'])->only('admin_product_edit', 'seller_product_edit');
        $this->middleware(['permission:product_duplicate'])->only('duplicate');
        $this->middleware(['permission:product_delete'])->only('destroy');
        $this->middleware(['permission:set_category_wise_discount'])->only('categoriesWiseProductDiscount');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $seller_type = 'admin';
        $product_types= ['All Products', 'Physical Products', 'Digital Products', 'Drafts'];
        $source='in-house';

        return view('backend.product.products.index', compact('seller_type', 'product_types','source'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request, $product_type)
    {
        $source = '';
        $seller_type = 'seller';
        if($product_type === 'physical'){
            $product_types = ['Physical Products'];
            $source = 'seller-physical';
        }
        elseif($product_type === 'digital'){
            $product_types = ['Digital Products'];
            $source  ='seller-digital';
        }
        else{
            $source  ='seller-all';
            $product_types = ['All Seller Products', 'Physical Products', 'Digital Products'];
        }
        if (get_setting('product_approve_by_admin')==1){
            $product_types[] = 'Not Approved';
        }

        return view('backend.product.products.index', compact('seller_type', 'product_types','source'));
    }

    public function all_products(Request $request)
    {
        $seller_id = null;
        $seller_type = 'all';
        $product_types = [];
        $brand_id = null;
        $category_id = null;
        $back_to=null;
        $source = 'all';
        
        $products = Product::where('auction_product', 0)->where('wholesale_product', 0);
        if (get_setting('vendor_system_activation') != 1) {
            $products = $products->where('added_by', 'admin');
        }
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
            $product_types = ['All Products', 'Inhouse Products', 'Seller Products', 'Digital Products', 'Physical Products', 'Drafts'];
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'all';

        return view('backend.product.products.index', compact('seller_type', 'product_types', 'brand_id', 'category_id','back_to','source'));
    }

    public function get_filter_products(Request $request)
    {
        //Log::info('Filter Products Request: ', $request->all());
        $col_name = null;
        $query = null;
        $sort_search = null;
        $source = $request->source ??'';
        $products = Product::where('auction_product', 0)->where('wholesale_product', 0);  
        if ($request->product_type == 'drafts') {
            $products = $products->where('draft', 1)->where('added_by', 'admin');
        } else {
            $products = $products->where('draft', 0);
            if ($request->seller_type == 'admin') {
                $products = $products->where('added_by', 'admin');
            } elseif ($request->seller_type == 'seller') {
                $products = $products->where('added_by', 'seller');
                if ($request->user_id != null) {
                    $products = $products->where('user_id', $request->user_id);
                }
            }
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
            if (in_array('low-stock', $filters)) {
                $products->where(function ($query) {
                    $query->whereRaw("
                        (
                            SELECT CASE
                                WHEN products.variant_product = 1 
                                    THEN (SELECT SUM(qty) FROM product_stocks WHERE product_stocks.product_id = products.id)
                                ELSE 
                                    (SELECT qty FROM product_stocks WHERE product_stocks.product_id = products.id LIMIT 1)
                            END
                        ) <= products.low_stock_quantity
                    ");
                });
            }
            if (in_array('all-discount', $filters)) {
                $products->where('discount', '>', 0);
            }
            if (in_array('all-publish', $filters)) {
                $products->where('published', 1);
            }
            if (in_array('refundable', $filters)) {
                $products->where('refundable', 1);
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
        
        $type = $request->seller_type;
        $ptoduct_type = $request->product_type;

        $view = view('backend.product.products.products_table',
            compact('products', 'type', 'col_name', 'query', 'sort_search','ptoduct_type','source')
        )->render();

        return response()->json(['html' => $view]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        CoreComponentRepository::initializeCache();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
            if (addon_is_activated('gst_system')) {
                $business_info = admin_business_info();
                if ( empty($business_info) || !is_array($business_info) || empty($business_info['gstin'])) {
                    flash(translate('Please Update Your GST Information'))->warning();
                    return back();
                }
            }

        return view('backend.product.products.create', compact('categories'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //Log::info('Products Request: ', $request->all());

        $product = $this->productService->store($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->attach($request->category_ids);

        //VAT & Tax
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id',
                'tax',
                'tax_type',
                'product_id'
            ]));
        }

        // Delete other Taxes if GST Rate is updated
        if ($request->has('gst_rate') && addon_is_activated('gst_system')) {
            $product->taxes()->delete();
        }

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]), $product);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id'
        ]), $product);

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id',
            'frequently_bought_selection_type',
            'fq_bought_product_ids',
            'fq_bought_product_category_id'
        ]));

        // Product Translations
        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang',
            'name',
            'unit',
            'description',
            'product_id'
        ]));


        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $redirrect_url = route('products.admin');
        return response()->json([
                'success' => true,
                'message' => translate('Product has been inserted successfully'),
                'redirect' => $redirrect_url
            ]);
    }

    public function store_as_draft(ProductDraftRequest $request)
    {
        //Log::info('Product stoate Request:', $request->all());
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
                'tax_type',
                'flash_deal_id',
                'flash_discount',
                'flash_discount_type',
                'featured',
                'todays_deal',
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

            // Flash deal exist
            if ($request->filled('flash_deal_id')) {
                $this->productFlashDealService->store([
                    'flash_deal_id' => $request->flash_deal_id,
                    'flash_discount' => $request->flash_discount,
                    'flash_discount_type' => $request->flash_discount_type
                ], $product);
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
    public function admin_product_edit(Request $request, $id)
    {
        CoreComponentRepository::initializeCache();

        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
             return redirect()->route('digitalproducts.edit', [
                'id'   => $id,
                'lang' => request('lang', env('DEFAULT_LANGUAGE')),
                'source' => $request->source,
            ]);
        }

        $source= $request->source;

        if (addon_is_activated('gst_system')) {
            if($product->added_by=='admin'){
                $business_info = admin_business_info();
                if ( empty($business_info) || !is_array($business_info) || empty($business_info['gstin'])) {
                    flash(translate('Please Update Your GST Information'))->warning();
                    return back();
                }
            }else{
                $shop = $product->user->shop;
                if ($shop && !$shop->gst_verification) {
                    flash(translate('GST verification is pending for This Seller'))->warning();
                    return back();
                }
            }
        }

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $type = 'In House';
        //dd($categories);
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang', 'type','source'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if (addon_is_activated('gst_system')) {
            $shop = $product->user->shop;
            if ($shop && !$shop->gst_verification) {
                flash(translate('GST verification is pending for This Seller'))->warning();
                return back();
            }

        }
        $source= $request->source;
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect()->route('digitalproducts.seller.edit', [
            'id'   => $id,
            'lang' => request('lang', env('DEFAULT_LANGUAGE')),
            'source'=> $source,
        ]);
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        $type = 'Seller';
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang', 'type', 'source'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        //Log::info('Product Update Request:', $request->all());
        //Product
        if (addon_is_activated('gst_system')) {
            if($product->added_by=='admin'){
                $business_info = admin_business_info();
                if ( empty($business_info) || !is_array($business_info) || empty($business_info['gstin'])) {
                    flash(translate('Please Update Your GST Information'))->warning();
                    return back();
                }
            }else{
                $shop = $product->user->shop;
                if ($shop && !$shop->gst_verification) {
                    flash(translate('GST verification is pending for This Seller'))->warning();
                    return back();
                }
            }
        }

        $product = $this->productService->update($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]), $product);

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);


        //Product Stock
        $product->stocks()->delete();
        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id'
        ]), $product);

        //Flash Deal
        $this->productFlashDealService->store($request->only([
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]), $product);

        //VAT & Tax
        if ($request->tax_id) {
            $product->taxes()->delete();
            $this->productTaxService->store($request->only([
                'tax_id',
                'tax',
                'tax_type',
                'product_id'
            ]));
        }

        // Delete other Taxes if GST Rate is updated
        if ($request->has('gst_rate') && addon_is_activated('gst_system')) {
            $product->taxes()->delete();
        }

        // Frequently Bought Products
        $product->frequently_bought_products()->delete();
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id',
            'frequently_bought_selection_type',
            'fq_bought_product_ids',
            'fq_bought_product_category_id'
        ]));

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang',
                'product_id'
            ]),
            $request->only([
                'name',
                'unit',
                'description'
            ])
        );

        // flash(translate('Product has been updated successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $redirrect_url = 'products.all';

        if($request->source=='all'){
            $redirrect_url = route('products.' . $request->source);
        }elseif($request->source=='in-house')  {
            $redirrect_url = route('products.admin');
        }elseif($request->source=='seller-all') {
            $redirrect_url = route('products.seller', 'all');
        }elseif($request->source=='seller-physical') {
            $redirrect_url = route('products.seller', 'physical');
        }

        return response()->json([
                'success' => true,
                'message' => translate('Product has been updated successfully'),
                'redirect' => $redirrect_url
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
        $result =  $this->single_product_delete($id);
        if ($result) {
            flash(translate('Product has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return $result;
    }

    public function single_product_delete($id)
    {
        $product = Product::findOrFail($id);

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
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return 1;
        } else {
            return 0;
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->single_product_delete($product_id);
            }
        }

        return 1;
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request, $id)
    {
        if (env('DEMO_MODE') == 'On') {
           return response()->json([
                'success' => false,
                'message' => translate('This action is disabled in demo mode'),
                'redirect' => ''
            ]);
        }
        $product = Product::find($id);

        //Product
        $product_new = $this->productService->product_duplicate_store($product);

        //Product Stock
        $this->productStockService->product_duplicate_store($product->stocks, $product_new);

        //VAT & Tax
        $this->productTaxService->product_duplicate_store($product->taxes, $product_new);

        // Product Categories
        foreach ($product->product_categories as $product_category) {
            ProductCategory::insert([
                'product_id' => $product_new->id,
                'category_id' => $product_category->category_id,
            ]);
        }

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->product_duplicate_store($product->frequently_bought_products, $product_new);

        $redirrect_url = '';
        if ($request->has('type') && $request->type == 'Seller') {
            $redirrect_url = route('products.seller.edit', ['id' => $product_new->id, 'lang' => env('DEFAULT_LANGUAGE'), 'source' => 'all']);
        } else {
            $redirrect_url = route('products.admin.edit', ['id' => $product_new->id, 'lang' => env('DEFAULT_LANGUAGE'), 'source' => 'all']);
        }
        return response()->json([
                'success' => true,
                'message' => translate('Product Copied Successfully. You can now edit and save your new product'),
                'redirect' => $redirrect_url
            ]);
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->promotional = 1;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }

    public function bulk_product_todays_deal(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $product = Product::findOrFail($product_id);
                if (!$product) {
                    continue;
                }
                $product->todays_deal = 1;
                $product->promotional = 1;
                $product->save();
            }
            Cache::forget('todays_deal_products');
            return 1;
        }
    }


    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription') && $request->status == 1) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        if (addon_is_activated('gst_system')) {
            if($product->added_by=='admin'){
                $business_info = admin_business_info();
                if ( empty($business_info) || !is_array($business_info) || empty($business_info['gstin'])) {
                    return 3;
                }
                if($product->gst_rate==''|| $product->gst_rate==null || $product->hsn_code=='' || $product->hsn_code==null){
                    return 4;
                }
            }else{
                $shop = $product->user->shop;
                if ($shop && !$shop->gst_verification) {
                    return 3;
                }
                
            }
        }

        $product->save();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    //only upblished
    public function bulk_product_publish(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $product = Product::findOrFail($product_id);
                if (!$product || $product->published) {
                    // skip if already published
                    continue;
                }
                

                if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
                    $shop = $product->user->shop;
                    if (
                        $shop->package_invalid_at == null
                        || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                        || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
                    ) {
                        continue;
                    }
                }

                if (addon_is_activated('gst_system')) {
                    if($product->added_by=='admin'){
                        $business_info = admin_business_info();
                        if ( empty($business_info) || !is_array($business_info) || empty($business_info['gstin'])) {
                           continue;
                        }
                        if($product->gst_rate==''|| $product->gst_rate==null || $product->hsn_code=='' || $product->hsn_code==null){
                            continue;
                        }
                    }else{
                        $shop = $product->user->shop;
                        if ($shop && !$shop->gst_verification) {
                            continue;
                        }
                        if($product->gst_rate==''|| $product->gst_rate==null || $product->hsn_code=='' || $product->hsn_code==null){
                            continue;
                        }
                    }
                }

                $product->published = 1;
                $product->save();
            }
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $shop = $product->user->shop;
            if (
                $shop->package_invalid_at == null
                || Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) < 0
                || $shop->product_upload_limit <= $shop->user->products()->where('published', 1)->count()
            ) {
                return 0;
            }
        }

        $product->save();

        $users                  = User::findMany($product->user_id);
        $data = array();
        $data['product_type']   = $product->digital ==  0 ? 'physical' : 'digital';
        $data['status']         = $request->approved == 1 ? 'approved' : 'rejected';
        $data['product']        = $product;
        $data['notification_type_id'] = get_notification_type('seller_product_approved', 'type')->id;
        Notification::send($users, new ShopProductNotification($data));

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function bulk_product_featured(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $product = Product::findOrFail($product_id);
                if (!$product) {
                    continue;
                }
                $product->featured = 1;
                $product->save();
            }
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
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
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
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
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        // array_push($data, $item->value);
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                }
            }
        }

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
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


    public function get_selected_products(Request $request)
    {
        $products = product::whereIn('id', $request->product_ids)->get();
        return  view('partials.product.frequently_bought_selected_product', compact('products'));
    }

    public function setProductDiscount(Request $request)
    {
        return $this->productService->setCategoryWiseDiscount($request->except(['_token']));
    }

    public function smartBar()
    {
        return view('backend.product.products.smartBar');
    }

    public function updateBusinessSettings(Request $request)
    {
        // dd($request->all());
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if ($business_settings != null) {
            $business_settings->value = $request->value;
            $business_settings->save();
        } else {
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }

        Artisan::call('cache:clear');
        return 1;
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
    
    public function get_products_byCategory(Request $request)
    {
        $products = $this->productService->products_search($request->except(['_token']));
        $single_select = $request->single_select ?? 0;
        return view('partials.product.multiPick_products', compact('products', 'single_select'));
    }

    public function generateWithAI(Request $request)
    {
       return $products = $this->aiService->productGenerateWithAI($request->all());
    }

    public function dashboard()
    {
        return view('backend.product.products.dashboard');
    }
}