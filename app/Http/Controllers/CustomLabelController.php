<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CustomLabel;
use App\Models\CustomLabelTranslation;
use App\Models\Product;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Redirect;

class CustomLabelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_custom_label'])->only('index');
        $this->middleware(['permission:custom_label_create'])->only('create');
        $this->middleware(['permission:custom_label_create'])->only('store');
        $this->middleware(['permission:custom_label_edit'])->only('edit');
        $this->middleware(['permission:custom_label_edit'])->only('update');
        $this->middleware(['permission:custom_label_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $custom_label_tabs = ['All Custom Labels', 'In House', 'Seller'];
        $lockedIds = [200, 201, 202, 203];

        $custom_labels = CustomLabel::where(function ($query) {
                $query->whereHas('user')
                    ->orWhere('user_id', 0);
            })
            ->orderByRaw('CASE WHEN id IN (' . implode(',', $lockedIds) . ') THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc');


        if ($request->has('search')) {
            $sort_search = $request->search;
            $custom_labels->where('text', 'like', '%' . $sort_search . '%');
        }

        if (!addon_is_activated('wholesale')) {
            $custom_labels->where('id', '!=', 203);
        }

        $custom_labels = $custom_labels->paginate(15);

        return view('backend.product.custom_label.custom_label_list', compact('custom_labels', 'sort_search', 'custom_label_tabs'));
    }

    public function filter(Request $request)
    {
        $lockedIds = [200, 201, 202, 203];

        $custom_labels = CustomLabel::where(function ($query) {
                $query->whereHas('user')
                    ->orWhere('user_id', 0);
            })
            ->orderByRaw('CASE WHEN id IN (' . implode(',', $lockedIds) . ') THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc');

        $sort_search = null;

        $adminId = get_admin()->id;
        if ($request->custom_label_status == "in_house") {

            $custom_labels->where(function ($query) use ($adminId) {
                $query->where('user_id', $adminId)
                    ->orWhere('user_id', 0);
            });

        } elseif ($request->custom_label_status == 'seller') {

            $custom_labels->where('user_id', '!=', $adminId)
                        ->where('user_id', '!=', 0);
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $custom_labels->where('text', 'like', '%' . $sort_search . '%');
        }

        if (!addon_is_activated('wholesale')) {
            $custom_labels->where('id', '!=', 203);
        }

        $custom_labels = $custom_labels->paginate(15);
        $view = view(
            'backend.product.custom_label.partials.table',
            compact('custom_labels', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        $products = Product::isApprovedPublished()->where('auction_product', 0)->orderBy('created_at', 'desc')->get();
        return view('backend.product.custom_label.custom_label_create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $validator = Validator::make($request->all(), [
            'text'             => 'required',
            'background_color' => 'required',
            'text_color'       => 'required',       
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $custom_label = new CustomLabel();
        $custom_label->text             = $request->text;
        $custom_label->background_color = $request->background_color;
        $custom_label->text_color       = $request->text_color;
        $custom_label->user_id          = $user_id;
        $custom_label->save();

        $custom_label_translation = CustomLabelTranslation::firstOrNew([
            'lang' => env('DEFAULT_LANGUAGE'),
            'custom_label_id' => $custom_label->id
        ]);
        $custom_label_translation->text = $request->text;
        $custom_label_translation->save();

        if ($request->has('products') && count($request->products) > 0) {
            Product::whereIn('id', $request->products)
                ->update(['custom_label_id' => $custom_label->id]);
        }

        flash(translate('Custom Label has been created successfully!'))->success();
        return redirect()->route('custom_label.index');
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang ?? env('DEFAULT_LANGUAGE');
        $custom_label = CustomLabel::findOrFail($id);

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        $selected_products_ids = Product::whereRaw("FIND_IN_SET(?, custom_label_id)", [$custom_label->id])
            ->pluck('id')
            ->toArray();

        $all_products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $selected_products = $all_products->whereIn('id', $selected_products_ids);
        $unselected_products = $all_products->whereNotIn('id', $selected_products_ids);

        $products = $selected_products->merge($unselected_products);

        return view('backend.product.custom_label.custom_label_edit', compact('custom_label', 'lang', 'products', 'selected_products_ids', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text'             => 'required',
            'background_color' => 'required',
            'text_color'       => 'required',
        ]);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $custom_label = CustomLabel::findOrFail($id);
        $isProtectedLabel = $request->lang == 'en' && in_array($custom_label->id, [200, 201, 202, 203]);

        if (!$isProtectedLabel && $request->lang == env('DEFAULT_LANGUAGE')) {
            $custom_label->text = $request->text;
        }

        $custom_label->background_color = $request->background_color;
        $custom_label->text_color = $request->text_color;
        $custom_label->save();

        if (!$isProtectedLabel) {
            $custom_label_translation = CustomLabelTranslation::firstOrNew([
                'lang' => $request->lang,
                'custom_label_id' => $custom_label->id
            ]);

            $custom_label_translation->text = $request->text;
            $custom_label_translation->save();
        }

        $selected_product_ids = $request->has('products') ? $request->products : [];
        if (count($selected_product_ids) > 0) {
            Product::whereIn('id', $selected_product_ids)->get()->each(function ($product) use ($custom_label) {
                $labels = $product->custom_label_id ? array_filter(array_map('trim', explode(',', $product->custom_label_id))) : [];
                $labelId = (string) $custom_label->id;
                if (!in_array($labelId, $labels)) {
                    $labels[] = $labelId;
                    $product->custom_label_id = implode(',', array_values(array_unique($labels)));
                    $product->save();
                }
            });
            Product::whereRaw("FIND_IN_SET(?, custom_label_id)", [$custom_label->id])
                ->whereNotIn('id', $selected_product_ids)
                ->get()
                ->each(function ($product) use ($custom_label) {
                    $labels = $product->custom_label_id ? array_filter(array_map('trim', explode(',', $product->custom_label_id))) : [];
                    $labels = array_diff($labels, [(string) $custom_label->id]);
                    $labels = array_values($labels);
                    $product->custom_label_id = count($labels) ? implode(',', $labels) : null;
                    $product->save();
                });
        } else {
            Product::whereRaw("FIND_IN_SET(?, custom_label_id)", [$custom_label->id])
                ->get()
                ->each(function ($product) use ($custom_label) {
                    $labels = $product->custom_label_id ? array_filter(array_map('trim', explode(',', $product->custom_label_id))) : [];
                    $labels = array_diff($labels, [(string) $custom_label->id]);
                    $labels = array_values($labels);
                    $product->custom_label_id = count($labels) ? implode(',', $labels) : null;
                    $product->save();
                });
        }
        Artisan::call('cache:clear');
        flash(translate('Label has been updated successfully!'))->success();
        return redirect()->route('custom_label.index');
    }


    public function destroy($id)
    {
        $custom_label = CustomLabel::findOrFail($id);
        $selected_products_ids = Product::whereRaw("FIND_IN_SET(?, custom_label_id)", [$custom_label->id])
            ->pluck('id')
            ->toArray();
        Product::whereIn('id', $selected_products_ids)
            ->get()
            ->each(function ($product) use ($custom_label) {
                $labels = $product->custom_label_id ? array_filter(array_map('trim', explode(',', $product->custom_label_id))) : [];
                $labels = array_diff($labels, [(string) $custom_label->id]);
                $labels = array_values($labels);
                $product->custom_label_id = count($labels) ? implode(',', $labels) : null;
                $product->save();
            });
        $custom_label->delete();
        Artisan::call('cache:clear');
        return 1;
    }

    public function bulk_custom_label_delete(Request $request) 
    {
        if($request->id) {
            foreach ($request->id as $custom_label_id) {
                $custom_label = CustomLabel::findOrFail($custom_label_id);
                if (!$custom_label) {
                    continue;
                }
                $selected_products_ids = Product::whereRaw("FIND_IN_SET(?, custom_label_id)", [$custom_label->id])
                    ->pluck('id')
                    ->toArray();
                Product::whereIn('id', $selected_products_ids)
                    ->get()
                    ->each(function ($product) use ($custom_label) {
                        $labels = $product->custom_label_id ? array_filter(array_map('trim', explode(',', $product->custom_label_id))) : [];
                        $labels = array_diff($labels, [(string) $custom_label->id]);
                        $labels = array_values($labels);
                        $product->custom_label_id = count($labels) ? implode(',', $labels) : null;
                        $product->save();
                    });
                $custom_label->delete();
            }
            return 1;
        }
    }

    public function updateSellerAccess(Request $request)
    {
        $custom_label = CustomLabel::findOrFail($request->id);
        $custom_label->seller_access = $request->status;
        $custom_label->save();
        return 1;
    }

    public function update_status(Request $request)
    {
        $custom_label = CustomLabel::findOrFail($request->id);
        $custom_label->seller_access = $request->seller_access;
        if ($custom_label->save()) {
            return 1;
        }
        return 0;
    }

    public function status_update(Request $request)
    {
        $custom_label = CustomLabel::findOrFail($request->id);
        $custom_label->status = $request->status;
        
        if ($custom_label->save()) {
            return 1;
        }
        return 0;
    }

    public function custom_label_product_search(Request $request)
    {
        if (!$request->category && !$request->search_key) {
            return view('backend.product.custom_label.custom_label_product_list', ['products' => collect()]);
        }

        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)

            ->when($request->category, function ($q) use ($request) {
                $q->whereHas('product_categories', function ($q2) use ($request) {
                    $q2->where('category_id', $request->category);
                });
            })
            ->when($request->search_key, function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search_key . '%')
                    ->orWhereHas('product_translations', function ($q3) use ($request) {
                        $q3->where('name', 'like', '%' . $request->search_key . '%');
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.product.custom_label.custom_label_product_list', compact('products'));
    }

    public function product_add(Request $request)
    {
        $product_ids = $request->product_ids;
        return view('backend.product.custom_label.products', compact('product_ids'));
    }

    public function product_edit(Request $request)
    {
        $product_ids = $request->product_ids;
        $custom_label_id = $request->custom_label_id;
        return view('backend.product.custom_label.products_edit', compact('product_ids', 'custom_label_id'));
    }

}
