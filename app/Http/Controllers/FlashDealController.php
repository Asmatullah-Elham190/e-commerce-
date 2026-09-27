<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\FlashDeal;
use App\Models\FlashDealTranslation;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class FlashDealController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_flash_deals'])->only('index');
        $this->middleware(['permission:add_flash_deal'])->only('create');
        $this->middleware(['permission:edit_flash_deal'])->only('edit');
        $this->middleware(['permission:delete_flash_deal'])->only('destroy');
        $this->middleware(['permission:publish_flash_deal'])->only('update_featured');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $flash_deal_tabs = ['All Flash Deals', 'Active', 'Inactive'];
        $flash_deals = FlashDeal::orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $flash_deals = $flash_deals->where('title', 'like', '%' . $sort_search . '%');
        }
        $flash_deals = $flash_deals->paginate(15);
        return view('backend.marketing.flash_deals.index', compact('flash_deals', 'sort_search', 'flash_deal_tabs'));
    }

    public function create()
    {
        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->where('promotional', 1)
            ->with(['product_categories'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        $sellers = User::where('user_type', 'seller')->get();

        return view('backend.marketing.flash_deals.create', compact('products', 'categories', 'sellers'));
    }

    public function store(Request $request)
    {
        $flash_deal = new FlashDeal;
        $flash_deal->title = $request->title;
        $flash_deal->text_color = $request->text_color;
        $flash_deal->background_color = $request->background_color;
        $flash_deal->slug = Str::slug($request->title) . '-' . Str::random(5);
        $flash_deal->banner = $request->thumbnail_img;

        $date_var = explode(" to ", $request->date_range);
        $flash_deal->start_date = strtotime($date_var[0]);
        $flash_deal->end_date   = strtotime($date_var[1]);

        if ($flash_deal->save()) {

            if ($request->products) {
                foreach ($request->products as $product_id) {
                    $flash_deal_product = new FlashDealProduct;
                    $flash_deal_product->flash_deal_id = $flash_deal->id;
                    $flash_deal_product->product_id = $product_id;
                    $flash_deal_product->save();

                    $root_product = Product::findOrFail($product_id);
                    $root_product->discount           = $request['discount_' . $product_id] ?? 0;
                    $root_product->discount_type      = $request['discount_type_' . $product_id] ?? 'percent';
                    $root_product->discount_start_date = strtotime($date_var[0]);
                    $root_product->discount_end_date   = strtotime($date_var[1]);
                    $root_product->save();
                }
            }

            $flash_deal_translation = FlashDealTranslation::firstOrNew([
                'lang' => env('DEFAULT_LANGUAGE'),
                'flash_deal_id' => $flash_deal->id
            ]);
            $flash_deal_translation->title = $request->title;
            $flash_deal_translation->save();

            flash(translate('Flash Deal has been inserted successfully'))->success();
            return redirect()->route('flash_deals.index');
        }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function show($id) {}

    public function edit(Request $request, $id)
    {
        $lang           = $request->lang;
        $flash_deal = FlashDeal::findOrFail($id);

        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->where('promotional', 1)
            ->with(['product_categories'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        
        $sellers = User::where('user_type', 'seller')->get();

        return view('backend.marketing.flash_deals.edit', compact('flash_deal', 'lang', 'products', 'categories', 'sellers'));
    }

    public function update(Request $request, $id)
    {
        $flash_deal = FlashDeal::findOrFail($id);

        $flash_deal->text_color = $request->text_color;

        $date_var               = explode(" to ", $request->date_range);
        $flash_deal->start_date = strtotime($date_var[0]);
        $flash_deal->end_date   = strtotime($date_var[1]);

        $flash_deal->background_color = $request->background_color;

        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $flash_deal->title = $request->title;
            if (($flash_deal->slug == null) || ($flash_deal->title != $request->title)) {
                $flash_deal->slug = strtolower(str_replace(' ', '-', $request->title) . '-' . Str::random(5));
            }
        }

        $flash_deal->banner = $request->banner;
        foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product) {
            $prev_product = Product::findOrFail($flash_deal_product->product_id);
            $prev_product->discount = 0.00;
            $prev_product->discount_type = 'amount';
            $prev_product->discount_start_date = null;
            $prev_product->discount_end_date   = null;
            $prev_product->save();

            $flash_deal_product->delete();
        }
        if ($flash_deal->save()) {
            if ($request->products) {
                foreach ($request->products as $key => $product) {
                    $flash_deal_product = new FlashDealProduct;
                    $flash_deal_product->flash_deal_id = $flash_deal->id;
                    $flash_deal_product->product_id = $product;
                    $flash_deal_product->save();

                    $root_product = Product::findOrFail($product);
                    $root_product->discount = $request['discount_' . $product];
                    $root_product->discount_type = $request['discount_type_' . $product];
                    $root_product->discount_start_date = strtotime($date_var[0]);
                    $root_product->discount_end_date   = strtotime($date_var[1]);
                    $root_product->save();
                }
            }

            $sub_category_translation = FlashDealTranslation::firstOrNew(['lang' => $request->lang, 'flash_deal_id' => $flash_deal->id]);
            $sub_category_translation->title = $request->title;
            $sub_category_translation->save();

            flash(translate('Flash Deal has been updated successfully'))->success();
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function destroy($id)
    {
        $flash_deal = FlashDeal::findOrFail($id);

        foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product) {
            $root_product = Product::findOrFail($flash_deal_product->product_id);
            $root_product->discount = 0.00;
            $root_product->discount_type = 'amount';
            $root_product->discount_start_date = null;
            $root_product->discount_end_date   = null;
            $root_product->save();

            $flash_deal_product->delete();
        }

        $flash_deal->flash_deal_translations()->delete();

        FlashDeal::destroy($id);
        flash(translate('FlashDeal has been deleted successfully'))->success();
        return 1;
    }

    public function bulk_flash_deal_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $flash_deal_id) {
                $flash_deal = FlashDeal::findOrFail($flash_deal_id);

                foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product) {
                    $root_product = Product::findOrFail($flash_deal_product->product_id);
                    $root_product->discount = 0.00;
                    $root_product->discount_type = 'amount';
                    $root_product->discount_start_date = null;
                    $root_product->discount_end_date   = null;
                    $root_product->save();

                    $flash_deal_product->delete();
                }

                $flash_deal->flash_deal_translations()->delete();

                FlashDeal::destroy($flash_deal_id);
                flash(translate('Flash Deals has been deleted successfully'))->success();
            }
            return 1;
        }
    }

    public function update_status(Request $request)
    {
        $flash_deal = FlashDeal::findOrFail($request->id);
        $flash_deal->status = $request->status;
        if ($flash_deal->save()) {
            flash(translate('Flash deal status updated successfully'))->success();
            return 1;
        }
        return 0;
    }

    public function update_featured(Request $request)
    {
        foreach (FlashDeal::all() as $key => $flash_deal) {
            $flash_deal->featured = 0;
            $flash_deal->save();
        }
        $flash_deal = FlashDeal::findOrFail($request->id);
        $flash_deal->featured = $request->featured;
        if ($flash_deal->save()) {
            flash(translate('Flash deal status updated successfully'))->success();
            return 1;
        }
        return 0;
    }

    public function product_discount(Request $request)
    {
        $product_ids = $request->product_ids;
        return view('backend.marketing.flash_deals.flash_deal_discount', compact('product_ids'));
    }

    public function product_discount_edit(Request $request)
    {
        $product_ids = $request->product_ids;
        $flash_deal_id = $request->flash_deal_id;
        return view('backend.marketing.flash_deals.flash_deal_discount_edit', compact('product_ids', 'flash_deal_id'));
    }

    public function filter_flash_deal(Request $request)
    {
        $flash_deals = FlashDeal::orderBy('created_at', 'desc');
        $sort_search = null;

        if ($request->flash_deal_status == "active") {
            $flash_deals = $flash_deals->where('status', 1);
        } else if ($request->flash_deal_status == 'inactive') {
            $flash_deals = $flash_deals->where('status', 0);
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $flash_deals = $flash_deals->where('title', 'like', '%' . $sort_search . '%');
        }

        $flash_deals = $flash_deals->paginate(15);
        $view = view(
            'backend.marketing.flash_deals.table',
            compact('flash_deals', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function flash_deal_product_search(Request $request)
    {
        if (!$request->category && !$request->seller && !$request->search_key) {
            return view('backend.marketing.flash_deals.flash_deal_product_list', ['products' => collect()]);
        }

        $now = now()->timestamp; 

        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->where('promotional', 1)
            ->where(function ($q) use ($now) {
                $q->where('discount', 0)
                ->orWhere(function ($q2) use ($now) {
                    $q2->where('discount', '>', 0)
                        ->where('discount_end_date', '<', $now);
                });
            })
            
            ->whereNotIn('id', function ($q) use ($now) {
                $q->select('product_id')
                ->from('flash_deal_products')
                ->join('flash_deals', 'flash_deals.id', '=', 'flash_deal_products.flash_deal_id')
                ->where('flash_deals.end_date', '>', $now)
                ->where('flash_deal_products.discount', '<=', 0);
            })

            ->when($request->category, function ($q) use ($request) {
                $q->whereHas('product_categories', function ($q2) use ($request) {
                    $q2->where('category_id', $request->category);
                });
            })

            // Seller filter
            ->when($request->seller, function ($q) use ($request) {
                $q->where('added_by', 'seller')
                ->where('user_id', $request->seller);
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

        return view('backend.marketing.flash_deals.flash_deal_product_list', compact('products'));
    }

    public function update_product_discount(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->discount      = $request->discount ?? 0;
        $product->discount_type = $request->discount_type ?? 'percent';

        if ($request->flash_deal_id) {
            $flash_deal = FlashDeal::find($request->flash_deal_id);
            if ($flash_deal) {
                $product->discount_start_date = $flash_deal->start_date;
                $product->discount_end_date   = $flash_deal->end_date;
            }
        }

        if ($product->save()) {
            return response()->json(['success' => true, 'message' => translate('Discount updated')]);
        }

        return response()->json(['success' => false, 'message' => translate('Something went wrong')], 500);
    }

    public function admin_ajax_add_flash_sale_modal(Request $request)
    {
        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->where('promotional', 1)
            ->with(['product_categories'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.marketing.flash_deals.ajax_add_flash_sale_modal',
        [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function admin_ajax_add_flash_sale_store(Request $request)
    {
        $flash_deal = new FlashDeal;
        $flash_deal->title = $request->title;
        $flash_deal->slug = Str::slug($request->title) . '-' . Str::random(5);
        $flash_deal->banner = $request->fs_thumbnail_img;

        $date_var = explode(" to ", $request->date_range);
        $flash_deal->start_date = strtotime($date_var[0]);
        $flash_deal->end_date   = strtotime($date_var[1]);

        $flash_deal->save();

        $flash_deal_translation = FlashDealTranslation::firstOrNew([
            'lang' => env('DEFAULT_LANGUAGE'),
            'flash_deal_id' => $flash_deal->id
        ]);

        $flash_deal_translation->title = $request->title;
        $flash_deal_translation->save();

        return response()->json([
            'success'        => true,
            'message'        => translate('Flash Deal has been inserted successfully'),
            'flash_sale_id'    => $flash_deal->id,
            'flash_sale_title'  => $flash_deal->title,
        ]);

    }
}
