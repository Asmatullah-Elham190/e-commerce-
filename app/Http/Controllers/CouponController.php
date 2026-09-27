<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_coupons'])->only('index');
        $this->middleware(['permission:add_coupon'])->only('create');
        $this->middleware(['permission:edit_coupon'])->only('edit');
        $this->middleware(['permission:delete_coupon'])->only('destroy');
    }

    public function index(Request $request)
    {
        $sort_search = null;
        $coupon_tabs = ['All Coupons', 'Admin Coupons', 'Seller Coupons'];
        $coupons = Coupon::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $coupons = $coupons->where('code', 'like', '%' . $sort_search . '%');
        }
        $coupons = $coupons->paginate(15);
        return view('backend.marketing.coupons.index', compact('coupons', 'sort_search', 'coupon_tabs'));
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

        return view('backend.marketing.coupons.create', compact('categories', 'products'));
    }

    public function store(CouponRequest $request)
    {
        $user_id = get_admin()->id;
        $status = $request->type == 'welcome_base' ? 0 : 1;
        Coupon::create($request->validated() + [
            'user_id' => $user_id,
            'status' => $status,
        ]);
        flash(translate('Coupon has been saved successfully'))->success();
        return redirect()->route('coupon.index');
    }

    public function show($id) {}

    public function edit($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));
        $categories = Category::where('parent_id', 0)->with('childrenCategories')->get();
        return view('backend.marketing.coupons.edit', compact('coupon', 'categories'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());
        flash(translate('Coupon has been updated successfully'))->success();
        return redirect()->route('coupon.index');
    }

    public function destroy($id)
    {
        Coupon::destroy($id);
        flash(translate('Coupon has been deleted successfully'))->success();
        return 1;
    }

    public function bulk_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $coupon_id) {
                Coupon::destroy($coupon_id);
                flash(translate('Coupon has been deleted successfully'))->success();
            }
        }
        return 1;
    }

    public function get_coupon_form(Request $request)
    {
        if ($request->coupon_type == "product_base") {
            $admin_id = get_admin()->id;
            $products = filter_products(Product::where('user_id', $admin_id))->get();
            return view('partials.coupons.product_base_coupon', compact('products'));
        } elseif ($request->coupon_type == "cart_base") {
            return view('partials.coupons.cart_base_coupon');
        } elseif ($request->coupon_type == "welcome_base") {
            return view('partials.coupons.welcome_base_coupon');
        }
    }

    public function get_coupon_form_edit(Request $request)
    {
        if ($request->coupon_type == "product_base") {
            $coupon = Coupon::findOrFail($request->id);
            $admin_id = get_admin()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('partials.coupons.product_base_coupon_edit', compact('coupon', 'products'));
        } elseif ($request->coupon_type == "cart_base") {
            $coupon = Coupon::findOrFail($request->id);
            return view('partials.coupons.cart_base_coupon_edit', compact('coupon'));
        } elseif ($request->coupon_type == "welcome_base") {
            $coupon = Coupon::findOrFail($request->id);
            return view('partials.coupons.welcome_base_coupon_edit', compact('coupon'));
        }
    }

    public function updateStatus(Request $request)
    {
        foreach (Coupon::where('type', 'welcome_base')->get() as $welcome_coupon) {
            $welcome_coupon->status = 0;
            $welcome_coupon->save();
        }

        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        if ($coupon->save()) {
            return 1;
        }
        return 0;
    }

    public function filter(Request $request)
    {
        $coupons = Coupon::with('user')->orderBy('id', 'desc');
        $sort_search = null;

        if ($request->coupon_status == "admin_coupons") {
            $coupons = $coupons->whereHas('user', function ($query) {
                $query->where('user_type', 'admin');
            });
        } elseif ($request->coupon_status == 'seller_coupons') {
            $coupons = $coupons->whereHas('user', function ($query) {
                $query->where('user_type', 'seller');
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $coupons = $coupons->where('code', 'like', '%' . $sort_search . '%');
        }

        $coupons = $coupons->paginate(15);
        $view = view(
            'backend.marketing.coupons.table',
            compact('coupons', 'sort_search')
        )->render();
        return response()->json(['html' => $view]);
    }

    public function coupon_product_search(Request $request)
    {
        if (!$request->category && !$request->search_key) {
            return view('backend.marketing.coupons.coupon_product_list', ['products' => collect()]);
        }

        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->where('promotional', 1)
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

        return view('backend.marketing.coupons.coupon_product_list', compact('products'));
    }
}