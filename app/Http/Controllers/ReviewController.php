<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Auth;
use App\Models\ClubPoint;
use App\Models\ClubPointDetail;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_product_reviews'])->only('index');
        $this->middleware(['permission:publish_product_review'])->only('updatePublished');
        $this->middleware(['permission:add_custom_review'])->only('customReviewCreate');
        $this->middleware(['permission:edit_custom_review'])->only('customReviewEdit','customReviewUpdate');
    }
        
    public function index(Request $request)
    {
        $review_tabs = ['All Reviews', 'Admin Reviews', 'Seller Reviews'];
        $sellers = User::whereUserType('seller')->where('email_verified_at', '!=', null)->get();

        return view('backend.product.reviews.index', compact('review_tabs', 'sellers'));
    }

    public function filter(Request $request)
    {
        $products = Product::join('reviews', 'reviews.product_id', '=', 'products.id')->groupBy('products.id');

        if ($request->review_status == 'admin_reviews') {
            $products->where('products.user_id', get_admin()->id);
        } elseif ($request->review_status == 'seller_reviews') {
            $products->where('products.user_id', '!=', get_admin()->id);
        }

        if ($request->seller_id && is_array($request->seller_id) && count($request->seller_id) > 0) {
            $products->whereIn('products.user_id', $request->seller_id);
        }

        if ($request->search != null) {
            $search = $request->search;
            $products->where(function ($q) use ($search) {
                $q->where('products.name', 'like', '%'.$search.'%')
                ->orWhereHas('product_translations', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
            });
        }

        if (in_array($request->rating, ['asc', 'desc'])) {
            $products->orderBy('products.rating', $request->rating);
        } else {
            $products->orderBy('products.created_at', 'desc');
        }

        $products = $products->select('products.*')->paginate(15);

        $view = view('backend.product.reviews.table', compact('products'))->render();
        return response()->json(['html' => $view]);
    }

    public function detailReviews($product_id)
    {
        $review_tabs = ['All Reviews', 'Real Reviews', 'Custom Reviews'];
        $product = Product::findOrFail($product_id);

        if (env('DEMO_MODE') != 'On') {
            $product->reviews()->update(['viewed' => 1]);
        }

        return view('backend.product.reviews.detail_reviews', compact('product', 'review_tabs'));
    }

    public function detailFilter(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $reviewsQuery = $product->reviews();

        if ($request->review_status == 'real_reviews') {
            $reviewsQuery->where('type', 'real');
        } elseif ($request->review_status == 'custom_reviews') {
            $reviewsQuery->where('type', 'custom');
        }

        if ($request->search != null) {
            $search = $request->search;
            $reviewsQuery->where(function ($q) use ($search) {
                $q->where('comment', 'like', '%'.$search.'%')
                ->orWhere('custom_reviewer_name', 'like', '%'.$search.'%')
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                });
            });
        }

        $reviews = $reviewsQuery->paginate(15);

        $view = view('backend.product.reviews.detail_reviews_table', compact('product', 'reviews'))->render();
        return response()->json(['html' => $view]);
    }

    public function create()
    {
        //
    }

    public function customReviewCreate($productId = null){
        if($productId == null ){
            $categories = Category::where('parent_id', 0)
                ->with('childrenCategories')
                ->get();
        }
        else
        {
            $categories = [];
        }
        $product = $productId != null ? Product::whereId($productId)->first() : null ;
        return view('backend.product.reviews.create_custom_review', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->user_type != 'admin'){
            $request->validate([
                'photos.*' => 'nullable|file|mimes:jpeg,jpg,webp,png',
            ]);
        }
        
        $authUser = auth()->user();
        $review             = new Review;
        $review->product_id = $request->product_id;
        if($authUser->user_type == 'customer'){
            $review->user_id = $authUser->id;
        }
        else {
            $review->type = 'custom';
            $review->custom_reviewer_name     = $request->custom_reviewer_name;
            $review->custom_reviewer_image    = $request->custom_reviewer_image;
        }
        $review->rating     = $request->rating;
        $review->comment    = $request->comment;

        if(auth()->user()->user_type != 'admin'){
            $image_ids = [];

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $image) {
                    $id = custom_upload_file($image);
                    if ($id) {
                        $image_ids[] = $id;
                    }
                }
            }
            
            $review->photos = implode(',', $image_ids);
        }
        else {
            $review->photos = implode(',', $request->photos);
        }
        
        
        $review->viewed     = '0';
        if(($request->review_date_type == "custom") && $request->custom_date != null){
            $review->created_at = $request->custom_date;
            $review->created_at_is_custom = 1;
        }
        $review->save();
        if ($authUser->user_type == 'customer') {
            $orderIds = Order::where('user_id', $authUser->id)->pluck('id');
            OrderDetail::whereIn('order_id', $orderIds)
                ->where('product_id', $request->product_id)
                ->update(['reviewed' => 1]);
        }
        
        $product = Product::findOrFail($request->product_id);
        $reviewCount = Review::whereProductId($product->id)->whereStatus(1)->count();
        if ( $reviewCount > 0) {
            $product->rating = Review::whereProductId($product->id)->whereStatus(1)->sum('rating') /  $reviewCount;
        } else {
            $product->rating = 0;
        }
        $product->save();

        if ($product->added_by == 'seller') {
            $seller = $product->user->shop;
            $seller->rating = (($seller->rating * $seller->num_of_reviews) + $review->rating) / ($seller->num_of_reviews + 1);
            $seller->num_of_reviews += 1;
            $seller->save();
        }

        if (addon_is_activated('club_point')) {
            $product = Product::findOrFail($request->product_id);
            $getPoint = false;

            if ($product->added_by == 'admin') {
                $getPoint = true;
            } elseif ($product->added_by == 'seller') {
                $getPoint = get_setting('set_club_point_for_sellers_product_review') == 1;
            }
            if ($getPoint) {
                $order = Order::where('id', $request->order_id)->first();
                $reviewPoint = get_setting('set_point_for_product_review') ?? 0;
                if($order){
                    $orderDetail = $order->orderDetails
                                ->where('product_id', $request->product_id)
                                ->where('reviewed', 1)
                                ->first();
                   
                    if($orderDetail){
                        $orderDetail->earn_point += $reviewPoint;
                        $orderDetail->save();

                        $clubPoint = ClubPoint::create([
                            'user_id' => Auth::id(),
                            'points' => $reviewPoint,
                            'order_id' => $request->order_id
                        ]);
                
                        ClubPointDetail::create([
                            'club_point_id' => $clubPoint->id,
                            'product_id'    => $request->product_id,
                            'point'         => $reviewPoint,
                        ]);
                       
                    }
                }
            }
        }

        flash(translate('Review has been submitted successfully'))->success();
        if($authUser->user_type == 'customer'){
            return back();
        }
        else {
            return redirect()->route('detail-reviews', $product->id.'?review_type=custom');
        }
    }

    public function customReviewEdit($id){
        $review = Review::whereId($id)->first();
        return view('backend.product.reviews.edit_custom_review', compact('review'));
    }

    public function customReviewUpdate(Request $request){
        $review = Review::findOrFail($request->id);
        $review->custom_reviewer_name     = $request->custom_reviewer_name;
        $review->custom_reviewer_image    = $request->custom_reviewer_image;
        
        $review->rating     = $request->rating;
        $review->comment    = $request->comment;
        $review->photos     = implode(',', $request->photos);
        if(isset($request->custom_date) && $request->custom_date != null){
            $review->created_at = $request->custom_date;
            $review->created_at_is_custom = 1;
        }
        $review->save();
        
        $product = $review->product;
        $reviewCount = Review::whereProductId($product->id)->whereStatus(1)->count();
        if ($reviewCount > 0) {
            $product->rating = Review::whereProductId($product->id)->whereStatus(1)->sum('rating') / $reviewCount;
        } else {
            $product->rating = 0;
        }
        $product->save();

        flash(translate('Review has been updated successfully'))->success();
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function updatePublished(Request $request)
    {
        $review = Review::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        $product = Product::findOrFail($review->product->id);
        if (Review::where('product_id', $product->id)->where('status', 1)->count() > 0) {
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating') / Review::where('product_id', $product->id)->where('status', 1)->count();
        } else {
            $product->rating = 0;
        }
        $product->save();

        if ($product->added_by == 'seller') {
            $seller = $product->user->shop;
            if ($review->status) {
                $seller->rating = (($seller->rating * $seller->num_of_reviews) + $review->rating) / ($seller->num_of_reviews + 1);
                $seller->num_of_reviews += 1;
            } else {
                $seller->rating = (($seller->rating * $seller->num_of_reviews) - $review->rating) / max(1, $seller->num_of_reviews - 1);
                $seller->num_of_reviews = max(0, $seller->num_of_reviews - 1);
            }

            $seller->save();
        }

        return 1;
    }

    public function updateBulkPublished(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids) || count($ids) == 0) {
            return 0;
        }

        $reviews = Review::whereIn('id', $ids)->get();

        foreach ($reviews as $review) {
            $newStatus = $review->status == 1 ? 0 : 1; 

            $review->status = $newStatus;
            $review->save();

            $product = Product::findOrFail($review->product_id);
            $publishedCount = Review::where('product_id', $product->id)->where('status', 1)->count();
            $product->rating = $publishedCount > 0
                ? Review::where('product_id', $product->id)->where('status', 1)->sum('rating') / $publishedCount
                : 0;
            $product->save();

            if ($product->added_by == 'seller') {
                $seller = $product->user->shop;

                if ($newStatus == 1) {
                    $seller->rating = (($seller->rating * $seller->num_of_reviews) + $review->rating) / ($seller->num_of_reviews + 1);
                    $seller->num_of_reviews += 1;
                } else {
                    $seller->rating = (($seller->rating * $seller->num_of_reviews) - $review->rating) / max(1, $seller->num_of_reviews - 1);
                    $seller->num_of_reviews = max(0, $seller->num_of_reviews - 1);
                }

                $seller->save();
            }
        }

        return 1;
    }

    public function product_review_modal(Request $request)
    {
        $order_id = $request->order_id;
        $product = Product::where('id', $request->product_id)->first();
        $review = Review::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();
        return view('frontend.user.product_review_modal', compact('product', 'review', 'order_id'));
    }

    public function getProductByCategory(Request $request){
        $products = Product::whereCategoryId($request->category_id)->whereAddedBy('admin')->isApprovedPublished()->whereAuctionProduct(0)->orderBy('created_at', 'desc')->get();
        return view('backend.product.reviews.get_review_product_by_category', compact('products'));
    }

}
