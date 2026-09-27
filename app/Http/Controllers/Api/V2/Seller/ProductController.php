<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\V2\Seller\AttributeCollection;
use App\Http\Resources\V2\Seller\BrandCollection;
use Illuminate\Http\Request;

use App\Http\Resources\V2\Seller\CategoriesCollection;
use App\Http\Resources\V2\Seller\ColorCollection;
use App\Http\Resources\V2\Seller\ProductCollection;
use App\Http\Resources\V2\Seller\ProductDetailsCollection;
use App\Http\Resources\V2\Seller\ProductReviewCollection;
use App\Http\Resources\V2\Seller\TaxCollection;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTax;
use App\Models\ProductTranslation;
use App\Models\Review;
use App\Models\Tax;
use Artisan;

use App\Services\ProductFlashDealService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use App\Services\FrequentlyBoughtProductService;

use App\Http\Resources\V2\Seller\NoteCollection;
use App\Http\Resources\V2\Seller\ProductReviewDetailsCollection;
use App\Http\Resources\V2\Seller\UnitCollection;
use App\Http\Resources\V2\Seller\SizeChartCollection;
use App\Models\AttributeValue;
use App\Models\MeasurementPoint;
use App\Models\Note;
use App\Models\SizeChart;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $frequentlyBoughtProductService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        FrequentlyBoughtProductService $frequentlyBoughtProductService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->frequentlyBoughtProductService = $frequentlyBoughtProductService;
    }

    public function index(Request $request)
    {
        $products = Product::where('user_id', auth()->user()->id)
            ->where('digital', 0)
            ->where('auction_product', 0)
            ->where('wholesale_product', 0);

        if ($request->filled('category_id')) {
            $products = $products->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $products = $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(10);

        return new ProductCollection($products);
    }

    public function getCategory()
    {
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return CategoriesCollection::collection($categories);
    }

    public function getBrands()
    {
        $brands = Brand::all();

        return BrandCollection::collection($brands);
    }
    public function getTaxes()
    {
        $taxes = Tax::where('tax_status', 1)->get();

        return TaxCollection::collection($taxes);
    }
    public function getAttributes()
    {
        $attributes = Attribute::with('attribute_values')->get();

        return AttributeCollection::collection($attributes);
    }
    public function getColors()
    {
        $colors = Color::orderBy('name', 'asc')->get();

        return ColorCollection::collection($colors);
    }


    public function store(ProductRequest $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check(auth()->user()->id)) {
                return $this->failed(translate('Please upgrade your package.'));
            }
        }

        if (auth()->user()->user_type != 'seller') {
            return $this->failed(translate('Unauthenticated User.'));
        }

        $request->merge(['added_by' => 'seller']);
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

        return $this->success(translate('Product has been inserted successfully'));
    }

    public function edit(Request $request, $id)
    {

        if (auth()->user()->user_type != 'seller') {
            return $this->failed(translate('Unauthenticated User.'));
        }

        $product = Product::where('id', $id)->with('stocks')->first();

        if (auth()->user()->id != $product->user_id) {
            return $this->failed(translate('This product is not yours.'));
        }
        $product->lang =  $request->lang == null ? env("DEFAULT_LANGUAGE") : $request->lang;

        return new ProductDetailsCollection($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        //Product
        $product = $this->productService->update($request->except([
            '_token', 'sku', 'choice', 'tax_id', 'tax', 'tax_type', 'flash_deal_id', 'flash_discount', 'flash_discount_type'
        ]), $product);

        //Product Stock
        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }
        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->sync($request->category_ids);

        //Product Stock
        $this->productStockService->store($request->only([
            'colors_active', 'colors', 'choice_no', 'unit_price', 'sku', 'current_stock', 'product_id'
        ]), $product);

        // Frequently Bought Products
        $product->frequently_bought_products()->delete();
        $this->frequentlyBoughtProductService->store($request->only([
            'product_id', 'frequently_bought_selection_type', 'fq_bought_product_ids', 'fq_bought_product_category_id'
        ]));

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            $request->merge(['product_id' => $product->id]);
            $this->productTaxService->store($request->only([
                'tax_id', 'tax', 'tax_type', 'product_id'
            ]));
        }

        // Product Translations
        ProductTranslation::updateOrCreate(
            $request->only([
                'lang', 'product_id'
            ]),
            $request->only([
                'name', 'unit', 'description'
            ])
        );

        return $this->success(translate('Product has been updated successfully'));
    }

    public function change_status(Request $request)
    {
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check()) {
                return $this->failed(translate('Please upgrade your package'));
            }
        }

        $product = Product::where('user_id', auth()->user()->id)
            ->where('id', $request->id)
            ->update([
                'published' => $request->status
            ]);

        if ($product == 0) {
            return $this->failed(translate('This product is not yours'));
        }
        return ($request->status == 1) ?
            $this->success(translate('Product has been published successfully')) :
            $this->success(translate('Product has been unpublished successfully'));
    }

    public function change_featured_status(Request $request)
    {
        $product = Product::where('user_id', auth()->user()->id)
            ->where('id', $request->id)
            ->update([
                'seller_featured' => $request->featured_status
            ]);

        if ($product == 0) {
            return  $this->failed(translate('This product is not yours'));
        }

        return ($request->featured_status == 1) ?
            $this->success(translate('Product has been featured successfully')) :
            $this->success(translate('Product has been unfeatured successfully'));
    }

    public function duplicate($id)
    {
        $product = Product::findOrFail($id);

        if (auth()->user()->id != $product->user_id) {
            return $this->failed(translate('This product is not yours'));
        }
        if (addon_is_activated('seller_subscription')) {
            if (!seller_package_validity_check(auth()->user()->id)) {
                return $this->failed(translate('Please upgrade your package'));
            }
        }

        //Product
        $product_new = (new ProductService)->product_duplicate_store($product);

        //Store in Product Stock Table
        (new ProductStockService)->product_duplicate_store($product->stocks, $product_new);

        //Store in Product Tax Table
        (new ProductTaxService)->product_duplicate_store($product->taxes, $product_new);

        // Product Categories
        foreach($product_new->product_categories as $product_category){
            ProductCategory::insert([
                'product_id' => $product_new->id,
                'category_id' => $product_category->category_id,
            ]);
        }

        // Frequently Bought Products
        $this->frequentlyBoughtProductService->product_duplicate_store($product->frequently_bought_products, $product_new);

        return $this->success(translate('Product has been duplicated successfully'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (auth()->user()->id != $product->user_id) {
            return $this->failed(translate('This product is not yours'));
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

            return $this->success(translate('Product has been deleted successfully'));

            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        }
    }

    public function product_reviews(Request $request)
    {
        $sortByRating = $request->rating != null ? $request->rating : null;
        $search       = $request->search != null ? $request->search : null;

        $products = Product::join('reviews', 'reviews.product_id', '=', 'products.id')
            ->where('products.user_id', auth()->user()->id)
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'products.slug as product_slug',
                'products.thumbnail_img as product_thumbnail_img',
                'products.rating as rating'
            )
            ->groupBy('products.id');

        $products = $sortByRating != null
            ? $products->orderBy('products.rating', $sortByRating)
            : $products->orderBy('products.id', 'desc');

        if ($search != null) {
            $products->where(function ($q) use ($search) {
                $q->where('products.name', 'like', '%' . $search . '%')
                ->orWhereHas('product_translations', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $products = $products->paginate(10);

        $products->getCollection()->transform(function ($product) {
            $newCount = Review::where('product_id', $product->product_id)
                ->where('viewed', 0)
                ->count();

            $product->total_reviews     = Review::where('product_id', $product->product_id)->count();
            $product->new_reviews_count = $newCount;
            $product->has_new_review    = $newCount > 0;

            return $product;
        });

        return new ProductReviewCollection($products);
    }

    public function product_review_details(Request $request, $id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        if (env('DEMO_MODE') != 'On') {
            $product->reviews()->update(['viewed' => 1]);
        }

        $reviews = $product->reviews()
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->select(
                'reviews.id',
                'reviews.rating',
                'reviews.comment',
                'reviews.photos',
                'reviews.status',
                'reviews.created_at',
                'users.name as user_name',
                'users.avatar_original'
            )
            ->orderBy('reviews.id', 'desc')
            ->paginate(10);

        return new ProductReviewDetailsCollection($reviews, $product);
    }

    public function remainingUploads()
    {
        $remaining_uploads = (max(0, auth()->user()->shop->product_upload_limit - auth()->user()->products->count()));
        return response()->json([
            'ramaining_product' => $remaining_uploads,
        ]);
    }

    public function productSearch(Request $request){
        $products = (new ProductService)->product_search($request->all());
        return new ProductCollection($products);
    }

    public function getUnit()
    {
        $units = Unit::all();

        return UnitCollection::collection($units);
    }

    public function getRefundNotes()
    {
        $user_id = Auth::id();
        $refundNotes = Note::where('note_type', 'refund')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->orWhere(function ($q) {
                        $q->where('seller_access', 1)
                        ->whereHas('user', function ($uq) {
                            $uq->where('user_type', 'admin');
                        });
                    });
            })
            ->get();

        return NoteCollection::collection($refundNotes);
    }

    public function getWarrantyNotes()
    {
        $user_id = Auth::id();
        $warrantyNotes = Note::where('note_type', 'warranty')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->orWhere(function ($q) {
                        $q->where('seller_access', 1)
                        ->whereHas('user', function ($uq) {
                            $uq->where('user_type', 'admin');
                        });
                    });
            })
            ->get();

        return NoteCollection::collection($warrantyNotes);
    }

    public function getDeliveryNotes()
    {
        $user_id = Auth::id();
        $warrantyNotes = Note::where('note_type', 'delivery')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->orWhere(function ($q) {
                        $q->where('seller_access', 1)
                        ->whereHas('user', function ($uq) {
                            $uq->where('user_type', 'admin');
                        });
                    });
            })
            ->get();

        return NoteCollection::collection($warrantyNotes);
    }

    public function getShippingNotes()
    {
        $user_id = Auth::id();
        $warrantyNotes = Note::where('note_type', 'shipping')
            ->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                    ->orWhere(function ($q) {
                        $q->where('seller_access', 1)
                        ->whereHas('user', function ($uq) {
                            $uq->where('user_type', 'admin');
                        });
                    });
            })
            ->get();

        return NoteCollection::collection($warrantyNotes);
    }

    public function getSizeCharts()
    {
        $sizeCharts = SizeChart::where('user_id', auth()->id())
            ->orWhere(function ($query) {
                $query->where('user_id', get_admin()->id)
                    ->where('seller_access', 1);
            })
            ->get();

        return SizeChartCollection::collection($sizeCharts);
    }

    public function viewSizeCharts($id)
    {
        $size_chart = SizeChart::findOrFail($id);

        $measurement_options = json_decode($size_chart->measurement_option);
        $measurement_option_inch = in_array("inch", $measurement_options) ? 1 : 0;
        $measurement_option_cen = in_array("cen", $measurement_options) ? 1 : 0;

        $measurementPoints = MeasurementPoint::whereIn('id', json_decode($size_chart->measurement_points, true))->get();
        $size_options = AttributeValue::selectRaw('id,value')->whereIn('id', json_decode($size_chart->size_options, true))->get();

        $data = array();
        foreach ($size_chart->sizeChartDetails as $sizeChartDetail) {
            $data['inch'][$sizeChartDetail->measurement_point_id][$sizeChartDetail->attribute_value_id] = $sizeChartDetail->inch_value;
            $data['cen'][$sizeChartDetail->measurement_point_id][$sizeChartDetail->attribute_value_id] = $sizeChartDetail->cen_value;
        }

        $fitTypeLabels = [
            'slim_fit'    => translate('Slim Fit'),
            'regular_fit' => translate('Regular Fit'),
            'relaxed'     => translate('Relaxed'),
        ];

        $stretchTypeLabels = [
            'non'    => translate('Non'),
            'slight' => translate('Slight'),
            'medium' => translate('Medium'),
            'hign'   => translate('Hign'),
        ];

        $headers = $measurementPoints->pluck('name')->values();

        $inches = [];
        if ($measurement_option_inch) {
            foreach ($size_options as $size_option) {
                $values = [];
                foreach ($measurementPoints as $measurementPoint) {
                    $values[] = $data['inch'][$measurementPoint->id][$size_option->id] ?? null;
                }
                $inches[] = [
                    'size_options'   => $size_option->value,
                    'values' => $values,
                ];
            }
        }

        $centimeters = [];
        if ($measurement_option_cen) {
            foreach ($size_options as $size_option) {
                $values = [];
                foreach ($measurementPoints as $measurementPoint) {
                    $values[] = $data['cen'][$measurementPoint->id][$size_option->id] ?? null;
                }
                $centimeters[] = [
                    'size_options'   => $size_option->value,
                    'values' => $values,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id'           => $size_chart->id,
                'title'        => $size_chart->name,
                'fit_type'     => $fitTypeLabels[$size_chart->fit_type] ?? null,
                'stretch_type' => $stretchTypeLabels[$size_chart->stretch_type] ?? null,
                'description'  => $size_chart->description,
                'measurement_points' => $headers,
                'chart_data'   => [
                    'inches'      => $inches,
                    'centimeters' => $centimeters,
                ],
            ],
        ]);
    }

    public function assignSizeChart(Request $request)
    {
        $request->validate([
            'size_chart_id' => 'required|integer|exists:size_charts,id',
            'category_id'   => 'nullable|integer',
            'product_ids'   => 'nullable|array',
        ]);

        $size_chart = SizeChart::findOrFail($request->size_chart_id);

        if ($request->category_id) {
            $size_chart->category_id = $request->category_id;
            $size_chart->product_ids = null;
        } else {
            $size_chart->category_id = null;
            $size_chart->product_ids = json_encode($request->product_ids ?? []);
        }
        $size_chart->save();

        return response()->json([
            'status' => 'success',
            'data' => 'Size Chart Assigned Successfully.'
        ]);
    }

    public function viewAssignSizeChart($id, Request $request)
    {
        $size_chart = SizeChart::findOrFail($id);
        $category = null;
        $products = [];
        $auth_user = auth()->user();

        if ($size_chart->category_id != null) {
            $cat = Category::find($size_chart->category_id);
            if ($cat != null) {
                $category = [
                    "category_id" => $cat->id,
                    "name"        => $cat->getTranslation('name'),
                    "icon"        => $cat->icon ? uploaded_asset($cat->icon) : null,
                ];

                $products = Product::where('user_id', $auth_user->id)
                    ->where('category_id', $cat->id)
                    ->where('published', 1)
                    ->where('variant_product', 1)
                    ->where('auction_product', 0)
                    ->where('approved', 1)
                    ->select('id', 'name', 'unit_price', 'thumbnail_img')
                    ->paginate(10);

                $products->getCollection()->transform(function ($product) {
                    return [
                        "product_id"        => $product->id,
                        "product_name"      => $product->getTranslation('name'),
                        "price"             => single_price($product->unit_price),
                        "product_thumbnail" => $product->thumbnail_img ? uploaded_asset($product->thumbnail_img) : null,
                    ];
                });
            }
        } elseif ($size_chart->product_ids != null) {
            $product_ids = json_decode($size_chart->product_ids, true) ?? [];
            if (count($product_ids) > 0) {
                $products = Product::whereIn('id', $product_ids)
                    ->select('id', 'name', 'unit_price', 'thumbnail_img')
                    ->paginate(10);

                $products->getCollection()->transform(function ($product) {
                    return [
                        "product_id"        => $product->id,
                        "product_name"      => $product->getTranslation('name'),
                        "price"             => single_price($product->unit_price),
                        "product_thumbnail" => $product->thumbnail_img ? uploaded_asset($product->thumbnail_img) : null,
                    ];
                });
            }
        }

        return response()->json([
            "status" => "success",
            "data" => [
                "size_chart_id"      => $size_chart->id,
                "assigned_type"      => $size_chart->category_id ? "category" : "product",
                "assigned_category"  => $category,
                "assigned_products"  => $products,
            ],
        ]);
    }

    public function product_search_for_size_chart(Request $request)
    {
        $auth_user      = auth()->user();
        $productType    = $request->product_type ?? 'physical';
        $categoryId     = $request->category_id ?? null;
        $productId      = $request->product_id ?? null;
        $searchKey      = $request->search_key ?? null;
        $selectedIds    = $request->selected_product_ids ?? [];

        $products = Product::query();

        if ($categoryId != null) {
            $category = Category::with('childrenCategories')->find($categoryId);

            if ($category == null) {
                return response()->json([
                    "status"  => "error",
                    "message" => "Category not found",
                ], 404);
            }

            $products = $category->products();
        }

        if ($auth_user->user_type == 'seller') {
            $products = $products->where('products.user_id', $auth_user->id);
        }

        $products->where('published', '1')
            ->where('variant_product', '1')
            ->where('auction_product', 0)
            ->where('approved', '1');

        if ($productType == 'physical') {
            $products->where('digital', 0)->where('wholesale_product', 0);
        } elseif ($productType == 'digital') {
            $products->where('digital', 1);
        } elseif ($productType == 'wholesale') {
            $products->where('wholesale_product', 1);
        } elseif ($productType == 'physical_digital') {
            $products->where('wholesale_product', 0);
        }

        if ($productId != null) {
            $products->where('id', '!=', $productId);
        }

        if (!empty($selectedIds)) {
            $products->whereIn('id', (array) $selectedIds);
        }

        if ($searchKey != null) {
            $products->where('name', 'like', '%' . $searchKey . '%');
        }

        $result = $products->select('id', 'name', 'thumbnail_img', 'unit_price', 'slug')
            ->get()
            ->map(function ($product) {
                return [
                    "product_id"        => $product->id,
                    "product_name"      => $product->getTranslation('name'),
                    "slug"              => $product->slug,
                    "product_thumbnail" => $product->thumbnail_img ? uploaded_asset($product->thumbnail_img) : null,
                    "price"             => single_price($product->unit_price),
                ];
            });

        return response()->json([
            "status" => "success",
            "data"   => $result,
        ]);
    }
}
