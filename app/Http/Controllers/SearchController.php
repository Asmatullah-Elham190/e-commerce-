<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Shop;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Models\PreorderProduct;
use App\Models\PreorderProductCategory;
use App\Models\ProductCategory;
use App\Utility\CategoryUtility;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function index(Request $request, $category_id = null, $brand_id = null)
    {
        // dd( $category_id);
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $product_type = $request->product_type ?? 'general_product';
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $selected_attribute_values = array();
        $is_available = array();
        $selected_color = null;
        $category = [];
        $categories = [];
        $brand = null;

        $conditions = [];

        $attributes = Attribute::with('attribute_values')->get();

        foreach ($attributes as $attribute) {
            $productsQuery = Product::whereJsonContains('attributes', (string) $attribute->id);
            $productsQuery = filter_products($productsQuery);
            $filteredProducts = $productsQuery->get();

            $attribute->product_count = $filteredProducts->count();

            foreach ($attribute->attribute_values as $value) {
                $valueQuery = Product::query()
                    ->where('choice_options', 'like', '%"attribute_id":"' . $attribute->id . '"%')
                    ->where('choice_options', 'like', '%"' . $value->value . '"%');

                $valueQuery = filter_products($valueQuery);

                $value->product_count = $valueQuery->count();
            }
        }


        $colors = Color::all();
        foreach ($colors as $color) {
            // $color->product_count = Product::where('colors', 'like', '%' . $color->code . '%')
            // ->count();
            $productsColor =  Product::where('colors', 'like', '%' . $color->code . '%');
            $productsColor = filter_products($productsColor);
            $color->product_count = $productsColor->count();
        }

        // return $colors;


        if (addon_is_activated('preorder') && $request->product_type == 'preorder_product') {
            $products = PreorderProduct::where('is_published', 1);
            $products = filter_preorder_product($products);
            if ($category_id != null) {
                $category_ids[] = $category_id;
                $category = Category::with('childrenCategories')->find($category_id);

                $products = $category->preorderProducts();
            } else {
                $categories = Category::with('childrenCategories', 'coverImage')->where('level', 0)->orderBy('order_level', 'desc')->get();
            }

            if ($request->has('is_available') && $request->is_available !== null) {
                $availability = $request->is_available;
                $currentDate = Carbon::now()->format('Y-m-d');

                $products->where(function ($query) use ($availability, $currentDate) {
                    if ($availability == 1) {
                        $query->where('is_available', 1)->orWhere('available_date', '<=', $currentDate);
                    } else {
                        $query->where(function ($query) {
                            $query->where('is_available', '!=', 1)
                                ->orWhereNull('is_available');
                        })
                            ->where(function ($query) use ($currentDate) {
                                $query->whereNull('available_date')
                                    ->orWhere('available_date', '>', $currentDate);
                            });
                    }
                });

                $is_available = $availability;
            } else {
                $is_available = null;
            }

            if ($min_price != null && $max_price != null) {
                $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
            }

            if ($query != null) {

                $products->where(function ($q) use ($query) {
                    foreach (explode(' ', trim($query)) as $word) {
                        $q->where('product_name', 'like', '%' . $word . '%')
                            ->orWhere('tags', 'like', '%' . $word . '%')
                            ->orWhereHas('preorder_product_translations', function ($q) use ($word) {
                                $q->where('product_name', 'like', '%' . $word . '%');
                            });
                    }
                });

                $case1 = $query . '%';
                $case2 = '%' . $query . '%';

                $products->orderByRaw('CASE
                    WHEN product_name LIKE "' . $case1 . '" THEN 1
                    WHEN product_name LIKE "' . $case2 . '" THEN 2
                    ELSE 3
                    END');
            }

            switch ($sort_by) {
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'price-asc':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case 'price-desc':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    $products->orderBy('id', 'desc');
                    break;
            }
            $products = $products->with('taxes')->paginate(12, ['*'], 'preorder_product')->appends(request()->query());
            return view('frontend.product_listing', compact('products', 'query', 'category', 'categories', 'category_id', 'brand_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color', 'product_type', 'is_available'));
        }


        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
            $brand = Brand::find($brand_id);
        } elseif ($request->brand != null) {
            $brand = Brand::where('slug', $request->brand)->first();
            $brand_id = ($brand != null) ? $brand->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products = Product::where($conditions);

        // return "working";

        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;
            $category = Category::with('childrenCategories')->find($category_id);
            $products = $category->products();

        }
        //------------------- category product count start here ----------------------

        $filteredProductIds = filter_products(Product::query())->pluck('id');

        $productCountsSubCategory = ProductCategory::select('category_id')
            ->selectRaw('COUNT(product_id) as count')
            ->whereIn('product_id', $filteredProductIds)
            ->groupBy('category_id')
            ->pluck('count', 'category_id');


        $allCategories = Category::with('childrenCategories', 'coverImage')
            ->orderBy('order_level', 'desc')
            ->where('level', 0)
            ->get();

        foreach ($allCategories as $category1) {
            $this->categoryProductCount($category1, $productCountsSubCategory);
        }

        $categories = $allCategories;
        // return $categories;
        
       $preorder_categories=[];
       if (addon_is_activated('preorder')) {
            // ################# preorder category start here #################

            $preorder_products = PreorderProduct::where('is_published', 1);
            $preorder_products_ids = filter_preorder_product($preorder_products)->pluck('id');


            //    return $preorder_products_ids;

            $preorder_productCountsSubCategory = PreorderProductCategory::select('category_id')
                ->selectRaw('COUNT(preorder_product_id) as count')
                ->whereIn('preorder_product_id', $preorder_products_ids)
                ->groupBy('category_id')
                ->pluck('count', 'category_id');
            // return $preorder_productCountsSubCategory;

            $preorder_allCategories = Category::with('childrenCategories', 'coverImage')
                ->orderBy('order_level', 'desc')
                ->where('level', 0)
                ->get();

            foreach ($preorder_allCategories as $category1) {
                $this->categoryProductCount($category1, $preorder_productCountsSubCategory);
            }

            $preorder_categories = $preorder_allCategories;

            // return $preorder_categories;

            // preorder category end here ----------
        }
        //################# category product count end here #################


        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });

            $case1 = $query . '%';
            $case2 = '%' . $query . '%';

            $products->orderByRaw('CASE
                WHEN name LIKE "' . $case1 . '" THEN 1
                WHEN name LIKE "' . $case2 . '" THEN 2
                ELSE 3
                END');
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }
        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = $request->selected_attribute_values;
            $products->where(function ($query) use ($selected_attribute_values) {
                foreach ($selected_attribute_values as $key => $value) {
                    $str = '"' . $value . '"';

                    $query->orWhere('choice_options', 'like', '%' . $str . '%');
                }
            });
        }

        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        // return $brand_id;
        return view('frontend.product_listing', compact('products', 'query', 'category', 'categories', 'category_id', 'brand_id', 'brand', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color', 'product_type', 'is_available', 'preorder_categories'));
    }

    public function index2(Request $request, $category_id = null, $brand_id = null)
    {
        // dd($request->all());
        // return $request->all();
        $category_list = $request->categories ?? [];
        $category_ids = array_map(function ($str) {
            preg_match('/\d+/', $str, $matches);
            return isset($matches[0]) ? (int)$matches[0] : null;
        }, $category_list);
        $category_list = array_filter($category_ids, fn($v) => $v !== null);

        $category_list_preorder = $request->categories_preorder ?? [];
        $category_ids2 = array_map(function ($str) {
            preg_match('/\d+/', $str, $matches);
            return isset($matches[0]) ? (int)$matches[0] : null;
        }, $category_list_preorder);
        $category_list_preorder = array_filter($category_ids2, fn($v) => $v !== null);

        if ($request->has('brand_id')) {
            $brand_id = $request->brand_id;
        }
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $product_type = $request->product_type ?? 'general_product';
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $selected_attribute_values = array();
        $is_available = array();
        $selected_color = null;
        $category = [];
        $categories = [];

        $conditions = [];

        $attributes = Attribute::with('attribute_values')->get();

        foreach ($attributes as $attribute) {

            $attribute->product_count = Product::whereJsonContains('attributes',  (string) $attribute->id)->count();

            foreach ($attribute->attribute_values as $value) {
                $value->product_count = Product::where('choice_options', 'like', '%"attribute_id":"' . $attribute->id . '"%')
                    ->where('choice_options', 'like', '%"' . $value->value . '"%')
                    ->count();
            }
        }
        $colors = Color::all();
        foreach ($colors as $color) {
            $color->product_count = Product::where('colors', 'like', '%' . $color->code . '%')
                ->count();
        }

        // return $colors;
        if (addon_is_activated('preorder') && $request->product_type == 'preorder_product') {
            $products = PreorderProduct::where('is_published', 1);

            if (count($category_list_preorder) > 0) {
                $products_ids = PreorderProductCategory::whereIn('category_id', $category_list_preorder)->pluck('preorder_product_id')->toArray();;

                $products->whereIn('id', $products_ids);
            }
            $products = filter_preorder_product($products);

            if ($category_id != null) {
                $category_ids[] = $category_id;
                $category = Category::with('childrenCategories')->find($category_id);

                $products = $category->preorderProducts();
            } else {
                $categories = Category::with('childrenCategories', 'coverImage')->where('level', 0)->orderBy('order_level', 'desc')->get();
            }

            if ($request->has('is_available') && $request->is_available !== null) {
                $availability = $request->is_available;
                $currentDate = Carbon::now()->format('Y-m-d');

                $products->where(function ($query) use ($availability, $currentDate) {
                    if ($availability == 1) {
                        $query->where('is_available', 1)->orWhere('available_date', '<=', $currentDate);
                    } else {
                        $query->where(function ($query) {
                            $query->where('is_available', '!=', 1)
                                ->orWhereNull('is_available');
                        })
                            ->where(function ($query) use ($currentDate) {
                                $query->whereNull('available_date')
                                    ->orWhere('available_date', '>', $currentDate);
                            });
                    }
                });

                $is_available = $availability;
            } else {
                $is_available = null;
            }

            if ($min_price != null && $max_price != null) {
                $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
            }

            if ($query != null) {

                $products->where(function ($q) use ($query) {
                    foreach (explode(' ', trim($query)) as $word) {
                        $q->where('product_name', 'like', '%' . $word . '%')
                            ->orWhere('tags', 'like', '%' . $word . '%')
                            ->orWhereHas('preorder_product_translations', function ($q) use ($word) {
                                $q->where('product_name', 'like', '%' . $word . '%');
                            });
                    }
                });

                $case1 = $query . '%';
                $case2 = '%' . $query . '%';

                $products->orderByRaw('CASE
                    WHEN product_name LIKE "' . $case1 . '" THEN 1
                    WHEN product_name LIKE "' . $case2 . '" THEN 2
                    ELSE 3
                    END');
            }

            switch ($sort_by) {
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'price-asc':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case 'price-desc':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    $products->orderBy('id', 'desc');
                    break;
            }


            if ($request->has('colors') && is_array($request->colors)) {
                $colors = $request->colors;

                $products->where(function ($query) use ($colors) {
                    foreach ($colors as $color) {
                        $str = '"' . $color . '"';
                        $query->orWhere('colors', 'like', '%' . $str . '%');
                    }
                });
            }

            if ($request->has('selected_attribute_values')) {
                $selected_attribute_values = $request->selected_attribute_values;
                $products->where(function ($query) use ($selected_attribute_values) {
                    foreach ($selected_attribute_values as $key => $value) {
                        $str = '"' . $value . '"';

                        $query->orWhere('choice_options', 'like', '%' . $str . '%');
                    }
                });
            }


            $products = $products->with('taxes')->paginate(12)->appends(request()->query());

            $product_type = "preorder_product";
            $product_html =  view('frontend.product_listing_products', compact('products', 'product_type'))->render();

            $pagination_html = view('frontend.product_listing_pagination', [
                'current' => $products->currentPage(),
                'last' => $products->lastPage()
            ])->render();


            return response()->json([
                'success' => true,
                'total_product_count' => $products->total(),
                'product_html' => $product_html,
                'pagination_html' => $pagination_html,
            ]);
        }


        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        $products = Product::where($conditions);

        if (count($category_list) > 0) {
            $products_ids = ProductCategory::whereIn('category_id', $category_list)->pluck('product_id')->toArray();

            $products = $products->whereIn('id', $products_ids);
        }



        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });

            $case1 = $query . '%';
            $case2 = '%' . $query . '%';

            $products->orderByRaw('CASE
                WHEN name LIKE "' . $case1 . '" THEN 1
                WHEN name LIKE "' . $case2 . '" THEN 2
                ELSE 3
                END');
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }

        if ($request->has('colors') && is_array($request->colors)) {
            $colors = $request->colors;

            $products->where(function ($query) use ($colors) {
                foreach ($colors as $color) {
                    $str = '"' . $color . '"';
                    $query->orWhere('colors', 'like', '%' . $str . '%');
                }
            });
        }

        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = $request->selected_attribute_values;
            $products->where(function ($query) use ($selected_attribute_values) {
                foreach ($selected_attribute_values as $key => $value) {
                    $str = '"' . $value . '"';

                    $query->orWhere('choice_options', 'like', '%' . $str . '%');
                }
            });
        }

        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());

        $product_html =  view('frontend.product_listing_products', compact('products'))->render();
        $pagination_html = view('frontend.product_listing_pagination', [
            'current' => $products->currentPage(),
            'last' => $products->lastPage()
        ])->render();

        return response()->json([
            'success' => true,
            'total_product_count' => $products->total(),
            'product_html' => $product_html,
            'pagination_html' => $pagination_html,
        ]);
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id);
        }
        abort(404);
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = array();
        $query = $request->search;
        $preorder_products = null;
        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products_query = filter_products(Product::query());

        $products_query = $products_query->where('published', 1)
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });
        $case1 = $query . '%';
        $case2 = '%' . $query . '%';

        $products_query->orderByRaw('CASE
                WHEN name LIKE "' . $case1 . '" THEN 1
                WHEN name LIKE "' . $case2 . '" THEN 2
                ELSE 3
                END');
        $products = $products_query->limit(3)->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (addon_is_activated('preorder')) {
            $preorder_products =  PreorderProduct::where('is_published', 1)
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('product_name', 'like', '%' . $query . '%')
                        ->orWhere('tags', 'like', '%' . $query . '%');
                })
                ->where(function ($query) {
                    $query->whereHas('user', function ($q) {
                        $q->where('user_type', 'admin');
                    })->orWhereHas('user.shop', function ($q) {
                        $q->where('verification_status', 1);
                    });
                })
                ->limit(3)
                ->get();
        }

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0  || sizeof($preorder_products) > 0) {
            return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops', 'preorder_products'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = Search::where('query', $request->keyword)->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else {
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }

    public function categoryProductCount($category, $productCounts, $childrenKey = 'childrenCategories')
    {

        $category->products_count = $productCounts[$category->id] ?? 0;

        // If children exist, loop recursively
        if (!empty($category->{$childrenKey})) {
            foreach ($category->{$childrenKey} as $child) {
                $this->categoryProductCount($child, $productCounts, $childrenKey);
            }
        }
    }
}
