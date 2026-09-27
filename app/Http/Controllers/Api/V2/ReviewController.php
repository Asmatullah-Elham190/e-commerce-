<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ReviewCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;

class ReviewController extends Controller
{
    public function index($id, Request $request)
    {
        // return new ReviewCollection(Review::where('product_id', $id)->where('status', 1)->orderBy('updated_at', 'desc')->paginate(10));

        $query = Review::where('product_id', $id)->where('status', 1); 
        if ($request->has('images') && $request->images != null) {
            $query->whereNotNull('photos')
                  ->where('photos', '!=', '')
                  ->where('photos', '!=', '[]')
                  ->orderByRaw("
                      CASE 
                          WHEN photos IS NOT NULL AND photos != '' AND photos != '[]' THEN 0 
                          ELSE 1 
                      END ASC")
                  ->orderBy('created_at', 'desc');
        } else {
            $sortBy = $request->input('sort_by', 'newest');
            switch ($sortBy) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'highest':
                    $query->orderBy('rating', 'desc')
                          ->orderBy('created_at', 'desc');
                    break;
                case 'lowest':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
        if ($request->has('rating') && $request->rating != '') {
            $ratings = explode(',', $request->rating);
            $ratings = array_filter($ratings, function($rating) {
                return in_array((int)$rating, [1, 2, 3, 4, 5]);
            });
            
            if (!empty($ratings)) {
                $query->whereIn('rating', $ratings);
            }
        }
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $reviews = $query->paginate($perPage, ['*'], 'page', $page);
        return new ReviewCollection($reviews);
    }

    public function submit(Request $request)
    {
        $product = Product::find($request->product_id);
        $user = User::find(auth()->user()->id);

        $reviewable = false;

        foreach ($product->orderDetails as $key => $orderDetail) {
            if($orderDetail->order != null && $orderDetail->order->user_id == auth()->user()->id && $orderDetail->delivery_status == 'delivered' && \App\Models\Review::where('user_id', auth()->user()->id)->where('product_id', $product->id)->first() == null){
                $reviewable = true;
            }
        }

        if(!$reviewable){
            return response()->json([
                'result' => false,
                'message' => translate('You cannot review this product')
            ]);
        }

        $review = new \App\Models\Review;
        $review->product_id = $request->product_id;
        $review->user_id = auth()->user()->id;
        $review->photos = $request->image_ids;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->viewed = 0;
        $review->save();

        $orderIds = Order::where('user_id', auth()->user()->id)->pluck('id');
            OrderDetail::whereIn('order_id', $orderIds)
                ->where('product_id', $request->product_id)
                ->update(['reviewed' => 1]);

        $count = Review::where('product_id', $product->id)->where('status', 1)->count();
        if($count > 0){
            $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating')/$count;
        }
        else {
            $product->rating = 0;
        }
        $product->save();

        if($product->added_by == 'seller'){
            $seller = $product->user->shop;
            $seller->rating = (($seller->rating*$seller->num_of_reviews)+$review->rating)/($seller->num_of_reviews + 1);
            $seller->num_of_reviews += 1;
            $seller->save();
        }

        return response()->json([
            'result' => true,
            'message' => translate('Review  Submitted')
        ]);
    }
}
